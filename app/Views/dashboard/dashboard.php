<?= $this->include('layouts/header') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Welcome back, <?= esc($user['full_name']) ?>! Here's your system overview.</p>
    </div>

    <!-- Stats Cards Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stats-value"><?= $stats['total_products'] ?></div>
                        <div class="stats-label">Total Products</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stats-value"><?= $stats['low_stock_products'] ?></div>
                        <div class="stats-label">Low Stock Items</div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($user['role'] === 'admin'): ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="ms-3">
                            <div class="stats-value"><?= $stats['total_users'] ?></div>
                            <div class="stats-label">Total Users</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="ms-3">
                            <div class="stats-value"><?= $stats['active_users'] ?></div>
                            <div class="stats-label">Active Users</div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Quick Actions Section -->
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                    <small class="text-muted">Common tasks</small>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="<?= site_url('/pos') ?>" class="btn btn-primary btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-shopping-cart mb-2" style="font-size: 2rem;"></i>
                                <span>Point of Sale</span>
                                <small class="text-white-50">Process sales</small>
                            </a>
                        </div>
                        <?php if ($user['role'] === 'admin'): ?>
                            <div class="col-md-4">
                                <a href="<?= site_url('/staff') ?>" class="btn btn-outline-primary btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="fas fa-users mb-2" style="font-size: 2rem;"></i>
                                    <span>Manage Staff</span>
                                    <small class="text-muted">User management</small>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-4">
                            <a href="<?= site_url('/products') ?>" class="btn btn-success btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-box-open mb-2" style="font-size: 2rem;"></i>
                                <span>Products</span>
                                <small class="text-white-50">Manage inventory</small>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Secondary Actions -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <a href="<?= site_url('/orders') ?>" class="btn btn-outline-secondary btn-lg w-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-receipt me-2"></i>
                                <span>View Orders</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-outline-info btn-lg w-100 d-flex align-items-center justify-content-center" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>
                                <span>Print Report</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Information Section -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>System Information
                    </h5>
                    <small class="text-muted">System status</small>
                </div>
                <div class="card-body">
                    <div class="system-info-list">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-tag me-2"></i>Version
                            </div>
                            <div class="info-value">Quick Puff v1.0.0</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-code me-2"></i>PHP Version
                            </div>
                            <div class="info-value"><?= PHP_VERSION ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-database me-2"></i>Database
                            </div>
                            <div class="info-value">
                                <span class="badge bg-success">Connected</span>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-user me-2"></i>Role
                            </div>
                            <div class="info-value"><?= ucfirst($user['role']) ?></div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-clock me-2"></i>Last Login
                            </div>
                            <div class="info-value"><?= date('M j, Y H:i') ?></div>
                        </div>
                        
                        <?php if ($user['role'] === 'admin'): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-fingerprint me-2"></i>Session
                                </div>
                                <div class="info-value">
                                    <small class="text-muted"><?= substr(session_id(), 0, 8) ?>...</small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information Row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Recent Activity
                    </h5>
                    <small class="text-muted">Latest system updates</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="activity-content">
                                    <h6>Inventory Status</h6>
                                    <p class="mb-0">
                                        <?php if ($stats['low_stock_products'] > 0): ?>
                                            <span class="text-warning"><?= $stats['low_stock_products'] ?> items need restocking</span>
                                        <?php else: ?>
                                            <span class="text-success">All products are well stocked</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="activity-content">
                                    <h6>Sales Ready</h6>
                                    <p class="mb-0">
                                        <span class="text-info">Point of Sale system is operational</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($user['role'] === 'admin'): ?>
                            <div class="col-md-4">
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h6>User Management</h6>
                                        <p class="mb-0">
                                            <span class="text-primary"><?= $stats['active_users'] ?> of <?= $stats['total_users'] ?> users active</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="col-md-4">
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h6>System Health</h6>
                                        <p class="mb-0">
                                            <span class="text-success">All systems operational</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.system-info-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    display: flex;
    align-items: center;
    font-weight: 500;
    color: #6c757d;
}

.info-value {
    font-weight: 600;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    padding: 15px;
    border-radius: 8px;
    background: rgba(0,0,0,0.02);
    margin-bottom: 15px;
}

.activity-item:last-child {
    margin-bottom: 0;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(0,123,255,0.1);
    color: #007bff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
}

.activity-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}

.activity-content p {
    font-size: 0.9rem;
}

.btn-lg {
    padding: 20px 15px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-lg:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
</style>

<?= $this->include('layouts/footer') ?>
