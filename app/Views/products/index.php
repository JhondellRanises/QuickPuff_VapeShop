<?= $this->include('layouts/header') ?>

<style>
.category-section {
    border: none;
    border-radius: 0;
    padding: 0;
    background: transparent;
    margin-bottom: 30px;
}

.category-header {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 15px;
    margin-bottom: 20px;
}

.category-stats .badge {
    font-size: 0.75rem;
    font-weight: 500;
}

.table-sm th {
    font-weight: 600;
    font-size: 0.85rem;
    border-top: none;
    color: #ffffff !important;
}

.table-sm td {
    vertical-align: middle;
    font-size: 0.9rem;
    color: #ffffff !important;
}

.badge.bg-light.text-dark {
    background-color: #f8f9fa !important;
    color: #495057 !important;
    border: 1px solid #dee2e6;
}

hr.my-4 {
    display: none;
}

.text-muted {
    color: #ffffff !important;
}

.fw-semibold {
    color: #ffffff !important;
}

.category-info h4 {
    color: #ffffff !important;
}

.badge {
    font-size: 0.75em;
}

.category-title {
    color: #ffffff !important;
}

.product-name {
    color: #ffffff !important;
}

.brand-name {
    color: #ffffff !important;
}

.flavor-badge {
    /* Colors handled by inline styles */
    font-size: 0.75em;
    padding: 0.25em 0.5em;
}

.puffs-count {
    color: #ffffff !important;
}

.price-display {
    color: #ffffff !important;
}

.stock-badge {
    color: #ffffff !important;
}

.status-badge {
    color: #ffffff !important;
}
</style>

