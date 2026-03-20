<?= $this->include('layouts/header') ?>

<?php
$defaultVapeSvg = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 340'>"
    . "<defs><linearGradient id='grad' x1='0' y1='0' x2='1' y2='1'>"
    . "<stop offset='0%' stop-color='#243251'/><stop offset='100%' stop-color='#4f73d8'/>"
    . "</linearGradient></defs>"
    . "<rect width='600' height='340' fill='url(#grad)'/>"
    . "<g fill='none' stroke='#e7edff' stroke-width='14' stroke-linecap='round' stroke-linejoin='round' opacity='0.95'>"
    . "<rect x='258' y='72' width='84' height='188' rx='28'/>"
    . "<path d='M286 58h28'/><path d='M272 234h56'/>"
    . "</g>"
    . "<text x='300' y='302' font-family='Arial, sans-serif' font-size='28' fill='#f2f6ff' text-anchor='middle'>VAPE PRODUCT</text>"
    . "</svg>";
$defaultVapeImage = 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($defaultVapeSvg);
?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title">Point of Sale</h1>
            <p class="page-subtitle">Process sales and manage transactions</p>
        </div>
    </div>

    <div class="row">
        <!-- Products Section -->
        <div class="col-lg-12" id="mainContent">
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
                                <?php
                                    $productImage = trim((string) ($product['image_url'] ?? ''));
                                    if ($productImage === '') {
                                        $productImage = $defaultVapeImage;
                                    } elseif (!preg_match('#^(?:https?:)?//#i', $productImage) && strpos($productImage, 'data:image') !== 0) {
                                        $productImage = base_url(ltrim($productImage, '/'));
                                    }
                                ?>
                                <div class="col-md-6 col-lg-4 mb-3 product-item" 
                                     data-category="<?= esc($product['category']) ?>" 
                                     data-name="<?= esc($product['name']) ?>"
                                     data-brand="<?= esc($product['brand'] ?? '') ?>">
                                    <div class="card h-100 product-card">
                                        <div class="product-image-wrap">
                                            <img src="<?= esc($productImage, 'attr') ?>"
                                                 class="product-image"
                                                 alt="<?= esc($product['name']) ?> image"
                                                 loading="lazy"
                                                 onerror="this.onerror=null;this.src='<?= esc($defaultVapeImage, 'attr') ?>';">
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title"><?= esc($product['name']) ?></h6>
                                            <p class="card-text">
                                                <small class="text-muted"><?= esc($product['category']) ?></small>
                                                <?php if (!empty($product['brand'])): ?>
                                                    <br><small class="text-muted">Brand: <?= esc($product['brand']) ?></small>
                                                <?php endif; ?>
                                                <br><strong><?= $product['price_display'] ?></strong>
                                                <br><small class="text-muted">Stock: <?= $product['total_stock'] ?></small>
                                                <?php if ($product['variant_count'] > 1): ?>
                                                    <br><small class="text-info"><?= $product['variant_count'] ?> variants available</small>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-primary btn-sm w-100" 
                                                    onclick="showVariantModal('<?= esc($product['name']) ?>', '<?= esc($product['brand'] ?? '') ?>', '<?= esc($product['category']) ?>')"
                                                    <?= $product['total_stock'] <= 0 ? 'disabled' : '' ?>>
                                                <i class="fas fa-plus me-1"></i>
                                                Add to Cart
                                                <?php if ($product['total_stock'] <= 0): ?>
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

        <!-- Cart Section - Auto-hide Sliding Sidebar -->
        <div class="col-lg-4">
            <!-- Cart Indicator (visible when cart is hidden) -->
            <div class="cart-indicator" id="cartIndicator">
                <div class="indicator-content">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count-indicator" id="cartCountIndicator">0</span>
                </div>
                <div class="indicator-arrow">
                    <i class="fas fa-chevron-left"></i>
                </div>
            </div>

            <!-- Cart Container -->
            <div class="cart-sidebar" id="cartSidebar">
                <!-- Pin/Unpin Toggle -->
                <button class="cart-pin-toggle" id="cartPinToggle" title="Pin/Unpin Cart">
                    <i class="fas fa-thumbtack" id="pinIcon"></i>
                </button>

                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Shopping Cart
                            </h5>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-sm btn-outline-danger" onclick="clearCart()" title="Clear Cart">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <span class="badge bg-primary" id="cartCount">0</span>
                            </div>
                        </div>
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
                        <div class="mb-3">
                            <label for="customerBirthDate" class="form-label">
                                Customer Birth Date <span class="text-danger">*</span>
                            </label>
                            <div class="row g-2">
                                <div class="col-12">
                                    <input type="date"
                                           class="form-control"
                                           id="customerBirthDate"
                                           max="<?= date('Y-m-d') ?>"
                                           onchange="verifyAgeDisplay()"
                                           required>
                                </div>
                                <div class="col-12">
                                    <div class="d-grid">
                                        <button type="button" class="btn btn-info btn-sm text-white" onclick="quickAgeVerify('18')">
                                            <i class="fas fa-check me-1"></i>
                                            Verify 18+ Years Old
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="ageVerificationResult" class="mt-2" style="display: none;">
                                <!-- Age verification result will be displayed here -->
                            </div>
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-info-circle me-1"></i>
                                Age restriction: 18 years old and above only.
                            </small>
                        </div>
                        <div class="mb-3">
                            <label for="amountPaid" class="form-label">
                                Amount Paid <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number"
                                       class="form-control"
                                       id="amountPaid"
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       oninput="debouncedCalculateChange()"
                                       required>
                            </div>
                            <small class="text-muted d-block mt-1">Enter the amount of money the customer gives.</small>
                            <small id="changeDisplay" class="text-success d-block mt-1" style="display: none;">
                                <strong>Change: ₱<span id="changeAmount">0.00</span></strong>
                            </small>
                        </div>
                        <button class="btn btn-success btn-lg w-100 text-white fw-bold shadow-sm" onclick="processSale()" id="processSaleBtn" disabled>
                            <i class="fas fa-credit-card me-2"></i>
                            Process Sale
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Product card image support with default vape fallback */
.product-card .product-image-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    overflow: hidden;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    background: linear-gradient(135deg, rgba(93, 155, 255, 0.22), rgba(111, 107, 255, 0.32));
}

