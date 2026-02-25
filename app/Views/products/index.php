<?= $this->include('layouts/header') ?>

<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Stock Management</h1>
                <p class="page-subtitle">Create, update, and monitor product inventory</p>
            </div>
            <a href="<?= site_url('/products/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Add Product
            </a>
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
            <form action="<?= site_url('/products') ?>" method="GET" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="q" class="form-label">Search</label>
                    <input type="text"
                           class="form-control"
                           id="q"
                           name="q"
                           placeholder="Search name, category, or brand"
                           value="<?= esc($filters['q'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="" <?= empty($filters['status']) ? 'selected' : '' ?>>All</option>
                        <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                        <a href="<?= site_url('/products') ?>" class="btn btn-outline-secondary w-100">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Product Inventory</h5>
                <small class="text-muted">
                    Showing <?= (int) ($filtered_count ?? 0) ?> result(s)
                </small>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Stock Qty</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= (int) $product['id'] ?></td>
                                    <td><?= esc($product['name']) ?></td>
                                    <td><?= esc($product['category']) ?></td>
                                    <td>
                                        <?php if (!empty($product['brand'])): ?>
                                            <?= esc($product['brand']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>$<?= number_format((float) $product['price'], 2) ?></td>
                                    <td>
                                        <?php if ((int) $product['stock_qty'] === 0): ?>
                                            <span class="badge bg-danger">0 (Out)</span>
                                        <?php elseif ((int) $product['stock_qty'] <= 10): ?>
                                            <span class="badge bg-warning"><?= (int) $product['stock_qty'] ?> (Low)</span>
                                        <?php else: ?>
                                            <span class="badge bg-success"><?= (int) $product['stock_qty'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ((int) $product['is_active'] === 1): ?>
                                            <span class="badge status-active">Active</span>
                                        <?php else: ?>
                                            <span class="badge status-inactive">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= !empty($product['updated_at']) ? date('M d, Y H:i', strtotime($product['updated_at'])) : 'N/A' ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= site_url('/products/edit/' . $product['id']) ?>"
                                               class="btn btn-outline-primary btn-sm"
                                               title="Edit Product">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ((int) $product['is_active'] === 1): ?>
                                                <a href="<?= site_url('/products/delete/' . $product['id']) ?>"
                                                   class="btn btn-outline-danger btn-sm"
                                                   onclick="return confirm('Deactivate this product? It will no longer appear in POS.')"
                                                   title="Deactivate Product">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= site_url('/products/activate/' . $product['id']) ?>"
                                                   class="btn btn-outline-success btn-sm"
                                                   onclick="return confirm('Activate this product and make it available again?')"
                                                   title="Activate Product">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-box-open fa-3x mb-3 d-block text-muted"></i>
                                    No products found.
                                    <a href="<?= site_url('/products/create') ?>" class="btn btn-primary btn-sm mt-2">
                                        Add your first product
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layouts/footer') ?>
