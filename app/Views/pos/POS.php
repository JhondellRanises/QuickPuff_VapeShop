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
    <!-- Category Sidebar -->
    <div class="category-sidebar" id="categorySidebar">
        <div class="category-indicator" id="categoryIndicator">
            <div class="category-word">
                CATEGORY
            </div>
        </div>
        
        <div class="category-content">
            <div class="category-header">
                <h5 class="category-title">
                    <i class="fas fa-list me-2"></i>Categories
                </h5>
            </div>
            <div class="category-list">
                <?php foreach ($categories as $category): ?>
                    <div class="category-item" data-category="<?= esc($category) ?>" onclick="filterByCategorySidebar('<?= esc($category) ?>')">
                        <i class="fas fa-tag me-2 category-icon"></i>
                        <span class="category-name"><?= esc($category) ?></span>
                        <i class="fas fa-chevron-right category-arrow"></i>
                    </div>
                <?php endforeach; ?>
                <div class="category-item active" data-category="" onclick="filterByCategorySidebar('')">
                    <i class="fas fa-th me-2 category-icon"></i>
                    <span class="category-name">All Categories</span>
                    <i class="fas fa-chevron-right category-arrow"></i>
                </div>
            </div>
            
            <!-- Flavors and Puffs Section -->
            <div class="flavors-section" id="flavorsSection" style="display: none;">
                <div class="flavors-header">
                    <h6 class="flavors-title">
                        <i class="fas fa-palette me-2"></i>Available Flavors
                    </h6>
                </div>
                <div class="flavors-list" id="flavorsList">
                    <!-- Flavors will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>

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
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="searchProduct" 
                                   placeholder="Search products..." onkeyup="searchProducts()">
                        </div>
                        <div class="col-md-3">
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
                                    $productFlavors = [];
                                    foreach (($product['flavors'] ?? []) as $flavor) {
                                        $flavor = trim((string) $flavor);
                                        if ($flavor !== '' && !in_array($flavor, $productFlavors, true)) {
                                            $productFlavors[] = $flavor;
                                        }
                                    }

                                    if ($productImage === '') {
                                        $productImage = $defaultVapeImage;
                                    } elseif (!preg_match('#^(?:https?:)?//#i', $productImage) && strpos($productImage, 'data:image') !== 0) {
                                        $productImage = base_url(ltrim($productImage, '/'));
                                    }
                                ?>
                                <div class="col-md-6 col-lg-4 mb-3 product-item" 
                                     data-category="<?= esc($product['category']) ?>" 
                                     data-name="<?= esc($product['name']) ?>"
                                     data-brand="<?= esc($product['brand'] ?? '') ?>"
                                     data-flavors="<?= esc(json_encode($productFlavors, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 'attr') ?>">
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

<!-- Category Overlay -->
<div class="category-overlay" id="categoryOverlay"></div>

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

/* Category Sidebar Styles */
.category-sidebar {
    position: fixed;
    top: 80px; /* Reduced to eliminate top space and align with header */
    left: -280px; /* Initially hidden */
    width: 280px;
    height: calc(100vh - 80px); /* Adjust height to match new top position */
    background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
    transition: transform 0.3s ease-in-out;
    z-index: 1040;
    box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.category-sidebar.show {
    transform: translateX(280px);
}

/* Category Indicator - Vertical bar with word */
.category-indicator {
    position: absolute;
    top: 40%; /* Match cart indicator position */
    right: -40px;
    transform: translateY(-50%);
    background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
    border-radius: 0 8px 8px 0;
    padding: 12px 8px; /* Match cart indicator padding */
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
    cursor: pointer;
    transition: all 0.3s ease;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    height: auto; /* Auto height to match content */
    display: flex;
    align-items: center;
    justify-content: center;
}

.category-word {
    writing-mode: vertical-rl; /* Vertical text orientation */
    text-orientation: mixed; /* Mixed orientation for better readability */
    color: #e2e8f0;
    font-weight: 600;
    font-size: 11px;
    letter-spacing: 2px;
    transition: all 0.2s ease;
}

.category-indicator:hover .category-word {
    color: #ffffff;
    transform: scale(1.05);
}

/* Category Content */
.category-content {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.category-header {
    padding: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.1); /* Subtle background for header */
}

.category-title {
    color: #f7fafc;
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.category-list {
    flex: 1;
    overflow-y: auto;
    padding: 1rem 0;
}

/* Flavors and Puffs Section */
.flavors-section {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.05);
}

.flavors-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.flavors-title {
    color: #f7fafc;
    margin: 0;
    font-size: 0.9rem;
    font-weight: 600;
}

.flavors-list {
    max-height: 200px;
    overflow-y: auto;
    padding: 0.5rem 0;
}

.flavor-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    color: #e2e8f0;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.85rem;
    border-left: 3px solid transparent;
    position: relative;
}