.product-card .product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

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

/* Auto-hide Sliding Cart Styles */
.cart-sidebar {
    position: fixed;
    top: 120px; /* Moved down further to avoid covering header */
    right: -450px; /* Increased from 400px to 450px */
    width: 450px; /* Increased from 400px to 450px */
    height: auto;
    max-height: calc(100vh - 140px); /* Adjusted max-height for new top position */
    background: #1d2238; /* Solid background - no transparency */
    transition: all 0.3s ease-in-out;
    z-index: 1050;
    overflow-y: auto;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5); /* Darker shadow for solid appearance */
    border-radius: 12px 0 0 12px;
    border: 2px solid #2d3348; /* Solid border instead of transparent */
    border-right: none;
}

.cart-sidebar.show {
    right: 0;
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.6); /* Darker, more prominent shadow */
}

.cart-sidebar .card {
    height: auto;
    border: none;
    border-radius: 0;
    background: transparent;
    cursor: pointer; /* Indicate cart is clickable for pin toggle */
}

/* Override cursor for interactive elements */
.cart-sidebar input,
.cart-sidebar button,
.cart-sidebar select,
.cart-sidebar a,
.cart-sidebar .cart-pin-toggle {
    cursor: default; /* Reset cursor for interactive elements */
}

/* Ensure Clear Cart button has pointer cursor for auto-spacing compatibility */
.cart-sidebar .btn-outline-danger {
    cursor: pointer !important;
}

/* Dynamic main content adjustment */
#mainContent {
    transition: all 0.3s ease-in-out;
    width: 100%;
}

#mainContent.cart-hidden {
    margin-right: 0;
    max-width: 100%;
}

#mainContent.cart-visible {
    margin-right: 470px; /* Cart width (450px) + margin (20px) */
    max-width: calc(100% - 470px);
}

/* Adjust cart content for larger sidebar */
.cart-sidebar .card-body {
    padding: 1.5rem; /* Increased from 1.2rem */
}

.cart-sidebar .card-footer {
    padding: 1.5rem; /* Increased from 1.2rem */
}

/* Cart header adjustments */
.cart-sidebar .card-header {
    background: #252b42; /* Solid background instead of transparent */
    border-bottom: 2px solid #2d3348; /* Solid border */
    padding: 1.2rem 1.5rem; /* Increased padding */
}

/* Clear Cart button in header - integrated with auto-spacing */
.cart-sidebar .btn-outline-danger {
    border-color: #dc3545;
    color: #dc3545;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    flex-shrink: 0; /* Prevent button from shrinking in flex layout */
}

.cart-sidebar .btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    transform: scale(1.05);
}

.cart-sidebar .btn-outline-danger:active {
    transform: scale(0.95);
}

.cart-indicator {
    position: fixed;
    top: 50%; /* Center vertically on screen */
    right: 0;
    transform: translateY(-50%);
    background: linear-gradient(135deg, #5d9bff 0%, #6f6bff 100%);
    color: white;
    padding: 15px 8px;
    border-radius: 8px 0 0 8px;
    cursor: pointer;
    z-index: 1049;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
}

.cart-indicator:hover {
    padding-right: 15px;
    background: linear-gradient(135deg, #4a8ae6 0%, #5a5ae6 100%);
}

.cart-indicator.hidden {
    right: -50px;
}

.indicator-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.indicator-content i {
    font-size: 18px;
}

.cart-count-indicator {
    background: #ff6b7a;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: bold;
    min-width: 20px;
}

.indicator-arrow i {
    font-size: 12px;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateX(0);
    }
    40% {
        transform: translateX(-3px);
    }
    60% {
        transform: translateX(-1px);
    }
}

.cart-pin-toggle {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #2d3348; /* Solid background */
    border: 2px solid #3d4358; /* Solid border */
    color: #f4f7ff;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 10;
}

.cart-pin-toggle:hover {
    background: #3d4358; /* Solid hover background */
    transform: scale(1.1);
}

.cart-pin-toggle.pinned {
    background: #28a745;
    border-color: #28a745;
    color: white;
}

.cart-pin-toggle.pinned i {
    transform: rotate(45deg);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .cart-sidebar {
        width: 350px; /* Increased from 320px to 350px */
        right: -350px;
        top: 100px; /* Adjusted top position to avoid header on tablet */
        max-height: calc(100vh - 120px); /* Adjusted max-height */
        border-radius: 8px 0 0 8px;
    }
    
    #mainContent.cart-visible {
        margin-right: 370px; /* Mobile cart width (350px) + margin (20px) */
        max-width: calc(100% - 370px);
    }
    
    .cart-indicator {
        padding: 12px 6px;
    }
    
    .indicator-content i {
        font-size: 16px;
    }
}

