<?= $this->include('layouts/header') ?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title">Point of Sale</h1>
            <p class="page-subtitle">Process sales and manage transactions</p>
        </div>
        <button class="btn btn-danger" onclick="clearCart()">
            <i class="fas fa-trash me-2"></i>
            Clear Cart
        </button>
    </div>

    <div class="row">
        <!-- Products Section -->
        <div class="col-lg-8">
            <!-- Search and Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="searchProduct" 
                                   placeholder="Search products..." onkeyup="searchProducts()">
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="categoryFilter" onchange="filterByCategory()">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= esc($category) ?>"><?= esc($category) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-primary w-100" onclick="resetFilters()">
                                <i class="fas fa-redo me-2"></i>Reset Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-box me-2"></i>Products
                        <span class="badge bg-primary float-end" id="productCount"><?= count($products) ?></span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row" id="productsContainer">
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <div class="col-md-6 col-lg-4 mb-3 product-item" 
                                     data-category="<?= esc($product['category']) ?>" 
                                     data-name="<?= esc($product['name']) ?>">
                                    <div class="card h-100 product-card">
                                        <div class="card-body">
                                            <h6 class="card-title"><?= esc($product['name']) ?></h6>
                                            <p class="card-text">
                                                <small class="text-muted"><?= esc($product['category']) ?></small><br>
                                                <strong>₱<?= number_format($product['price'], 2) ?></strong><br>
                                                <small class="text-muted">Stock: <?= $product['stock_qty'] ?></small>
                                            </p>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-primary btn-sm w-100" 
                                                    onclick="addToCart(<?= $product['id'] ?>, '<?= esc($product['name']) ?>', <?= $product['price'] ?>, <?= $product['stock_qty'] ?>)"
                                                    <?= $product['stock_qty'] <= 0 ? 'disabled' : '' ?>>
                                                <i class="fas fa-plus me-1"></i>
                                                Add to Cart
                                                <?php if ($product['stock_qty'] <= 0): ?>
                                                    <span class="badge bg-danger ms-1">Out of Stock</span>
                                                <?php endif; ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12 text-center py-4">
                                <i class="fas fa-box fa-3x mb-3 d-block text-muted"></i>
                                <p class="text-muted">No products available</p>
                                <button class="btn btn-primary" onclick="location.reload()">
                                    <i class="fas fa-sync me-2"></i>Reload Page
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Shopping Cart
                        <span class="badge bg-primary float-end" id="cartCount">0</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="cartItems">
                        <p class="text-muted text-center">Your cart is empty</p>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <strong>Subtotal:</strong>
                            <span id="subtotal">₱0.00</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <strong>Tax (10%):</strong>
                            <span id="tax">₱0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h5>Total:</h5>
                            <h5 id="total">₱0.00</h5>
                        </div>
                    </div>
                    <button class="btn btn-success btn-lg w-100" onclick="processSale()" id="processSaleBtn" disabled>
                        <i class="fas fa-credit-card me-2"></i>
                        Process Sale
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Cart text contrast fixes */
#cartItems strong {
    color: #f4f7ff !important;
}

#cartItems small,
#cartItems .text-muted {
    color: #c9d1e3 !important;
}

#cartItems .mx-2 {
    color: #f4f7ff !important;
    font-weight: 600;
}

#subtotal,
#tax {
    color: #d7deef !important;
    font-weight: 600;
}

#total {
    color: #f4f7ff !important;
    font-weight: 800;
}

.card-footer strong,
.card-footer h5 {
    color: #f4f7ff !important;
}

#cartItems .btn-outline-danger {
    color: #ff6b7a !important;
    border-color: rgba(255, 107, 122, 0.65) !important;
}

#cartItems .btn-outline-danger:hover {
    color: #ffffff !important;
    background-color: rgba(255, 107, 122, 0.25) !important;
    border-color: rgba(255, 107, 122, 0.85) !important;
}

/* Force solid white receipt surfaces inside the sale receipt modal */
#receiptModal .modal-content,
#receiptModal .modal-header,
#receiptModal .modal-body {
    background-color: #ffffff !important;
    color: #111111 !important;
    border-color: #d7dbe3 !important;
}

#receiptModal .modal-footer {
    background-color: #1d2238 !important;
    border-top: 1px solid rgba(255, 255, 255, 0.18) !important;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
}

