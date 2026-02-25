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
            if ($this->productModel->insert($data)) {
                session()->setFlashdata('success', 'Product created successfully.');
                return redirect()->to('/products');
            }

            session()->setFlashdata('error', 'Failed to create product. Please try again.');
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
            if ($this->productModel->update($id, $data)) {
                session()->setFlashdata('success', 'Product updated successfully.');
                return redirect()->to('/products');
            }

            session()->setFlashdata('error', 'Failed to update product. Please try again.');
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