@media (max-width: 576px) {
    .cart-sidebar {
        width: 100%;
        right: -100%;
        top: 0;
        max-height: 100vh;
        border-radius: 0;
    }
    
    #mainContent.cart-visible {
        margin-right: 0; /* Full width on small mobile */
        max-width: 100%;
    }
}

/* Ensure cart content is visible when sidebar is shown */
.cart-sidebar.show .card {
    opacity: 1;
}

/* Smooth transitions for cart content */
.cart-sidebar .card-body,
.cart-sidebar .card-footer {
    transition: opacity 0.3s ease-in-out;
}

/* Optimize product grid for maximized space */
#mainContent.cart-hidden .product-item {
    transition: all 0.3s ease-in-out;
}

/* When cart is hidden, show more products per row on larger screens */
@media (min-width: 1400px) {
    #mainContent.cart-hidden .product-item {
        max-width: 20%; /* 5 products per row on extra large screens */
        flex: 0 0 20%;
    }
}

@media (min-width: 1200px) and (max-width: 1399px) {
    #mainContent.cart-hidden .product-item {
        max-width: 25%; /* 4 products per row on large screens */
        flex: 0 0 25%;
    }
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

<!-- Product Variant Selection Modal -->
<div class="modal fade" id="variantModal" tabindex="-1" aria-labelledby="variantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantModalLabel">
                    <i class="fas fa-box me-2"></i>Select Product Variant
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="variantContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading variants...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="addVariantToCartBtn" disabled>
                    <i class="fas fa-plus me-2"></i>Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layouts/footer') ?>

<script>
let cart = [];
let currentProductVariants = [];
let selectedVariant = null;

// Sliding Cart Variables
let cartTimeout = null;
let isCartPinned = false;
let isMouseOverCart = false;
let isMouseOverIndicator = false;

// Initialize Sliding Cart
document.addEventListener('DOMContentLoaded', function() {
    const cartSidebar = document.getElementById('cartSidebar');
    const cartIndicator = document.getElementById('cartIndicator');
    const cartPinToggle = document.getElementById('cartPinToggle');
    const mainContent = document.getElementById('mainContent');
    
    if (!cartSidebar || !cartIndicator || !cartPinToggle || !mainContent) {
        console.error('Cart elements not found');
        return;
    }
    
    // Initialize main content as cart-hidden
    mainContent.classList.add('cart-hidden');
    
    // Hide cart initially
    hideCart();
    
    // Cart indicator click/hover events
    cartIndicator.addEventListener('mouseenter', showCart);
    cartIndicator.addEventListener('click', toggleCart);
    
    // Cart sidebar mouse events
    cartSidebar.addEventListener('mouseenter', function() {
        isMouseOverCart = true;
        clearTimeout(cartTimeout);
    });
    
    cartSidebar.addEventListener('mouseleave', function() {
        isMouseOverCart = false;
        if (!isCartPinned) {
            startHideTimer();
        }
    });
    
    // Click anywhere in cart to toggle pin
    cartSidebar.addEventListener('click', function(e) {
        // Don't toggle pin if clicking on the pin toggle button, form inputs, buttons, or specific cart controls
        // Clear Cart button is excluded to allow it to work independently with auto-spacing
        if (!e.target.closest('.cart-pin-toggle') && 
            !e.target.closest('input') && 
            !e.target.closest('button:not(.btn-outline-danger)') && // Allow Clear Cart button to work normally
            !e.target.closest('select') &&
            !e.target.closest('a') &&
            !e.target.closest('.btn-outline-danger')) { // Explicitly exclude Clear Cart button
            togglePin(); // Toggle pin state - pin if unpinned, unpin if pinned
        }
    });
    
    cartIndicator.addEventListener('mouseleave', function() {
        isMouseOverIndicator = false;
        if (!isMouseOverCart && !isCartPinned) {
            startHideTimer();
        }
    });
    
    cartIndicator.addEventListener('mouseenter', function() {
        isMouseOverIndicator = true;
        clearTimeout(cartTimeout);
    });
    
    // Pin/Unpin toggle
    cartPinToggle.addEventListener('click', togglePin);
    
    // Hide cart when clicking on products
    document.addEventListener('click', function(e) {
        if (e.target.closest('.product-card') || e.target.closest('.product-item')) {
            if (!isCartPinned) {
                hideCart();
            }
        }
    });
    
    // Hide cart when clicking outside
    document.addEventListener('click', function(e) {
        if (!cartSidebar.contains(e.target) && !cartIndicator.contains(e.target) && !isCartPinned) {
            hideCart();
        }
    });
});

// Show cart
function showCart() {
    const cartSidebar = document.getElementById('cartSidebar');
    const cartIndicator = document.getElementById('cartIndicator');
    const mainContent = document.getElementById('mainContent');
    
    cartSidebar.classList.add('show');
    cartIndicator.classList.add('hidden');
    mainContent.classList.remove('cart-hidden');
    mainContent.classList.add('cart-visible');
    clearTimeout(cartTimeout);
}