#receiptModal .modal-title,
#receiptModal .modal-body p,
#receiptModal .modal-body span,
#receiptModal .modal-body td,
#receiptModal .modal-body th {
    color: #111111 !important;
}

#receiptModal .receipt-print-btn {
    background: linear-gradient(135deg, #5d9bff 0%, #6f6bff 100%) !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 12px !important;
    font-weight: 700;
    padding: 0.6rem 1.35rem;
}

#receiptModal .receipt-print-btn:hover {
    transform: translateY(-1px);
    color: #ffffff !important;
}

#receiptModal .receipt-text-btn {
    background: transparent !important;
    color: #ffffff !important;
    border: none !important;
    font-weight: 700;
    padding: 0.6rem 0.75rem;
}

#receiptModal .receipt-text-btn:hover {
    color: #9fc0ff !important;
}

#receiptModal .btn-close {
    filter: none !important;
    opacity: 0.75;
}

#receiptModal .btn-close:hover {
    opacity: 1;
}
</style>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">
                    <i class="fas fa-receipt me-2"></i>Sale Receipt
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="receiptContent">
                <!-- Receipt content will be loaded here -->
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Generating receipt...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn receipt-print-btn" onclick="printReceipt()">
                    <i class="fas fa-print me-2"></i>Print Receipt
                </button>
                <button type="button" class="btn receipt-text-btn" onclick="newSale()">
                    <i class="fas fa-shopping-cart me-2"></i>New Sale
                </button>
                <button type="button" class="btn receipt-text-btn" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layouts/footer') ?>

<script>
let cart = [];

function addToCart(id, name, price, stock) {
    if (stock <= 0) {
        alert('This product is out of stock!');
        return;
    }

    const existingItem = cart.find(item => item.id === id);
    
    if (existingItem) {
        if (existingItem.quantity >= stock) {
            alert('Cannot add more than available stock!');
            return;
        }
        existingItem.quantity++;
    } else {
        cart.push({
            id: id,
            name: name,
            price: price,
            quantity: 1,
            stock: stock
        });
    }
    
    updateCart();
}

function updateCart() {
    const cartItems = document.getElementById('cartItems');
    const cartCount = document.getElementById('cartCount');
    const subtotal = document.getElementById('subtotal');
    const tax = document.getElementById('tax');
    const total = document.getElementById('total');
    const processBtn = document.getElementById('processSaleBtn');
    
    if (cart.length === 0) {
        cartItems.innerHTML = '<p class="text-muted text-center">Your cart is empty</p>';
        cartCount.textContent = '0';
        subtotal.textContent = '₱0.00';
        tax.textContent = '₱0.00';
        total.textContent = '₱0.00';
        processBtn.disabled = true;
        return;
    }
    
    let html = '';
    let subtotalAmount = 0;
    let totalItems = 0;
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        subtotalAmount += itemTotal;
        totalItems += item.quantity;
        
        html += `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <strong>${item.name}</strong><br>
                    <small class="text-muted">₱${item.price.toFixed(2)} x ${item.quantity}</small>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-outline-primary me-1" onclick="updateQuantity(${item.id}, -1)">
                        <i class="fas fa-minus"></i>
                    </button>
                    <span class="mx-2">${item.quantity}</span>
                    <button class="btn btn-sm btn-outline-primary me-2" onclick="updateQuantity(${item.id}, 1)">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${item.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <hr>
        `;
    });
    
    const taxAmount = subtotalAmount * 0.1;
    const totalAmount = subtotalAmount + taxAmount;
    
    cartItems.innerHTML = html;
    cartCount.textContent = totalItems;
    subtotal.textContent = `₱${subtotalAmount.toFixed(2)}`;
    tax.textContent = `₱${taxAmount.toFixed(2)}`;
    total.textContent = `₱${totalAmount.toFixed(2)}`;
    processBtn.disabled = false;
}

function updateQuantity(id, change) {
    const item = cart.find(item => item.id === id);
    if (item) {
        const newQuantity = item.quantity + change;
        if (newQuantity <= 0) {
            removeFromCart(id);
        } else if (newQuantity <= item.stock) {
            item.quantity = newQuantity;
            updateCart();
        } else {
            alert('Cannot add more than available stock!');
        }
    }
}

