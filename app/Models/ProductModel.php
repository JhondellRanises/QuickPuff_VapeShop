<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'category', 'brand', 'image_url', 'price', 'stock_qty', 'is_active', 'flavor', 'puffs'];
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

        return $this->groupProductsForStockManagement($products);
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
        $products = $this->builder()
            ->select('id, name, category, brand, price, stock_qty, is_active, flavor, puffs')
            ->orderBy('category', 'ASC')
            ->orderBy('brand', 'ASC')
            ->orderBy('name', 'ASC')
            ->orderBy('flavor', 'ASC')
            ->get()
            ->getResultArray();

        $groupedProducts = $this->groupProductsForStockManagement($products);
        $summary = [
            'total' => 0,
            'active' => 0,
            'low_stock' => 0,
            'out_of_stock' => 0,
        ];

        foreach ($groupedProducts as $categoryGroups) {
            foreach ($categoryGroups as $productGroup) {
                $summary['total']++;

                if (($productGroup['status_state'] ?? 'inactive') !== 'inactive') {
                    $summary['active']++;

                    if (($productGroup['total_stock'] ?? 0) === 0) {
                        $summary['out_of_stock']++;
                    } elseif (($productGroup['total_stock'] ?? 0) <= 10) {
                        $summary['low_stock']++;
                    }
                }
            }
        }

        return $summary;
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
     * Get real available flavor inventory grouped by category and flavor.
     */
    public function getAvailableFlavorInventory()
    {
        $builder = $this->builder();

        $result = $builder->select([
            'category',
            'flavor',
            'SUM(stock_qty) as total_stock',
            'GROUP_CONCAT(DISTINCT CASE WHEN puffs IS NOT NULL THEN puffs END ORDER BY puffs) as puff_counts',
        ])
        ->where('is_active', 1)
        ->where('stock_qty >', 0)
        ->where('flavor IS NOT NULL', null, false)
        ->where('flavor !=', '')
        ->groupBy('category, flavor')
        ->orderBy('category', 'ASC')
        ->orderBy('flavor', 'ASC')
        ->get()
        ->getResultArray();

        $inventoryByCategory = [];

        foreach ($result as $row) {
            $category = trim((string) ($row['category'] ?? ''));
            $flavor = trim((string) ($row['flavor'] ?? ''));

            if ($category === '' || $flavor === '') {
                continue;
            }

            $puffCounts = array_values(array_filter(array_map(
                static fn ($value) => $value === '' ? null : (int) $value,
                explode(',', (string) ($row['puff_counts'] ?? ''))
            )));

            $inventoryByCategory[$category][] = [
                'flavor' => $flavor,
                'total_stock' => (int) ($row['total_stock'] ?? 0),
                'puff_counts' => $puffCounts,
            ];
        }

        return $inventoryByCategory;
    }

    /**
     * Get product variants for a specific product group
     */
    public function getProductVariants($name, $brand, $category)
    {
        return $this->buildProductVariantGroupQuery($name, $brand, $category)
            ->where('is_active', 1)
            ->orderBy('flavor', 'ASC')
            ->orderBy('puffs', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get all product variants in a group for inventory editing.
     */
    public function getProductVariantsForEditing($name, $brand, $category)
    {
        return $this->buildProductVariantGroupQuery($name, $brand, $category)
            ->orderBy('flavor', 'ASC')
            ->orderBy('puffs', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Find specific product variant by selection criteria
     */
    public function findProductVariant($name, $brand, $category, $flavor = null, $puffs = null)
    {
        $builder = $this->buildProductVariantGroupQuery($name, $brand, $category)
            ->where('is_active', 1);
        
        if ($flavor !== null) {
            $builder->where('flavor', $flavor);
        }
        
        if ($puffs !== null) {
            $builder->where('puffs', $puffs);
        }
        
        return $builder->get()->getRowArray();
    }

    private function buildProductVariantGroupQuery($name, $brand, $category)
    {
        $builder = $this->builder()
            ->where('name', $name)
            ->where('category', $category);

        $normalizedBrand = trim((string) ($brand ?? ''));
        if ($normalizedBrand === '') {
            $builder->groupStart()
                ->where('brand IS NULL', null, false)
                ->orWhere('brand', '')
                ->groupEnd();
        } else {
            $builder->where('brand', $normalizedBrand);
        }

        return $builder;
    }

    private function groupProductsForStockManagement(array $products): array
    {
        $groupedProducts = [];

        foreach ($products as $product) {
            $category = trim((string) ($product['category'] ?? ''));
            $category = $category === '' ? 'Uncategorized' : $category;
            $name = trim((string) ($product['name'] ?? ''));
            $brand = trim((string) ($product['brand'] ?? ''));
            $groupKey = strtolower($category) . '|' . strtolower($name) . '|' . strtolower($brand);

            if (!isset($groupedProducts[$category][$groupKey])) {
                $price = (float) ($product['price'] ?? 0);

                $groupedProducts[$category][$groupKey] = [
                    'id' => (int) ($product['id'] ?? 0),
                    'name' => $name,
                    'category' => $category,
                    'brand' => $brand === '' ? null : $brand,
                    'min_price' => $price,
                    'max_price' => $price,
                    'price_display' => '',
                    'total_stock' => 0,
                    'variant_count' => 0,
                    'active_variant_count' => 0,
                    'inactive_variant_count' => 0,
                    'status_state' => 'inactive',
                    'flavors' => [],
                    'variant_options' => [],
                    'puff_counts' => [],
                    'puff_prices' => [],
                    '_flavor_map' => [],
                    '_variant_option_map' => [],
                    '_puff_map' => [],
                    '_puff_price_map' => [],
                ];
            }

            $group = &$groupedProducts[$category][$groupKey];
            $group['id'] = min($group['id'], (int) ($product['id'] ?? 0));
            $group['min_price'] = min($group['min_price'], (float) ($product['price'] ?? 0));
            $group['max_price'] = max($group['max_price'], (float) ($product['price'] ?? 0));
            $group['total_stock'] += (int) ($product['stock_qty'] ?? 0);
            $group['variant_count']++;

            if ((int) ($product['is_active'] ?? 0) === 1) {
                $group['active_variant_count']++;
            } else {
                $group['inactive_variant_count']++;
            }

            $puffs = (int) ($product['puffs'] ?? 0);
            if ($puffs > 0) {
                $group['_puff_map'][$puffs] = $puffs;
                if (!isset($group['_puff_price_map'][$puffs])) {
                    $group['_puff_price_map'][$puffs] = [
                        'puffs' => $puffs,
                        'min_price' => (float) ($product['price'] ?? 0),
                        'max_price' => (float) ($product['price'] ?? 0),
                    ];
                } else {
                    $group['_puff_price_map'][$puffs]['min_price'] = min(
                        $group['_puff_price_map'][$puffs]['min_price'],
                        (float) ($product['price'] ?? 0)
                    );
                    $group['_puff_price_map'][$puffs]['max_price'] = max(
                        $group['_puff_price_map'][$puffs]['max_price'],
                        (float) ($product['price'] ?? 0)
                    );
                }
            }

            $flavor = trim((string) ($product['flavor'] ?? ''));
            if ($flavor !== '') {
                $flavorKey = strtolower($flavor);
                if (!isset($group['_flavor_map'][$flavorKey])) {
                    $group['_flavor_map'][$flavorKey] = [
                        'flavor' => $flavor,
                        'stock_qty' => 0,
                        'variant_count' => 0,
                        'puff_counts' => [],
                    ];
                }

                $group['_flavor_map'][$flavorKey]['stock_qty'] += (int) ($product['stock_qty'] ?? 0);
                $group['_flavor_map'][$flavorKey]['variant_count']++;
                if ($puffs > 0) {
                    $group['_flavor_map'][$flavorKey]['puff_counts'][$puffs] = $puffs;
                }

                $variantOptionKey = $flavorKey . '|' . ($puffs > 0 ? (string) $puffs : 'none');
                if (!isset($group['_variant_option_map'][$variantOptionKey])) {
                    $group['_variant_option_map'][$variantOptionKey] = [
                        'flavor' => $flavor,
                        'puffs' => $puffs > 0 ? $puffs : null,
                        'stock_qty' => 0,
                        'variant_count' => 0,
                    ];
                }

                $group['_variant_option_map'][$variantOptionKey]['stock_qty'] += (int) ($product['stock_qty'] ?? 0);
                $group['_variant_option_map'][$variantOptionKey]['variant_count']++;
            }

            unset($group);
        }

        foreach ($groupedProducts as $category => $categoryGroups) {
            foreach ($categoryGroups as $groupKey => $group) {
                $group['puff_counts'] = array_values($group['_puff_map']);
                sort($group['puff_counts'], SORT_NUMERIC);
                $group['puff_prices'] = array_values($group['_puff_price_map']);
                usort($group['puff_prices'], static function (array $left, array $right): int {
                    return ((int) ($left['puffs'] ?? 0)) <=> ((int) ($right['puffs'] ?? 0));
                });
                $group['puff_prices'] = array_map(static function (array $puffPrice): array {
                    $minPrice = (float) ($puffPrice['min_price'] ?? 0);
                    $maxPrice = (float) ($puffPrice['max_price'] ?? 0);

                    if ($minPrice === $maxPrice) {
                        $puffPrice['price_display'] = '&#8369;' . number_format($minPrice, 2);
                    } else {
                        $puffPrice['price_display'] = '&#8369;' . number_format($minPrice, 2) . ' - &#8369;' . number_format($maxPrice, 2);
                    }

                    return $puffPrice;
                }, $group['puff_prices']);

                $group['flavors'] = array_values(array_map(
                    static function (array $flavorData): array {
                        $flavorData['puff_counts'] = array_values($flavorData['puff_counts']);
                        sort($flavorData['puff_counts'], SORT_NUMERIC);
                        return $flavorData;
                    },
                    $group['_flavor_map']
                ));

                usort($group['flavors'], static function (array $left, array $right): int {
                    return strcasecmp((string) ($left['flavor'] ?? ''), (string) ($right['flavor'] ?? ''));
                });

                $group['variant_options'] = array_values($group['_variant_option_map']);
                usort($group['variant_options'], static function (array $left, array $right): int {
                    $flavorComparison = strcasecmp((string) ($left['flavor'] ?? ''), (string) ($right['flavor'] ?? ''));
                    if ($flavorComparison !== 0) {
                        return $flavorComparison;
                    }

                    $leftPuffs = $left['puffs'] ?? null;
                    $rightPuffs = $right['puffs'] ?? null;

                    if ($leftPuffs === $rightPuffs) {
                        return 0;
                    }

                    if ($leftPuffs === null) {
                        return -1;
                    }

                    if ($rightPuffs === null) {
                        return 1;
                    }

                    return (int) $leftPuffs <=> (int) $rightPuffs;
                });

                if ($group['active_variant_count'] > 0 && $group['inactive_variant_count'] === 0) {
                    $group['status_state'] = 'active';
                } elseif ($group['active_variant_count'] === 0) {
                    $group['status_state'] = 'inactive';
                } else {
                    $group['status_state'] = 'mixed';
                }

                if ($group['min_price'] === $group['max_price']) {
                    $group['price_display'] = '&#8369;' . number_format($group['min_price'], 2);
                } else {
                    $group['price_display'] = '&#8369;' . number_format($group['min_price'], 2) . ' - &#8369;' . number_format($group['max_price'], 2);
                }

                unset($group['_flavor_map'], $group['_variant_option_map'], $group['_puff_map'], $group['_puff_price_map']);
                $groupedProducts[$category][$groupKey] = $group;
            }

            $groupedProducts[$category] = array_values($groupedProducts[$category]);
        }

        uksort($groupedProducts, fn (string $left, string $right): int => $this->compareStockManagementCategories($left, $right));

        return $groupedProducts;
    }

    private function compareStockManagementCategories(string $left, string $right): int
    {
        $categoryOrder = [
            'device' => 10,
            'pods' => 20,
            'disposable' => 30,
            'e-liquid' => 40,
            'pods kit' => 50,
            'accessory' => 60,
            'uncategorized' => 999,
        ];

        $leftKey = strtolower(trim($left));
        $rightKey = strtolower(trim($right));
        $leftWeight = $categoryOrder[$leftKey] ?? 500;
        $rightWeight = $categoryOrder[$rightKey] ?? 500;

        if ($leftWeight !== $rightWeight) {
            return $leftWeight <=> $rightWeight;
        }

        return strcasecmp($left, $right);
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
