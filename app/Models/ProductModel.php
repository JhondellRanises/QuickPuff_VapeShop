<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'category', 'brand', 'image_url', 'price', 'stock_qty', 'is_active', 'flavor', 'flavor_category', 'puffs'];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'name' => 'required|max_length[255]',
        'category' => 'required|max_length[100]',
        'brand' => 'max_length[100]',
        'image_url' => 'permit_empty|max_length[2048]',
        'price' => 'required|numeric|greater_than_equal_to[0]',
        'stock_qty' => 'required|integer|greater_than_equal_to[0]',
        'flavor' => 'permit_empty|max_length[100]',
        'flavor_category' => 'permit_empty|max_length[50]',
        'puffs' => 'permit_empty|integer|greater_than_equal_to[0]',
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
     * Get products grouped by category for better organization.
     */
    public function getProductsGroupedByCategory(string $search = '', string $status = '', string $category = '', string $brand = ''): array
    {
        $builder = $this->builder();

        if ($status === 'active') {
            $builder->where('is_active', 1);
        } elseif ($status === 'inactive') {
            $builder->where('is_active', 0);
        }

        if ($category !== '') {
            $builder->where('category', $category);
        }

        if ($brand !== '') {
            $builder->where('brand', $brand);
        }

        if ($search !== '') {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('category', $search)
                ->orLike('brand', $search)
                ->orLike('flavor', $search)
                ->groupEnd();
        }

        $products = $builder->orderBy('category', 'ASC')
            ->orderBy('brand', 'ASC')
            ->orderBy('name', 'ASC')
            ->orderBy('flavor', 'ASC')
            ->get()
            ->getResultArray();

        // Group products by category
        $groupedProducts = [];
        foreach ($products as $product) {
            $cat = $product['category'] ?: 'Uncategorized';
            $groupedProducts[$cat][] = $product;
        }

        return $groupedProducts;
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

    /**
     * Get distinct categories from products
     */
    public function getDistinctCategories()
    {
        return $this->builder()
            ->select('category')
            ->where('category IS NOT NULL')
            ->where('category !=', '')
            ->distinct()
            ->orderBy('category', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get distinct brands from products
     */
    public function getDistinctBrands()
    {
        return $this->builder()
            ->select('brand')
            ->where('brand IS NOT NULL')
            ->where('brand !=', '')
            ->distinct()
            ->orderBy('brand', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get grouped products for POS display
     * Groups products by name, brand, and category
     */
    public function getGroupedProducts()
    {
        $builder = $this->builder();
        
        $result = $builder->select([
            'name',
            'brand', 
            'category',
            'MIN(price) as min_price',
            'MAX(price) as max_price',
            'SUM(stock_qty) as total_stock',
            'GROUP_CONCAT(DISTINCT CASE WHEN flavor IS NOT NULL AND flavor != "" THEN flavor END) as flavors',
            'GROUP_CONCAT(DISTINCT CASE WHEN puffs IS NOT NULL THEN puffs END ORDER BY puffs) as puff_counts',
            'MIN(image_url) as image_url',
            'COUNT(*) as variant_count'
        ])
        ->where('is_active', 1)
        ->groupBy('name, brand, category')
        ->orderBy('category', 'ASC')
        ->orderBy('name', 'ASC')
        ->get()
        ->getResultArray();
        
        // Process the results
        foreach ($result as &$product) {
            $product['flavors'] = $product['flavors'] ? explode(',', $product['flavors']) : [];
            $product['puff_counts'] = $product['puff_counts'] ? array_filter(explode(',', $product['puff_counts'])) : [];
            $product['min_price'] = (float) $product['min_price'];
            $product['max_price'] = (float) $product['max_price'];
            $product['total_stock'] = (int) $product['total_stock'];
            $product['variant_count'] = (int) $product['variant_count'];
            
            // Format price display
            if ($product['min_price'] == $product['max_price']) {
                $product['price_display'] = '₱' . number_format($product['min_price'], 2);
            } else {
                $product['price_display'] = '₱' . number_format($product['min_price'], 2) . ' - ₱' . number_format($product['max_price'], 2);
            }
        }
        
        return $result;
    }

    /**
     * Get product variants for a specific product group
     */
    public function getProductVariants($name, $brand, $category)
    {
        return $this->where('name', $name)
                    ->where('brand', $brand)
                    ->where('category', $category)
                    ->where('is_active', 1)
                    ->orderBy('flavor', 'ASC')
                    ->orderBy('puffs', 'ASC')
                    ->findAll();
    }

    /**
     * Find specific product variant by selection criteria
     */
    public function findProductVariant($name, $brand, $category, $flavor = null, $puffs = null)
    {
        $builder = $this->where('name', $name)
                        ->where('brand', $brand)
                        ->where('category', $category)
                        ->where('is_active', 1);
        
        if ($flavor !== null) {
            $builder->where('flavor', $flavor);
        }
        
        if ($puffs !== null) {
            $builder->where('puffs', $puffs);
        }
        
        return $builder->first();
    }

    /**
     * Check if category requires flavor selection
     */
    public function categoryRequiresFlavor($category)
    {
        // These categories require flavor selection when products have variants
        $flavorCategories = ['Pods', 'E-liquid', 'Disposable'];
        return in_array($category, $flavorCategories);
    }

    /**
     * Check if category requires puff selection
     */
    public function categoryRequiresPuffs($category)
    {
        $puffCategories = ['Disposable'];
        $optionalPuffCategories = ['Pods']; // Pods now have optional puffs
        return [
            'required' => in_array($category, $puffCategories),
            'optional' => in_array($category, $optionalPuffCategories)
        ];
    }
}
