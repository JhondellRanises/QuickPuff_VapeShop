<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Products extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    /**
     * Display product stock list with filters.
     */
    public function index()
    {
        $search = trim((string) $this->request->getGet('q'));
        $status = strtolower(trim((string) $this->request->getGet('status')));
        $allowedStatuses = ['', 'active', 'inactive'];

        if (!in_array($status, $allowedStatuses, true)) {
            $status = '';
        }

        $products = $this->productModel->getStockManagementList($search, $status);
        $stats = $this->productModel->getStockSummary();
        $allProductsForImageMap = $this->productModel->findAll();
        $this->syncImageSeedMapFromProducts($allProductsForImageMap);

        $data = [
            'title' => 'Stock Management - Quick Puff Vape Shop',
            'user' => $this->getCurrentUser(),
            'products' => $products,
            'filtered_count' => count($products),
            'filters' => [
                'q' => $search,
                'status' => $status,
            ],
            'stats' => $stats,
        ];

        return view('products/index', $data);
    }

    /**
     * Display create product form.
     */
    public function create()
    {
        $data = [
            'title' => 'Add Product - Quick Puff Vape Shop',
            'user' => $this->getCurrentUser(),
        ];

        return view('products/create', $data);
    }

    /**
     * Store new product.
     */
    public function store()
    {
        $data = $this->getProductFormData();

        if (!$this->validate($this->getValidationRules())) {
            session()->setFlashdata('error', $this->buildValidationMessage());
            return redirect()->to('/products/create')->withInput();
        }

        try {
            $uploadedImagePath = $this->uploadProductImage();
            if ($uploadedImagePath !== null) {
                $data['image_url'] = $uploadedImagePath;
            }

            if ($this->productModel->insert($data)) {
                $this->syncImageSeedMap(
                    null,
                    (string) ($data['name'] ?? ''),
                    $data['image_url'] ?? null
                );

                session()->setFlashdata('success', 'Product created successfully.');
                return redirect()->to('/products');
            }

            session()->setFlashdata('error', 'Failed to create product. Please try again.');
            return redirect()->to('/products/create')->withInput();
        } catch (\RuntimeException $e) {
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->to('/products/create')->withInput();
        } catch (\Exception $e) {
            log_message('error', 'Product create error: ' . $e->getMessage());
            session()->setFlashdata('error', 'An unexpected error occurred while creating the product.');
            return redirect()->to('/products/create')->withInput();
        }
    }

    /**
     * Display edit product form.
     */
    public function edit($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            session()->setFlashdata('error', 'Product not found.');
            return redirect()->to('/products');
        }

        $data = [
            'title' => 'Edit Product - Quick Puff Vape Shop',
            'user' => $this->getCurrentUser(),
            'product' => $product,
        ];

        return view('products/edit', $data);
    }

    /**
     * Update product.
     */
    public function update($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            session()->setFlashdata('error', 'Product not found.');
            return redirect()->to('/products');
        }

        $data = $this->getProductFormData();

        if (!$this->validate($this->getValidationRules())) {
            session()->setFlashdata('error', $this->buildValidationMessage());
            return redirect()->to('/products/edit/' . $id)->withInput();
        }

        try {
            $uploadedImagePath = $this->uploadProductImage($product['image_url'] ?? null);
            if ($uploadedImagePath !== null) {
                $data['image_url'] = $uploadedImagePath;
            }

            if ($this->productModel->update($id, $data)) {
                $finalImageUrl = array_key_exists('image_url', $data)
                    ? $data['image_url']
                    : ($product['image_url'] ?? null);

                $this->syncImageSeedMap(
                    (string) ($product['name'] ?? ''),
                    (string) ($data['name'] ?? ''),
                    $finalImageUrl
                );

                session()->setFlashdata('success', 'Product updated successfully.');
                return redirect()->to('/products');
            }

            session()->setFlashdata('error', 'Failed to update product. Please try again.');
            return redirect()->to('/products/edit/' . $id)->withInput();
        } catch (\RuntimeException $e) {
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->to('/products/edit/' . $id)->withInput();
        } catch (\Exception $e) {
            log_message('error', 'Product update error: ' . $e->getMessage());
            session()->setFlashdata('error', 'An unexpected error occurred while updating the product.');
            return redirect()->to('/products/edit/' . $id)->withInput();
        }
    }

    /**
     * Soft delete product by deactivating it.
     */
    public function delete($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            session()->setFlashdata('error', 'Product not found.');
            return redirect()->to('/products');
        }

        try {
            if ($this->productModel->update($id, ['is_active' => 0])) {
                session()->setFlashdata('success', 'Product deactivated successfully.');
            } else {
                session()->setFlashdata('error', 'Failed to deactivate product.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Product delete error: ' . $e->getMessage());
            session()->setFlashdata('error', 'An unexpected error occurred while deactivating the product.');
        }

        return redirect()->to('/products');
    }

    /**
     * Reactivate product.
     */
    public function activate($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            session()->setFlashdata('error', 'Product not found.');
            return redirect()->to('/products');
        }

        try {
            if ($this->productModel->update($id, ['is_active' => 1])) {
                session()->setFlashdata('success', 'Product activated successfully.');
            } else {
                session()->setFlashdata('error', 'Failed to activate product.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Product activate error: ' . $e->getMessage());
            session()->setFlashdata('error', 'An unexpected error occurred while activating the product.');
        }

        return redirect()->to('/products');
    }

    private function getProductFormData(): array
    {
        $brand = trim((string) $this->request->getPost('brand'));

        return [
            'name' => trim((string) $this->request->getPost('name')),
            'category' => trim((string) $this->request->getPost('category')),
            'brand' => $brand === '' ? null : $brand,
            'price' => (float) $this->request->getPost('price'),
            'stock_qty' => (int) $this->request->getPost('stock_qty'),
            'is_active' => $this->request->getPost('is_active') === '0' ? 0 : 1,
        ];
    }

    private function getValidationRules(): array
    {
        return [
            'name' => 'required|min_length[2]|max_length[255]',
            'category' => 'required|min_length[2]|max_length[100]',
            'brand' => 'permit_empty|max_length[100]',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'stock_qty' => 'required|integer|greater_than_equal_to[0]',
            'is_active' => 'required|in_list[0,1]',
        ];
    }

    private function uploadProductImage(?string $existingImageUrl = null): ?string
    {
        $file = $this->request->getFile('image_file');

        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (!$file->isValid()) {
            throw new \RuntimeException('Image upload failed. Please try again.');
        }

        if ($file->getSizeByUnit('mb') > 4) {
            throw new \RuntimeException('Image upload failed. Maximum file size is 4MB.');
        }

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $mimeType = (string) $file->getMimeType();
        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            throw new \RuntimeException('Image upload failed. Allowed types: JPG, PNG, WEBP, GIF.');
        }

        $uploadDir = rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'products';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
            throw new \RuntimeException('Image upload failed. Unable to prepare upload folder.');
        }

        $newFileName = $file->getRandomName();
        $file->move($uploadDir, $newFileName);

        $newRelativePath = 'uploads/products/' . $newFileName;
        $this->deleteOldUploadedImage($existingImageUrl);

        return $newRelativePath;
    }

    private function deleteOldUploadedImage(?string $existingImageUrl): void
    {
        if (empty($existingImageUrl)) {
            return;
        }

        $normalizedPath = ltrim(str_replace('\\', '/', $existingImageUrl), '/');
        if (strpos($normalizedPath, 'uploads/products/') !== 0) {
            return;
        }

        $oldFilePath = rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $normalizedPath);
        if (is_file($oldFilePath)) {
            @unlink($oldFilePath);
        }
    }

    private function syncImageSeedMap(?string $oldName, ?string $newName, $imageUrl): void
    {
        $normalizedOldName = trim((string) $oldName);
        $normalizedNewName = trim((string) $newName);
        $normalizedImageUrl = trim((string) ($imageUrl ?? ''));

        $mapPath = WRITEPATH . 'product_image_seed_map.json';
        $map = [];

        if (is_file($mapPath)) {
            $raw = @file_get_contents($mapPath);
            $decoded = json_decode((string) $raw, true);
            if (is_array($decoded)) {
                $map = $decoded;
            }
        }

        if ($normalizedOldName !== '' && $normalizedOldName !== $normalizedNewName) {
            unset($map[$normalizedOldName]);
        }

        if ($normalizedNewName !== '') {
            if ($normalizedImageUrl === '') {
                unset($map[$normalizedNewName]);
            } else {
                $map[$normalizedNewName] = $normalizedImageUrl;
            }
        }

        @file_put_contents(
            $mapPath,
            json_encode($map, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    private function syncImageSeedMapFromProducts(array $products): void
    {
        if (empty($products)) {
            return;
        }

        $mapPath = WRITEPATH . 'product_image_seed_map.json';
        $map = [];

        if (is_file($mapPath)) {
            $raw = @file_get_contents($mapPath);
            $decoded = json_decode((string) $raw, true);
            if (is_array($decoded)) {
                $map = $decoded;
            }
        }

        foreach ($products as $product) {
            $name = trim((string) ($product['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $imageUrl = trim((string) ($product['image_url'] ?? ''));
            if ($imageUrl === '') {
                unset($map[$name]);
            } else {
                $map[$name] = $imageUrl;
            }
        }

        @file_put_contents(
            $mapPath,
            json_encode($map, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    private function buildValidationMessage(): string
    {
        $errors = $this->validator->getErrors();
        $message = 'Please fix the following errors:<ul>';

        foreach ($errors as $error) {
            $message .= '<li>' . esc($error) . '</li>';
        }

        $message .= '</ul>';

        return $message;
    }

    private function getCurrentUser(): array
    {
        return [
            'id' => session()->get('user_id'),
            'username' => session()->get('username'),
            'full_name' => session()->get('full_name'),
            'role' => session()->get('role'),
        ];
    }
}