// Hide cart
function hideCart() {
    const cartSidebar = document.getElementById('cartSidebar');
    const cartIndicator = document.getElementById('cartIndicator');
    const mainContent = document.getElementById('mainContent');
    
    cartSidebar.classList.remove('show');
    cartIndicator.classList.remove('hidden');
    mainContent.classList.remove('cart-visible');
    mainContent.classList.add('cart-hidden');
}

// Toggle cart visibility
function toggleCart() {
    const cartSidebar = document.getElementById('cartSidebar');
    
    if (cartSidebar.classList.contains('show')) {
        hideCart();
    } else {
        showCart();
    }
}

// Toggle pin state
function togglePin() {
    const cartPinToggle = document.getElementById('cartPinToggle');
    const pinIcon = document.getElementById('pinIcon');
    
    isCartPinned = !isCartPinned;
    
    if (isCartPinned) {
        // Pin the cart
        cartPinToggle.classList.add('pinned');
        pinIcon.style.transform = 'rotate(45deg)';
        showCart(); // Ensure cart is visible when pinned
    } else {
        // Unpin the cart
        cartPinToggle.classList.remove('pinned');
        pinIcon.style.transform = 'rotate(0deg)';
        startHideTimer(); // Start auto-hide timer when unpinned
    }
}

// Start hide timer
function startHideTimer() {
    clearTimeout(cartTimeout);
    cartTimeout = setTimeout(() => {
        if (!isMouseOverCart && !isMouseOverIndicator && !isCartPinned) {
            hideCart();
        }
    }, 1000); // Hide after 1 second
}

// Show cart when item is added
function showCartOnAdd() {
    showCart();
    
    // Keep cart visible for 3 seconds unless pinned or mouse is over it
    if (!isCartPinned) {
        clearTimeout(cartTimeout);
        cartTimeout = setTimeout(() => {
            if (!isMouseOverCart && !isMouseOverIndicator) {
                hideCart();
            }
        }, 3000);
    }
}

// Update cart count indicator
function updateCartCountIndicator() {
    const cartCountIndicator = document.getElementById('cartCountIndicator');
    const cartCount = document.getElementById('cartCount');
    
    if (cartCountIndicator && cartCount) {
        cartCountIndicator.textContent = cartCount.textContent;
    }
}

// Show variant selection modal
function showVariantModal(name, brand, category) {
    const modal = new bootstrap.Modal(document.getElementById('variantModal'));
    const modalTitle = document.getElementById('variantModalLabel');
    const variantContent = document.getElementById('variantContent');
    const addBtn = document.getElementById('addVariantToCartBtn');
    
    // IMMEDIATE VALIDATION: Categories that should NEVER show modal
    const noModalCategories = ['Device'];
    if (noModalCategories.includes(category)) {
        // Add directly without showing any modal
        fetch(`<?= site_url('/pos/variants') ?>?name=${encodeURIComponent(name)}&brand=${encodeURIComponent(brand)}&category=${encodeURIComponent(category)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.variants.length > 0) {
                    selectedVariant = data.variants[0];
                    addVariantToCart();
                } else {
                    // Handle no variants case
                    console.warn('No variants found for product:', name);
                    // Try to add as a simple product
                    legacyAddToCart(name, brand, category);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding product to cart');
            });
        return;
    }
    
    // Reset selection
    selectedVariant = null;
    addBtn.disabled = true;
    
    // Fetch variants first before showing modal
    fetch(`<?= site_url('/pos/variants') ?>?name=${encodeURIComponent(name)}&brand=${encodeURIComponent(brand)}&category=${encodeURIComponent(category)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentProductVariants = data.variants;
                
                // Check if variant selection is actually needed
                const requiresSelection = data.category_info.requires_flavor || 
                                        data.category_info.requires_puffs.required || 
                                        data.category_info.requires_puffs.optional;
                
                // Smart logic: Only show modal if there are actual choices to make
                const shouldShowModal = requiresSelection && data.variants.length > 1;
                
                // Handle different scenarios:
                // 1. No variants at all - add directly like Device category
                // 2. Only 1 variant - add directly (no choice needed)
                // 3. Multiple variants - show modal only if selection is required
                if (data.variants.length === 0) {
                    // No variants found - add directly to cart like Device category
                    console.log('No variants found, adding directly to cart like Device category');
                    legacyAddToCart(name, brand, category);
                    return;
                }
                
                if (!shouldShowModal || data.variants.length === 1) {
                    // Add directly to cart - no choice needed or only one option
                    selectedVariant = data.variants[0];
                    addVariantToCart();
                    return;
                }
                
                // Only show modal if we actually need to
                modalTitle.innerHTML = `<i class="fas fa-box me-2"></i>Select Variant: ${name}`;
                
                // Show loading state
                variantContent.innerHTML = `
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading variants...</p>
                    </div>
                `;
                
                modal.show();
                displayVariants(data.variants, data.category_info, name, brand, category);
            } else {
                // If no variants found, add directly like Device category
                console.log('No variants found, adding directly to cart like Device category');
                legacyAddToCart(name, brand, category);
            }
        })
        .catch(error => {
            console.error('Error loading variants:', error);
            // On error, try to add directly
            legacyAddToCart(name, brand, category);
        });
}

