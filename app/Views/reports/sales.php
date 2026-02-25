<?= $this->include('layouts/header') ?>

<div class="container-fluid">
    <main class="px-md-4">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="page-title">Sales Report</h1>
                    <p class="page-subtitle">View and analyze sales data</p>
                </div>
                <div>
                    <?php if (!empty($sales)): ?>
                        <a href="<?= site_url('/reports/export-sales') ?>?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>" 
                           class="btn btn-success me-2">
                            <i class="fas fa-download me-2"></i>Export CSV
                        </a>
                    <?php endif; ?>
                    <button class="btn btn-primary" onclick="showFilterModal()">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
            </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Sales Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="GET" action="<?= site_url('/reports/sales') ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Filter Type</label>
                            <select class="form-select" id="filterType" onchange="toggleFilterFields()">
                                <option value="single" <?= $single_date ? 'selected' : '' ?>>Single Date</option>
                                <option value="range" <?= ($start_date && $end_date && !$single_date) ? 'selected' : '' ?>>Date Range</option>
                            </select>
                        </div>
                        
                        <div id="singleDateField" class="mb-3" <?= $single_date ? '' : 'style="display:none"' ?>>
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" value="<?= $single_date ?>">
                        </div>
                        
                        <div id="rangeFields" class="mb-3" <?= ($start_date && $end_date && !$single_date) ? '' : 'style="display:none"' ?>>
                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <?php if (!empty($sales)): ?>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-value"><?= $summary['total_sales'] ?></div>
                        <div class="stats-label">Total Sales</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-value">₱<?= number_format($summary['total_subtotal'], 2) ?></div>
                        <div class="stats-label">Subtotal</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-value">₱<?= number_format($summary['total_tax'], 2) ?></div>
                        <div class="stats-label">Tax</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-value">₱<?= number_format($summary['total_revenue'], 2) ?></div>
                        <div class="stats-label">Total Revenue</div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Sales Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                Sales Transactions
                <?php if ($start_date): ?>
                    <small class="text-muted">
                        (<?= date('F d, Y', strtotime($start_date)) ?>
                        <?= $end_date && $end_date != $start_date ? ' - ' . date('F d, Y', strtotime($end_date)) : '' ?>)
                    </small>
                <?php endif; ?>
            </h5>
        </div>
        <div class="card-body">
            <?php if (empty($sales)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-chart-line fa-3x mb-3 d-block text-muted"></i>
                    <p class="text-muted">No sales data found for the selected period</p>
                    <button class="btn btn-primary" onclick="showFilterModal()">Select Different Date</button>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sale Code</th>
                                <th>Date & Time</th>
                                <th>Cashier</th>
                                <th>Subtotal</th>
                                <th>Tax</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sales as $sale): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($sale['sale_code']) ?></strong>
                                    </td>
                                    <td>
                                        <?= date('M d, Y', strtotime($sale['created_at'])) ?><br>
                                        <small class="text-muted"><?= date('h:i A', strtotime($sale['created_at'])) ?></small>
                                    </td>
                                    <td><?= esc($sale['cashier_name']) ?></td>
                                    <td>₱<?= number_format($sale['subtotal'], 2) ?></td>
                                    <td>₱<?= number_format($sale['tax_amount'], 2) ?></td>
                                    <td><strong>₱<?= number_format($sale['total_amount'], 2) ?></strong></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= ucfirst($sale['payment_method']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('/pos/receipt/' . $sale['id']) ?>" 
                                           class="btn btn-sm btn-outline-primary" 
                                           target="_blank" 
                                           title="View Receipt">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    </main>
</div>

<script>
function showFilterModal() {
    const modal = new bootstrap.Modal(document.getElementById('filterModal'));
    modal.show();
}

function toggleFilterFields() {
    const filterType = document.getElementById('filterType').value;
    const singleDateField = document.getElementById('singleDateField');
    const rangeFields = document.getElementById('rangeFields');
    
    if (filterType === 'single') {
        singleDateField.style.display = 'block';
        rangeFields.style.display = 'none';
    } else {
        singleDateField.style.display = 'none';
        rangeFields.style.display = 'block';
    }
}

// Auto-show filter modal on first visit if no data
document.addEventListener('DOMContentLoaded', function() {
    <?php if (empty($sales) && !$start_date): ?>
        showFilterModal();
    <?php endif; ?>
});
</script>

<?= $this->include('layouts/footer') ?>
