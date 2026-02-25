<?= $this->include('layouts/header') ?>

<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Add Product</h1>
                <p class="page-subtitle">Create a new inventory item</p>
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
                <div class="card-header">
                    <h5 class="card-title mb-0">Product Information</h5>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('/products/store') ?>" method="POST">
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
                                <input type="text"
                                       class="form-control"
                                       id="category"
                                       name="category"
                                       required
                                       maxlength="100"
                                       placeholder="e.g. Device, E-liquid"
                                       value="<?= old('category') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="brand" class="form-label">Brand</label>
                                <input type="text"
                                       class="form-control"
                                       id="brand"
                                       name="brand"
                                       maxlength="100"
                                       placeholder="Optional brand name"
                                       value="<?= old('brand') ?>">
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

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Save Product
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
