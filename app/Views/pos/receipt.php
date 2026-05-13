<?php $embedMode = isset($embed_mode) && $embed_mode === true; ?>
<?php if (!$embedMode): ?>
<?= $this->include('layouts/header') ?>
<?php endif; ?>

<style>
@media print {
    @page {
        size: A4 portrait;
        margin: 8mm;
    }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
    }

    body * {
        visibility: hidden;
    }

    #receiptContent, #receiptContent * {
        visibility: visible;
    }

    #receiptContent {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        max-width: 540px;
        margin: 0 auto;
        box-sizing: border-box;
        padding: 8px !important;
        background: white !important;
        color: black !important;
        font-size: 12px !important;
        line-height: 1.22 !important;
    }

    .receipt-success-badge { font-size: 14px !important; padding: 8px 10px !important; margin-bottom: 8px !important; }
    .receipt-shop-name { font-size: 20px !important; margin-bottom: 4px !important; }
    .receipt-shop-address { font-size: 10.5px !important; margin-bottom: 2px !important; }
    .receipt-header { padding-bottom: 8px !important; margin-bottom: 8px !important; }
    .receipt-sale-info { padding: 8px 10px !important; margin-bottom: 8px !important; }
    .receipt-info-row { font-size: 13px !important; margin-bottom: 3px !important; gap: 6px !important; }

    .receipt-items-table { width: 100% !important; table-layout: fixed !important; margin-bottom: 8px !important; }
    .receipt-items-table th,
    .receipt-items-table td {
        font-size: 11px !important;
        padding: 4px 4px !important;
        word-break: break-word !important;
        overflow-wrap: anywhere !important;
    }

    .receipt-items-table th:nth-child(1),
    .receipt-items-table td:nth-child(1) { width: 36% !important; }
    .receipt-items-table th:nth-child(2),
    .receipt-items-table td:nth-child(2) { width: 10% !important; text-align: center !important; }
    .receipt-items-table th:nth-child(3),
    .receipt-items-table td:nth-child(3) { width: 24% !important; text-align: right !important; }
    .receipt-items-table th:nth-child(4),
    .receipt-items-table td:nth-child(4) { width: 30% !important; text-align: right !important; }

    .receipt-total-row { font-size: 13px !important; margin-bottom: 2px !important; }
    .receipt-grand-total { font-size: 16px !important; padding-top: 5px !important; }
    .receipt-footer { padding-top: 8px !important; margin-top: 8px !important; }
    .receipt-footer .headline { font-size: 17px !important; margin-bottom: 3px !important; }
    .receipt-footer .subline { font-size: 13px !important; }

    .card,
    .card-body {
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    .no-print {
        display: none !important;
    }
}

<?php if ($embedMode): ?>
html, body {
    margin: 0 !important;
    padding: 0 !important;
    background: #ffffff !important;
}
.container-fluid,
.row {
    margin: 0 !important;
    padding: 0 !important;
}
#receiptContent {
    max-width: 760px;
    margin: 0 auto;
    padding: 12px 14px !important;
    font-size: 0.9rem !important;
}

/* Compact fit for Sales Report modal preview (iframe embed mode) */
.receipt-success-badge {
    font-size: 14px !important;
    padding: 8px 10px !important;
    margin-bottom: 10px !important;
}

.receipt-header {
    padding-bottom: 10px !important;
    margin-bottom: 10px !important;
}

.receipt-shop-name {
    font-size: 24px !important;
    margin-bottom: 6px !important;
}

.receipt-shop-address {
    font-size: 12px !important;
    margin-bottom: 3px !important;
}

.receipt-sale-info {
    padding: 10px 12px !important;
    margin-bottom: 10px !important;
}

.receipt-info-row {
    font-size: 16px !important;
    margin-bottom: 4px !important;
    gap: 10px !important;
}

.receipt-items-table {
    margin-bottom: 10px !important;
}

.receipt-items-table th,
.receipt-items-table td {
    font-size: 13px !important;
    padding: 7px 6px !important;
}

.receipt-total-row {
    font-size: 16px !important;
    margin-bottom: 3px !important;
}

