<?= $this->include('layouts/header') ?>

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
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Product Information</h5>
                    <span class="badge bg-info">ID: <?= (int) $product['id'] ?></span>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('/products/update/' . $product['id']) ?>" method="POST" enctype="multipart/form-data">
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
                                <input type="text"
                                       class="form-control"
                                       id="category"
                                       name="category"
                                       required
                                       maxlength="100"
                                       placeholder="e.g. Device, E-liquid"
                                       value="<?= old('category', $product['category']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="brand" class="form-label">Brand</label>
                                <input type="text"
                                       class="form-control"
                                       id="brand"
                                       name="brand"
                                       maxlength="100"
                                       placeholder="Optional brand name"
                                       value="<?= old('brand', $product['brand']) ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image_file" class="form-label">Product Image Upload</label>
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
                                     style="width: 120px; height: 120px; object-fit: cover; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2);">
                            </div>
                        <?php endif; ?>

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

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Update Product
                            </button>
                            <a href="<?= site_url('/products') ?>" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layouts/footer') ?>
