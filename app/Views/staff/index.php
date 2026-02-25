<?= $this->include('layouts/header') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Staff Management</h1>
                <p class="page-subtitle">Manage system users and their roles</p>
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    For security reasons, you cannot edit or deactivate your own account
                </small>
            </div>
            <a href="<?= site_url('/staff/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Add New Staff
            </a>
        </div>
    </div>

    <!-- Staff Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Staff Members</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($staff)): ?>
                            <?php foreach ($staff as $member): ?>
                                <?php 
                                $currentUserId = session()->get('user_id');
                                $isCurrentUser = ($member['id'] == $currentUserId);
                                ?>
                                <tr class="<?= $isCurrentUser ? 'table-info' : '' ?>">
                                    <td><?= $member['id'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                                <?= strtoupper(substr($member['full_name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <?= esc($member['username']) ?>
                                                <?php if ($isCurrentUser): ?>
                                                    <small class="d-block text-muted">
                                                        <i class="fas fa-user-check"></i> You
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= esc($member['full_name']) ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= ucfirst($member['role']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($member['is_active']): ?>
                                            <span class="badge status-active">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Active
                                            </span>
                                        <?php else: ?>
                                            <span class="badge status-inactive">
                                                <i class="fas fa-times-circle me-1"></i>
                                                Inactive
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($member['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <?php 
                                            $currentUserId = session()->get('user_id');
                                            $isCurrentUser = ($member['id'] == $currentUserId);
                                            ?>
                                            
                                            <?php if (!$isCurrentUser): ?>
                                                <a href="<?= site_url('/staff/edit/' . $member['id']) ?>" 
                                                   class="btn btn-outline-primary btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <?php if ($member['is_active']): ?>
                                                    <a href="<?= site_url('/staff/deactivate/' . $member['id']) ?>" 
                                                       class="btn btn-outline-warning btn-sm" 
                                                       onclick="return confirm('Are you sure you want to deactivate this staff member?')"
                                                       title="Deactivate">
                                                        <i class="fas fa-user-slash"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?= site_url('/staff/activate/' . $member['id']) ?>" 
                                                       class="btn btn-outline-success btn-sm" 
                                                       onclick="return confirm('Are you sure you want to activate this staff member?')"
                                                       title="Activate">
                                                        <i class="fas fa-user-check"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <!-- Current user - show disabled buttons with tooltip -->
                                                <button class="btn btn-outline-secondary btn-sm" 
                                                        disabled 
                                                        title="Cannot edit your own account"
                                                        data-bs-toggle="tooltip">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                
                                                <?php if ($member['is_active']): ?>
                                                    <button class="btn btn-outline-secondary btn-sm" 
                                                            disabled 
                                                            title="Cannot deactivate your own account"
                                                            data-bs-toggle="tooltip">
                                                        <i class="fas fa-user-slash"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-outline-secondary btn-sm" 
                                                            disabled 
                                                            title="Cannot activate your own account"
                                                            data-bs-toggle="tooltip">
                                                        <i class="fas fa-user-check"></i>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if ($isCurrentUser): ?>
                                            <small class="text-muted d-block mt-1">
                                                <i class="fas fa-info-circle"></i> Your account
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-users fa-3x mb-3 d-block text-muted"></i>
                                    No staff members found. 
                                    <a href="<?= site_url('/staff/create') ?>" class="btn btn-primary btn-sm mt-2">
                                        Add your first staff member
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