.flavor-item.clickable:hover {
    background: rgba(255, 255, 255, 0.08);
    border-left-color: #5d9bff;
    transform: translateX(2px);
}

.flavor-item.clickable:active {
    background: rgba(255, 255, 255, 0.12);
    transform: translateX(1px);
}

.flavor-item.active {
    background: rgba(93, 155, 255, 0.15);
    border-left-color: #5d9bff;
    color: #5d9bff;
}

.flavor-icon {
    color: #a0aec0;
    font-size: 0.9rem;
    transition: color 0.2s ease;
}

.flavor-item.clickable:hover .flavor-icon {
    color: #5d9bff;
}

.flavor-item.active .flavor-icon {
    color: #5d9bff;
}

.flavor-name {
    flex: 1;
    font-weight: 500;
}

.flavor-details {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 2px;
    margin-left: 0.5rem;
}

.flavor-puffs {
    font-size: 0.7rem;
    color: #a0aec0;
    white-space: nowrap;
}

.flavor-stock {
    font-size: 0.7rem;
    color: #68d391;
    white-space: nowrap;
    display: flex;
    align-items: center;
}

.flavor-stock.out-of-stock {
    color: #fc8181;
}

.category-item {
    display: flex;
    align-items: center;
    padding: 0.875rem 1.5rem;
    color: #e2e8f0;
    cursor: pointer;
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
    position: relative;
}

.category-item:hover {
    background: rgba(255, 255, 255, 0.05);
    border-left-color: #5d9bff;
}

.category-item.active {
    background: rgba(93, 155, 255, 0.1);
    border-left-color: #5d9bff;
    color: #5d9bff;
}

.category-icon {
    width: 16px;
    text-align: center;
    opacity: 0.8;
}

.category-name {
    flex: 1;
    font-weight: 500;
}

.category-arrow {
    opacity: 0.5;
    font-size: 12px;
    transition: all 0.2s ease;
}

.category-item:hover .category-arrow {
    opacity: 1;
    transform: translateX(2px);
}

/* Overlay effect */
.category-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3);
    z-index: 1035;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.category-overlay.show {
    opacity: 1;
    visibility: visible;
}

/* Adjust main content for both sidebars */
#mainContent {
    transition: all 0.3s ease-in-out;
    width: 100%;
}

#mainContent.category-hidden.cart-hidden {
    margin-left: 0;
    margin-right: 0;
    max-width: 100%;
}

#mainContent.category-visible.cart-hidden {
    margin-left: 300px; /* Category sidebar width + margin */
    margin-right: 0;
    max-width: calc(100% - 300px);
}

#mainContent.category-hidden.cart-visible {
    margin-left: 0;
    margin-right: 470px; /* Cart width + margin */
    max-width: calc(100% - 470px);
}

#mainContent.category-visible.cart-visible {
    margin-left: 300px; /* Category sidebar width + margin */
    margin-right: 470px; /* Cart width + margin */
    max-width: calc(100% - 770px); /* Both sidebars */
}

/* Responsive adjustments for category sidebar */
@media (max-width: 768px) {
    .category-sidebar {
        width: 250px;
        left: -250px;
        top: 80px; /* Reduced to eliminate top space on tablet */
        height: calc(100vh - 80px); /* Adjust height for tablet */
    }
    
    .category-sidebar.show {
        transform: translateX(250px);
    }
    
    #mainContent.category-visible.cart-hidden {
        margin-left: 270px;
        max-width: calc(100% - 270px);
    }
    
    #mainContent.category-visible.cart-visible {
        margin-left: 270px;
        margin-right: 370px; /* Mobile cart width + margin */
        max-width: calc(100% - 640px); /* Both sidebars on tablet */
    }
}

