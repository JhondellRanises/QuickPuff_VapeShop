<?= $this->include('layouts/header') ?>

<style>
.form-label {
    color: #ffffff !important;
    font-weight: 500;
}

.form-control, .form-select {
    background-color: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff !important;
}

.form-control:focus, .form-select:focus {
    background-color: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.4);
    color: #ffffff !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
}

.form-control::placeholder {
    color: rgba(255, 255, 255, 0.6) !important;
}

.form-select option {
    background-color: #2d3748;
    color: #ffffff;
}

.text-muted {
    color: rgba(255, 255, 255, 0.7) !important;
}

.card {
    background-color: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.card-header {
    background-color: rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.page-title {
    color: #ffffff !important;
}

.page-subtitle {
    color: rgba(255, 255, 255, 0.7) !important;
}

.btn-outline-primary {
    border-color: rgba(255, 255, 255, 0.3);
    color: #ffffff;
}

.btn-outline-primary:hover {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.5);
    color: #ffffff;
}

.btn-outline-secondary {
    border-color: rgba(255, 255, 255, 0.3);
    color: #ffffff;
}

.btn-outline-secondary:hover {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.5);
    color: #ffffff;
}

.current-image {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid rgba(255, 255, 255, 0.2);
}
</style>

<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Edit Product</h1>
                <p class="page-subtitle">Update product details and stock levels</p>
            </div>
            <a href="<?= site_url('/products') ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Stock
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0" style="color: #ffffff !important;">
                        <i class="fas fa-edit me-2"></i>Product Information
                    </h5>
                    <span class="badge bg-info">ID: <?= (int) $product['id'] ?></span>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('/products/update/' . $product['id']) ?>" method="POST" enctype="multipart/form-data" id="editProductForm">
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
                                   value="<?= old('name', $product['name']) ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <div class="input-group">
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="">Select category...</option>
                                        <?php if (isset($categories) && !empty($categories)): ?>
                                            <?php foreach ($categories as $cat): ?>
                                                <option value="<?= esc($cat['category']) ?>" <?= old('category', $product['category']) === $cat['category'] ? 'selected' : '' ?>>
                                                    <?= esc($cat['category']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <option value="__new__" <?= old('category') === '__new__' ? 'selected' : '' ?>>+ Add New Category</option>
                                    </select>
                                </div>
                                <input type="text" 
                                       class="form-control mt-2" 
                                       id="new_category" 
                                       name="new_category" 
                                       placeholder="Enter new category name" 
                                       value="<?= old('new_category') ?>"
                                       style="display: none;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="brand" class="form-label">Brand</label>
                                <div class="input-group">
                                    <select class="form-select" id="brand" name="brand">
                                        <option value="">Select brand...</option>
                                        <?php if (isset($brands) && !empty($brands)): ?>
                                            <?php foreach ($brands as $brand_item): ?>
                                                <option value="<?= esc($brand_item['brand']) ?>" <?= old('brand', $product['brand']) === $brand_item['brand'] ? 'selected' : '' ?>>
                                                    <?= esc($brand_item['brand']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <option value="__new__" <?= old('brand') === '__new__' ? 'selected' : '' ?>>+ Add New Brand</option>
                                    </select>
                                </div>
                                <input type="text" 
                                       class="form-control mt-2" 
                                       id="new_brand" 
                                       name="new_brand" 
                                       placeholder="Enter new brand name" 
                                       value="<?= old('new_brand') ?>"
                                       style="display: none;">
                            </div>
                        </div>

                        <!-- Disposable Vape Specific Fields -->
                        <div id="disposableFields" style="display: <?= in_array($product['category'], ['Disposable', 'Pods', 'E-liquid']) ? 'block' : 'none' ?>;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="flavor_category" class="form-label">Flavor Category</label>
                                    <select class="form-select" id="flavor_category" name="flavor_category">
                                        <option value="">Select flavor category...</option>
                                        <option value="Fruity" <?= old('flavor_category', $product['flavor_category']) === 'Fruity' ? 'selected' : '' ?>>🍓 Fruity Flavors</option>
                                        <option value="Dessert" <?= old('flavor_category', $product['flavor_category']) === 'Dessert' ? 'selected' : '' ?>>🍰 Dessert / Sweet Flavors</option>
                                        <option value="Menthol" <?= old('flavor_category', $product['flavor_category']) === 'Menthol' ? 'selected' : '' ?>>❄️ Menthol / Icy Flavors</option>
                                        <option value="Tobacco" <?= old('flavor_category', $product['flavor_category']) === 'Tobacco' ? 'selected' : '' ?>>🚬 Tobacco Flavors</option>
                                        <option value="Beverage" <?= old('flavor_category', $product['flavor_category']) === 'Beverage' ? 'selected' : '' ?>>🥤 Beverage Flavors</option>
                                        <option value="Candy" <?= old('flavor_category', $product['flavor_category']) === 'Candy' ? 'selected' : '' ?>>🍬 Candy Flavors</option>
                                        <option value="Herbal" <?= old('flavor_category', $product['flavor_category']) === 'Herbal' ? 'selected' : '' ?>>🌿 Herbal / Unique Flavors</option>
                                        <option value="custom" <?= old('flavor_category') === 'custom' ? 'selected' : '' ?>>+ Custom Flavor</option>
                                    </select>
                                    <small class="text-muted">Choose the flavor category for vapes and e-liquids</small>
                                </div>
                                <div class="col-md-6 mb-3" id="puffsFieldContainer">
                                    <label for="puffs" class="form-label">Puff Count</label>
                                    <input type="number"
                                           class="form-control"
                                           id="puffs"
                                           name="puffs"
                                           placeholder="e.g. 5000, 8000, 12000"
                                           value="<?= old('puffs', $product['puffs']) ?>">
                                    <small class="text-muted">Number of puffs per device (disposables and pods)</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="flavor" class="form-label">Flavor</label>
                                    <select class="form-select" id="flavor" name="flavor">
                                        <option value="">Select flavor category first...</option>
                                        <?php if (!empty($product['flavor_category']) && $product['flavor_category'] !== 'custom'): ?>
                                            <?php
                                            $flavorOptions = [
                                                'Fruity' => ['Strawberry', 'Mango', 'Watermelon', 'Blueberry', 'Grape', 'Pineapple', 'Cherry', 'Raspberry'],
                                                'Dessert' => ['Vanilla', 'Chocolate', 'Caramel', 'Cream', 'Cheesecake', 'Coffee', 'Donut', 'Ice Cream'],
                                                'Menthol' => ['Mint', 'Cool Mint', 'Peppermint', 'Spearmint', 'Ice', 'Frost', 'Wintergreen', 'Eucalyptus'],
                                                'Tobacco' => ['Classic Tobacco', 'Menthol Tobacco', 'Vanilla Tobacco', 'Caramel Tobacco', 'Cuban Cigar', 'Pipe Tobacco'],
                                                'Beverage' => ['Cola', 'Energy Drink', 'Lemonade', 'Orange Soda', 'Grape Soda', 'Apple Juice', 'Coconut', 'Peach Tea'],
                                                'Candy' => ['Gummy Bears', 'Sour Patch', 'Jolly Rancher', 'Skittles', 'Starburst', 'Lollipop', 'Cotton Candy', 'Bubblegum'],
                                                'Herbal' => ['Green Tea', 'Matcha', 'Aloe Vera', 'Honey', 'Lavender', 'Chamomile', 'Ginseng', 'Echinacea']
                                            ];
                                            
                                            if (isset($flavorOptions[$product['flavor_category']])) {
                                                foreach ($flavorOptions[$product['flavor_category']] as $flavorOption) {
                                                    echo '<option value="' . esc($flavorOption) . '"' . ($product['flavor'] === $flavorOption ? ' selected' : '') . '>' . esc($flavorOption) . '</option>';
                                                }
                                            }
                                            ?>
                                        <?php endif; ?>
                                    </select>
                                    <input type="text" 
                                           class="form-control mt-2" 
                                           id="custom_flavor" 
                                           name="custom_flavor" 
                                           placeholder="Enter custom flavor name" 
                                           value="<?= old('custom_flavor') ?>"
                                           style="display: <?= $product['flavor_category'] === 'custom' ? 'block' : 'none' ?>;">
                                    <small class="text-muted">Select flavor from the list or choose custom option</small>
                                </div>
                            </div>
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
                                       value="<?= old('price', $product['price']) ?>">
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
                                       value="<?= old('stock_qty', $product['stock_qty']) ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image_file" class="form-label">Product Image</label>
                            <input type="file"
                                   class="form-control"
                                   id="image_file"
                                   name="image_file"
                                   accept="image/jpeg,image/png,image/webp,image/gif">
                            <small class="text-muted">Optional. Choose a new file to replace the current image (max 4MB).</small>
                        </div>

                        <?php if (!empty($product['image_url'])): ?>
                            <?php
                                $currentImageUrl = (string) $product['image_url'];
                                $currentImageSrc = preg_match('#^(?:https?:)?//#i', $currentImageUrl) || strpos($currentImageUrl, 'data:image') === 0
                                    ? $currentImageUrl
                                    : base_url(ltrim($currentImageUrl, '/'));
                            ?>
                            <div class="mb-3">
                                <label class="form-label d-block">Current Image</label>
                                <img src="<?= esc($currentImageSrc) ?>"
                                     alt="<?= esc($product['name']) ?> current image"
                                     class="current-image">
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="is_active" class="form-label">Status *</label>
                            <select class="form-select" id="is_active" name="is_active" required>
                                <option value="1" <?= old('is_active', (string) $product['is_active']) === '1' ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= old('is_active', (string) $product['is_active']) === '0' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                            <small class="text-muted">Inactive products are hidden from POS.</small>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Created</small>
                                <strong><?= !empty($product['created_at']) ? date('M d, Y H:i', strtotime($product['created_at'])) : 'N/A' ?></strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Last Updated</small>
                                <strong><?= !empty($product['updated_at']) ? date('M d, Y H:i', strtotime($product['updated_at'])) : 'N/A' ?></strong>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= site_url('/products') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" form="editProductForm">
                                <i class="fas fa-save me-2"></i>Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Handle category change
document.getElementById('category').addEventListener('change', function() {
    const newCategoryInput = document.getElementById('new_category');
    const disposableFields = document.getElementById('disposableFields');
    
    if (this.value === '__new__') {
        newCategoryInput.style.display = 'block';
        newCategoryInput.required = true;
        this.name = 'category_old';
        newCategoryInput.name = 'category';
        disposableFields.style.display = 'block';
    } else {
        newCategoryInput.style.display = 'none';
        newCategoryInput.required = false;
        newCategoryInput.value = '';
        this.name = 'category';
        newCategoryInput.name = 'new_category';
        
        // Show/hide disposable fields based on category
        const vapeCategories = ['Disposable', 'Pods', 'E-liquid'];
        disposableFields.style.display = vapeCategories.includes(this.value) ? 'block' : 'none';
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
            } else if (selectedCategory) {
                // Show flavor dropdown with category flavors
                flavorSelect.style.display = 'block';
                flavorSelect.required = true;
                
                const flavorCategories = {
                    'Fruity': ['Strawberry', 'Mango', 'Watermelon', 'Blueberry', 'Grape', 'Pineapple', 'Cherry', 'Raspberry'],
                    'Dessert': ['Vanilla', 'Chocolate', 'Caramel', 'Cream', 'Cheesecake', 'Coffee', 'Donut', 'Ice Cream'],
                    'Menthol': ['Mint', 'Cool Mint', 'Peppermint', 'Spearmint', 'Ice', 'Frost', 'Wintergreen', 'Eucalyptus'],
                    'Tobacco': ['Classic Tobacco', 'Menthol Tobacco', 'Vanilla Tobacco', 'Caramel Tobacco', 'Cuban Cigar', 'Pipe Tobacco'],
                    'Beverage': ['Cola', 'Energy Drink', 'Lemonade', 'Orange Soda', 'Grape Soda', 'Apple Juice', 'Coconut', 'Peach Tea'],
                    'Candy': ['Gummy Bears', 'Sour Patch', 'Jolly Rancher', 'Skittles', 'Starburst', 'Lollipop', 'Cotton Candy', 'Bubblegum'],
                    'Herbal': ['Green Tea', 'Matcha', 'Aloe Vera', 'Honey', 'Lavender', 'Chamomile', 'Ginseng', 'Echinacea']
                };
                
                if (flavorCategories[selectedCategory]) {
                    flavorCategories[selectedCategory].forEach(function(flavor) {
                        const option = document.createElement('option');
                        option.value = flavor;
                        option.textContent = flavor;
                        flavorSelect.appendChild(option);
                    });
                }
            } else {
                // Hide both if no category selected
                flavorSelect.style.display = 'block';
                flavorSelect.required = false;
                flavorSelect.innerHTML = '<option value="">Select flavor category first...</option>';
            }
        });
        
        // Trigger change on load to set initial state
        flavorCategorySelect.dispatchEvent(new Event('change'));
    }
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

// Handle form submission
document.getElementById('editProductForm').addEventListener('submit', function(e) {
    const submitBtn = document.querySelector('button[form="editProductForm"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
    submitBtn.disabled = true;
    
    // Re-enable after a delay in case form submission fails
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 5000);
});
</script>

<?= $this->include('layouts/footer') ?>
