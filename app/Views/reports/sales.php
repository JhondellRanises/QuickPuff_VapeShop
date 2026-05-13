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
                        <button type="button" class="btn btn-success me-2" onclick="printSalesReport()">
                            <i class="fas fa-print me-2"></i>Print Report
                        </button>
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
                <form id="salesFilterForm" method="GET" action="<?= site_url('/reports/sales') ?>">
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
                            <input type="date" id="singleDateInput" name="date" class="form-control" value="<?= $single_date ?>">
                        </div>

                        <div id="rangeFields" class="mb-3" <?= ($start_date && $end_date && !$single_date) ? '' : 'style="display:none"' ?>>
                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" id="startDateInput" name="start_date" class="form-control" value="<?= $start_date ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" id="endDateInput" name="end_date" class="form-control" value="<?= $end_date ?>">
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
            <div class="col-md-6 mb-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-value"><?= $summary['total_sales'] ?></div>
                        <div class="stats-label">Total Sales</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-value">PHP <?= number_format($summary['total_revenue'], 2) ?></div>
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
                Sales Transaction History
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
                                <th>Items (Product/Flavor/Puffs)</th>
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
                                    <td><small class="sales-items-text"><?= esc($sale['items_summary'] ?? 'No items recorded') ?></small></td>
                                    <td><strong>PHP <?= number_format($sale['subtotal'], 2) ?></strong></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= ucfirst($sale['payment_method']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button"
                                           class="btn btn-sm btn-outline-primary"
                                           onclick="showReceiptModal('<?= site_url('/pos/receipt/' . $sale['id']) ?>', '<?= esc($sale['sale_code'], 'js') ?>')"
                                           title="View Receipt">
                                            <i class="fas fa-receipt"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (isset($pager) && $pager): ?>
                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                        <div></div>
                        <div class="d-flex align-items-center gap-2">
                            <?= $pager->links('sales', 'sales_numeric') ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptModalLabel">Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe id="receiptModalFrame"
                            src="about:blank"
                            title="Receipt Preview"
                            style="width: 100%; height: 70vh; border: 0; background: #ffffff;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="printReceiptFromModal()">
                        <i class="fas fa-print me-2"></i>Reprint Receipt
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Exit</button>
                </div>
            </div>
        </div>
    </div>
    </main>
</div>

<div id="salesPrintArea" style="display: none;"></div>

<script>
function showFilterModal() {
    const modal = new bootstrap.Modal(document.getElementById('filterModal'));
    modal.show();
}

function toggleFilterFields() {
    const filterType = document.getElementById('filterType').value;
    const singleDateField = document.getElementById('singleDateField');
    const rangeFields = document.getElementById('rangeFields');
    const singleDateInput = document.getElementById('singleDateInput');
    const startDateInput = document.getElementById('startDateInput');
    const endDateInput = document.getElementById('endDateInput');

    if (filterType === 'single') {
        singleDateField.style.display = 'block';
        rangeFields.style.display = 'none';
        if (startDateInput) startDateInput.value = '';
        if (endDateInput) endDateInput.value = '';
        if (singleDateInput) singleDateInput.disabled = false;
        if (startDateInput) startDateInput.disabled = true;
        if (endDateInput) endDateInput.disabled = true;
    } else {
        singleDateField.style.display = 'none';
        rangeFields.style.display = 'block';
        if (singleDateInput) singleDateInput.value = '';
        if (singleDateInput) singleDateInput.disabled = true;
        if (startDateInput) startDateInput.disabled = false;
        if (endDateInput) endDateInput.disabled = false;
    }
}

function showReceiptModal(url, saleCode) {
    const receiptModalEl = document.getElementById('receiptModal');
    const receiptModalTitle = document.getElementById('receiptModalLabel');
    const receiptModalFrame = document.getElementById('receiptModalFrame');

    receiptModalTitle.textContent = saleCode ? ('Receipt - ' + saleCode) : 'Receipt';
    receiptModalFrame.src = url + (url.includes('?') ? '&embed=1' : '?embed=1');

    const modal = new bootstrap.Modal(receiptModalEl);
    modal.show();
}

function printReceiptFromModal() {
    const receiptModalFrame = document.getElementById('receiptModalFrame');
    if (!receiptModalFrame || !receiptModalFrame.contentWindow) {
        alert('Receipt preview is not ready yet.');
        return;
    }

    try {
        const frameWindow = receiptModalFrame.contentWindow;
        const frameDocument = frameWindow.document;

        if (!frameDocument || frameDocument.readyState !== 'complete') {
            alert('Receipt is still loading. Please try again in a moment.');
            return;
        }

        if (typeof frameWindow.printReceipt === 'function') {
            frameWindow.printReceipt();
            return;
        }

        frameWindow.focus();
        frameWindow.print();
    } catch (error) {
        console.error('Unable to print receipt from modal:', error);
        alert('Unable to print receipt right now. Please close and open the receipt again.');
    }
}

function printSalesReport() {
    const originalDocumentTitle = document.title;
    document.title = '';
    const reportTitle = document.querySelector('.page-title')?.textContent || 'Sales Report';
    const printedAt = new Date().toLocaleString();
    const summaryCardsData = Array.from(document.querySelectorAll('.row.mb-4 .stats-card')).map((card) => {
        return {
            value: card.querySelector('.stats-value')?.textContent?.trim() || '',
            label: card.querySelector('.stats-label')?.textContent?.trim() || ''
        };
    }).filter((item) => item.value !== '' || item.label !== '');

    const summaryCards = summaryCardsData.map((item) => `
        <div class="print-summary-card">
            <div class="print-summary-value">${item.value}</div>
            <div class="print-summary-label">${item.label}</div>
        </div>
    `).join('');
    const sourceTable = document.querySelector('.table');
    let tableHtml = '<p>No sales data.</p>';

    if (sourceTable) {
        const printTable = sourceTable.cloneNode(true);
        const headerCells = Array.from(printTable.querySelectorAll('thead th'));
        const removeIndexes = [];

        headerCells.forEach((th, idx) => {
            const label = (th.textContent || '').trim().toLowerCase();
            if (label === 'payment' || label === 'actions') {
                removeIndexes.push(idx);
            }
        });

        removeIndexes.sort((a, b) => b - a).forEach((idx) => {
            printTable.querySelectorAll('tr').forEach((row) => {
                const cells = row.children;
                if (cells[idx]) {
                    cells[idx].remove();
                }
            });
        });

        printTable.classList.add('print-sales-table');
        const colgroup = document.createElement('colgroup');
        ['18%', '15%', '16%', '37%', '14%'].forEach((w) => {
            const col = document.createElement('col');
            col.style.width = w;
            colgroup.appendChild(col);
        });
        printTable.prepend(colgroup);

        tableHtml = printTable.outerHTML;
    }

    const printArea = document.getElementById('salesPrintArea');
    if (!printArea) {
        return;
    }

    // Keep print area at <body> level to avoid hidden/clipped parent containers.
    if (printArea.parentElement !== document.body) {
        document.body.appendChild(printArea);
    }

    printArea.innerHTML = `
        <style>
            #salesPrintArea { font-family: Calibri, Arial, sans-serif; color: #000 !important; padding: 22px; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            #salesPrintArea h1 { margin: 0 0 4px 0; font-size: 34px; font-weight: 800; color: #000 !important; -webkit-text-fill-color: #000 !important; opacity: 1 !important; }
            #salesPrintArea .print-summary-row { display: flex; gap: 12px; margin-bottom: 14px; }
            #salesPrintArea .print-summary-card { border: 1.2px solid #000 !important; border-radius: 0; padding: 10px 12px; flex: 1; min-height: 86px; display: flex; flex-direction: column; justify-content: center; }
            #salesPrintArea .print-summary-value { font-size: 34px; font-weight: 800; margin-bottom: 4px; color: #000 !important; -webkit-text-fill-color: #000 !important; line-height: 1.05; text-align: center; opacity: 1 !important; }
            #salesPrintArea .print-summary-label { font-size: 13px; font-weight: 800; color: #000 !important; -webkit-text-fill-color: #000 !important; text-transform: uppercase; text-align: center; opacity: 1 !important; }
            #salesPrintArea .print-sales-table { width: 100%; max-width: 100%; box-sizing: border-box; border-collapse: collapse !important; border-spacing: 0 !important; font-size: 13px; table-layout: fixed; background: #fff; border: 1.2px solid #000 !important; border-radius: 0 !important; overflow: visible !important; }
            #salesPrintArea .print-sales-table th,
            #salesPrintArea .print-sales-table td { border: 1.2px solid #000 !important; padding: 7px 8px; text-align: left; vertical-align: top; word-wrap: break-word; color: #000 !important; -webkit-text-fill-color: #000 !important; opacity: 1 !important; line-height: 1.35; }
            #salesPrintArea .print-sales-table th { background: #efefef !important; font-weight: 800; letter-spacing: 0.2px; border: 1.2px solid #000 !important; color: #000 !important; -webkit-text-fill-color: #000 !important; }
            #salesPrintArea .print-sales-table tr > *:last-child { border-right: 1.2px solid #000 !important; }
            #salesPrintArea .print-sales-table tbody tr:last-child td { border-bottom: 1.2px solid #000 !important; }
            #salesPrintArea .print-sales-table tr:first-child th { border-top: 1.2px solid #000 !important; }
            #salesPrintArea .print-sales-table tr > *:first-child { border-left: 1.2px solid #000 !important; }
            #salesPrintArea tbody tr:nth-child(even) { background: #fafafa; }
            #salesPrintArea .print-sales-table td,
            #salesPrintArea .print-sales-table td *,
            #salesPrintArea .print-sales-table th,
            #salesPrintArea .print-sales-table th * {
                color: #000 !important;
                opacity: 1 !important;
                font-weight: 600 !important;
            }
            #salesPrintArea .badge, #salesPrintArea .btn, #salesPrintArea .btn-sm, #salesPrintArea .btn-outline-primary { display: none !important; }
            #salesPrintArea small { color: #000 !important; -webkit-text-fill-color: #000 !important; opacity: 1 !important; font-size: 12px !important; line-height: 1.25; display: inline-block; }
            #salesPrintArea .print-date { margin: 0 0 10px 0; font-size: 12px; color: #000 !important; -webkit-text-fill-color: #000 !important; opacity: 1 !important; font-weight: 700; }
            @page { size: A4 portrait; margin: 10mm; }
            @media print {
                #salesPrintArea .print-sales-table,
                #salesPrintArea .print-sales-table th,
                #salesPrintArea .print-sales-table td {
                    border: 1.2px solid #000 !important;
                }
            }
        </style>
        <h1>${reportTitle}</h1>
        <div class="print-date">Printed Date: ${printedAt}</div>
        <div class="print-summary-row">${summaryCards}</div>
        <div>${tableHtml}</div>
    `;

    document.body.classList.add('printing-sales-report');
    window.focus();
    setTimeout(() => {
        window.print();
        setTimeout(() => {
            document.title = originalDocumentTitle;
        }, 200);
    }, 50);
}

// Auto-show filter modal on first visit if no data
document.addEventListener('DOMContentLoaded', function() {
    <?php if (empty($sales) && !$start_date): ?>
        showFilterModal();
    <?php endif; ?>

    const receiptModalEl = document.getElementById('receiptModal');
    if (receiptModalEl) {
        receiptModalEl.addEventListener('hidden.bs.modal', function () {
            const receiptModalFrame = document.getElementById('receiptModalFrame');
            if (receiptModalFrame) {
                receiptModalFrame.src = 'about:blank';
            }
        });
    }

    toggleFilterFields();

    const salesFilterForm = document.getElementById('salesFilterForm');
    if (salesFilterForm) {
        salesFilterForm.addEventListener('submit', function () {
            const filterType = document.getElementById('filterType')?.value;
            const singleDateInput = document.getElementById('singleDateInput');
            const startDateInput = document.getElementById('startDateInput');
            const endDateInput = document.getElementById('endDateInput');

            if (filterType === 'single') {
                if (singleDateInput) singleDateInput.disabled = false;
                if (startDateInput) startDateInput.disabled = true;
                if (endDateInput) endDateInput.disabled = true;
            } else {
                if (singleDateInput) singleDateInput.disabled = true;
                if (startDateInput) startDateInput.disabled = false;
                if (endDateInput) endDateInput.disabled = false;
            }
        });
    }
});

function cleanupSalesPrintState() {
    document.body.classList.remove('printing-sales-report');
    const printArea = document.getElementById('salesPrintArea');
    if (printArea) {
        printArea.innerHTML = '';
    }
}

window.addEventListener('afterprint', cleanupSalesPrintState);
</script>

<style>
.sales-pagination .page-link {
    background: transparent;
    border: none;
    color: #d7d9e4;
    border-radius: 999px;
    min-width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 0.75rem;
}

.sales-pagination .page-item.active .page-link {
    background: rgba(255, 255, 255, 0.16);
    color: #ffffff;
    font-weight: 700;
}

.sales-pagination .page-item.disabled .page-link {
    color: rgba(215, 217, 228, 0.45);
}

.sales-pagination .page-link:hover {
    background: rgba(255, 255, 255, 0.08);
    color: #ffffff;
}

.sales-items-text {
    color: #ffffff !important;
    opacity: 1 !important;
    font-weight: 500 !important;
}

td .sales-items-text,
td .sales-items-text * {
    color: #ffffff !important;
    opacity: 1 !important;
}

/* Keep receipt footer buttons clickable (iframe must not overlap footer) */
#receiptModal .modal-content {
    overflow: hidden;
}

#receiptModal .modal-body {
    overflow: auto;
    position: relative;
    z-index: 2;
    background: #ffffff;
}

#receiptModal .modal-footer {
    position: relative;
    z-index: 5;
    pointer-events: auto !important;
    background: #020a2e;
}

#receiptModalFrame {
    display: block;
    width: 100%;
    height: 70vh;
    border: 0;
}

@media print {
    body.printing-sales-report > *:not(#salesPrintArea) {
        display: none !important;
    }

    body.printing-sales-report #salesPrintArea {
        display: block !important;
        position: static !important;
        width: auto !important;
        min-height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
    }
}

/* Improve date icon visibility in dark filter modal */
#filterModal input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1) brightness(1.9) contrast(1.2);
    opacity: 1 !important;
    cursor: pointer;
}

#filterModal input[type="date"] {
    color-scheme: dark;
}
</style>

<?= $this->include('layouts/footer') ?>