@media (max-width: 576px) {
    .category-sidebar {
        width: 100%;
        left: -100%;
        top: 80px; /* Reduced to eliminate top space on mobile */
        height: calc(100vh - 80px); /* Adjust height for mobile */
    }
    
    .category-sidebar.show {
        transform: translateX(100%);
    }
    
    #mainContent.category-visible.cart-hidden,
    #mainContent.category-visible.cart-visible {
        margin-left: 0;
        max-width: 100%;
    }
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
    top: calc(80px + 40%); /* Match category indicator position exactly */
    right: 0;
    transform: translateY(-50%);
    background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); /* Same as category sidebar */
    color: white;
    padding: 12px 8px; /* Match category indicator padding */
    border-radius: 0 8px 8px 0; /* Match category indicator shape */
    cursor: pointer;
    z-index: 1049;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    box-shadow: -2px 0 10px rgba(0, 0, 0, 0.2); /* Enhanced shadow to match category sidebar */
    border-left: 1px solid rgba(255, 255, 255, 0.1); /* Border to match category sidebar */
    height: auto; /* Auto height to match content */
}

.cart-indicator:hover {
    padding-right: 15px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%); /* Match category sidebar hover */
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
    background: rgba(255, 255, 255, 0.1); /* Match category letter background */
    color: #e2e8f0; /* Match category letter color */
    border-radius: 4px; /* Match category letter border radius */
    width: 24px; /* Match category letter width */
    height: 24px; /* Match category letter height */
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px; /* Match category letter font size */
    font-weight: 600; /* Match category letter font weight */
    min-width: 24px;
    transition: all 0.2s ease; /* Match category letter transition */
}

.cart-count-indicator:hover {
    background: rgba(255, 255, 255, 0.2); /* Match category letter hover */
    transform: scale(1.1); /* Match category letter hover */
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

#variantModal .modal-content {
    background: linear-gradient(180deg, #11162c 0%, #0d1226 100%);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: #f4f7ff;
}

#variantModal .modal-header,
#variantModal .modal-footer {
    border-color: rgba(255, 255, 255, 0.08);
}

#variantModal .modal-title,
#variantModal .form-label {
    color: #f4f7ff;
}

#variantModal .btn-close {
    filter: invert(1) grayscale(1);
    opacity: 0.8;
}

#variantModal .btn-close:hover {
    opacity: 1;
}