function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    updateCart();
}

function clearCart() {
    if (cart.length > 0 && confirm('Are you sure you want to clear the cart?')) {
        cart = [];
        updateCart();
    }
}

function processSale() {
    if (cart.length === 0) {
        alert('Your cart is empty!');
        return;
    }
    
    // Show loading state
    const processBtn = document.getElementById('processSaleBtn');
    const originalText = processBtn.innerHTML;
    processBtn.disabled = true;
    processBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    
    // Prepare request data
    const requestData = {
        cart: cart
    };
    
    console.log('Sending cart data:', requestData);
    
    // Submit sale to server
    fetch('<?= site_url('/pos/process-sale') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Process sale response:', data);
        
        if (data.success) {
            // Clear cart immediately
            cart = [];
            updateCart();
            
            // Show receipt modal
            showReceiptModal(data);
            
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error processing sale. Please try again.');
        processBtn.disabled = false;
        processBtn.innerHTML = originalText;
    });
}

function searchProducts() {
    const searchTerm = document.getElementById('searchProduct').value.toLowerCase();
    const products = document.querySelectorAll('.product-item');
    
    products.forEach(product => {
        const name = product.dataset.name.toLowerCase();
        if (name.includes(searchTerm)) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}

function filterByCategory() {
    const category = document.getElementById('categoryFilter').value;
    const products = document.querySelectorAll('.product-item');
    
    products.forEach(product => {
        if (category === '' || product.dataset.category === category) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}

function resetFilters() {
    document.getElementById('searchProduct').value = '';
    document.getElementById('categoryFilter').value = '';
    const products = document.querySelectorAll('.product-item');
    products.forEach(product => {
        product.style.display = 'block';
    });
}

// Show receipt modal with sale data
function showReceiptModal(saleData) {
    console.log('showReceiptModal called with data:', saleData);
    
    // Store sale data globally for print function
    window.currentSaleData = saleData;
    
    const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
    const receiptContent = document.getElementById('receiptContent');
    
    // Generate receipt HTML
    const receiptHTML = generateReceiptHTML(saleData);
    console.log('Generated receipt HTML length:', receiptHTML.length);
    
    receiptContent.innerHTML = receiptHTML;
    
    // Show modal
    modal.show();
    
    // Reset process button
    const processBtn = document.getElementById('processSaleBtn');
    processBtn.disabled = false;
    processBtn.innerHTML = '<i class="fas fa-credit-card me-2"></i>Process Sale';
}

// Generate receipt HTML
function generateReceiptHTML(saleData) {
    console.log('generateReceiptHTML called with:', saleData);
    
    // Check if sale data exists
    if (!saleData || !saleData.sale) {
        console.error('No sale data found');
        return `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Error: No sale data available
            </div>
        `;
    }
    
    const sale = saleData.sale;
    console.log('Sale object:', sale);
    
    // Check if items exist
    if (!sale.items || !Array.isArray(sale.items)) {
        console.error('No items found in sale data');
        return `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Error: No items found in sale data
            </div>
        `;
    }
    
    const items = sale.items;
    console.log('Items array:', items);
    
    let itemsHTML = '';
    items.forEach((item, index) => {
        console.log(`Processing item ${index}:`, item);
        const itemTotal = (parseFloat(item.price || 0) * (item.quantity || 0)).toFixed(2);
        itemsHTML += `
            <tr>
                <td>${item.name || 'Unknown Product'}</td>
                <td style="text-align: center;">${item.quantity || 0}</td>
                <td style="text-align: right;">₱${itemTotal}</td>
            </tr>
        `;
    });
    
    console.log('Generated items HTML:', itemsHTML);
    
    return `
        <style>
            #receiptContent {
                background-color: #ffffff !important;
            }
            .receipt-container {
                background: white;
                background-color: #ffffff !important;
                color: #000000;
                font-family: 'Courier New', monospace;
                font-size: 14px;
                line-height: 1.3;
                padding: 15px;
                max-width: 500px;
                margin: 0 auto;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            }
            .receipt-header {
                text-align: center;
                border-bottom: 2px dashed #000000;
                padding-bottom: 15px;
                margin-bottom: 15px;
                background-color: #ffffff !important;
            }
            .receipt-shop-name {
                font-size: 20px;
                font-weight: bold;
                margin-bottom: 5px;
                color: #000000;
            }
            .receipt-shop-address {
                font-size: 12px;
                color: #000000;
                margin-bottom: 3px;
            }
            .receipt-sale-info {
                background: #f8f8f8;
                background-color: #f8f8f8 !important;
                padding: 12px;
                border-radius: 5px;
                margin-bottom: 15px;
                border: 1px solid #000000;
            }
            .receipt-items-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 15px;
                font-size: 13px;
            }
            .receipt-items-table th,
            .receipt-items-table td {
                border: 1px solid #000000;
                padding: 8px;
                text-align: left;
                color: #000000;
                background-color: #ffffff !important;
            }
            .receipt-items-table th {
                background: #f0f0f0;
                background-color: #f0f0f0 !important;
                font-weight: bold;
                color: #000000;
            }
            .receipt-totals {
                text-align: right;
                margin-bottom: 15px;
                font-size: 13px;
                background-color: #ffffff !important;
            }
            .receipt-total-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 5px;
                color: #000000;
                background-color: #ffffff !important;
            }
            .receipt-grand-total {
                font-weight: bold;
                font-size: 15px;
                border-top: 2px solid #000000;
                padding-top: 8px;
                color: #000000;
            }
            .receipt-footer {
                text-align: center;
                border-top: 2px dashed #000000;
                padding-top: 15px;
                margin-top: 15px;
                color: #000000;
                font-size: 12px;
                background-color: #ffffff !important;
            }
            .receipt-success-badge {
                background: #000000;
                background-color: #000000 !important;
                color: #ffffff;
                padding: 10px 20px;
                border-radius: 5px;
                font-weight: bold;
                text-align: center;
                margin-bottom: 15px;
                font-size: 14px;
            }
            .receipt-label {
                font-weight: bold;
                color: #000000;
            }
            .receipt-value {
                color: #000000;
            }
            @media print {
                body * { visibility: hidden; }
                #receiptContent, #receiptContent * { visibility: visible; }
                #receiptContent { 
                    position: absolute; 
                    left: 0; 
                    top: 0; 
                    width: 100%; 
                    background: white !important;
                    background-color: #ffffff !important;
                }
                .modal-footer { display: none !important; }
                .modal-header { display: none !important; }
                .receipt-container {
                    max-width: 100%;
                    margin: 0;
                    padding: 5px;
                    border: none;
                    border-radius: 0;
                    box-shadow: none;
                    font-size: 10px;
                    line-height: 1.1;
                }
                .receipt-shop-name { font-size: 12px; }
                .receipt-shop-address { font-size: 8px; }
                .receipt-sale-info { padding: 5px; font-size: 9px; }
                .receipt-items-table { font-size: 9px; }
                .receipt-items-table th,
                .receipt-items-table td { padding: 2px; }
                .receipt-totals { font-size: 9px; }
                .receipt-grand-total { font-size: 10px; }
                .receipt-footer { font-size: 8px; }
                .receipt-success-badge { font-size: 10px; padding: 5px 10px; }
            }
            @media screen {
                .receipt-container {
                    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                    border-radius: 8px;
                }
            }
        </style>
        
        <div class="receipt-container">
            <div class="receipt-success-badge">
                ✓ SALE COMPLETED
            </div>

            <div class="receipt-header">
                <div class="receipt-shop-name">QUICK PUFF VAPE SHOP</div>
                <div class="receipt-shop-address">123 Vape Street, Manila, Philippines</div>
                <div class="receipt-shop-address">Tel: (02) 1234-5678</div>
                <div class="receipt-shop-address">Email: info@quickpuff.com</div>
            </div>

            <div class="receipt-sale-info">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span class="receipt-label">Sale Code:</span>
                    <span class="receipt-value">${sale.sale_code || 'N/A'}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span class="receipt-label">Date:</span>
                    <span class="receipt-value">${new Date().toLocaleDateString()}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span class="receipt-label">Time:</span>
                    <span class="receipt-value">${new Date().toLocaleTimeString()}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span class="receipt-label">Cashier:</span>
                    <span class="receipt-value">Staff</span>
                </div>
            </div>

            <table class="receipt-items-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Item</th>
                        <th style="width: 15%; text-align: center;">Qty</th>
                        <th style="width: 35%; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsHTML || '<tr><td colspan="3" style="text-align: center; color: #000000;">No items found</td></tr>'}
                </tbody>
            </table>

            <div class="receipt-totals">
                <div class="receipt-total-row">
                    <span class="receipt-label">Subtotal:</span>
                    <span class="receipt-value">₱${parseFloat(sale.subtotal || 0).toFixed(2)}</span>
                </div>
                <div class="receipt-total-row">
                    <span class="receipt-label">Tax (10%):</span>
                    <span class="receipt-value">₱${parseFloat(sale.tax_amount || 0).toFixed(2)}</span>
                </div>
                <div class="receipt-total-row receipt-grand-total">
                    <span class="receipt-label">TOTAL:</span>
                    <span class="receipt-value">₱${parseFloat(sale.total_amount || 0).toFixed(2)}</span>
                </div>
            </div>

            <div class="receipt-footer">
                <div style="margin-bottom: 8px; font-weight: bold;">
                    Thank you for your purchase!
                </div>
                <div style="margin-bottom: 8px;">
                    Please come again
                </div>
                <div>
                    <small>This is a computer-generated receipt</small>
                </div>
            </div>
        </div>
    `;
}

// Print receipt
function printReceipt() {
    console.log('Print receipt called');
    
    // Get the current sale data from the modal
    const receiptContent = document.getElementById('receiptContent');
    if (!receiptContent) {
        console.error('Receipt content not found');
        alert('Error: Receipt content not found');
        return;
    }
    
    // Get the receipt container HTML
    const receiptContainer = receiptContent.querySelector('.receipt-container');
    if (!receiptContainer) {
        console.error('Receipt container not found');
        alert('Error: Receipt container not found');
        return;
    }
    
    console.log('Receipt container found:', receiptContainer);
    
    // Create a new window for printing with small paper size
    const printWindow = window.open('', '_blank');
    
    // Get the current sale data from the global scope or recreate it
    const saleData = window.currentSaleData;
    if (!saleData) {
        console.error('No sale data available for printing');
        alert('Error: No sale data available for printing');
        return;
    }
    
    console.log('Sale data for printing:', saleData);
    
    // Generate print-optimized receipt HTML
    const printHTML = generatePrintReceiptHTML(saleData);
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Receipt - Quick Puff Vape Shop</title>
            <style>
                @page {
                    size: 80mm auto;
                    margin: 2mm;
                }
                body {
                    font-family: 'Courier New', monospace;
                    font-size: 12px;
                    line-height: 1.25;
                    margin: 0;
                    padding: 0;
                    color: #000000;
                    background: white;
                    width: 80mm;
                    max-width: 80mm;
                }
                .receipt-container {
                    width: 76mm;
                    padding: 2mm;
                    background: white;
                    color: #000000;
                    box-sizing: border-box;
                    margin: 0 auto;
                }
                .receipt-header {
                    text-align: center;
                    border-bottom: 2px dashed #000000;
                    padding-bottom: 4px;
                    margin-bottom: 4px;
                }
                .receipt-shop-name {
                    font-size: 14px;
                    font-weight: bold;
                    margin-bottom: 2px;
                    color: #000000;
                }
                .receipt-shop-address {
                    font-size: 10px;
                    color: #000000;
                    margin-bottom: 1px;
                }
                .receipt-sale-info {
                    background: #f8f8f8;
                    padding: 4px;
                    border: 1px solid #000000;
                    margin-bottom: 4px;
                    font-size: 11px;
                }
                .receipt-items-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 4px;
                    font-size: 11px;
                }
                .receipt-items-table th,
                .receipt-items-table td {
                    border: 1px solid #000000;
                    padding: 3px;
                    text-align: left;
                    color: #000000;
                }
                .receipt-items-table th {
                    background: #f0f0f0;
                    font-weight: bold;
                    color: #000000;
                    font-size: 10px;
                }
                .receipt-totals {
                    text-align: right;
                    margin-bottom: 4px;
                    font-size: 11px;
                }
                .receipt-total-row {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 2px;
                    color: #000000;
                }
                .receipt-grand-total {
                    font-weight: bold;
                    font-size: 13px;
                    border-top: 2px solid #000000;
                    padding-top: 3px;
                    color: #000000;
                }
                .receipt-footer {
                    text-align: center;
                    border-top: 2px dashed #000000;
                    padding-top: 4px;
                    margin-top: 4px;
                    color: #000000;
                    font-size: 10px;
                }
                .receipt-success-badge {
                    background: #000000;
                    color: #ffffff;
                    padding: 4px 8px;
                    text-align: center;
                    margin-bottom: 4px;
                    font-size: 11px;
                    font-weight: bold;
                }
                .receipt-label {
                    font-weight: bold;
                    color: #000000;
                }
                .receipt-value {
                    color: #000000;
                }
                @media print {
                    @page {
                        size: 80mm auto;
                        margin: 2mm;
                    }
                    body {
                        width: 80mm;
                        max-width: 80mm;
                        margin: 0;
                        padding: 0;
                    }
                    .receipt-container {
                        width: 76mm;
                        padding: 2mm;
                    }
                }
            </style>
        </head>
        <body>
            ${printHTML}
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    
    // Wait for content to load, then print
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 500);
}

// Generate print-optimized receipt HTML
function generatePrintReceiptHTML(saleData) {
    console.log('Generating print receipt HTML with data:', saleData);
    
    if (!saleData || !saleData.sale) {
        console.error('No sale data for print receipt');
        return '<div>Error: No sale data available</div>';
    }
    
    const sale = saleData.sale;
    const items = sale.items || [];
    
    let itemsHTML = '';
    items.forEach((item, index) => {
        const itemTotal = (parseFloat(item.price || 0) * (item.quantity || 0)).toFixed(2);
        itemsHTML += `
            <tr>
                <td>${item.name || 'Unknown Product'}</td>
                <td style="text-align: center;">${item.quantity || 0}</td>
                <td style="text-align: right;">₱${itemTotal}</td>
            </tr>
        `;
    });
    
    return `
        <div class="receipt-container">
            <div class="receipt-success-badge">
                ✓ SALE COMPLETED
            </div>

            <div class="receipt-header">
                <div class="receipt-shop-name">QUICK PUFF VAPE SHOP</div>
                <div class="receipt-shop-address">123 Vape Street, Manila, Philippines</div>
                <div class="receipt-shop-address">Tel: (02) 1234-5678</div>
                <div class="receipt-shop-address">Email: info@quickpuff.com</div>
            </div>

            <div class="receipt-sale-info">
                <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                    <span class="receipt-label">Sale Code:</span>
                    <span class="receipt-value">${sale.sale_code || 'N/A'}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                    <span class="receipt-label">Date:</span>
                    <span class="receipt-value">${new Date().toLocaleDateString()}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                    <span class="receipt-label">Time:</span>
                    <span class="receipt-value">${new Date().toLocaleTimeString()}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span class="receipt-label">Cashier:</span>
                    <span class="receipt-value">Staff</span>
                </div>
            </div>

            <table class="receipt-items-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Item</th>
                        <th style="width: 15%; text-align: center;">Qty</th>
                        <th style="width: 35%; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsHTML || '<tr><td colspan="3" style="text-align: center; color: #000000;">No items found</td></tr>'}
                </tbody>
            </table>

            <div class="receipt-totals">
                <div class="receipt-total-row">
                    <span class="receipt-label">Subtotal:</span>
                    <span class="receipt-value">₱${parseFloat(sale.subtotal || 0).toFixed(2)}</span>
                </div>
                <div class="receipt-total-row">
                    <span class="receipt-label">Tax (10%):</span>
                    <span class="receipt-value">₱${parseFloat(sale.tax_amount || 0).toFixed(2)}</span>
                </div>
                <div class="receipt-total-row receipt-grand-total">
                    <span class="receipt-label">TOTAL:</span>
                    <span class="receipt-value">₱${parseFloat(sale.total_amount || 0).toFixed(2)}</span>
                </div>
            </div>

            <div class="receipt-footer">
                <div style="margin-bottom: 3px; font-weight: bold;">
                    Thank you for your purchase!
                </div>
                <div style="margin-bottom: 3px;">
                    Please come again
                </div>
                <div>
                    <small>This is a computer-generated receipt</small>
                </div>
            </div>
        </div>
    `;
}

// Start new sale
function newSale() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('receiptModal'));
    modal.hide();
    // Cart is already cleared, ready for new sale
}
</script>