// Display variants in the modal
function displayVariants(variants, categoryInfo, productName, brand, category) {
    const variantContent = document.getElementById('variantContent');
    const addBtn = document.getElementById('addVariantToCartBtn');
    
    let html = `
        <div class="mb-3">
            <h6 class="text-muted">${productName} ${brand ? '- ' + brand : ''}</h6>
        </div>
    `;
    
    // Flavor selection
    if (categoryInfo.requires_flavor) {
        const flavors = [...new Set(variants.filter(v => v.flavor).map(v => v.flavor))];
        if (flavors.length > 0) {
            html += `
                <div class="mb-3">
                    <label class="form-label">Flavor <span class="text-danger">*</span></label>
                    <select class="form-select" id="flavorSelect" onchange="updateVariantSelection()">
                        <option value="">Select Flavor</option>
                        ${flavors.map(flavor => `<option value="${flavor}">${flavor}</option>`).join('')}
                    </select>
                </div>
            `;
        }
    }
    
    // Puff selection
    if (categoryInfo.requires_puffs.required || categoryInfo.requires_puffs.optional) {
        const puffCounts = [...new Set(variants.filter(v => v.puffs).map(v => v.puffs))].sort((a, b) => a - b);
        if (puffCounts.length > 0) {
            const required = categoryInfo.requires_puffs.required ? ' <span class="text-danger">*</span>' : '';
            html += `
                <div class="mb-3">
                    <label class="form-label">Puffs${required}</label>
                    <select class="form-select" id="puffSelect" onchange="updateVariantSelection()">
                        <option value="">Select Puffs</option>
                        ${puffCounts.map(puffs => `<option value="${puffs}">${puffs} puffs</option>`).join('')}
                    </select>
                </div>
            `;
        }
    }
    
    variantContent.innerHTML = html;
    
    // Store data for variant selection
    window.currentVariantData = {
        variants: variants,
        categoryInfo: categoryInfo,
        productName: productName,
        brand: brand,
        category: category
    };
}

// Update variant selection based on dropdowns
function updateVariantSelection() {
    const flavorSelect = document.getElementById('flavorSelect');
    const puffSelect = document.getElementById('puffSelect');
    const flavor = flavorSelect ? (flavorSelect.value || null) : null;
    const puffs = puffSelect ? (puffSelect.value || null) : null;
    const { variants, categoryInfo, productName, brand, category } = window.currentVariantData;
    
    // If flavor changed, update available puffs (only if puff select exists and puffs are required/optional)
    if (flavor && puffSelect && (categoryInfo.requires_puffs.required || categoryInfo.requires_puffs.optional)) {
        const availablePuffs = [...new Set(variants
            .filter(v => v.flavor === flavor)
            .map(v => v.puffs)
            .filter(Boolean)
        )].sort((a, b) => a - b);
        
        // Update puff dropdown
        puffSelect.innerHTML = '<option value="">Select Puffs</option>';
        availablePuffs.forEach(puff => {
            const option = document.createElement('option');
            option.value = puff;
            option.textContent = puff;
            puffSelect.appendChild(option);
        });
        
        // Auto-select if only one puff available
        if (availablePuffs.length === 1) {
            puffSelect.value = availablePuffs[0];
            // Call updateVariantSelection again after auto-selecting puff
            setTimeout(() => updateVariantSelection(), 100);
        }
    }
    
    // Find matching variant
    selectedVariant = null;
    
    for (let variant of variants) {
        let matches = true;
        
        // Check flavor requirement
        if (categoryInfo.requires_flavor) {
            if (!flavor) {
                matches = false; // Flavor is required but not selected
            } else {
                matches = matches && variant.flavor === flavor;
            }
        }
        
        // Check puff requirement
        if (categoryInfo.requires_puffs.required) {
            if (!puffs) {
                matches = false; // Puffs are required but not selected
            } else {
                matches = matches && variant.puffs == puffs;
            }
        } else if (categoryInfo.requires_puffs.optional) {
            // Puffs are optional - only filter if selected
            if (puffs) {
                matches = matches && variant.puffs == puffs;
            }
        }
        // If puffs are not required or optional, don't filter by puffs
        
        if (matches) {
            selectedVariant = variant;
            break;
        }
    }
    
    const addBtn = document.getElementById('addVariantToCartBtn');
    
    if (selectedVariant) {
        addBtn.disabled = selectedVariant.stock_qty <= 0;
        
        if (selectedVariant.stock_qty <= 0) {
            alert('This variant is out of stock!');
        }
    } else {
        addBtn.disabled = true;
    }
}

