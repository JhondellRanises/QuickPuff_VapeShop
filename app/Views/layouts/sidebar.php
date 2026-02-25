<!-- Sidebar -->
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($active) && $active === 'dashboard') ? 'active' : '' ?>" 
                           href="<?= site_url('/dashboard') ?>">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($active) && $active === 'pos') ? 'active' : '' ?>" 
                           href="<?= site_url('/pos') ?>">
                            <i class="fas fa-shopping-cart"></i>
                            Point of Sale
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= (isset($active) && $active === 'reports') ? 'active' : '' ?>" 
                           href="<?= site_url('/reports/sales') ?>">
                            <i class="fas fa-chart-line"></i>
                            Reports
                        </a>
                    </li>

                    <?php if (session()->get('role') === 'admin'): ?>
                        <li class="nav-item mt-3">
                            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                                <span>Administration</span>
                            </h6>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= (isset($active) && $active === 'staff') ? 'active' : '' ?>" 
                               href="<?= site_url('/staff') ?>">
                                <i class="fas fa-users"></i>
                                Staff Management
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?= (isset($active) && $active === 'products') ? 'active' : '' ?>" 
                               href="<?= site_url('/products') ?>">
                                <i class="fas fa-boxes-stacked"></i>
                                Stock Management
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <hr class="my-3">

                <div class="px-3 py-2">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Quick Puff Vape Shop System v1.0
                    </small>
                </div>
            </div>
        </nav>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?= isset($page_title) ? esc($page_title) : 'Dashboard' ?></h1>
                
                <?php if (isset($breadcrumb)): ?>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <?php foreach ($breadcrumb as $key => $crumb): ?>
                                <li class="breadcrumb-item <?= $key === array_key_last($breadcrumb) ? 'active' : '' ?>">
                                    <?php if ($key === array_key_last($breadcrumb)): ?>
                                        <?= $crumb ?>
                                    <?php else: ?>
                                        <a href="#" class="text-muted"><?= $crumb ?></a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </nav>
                <?php endif; ?>
            </div>

            <!-- Page content starts here -->