<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title" style="color: #ffffff !important;">Stock Management</h1>
                <p class="page-subtitle" style="color: #ffffff !important;">Create, update, and monitor product inventory</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fas fa-plus me-2"></i>
                Add Product
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Products</h6>
                    <div class="stats-value text-info fw-bold"><?= number_format((int) $stats['total']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Active Products</h6>
                    <div class="stats-value text-success fw-bold"><?= number_format((int) $stats['active']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Low Stock (1-10)</h6>
                    <div class="stats-value text-warning"><?= (int) $stats['low_stock'] ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Out of Stock</h6>
                    <div class="stats-value text-danger"><?= (int) $stats['out_of_stock'] ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="q" class="form-label">Search</label>
                    <input type="text"
                           class="form-control"
                           id="q"
                           name="q"
                           placeholder="Search name, category, brand, or flavor (automatic search)"
                           value="<?= esc($filters['q'] ?? '') ?>"
                           autocomplete="off">
                </div>
                <div class="col-md-2">
                    <label for="category_filter" class="form-label">Category</label>
                    <select class="form-select" id="category_filter" name="category_filter">
                        <option value="">All Categories</option>
                        <?php if (isset($categories) && !empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= esc($cat['category']) ?>" <?= ($filters['category_filter'] ?? '') === $cat['category'] ? 'selected' : '' ?>>
                                    <?= esc($cat['category']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="" <?= empty($filters['status']) ? 'selected' : '' ?>>All</option>
                        <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="brand_filter" class="form-label">Brand</label>
                    <select class="form-select" id="brand_filter" name="brand_filter">
                        <option value="">All Brands</option>
                        <?php if (isset($brands) && !empty($brands)): ?>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= esc($brand['brand']) ?>" <?= ($filters['brand_filter'] ?? '') === $brand['brand'] ? 'selected' : '' ?>>
                                    <?= esc($brand['brand']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button onclick="resetSearch()" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-refresh me-2"></i>Reset All
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0" style="color: #ffffff !important;">Product Inventory (<?= $totalProducts ?> products)</h5>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($groupedProducts)): ?>
                <?php foreach ($groupedProducts as $category => $products): ?>
                    <!-- Category Section -->
                    <div class="category-section mb-4">
                        <div class="category-header d-flex align-items-center mb-3">
                            <div class="category-info flex-grow-1">
                                <?php
                                $categoryColors = [
                                    'Pods' => 'primary',
                                    'Device' => 'success', 
                                    'E-liquid' => 'danger',
                                    'Disposable' => 'warning',
                                    'Pods Kit' => 'info',
                                    'Accessory' => 'secondary'
                                ];
                                $color = $categoryColors[$category] ?? 'dark';
                                ?>
                                <h4 class="mb-1 text-<?= $color ?> category-title"><?= esc($category) ?></h4>
                                <small class="text-muted"><?= count($products) ?> products in this category</small>
                            </div>
                            <div class="category-stats">
                                <?php
                                $activeCount = 0;
                                $lowStockCount = 0;
                                $outOfStockCount = 0;
                                $totalStock = 0;
                                 
                                foreach ($products as $product) {
                                    if ($product['is_active']) $activeCount++;
                                    if ($product['stock_qty'] > 0 && $product['stock_qty'] <= 10) $lowStockCount++;
                                    if ($product['stock_qty'] == 0) $outOfStockCount++;
                                    $totalStock += $product['stock_qty'];
                                }
                                ?>
                                <span class="badge bg-success me-1"><?= $activeCount ?> Active</span>
                                <?php if ($lowStockCount > 0): ?>
                                    <span class="badge bg-warning me-1"><?= $lowStockCount ?> Low Stock</span>
                                <?php endif; ?>
                                <?php if ($outOfStockCount > 0): ?>
                                    <span class="badge bg-danger me-1"><?= $outOfStockCount ?> Out of Stock</span>
                                <?php endif; ?>
                                <span class="badge bg-info"><?= $totalStock ?> Total Stock</span>
                            </div>
                        </div>

                        <!-- Products Table for this Category -->
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="60">ID</th>
                                        <th>Product Name</th>
                                        <th width="100">Brand</th>
                                        <th width="120">Flavor</th>
                                        <th width="80">Puffs</th>
                                        <th width="80">Price</th>
                                        <th width="80">Stock</th>
                                        <th width="80">Status</th>
                                        <th width="100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <tr class="<?= !$product['is_active'] ? 'table-secondary' : '' ?>">
                                            <td><?= (int) $product['id'] ?></td>
                                            <td>
                                                <div class="fw-semibold product-name"><?= esc($product['name']) ?></div>
                                                <?php if (!empty($product['flavor_category'])): ?>
                                                    <small class="text-muted">
                                                        <?php
                                                        if ($product['flavor_category'] === 'Fruity') {
                                                            echo '<span class="badge" style="background-color: #ffebee; color: #d32f2f !important; border: 1px solid #ffcdd2;">' . esc($product['flavor_category']) . '</span>';
                                                        } elseif ($product['flavor_category'] === 'Dessert') {
                                                            echo '<span class="badge" style="background-color: #fff8e1; color: #f57c00 !important; border: 1px solid #ffecb3;">' . esc($product['flavor_category']) . '</span>';
                                                        } elseif ($product['flavor_category'] === 'Menthol') {
                                                            echo '<span class="badge" style="background-color: #e0f7fa; color: #0097a7 !important; border: 1px solid #b2ebf2;">' . esc($product['flavor_category']) . '</span>';
                                                        } elseif ($product['flavor_category'] === 'Tobacco') {
                                                            echo '<span class="badge" style="background-color: #f5f5f5; color: #424242 !important; border: 1px solid #e0e0e0;">' . esc($product['flavor_category']) . '</span>';
                                                        } elseif ($product['flavor_category'] === 'Beverage') {
                                                            echo '<span class="badge" style="background-color: #e8f5e8; color: #2e7d32 !important; border: 1px solid #c8e6c9;">' . esc($product['flavor_category']) . '</span>';
                                                        } elseif ($product['flavor_category'] === 'Candy') {
                                                            echo '<span class="badge" style="background-color: #e3f2fd; color: #1976d2 !important; border: 1px solid #bbdefb;">' . esc($product['flavor_category']) . '</span>';
                                                        } elseif ($product['flavor_category'] === 'Herbal') {
                                                            echo '<span class="badge" style="background-color: #f5f5f5; color: #616161 !important; border: 1px solid #e0e0e0;">' . esc($product['flavor_category']) . '</span>';
                                                        } else {
                                                            echo '<span class="badge bg-secondary">' . esc($product['flavor_category']) . '</span>';
                                                        }
                                                        ?>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="brand-name"><?= !empty($product['brand']) ? esc($product['brand']) : '-' ?></span>
                                            </td>
                                            <td>
                                                <?php if (!empty($product['flavor'])): ?>
                                                    <span class="badge bg-dark text-white flavor-badge">
                                                        <?= esc($product['flavor']) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($product['puffs']) && $product['puffs'] > 0): ?>
                                                    <span class="text-muted puffs-count"><?= number_format($product['puffs']) ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="price-display">₱<?= number_format($product['price'], 2) ?></td>
                                            <td>
                                                <?php if ($product['stock_qty'] > 10): ?>
                                                    <span class="badge bg-success stock-badge"><?= (int) $product['stock_qty'] ?></span>
                                                <?php elseif ($product['stock_qty'] > 0): ?>
                                                    <span class="badge bg-warning stock-badge"><?= (int) $product['stock_qty'] ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger stock-badge">0</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($product['is_active']): ?>
                                                    <span class="badge bg-success status-badge">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary status-badge">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="<?= site_url('/products/edit/' . $product['id']) ?>" 
                                                       class="btn btn-outline-primary" 
                                                       title="Edit Product">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if ($product['is_active']): ?>
                                                        <a href="<?= site_url('/products/delete/' . $product['id']) ?>" 
                                                           class="btn btn-outline-danger" 
                                                           title="Delete"
                                                           onclick="return confirm('Are you sure you want to delete this product?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="<?= site_url('/products/activate/' . $product['id']) ?>" 
                                                           class="btn btn-outline-success" 
                                                           title="Activate">
                                                            <i class="fas fa-play"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No products found</h5>
                    <p class="text-muted">Try adjusting your search filters or add your first product.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="fas fa-plus me-2"></i>Add Your First Product
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">
                    <i class="fas fa-plus me-2"></i>Add Product
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= site_url('/products/store') ?>" method="POST" enctype="multipart/form-data" id="addProductForm">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name *</label>
                        <input type="text"
                               class="form-control"
                               id="name"
                               name="name"
                               required
                               maxlength="255"
                               placeholder="Enter product name"
                               value="<?= old('name') ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category *</label>
                            <div class="input-group">
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Select category...</option>
                                    <?php if (isset($categories) && !empty($categories)): ?>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= esc($cat['category']) ?>" <?= old('category') === $cat['category'] ? 'selected' : '' ?>>
                                                <?= esc($cat['category']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <option value="__new__">+ Add New Category</option>
                                </select>
                            </div>
                            <input type="text" 
                                   class="form-control mt-2" 
                                   id="new_category" 
                                   name="new_category" 
                                   placeholder="Enter new category name" 
                                   style="display: none;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="brand" class="form-label">Brand</label>
                            <div class="input-group">
                                <select class="form-select" id="brand" name="brand">
                                    <option value="">Select brand...</option>
                                    <?php if (isset($brands) && !empty($brands)): ?>
                                        <?php foreach ($brands as $brand): ?>
                                            <option value="<?= esc($brand['brand']) ?>" <?= old('brand') === $brand['brand'] ? 'selected' : '' ?>>
                                                <?= esc($brand['brand']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <option value="__new__">+ Add New Brand</option>
                                </select>
                            </div>
                            <input type="text" 
                                   class="form-control mt-2" 
                                   id="new_brand" 
                                   name="new_brand" 
                                   placeholder="Enter new brand name" 
                                   style="display: none;">
                        </div>
                    </div>

                    <!-- Disposable Vape Specific Fields (Hidden by default) -->
                    <div id="disposableFields" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="flavor_category" class="form-label">Flavor Category *</label>
                                <select class="form-select" id="flavor_category" name="flavor_category">
                                    <option value="">Select flavor category...</option>
                                    <option value="Fruity">🍓 Fruity Flavors</option>
                                    <option value="Dessert">🍰 Dessert / Sweet Flavors</option>
                                    <option value="Menthol">❄️ Menthol / Icy Flavors</option>
                                    <option value="Tobacco">🚬 Tobacco Flavors</option>
                                    <option value="Beverage">🥤 Beverage Flavors</option>
                                    <option value="Candy">🍬 Candy Flavors</option>
                                    <option value="Herbal">🌿 Herbal / Unique Flavors</option>
                                    <option value="custom">+ Custom Flavor</option>
                                </select>
                                <small class="text-muted">Choose the flavor category for vapes and e-liquids</small>
                            </div>
                            <div class="col-md-6 mb-3" id="puffsFieldContainer">
                                <label for="puffs" class="form-label">Puff Count *</label>
                                <input type="number"
                                       class="form-control"
                                       id="puffs"
                                       name="puffs"
                                       placeholder="e.g. 5000, 8000, 12000"
                                       value="<?= old('puffs') ?>">
                                <small class="text-muted">Number of puffs per device (disposables and pods)</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="flavor" class="form-label">Flavor *</label>
                                <select class="form-select" id="flavor" name="flavor">
                                    <option value="">Select flavor category first...</option>
                                </select>
                                <input type="text" 
                                       class="form-control mt-2" 
                                       id="custom_flavor" 
                                       name="custom_flavor" 
                                       placeholder="Enter custom flavor name" 
                                       style="display: none;">
                                <small class="text-muted">Select flavor from the list or choose custom option</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image_file" class="form-label">Product Image Upload</label>
                        <input type="file"
                               class="form-control"
                               id="image_file"
                               name="image_file"
                               accept="image/jpeg,image/png,image/webp,image/gif">
                        <small class="text-muted">Optional. Choose JPG, PNG, WEBP, or GIF (max 4MB).</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price *</label>
                            <input type="number"
                                   class="form-control"
                                   id="price"
                                   name="price"
                                   required
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00"
                                   value="<?= old('price', '0.00') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stock_qty" class="form-label">Stock Quantity *</label>
                            <input type="number"
                                   class="form-control"
                                   id="stock_qty"
                                   name="stock_qty"
                                   required
                                   step="1"
                                   min="0"
                                   placeholder="0"
                                   value="<?= old('stock_qty', '0') ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="is_active" class="form-label">Status *</label>
                        <select class="form-select" id="is_active" name="is_active" required>
                            <option value="1" <?= old('is_active', '1') === '1' ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= old('is_active') === '0' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                        <small class="text-muted">Inactive products are hidden from POS.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="submit" form="addProductForm" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Product
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Handle category dropdown change
document.getElementById('category').addEventListener('change', function() {
    const newCategoryInput = document.getElementById('new_category');
    const disposableFields = document.getElementById('disposableFields');
    const flavorInput = document.getElementById('flavor');
    const puffsInput = document.getElementById('puffs');
    const puffsContainer = document.getElementById('puffsFieldContainer');
    
    if (this.value === '__new__') {
        newCategoryInput.style.display = 'block';
        newCategoryInput.required = true;
        this.required = false;
        this.name = 'category_old';
        newCategoryInput.name = 'category';
        
        // Hide disposable fields when adding new category
        disposableFields.style.display = 'none';
        flavorInput.required = false;
        puffsInput.required = false;
        flavorInput.value = '';
        puffsInput.value = '';
    } else {
        newCategoryInput.style.display = 'none';
        newCategoryInput.required = false;
        newCategoryInput.value = '';
        this.required = true;
        this.name = 'category';
        newCategoryInput.name = 'new_category';
        
        // Check if selected category is disposable, pod, or e-liquid (case-insensitive)
        const selectedCategory = this.value.toLowerCase();
        if (selectedCategory === 'disposable' || selectedCategory.includes('disposable') || 
            selectedCategory === 'pod' || selectedCategory.includes('pod') || 
            selectedCategory === 'pods' || selectedCategory.includes('pods') ||
            selectedCategory === 'e-liquid' || selectedCategory.includes('e-liquid') ||
            selectedCategory === 'e liquid' || selectedCategory.includes('e liquid') ||
            selectedCategory === 'eliquid' || selectedCategory.includes('eliquid') ||
            selectedCategory === 'liquid' || selectedCategory.includes('liquid')) {
            disposableFields.style.display = 'block';
            flavorInput.required = true;
            
            // Puffs required for disposables and pods, not e-liquids
            if (selectedCategory === 'disposable' || selectedCategory.includes('disposable') ||
                selectedCategory === 'pod' || selectedCategory.includes('pod') ||
                selectedCategory === 'pods' || selectedCategory.includes('pods')) {
                puffsInput.required = true;
                puffsContainer.style.display = 'block';
            } else {
                puffsInput.required = false;
                puffsInput.value = '';
                puffsContainer.style.display = 'none';
            }
        } else {
            disposableFields.style.display = 'none';
            flavorInput.required = false;
            puffsInput.required = false;
            flavorInput.value = '';
            puffsInput.value = '';
            puffsContainer.style.display = 'block';
        }
    }
});

// Handle new category input change - check if it's disposable or pod
document.getElementById('new_category').addEventListener('input', function() {
    const disposableFields = document.getElementById('disposableFields');
    const flavorInput = document.getElementById('flavor');
    const puffsInput = document.getElementById('puffs');
    const puffsContainer = document.getElementById('puffsFieldContainer');
    
    const newCategoryValue = this.value.toLowerCase();
    if (newCategoryValue.includes('disposable') || newCategoryValue.includes('pod') || newCategoryValue.includes('pods') ||
        newCategoryValue.includes('e-liquid') || newCategoryValue.includes('e liquid') ||
        newCategoryValue.includes('eliquid') || newCategoryValue.includes('liquid')) {
        disposableFields.style.display = 'block';
        flavorInput.required = true;
        
        // Puffs for disposables and pods
        if (newCategoryValue.includes('disposable') || newCategoryValue.includes('pod') || newCategoryValue.includes('pods')) {
            puffsInput.required = true;
            puffsContainer.style.display = 'block';
        } else {
            puffsInput.required = false;
            puffsInput.value = '';
            puffsContainer.style.display = 'none';
        }
    } else {
        disposableFields.style.display = 'none';
        flavorInput.required = false;
        puffsInput.required = false;
        flavorInput.value = '';
        puffsInput.value = '';
        puffsContainer.style.display = 'block';
    }
});

// Flavor categories and their flavors
const flavorCategories = {
    'Fruity': ['Mango', 'Strawberry', 'Watermelon', 'Grape', 'Apple', 'Blueberry', 'Pineapple', 'Mixed Berries'],
    'Dessert': ['Vanilla Custard', 'Chocolate', 'Caramel', 'Cheesecake', 'Donut', 'Ice Cream', 'Marshmallow'],
    'Menthol': ['Mint', 'Menthol', 'Ice Mango', 'Ice Grape', 'Ice Lychee', 'Ice Watermelon'],
    'Tobacco': ['Classic Tobacco', 'American Blend', 'Smooth Tobacco', 'Bold Tobacco', 'Vanilla Tobacco'],
    'Beverage': ['Coffee', 'Milk Tea', 'Cola', 'Energy Drink', 'Lemonade', 'Iced Tea'],
    'Candy': ['Bubblegum', 'Cotton Candy', 'Gummy Bears', 'Sour Candy', 'Lollipop'],
    'Herbal': ['Green Tea', 'Matcha', 'Aloe Vera', 'Honey', 'Lavender']
};

// Handle flavor category change
document.addEventListener('DOMContentLoaded', function() {
    const flavorCategorySelect = document.getElementById('flavor_category');
    const flavorSelect = document.getElementById('flavor');
    const customFlavorInput = document.getElementById('custom_flavor');
    
    if (flavorCategorySelect) {
        flavorCategorySelect.addEventListener('change', function() {
            const selectedCategory = this.value;
            
            // Clear current flavor options
            flavorSelect.innerHTML = '<option value="">Select flavor...</option>';
            customFlavorInput.style.display = 'none';
            customFlavorInput.required = false;
            customFlavorInput.value = '';
            
            if (selectedCategory === 'custom') {
                // Show custom flavor input
                customFlavorInput.style.display = 'block';
                customFlavorInput.required = true;
                flavorSelect.style.display = 'none';
                flavorSelect.required = false;
            } else if (selectedCategory && flavorCategories[selectedCategory]) {
                // Show flavor dropdown with category flavors
                flavorSelect.style.display = 'block';
                flavorSelect.required = true;
                
                flavorCategories[selectedCategory].forEach(function(flavor) {
                    const option = document.createElement('option');
                    option.value = flavor;
                    option.textContent = flavor;
                    flavorSelect.appendChild(option);
                });
            } else {
                // Hide both if no category selected
                flavorSelect.style.display = 'block';
                flavorSelect.required = false;
                flavorSelect.innerHTML = '<option value="">Select flavor category first...</option>';
            }
        });
    }
});

// Handle brand dropdown change
document.getElementById('brand').addEventListener('change', function() {
    const newBrandInput = document.getElementById('new_brand');
    if (this.value === '__new__') {
        newBrandInput.style.display = 'block';
        this.name = 'brand_old';
        newBrandInput.name = 'brand';
    } else {
        newBrandInput.style.display = 'none';
        newBrandInput.value = '';
        this.name = 'brand';
        newBrandInput.name = 'new_brand';
    }
});

// Handle form submission - show loading state and handle AJAX
document.getElementById('addProductForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Client-side validation
    const flavorCategory = document.getElementById('flavor_category');
    const flavor = document.getElementById('flavor');
    const customFlavor = document.getElementById('custom_flavor');
    const disposableFields = document.getElementById('disposableFields');
    
    // Check if flavor fields are visible and validate them
    if (disposableFields.style.display !== 'none') {
        if (!flavorCategory.value) {
            showAlert('Please select a flavor category.', 'danger');
            return;
        }
        
        if (flavorCategory.value === 'custom') {
            if (!customFlavor.value.trim()) {
                showAlert('Please enter a custom flavor name.', 'danger');
                return;
            }
        } else {
            if (!flavor.value) {
                showAlert('Please select a flavor.', 'danger');
                return;
            }
        }
    }
    
    const submitBtn = document.querySelector('button[form="addProductForm"]');
    const originalText = submitBtn.innerHTML;
    const modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    submitBtn.disabled = true;
    
    // Create FormData for file upload
    const formData = new FormData(this);
    
    // Submit via AJAX
    fetch('<?= site_url('/products/store') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Show success message
            showAlert(data.message, 'success');
            
            // Close modal
            modal.hide();
            
            // Reset form
            this.reset();
            
            // Hide new category/brand inputs and flavor fields
            document.getElementById('new_category').style.display = 'none';
            document.getElementById('new_brand').style.display = 'none';
            document.getElementById('disposableFields').style.display = 'none';
            document.getElementById('custom_flavor').style.display = 'none';
            
            // Reload page after a short delay to show new product
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Show error message
            showAlert(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while saving the product. Please check the console for details.', 'danger');
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Clear default values on focus
document.getElementById('price').addEventListener('focus', function() {
    if (this.value === '0.00') {
        this.value = '';
    }
});

document.getElementById('stock_qty').addEventListener('focus', function() {
    if (this.value === '0') {
        this.value = '';
    }
});

// Restore default values on blur if empty
document.getElementById('price').addEventListener('blur', function() {
    if (this.value === '') {
        this.value = '0.00';
    }
});

document.getElementById('stock_qty').addEventListener('blur', function() {
    if (this.value === '') {
        this.value = '0';
    }
});

// Function to show alert messages
function showAlert(message, type) {
    // Remove any existing alerts
    const existingAlert = document.querySelector('.alert-container');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Create alert container
    const alertContainer = document.createElement('div');
    alertContainer.className = 'alert-container position-fixed top-0 start-50 translate-middle-x mt-3';
    alertContainer.style.zIndex = '9999';
    
    // Create alert
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.appendChild(alert);
    document.body.appendChild(alertContainer);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alertContainer) {
            alertContainer.remove();
        }
    }, 5000);
}

// Simple filter functions that reload the page with new parameters
function applyFilters() {
    const search = document.getElementById('q').value;
    const category = document.getElementById('category_filter').value;
    const brand = document.getElementById('brand_filter').value;
    const status = document.getElementById('status').value;
    
    const params = new URLSearchParams();
    if (search) params.set('q', search);
    if (category) params.set('category_filter', category);
    if (brand) params.set('brand_filter', brand);
    if (status) params.set('status', status);
    
    const url = params.toString() ? '<?= site_url('/products') ?>?' + params.toString() : '<?= site_url('/products') ?>';
    window.location.href = url;
}

// Add event listeners to all filter elements
document.addEventListener('DOMContentLoaded', function() {
    const filters = ['q', 'category_filter', 'brand_filter', 'status'];
    filters.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('change', function() {
                if (id === 'q') {
                    // For search, use debounced approach
                    clearTimeout(window.searchTimeout);
                    window.searchTimeout = setTimeout(applyFilters, 500);
                } else {
                    // For dropdowns, apply immediately
                    applyFilters();
                }
            });
        }
    });
});

// Reset function
window.resetSearch = function() {
    window.location.href = '<?= site_url('/products') ?>';
};

</script>

<?= $this->include('layouts/footer') ?>