// Add selected variant to cart
function addVariantToCart() {
    if (!selectedVariant) {
        const { categoryInfo } = window.currentVariantData;
        const flavorSelect = document.getElementById('flavorSelect');
        const puffSelect = document.getElementById('puffSelect');
        const flavor = flavorSelect ? (flavorSelect.value || null) : null;
        const puffs = puffSelect ? (puffSelect.value || null) : null;
        
        let errorMessage = 'Please select ';
        const missingFields = [];
        
        if (categoryInfo.requires_flavor && !flavor) {
            missingFields.push('flavor');
        }
        if (categoryInfo.requires_puffs.required && !puffs) {
            missingFields.push('puffs');
        }
        
        if (missingFields.length > 0) {
            errorMessage += missingFields.join(' and ');
        } else {
            errorMessage = 'Please select valid product options';
        }
        
        alert(errorMessage);
        return;
    }
    
    if (selectedVariant.stock_qty <= 0) {
        alert('This variant is out of stock!');
        return;
    }
    
    // Check if variant already exists in cart
    const existingItem = cart.find(item => 
        item.id === selectedVariant.id && 
        item.flavor === (selectedVariant.flavor || '') && 
        item.puffs === (selectedVariant.puffs || 0)
    );
    
    if (existingItem) {
        if (existingItem.quantity >= selectedVariant.stock_qty) {
            alert('Cannot add more than available stock!');
            return;
        }
        existingItem.quantity++;
    } else {
        cart.push({
            id: selectedVariant.id,
            name: selectedVariant.name,
            price: parseFloat(selectedVariant.price),
            quantity: 1,
            stock: selectedVariant.stock_qty,
            flavor: selectedVariant.flavor || '',
            puffs: selectedVariant.puffs || 0
        });
    }
    
    updateCart();
    
    // Show cart when item is added
    showCartOnAdd();
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('variantModal'));
    if (modal) {
        modal.hide();
    }
}

// Legacy addToCart function (for compatibility)
function addToCart(id, name, price, stock) {
    if (stock <= 0) {
        alert('This product is out of stock!');
        return;
    }

    const existingItem = cart.find(item => 
        item.id === id && 
        item.flavor === '' && 
        item.puffs === 0
    );
    
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
            price: parseFloat(price),
            quantity: 1,
            stock: stock,
            flavor: '',
            puffs: 0
        });
    }
    
    updateCart();
    
    // Show cart when item is added
    showCartOnAdd();
}

