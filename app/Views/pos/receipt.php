<?= $this->include('layouts/header') ?>

<style>
@media print {
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
        padding: 20px;
        background: white !important;
        color: black !important;
    }
    .no-print {
        display: none !important;
    }
}

.receipt-header {
    text-align: center;
    border-bottom: 2px dashed #333;
    padding-bottom: 20px;
    margin-bottom: 20px;
}

.receipt-shop-name {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 5px;
    color: #333 !important;
}

.receipt-shop-address {
    font-size: 12px;
    color: #666 !important;
    margin-bottom: 3px;
}

.receipt-sale-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.receipt-items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.receipt-items-table th,
.receipt-items-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.receipt-items-table th {
    background: #f8f9fa;
    font-weight: bold;
    color: #333 !important;
}

.receipt-items-table td {
    color: #333 !important;
}

.receipt-totals {
    text-align: right;
    margin-bottom: 20px;
}

.receipt-total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
    color: #333 !important;
}

.receipt-grand-total {
    font-weight: bold;
    font-size: 16px;
    border-top: 2px solid #333;
    padding-top: 10px;
    color: #333 !important;
}

.receipt-footer {
    text-align: center;
    border-top: 2px dashed #333;
    padding-top: 20px;
    margin-top: 20px;
    color: #666 !important;
}

.receipt-success-badge {
    background: #28a745;
    color: white !important;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
}
</style>

<div class="container-fluid py-4">
    <!-- Action Buttons (Hidden when printing) -->
    <div class="no-print mb-4">
        <div class="d-flex gap-2 justify-content-center">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print me-2"></i>Print Receipt
            </button>
            <a href="<?= site_url('/pos') ?>" class="btn btn-success">
                <i class="fas fa-shopping-cart me-2"></i>New Sale
            </a>
            <a href="<?= site_url('/reports/sales') ?>" class="btn btn-info">
                <i class="fas fa-chart-bar me-2"></i>View Reports
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body" id="receiptContent">
                    <!-- Success Badge -->
                    <div class="receipt-success-badge">
                        <i class="fas fa-check-circle me-2"></i>SALE COMPLETED
                    </div>

                    <!-- Receipt Header -->
                    <div class="receipt-header">
                        <div class="receipt-shop-name">Quick Puff Vape Shop</div>
                        <div class="receipt-shop-address">123 Vape Street, Manila, Philippines</div>
                        <div class="receipt-shop-address">Tel: (02) 1234-5678</div>
                        <div class="receipt-shop-address">Email: info@quickpuff.com</div>
                    </div>

                    <!-- Sale Information -->
                    <div class="receipt-sale-info">
                        <div class="row">
                            <div class="col-6">
                                <strong>Sale Code:</strong><br>
                                <span id="saleCode"><?= isset($sale['sale_code']) ? esc($sale['sale_code']) : 'SALE-' . date('Ymd') . '-' . str_pad($sale_id, 4, '0', STR_PAD_LEFT) ?></span>
                            </div>
                            <div class="col-6 text-end">
                                <strong>Date & Time:</strong><br>
                                <span><?= isset($sale['created_at']) ? date('M d, Y h:i A', strtotime($sale['created_at'])) : date('M d, Y h:i A') ?></span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-6">
                                <strong>Cashier:</strong><br>
                                <span><?= session()->get('username') ?? 'Staff' ?></span>
                            </div>
                            <div class="col-6 text-end">
                                <strong>Payment:</strong><br>
                                <span><?= isset($sale['payment_method']) ? ucfirst($sale['payment_method']) : 'Cash' ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <table class="receipt-items-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($sale['items']) && !empty($sale['items'])): ?>
                                <?php foreach ($sale['items'] as $item): ?>
                                    <tr>
                                        <td><?= esc($item['product_name'] ?? $item['name'] ?? 'Unknown Product') ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td>₱<?= number_format($item['price'], 2) ?></td>
                                        <td>₱<?= number_format(($item['price'] * $item['quantity']), 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align: center;">No items found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Totals -->
                    <div class="receipt-totals">
                        <div class="receipt-total-row">
                            <span>Subtotal:</span>
                            <span>₱<?= number_format(isset($sale['subtotal']) ? $sale['subtotal'] : 0, 2) ?></span>
                        </div>
                        <div class="receipt-total-row">
                            <span>Tax (10%):</span>
                            <span>₱<?= number_format(isset($sale['tax_amount']) ? $sale['tax_amount'] : 0, 2) ?></span>
                        </div>
                        <div class="receipt-total-row receipt-grand-total">
                            <span>Total:</span>
                            <span>₱<?= number_format(isset($sale['total_amount']) ? $sale['total_amount'] : 0, 2) ?></span>
                        </div>
                    </div>

                    <!-- Receipt Footer -->
                    <div class="receipt-footer">
                        <div class="mb-2">
                            <strong>Thank you for your purchase!</strong>
                        </div>
                        <div class="mb-2">
                            Please come again
                        </div>
                        <div>
                            <small>This is a computer-generated receipt</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Auto-redirect script (Hidden when printing) -->
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

// Start timer when page loads
document.addEventListener('DOMContentLoaded', function() {
    timerInterval = setInterval(updateTimer, 1000);
    updateTimer(); // Initial call
});
</script>

<?= $this->include('layouts/footer') ?>
