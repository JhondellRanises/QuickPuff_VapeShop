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
        $category = trim((string) $this->request->getGet('category_filter'));
        $brand = trim((string) $this->request->getGet('brand_filter'));
        
        $allowedStatuses = ['', 'active', 'inactive'];

        if (!in_array($status, $allowedStatuses, true)) {
            $status = '';
        }

        // Use the new grouped method with additional filters
        $groupedProducts = $this->productModel->getProductsGroupedByCategory($search, $status, $category, $brand);
        $stats = $this->productModel->getStockSummary();
        $allProductsForImageMap = $this->productModel->findAll();
        $this->syncImageSeedMapFromProducts($allProductsForImageMap);
        
        // Get unique categories and brands
        $categories = $this->productModel->getDistinctCategories();
        $brands = $this->productModel->getDistinctBrands();

        // Count total products for display
        $totalProducts = 0;
        foreach ($groupedProducts as $categoryProducts) {
            $totalProducts += count($categoryProducts);
        }

        $data = [
            'title' => 'Stock Management - Quick Puff Vape Shop',
            'user' => $this->getCurrentUser(),
            'groupedProducts' => $groupedProducts,
            'totalProducts' => $totalProducts,
            'filters' => [
                'q' => $search,
                'status' => $status,
                'category_filter' => $category,
                'brand_filter' => $brand,
            ],
            'stats' => $stats,
            'categories' => $categories,
            'brands' => $brands,
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
        // Check if this is an AJAX request from modal
        $isAjax = $this->request->isAJAX();
        
        log_message('info', 'Product store method called. AJAX: ' . ($isAjax ? 'Yes' : 'No'));
        
        $data = $this->getProductFormData();
        log_message('info', 'Form data: ' . json_encode($data));

        if (!$this->validate($this->getValidationRules())) {
            $validationErrors = $this->validator->getErrors();
            log_message('error', 'Validation failed: ' . json_encode($validationErrors));
            
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->buildValidationMessage()
                ]);
            } else {
                session()->setFlashdata('error', $this->buildValidationMessage());
                return redirect()->to('/products/create')->withInput();
            }
        }

        try {
            $uploadedImagePath = $this->uploadProductImage();
            if ($uploadedImagePath !== null) {
                $data['image_url'] = $uploadedImagePath;
            }

            log_message('info', 'Attempting to insert product with data: ' . json_encode($data));

            if ($this->productModel->insert($data)) {
                $productId = $this->productModel->getInsertID();
                log_message('info', 'Product inserted successfully with ID: ' . $productId);
                
                $this->syncImageSeedMap(
                    null,
                    (string) ($data['name'] ?? ''),
                    $data['image_url'] ?? null
                );
                
                $productName = $data['name'] ?? 'Product';
                $successMessage = "✅ {$productName} has been successfully added to inventory!";
                
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => $successMessage,
                        'redirect' => site_url('/products')
                    ]);
                } else {
                    session()->setFlashdata('success', $successMessage);
                    return redirect()->to('/products');
                }
            } else {
                log_message('error', 'Product insert failed. Database error: ' . json_encode($this->productModel->errors()));
                $errorMessage = 'Failed to add product. Please try again.';
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $errorMessage
                    ]);
                } else {
                    session()->setFlashdata('error', $errorMessage);
                    return redirect()->to('/products/create')->withInput();
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Product store error: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            $errorMessage = 'An unexpected error occurred while adding the product.';
            
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $errorMessage . ' Error: ' . $e->getMessage()
                ]);
            } else {
                session()->setFlashdata('error', $errorMessage);
                return redirect()->to('/products/create')->withInput();
            }
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

        // Get unique categories and brands for dropdowns
        $categories = $this->productModel->getDistinctCategories();
        $brands = $this->productModel->getDistinctBrands();

        $data = [
            'title' => 'Edit Product - Quick Puff Vape Shop',
            'user' => $this->getCurrentUser(),
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
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
     * Delete product permanently.
     */
    public function delete($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            session()->setFlashdata('error', 'Product not found.');
            return redirect()->to('/products');
        }

        try {
            if ($this->productModel->delete($id)) {
                session()->setFlashdata('success', 'Product deleted successfully.');
            } else {
                session()->setFlashdata('error', 'Failed to delete product.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Product delete error: ' . $e->getMessage());
            session()->setFlashdata('error', 'An unexpected error occurred while deleting the product.');
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
        $category = trim((string) $this->request->getPost('category'));
        $brand = trim((string) $this->request->getPost('brand'));
        $flavorCategory = trim((string) $this->request->getPost('flavor_category'));
        $flavor = trim((string) $this->request->getPost('flavor'));
        $customFlavor = trim((string) $this->request->getPost('custom_flavor'));
        $puffs = trim((string) $this->request->getPost('puffs'));

        // Use custom flavor if provided, otherwise use selected flavor
        $finalFlavor = $customFlavor !== '' ? $customFlavor : $flavor;

        return [
            'name' => trim((string) $this->request->getPost('name')),
            'category' => $category,
            'brand' => $brand === '' ? null : $brand,
            'price' => (float) $this->request->getPost('price'),
            'stock_qty' => (int) $this->request->getPost('stock_qty'),
            'is_active' => $this->request->getPost('is_active') === '0' ? 0 : 1,
            'flavor' => $finalFlavor === '' ? null : $finalFlavor,
            'flavor_category' => $flavorCategory === '' ? null : $flavorCategory,
            'puffs' => $puffs === '' ? null : (int) $puffs,
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
            'flavor' => 'permit_empty|max_length[100]',
            'flavor_category' => 'permit_empty|max_length[50]',
            'puffs' => 'permit_empty|integer|greater_than_equal_to[0]',
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
