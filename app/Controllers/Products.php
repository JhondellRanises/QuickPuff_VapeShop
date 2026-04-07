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
            'categories' => $this->productModel->getDistinctCategories(),
            'brands' => $this->productModel->getDistinctBrands(),
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
        $flavorInventoryRows = $this->getFlavorInventoryRowsFromRequest();
        $usesFlavorInventory = $this->shouldUseFlavorInventoryEditor(
            (string) ($data['category'] ?? ''),
            $flavorInventoryRows
        );
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

        $inventoryErrors = $this->validateFlavorInventoryRows(
            (string) ($data['category'] ?? ''),
            $usesFlavorInventory,
            $flavorInventoryRows
        );
        if ($inventoryErrors !== []) {
            $errorMessage = $this->buildErrorListMessage($inventoryErrors);

            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $errorMessage
                ]);
            }

            session()->setFlashdata('error', $errorMessage);
            return redirect()->to('/products/create')->withInput();
        }

        try {
            $uploadedImagePath = $this->uploadProductImage();
            if ($uploadedImagePath !== null) {
                $data['image_url'] = $uploadedImagePath;
            }

            if ($usesFlavorInventory) {
                $this->createFlavorInventoryGroup($data, $flavorInventoryRows);

                $this->syncImageSeedMap(
                    null,
                    (string) ($data['name'] ?? ''),
                    $data['image_url'] ?? null
                );

                $productName = $data['name'] ?? 'Product';
                $successMessage = "Flavor inventory for {$productName} has been successfully added.";

                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => $successMessage,
                        'redirect' => site_url('/products')
                    ]);
                }

                session()->setFlashdata('success', $successMessage);
                return redirect()->to('/products');
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
        $isFlavorInventoryCategory = $this->isFlavorInventoryCategory((string) ($product['category'] ?? ''));
        $flavorVariants = $isFlavorInventoryCategory
            ? $this->productModel->getProductVariantsForEditing(
                (string) ($product['name'] ?? ''),
                $product['brand'] ?? null,
                (string) ($product['category'] ?? '')
            )
            : [];

        $data = [
            'title' => 'Edit Product - Quick Puff Vape Shop',
            'user' => $this->getCurrentUser(),
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
            'isFlavorInventoryCategory' => $isFlavorInventoryCategory,
            'flavorVariants' => $flavorVariants,
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

        $existingImageUrl = normalize_product_image_path($product['image_url'] ?? null, true);

        $data = $this->getProductFormData();
        $flavorInventoryRows = $this->getFlavorInventoryRowsFromRequest();
        $usesFlavorInventory = $this->shouldUseFlavorInventoryEditor(
            (string) ($data['category'] ?? ''),
            $flavorInventoryRows
        );

        if (!$this->validate($this->getValidationRules())) {
            session()->setFlashdata('error', $this->buildValidationMessage());
            return redirect()->to('/products/edit/' . $id)->withInput();
        }

        $inventoryErrors = $this->validateFlavorInventoryRows(
            (string) ($data['category'] ?? ''),
            $usesFlavorInventory,
            $flavorInventoryRows,
            $this->getExistingFlavorInventoryPuffChoices($product)
        );
        if ($inventoryErrors !== []) {
            session()->setFlashdata('error', $this->buildErrorListMessage($inventoryErrors));
            return redirect()->to('/products/edit/' . $id)->withInput();
        }

        try {
            $uploadedImagePath = $this->uploadProductImage($product['image_url'] ?? null);
            if ($uploadedImagePath !== null) {
                $data['image_url'] = $uploadedImagePath;
            } elseif ($existingImageUrl !== null && $existingImageUrl !== ($product['image_url'] ?? null)) {
                $data['image_url'] = $existingImageUrl;
            }

            if ($usesFlavorInventory) {
                $this->updateFlavorInventoryGroup($product, $data, $flavorInventoryRows);

                $finalImageUrl = array_key_exists('image_url', $data)
                    ? $data['image_url']
                    : ($product['image_url'] ?? null);

                $this->syncImageSeedMap(
                    (string) ($product['name'] ?? ''),
                    (string) ($data['name'] ?? ''),
                    $finalImageUrl
                );

                session()->setFlashdata('success', 'Product flavors updated successfully.');
                return redirect()->to('/products');
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
            $groupMembers = $this->productModel->getProductVariantsForEditing(
                (string) ($product['name'] ?? ''),
                $product['brand'] ?? null,
                (string) ($product['category'] ?? '')
            );
            $groupMemberIds = array_values(array_unique(array_map('intval', array_column($groupMembers, 'id'))));

            if ($groupMemberIds === []) {
                $groupMemberIds = [(int) $id];
            }

            $db = \Config\Database::connect();
            $db->transBegin();

            foreach ($groupMemberIds as $groupMemberId) {
                if (!$this->productModel->delete($groupMemberId)) {
                    throw new \RuntimeException('Failed to delete product group.');
                }
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Failed to delete product group.');
            }

            $db->transCommit();
            session()->setFlashdata(
                'success',
                count($groupMemberIds) > 1 ? 'Product group deleted successfully.' : 'Product deleted successfully.'
            );
        } catch (\RuntimeException $e) {
            if (isset($db) && $db->transStatus() !== false) {
                $db->transRollback();
            }
            session()->setFlashdata('error', $e->getMessage());
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
            $groupMembers = $this->productModel->getProductVariantsForEditing(
                (string) ($product['name'] ?? ''),
                $product['brand'] ?? null,
                (string) ($product['category'] ?? '')
            );
            $groupMemberIds = array_values(array_unique(array_map('intval', array_column($groupMembers, 'id'))));

            if ($groupMemberIds === []) {
                $groupMemberIds = [(int) $id];
            }

            $db = \Config\Database::connect();
            $db->transBegin();

            foreach ($groupMemberIds as $groupMemberId) {
                if (!$this->productModel->update($groupMemberId, ['is_active' => 1])) {
                    throw new \RuntimeException('Failed to activate product group.');
                }
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Failed to activate product group.');
            }

            $db->transCommit();
            session()->setFlashdata(
                'success',
                count($groupMemberIds) > 1 ? 'Product group activated successfully.' : 'Product activated successfully.'
            );
        } catch (\RuntimeException $e) {
            if (isset($db) && $db->transStatus() !== false) {
                $db->transRollback();
            }
            session()->setFlashdata('error', $e->getMessage());
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
        $flavor = trim((string) $this->request->getPost('flavor'));
        $puffs = trim((string) $this->request->getPost('puffs'));

        return [
            'name' => trim((string) $this->request->getPost('name')),
            'category' => $category,
            'brand' => $brand === '' ? null : $brand,
            'price' => (float) $this->request->getPost('price'),
            'stock_qty' => (int) $this->request->getPost('stock_qty'),
            'is_active' => $this->request->getPost('is_active') === '0' ? 0 : 1,
            'flavor' => $flavor === '' ? null : $flavor,
            'puffs' => $puffs === '' ? null : (int) $puffs,
        ];
    }

    private function getValidationRules(): array
    {
        $category = trim((string) $this->request->getPost('category'));
        $rows = $this->getFlavorInventoryRowsFromRequest();
        $usesFlavorInventory = $this->shouldUseFlavorInventoryEditor($category, $rows);

        return [
            'name' => 'required|min_length[2]|max_length[255]',
            'category' => 'required|min_length[2]|max_length[100]',
            'brand' => 'permit_empty|max_length[100]',
            'price' => $usesFlavorInventory ? 'permit_empty|numeric|greater_than_equal_to[0]' : 'required|numeric|greater_than_equal_to[0]',
            'stock_qty' => 'required|integer|greater_than_equal_to[0]',
            'is_active' => 'required|in_list[0,1]',
            'flavor' => 'permit_empty|max_length[100]',
            'puffs' => 'permit_empty|integer|greater_than_equal_to[0]',
        ];
    }

    private function getFlavorInventoryRowsFromRequest(): array
    {
        $variantIds = $this->request->getPost('variant_ids');
        $variantFlavors = $this->request->getPost('variant_flavors');
        $variantStocks = $this->request->getPost('variant_stocks');
        $variantPuffs = $this->request->getPost('variant_puffs');
        $variantPrices = $this->request->getPost('variant_prices');
        $defaultVariantPuffs = trim((string) $this->request->getPost('default_variant_puffs'));
        $defaultVariantPrice = trim((string) $this->request->getPost('price'));
        $puffGroupPrices = $this->getPuffGroupPricesFromRequest();

        $variantIds = is_array($variantIds) ? array_values($variantIds) : [];
        $variantFlavors = is_array($variantFlavors) ? array_values($variantFlavors) : [];
        $variantStocks = is_array($variantStocks) ? array_values($variantStocks) : [];
        $variantPuffs = is_array($variantPuffs) ? array_values($variantPuffs) : [];
        $variantPrices = is_array($variantPrices) ? array_values($variantPrices) : [];

        $rowCount = max(count($variantIds), count($variantFlavors), count($variantStocks), count($variantPuffs), count($variantPrices));
        $rows = [];

        for ($index = 0; $index < $rowCount; $index++) {
            $variantId = trim((string) ($variantIds[$index] ?? ''));
            $flavor = trim((string) ($variantFlavors[$index] ?? ''));
            $stock = trim((string) ($variantStocks[$index] ?? ''));
            $puffs = trim((string) ($variantPuffs[$index] ?? ''));
            $price = trim((string) ($variantPrices[$index] ?? ''));

            if ($variantId === '' && $flavor === '' && $stock === '' && $puffs === '' && $price === '') {
                continue;
            }

            if ($puffs === '' && $defaultVariantPuffs !== '') {
                $puffs = $defaultVariantPuffs;
            }

            $normalizedPuffs = $puffs === '' ? null : (int) $puffs;
            if ($normalizedPuffs !== null && array_key_exists($normalizedPuffs, $puffGroupPrices)) {
                $price = (string) $puffGroupPrices[$normalizedPuffs];
            } elseif ($price === '' && $defaultVariantPrice !== '') {
                $price = $defaultVariantPrice;
            }

            $rows[] = [
                'id' => $variantId === '' ? null : (int) $variantId,
                'flavor' => $flavor,
                'stock_qty' => $stock === '' ? null : (int) $stock,
                'puffs' => $normalizedPuffs,
                'price' => $price === '' ? null : (float) $price,
            ];
        }

        return $rows;
    }

    private function getPuffGroupPricesFromRequest(): array
    {
        $submittedPuffGroupPrices = $this->request->getPost('puff_group_prices');
        if (!is_array($submittedPuffGroupPrices)) {
            return [];
        }

        $puffGroupPrices = [];
        foreach ($submittedPuffGroupPrices as $puffValue => $priceValue) {
            $normalizedPuffValue = (int) $puffValue;
            $normalizedPriceValue = trim((string) $priceValue);

            if ($normalizedPuffValue <= 0 || $normalizedPriceValue === '') {
                continue;
            }

            $puffGroupPrices[$normalizedPuffValue] = (float) $normalizedPriceValue;
        }

        return $puffGroupPrices;
    }

    private function shouldUseFlavorInventoryEditor(string $category, array $rows): bool
    {
        return $this->isFlavorInventoryCategory($category) || $rows !== [];
    }

    private function validateFlavorInventoryRows(string $category, bool $usesFlavorInventory, array $rows, array $allowedPuffs = []): array
    {
        if (!$usesFlavorInventory) {
            return [];
        }

        $errors = [];
        $allowedPuffs = array_values(array_unique(array_map('intval', array_filter(
            $allowedPuffs,
            static fn ($value): bool => $value !== null && (int) $value > 0
        ))));

        if ($rows === []) {
            return ['Add at least one flavor row for this product.'];
        }

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 1;
            $flavor = trim((string) ($row['flavor'] ?? ''));
            $stockQty = $row['stock_qty'];
            $puffs = $row['puffs'];
            $price = $row['price'];

            if ($flavor === '') {
                $errors[] = "Flavor row {$rowNumber} is missing a flavor name.";
            }

            if ($stockQty === null || $stockQty < 0) {
                $errors[] = "Flavor row {$rowNumber} needs a valid stock quantity.";
            }

            if ($price === null || $price < 0) {
                $errors[] = "Flavor row {$rowNumber} needs a valid price.";
            }

            if ($this->categoryRequiresManagedPuffs($category)) {
                if ($puffs !== null && $puffs < 0) {
                    $errors[] = "Flavor row {$rowNumber} needs a valid puff count.";
                }

                if ($this->categoryRequiresRequiredPuffs($category) && $puffs === null) {
                    $errors[] = "Flavor row {$rowNumber} needs a puff count.";
                }

                if ($allowedPuffs !== [] && $puffs === null) {
                    $errors[] = "Flavor row {$rowNumber} must use one of the existing puff counts: " . implode(', ', array_map('number_format', $allowedPuffs)) . '.';
                }

                if ($allowedPuffs !== [] && $puffs !== null && !in_array($puffs, $allowedPuffs, true)) {
                    $errors[] = "Flavor row {$rowNumber} must use one of the existing puff counts: " . implode(', ', array_map('number_format', $allowedPuffs)) . '.';
                }
            }
        }

        return $errors;
    }

    private function getExistingFlavorInventoryPuffChoices(array $product): array
    {
        $variants = $this->productModel->getProductVariantsForEditing(
            (string) ($product['name'] ?? ''),
            $product['brand'] ?? null,
            (string) ($product['category'] ?? '')
        );

        $puffChoices = [];
        foreach ($variants as $variant) {
            $puffs = (int) ($variant['puffs'] ?? 0);
            if ($puffs > 0) {
                $puffChoices[$puffs] = $puffs;
            }
        }

        $puffChoices = array_values($puffChoices);
        sort($puffChoices, SORT_NUMERIC);

        return $puffChoices;
    }

    private function createFlavorInventoryGroup(array $data, array $rows): void
    {
        $commonData = [
            'name' => $data['name'],
            'category' => $data['category'],
            'brand' => $data['brand'],
            'image_url' => $data['image_url'] ?? null,
            'is_active' => $data['is_active'],
        ];

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            foreach ($rows as $row) {
                $variantData = $commonData + [
                    'flavor' => trim((string) ($row['flavor'] ?? '')),
                    'price' => (float) ($row['price'] ?? $data['price']),
                    'stock_qty' => (int) ($row['stock_qty'] ?? 0),
                    'puffs' => $this->normalizeFlavorInventoryPuffs(
                        (string) ($data['category'] ?? ''),
                        $row['puffs'] ?? null
                    ),
                ];

                if (!$this->productModel->insert($variantData)) {
                    throw new \RuntimeException('Failed to add a new flavor variant.');
                }
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Failed to save product flavor inventory.');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }

    private function updateFlavorInventoryGroup(array $product, array $data, array $rows): void
    {
        $existingVariants = $this->productModel->getProductVariantsForEditing(
            (string) ($product['name'] ?? ''),
            $product['brand'] ?? null,
            (string) ($product['category'] ?? '')
        );
        $existingIds = array_map('intval', array_column($existingVariants, 'id'));
        $submittedExistingIds = [];
        $finalImageUrl = array_key_exists('image_url', $data)
            ? $data['image_url']
            : ($product['image_url'] ?? null);

        $commonData = [
            'name' => $data['name'],
            'category' => $data['category'],
            'brand' => $data['brand'],
            'image_url' => $finalImageUrl,
            'is_active' => $data['is_active'],
        ];

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            foreach ($rows as $row) {
                $variantId = $row['id'] ?? null;
                $variantData = $commonData + [
                    'flavor' => trim((string) ($row['flavor'] ?? '')),
                    'price' => (float) ($row['price'] ?? $data['price']),
                    'stock_qty' => (int) ($row['stock_qty'] ?? 0),
                    'puffs' => $this->normalizeFlavorInventoryPuffs(
                        (string) ($data['category'] ?? ''),
                        $row['puffs'] ?? null
                    ),
                ];

                if ($variantId !== null && in_array($variantId, $existingIds, true)) {
                    if (!$this->productModel->update($variantId, $variantData)) {
                        throw new \RuntimeException('Failed to update one of the flavor variants.');
                    }

                    $submittedExistingIds[] = $variantId;
                    continue;
                }

                if (!$this->productModel->insert($variantData)) {
                    throw new \RuntimeException('Failed to add a new flavor variant.');
                }
            }

            $variantIdsToDelete = array_diff($existingIds, $submittedExistingIds);
            foreach ($variantIdsToDelete as $variantIdToDelete) {
                if (!$this->productModel->delete($variantIdToDelete)) {
                    throw new \RuntimeException('Failed to remove an old flavor variant.');
                }
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Failed to save product flavor inventory.');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }

    private function normalizeFlavorInventoryPuffs(string $category, ?int $puffs): ?int
    {
        if (!$this->categoryRequiresManagedPuffs($category)) {
            return null;
        }

        return $puffs === null ? null : max(0, (int) $puffs);
    }

    private function isFlavorInventoryCategory(string $category): bool
    {
        $normalizedCategory = strtolower(trim($category));
        return $normalizedCategory === 'disposable' || strpos($normalizedCategory, 'disposable') !== false
            || $normalizedCategory === 'pod' || strpos($normalizedCategory, 'pod') !== false
            || $normalizedCategory === 'pods' || strpos($normalizedCategory, 'pods') !== false
            || $normalizedCategory === 'e-liquid' || strpos($normalizedCategory, 'e-liquid') !== false
            || $normalizedCategory === 'e liquid' || strpos($normalizedCategory, 'e liquid') !== false
            || $normalizedCategory === 'eliquid' || strpos($normalizedCategory, 'eliquid') !== false
            || $normalizedCategory === 'liquid' || strpos($normalizedCategory, 'liquid') !== false;
    }

    private function categoryRequiresManagedPuffs(string $category): bool
    {
        $normalizedCategory = strtolower(trim($category));
        return $normalizedCategory === 'disposable' || strpos($normalizedCategory, 'disposable') !== false
            || $normalizedCategory === 'pod' || strpos($normalizedCategory, 'pod') !== false
            || $normalizedCategory === 'pods' || strpos($normalizedCategory, 'pods') !== false;
    }

    private function categoryRequiresRequiredPuffs(string $category): bool
    {
        $normalizedCategory = strtolower(trim($category));
        return $normalizedCategory === 'disposable' || strpos($normalizedCategory, 'disposable') !== false;
    }

    private function buildErrorListMessage(array $errors): string
    {
        $message = 'Please fix the following errors:<ul>';

        foreach ($errors as $error) {
            $message .= '<li>' . esc($error) . '</li>';
        }

        $message .= '</ul>';

        return $message;
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

        $trackedImageDirectory = trim(str_replace('\\', '/', product_image_tracked_directory()), '/');
        $uploadDir = rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $trackedImageDirectory);
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
            throw new \RuntimeException('Image upload failed. Unable to prepare upload folder.');
        }

        $newFileName = $file->getRandomName();
        $file->move($uploadDir, $newFileName);

        $newRelativePath = $trackedImageDirectory . '/' . $newFileName;
        $this->deleteOldUploadedImage($existingImageUrl);

        return $newRelativePath;
    }

    private function deleteOldUploadedImage(?string $existingImageUrl): void
    {
        if (empty($existingImageUrl)) {
            return;
        }

        $normalizedPath = ltrim(str_replace('\\', '/', $existingImageUrl), '/');
        $trackedImageDirectory = trim(str_replace('\\', '/', product_image_tracked_directory()), '/');
        if ($trackedImageDirectory === '' || !str_starts_with($normalizedPath, $trackedImageDirectory . '/')) {
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
        $normalizedImageUrl = trim((string) (normalize_product_image_path($imageUrl, true) ?? ''));

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

            $imageUrl = trim((string) (normalize_product_image_path($product['image_url'] ?? null, true) ?? ''));
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
