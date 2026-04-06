<?= $this->include('layouts/header') ?>

<style>
.category-section {
    border: none;
    border-radius: 0;
    padding: 0;
    background: transparent;
    margin-bottom: 2.5rem;
}

.category-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 1.5rem;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 1rem;
    margin-bottom: 1.1rem;
}

.category-info {
    min-width: 0;
}

.category-meta {
    margin: 0.35rem 0 0;
    color: rgba(255, 255, 255, 0.68) !important;
    font-size: 0.98rem;
    line-height: 1.35;
}

.category-stats .badge {
    font-size: 0.78rem;
    font-weight: 600;
    padding: 0.55rem 0.85rem;
    border-radius: 999px;
}

.category-stats {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    align-items: center;
    gap: 0.55rem;
}

.category-stats .badge {
    margin: 0 !important;
}

.table-sm th {
    font-weight: 600;
    font-size: 0.85rem;
    border-top: none;
    color: #ffffff !important;
}

.table-sm td {
    vertical-align: middle;
    font-size: 0.9rem;
    color: #ffffff !important;
}

.badge.bg-light.text-dark {
    background-color: #f8f9fa !important;
    color: #495057 !important;
    border: 1px solid #dee2e6;
}

hr.my-4 {
    display: none;
}

.text-muted {
    color: #ffffff !important;
}

.fw-semibold {
    color: #ffffff !important;
}

.category-info h4 {
    color: #ffffff !important;
}

.category-title {
    margin: 0;
    color: #ffffff !important;
    font-size: 2rem;
    line-height: 1.1;
    letter-spacing: -0.02em;
}

.badge {
    font-size: 0.75em;
}

.product-name {
    color: #ffffff !important;
}

.brand-name {
    color: #ffffff !important;
}

.flavor-badge {
    /* Colors handled by inline styles */
    font-size: 0.75em;
    padding: 0.25em 0.5em;
}

.flavor-group-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem 0.65rem;
}

.flavor-puff-grid {
    display: grid;
    grid-template-columns: repeat(var(--puff-group-columns, 1), minmax(0, 1fr));
    gap: 0.9rem 1.4rem;
    align-items: start;
}

.flavor-puff-group {
    min-width: 0;
}

.flavor-puff-heading {
    margin-bottom: 0.45rem;
    color: rgba(255, 255, 255, 0.72);
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.flavor-puff-group .flavor-group-list {
    flex-direction: column;
    align-items: flex-start;
}

@media (max-width: 991.98px) {
    .flavor-puff-grid {
        grid-template-columns: 1fr;
    }
}

.flavor-badge-stock {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.32rem 0.62rem;
    border-radius: 999px;
    line-height: 1.2;
}

.product-submeta {
    color: rgba(255, 255, 255, 0.7) !important;
    font-size: 0.82rem;
}

.group-status-badge {
    min-width: 78px;
}

.puffs-count {
    color: #ffffff !important;
    display: inline-block;
    width: 100%;
    line-height: 1.45;
}

.value-stack {
    display: grid;
    gap: 0.35rem;
    width: 100%;
    line-height: 1.45;
}

.value-stack-item {
    display: block;
    width: 100%;
}

.price-display {
    color: #ffffff !important;
    display: inline-block;
    font-weight: 700;
}

.category-table-simple .product-name-cell {
    min-width: 280px;
}

.category-table-simple col.col-id {
    width: 6%;
}

.category-table-simple col.col-name {
    width: 40%;
}

.category-table-simple col.col-brand {
    width: 14%;
}

.category-table-simple col.col-price {
    width: 12%;
}

.category-table-simple col.col-stock {
    width: 8%;
}

.category-table-simple col.col-status {
    width: 10%;
}

.category-table-simple col.col-actions {
    width: 10%;
}

.category-table-simple .column-brand,
.category-table-simple .brand-cell {
    min-width: 0;
}

.category-table-simple .column-price,
.category-table-simple .price-cell {
    text-align: center;
}

.category-table-simple .price-cell .price-display {
    width: auto;
}

.category-table-wrap {
    margin-top: 0.25rem;
}

.inventory-category-table {
    width: 100%;
    table-layout: fixed;
}

.inventory-category-table col.col-id {
    width: 60px;
}

.inventory-category-table col.col-name {
    width: 34%;
}

.inventory-category-table col.col-brand {
    width: 120px;
}

.inventory-category-table col.col-flavors {
    width: 320px;
}

.inventory-category-table col.col-puffs {
    width: 120px;
}

.inventory-category-table col.col-price {
    width: 110px;
}

.inventory-category-table col.col-stock {
    width: 90px;
}

.inventory-category-table col.col-status {
    width: 110px;
}

.inventory-category-table col.col-actions {
    width: 190px;
}

.inventory-category-table thead th {
    padding: 1.05rem 1rem;
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    vertical-align: middle;
    white-space: nowrap;
}

.inventory-category-table tbody td {
    padding: 1.1rem 1rem;
    vertical-align: middle;
}

.inventory-category-table .column-id,
.inventory-category-table .column-id-cell,
.inventory-category-table .column-stock,
.inventory-category-table .stock-cell,
.inventory-category-table .column-status,
.inventory-category-table .status-cell,
.inventory-category-table .column-actions,
.inventory-category-table .action-cell {
    text-align: center;
}

.inventory-category-table .column-brand,
.inventory-category-table .brand-cell {
    min-width: 120px;
}

.inventory-category-table .column-flavors,
.inventory-category-table .flavor-options-cell {
    min-width: 320px;
}

.inventory-category-table .column-puffs,
.inventory-category-table .puffs-cell {
    min-width: 120px;
    text-align: left;
}

.inventory-category-table .column-price,
.inventory-category-table .price-cell {
    white-space: nowrap;
    text-align: right;
}

.inventory-category-table .product-name-cell {
    min-width: 220px;
    overflow-wrap: break-word;
}

.inventory-category-table .puffs-cell .value-stack {
    justify-items: start;
}

.inventory-category-table .price-cell .value-stack {
    justify-items: end;
}

.inventory-category-table .price-cell .price-display {
    display: inline-block;
    width: 100%;
}

.inventory-category-table .action-cell .btn-group {
    gap: 0.45rem;
}

.inventory-category-table .action-cell .btn {
    border-radius: 12px;
}

.flavor-summary-card {
    display: grid;
    gap: 0.7rem;
    padding: 0.9rem 1rem;
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.flavor-summary-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 0.55rem 1rem;
    align-items: center;
}

.flavor-summary-stat {
    color: rgba(255, 255, 255, 0.72);
    font-size: 0.78rem;
    letter-spacing: 0.02em;
    text-transform: uppercase;
}

.flavor-summary-stat strong {
    color: #ffffff;
    font-size: 1rem;
    font-weight: 700;
    margin-right: 0.3rem;
}

.flavor-summary-puffs {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
}

.flavor-summary-chip {
    display: inline-flex;
    align-items: center;
    padding: 0.32rem 0.62rem;
    border-radius: 999px;
    background: rgba(93, 173, 226, 0.12);
    border: 1px solid rgba(93, 173, 226, 0.28);
    color: #a7d8ff;
    font-size: 0.76rem;
    font-weight: 700;
    line-height: 1;
}

.flavor-summary-chip.is-muted {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(255, 255, 255, 0.12);
    color: rgba(255, 255, 255, 0.62);
}

.btn-view-flavors {
    border-color: rgba(89, 201, 255, 0.55);
    color: #8adfff;
    background: rgba(89, 201, 255, 0.08);
}

.btn-view-flavors:hover {
    border-color: rgba(120, 216, 255, 0.78);
    color: #ffffff;
    background: rgba(89, 201, 255, 0.2);
}

.flavor-view-modal .modal-dialog {
    max-width: 980px;
}

.flavor-view-modal .modal-content {
    background: linear-gradient(180deg, rgba(33, 35, 51, 0.98) 0%, rgba(24, 25, 39, 0.98) 100%);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 28px;
    box-shadow: 0 28px 70px rgba(0, 0, 0, 0.42);
    color: #ffffff;
}

.flavor-view-modal .modal-header,
.flavor-view-modal .modal-footer {
    border-color: rgba(255, 255, 255, 0.08);
}

.flavor-view-modal .modal-header {
    padding: 1.4rem 1.5rem 1.15rem;
}

.flavor-view-modal .modal-body {
    padding: 1.35rem 1.5rem 1.5rem;
}

.flavor-view-kicker {
    color: rgba(255, 255, 255, 0.58);
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.flavor-view-title {
    margin: 0.3rem 0 0;
    font-size: 1.65rem;
    font-weight: 800;
    color: #ffffff;
    line-height: 1.05;
}

.flavor-view-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.55rem;
    margin-top: 0.9rem;
}

.flavor-view-meta-pill {
    display: inline-flex;
    align-items: center;
    padding: 0.42rem 0.78rem;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.82);
    font-size: 0.8rem;
    font-weight: 700;
}