.variant-price-preview-card {
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.variant-price-label {
    color: rgba(244, 247, 255, 0.7) !important;
    font-size: 0.82rem;
    letter-spacing: 0.03em;
}

.variant-price-value {
    color: #ffffff !important;
    font-size: 2rem;
    font-weight: 800;
    line-height: 1.1;
}

.variant-price-stock {
    color: rgba(244, 247, 255, 0.78) !important;
    font-size: 0.92rem;
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
const flavorInventoryByCategory = <?= json_encode($flavorInventory ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

// Category Sidebar Variables
let categoryTimeout = null;
let isMouseOverCategory = false;
let isMouseOverCategoryIndicator = false;
let currentActiveCategory = '';
let currentActiveFlavor = '';
let currentSearchTerm = '';

function formatCurrency(value) {
    const amount = Number(value || 0);
    return `₱${amount.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

function getPuffOptionData(variants) {
    const puffMap = new Map();

    (variants || []).forEach((variant) => {
        const puffValue = parseInt(variant.puffs || 0, 10);
        if (!puffValue) {
            return;
        }

        const priceValue = parseFloat(variant.price || 0);
        if (!puffMap.has(puffValue)) {
            puffMap.set(puffValue, {
                puffs: puffValue,
                minPrice: priceValue,
                maxPrice: priceValue,
            });
            return;
        }

        const existing = puffMap.get(puffValue);
        existing.minPrice = Math.min(existing.minPrice, priceValue);
        existing.maxPrice = Math.max(existing.maxPrice, priceValue);
    });

    return Array.from(puffMap.values()).sort((a, b) => a.puffs - b.puffs);
}

function formatPuffOptionLabel(option) {
    return `${Number(option.puffs || 0).toLocaleString('en-PH')} puffs`;
}

function getFlavorOptionData(variants, selectedPuffs = null) {
    const normalizedPuffs = selectedPuffs === null || selectedPuffs === '' ? null : String(selectedPuffs);
    const flavorMap = new Map();

    (variants || []).forEach((variant) => {
        const flavorValue = String(variant.flavor || '').trim();
        if (!flavorValue) {
            return;
        }

        if (normalizedPuffs !== null && String(variant.puffs || '') !== normalizedPuffs) {
            return;
        }

        if (!flavorMap.has(flavorValue)) {
            flavorMap.set(flavorValue, flavorValue);
        }
    });

    return Array.from(flavorMap.values()).sort((left, right) => left.localeCompare(right));
}

function requiresExplicitPuffSelection(variants, categoryInfo) {
    const puffOptions = getPuffOptionData(variants || []);
    return (categoryInfo.requires_puffs.required || puffOptions.length > 1) && puffOptions.length > 0;
}

function renderVariantPricePreview(variant, variants, categoryInfo, flavor = null, puffs = null) {
    const pricePreview = document.getElementById('variantPricePreview');
    const stockPreview = document.getElementById('variantStockPreview');

    if (!pricePreview || !stockPreview) {
        return;
    }

    const relevantVariants = (variants || []).filter((item) => {
        if (puffs && String(item.puffs || '') !== String(puffs)) {
            return false;
        }

        if (flavor && item.flavor !== flavor) {
            return false;
        }

        return true;
    });

    if (variant) {
        pricePreview.textContent = formatCurrency(variant.price);
        stockPreview.textContent = `${parseInt(variant.stock_qty || 0, 10)} in stock${variant.puffs ? ` • ${variant.puffs} puffs` : ''}`;
        return;
    }

    if (!relevantVariants.length) {
        pricePreview.textContent = 'Select options to view price';
        stockPreview.textContent = requiresExplicitPuffSelection(variants, categoryInfo) && !puffs
            ? 'Choose puffs first.'
            : (categoryInfo.requires_flavor ? 'Choose a flavor first.' : '');
        return;
    }

    const prices = relevantVariants
        .map(item => parseFloat(item.price || 0))
        .filter(value => !Number.isNaN(value));

    if (!prices.length) {
        pricePreview.textContent = 'Select options to view price';
        stockPreview.textContent = '';
        return;
    }

    const minPrice = Math.min(...prices);
    const maxPrice = Math.max(...prices);
    pricePreview.textContent = minPrice === maxPrice
        ? formatCurrency(minPrice)
        : `${formatCurrency(minPrice)} to ${formatCurrency(maxPrice)}`;

    if (requiresExplicitPuffSelection(variants, categoryInfo) && !puffs) {
        stockPreview.textContent = 'Choose puffs first.';
    } else if (categoryInfo.requires_flavor && !flavor) {
        stockPreview.textContent = 'Choose a flavor to see the exact price.';
    } else {
        stockPreview.textContent = 'Price updates automatically from the matching variant.';
    }
}

function normalizeCategoryKey(value) {
    return String(value || '').toLowerCase().replace(/[^a-z0-9]+/g, '');
}

function normalizeFlavorKey(value) {
    return String(value || '').trim().toLowerCase();
}

function isFlavorInventoryCategory(category) {
    return ['pods', 'disposable', 'eliquid'].includes(normalizeCategoryKey(category));
}

function getFlavorInventoryForCategory(category) {
    const requestedKey = normalizeCategoryKey(category);

    for (const [categoryName, flavors] of Object.entries(flavorInventoryByCategory || {})) {
        if (normalizeCategoryKey(categoryName) === requestedKey) {
            return Array.isArray(flavors) ? flavors : [];
        }
    }

    return [];
}

function parseProductFlavors(product) {
    try {
        const parsed = JSON.parse(product.dataset.flavors || '[]');
        return Array.isArray(parsed)
            ? parsed.map(flavor => String(flavor || '').trim()).filter(Boolean)
            : [];
    } catch (error) {
        console.warn('Unable to parse product flavors:', error);
        return [];
    }
}

function escapeHtml(value) {
    return String(value || '').replace(/[&<>"']/g, character => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    }[character]));
}

function updateVisibleProductCount(count = null) {
    const counter = document.getElementById('productCount');

    if (!counter) {
        return;
    }

    if (count === null) {
        count = Array.from(document.querySelectorAll('.product-item'))
            .filter(product => product.style.display !== 'none')
            .length;
    }

    counter.textContent = count;
}

function applyProductFilters(options = {}) {
    const { scrollToFirstMatch = false } = options;
    const products = document.querySelectorAll('.product-item');
    const normalizedFlavor = normalizeFlavorKey(currentActiveFlavor);
    let visibleCount = 0;
    let firstVisibleProduct = null;

    products.forEach(product => {
        const productFlavors = parseProductFlavors(product);
        const name = (product.dataset.name || '').toLowerCase();
        const brand = (product.dataset.brand || '').toLowerCase();

        const matchesCategory = currentActiveCategory === '' || product.dataset.category === currentActiveCategory;
        const matchesFlavor = normalizedFlavor === '' || productFlavors.some(flavor => normalizeFlavorKey(flavor) === normalizedFlavor);
        const matchesSearch = currentSearchTerm === ''
            || name.includes(currentSearchTerm)
            || brand.includes(currentSearchTerm)
            || productFlavors.some(flavor => flavor.toLowerCase().includes(currentSearchTerm));

        const isVisible = matchesCategory && matchesFlavor && matchesSearch;
        product.style.display = isVisible ? 'block' : 'none';

        if (isVisible) {
            visibleCount++;

            if (!firstVisibleProduct) {
                firstVisibleProduct = product;
            }
        }
    });

    updateVisibleProductCount(visibleCount);

    if (scrollToFirstMatch && firstVisibleProduct) {
        firstVisibleProduct.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }

    return visibleCount;
}

// Initialize Category Sidebar
document.addEventListener('DOMContentLoaded', function() {
    const categorySidebar = document.getElementById('categorySidebar');
    const categoryIndicator = document.getElementById('categoryIndicator');
    const categoryOverlay = document.getElementById('categoryOverlay');
    const mainContent = document.getElementById('mainContent');
    
    if (!categorySidebar || !categoryIndicator || !categoryOverlay || !mainContent) {
        console.error('Category sidebar elements not found');
        return;
    }
    
    // Initialize main content as category-hidden
    mainContent.classList.add('category-hidden');
    
    // Category indicator mouse events
    categoryIndicator.addEventListener('mouseenter', function() {
        clearTimeout(categoryTimeout);
        showCategorySidebar();
    });
    
    categoryIndicator.addEventListener('mouseleave', function() {
        isMouseOverCategoryIndicator = false;
        startCategoryHideTimer();
    });
    
    categoryIndicator.addEventListener('mouseenter', function() {
        isMouseOverCategoryIndicator = true;
        clearTimeout(categoryTimeout);
    });
    
    // Category sidebar mouse events
    categorySidebar.addEventListener('mouseenter', function() {
        isMouseOverCategory = true;
        clearTimeout(categoryTimeout);
    });
    
    categorySidebar.addEventListener('mouseleave', function() {
        isMouseOverCategory = false;
        startCategoryHideTimer();
    });
    
    // Category overlay click to hide
    categoryOverlay.addEventListener('click', function() {
        hideCategorySidebar();
    });
    
    // ESC key to close category sidebar
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && categorySidebar.classList.contains('show')) {
            hideCategorySidebar();
        }
    });
});

// Show category sidebar
function showCategorySidebar() {
    const categorySidebar = document.getElementById('categorySidebar');
    const categoryOverlay = document.getElementById('categoryOverlay');
    const mainContent = document.getElementById('mainContent');
    
    categorySidebar.classList.add('show');
    categoryOverlay.classList.add('show');
    mainContent.classList.remove('category-hidden');
    mainContent.classList.add('category-visible');
    clearTimeout(categoryTimeout);
}

// Hide category sidebar
function hideCategorySidebar() {
    const categorySidebar = document.getElementById('categorySidebar');
    const categoryOverlay = document.getElementById('categoryOverlay');
    const mainContent = document.getElementById('mainContent');
    
    categorySidebar.classList.remove('show');
    categoryOverlay.classList.remove('show');
    mainContent.classList.remove('category-visible');
    mainContent.classList.add('category-hidden');
}

// Start hide timer for category sidebar
function startCategoryHideTimer() {
    clearTimeout(categoryTimeout);
    categoryTimeout = setTimeout(() => {
        if (!isMouseOverCategory && !isMouseOverCategoryIndicator) {
            hideCategorySidebar();
        }
    }, 300); // 300ms delay before hiding
}

// Filter by category from sidebar
function filterByCategorySidebar(category) {
    // Update active states
    document.querySelectorAll('.category-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Set new active category
    if (category) {
        const activeItem = document.querySelector(`.category-item[data-category="${category}"]`);
        
        if (activeItem) activeItem.classList.add('active');
    } else {
        const activeItem = document.querySelector('.category-item[data-category=""]');
        if (activeItem) activeItem.classList.add('active');
    }
    
    currentActiveCategory = category;

    currentActiveFlavor = '';
    document.querySelectorAll('.flavor-item').forEach(item => {
        item.classList.remove('active');
    });

    if (category) {
        loadFlavorsForCategory(category);
    } else {
        hideFlavorsSection();
    }

    applyProductFilters();
}

// Load flavors for specific category
function loadFlavorsForCategory(category) {
    const flavorsSection = document.getElementById('flavorsSection');

    if (!isFlavorInventoryCategory(category)) {
        hideFlavorsSection();
        return;
    }

    flavorsSection.style.display = 'block';

    displayFlavors(getFlavorInventoryForCategory(category));
}

// Display flavors in the sidebar
function displayFlavors(flavors) {
    const flavorsList = document.getElementById('flavorsList');
    
    if (!flavors || flavors.length === 0) {
        flavorsList.innerHTML = '<div class="flavor-item"><span class="flavor-name">No flavors available</span></div>';
        return;
    }

    let html = '';
    flavors.forEach(flavorItem => {
        const flavorName = String(flavorItem.flavor || '').trim();
        const totalStock = parseInt(flavorItem.total_stock || 0, 10);
        const puffCounts = Array.isArray(flavorItem.puff_counts)
            ? [...new Set(flavorItem.puff_counts.map(value => parseInt(value, 10)).filter(value => value > 0))]
            : [];
        const puffsText = puffCounts.length > 0 ? `${puffCounts.join(', ')} puffs` : 'Flavor available';
        const encodedFlavorName = encodeURIComponent(flavorName);

        html += `
            <div class="flavor-item clickable" 
                 onclick="filterByFlavor(decodeURIComponent(this.dataset.flavor))" 
                 title="Click to show all ${escapeHtml(flavorName)} products"
                 data-flavor="${encodedFlavorName}">
                <i class="fas fa-tint me-2 flavor-icon"></i>
                <span class="flavor-name">${escapeHtml(flavorName)}</span>
                <div class="flavor-details">
                    <span class="flavor-puffs">${escapeHtml(puffsText)}</span>
                    <span class="flavor-stock ${totalStock > 0 ? '' : 'out-of-stock'}">
                        <i class="fas fa-box me-1"></i>${totalStock > 0 ? totalStock : 'Out of stock'}
                    </span>
                </div>
            </div>
        `;
    });
    
    flavorsList.innerHTML = html;
}

// Hide flavors section
function hideFlavorsSection() {
    const flavorsSection = document.getElementById('flavorsSection');
    flavorsSection.style.display = 'none';
}

// Filter by flavor
function filterByFlavor(flavorName) {
    console.log(`Filtering by flavor: ${flavorName}`);
    
    // Update active states
    document.querySelectorAll('.flavor-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Set active flavor
    const encodedFlavorName = encodeURIComponent(flavorName);
    const clickedItem = Array.from(document.querySelectorAll('.flavor-item'))
        .find(item => item.dataset.flavor === encodedFlavorName);
    if (clickedItem) {
        clickedItem.classList.add('active');
    }
    
    currentActiveFlavor = flavorName;
    const foundCount = applyProductFilters({ scrollToFirstMatch: true });
    // Always show a message (success or info)
    if (foundCount > 0) {
        showFlavorFilterMessage(flavorName, foundCount, false);
    } else {
        currentActiveFlavor = '';
        if (clickedItem) {
            clickedItem.classList.remove('active');
        }
        applyProductFilters();
        showFlavorFilterMessage(flavorName, 0, true);
    }
}

// Test function to verify message system
function testFlavorMessage() {
    console.log('Testing flavor message system...');
    showFlavorFilterMessage('Test Flavor', 3, false);
    setTimeout(() => {
        showFlavorFilterMessage('Test Flavor', 0, true);
    }, 2000);
}

// Show flavor filter message
function showFlavorFilterMessage(flavorName, count, isError = false) {
    // Remove existing message
    const existingMessage = document.querySelector('.flavor-filter-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Create message element
    const message = document.createElement('div');
    message.className = `flavor-filter-message ${isError ? 'error' : 'success'}`;
    
    if (isError) {
        message.innerHTML = `
            <i class="fas fa-info-circle me-2"></i>
            Showing all products in category (no exact "${escapeHtml(flavorName)}" matches found)
        `;
    } else {
        message.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            Found ${count} product${count > 1 ? 's' : ''} for "${escapeHtml(flavorName)}"
        `;
    }
    
    // Style the message
    message.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${isError ? '#3b82f6' : '#10b981'};
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        font-size: 0.9rem;
        font-weight: 500;
        animation: slideInRight 0.3s ease;
        max-width: 350px;
        line-height: 1.4;
    `;
    
    // Add animation if not already added
    if (!document.querySelector('#flavor-message-style')) {
        const style = document.createElement('style');
        style.id = 'flavor-message-style';
        style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(message);
    
    // Remove message after 4 seconds
    setTimeout(() => {
        message.style.animation = 'slideInRight 0.3s ease reverse';
        setTimeout(() => {
            if (message.parentNode) {
                message.remove();
            }
        }, 300);
    }, 4000);
}

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
                } else if (data.success) {
                    // Handle no variants case
                    console.warn('No variants found for product:', name);
                    // Try to add as a simple product
                    legacyAddToCart(name, brand, category);
                } else {
                    alert(data.message || 'Error loading product variants');
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
                alert(data.message || 'Error loading product variants');
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
    const puffOptions = getPuffOptionData(variants);
    const showsPuffSelector = (categoryInfo.requires_puffs.required || categoryInfo.requires_puffs.optional) && puffOptions.length > 0;
    
    let html = `
        <div class="mb-3">
            <h6 class="text-muted">${productName} ${brand ? '- ' + brand : ''}</h6>
        </div>
    `;
    
    // Puff selection comes first
    if (showsPuffSelector) {
        const required = categoryInfo.requires_puffs.required ? ' <span class="text-danger">*</span>' : '';
        html += `
            <div class="mb-3">
                <label class="form-label">Puffs${required}</label>
                <select class="form-select" id="puffSelect" onchange="updateVariantSelection()">
                    <option value="">Select Puffs</option>
                    ${puffOptions.map(option => `<option value="${option.puffs}" ${puffOptions.length === 1 ? 'selected' : ''}>${escapeHtml(formatPuffOptionLabel(option))}</option>`).join('')}
                </select>
            </div>
        `;
    }
    
    // Flavor selection
    if (categoryInfo.requires_flavor) {
        const flavors = getFlavorOptionData(variants, puffOptions.length === 1 ? puffOptions[0].puffs : null);
        if (flavors.length > 0 || !showsPuffSelector) {
            const requiresPuffsFirst = showsPuffSelector && puffOptions.length > 1;
            html += `
                <div class="mb-3">
                    <label class="form-label">Flavor <span class="text-danger">*</span></label>
                    <select class="form-select" id="flavorSelect" onchange="updateVariantSelection()" ${requiresPuffsFirst ? 'disabled' : ''}>
                        <option value="">${requiresPuffsFirst ? 'Select Puffs First' : 'Select Flavor'}</option>
                        ${flavors.map(flavor => `<option value="${flavor}">${flavor}</option>`).join('')}
                    </select>
                </div>
            `;
        }
    }

    html += `
        <div class="variant-price-preview-card">
            <div class="card-body py-3">
                <small class="variant-price-label d-block mb-1">Selected Price</small>
                <div class="variant-price-value" id="variantPricePreview">Select options to view price</div>
                <small class="variant-price-stock d-block mt-1" id="variantStockPreview"></small>
            </div>
        </div>
    `;
    
    variantContent.innerHTML = html;
    
    // Store data for variant selection
    window.currentVariantData = {
        variants: variants,
        categoryInfo: categoryInfo,
        productName: productName,
        brand: brand,
        category: category
    };

    updateVariantSelection();
}

// Update variant selection based on dropdowns
function updateVariantSelection() {
    const flavorSelect = document.getElementById('flavorSelect');
    const puffSelect = document.getElementById('puffSelect');
    let puffs = puffSelect ? (puffSelect.value || null) : null;
    const { variants, categoryInfo, productName, brand, category } = window.currentVariantData;

    if (flavorSelect && categoryInfo.requires_flavor) {
        const availableFlavors = getFlavorOptionData(variants, puffs);
        const currentFlavor = flavorSelect.value || null;
        const requiresPuffsFirst = requiresExplicitPuffSelection(variants, categoryInfo) && !puffs;

        flavorSelect.innerHTML = `<option value="">${requiresPuffsFirst ? 'Select Puffs First' : 'Select Flavor'}</option>`;
        availableFlavors.forEach((flavorOption) => {
            const optionElement = document.createElement('option');
            optionElement.value = flavorOption;
            optionElement.textContent = flavorOption;
            flavorSelect.appendChild(optionElement);
        });

        flavorSelect.disabled = requiresPuffsFirst;

        if (!requiresPuffsFirst && currentFlavor && availableFlavors.includes(currentFlavor)) {
            flavorSelect.value = currentFlavor;
        } else if (!requiresPuffsFirst && availableFlavors.length === 1) {
            flavorSelect.value = availableFlavors[0];
        } else {
            flavorSelect.value = '';
        }
    }

    const flavor = flavorSelect ? (flavorSelect.value || null) : null;
    const puffSelectionRequired = requiresExplicitPuffSelection(variants, categoryInfo);
    
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
        if (puffSelectionRequired) {
            if (!puffs) {
                matches = false;
            } else {
                matches = matches && variant.puffs == puffs;
            }
        } else if (categoryInfo.requires_puffs.optional || categoryInfo.requires_puffs.required) {
            if (puffs) {
                matches = matches && variant.puffs == puffs;
            }
        }
        
        if (matches) {
            selectedVariant = variant;
            break;
        }
    }
    
    const addBtn = document.getElementById('addVariantToCartBtn');
    renderVariantPricePreview(selectedVariant, variants, categoryInfo, flavor, puffs);
    
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
        const { categoryInfo, variants } = window.currentVariantData;
        const flavorSelect = document.getElementById('flavorSelect');
        const puffSelect = document.getElementById('puffSelect');
        const flavor = flavorSelect ? (flavorSelect.value || null) : null;
        const puffs = puffSelect ? (puffSelect.value || null) : null;
        
        let errorMessage = 'Please select ';
        const missingFields = [];
        
        if (requiresExplicitPuffSelection(variants, categoryInfo) && !puffs) {
            missingFields.push('puffs');
        }
        if (categoryInfo.requires_flavor && !flavor) {
            missingFields.push('flavor');
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
                alert(data.message || 'Product not found or out of stock');
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
    currentSearchTerm = document.getElementById('searchProduct').value.toLowerCase().trim();
    applyProductFilters();
}

function resetFilters() {
    document.getElementById('searchProduct').value = '';
    
    // Reset category sidebar active state
    document.querySelectorAll('.category-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Reset flavor active states
    document.querySelectorAll('.flavor-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Set "All Categories" as active
    const allCategoriesItem = document.querySelector('.category-item[data-category=""]');
    if (allCategoriesItem) {
        allCategoriesItem.classList.add('active');
    }
    
    // Hide flavors section
    hideFlavorsSection();
    
    currentActiveCategory = '';
    currentActiveFlavor = '';
    currentSearchTerm = '';
    applyProductFilters();
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