// Legacy addToCart function for products without variants (like Device category)
function legacyAddToCart(name, brand, category) {
    // Fetch product details to get ID, price, and stock
    fetch(`<?= site_url('/pos/variants') ?>?name=${encodeURIComponent(name)}&brand=${encodeURIComponent(brand)}&category=${encodeURIComponent(category)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.variants.length > 0) {
                const variant = data.variants[0];
                addToCart(variant.id, variant.name, variant.price, variant.stock_qty);
            } else {
                alert('Product not found or out of stock');
            }
        })
        .catch(error => {
            console.error('Error adding product to cart:', error);
            alert('Error adding product to cart');
        });
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
        
        // Auto-unpin cart when empty
        if (isCartPinned) {
            togglePin();
        }
        
        return;
    }
    
    let html = '';
    let subtotalAmount = 0;
    let totalItems = 0;
    
    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        subtotalAmount += itemTotal;
        totalItems += item.quantity;
        
        // Build display name with variant info
        let displayName = item.name;
        if (item.flavor) {
            displayName += ' - ' + item.flavor;
        }
        if (item.puffs > 0) {
            displayName += ' (' + item.puffs + ' puffs)';
        }
        
        html += `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <strong>${displayName}</strong><br>
                    <small class="text-muted">₱${item.price.toFixed(2)} x ${item.quantity}</small>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-outline-primary me-1" onclick="cartMinus(${index})">
                        <i class="fas fa-minus"></i>
                    </button>
                    <span class="mx-2">${item.quantity}</span>
                    <button class="btn btn-sm btn-outline-primary me-2" onclick="cartPlus(${index})">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="cartDelete(${index})">
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
    
    // Auto-pin cart when items are added
    if (!isCartPinned) {
        togglePin();
    }
    
    // Update cart count indicator
    updateCartCountIndicator();
}

function cartPlus(index) {
    if (cart[index].quantity < cart[index].stock) {
        cart[index].quantity++;
        updateCart();
    } else {
        alert('Cannot add more than available stock!');
    }
}

function cartMinus(index) {
    if (cart[index].quantity > 1) {
        cart[index].quantity--;
        updateCart();
    } else {
        cartDelete(index);
    }
}

function cartDelete(index) {
    cart.splice(index, 1);
    updateCart();
}

// Add event listener for the add variant button
document.addEventListener('DOMContentLoaded', function() {
    const addVariantBtn = document.getElementById('addVariantToCartBtn');
    if (addVariantBtn) {
        addVariantBtn.addEventListener('click', addVariantToCart);
    }
});
let changeCalcElements = null;

function initChangeCalcElements() {
    if (!changeCalcElements) {
        changeCalcElements = {
            amountPaidInput: document.getElementById('amountPaid'),
            totalElement: document.getElementById('total'),
            changeDisplay: document.getElementById('changeDisplay'),
            changeAmountElement: document.getElementById('changeAmount')
        };
    }
}

// Debounce function to limit how often calculateChange runs
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function calculateChange() {
    // Initialize cached elements if not already done
    initChangeCalcElements();
    
    const { amountPaidInput, totalElement, changeDisplay, changeAmountElement } = changeCalcElements;
    
    if (!totalElement || !amountPaidInput) return;
    
    const amountPaidValue = amountPaidInput.value || '';
    const amountPaid = parseFloat(amountPaidValue) || 0;
    
    // If amount paid is empty or zero, reset change display
    if (amountPaidValue === '' || amountPaid <= 0) {
        amountPaidInput.style.borderColor = ''; // Reset to default
        changeDisplay.style.display = 'none';
        changeAmountElement.textContent = '0.00';
        return;
    }
    
    // Cache the total amount to avoid repeated parsing
    if (!changeCalcElements.cachedTotalAmount) {
        const totalText = totalElement.textContent;
        changeCalcElements.cachedTotalAmount = parseFloat(totalText.replace(/[₱,]/g, '')) || 0;
    }
    
    const totalAmount = changeCalcElements.cachedTotalAmount;
    
    if (!isNaN(totalAmount)) {
        const change = amountPaid - totalAmount;
        
        // Update the input border color
        if (change >= 0) {
            amountPaidInput.style.borderColor = '#28a745'; // Green for sufficient payment
            changeDisplay.style.display = 'block';
            changeDisplay.className = 'text-success d-block mt-1';
            changeAmountElement.textContent = change.toFixed(2);
        } else {
            amountPaidInput.style.borderColor = '#dc3545'; // Red for insufficient payment
            changeDisplay.style.display = 'none';
            changeAmountElement.textContent = '0.00';
        }
    } else {
        amountPaidInput.style.borderColor = ''; // Reset to default
        changeDisplay.style.display = 'none';
        changeAmountElement.textContent = '0.00';
    }
}

// Create debounced version for input events
const debouncedCalculateChange = debounce(calculateChange, 100);

// Add event listener for the add variant button
document.addEventListener('DOMContentLoaded', function() {
    const addVariantBtn = document.getElementById('addVariantToCartBtn');
    if (addVariantBtn) {
        addVariantBtn.addEventListener('click', addVariantToCart);
    }
});

function clearCart() {
    if (cart.length > 0 && confirm('Are you sure you want to clear the cart?')) {
        cart = [];
        
        // Clear cached values
        if (changeCalcElements) {
            changeCalcElements.cachedTotalAmount = null;
            if (changeCalcElements.amountPaidInput) {
                changeCalcElements.amountPaidInput.value = '';
                changeCalcElements.amountPaidInput.style.borderColor = '';
            }
            if (changeCalcElements.changeDisplay) {
                changeCalcElements.changeDisplay.style.display = 'none';
            }
        }
        
        // Clear age verification fields
        const birthDateInput = document.getElementById('customerBirthDate');
        const ageResultDiv = document.getElementById('ageVerificationResult');
        if (birthDateInput) {
            birthDateInput.value = '';
            birthDateInput.style.borderColor = '';
            birthDateInput.classList.remove('is-valid', 'is-invalid');
        }
        if (ageResultDiv) {
            ageResultDiv.style.display = 'none';
        }
        
        updateCart();
    }
}

// Quick Age Verification Functions
function quickAgeVerify(ageRequirement) {
    const birthDateInput = document.getElementById('customerBirthDate');
    
    // Calculate birth date for 18 years ago
    const today = new Date();
    const birthYear = today.getFullYear() - 18;
    const birthMonth = (today.getMonth() + 1).toString().padStart(2, '0');
    const birthDay = today.getDate().toString().padStart(2, '0');
    
    // Format as YYYY-MM-DD for date input
    const birthDateString = `${birthYear}-${birthMonth}-${birthDay}`;
    
    // Set the birth date in the input
    birthDateInput.value = birthDateString;
    
    // Trigger verification display
    verifyAgeDisplay();
}

function verifyAgeDisplay() {
    const birthDateInput = document.getElementById('customerBirthDate');
    const birthDateValue = birthDateInput.value;
    const resultDiv = document.getElementById('ageVerificationResult');
    
    if (!birthDateValue) {
        resultDiv.style.display = 'none';
        birthDateInput.style.borderColor = '';
        birthDateInput.classList.remove('is-valid', 'is-invalid');
        return;
    }
    
    const age = getCustomerAge(birthDateValue);
    displayAgeResult(age);
}

function displayAgeResult(age, quickVerifyType = null) {
    const resultDiv = document.getElementById('ageVerificationResult');
    const birthDateInput = document.getElementById('customerBirthDate');
    
    if (age === null) {
        resultDiv.innerHTML = `
            <div class="alert alert-danger py-2 mb-0">
                <i class="fas fa-exclamation-triangle me-1"></i>
                Invalid birth date format
            </div>
        `;
        resultDiv.style.display = 'block';
        birthDateInput.style.borderColor = '#dc3545';
        birthDateInput.classList.add('is-invalid');
        birthDateInput.classList.remove('is-valid');
    } else if (age < 18) {
        resultDiv.innerHTML = `
            <div class="alert alert-danger py-2 mb-0">
                <i class="fas fa-times-circle me-1"></i>
                Customer is ${age} years old. Under 18
            </div>
        `;
        resultDiv.style.display = 'block';
        birthDateInput.style.borderColor = '#dc3545';
        birthDateInput.classList.add('is-invalid');
        birthDateInput.classList.remove('is-valid');
    } else {
        resultDiv.innerHTML = `
            <div class="alert alert-success py-2 mb-0">
                <i class="fas fa-check-circle me-1"></i>
                Customer is ${age} years old. Age Verified.
            </div>
        `;
        resultDiv.style.display = 'block';
        birthDateInput.style.borderColor = '#28a745';
        birthDateInput.classList.add('is-valid');
        birthDateInput.classList.remove('is-invalid');
    }
}

function getCustomerAge(birthDateValue) {
    if (!birthDateValue || !/^\d{4}-\d{2}-\d{2}$/.test(birthDateValue)) {
        return null;
    }

    const birthDate = new Date(`${birthDateValue}T00:00:00`);
    if (Number.isNaN(birthDate.getTime())) {
        return null;
    }

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    const dayDiff = today.getDate() - birthDate.getDate();
    if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
        age--;
    }

    return age;
}