.receipt-grand-total {
    font-size: 21px !important;
    padding-top: 7px !important;
}

.receipt-footer {
    margin-top: 10px !important;
    padding-top: 10px !important;
}

.receipt-footer .headline {
    font-size: 19px !important;
    margin-bottom: 4px !important;
}

.receipt-footer .subline {
    font-size: 14px !important;
}
<?php endif; ?>

#receiptContent {
    background-color: #ffffff !important;
    color: #111 !important;
    font-family: "Courier New", Courier, monospace;
}

#receiptContent * {
    color: inherit;
}

.receipt-success-badge {
    background: #000;
    color: #fff !important;
    padding: 10px 16px;
    border-radius: 6px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 18px;
    font-size: 20px;
}

.receipt-header {
    text-align: center;
    border-bottom: 3px dashed #111;
    padding-bottom: 18px;
    margin-bottom: 18px;
    background-color: #fff !important;
}

.receipt-shop-name {
    font-size: 38px;
    font-weight: bold;
    margin-bottom: 10px;
    line-height: 1.08;
}

.receipt-shop-address {
    font-size: 17px;
    color: #333 !important;
    margin-bottom: 6px;
}

.receipt-sale-info {
    background: #fff;
    border: 1px solid #111;
    border-radius: 6px;
    padding: 16px 18px;
    margin-bottom: 18px;
}

.receipt-info-row {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 6px;
    font-size: 22px;
}

.receipt-info-row:last-child {
    margin-bottom: 0;
}

.receipt-items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 18px;
}

.receipt-items-table th,
.receipt-items-table td {
    border: 1px solid #111;
    padding: 10px 8px;
    text-align: left;
    background-color: #fff !important;
    font-size: 20px;
}

.receipt-items-table th {
    font-weight: bold;
}

.receipt-items-table th:nth-child(2),
.receipt-items-table td:nth-child(2) {
    width: 70px;
    text-align: center;
}

.receipt-items-table th:nth-child(3),
.receipt-items-table td:nth-child(3) {
    width: 150px;
    text-align: right;
}

.receipt-items-table th:nth-child(4),
.receipt-items-table td:nth-child(4) {
    width: 180px;
    text-align: right;
}

.receipt-totals {
    margin-bottom: 18px;
}

.receipt-total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
    font-size: 22px;
}

.receipt-grand-total {
    font-weight: bold;
    font-size: 26px;
    border-top: 2px solid #111;
    padding-top: 10px;
}

.receipt-footer {
    text-align: center;
    border-top: 3px dashed #111;
    padding-top: 16px;
    margin-top: 18px;
}

.receipt-footer .headline {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 8px;
}

.receipt-footer .subline {
    font-size: 19px;
}

.receipt-actions {
    background-color: #1d2238 !important;
    border-top: 1px solid rgba(255, 255, 255, 0.18) !important;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
}