.flavor-view-meta-pill.is-brand {
    background: rgba(80, 200, 180, 0.12);
    border-color: rgba(80, 200, 180, 0.22);
    color: #b4fff2;
}

.flavor-view-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 0.9rem;
    margin-bottom: 1.2rem;
}

.flavor-view-stat {
    min-width: 0;
    padding: 1rem 1.05rem;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.flavor-view-stat.is-wide {
    grid-column: span 2;
}

.flavor-view-stat-label {
    display: block;
    margin-bottom: 0.55rem;
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

.flavor-view-stat-value {
    display: block;
    color: #ffffff;
    font-size: 1.55rem;
    font-weight: 800;
    line-height: 1;
}

.flavor-view-stat-subvalue {
    display: block;
    margin-top: 0.35rem;
    color: rgba(255, 255, 255, 0.72);
    font-size: 0.86rem;
    font-weight: 600;
}

.flavor-view-price-list {
    display: grid;
    gap: 0.45rem;
}

.flavor-view-price-line {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 0.75rem;
    color: #ffffff;
}

.flavor-view-price-label {
    color: rgba(255, 255, 255, 0.65);
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
}

.flavor-view-price-value {
    color: #ffffff;
    font-size: 1rem;
    font-weight: 800;
}

.flavor-view-groups {
    display: grid;
    gap: 1rem;
}

.flavor-view-group {
    padding: 1rem 1.05rem;
    border-radius: 22px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.flavor-view-group-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 0.95rem;
}

.flavor-view-group-title {
    margin: 0;
    color: #ffffff;
    font-size: 1rem;
    font-weight: 800;
}

.flavor-view-group-subtitle {
    margin: 0.28rem 0 0;
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.82rem;
}

.flavor-view-group-price {
    color: #ffffff;
    font-size: 0.96rem;
    font-weight: 800;
    white-space: nowrap;
}

.flavor-view-list {
    display: grid;
    gap: 0.55rem;
}

.flavor-view-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.85rem;
    padding: 0.8rem 0.9rem;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.06);
}

.flavor-view-item-main {
    min-width: 0;
}

.flavor-view-item-name {
    display: block;
    color: #ffffff;
    font-size: 0.95rem;
    font-weight: 700;
    overflow-wrap: break-word;
}

.flavor-view-item-note {
    display: block;
    margin-top: 0.22rem;
    color: rgba(255, 255, 255, 0.58);
    font-size: 0.78rem;
}

.flavor-view-stock {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 54px;
    padding: 0.42rem 0.7rem;
    border-radius: 999px;
    font-size: 0.82rem;
    font-weight: 800;
    line-height: 1;
}

.flavor-view-stock.is-good {
    background: rgba(46, 204, 113, 0.16);
    color: #9bffbf;
    border: 1px solid rgba(46, 204, 113, 0.26);
}

.flavor-view-stock.is-low {
    background: rgba(241, 196, 15, 0.16);
    color: #ffe59a;
    border: 1px solid rgba(241, 196, 15, 0.24);
}

.flavor-view-stock.is-out {
    background: rgba(255, 99, 132, 0.14);
    color: #ffb4c4;
    border: 1px solid rgba(255, 99, 132, 0.24);
}

.flavor-view-empty {
    padding: 1.2rem 1rem;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px dashed rgba(255, 255, 255, 0.14);
    color: rgba(255, 255, 255, 0.68);
    text-align: center;
}

.stock-badge {
    color: #ffffff !important;
}

.status-badge {
    color: #ffffff !important;
}

.form-control[readonly] {
    background: rgba(255, 255, 255, 0.12);
}

.inventory-shell {
    margin-top: 0.35rem;
    padding: 1.35rem;
    border-radius: 22px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.inventory-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
}

.inventory-title {
    margin: 0;
    color: #fff;
    font-size: 1.12rem;
    font-weight: 700;
}

.inventory-description {
    margin: 0.4rem 0 0;
    color: rgba(255, 255, 255, 0.68);
}

.btn-add-flavor {
    min-width: 132px;
    border-radius: 14px;
    border: 1px solid rgba(110, 99, 255, 0.52);
    color: #a79eff;
    background: rgba(110, 99, 255, 0.08);
    font-weight: 600;
}

.btn-add-flavor:hover {
    color: #fff;
    background: rgba(110, 99, 255, 0.18);
    border-color: rgba(130, 120, 255, 0.72);
}

.inventory-grid {
    border-radius: 18px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(255, 255, 255, 0.05);
}

.inventory-grid-head,
.flavor-inventory-row {
    display: grid;
    grid-template-columns: minmax(0, 1.9fr) minmax(140px, 0.72fr) 72px;
    gap: 1rem;
    align-items: center;
    padding: 1rem 1.1rem;
}

.inventory-grid.with-puffs .inventory-grid-head,
.inventory-grid.with-puffs .flavor-inventory-row {
    grid-template-columns: minmax(0, 1.6fr) minmax(150px, 0.82fr) minmax(140px, 0.72fr) 72px;
}

.inventory-grid-head,
.flavor-inventory-row {
    grid-template-columns: minmax(0, 1.65fr) minmax(140px, 0.8fr) minmax(140px, 0.72fr) 72px;
}

.inventory-grid.with-puffs .inventory-grid-head,
.inventory-grid.with-puffs .flavor-inventory-row {
    grid-template-columns: minmax(0, 1.4fr) minmax(150px, 0.82fr) minmax(140px, 0.82fr) minmax(140px, 0.72fr) 72px;
}

.inventory-grid-head,
.flavor-inventory-row,
.inventory-grid.with-puffs .inventory-grid-head,
.inventory-grid.with-puffs .flavor-inventory-row {
    grid-template-columns: minmax(0, 1.9fr) minmax(140px, 0.72fr) 72px;
}

.inventory-grid-head {
    background: rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.88);
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.inventory-puffs-heading,
.variant-puff-cell {
    display: none;
}

.inventory-price-heading,
.variant-price-cell {
    display: block;
}

.inventory-puffs-heading,
.variant-puff-cell,
.inventory-price-heading,
.variant-price-cell {
    display: none !important;
}

.inventory-grid.with-puffs .inventory-puffs-heading,
.inventory-grid.with-puffs .variant-puff-cell {
    display: block;
}

.inventory-grid-body .flavor-inventory-row + .flavor-inventory-row {
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}

.inventory-remove-btn {
    width: 42px;
    height: 42px;
    border: none;
    border-radius: 12px;
    background: rgba(255, 77, 109, 0.08);
    color: #ff5f7d;
    font-size: 1rem;
    font-weight: 700;
}

.inventory-remove-btn:hover {
    background: rgba(255, 77, 109, 0.18);
    color: #ffd8df;
}

.inventory-hint {
    display: block;
    margin-top: 0.9rem;
    color: rgba(255, 255, 255, 0.62);
}

.puff-shortcut-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.55rem;
    margin-top: 0.75rem;
}

.puff-shortcut-btn {
    padding: 0.42rem 0.8rem;
    border-radius: 999px;
    border: 1px solid rgba(110, 99, 255, 0.38);
    background: rgba(110, 99, 255, 0.08);
    color: #d8d3ff;
    font-size: 0.82rem;
    font-weight: 700;
}

.puff-shortcut-btn:hover,
.puff-shortcut-btn.is-active {
    background: rgba(110, 99, 255, 0.22);
    border-color: rgba(130, 120, 255, 0.72);
    color: #fff;
}

@media (max-width: 767.98px) {
    .category-header {
        gap: 1rem;
    }

    .category-title {
        font-size: 1.7rem;
    }

    .category-stats {
        justify-content: flex-start;
    }

    .inventory-category-table thead th,
    .inventory-category-table tbody td {
        padding: 0.95rem 0.85rem;
    }

    .inventory-header {
        flex-direction: column;
        align-items: stretch;
    }

    .btn-add-flavor {
        width: 100%;
    }

    .inventory-grid-head {
        display: none;
    }

    .flavor-inventory-row,
    .inventory-grid.with-puffs .flavor-inventory-row {
        grid-template-columns: 1fr;
    }

    .inventory-remove-btn {
        width: 100%;
    }

    .inventory-category-table col.col-actions {
        width: 220px;
    }

    .flavor-view-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .flavor-view-stat.is-wide {
        grid-column: span 2;
    }

    .flavor-view-group-header {
        flex-direction: column;
    }

    .flavor-view-group-price {
        white-space: normal;
    }
}

