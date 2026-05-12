<?= $this->include('layouts/header') ?>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Welcome back, <?= esc($user['full_name']) ?>. Here is your general system preview.</p>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon"><i class="fas fa-box"></i></div>
                    <div class="ms-3">
                        <div class="stats-value"><?= (int) ($stats['total_products'] ?? 0) ?></div>
                        <div class="stats-label">Total Products</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="ms-3">
                        <div class="stats-value"><?= (int) ($stats['low_stock_products'] ?? 0) ?></div>
                        <div class="stats-label">Low Stock Items</div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (($user['role'] ?? '') === 'admin'): ?>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon"><i class="fas fa-users"></i></div>
                        <div class="ms-3">
                            <div class="stats-value"><?= (int) ($stats['total_users'] ?? 0) ?></div>
                            <div class="stats-label">Total Users</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon"><i class="fas fa-user-check"></i></div>
                        <div class="ms-3">
                            <div class="stats-value"><?= (int) ($stats['active_users'] ?? 0) ?></div>
                            <div class="stats-label">Active Users</div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fas fa-compass me-2"></i>Quick Access</h5>
            <small class="text-muted">Main pages</small>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="<?= site_url('/pos') ?>" class="btn btn-primary w-100 py-3">
                        <i class="fas fa-shopping-cart me-2"></i>Point of Sale
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="<?= site_url('/reports/sales') ?>" class="btn btn-outline-primary w-100 py-3">
                        <i class="fas fa-chart-line me-2"></i>Sales Reports
                    </a>
                </div>
                <?php if (($user['role'] ?? '') === 'admin'): ?>
                    <div class="col-md-4">
                        <a href="<?= site_url('/products') ?>" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-boxes-stacked me-2"></i>Stock Management
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layouts/footer') ?>