function validateAgeRestriction() {
    const birthDateInput = document.getElementById('customerBirthDate');
    const birthDateValue = (birthDateInput?.value || '').trim();

    if (!birthDateValue) {
        return {
            isValid: false,
            message: 'Please enter customer birth date before processing sale.'
        };
    }

    const age = getCustomerAge(birthDateValue);
    console.log('Frontend age calculation:', { birthDateValue, age });
    
    if (age === null) {
        return {
            isValid: false,
            message: 'Invalid birth date. Please use a valid date.'
        };
    }

    if (age < 18) {
        return {
            isValid: false,
            message: `Sale blocked: customer is ${age} years old. Must be 18 years old or above.`
        };
    }

    return {
        isValid: true,
        birthDate: birthDateValue
    };
}

function processSale() {
    if (cart.length === 0) {
        alert('Your cart is empty!');
        return;
    }

    const ageValidation = validateAgeRestriction();
    if (!ageValidation.isValid) {
        alert(ageValidation.message);
        return;
    }
    
    // Validate payment amount
    const amountPaidInput = document.getElementById('amountPaid');
    const amountPaid = parseFloat(amountPaidInput?.value || '0');
    
    if (isNaN(amountPaid) || amountPaid <= 0) {
        alert('Please enter a valid amount paid.');
        amountPaidInput?.focus();
        return;
    }
    
    // Calculate total amount
    let subtotalAmount = 0;
    cart.forEach(item => {
        subtotalAmount += item.price * item.quantity;
    });
    const taxAmount = subtotalAmount * 0.1;
    const totalAmount = subtotalAmount + taxAmount;
    
    if (amountPaid < totalAmount) {
        alert('Insufficient payment amount. Total is ₱' + totalAmount.toFixed(2) + ' but customer paid ₱' + amountPaid.toFixed(2));
        amountPaidInput?.focus();
        return;
    }
    
    // Show loading state
    const processBtn = document.getElementById('processSaleBtn');
    const originalText = processBtn.innerHTML;
    processBtn.disabled = true;
    processBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    
    // Prepare request data
    const requestData = {
        cart: cart,
        customer_birthdate: ageValidation.birthDate,
        amount_paid: amountPaid
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
            const birthDateInput = document.getElementById('customerBirthDate');
            if (birthDateInput) {
                birthDateInput.value = '';
            }
            if (amountPaidInput) {
                amountPaidInput.value = '';
            }
            
            // Show receipt modal
            showReceiptModal(data);
            
        } else {
            alert('Error: ' + data.message);
            processBtn.disabled = false;
            processBtn.innerHTML = originalText;
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
        const brand = product.dataset.brand.toLowerCase();
        if (name.includes(searchTerm) || brand.includes(searchTerm)) {
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
                <div class="receipt-shop-name">QuickPuff VapeShop</div>
                <div class="receipt-shop-address">Bula, General Santos City, South Cotabato</div>
                <div class="receipt-shop-address">Tel: 09365879409</div>
                <div class="receipt-shop-address">Email: quickpuff@gmail.com</div>
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
                <div class="receipt-total-row">
                    <span class="receipt-label">TOTAL:</span>
                    <span class="receipt-value">₱${parseFloat(sale.total_amount || 0).toFixed(2)}</span>
                </div>
                <div class="receipt-total-row">
                    <span class="receipt-label">Amount Paid:</span>
                    <span class="receipt-value">₱${parseFloat(sale.amount_paid || 0).toFixed(2)}</span>
                </div>
                <div class="receipt-total-row receipt-grand-total">
                    <span class="receipt-label">CHANGE:</span>
                    <span class="receipt-value">₱${parseFloat(sale.change_amount || 0).toFixed(2)}</span>
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
                    size: 89mm 127mm;
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
                    width: 89mm;
                    max-width: 89mm;
                }
                .receipt-container {
                    width: 85mm;
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
                        size: 89mm 127mm;
                        margin: 2mm;
                    }
                    body {
                        width: 89mm;
                        max-width: 89mm;
                        margin: 0;
                        padding: 0;
                    }
                    .receipt-container {
                        width: 85mm;
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
                <div class="receipt-shop-name">QuickPuff VapeShop</div>
                <div class="receipt-shop-address">Bula, General Santos City, South Cotabato</div>
                <div class="receipt-shop-address">Tel: 09365879409</div>
                <div class="receipt-shop-address">Email: quickpuff@gmail.com</div>
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
                <div class="receipt-total-row">
                    <span class="receipt-label">TOTAL:</span>
                    <span class="receipt-value">₱${parseFloat(sale.total_amount || 0).toFixed(2)}</span>
                </div>
                <div class="receipt-total-row">
                    <span class="receipt-label">Amount Paid:</span>
                    <span class="receipt-value">₱${parseFloat(sale.amount_paid || 0).toFixed(2)}</span>
                </div>
                <div class="receipt-total-row receipt-grand-total">
                    <span class="receipt-label">CHANGE:</span>
                    <span class="receipt-value">₱${parseFloat(sale.change_amount || 0).toFixed(2)}</span>
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