@media (max-width: 575.98px) {
    .flavor-summary-card {
        padding: 0.8rem 0.85rem;
    }

    .flavor-view-title {
        font-size: 1.3rem;
    }

    .flavor-view-stats {
        grid-template-columns: 1fr;
    }

    .flavor-view-stat.is-wide {
        grid-column: span 1;
    }

    .flavor-view-price-line {
        flex-direction: column;
        align-items: flex-start;
    }

    .flavor-view-item {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title" style="color: #ffffff !important;">Stock Management</h1>
                <p class="page-subtitle" style="color: #ffffff !important;">Create, update, and monitor product inventory</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fas fa-plus me-2"></i>
                Add Product
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Products</h6>
                    <div class="stats-value text-info fw-bold"><?= number_format((int) $stats['total']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Active Products</h6>
                    <div class="stats-value text-success fw-bold"><?= number_format((int) $stats['active']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Low Stock (1-10)</h6>
                    <div class="stats-value text-warning"><?= (int) $stats['low_stock'] ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Out of Stock</h6>
                    <div class="stats-value text-danger"><?= (int) $stats['out_of_stock'] ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="q" class="form-label">Search</label>
                    <input type="text"
                           class="form-control"
                           id="q"
                           name="q"
                           placeholder="Search name, category, brand, or flavor (automatic search)"
                           value="<?= esc($filters['q'] ?? '') ?>"
                           autocomplete="off">
                </div>
                <div class="col-md-2">
                    <label for="category_filter" class="form-label">Category</label>
                    <select class="form-select" id="category_filter" name="category_filter">
                        <option value="">All Categories</option>
                        <?php if (isset($categories) && !empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= esc($cat['category']) ?>" <?= ($filters['category_filter'] ?? '') === $cat['category'] ? 'selected' : '' ?>>
                                    <?= esc($cat['category']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="" <?= empty($filters['status']) ? 'selected' : '' ?>>All</option>
                        <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="brand_filter" class="form-label">Brand</label>
                    <select class="form-select" id="brand_filter" name="brand_filter">
                        <option value="">All Brands</option>
                        <?php if (isset($brands) && !empty($brands)): ?>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= esc($brand['brand']) ?>" <?= ($filters['brand_filter'] ?? '') === $brand['brand'] ? 'selected' : '' ?>>
                                    <?= esc($brand['brand']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button onclick="resetSearch()" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-refresh me-2"></i>Reset All
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0" style="color: #ffffff !important;">Product Inventory (<?= $totalProducts ?> products)</h5>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($groupedProducts)): ?>
                <?php foreach ($groupedProducts as $category => $products): ?>
                    <!-- Category Section -->
                    <div class="category-section mb-4">
                        <div class="category-header">
                            <div class="category-info flex-grow-1">
                                <?php
                                $categoryColors = [
                                    'Pods' => 'primary',
                                    'Device' => 'success', 
                                    'E-liquid' => 'danger',
                                    'Disposable' => 'warning',
                                    'Pods Kit' => 'info',
                                    'Accessory' => 'secondary'
                                ];
                                $color = $categoryColors[$category] ?? 'dark';
                                ?>
                                <h4 class="text-<?= $color ?> category-title"><?= esc($category) ?></h4>
                                <p class="category-meta"><?= count($products) ?> products in this category</p>
                            </div>
                            <div class="category-stats">
                                <?php
                                $activeCount = 0;
                                $lowStockCount = 0;
                                $outOfStockCount = 0;
                                $totalStock = 0;
                                 
                                foreach ($products as $product) {
                                    if (($product['status_state'] ?? 'inactive') !== 'inactive') {
                                        $activeCount++;
                                        if (($product['total_stock'] ?? 0) === 0) {
                                            $outOfStockCount++;
                                        } elseif (($product['total_stock'] ?? 0) <= 10) {
                                            $lowStockCount++;
                                        }
                                    }
                                    $totalStock += (int) ($product['total_stock'] ?? 0);
                                }

                                $showFlavorOptionsColumn = false;
                                $showPuffsColumn = false;
                                foreach ($products as $product) {
                                    if (!empty($product['variant_options'])) {
                                        $showFlavorOptionsColumn = true;
                                    }

                                    if (!empty($product['puff_counts'])) {
                                        $showPuffsColumn = true;
                                    }

                                    if ($showFlavorOptionsColumn && $showPuffsColumn) {
                                        break;
                                    }
                                }
                                $reservePuffsColumn = $showFlavorOptionsColumn && !$showPuffsColumn;
                                $renderPuffsColumn = $showPuffsColumn || $reservePuffsColumn;
                                ?>
                                <span class="badge bg-success me-1"><?= $activeCount ?> Active</span>
                                <?php if ($lowStockCount > 0): ?>
                                    <span class="badge bg-warning me-1"><?= $lowStockCount ?> Low Stock</span>
                                <?php endif; ?>
                                <?php if ($outOfStockCount > 0): ?>
                                    <span class="badge bg-danger me-1"><?= $outOfStockCount ?> Out of Stock</span>
                                <?php endif; ?>
                                <span class="badge bg-info"><?= $totalStock ?> Total Stock</span>
                            </div>
                        </div>

                        <!-- Products Table for this Category -->
                        <div class="table-responsive category-table-wrap">
                            <table class="table table-sm table-hover inventory-category-table <?= (!$showFlavorOptionsColumn && !$showPuffsColumn) ? 'category-table-simple' : '' ?>">
                                <colgroup>
                                    <col class="col-id">
                                    <col class="col-name">
                                    <col class="col-brand">
                                    <?php if ($showFlavorOptionsColumn): ?>
                                        <col class="col-flavors">
                                    <?php endif; ?>
                                    <?php if ($renderPuffsColumn): ?>
                                        <col class="col-puffs">
                                    <?php endif; ?>
                                    <col class="col-price">
                                    <col class="col-stock">
                                    <col class="col-status">
                                    <col class="col-actions">
                                </colgroup>
                                <thead class="table-light">
                                    <tr>
                                        <th class="column-id" width="60">ID</th>
                                        <th class="column-name">Product Name</th>
                                        <th class="column-brand" width="100">Brand</th>
                                        <?php if ($showFlavorOptionsColumn): ?>
                                            <th class="column-flavors" width="320">Flavor Options</th>
                                        <?php endif; ?>
                                        <?php if ($renderPuffsColumn): ?>
                                            <th class="column-puffs" width="120">Puffs</th>
                                        <?php endif; ?>
                                        <th class="column-price" width="110">Price</th>
                                        <th class="column-stock" width="80">Stock</th>
                                        <th class="column-status" width="100">Status</th>
                                        <th class="column-actions" width="150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <?php
                                        $statusState = (string) ($product['status_state'] ?? 'inactive');
                                        $showActivateAction = $statusState !== 'active';
                                        $deleteMessage = (int) ($product['variant_count'] ?? 0) > 1
                                            ? 'Are you sure you want to delete this product group and all of its flavor variants?'
                                            : 'Are you sure you want to delete this product?';
                                        ?>
                                        <tr class="<?= $statusState === 'inactive' ? 'table-secondary' : '' ?>">
                                            <td class="column-id-cell"><?= (int) $product['id'] ?></td>
                                            <td class="product-name-cell">
                                                <div class="fw-semibold product-name"><?= esc((string) ($product['name'] ?? '')) ?></div>
                                            </td>
                                            <td class="brand-cell">
                                                <span class="brand-name"><?= !empty($product['brand']) ? esc((string) $product['brand']) : '-' ?></span>
                                            </td>
                                            <?php if ($showFlavorOptionsColumn): ?>
                                                <td class="flavor-options-cell">
                                                    <?php if (!empty($product['variant_options'])): ?>
                                                        <?php
                                                        $summaryFlavorCount = count($product['flavors'] ?? []);
                                                        $summaryPuffCounts = array_values(array_filter(array_map(
                                                            static fn ($puff) => $puff === null || $puff === '' ? null : (int) $puff,
                                                            $product['puff_counts'] ?? []
                                                        )));
                                                        $summaryVisiblePuffs = array_slice($summaryPuffCounts, 0, 3);
                                                        $summaryRemainingPuffs = max(0, count($summaryPuffCounts) - count($summaryVisiblePuffs));
                                                        ?>
                                                        <div class="flavor-summary-card">
                                                            <div class="flavor-summary-stats">
                                                                <span class="flavor-summary-stat"><strong><?= $summaryFlavorCount ?></strong> Flavors</span>
                                                                <span class="flavor-summary-stat"><strong><?= count($summaryPuffCounts) ?></strong> Puff Groups</span>
                                                            </div>
                                                            <?php if ($summaryVisiblePuffs !== []): ?>
                                                                <div class="flavor-summary-puffs">
                                                                    <?php foreach ($summaryVisiblePuffs as $summaryPuffCount): ?>
                                                                        <span class="flavor-summary-chip"><?= esc(number_format($summaryPuffCount)) ?></span>
                                                                    <?php endforeach; ?>
                                                                    <?php if ($summaryRemainingPuffs > 0): ?>
                                                                        <span class="flavor-summary-chip is-muted">+<?= $summaryRemainingPuffs ?> more</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endif; ?>
                                            <?php if ($renderPuffsColumn): ?>
                                                <td class="puffs-cell">
                                                    <?php if ($showPuffsColumn && !empty($product['puff_counts'])): ?>
                                                        <?php if (count($product['puff_counts']) > 1): ?>
                                                            <span class="text-muted puffs-count value-stack">
                                                                <?php foreach ($product['puff_counts'] as $puffCount): ?>
                                                                    <span class="value-stack-item"><?= number_format((int) $puffCount) ?></span>
                                                                <?php endforeach; ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted puffs-count"><?= esc(implode(' / ', array_map(static fn ($puff) => number_format((int) $puff), $product['puff_counts']))) ?></span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endif; ?>
                                            <td class="price-cell">
                                                <?php if (!empty($product['puff_prices']) && count($product['puff_prices']) > 1): ?>
                                                    <span class="value-stack">
                                                        <?php foreach ($product['puff_prices'] as $puffPrice): ?>
                                                            <span class="value-stack-item price-display"><?= $puffPrice['price_display'] ?></span>
                                                        <?php endforeach; ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="price-display"><?= $product['price_display'] ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="stock-cell">
                                                <?php if (($product['total_stock'] ?? 0) > 10): ?>
                                                    <span class="badge bg-success stock-badge"><?= (int) ($product['total_stock'] ?? 0) ?></span>
                                                <?php elseif (($product['total_stock'] ?? 0) > 0): ?>
                                                    <span class="badge bg-warning stock-badge"><?= (int) ($product['total_stock'] ?? 0) ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger stock-badge">0</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="status-cell">
                                                <?php if ($statusState === 'active'): ?>
                                                    <span class="badge bg-success status-badge group-status-badge">Active</span>
                                                <?php elseif ($statusState === 'mixed'): ?>
                                                    <span class="badge bg-warning text-dark status-badge group-status-badge">Mixed</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary status-badge group-status-badge">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="action-cell">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <?php if (!empty($product['variant_options'])): ?>
                                                        <button type="button"
                                                                class="btn btn-view-flavors"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#viewFlavorsModal"
                                                                data-product-id="<?= (int) $product['id'] ?>"
                                                                title="View Flavors"
                                                                aria-label="View flavors for <?= esc((string) ($product['name'] ?? '')) ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <a href="<?= site_url('/products/edit/' . $product['id']) ?>" 
                                                       class="btn btn-outline-primary" 
                                                       title="<?= (int) ($product['variant_count'] ?? 0) > 1 ? 'Edit Product Group' : 'Edit Product' ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if ($showActivateAction): ?>
                                                        <a href="<?= site_url('/products/activate/' . $product['id']) ?>" 
                                                           class="btn btn-outline-success" 
                                                           title="<?= (int) ($product['variant_count'] ?? 0) > 1 ? 'Activate Product Group' : 'Activate Product' ?>">
                                                            <i class="fas fa-play"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="<?= site_url('/products/delete/' . $product['id']) ?>" 
                                                       class="btn btn-outline-danger" 
                                                       title="<?= (int) ($product['variant_count'] ?? 0) > 1 ? 'Delete Product Group' : 'Delete Product' ?>"
                                                       onclick="return confirm('<?= esc($deleteMessage, 'js') ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No products found</h5>
                    <p class="text-muted">Try adjusting your search filters or add your first product.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="fas fa-plus me-2"></i>Add Your First Product
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$flavorViewProducts = [];
foreach (($groupedProducts ?? []) as $categoryProducts) {
    foreach ($categoryProducts as $product) {
        $productId = (int) ($product['id'] ?? 0);
        if ($productId <= 0) {
            continue;
        }

        $flavorViewProducts[$productId] = [
            'id' => $productId,
            'name' => (string) ($product['name'] ?? ''),
            'brand' => (string) ($product['brand'] ?? ''),
            'category' => (string) ($product['category'] ?? ''),
            'total_stock' => (int) ($product['total_stock'] ?? 0),
            'flavors' => array_values(array_map(
                static function (array $flavor): array {
                    $puffCounts = array_values(array_filter(array_map(
                        static fn ($puff) => $puff === null || $puff === '' ? null : (int) $puff,
                        $flavor['puff_counts'] ?? []
                    )));
                    sort($puffCounts, SORT_NUMERIC);

                    return [
                        'flavor' => (string) ($flavor['flavor'] ?? ''),
                        'stock_qty' => (int) ($flavor['stock_qty'] ?? 0),
                        'variant_count' => (int) ($flavor['variant_count'] ?? 0),
                        'puff_counts' => $puffCounts,
                    ];
                },
                $product['flavors'] ?? []
            )),
            'variant_options' => array_values(array_map(
                static function (array $option): array {
                    $puffs = $option['puffs'] ?? null;

                    return [
                        'flavor' => (string) ($option['flavor'] ?? ''),
                        'puffs' => $puffs === null || $puffs === '' ? null : (int) $puffs,
                        'stock_qty' => (int) ($option['stock_qty'] ?? 0),
                        'variant_count' => (int) ($option['variant_count'] ?? 0),
                    ];
                },
                $product['variant_options'] ?? []
            )),
            'puff_counts' => array_values(array_filter(array_map(
                static fn ($puff) => $puff === null || $puff === '' ? null : (int) $puff,
                $product['puff_counts'] ?? []
            ))),
            'puff_prices' => array_values(array_map(
                static function (array $puffPrice): array {
                    return [
                        'puffs' => (int) ($puffPrice['puffs'] ?? 0),
                        'price_display' => html_entity_decode((string) ($puffPrice['price_display'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                    ];
                },
                $product['puff_prices'] ?? []
            )),
            'price_display' => html_entity_decode((string) ($product['price_display'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
        ];
    }
}

$modalSelectedCategoryOption = old('category', '');
$modalSelectedCategoryValue = $modalSelectedCategoryOption === '__new__' ? old('new_category') : $modalSelectedCategoryOption;
$modalNormalizedCategory = strtolower(trim((string) $modalSelectedCategoryValue));
$modalUsesFlavorInventory = in_array($modalNormalizedCategory, ['disposable', 'pods', 'e-liquid', 'e liquid', 'eliquid'], true);
$modalUsesManagedPuffs = in_array($modalNormalizedCategory, ['disposable', 'pods'], true);
$modalRequiresPuffs = $modalNormalizedCategory === 'disposable';
$modalDefaultVariantPuffs = old('default_variant_puffs', old('puffs', ''));
$modalInventoryRows = [];
$modalOldVariantIds = old('variant_ids');
$modalOldVariantFlavors = old('variant_flavors');
$modalOldVariantStocks = old('variant_stocks');
$modalOldVariantPuffs = old('variant_puffs');
$modalOldVariantPrices = old('variant_prices');

if (is_array($modalOldVariantIds) || is_array($modalOldVariantFlavors) || is_array($modalOldVariantStocks) || is_array($modalOldVariantPuffs) || is_array($modalOldVariantPrices)) {
    $modalOldVariantIds = is_array($modalOldVariantIds) ? array_values($modalOldVariantIds) : [];
    $modalOldVariantFlavors = is_array($modalOldVariantFlavors) ? array_values($modalOldVariantFlavors) : [];
    $modalOldVariantStocks = is_array($modalOldVariantStocks) ? array_values($modalOldVariantStocks) : [];
    $modalOldVariantPuffs = is_array($modalOldVariantPuffs) ? array_values($modalOldVariantPuffs) : [];
    $modalOldVariantPrices = is_array($modalOldVariantPrices) ? array_values($modalOldVariantPrices) : [];
    $modalRowCount = max(count($modalOldVariantIds), count($modalOldVariantFlavors), count($modalOldVariantStocks), count($modalOldVariantPuffs), count($modalOldVariantPrices));

    for ($index = 0; $index < $modalRowCount; $index++) {
        $rowId = trim((string) ($modalOldVariantIds[$index] ?? ''));
        $rowFlavor = trim((string) ($modalOldVariantFlavors[$index] ?? ''));
        $rowStock = trim((string) ($modalOldVariantStocks[$index] ?? ''));
        $rowPuffs = trim((string) ($modalOldVariantPuffs[$index] ?? ''));
        $rowPrice = trim((string) ($modalOldVariantPrices[$index] ?? ''));

        if ($rowId === '' && $rowFlavor === '' && $rowStock === '' && $rowPuffs === '' && $rowPrice === '') {
            continue;
        }

        $modalInventoryRows[] = [
            'id' => $rowId,
            'flavor' => $rowFlavor,
            'stock_qty' => $rowStock,
            'puffs' => $rowPuffs,
            'price' => $rowPrice,
        ];
    }
}

if ($modalUsesFlavorInventory && $modalInventoryRows === []) {
    $modalInventoryRows[] = ['id' => '', 'flavor' => '', 'stock_qty' => '', 'puffs' => '', 'price' => ''];
}

$modalInventoryTotalStock = 0;
foreach ($modalInventoryRows as $modalInventoryRow) {
    $modalInventoryTotalStock += max(0, (int) ($modalInventoryRow['stock_qty'] ?? 0));
}

$modalStockValue = old('stock_qty', $modalUsesFlavorInventory ? $modalInventoryTotalStock : 0);
$modalBasePuffChoices = [12000, 25000];
$modalAvailablePuffChoiceMap = [];
foreach ($modalBasePuffChoices as $modalBasePuffChoice) {
    $modalAvailablePuffChoiceMap[(int) $modalBasePuffChoice] = (int) $modalBasePuffChoice;
}
if ((int) $modalDefaultVariantPuffs > 0) {
    $modalAvailablePuffChoiceMap[(int) $modalDefaultVariantPuffs] = (int) $modalDefaultVariantPuffs;
}
foreach ($modalInventoryRows as $modalInventoryRow) {
    $modalRowPuffValue = (int) ($modalInventoryRow['puffs'] ?? 0);
    if ($modalRowPuffValue > 0) {
        $modalAvailablePuffChoiceMap[$modalRowPuffValue] = $modalRowPuffValue;
    }
}
$modalAvailablePuffChoices = array_values($modalAvailablePuffChoiceMap);
sort($modalAvailablePuffChoices, SORT_NUMERIC);
?>

<div class="modal fade flavor-view-modal" id="viewFlavorsModal" tabindex="-1" aria-labelledby="viewFlavorsModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="flavor-view-kicker">Flavor Details</div>
                    <h5 class="modal-title flavor-view-title" id="viewFlavorsModalTitle">Flavor Details</h5>
                    <div class="flavor-view-meta" id="viewFlavorsModalMeta"></div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="flavor-view-stats" id="viewFlavorsModalStats"></div>
                <div class="flavor-view-groups" id="viewFlavorsModalGroups"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">
                    <i class="fas fa-plus me-2"></i>Add Product
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= site_url('/products/store') ?>" method="POST" enctype="multipart/form-data" id="addProductForm">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name *</label>
                        <input type="text"
                               class="form-control"
                               id="name"
                               name="name"
                               required
                               maxlength="255"
                               placeholder="Enter product name"
                               value="<?= old('name') ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category *</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Select category...</option>
                                <?php if (!empty($categories ?? [])): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= esc($cat['category']) ?>" <?= old('category') === $cat['category'] ? 'selected' : '' ?>><?= esc($cat['category']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <option value="__new__" <?= old('category') === '__new__' ? 'selected' : '' ?>>+ Add New Category</option>
                            </select>
                            <input type="text" class="form-control mt-2" id="new_category" name="new_category" placeholder="Enter new category name" value="<?= old('new_category') ?>" style="display:<?= old('category') === '__new__' ? 'block' : 'none' ?>;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="brand" class="form-label">Brand</label>
                            <select class="form-select" id="brand" name="brand">
                                <option value="">Select brand...</option>
                                <?php if (!empty($brands ?? [])): ?>
                                    <?php foreach ($brands as $brand): ?>
                                        <option value="<?= esc($brand['brand']) ?>" <?= old('brand') === $brand['brand'] ? 'selected' : '' ?>><?= esc($brand['brand']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <option value="__new__" <?= old('brand') === '__new__' ? 'selected' : '' ?>>+ Add New Brand</option>
                            </select>
                            <input type="text" class="form-control mt-2" id="new_brand" name="new_brand" placeholder="Enter new brand name" value="<?= old('new_brand') ?>" style="display:<?= old('brand') === '__new__' ? 'block' : 'none' ?>;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label" id="priceLabel">Price *</label>
                            <input type="number"
                                   class="form-control"
                                   id="price"
                                   name="price"
                                   required
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00"
                                   value="<?= old('price', '0.00') ?>">
                            <small class="text-muted" id="priceHelp"><?= $modalUsesFlavorInventory ? 'Applied to all flavor rows below.' : 'Price for this product.' ?></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stock_qty" class="form-label" id="stockQtyLabel"><?= $modalUsesFlavorInventory ? 'Total Stock Quantity *' : 'Stock Quantity *' ?></label>
                            <input type="number"
                                   class="form-control"
                                   id="stock_qty"
                                   name="stock_qty"
                                   required
                                   step="1"
                                   min="0"
                                   placeholder="0"
                                   value="<?= esc((string) $modalStockValue) ?>"
                                   <?= $modalUsesFlavorInventory ? 'readonly' : '' ?>>
                            <small class="text-muted" id="stockQtyHelp"><?= $modalUsesFlavorInventory ? 'Total stock is based on the sum of all flavor quantities below.' : 'Current stock for this product.' ?></small>
                        </div>
                    </div>

                    <div class="row" id="defaultPuffsFieldContainer" style="display:<?= $modalUsesManagedPuffs ? 'flex' : 'none' ?>;">
                        <div class="col-md-6 mb-3">
                            <label for="default_variant_puffs" class="form-label" id="defaultPuffsLabel">Puff Count</label>
                            <input type="number"
                                   class="form-control"
                                   id="default_variant_puffs"
                                   name="default_variant_puffs"
                                   min="1"
                                   step="1"
                                   inputmode="numeric"
                                   list="puffChoiceList"
                                   placeholder="Type puff count or pick a suggestion"
                                   value="<?= esc((string) $modalDefaultVariantPuffs) ?>">
                            <datalist id="puffChoiceList">
                                <?php foreach ($modalAvailablePuffChoices as $puffChoice): ?>
                                    <option value="<?= (int) $puffChoice ?>"><?= esc(number_format((int) $puffChoice) . ' puffs') ?></option>
                                <?php endforeach; ?>
                            </datalist>
                            <div class="puff-shortcut-list" id="puffShortcutButtons">
                                <?php foreach ($modalAvailablePuffChoices as $puffChoice): ?>
                                    <?php $shortcutLabel = ((int) $puffChoice % 1000 === 0) ? ((int) $puffChoice / 1000) . 'k' : number_format((int) $puffChoice); ?>
                                    <button type="button" class="btn puff-shortcut-btn <?= (string) $modalDefaultVariantPuffs === (string) $puffChoice ? 'is-active' : '' ?>" data-puff-value="<?= (int) $puffChoice ?>"><?= esc($shortcutLabel) ?></button>
                                <?php endforeach; ?>
                            </div>
                            <small class="text-muted" id="defaultPuffsHelp">Type one puff count for all flavor rows or use the quick buttons.</small>
                        </div>
                    </div>

                    <div class="inventory-shell" id="flavorInventoryPanel" style="display:<?= $modalUsesFlavorInventory ? 'block' : 'none' ?>;">
                        <div class="inventory-header">
                            <div>
                                <h6 class="inventory-title">Flavor Inventory</h6>
                                <p class="inventory-description">Add one row per flavor. The price and puff count above apply to all flavor rows, and total stock is calculated automatically.</p>
                            </div>
                            <button type="button" class="btn btn-add-flavor" id="addFlavorRowButton">+ Add Flavor</button>
                        </div>
                            <div class="inventory-grid <?= $modalUsesManagedPuffs ? 'with-puffs' : '' ?>">
                            <div class="inventory-grid-head">
                                <div>Flavor Name</div>
                                <div>Flavor Stock</div>
                                <div class="text-end">Action</div>
                            </div>
                            <div class="inventory-grid-body" id="flavorRowsContainer">
                                <?php foreach ($modalInventoryRows as $row): ?>
                                    <div class="flavor-inventory-row">
                                        <input type="hidden" name="variant_ids[]" value="<?= esc((string) ($row['id'] ?? '')) ?>">
                                        <div><input type="text" class="form-control variant-flavor-input" name="variant_flavors[]" placeholder="e.g. Bacteria Monster (Yakult)" value="<?= esc((string) ($row['flavor'] ?? '')) ?>"></div>
                                        <div><input type="number" class="form-control variant-stock-input" name="variant_stocks[]" min="0" step="1" placeholder="0" value="<?= esc((string) ($row['stock_qty'] ?? '')) ?>"></div>
                                        <div class="text-md-end"><button type="button" class="inventory-remove-btn" aria-label="Remove flavor row">x</button></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <label for="image_file" class="form-label">Product Image Upload</label>
                        <input type="file"
                               class="form-control"
                               id="image_file"
                               name="image_file"
                               accept="image/jpeg,image/png,image/webp,image/gif">
                        <small class="text-muted">Optional. Choose JPG, PNG, WEBP, or GIF (max 4MB).</small>
                    </div>

                    <div class="mb-4">
                        <label for="is_active" class="form-label">Status *</label>
                        <select class="form-select" id="is_active" name="is_active" required>
                            <option value="1" <?= old('is_active', '1') === '1' ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= old('is_active') === '0' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                        <small class="text-muted">Inactive products are hidden from POS.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="submit" form="addProductForm" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Product
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const availablePuffChoices = <?= json_encode(array_values($modalAvailablePuffChoices), JSON_UNESCAPED_SLASHES) ?>;
const flavorViewProductMap = <?= json_encode($flavorViewProducts, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

function getSelectedCategoryValue() {
    const categorySelect = document.getElementById('category');
    const newCategoryInput = document.getElementById('new_category');
    return categorySelect.value === '__new__' ? newCategoryInput.value : categorySelect.value;
}

function isFlavorInventoryCategory(categoryValue) {
    const normalized = (categoryValue || '').toLowerCase();
    return normalized === 'disposable' || normalized.includes('disposable') ||
        normalized === 'pod' || normalized.includes('pod') ||
        normalized === 'pods' || normalized.includes('pods') ||
        normalized === 'e-liquid' || normalized.includes('e-liquid') ||
        normalized === 'e liquid' || normalized.includes('e liquid') ||
        normalized === 'eliquid' || normalized.includes('eliquid') ||
        normalized === 'liquid' || normalized.includes('liquid');
}

function categoryUsesManagedPuffs(categoryValue) {
    const normalized = (categoryValue || '').toLowerCase();
    return normalized === 'disposable' || normalized.includes('disposable') ||
        normalized === 'pod' || normalized.includes('pod') ||
        normalized === 'pods' || normalized.includes('pods');
}

function categoryRequiresPuffs(categoryValue) {
    const normalized = (categoryValue || '').toLowerCase();
    return normalized === 'disposable' || normalized.includes('disposable');
}

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function getFlavorRows() {
    return Array.from(document.querySelectorAll('.flavor-inventory-row'));
}

function formatPuffLabel(value) {
    return `${Number(value).toLocaleString()} puffs`;
}

function getFlavorViewProduct(productId) {
    const normalizedProductId = String(productId ?? '').trim();
    if (normalizedProductId === '' || !Object.prototype.hasOwnProperty.call(flavorViewProductMap, normalizedProductId)) {
        return null;
    }

    return flavorViewProductMap[normalizedProductId];
}

function getFlavorViewPriceLines(product) {
    const puffPrices = Array.isArray(product?.puff_prices) ? [...product.puff_prices] : [];
    puffPrices.sort((left, right) => (parseInt(left?.puffs ?? 0, 10) || 0) - (parseInt(right?.puffs ?? 0, 10) || 0));

    if (puffPrices.length > 1) {
        return puffPrices.map((puffPrice) => {
            const puffValue = parseInt(puffPrice?.puffs ?? 0, 10) || 0;
            return {
                label: puffValue > 0 ? formatPuffLabel(puffValue) : 'All Flavors',
                value: String(puffPrice?.price_display ?? product?.price_display ?? '-'),
            };
        });
    }

    return [
        {
            label: 'Price',
            value: String(product?.price_display ?? '-'),
        }
    ];
}

function getFlavorViewStockBadgeClass(stockQty) {
    const normalizedStock = parseInt(stockQty ?? 0, 10) || 0;

    if (normalizedStock <= 0) {
        return 'is-out';
    }

    if (normalizedStock <= 10) {
        return 'is-low';
    }

    return 'is-good';
}

function getFlavorViewGroups(product) {
    const groupedOptions = new Map();
    const priceByPuff = new Map();
    const puffPrices = Array.isArray(product?.puff_prices) ? product.puff_prices : [];

    puffPrices.forEach((puffPrice) => {
        const puffValue = parseInt(puffPrice?.puffs ?? 0, 10) || 0;
        priceByPuff.set(puffValue > 0 ? String(puffValue) : 'all', String(puffPrice?.price_display ?? product?.price_display ?? '-'));
    });

    const sourceOptions = Array.isArray(product?.variant_options) && product.variant_options.length > 0
        ? product.variant_options
        : Array.isArray(product?.flavors)
            ? product.flavors.map((flavor) => ({
                flavor: flavor?.flavor ?? '',
                puffs: Array.isArray(flavor?.puff_counts) && flavor.puff_counts.length > 0 ? flavor.puff_counts[0] : null,
                stock_qty: flavor?.stock_qty ?? 0,
                variant_count: flavor?.variant_count ?? 0,
            }))
            : [];

    sourceOptions.forEach((option) => {
        const puffValue = option?.puffs === null || option?.puffs === '' ? null : (parseInt(option.puffs, 10) || 0);
        const groupKey = puffValue && puffValue > 0 ? String(puffValue) : 'all';

        if (!groupedOptions.has(groupKey)) {
            groupedOptions.set(groupKey, {
                puffs: puffValue && puffValue > 0 ? puffValue : null,
                label: puffValue && puffValue > 0 ? formatPuffLabel(puffValue) : 'All Flavors',
                price_display: priceByPuff.get(groupKey) || String(product?.price_display ?? '-'),
                options: [],
            });
        }

        groupedOptions.get(groupKey).options.push({
            flavor: String(option?.flavor ?? ''),
            stock_qty: parseInt(option?.stock_qty ?? 0, 10) || 0,
            variant_count: parseInt(option?.variant_count ?? 0, 10) || 0,
        });
    });

    const groups = Array.from(groupedOptions.values());
    groups.sort((left, right) => {
        const leftSort = left.puffs === null ? Number.MAX_SAFE_INTEGER : left.puffs;
        const rightSort = right.puffs === null ? Number.MAX_SAFE_INTEGER : right.puffs;

        if (leftSort !== rightSort) {
            return leftSort - rightSort;
        }

        return String(left.label ?? '').localeCompare(String(right.label ?? ''), undefined, { sensitivity: 'base' });
    });

    groups.forEach((group) => {
        group.options.sort((left, right) => String(left.flavor ?? '').localeCompare(String(right.flavor ?? ''), undefined, { sensitivity: 'base' }));
    });

    return groups;
}

function renderFlavorViewModal(productId) {
    const titleElement = document.getElementById('viewFlavorsModalTitle');
    const metaElement = document.getElementById('viewFlavorsModalMeta');
    const statsElement = document.getElementById('viewFlavorsModalStats');
    const groupsElement = document.getElementById('viewFlavorsModalGroups');
    const product = getFlavorViewProduct(productId);

    if (!titleElement || !metaElement || !statsElement || !groupsElement) {
        return;
    }

    if (!product) {
        titleElement.textContent = 'Flavor Details';
        metaElement.innerHTML = '';
        statsElement.innerHTML = '';
        groupsElement.innerHTML = '<div class="flavor-view-empty">Flavor details are not available for this product.</div>';
        return;
    }

    const normalizedName = String(product.name || 'Flavor Details').trim() || 'Flavor Details';
    const normalizedCategory = String(product.category || '').trim() || 'Uncategorized';
    const normalizedBrand = String(product.brand || '').trim();
    const priceLines = getFlavorViewPriceLines(product);
    const groups = getFlavorViewGroups(product);
    const flavorCount = Array.isArray(product.flavors) ? product.flavors.length : 0;
    const puffGroupCount = groups.length;
    const hasExplicitPuffGrouping = Array.isArray(product.puff_counts) && product.puff_counts.length > 0;

    titleElement.textContent = normalizedName;
    metaElement.innerHTML = `
        <span class="flavor-view-meta-pill">${escapeHtml(normalizedCategory)}</span>
        ${normalizedBrand !== '' ? `<span class="flavor-view-meta-pill is-brand">${escapeHtml(normalizedBrand)}</span>` : ''}
    `;
    statsElement.innerHTML = `
        <div class="flavor-view-stat">
            <span class="flavor-view-stat-label">Flavors</span>
            <span class="flavor-view-stat-value">${flavorCount}</span>
            <span class="flavor-view-stat-subvalue">unique flavor options</span>
        </div>
        <div class="flavor-view-stat">
            <span class="flavor-view-stat-label">Puff Groups</span>
            <span class="flavor-view-stat-value">${puffGroupCount}</span>
            <span class="flavor-view-stat-subvalue">${hasExplicitPuffGrouping ? 'grouped by puff count' : (puffGroupCount > 0 ? 'single flavor list' : 'no puff grouping')}</span>
        </div>
        <div class="flavor-view-stat">
            <span class="flavor-view-stat-label">Total Stock</span>
            <span class="flavor-view-stat-value">${Number(product.total_stock || 0).toLocaleString()}</span>
            <span class="flavor-view-stat-subvalue">units available</span>
        </div>
        <div class="flavor-view-stat ${priceLines.length > 1 ? 'is-wide' : ''}">
            <span class="flavor-view-stat-label">Price</span>
            <div class="flavor-view-price-list">
                ${priceLines.map((priceLine) => `
                    <div class="flavor-view-price-line">
                        ${priceLines.length > 1 ? `<span class="flavor-view-price-label">${escapeHtml(priceLine.label)}</span>` : ''}
                        <span class="flavor-view-price-value">${escapeHtml(priceLine.value)}</span>
                    </div>
                `).join('')}
            </div>
        </div>
    `;
    groupsElement.innerHTML = groups.length > 0
        ? groups.map((group) => `
            <section class="flavor-view-group">
                <div class="flavor-view-group-header">
                    <div>
                        <h6 class="flavor-view-group-title">${escapeHtml(group.label)}</h6>
                        <p class="flavor-view-group-subtitle">${group.options.length} flavor${group.options.length === 1 ? '' : 's'}</p>
                    </div>
                    <div class="flavor-view-group-price">${escapeHtml(group.price_display || String(product.price_display || '-'))}</div>
                </div>
                <div class="flavor-view-list">
                    ${group.options.map((option) => `
                        <div class="flavor-view-item">
                            <div class="flavor-view-item-main">
                                <span class="flavor-view-item-name">${escapeHtml(option.flavor || 'Unnamed Flavor')}</span>
                                ${option.variant_count > 1 ? `<span class="flavor-view-item-note">${option.variant_count} variants combined</span>` : ''}
                            </div>
                            <span class="flavor-view-stock ${getFlavorViewStockBadgeClass(option.stock_qty)}">${Number(option.stock_qty || 0).toLocaleString()}</span>
                        </div>
                    `).join('')}
                </div>
            </section>
        `).join('')
        : '<div class="flavor-view-empty">No flavor details are available for this product.</div>';
}

function getDefaultVariantPuffValue() {
    const puffInput = document.getElementById('default_variant_puffs');
    return puffInput && !puffInput.disabled ? puffInput.value.trim() : '';
}

function getDefaultVariantPriceValue() {
    const priceInput = document.getElementById('price');
    return priceInput ? priceInput.value.trim() : '';
}

function getResolvedVariantPuffValue(row) {
    const puffInput = row.querySelector('.variant-puff-input');
    if (!puffInput || puffInput.disabled) {
        return getDefaultVariantPuffValue();
    }

    return puffInput.value.trim() || getDefaultVariantPuffValue();
}

function createFlavorRow(row = {}) {
    return `
        <div class="flavor-inventory-row">
            <input type="hidden" name="variant_ids[]" value="${escapeHtml(row.id ?? '')}">
            <div><input type="text" class="form-control variant-flavor-input" name="variant_flavors[]" placeholder="e.g. Bacteria Monster (Yakult)" value="${escapeHtml(row.flavor ?? '')}"></div>
            <div><input type="number" class="form-control variant-stock-input" name="variant_stocks[]" min="0" step="1" placeholder="0" value="${escapeHtml(row.stock_qty ?? '')}"></div>
            <div class="text-md-end"><button type="button" class="inventory-remove-btn" aria-label="Remove flavor row">x</button></div>
        </div>
    `;
}

function syncTotalStockQuantity() {
    if (!isFlavorInventoryCategory(getSelectedCategoryValue())) {
        return;
    }

    let total = 0;
    getFlavorRows().forEach((row) => {
        const stockInput = row.querySelector('.variant-stock-input');
        if (!stockInput || stockInput.disabled) {
            return;
        }

        total += parseInt(stockInput.value || '0', 10) || 0;
    });

    document.getElementById('stock_qty').value = total;
}

function updatePuffFieldState(categoryValue) {
    const container = document.getElementById('defaultPuffsFieldContainer');
    const puffInput = document.getElementById('default_variant_puffs');
    const puffHelp = document.getElementById('defaultPuffsHelp');
    const usesManagedPuffs = categoryUsesManagedPuffs(categoryValue);

    container.style.display = usesManagedPuffs ? 'flex' : 'none';
    puffInput.disabled = !usesManagedPuffs;
    puffInput.required = usesManagedPuffs && categoryRequiresPuffs(categoryValue);

    if (!usesManagedPuffs) {
        return;
    }

    puffHelp.textContent = 'Type one puff count for all flavor rows or use the quick buttons.';
}

function refreshPuffControls() {
    const usesManagedPuffs = categoryUsesManagedPuffs(getSelectedCategoryValue());
    const currentDefaultValue = getDefaultVariantPuffValue();

    const shortcutButtons = Array.from(document.querySelectorAll('#puffShortcutButtons [data-puff-value]'));
    shortcutButtons.forEach((button) => {
        const isActive = usesManagedPuffs && button.dataset.puffValue === currentDefaultValue;
        button.classList.toggle('is-active', isActive);
    });
}

function syncFlavorInventoryState() {
    const categoryValue = getSelectedCategoryValue();
    const usesInventory = isFlavorInventoryCategory(categoryValue);
    const usesManagedPuffs = categoryUsesManagedPuffs(categoryValue);
    const requiresPuffs = categoryRequiresPuffs(categoryValue);
    const inventoryPanel = document.getElementById('flavorInventoryPanel');
    const inventoryGrid = inventoryPanel.querySelector('.inventory-grid');
    const priceInput = document.getElementById('price');
    const stockQtyInput = document.getElementById('stock_qty');
    const stockQtyLabel = document.getElementById('stockQtyLabel');
    const stockQtyHelp = document.getElementById('stockQtyHelp');
    const priceLabel = document.getElementById('priceLabel');
    const priceHelp = document.getElementById('priceHelp');

    inventoryPanel.style.display = usesInventory ? 'block' : 'none';
    inventoryGrid.classList.toggle('with-puffs', usesManagedPuffs);
    priceInput.required = true;
    stockQtyInput.readOnly = usesInventory;
    stockQtyLabel.textContent = usesInventory ? 'Total Stock Quantity *' : 'Stock Quantity *';
    stockQtyHelp.textContent = usesInventory
        ? 'Total stock is based on the sum of all flavor quantities below.'
        : 'Current stock for this product.';
    priceLabel.textContent = 'Price *';
    priceHelp.textContent = usesInventory
        ? 'Applied to all flavor rows below.'
        : 'Price for this product.';

    if (usesInventory && getFlavorRows().length === 0) {
        document.getElementById('flavorRowsContainer').insertAdjacentHTML('beforeend', createFlavorRow());
    }

    getFlavorRows().forEach((row) => {
        const flavorInput = row.querySelector('.variant-flavor-input');
        const stockInput = row.querySelector('.variant-stock-input');
        const removeButton = row.querySelector('.inventory-remove-btn');

        flavorInput.disabled = !usesInventory;
        flavorInput.required = usesInventory;
        stockInput.disabled = !usesInventory;
        stockInput.required = usesInventory;
        removeButton.disabled = !usesInventory;
    });

    updatePuffFieldState(categoryValue);
    refreshPuffControls();
    syncTotalStockQuantity();
}

function syncCategoryInputState() {
    const categorySelect = document.getElementById('category');
    const newCategoryInput = document.getElementById('new_category');

    if (categorySelect.value === '__new__') {
        newCategoryInput.style.display = 'block';
        newCategoryInput.required = true;
        categorySelect.name = 'category_old';
        newCategoryInput.name = 'category';
    } else {
        newCategoryInput.style.display = 'none';
        newCategoryInput.required = false;
        categorySelect.name = 'category';
        newCategoryInput.name = 'new_category';
    }

    syncFlavorInventoryState();
}

function syncBrandInputState() {
    const brandSelect = document.getElementById('brand');
    const newBrandInput = document.getElementById('new_brand');

    if (brandSelect.value === '__new__') {
        newBrandInput.style.display = 'block';
        brandSelect.name = 'brand_old';
        newBrandInput.name = 'brand';
    } else {
        newBrandInput.style.display = 'none';
        brandSelect.name = 'brand';
        newBrandInput.name = 'new_brand';
    }
}

function resetAddProductFormState() {
    const form = document.getElementById('addProductForm');
    form.reset();
    document.getElementById('price').value = '0.00';
    document.getElementById('stock_qty').value = '0';
    document.getElementById('is_active').value = '1';
    document.getElementById('flavorRowsContainer').innerHTML = '';
    syncCategoryInputState();
    syncBrandInputState();
}

document.getElementById('category').addEventListener('change', syncCategoryInputState);
document.getElementById('new_category').addEventListener('input', syncFlavorInventoryState);
document.getElementById('brand').addEventListener('change', syncBrandInputState);
document.getElementById('default_variant_puffs').addEventListener('change', refreshPuffControls);
document.getElementById('puffShortcutButtons').addEventListener('click', function(event) {
    const shortcutButton = event.target.closest('[data-puff-value]');
    if (!shortcutButton) {
        return;
    }

    const puffValue = shortcutButton.dataset.puffValue;
    document.getElementById('default_variant_puffs').value = puffValue;

    getFlavorRows().forEach((row) => {
        const puffInput = row.querySelector('.variant-puff-input');
        if (puffInput && puffInput.value.trim() === '') {
            puffInput.value = puffValue;
        }
    });

    refreshPuffControls();
});

document.getElementById('addFlavorRowButton').addEventListener('click', function() {
    document.getElementById('flavorRowsContainer').insertAdjacentHTML('beforeend', createFlavorRow());
    syncFlavorInventoryState();
    const rows = getFlavorRows();
    const newestFlavorInput = rows.length ? rows[rows.length - 1].querySelector('.variant-flavor-input') : null;
    if (newestFlavorInput) {
        newestFlavorInput.focus();
    }
});

document.getElementById('flavorRowsContainer').addEventListener('input', function(event) {
    if (event.target.classList.contains('variant-stock-input')) {
        syncTotalStockQuantity();
    }
});

document.getElementById('flavorRowsContainer').addEventListener('click', function(event) {
    const removeButton = event.target.closest('.inventory-remove-btn');
    if (!removeButton) {
        return;
    }

    const row = removeButton.closest('.flavor-inventory-row');
    if (row) {
        row.remove();
        syncTotalStockQuantity();
    }
});

document.getElementById('addProductForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const categoryValue = getSelectedCategoryValue();
    if (isFlavorInventoryCategory(categoryValue)) {
        const rows = getFlavorRows().filter((row) => {
            const flavorInput = row.querySelector('.variant-flavor-input');
            const stockInput = row.querySelector('.variant-stock-input');
            return !flavorInput.disabled || !stockInput.disabled;
        });

        if (rows.length === 0) {
            showAlert('Add at least one flavor row before saving.', 'danger');
            return;
        }

        for (const row of rows) {
            const flavorInput = row.querySelector('.variant-flavor-input');
            const stockInput = row.querySelector('.variant-stock-input');
            if (flavorInput.value.trim() === '' || stockInput.value.trim() === '') {
                showAlert('Complete every flavor row before saving.', 'danger');
                return;
            }

            if (categoryRequiresPuffs(categoryValue) && getResolvedVariantPuffValue(row) === '') {
                showAlert('Enter a puff count for every flavor row, or set a default puff count.', 'danger');
                return;
            }
        }

        syncTotalStockQuantity();
    }

    const submitBtn = document.querySelector('button[form="addProductForm"]');
    const originalText = submitBtn.innerHTML;
    const modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));

    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    submitBtn.disabled = true;

    const formData = new FormData(this);

    fetch('<?= site_url('/products/store') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showAlert(data.message, 'success');
            modal.hide();
            resetAddProductFormState();

            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while saving the product. Please check the console for details.', 'danger');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Clear default values on focus
document.getElementById('price').addEventListener('focus', function() {
    if (this.value === '0.00') {
        this.value = '';
    }
});

document.getElementById('price').addEventListener('change', function() {
    if (!isFlavorInventoryCategory(getSelectedCategoryValue())) {
        return;
    }

    const defaultPriceValue = this.value.trim();
    getFlavorRows().forEach((row) => {
        const priceInput = row.querySelector('.variant-price-input');
        if (priceInput && priceInput.value.trim() === '') {
            priceInput.value = defaultPriceValue;
        }
    });
});

document.getElementById('stock_qty').addEventListener('focus', function() {
    if (!this.readOnly && this.value === '0') {
        this.value = '';
    }
});

// Restore default values on blur if empty
document.getElementById('price').addEventListener('blur', function() {
    if (!isFlavorInventoryCategory(getSelectedCategoryValue()) && this.value === '') {
        this.value = '0.00';
    }
});

document.getElementById('stock_qty').addEventListener('blur', function() {
    if (!this.readOnly && this.value === '') {
        this.value = '0';
    }
});

// Function to show alert messages
function showAlert(message, type) {
    const existingAlert = document.querySelector('.alert-container');
    if (existingAlert) {
        existingAlert.remove();
    }

    const alertContainer = document.createElement('div');
    alertContainer.className = 'alert-container position-fixed top-0 start-50 translate-middle-x mt-3';
    alertContainer.style.zIndex = '9999';

    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    alertContainer.appendChild(alert);
    document.body.appendChild(alertContainer);

    setTimeout(() => {
        if (alertContainer) {
            alertContainer.remove();
        }
    }, 5000);
}

// Simple filter functions that reload the page with new parameters
function applyFilters() {
    const search = document.getElementById('q').value;
    const category = document.getElementById('category_filter').value;
    const brand = document.getElementById('brand_filter').value;
    const status = document.getElementById('status').value;

    const params = new URLSearchParams();
    if (search) params.set('q', search);
    if (category) params.set('category_filter', category);
    if (brand) params.set('brand_filter', brand);
    if (status) params.set('status', status);

    const url = params.toString() ? '<?= site_url('/products') ?>?' + params.toString() : '<?= site_url('/products') ?>';
    window.location.href = url;
}

document.addEventListener('DOMContentLoaded', function() {
    const filters = ['q', 'category_filter', 'brand_filter', 'status'];
    filters.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('change', function() {
                if (id === 'q') {
                    clearTimeout(window.searchTimeout);
                    window.searchTimeout = setTimeout(applyFilters, 500);
                } else {
                    applyFilters();
                }
            });
        }
    });

    syncCategoryInputState();
    syncBrandInputState();

    const addProductModal = document.getElementById('addProductModal');
    if (addProductModal) {
        addProductModal.addEventListener('hidden.bs.modal', function() {
            resetAddProductFormState();
        });
    }

    const viewFlavorsModal = document.getElementById('viewFlavorsModal');
    if (viewFlavorsModal) {
        viewFlavorsModal.addEventListener('show.bs.modal', function(event) {
            const trigger = event.relatedTarget;
            const productId = trigger ? trigger.getAttribute('data-product-id') : '';
            renderFlavorViewModal(productId);
        });
    }
});

window.resetSearch = function() {
    window.location.href = '<?= site_url('/products') ?>';
};
</script>

<?= $this->include('layouts/footer') ?>