.receipt-print-btn {
    background: linear-gradient(135deg, #5d9bff 0%, #6f6bff 100%) !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 12px !important;
    font-weight: 700;
    padding: 0.6rem 1.35rem;
}

.receipt-print-btn:hover {
    transform: translateY(-1px);
    color: #ffffff !important;
}

.receipt-text-btn {
    background: transparent !important;
    color: #ffffff !important;
    border: none !important;
    font-weight: 700;
    padding: 0.6rem 0.75rem;
}

.receipt-text-btn:hover {
    color: #9fc0ff !important;
}
</style>

<div class="<?= $embedMode ? '' : 'container-fluid py-4' ?>">
    <div class="row justify-content-center">
        <div class="<?= $embedMode ? 'col-12' : 'col-md-8 col-lg-6' ?>">
            <div class="card" style="<?= $embedMode ? 'border: none; box-shadow: none; background: #ffffff !important; border-radius: 0;' : '' ?>">
                <div class="card-body" id="receiptContent">
                    <div class="receipt-success-badge">✓ SALE COMPLETED</div>

                    <div class="receipt-header">
                        <div class="receipt-shop-name">QuickPuff VapeShop</div>
                        <div class="receipt-shop-address">Bula, General Santos City, South Cotabato</div>
                        <div class="receipt-shop-address">Tel: 09365879409</div>
                        <div class="receipt-shop-address">Email: quickpuff@gmail.com</div>
                    </div>

                    <?php $receiptDateTime = isset($sale['created_at']) ? strtotime($sale['created_at']) : time(); ?>
                    <div class="receipt-sale-info">
                        <div class="receipt-info-row">
                            <strong>Sale Code:</strong>
                            <span id="saleCode"><?= isset($sale['sale_code']) ? esc($sale['sale_code']) : 'SALE-' . date('Ymd') . '-' . str_pad($sale_id, 4, '0', STR_PAD_LEFT) ?></span>
                        </div>
                        <div class="receipt-info-row">
                            <strong>Date:</strong>
                            <span><?= date('n/j/Y', $receiptDateTime) ?></span>
                        </div>
                        <div class="receipt-info-row">
                            <strong>Time:</strong>
                            <span><?= date('h:i:s A', $receiptDateTime) ?></span>
                        </div>
                        <div class="receipt-info-row">
                            <strong>Cashier:</strong>
                            <span><?= esc($sale['cashier_name'] ?? (session()->get('full_name') ?: session()->get('username') ?: 'Cashier')) ?></span>
                        </div>
                    </div>

                    <table class="receipt-items-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($sale['items']) && !empty($sale['items'])): ?>
                                <?php foreach ($sale['items'] as $item): ?>
                                    <tr>
                                        <td><?= esc($item['product_name'] ?? $item['name'] ?? 'Unknown Product') ?></td>
                                        <td><?= (int) ($item['quantity'] ?? 0) ?></td>
                                        <td>₱<?= number_format((float) ($item['price'] ?? 0), 2) ?></td>
                                        <td>₱<?= number_format((float) ($item['total'] ?? (($item['price'] ?? 0) * ($item['quantity'] ?? 0))), 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align: center;">No items found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="receipt-totals">
                        <div class="receipt-total-row">
                            <span>TOTAL:</span>
                            <span>₱<?= number_format((float) ($sale['total_amount'] ?? 0), 2) ?></span>
                        </div>
                        <div class="receipt-total-row">
                            <span>Amount Paid:</span>
                            <span>₱<?= number_format((float) ($sale['amount_paid'] ?? 0), 2) ?></span>
                        </div>
                        <div class="receipt-total-row receipt-grand-total">
                            <span>CHANGE:</span>
                            <span>₱<?= number_format((float) ($sale['change_amount'] ?? 0), 2) ?></span>
                        </div>
                    </div>

                    <div class="receipt-footer">
                        <div class="headline">Thank you for your purchase!</div>
                        <div class="subline">Please come again</div>
                    </div>
                </div>

                <?php if (!$embedMode): ?>
                    <div class="card-footer no-print receipt-actions">
                        <button type="button" class="btn receipt-print-btn" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print Receipt
                        </button>
                        <a href="<?= site_url('/pos') ?>" class="btn receipt-text-btn">
                            <i class="fas fa-shopping-cart me-2"></i>New Sale
                        </a>
                        <button type="button" class="btn receipt-text-btn"
                                onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href='<?= site_url('/pos') ?>'; }">
                            Close
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (!$embedMode): ?>
<script class="no-print">
let redirectTimer = 30;
let timerInterval;

function updateTimer() {
    const timerElement = document.getElementById('redirectTimer');
    if (timerElement) {
        timerElement.textContent = redirectTimer;
    }

    if (redirectTimer <= 0) {
        clearInterval(timerInterval);
        window.location.href = '<?= site_url('/pos') ?>';
    }
    redirectTimer--;
}

function cancelRedirect() {
    clearInterval(timerInterval);
    const noticeElement = document.getElementById('redirectNotice');
    if (noticeElement) {
        noticeElement.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    timerInterval = setInterval(updateTimer, 1000);
    updateTimer();
});
</script>
<?php endif; ?>

<?php if (!$embedMode): ?>
<?= $this->include('layouts/footer') ?>
<?php endif; ?>
