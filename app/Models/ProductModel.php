<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'category', 'brand', 'price', 'stock_qty', 'is_active'];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'name' => 'required|max_length[255]',
        'category' => 'required|max_length[100]',
        'brand' => 'max_length[100]',
        'price' => 'required|numeric|greater_than_equal_to[0]',
        'stock_qty' => 'required|integer|greater_than_equal_to[0]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Product name is required',
        ],
        'category' => [
            'required' => 'Category is required',
        ],
        'price' => [
            'required' => 'Price is required',
            'numeric' => 'Price must be a number',
            'greater_than_equal_to' => 'Price cannot be negative',
        ],
        'stock_qty' => [
            'required' => 'Stock quantity is required',
            'integer' => 'Stock quantity must be a whole number',
            'greater_than_equal_to' => 'Stock quantity cannot be negative',
        ],
    ];

    /**
     * Get all active products for POS
     */
    public function getActiveProducts()
    {
        return $this->where('is_active', 1)
                    ->orderBy('category', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get products by category
     */
    public function getProductsByCategory($category)
    {
        return $this->where('is_active', 1)
                    ->where('category', $category)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Search products by name
     */
    public function searchProducts($searchTerm)
    {
        return $this->where('is_active', 1)
                    ->like('name', $searchTerm)
                    ->orLike('brand', $searchTerm)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get single product by ID
     */
    public function getProductById($id)
    {
        return $this->where('id', $id)
                    ->where('is_active', 1)
                    ->first();
    }

    /**
     * Check if product has sufficient stock
     */
    public function checkStock($productId, $requiredQty)
    {
        $product = $this->find($productId);
        if (!$product) {
            return false;
        }
        
        return $product['stock_qty'] >= $requiredQty;
    }

    /**
     * Update stock quantity
     */
    public function updateStock($productId, $quantity)
    {
        return $this->update($productId, ['stock_qty' => $quantity]);
    }

    /**
     * Decrease stock (for sales)
     */
    public function decreaseStock($productId, $quantity)
    {
        $product = $this->find($productId);
        if ($product && $product['stock_qty'] >= $quantity) {
            $newStock = $product['stock_qty'] - $quantity;
            return $this->update($productId, ['stock_qty' => $newStock]);
        }
        return false;
    }

    /**
     * Get low stock products
     */
    public function getLowStockProducts($threshold = 10)
    {
        return $this->where('is_active', 1)
                    ->where('stock_qty <=', $threshold)
                    ->orderBy('stock_qty', 'ASC')
                    ->findAll();
    }

    /**
     * Get product categories
     */
    public function getCategories()
    {
        $result = $this->select('category')
                      ->where('is_active', 1)
                      ->distinct()
                      ->findAll();
        
        return array_column($result, 'category');
    }

    /**
     * Get products for stock management list with optional filters.
     */
    public function getStockManagementList(string $search = '', string $status = ''): array
    {
        $builder = $this->builder();

        if ($status === 'active') {
            $builder->where('is_active', 1);
        } elseif ($status === 'inactive') {
            $builder->where('is_active', 0);
        }

        if ($search !== '') {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('category', $search)
                ->orLike('brand', $search)
                ->groupEnd();
        }

        return $builder->orderBy('is_active', 'DESC')
            ->orderBy('stock_qty', 'ASC')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get stock summary counters for dashboard cards.
     */
    public function getStockSummary(): array
    {
        $row = $this->builder()
            ->select(
                'COUNT(*) AS total_products, ' .
                'SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) AS active_products, ' .
                'SUM(CASE WHEN is_active = 1 AND stock_qty BETWEEN 1 AND 10 THEN 1 ELSE 0 END) AS low_stock_products, ' .
                'SUM(CASE WHEN is_active = 1 AND stock_qty = 0 THEN 1 ELSE 0 END) AS out_of_stock_products',
                false
            )
            ->get()
            ->getRowArray();

        return [
            'total' => (int) ($row['total_products'] ?? 0),
            'active' => (int) ($row['active_products'] ?? 0),
            'low_stock' => (int) ($row['low_stock_products'] ?? 0),
            'out_of_stock' => (int) ($row['out_of_stock_products'] ?? 0),
        ];
    }
}
