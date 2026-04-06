<?= $this->include('layouts/header') ?>

<?php
$selectedCategoryOption = old('category', '');
$selectedCategoryValue = $selectedCategoryOption === '__new__' ? old('new_category') : $selectedCategoryOption;
$normalizedSelectedCategory = strtolower(trim((string) $selectedCategoryValue));
$usesFlavorInventory = in_array($normalizedSelectedCategory, ['disposable', 'pods', 'e-liquid', 'e liquid', 'eliquid'], true);
$usesManagedPuffs = in_array($normalizedSelectedCategory, ['disposable', 'pods'], true);
$requiresPuffs = $normalizedSelectedCategory === 'disposable';
$defaultVariantPuffs = old('default_variant_puffs', old('puffs', ''));
$inventoryRows = [];
$oldVariantIds = old('variant_ids');
$oldVariantFlavors = old('variant_flavors');
$oldVariantStocks = old('variant_stocks');
$oldVariantPuffs = old('variant_puffs');
$oldVariantPrices = old('variant_prices');

if (is_array($oldVariantIds) || is_array($oldVariantFlavors) || is_array($oldVariantStocks) || is_array($oldVariantPuffs) || is_array($oldVariantPrices)) {
    $oldVariantIds = is_array($oldVariantIds) ? array_values($oldVariantIds) : [];
    $oldVariantFlavors = is_array($oldVariantFlavors) ? array_values($oldVariantFlavors) : [];
    $oldVariantStocks = is_array($oldVariantStocks) ? array_values($oldVariantStocks) : [];
    $oldVariantPuffs = is_array($oldVariantPuffs) ? array_values($oldVariantPuffs) : [];
    $oldVariantPrices = is_array($oldVariantPrices) ? array_values($oldVariantPrices) : [];
    $rowCount = max(count($oldVariantIds), count($oldVariantFlavors), count($oldVariantStocks), count($oldVariantPuffs), count($oldVariantPrices));

    for ($index = 0; $index < $rowCount; $index++) {
        $rowId = trim((string) ($oldVariantIds[$index] ?? ''));
        $rowFlavor = trim((string) ($oldVariantFlavors[$index] ?? ''));
        $rowStock = trim((string) ($oldVariantStocks[$index] ?? ''));
        $rowPuffs = trim((string) ($oldVariantPuffs[$index] ?? ''));
        $rowPrice = trim((string) ($oldVariantPrices[$index] ?? ''));

        if ($rowId === '' && $rowFlavor === '' && $rowStock === '' && $rowPuffs === '' && $rowPrice === '') {
            continue;
        }

        $inventoryRows[] = [
            'id' => $rowId,
            'flavor' => $rowFlavor,
            'stock_qty' => $rowStock,
            'puffs' => $rowPuffs,
            'price' => $rowPrice,
        ];
    }
}

if ($usesFlavorInventory && $inventoryRows === []) {
    $inventoryRows[] = ['id' => '', 'flavor' => '', 'stock_qty' => '', 'puffs' => '', 'price' => ''];
}

$inventoryTotalStock = 0;
foreach ($inventoryRows as $inventoryRow) {
    $inventoryTotalStock += max(0, (int) ($inventoryRow['stock_qty'] ?? 0));
}

$stockValue = old('stock_qty', $usesFlavorInventory ? $inventoryTotalStock : 0);
$basePuffChoices = [12000, 25000];
$availablePuffChoiceMap = [];
foreach ($basePuffChoices as $basePuffChoice) {
    $availablePuffChoiceMap[(int) $basePuffChoice] = (int) $basePuffChoice;
}
if ((int) $defaultVariantPuffs > 0) {
    $availablePuffChoiceMap[(int) $defaultVariantPuffs] = (int) $defaultVariantPuffs;
}
foreach ($inventoryRows as $inventoryRow) {
    $rowPuffValue = (int) ($inventoryRow['puffs'] ?? 0);
    if ($rowPuffValue > 0) {
        $availablePuffChoiceMap[$rowPuffValue] = $rowPuffValue;
    }
}
$availablePuffChoices = array_values($availablePuffChoiceMap);
sort($availablePuffChoices, SORT_NUMERIC);
?>

<style>
.form-label{color:#fff!important;font-weight:600}.form-control,.form-select{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);border-radius:14px;color:#fff!important;min-height:48px}.form-control:focus,.form-select:focus{background:rgba(255,255,255,.12);border-color:rgba(110,99,255,.85);box-shadow:0 0 0 .22rem rgba(110,99,255,.18)}.form-control[readonly]{background:rgba(255,255,255,.12)}.form-control::placeholder{color:rgba(255,255,255,.55)!important}.form-select option{background:#232436;color:#fff}.text-muted{color:rgba(255,255,255,.68)!important}.card{background:linear-gradient(180deg,rgba(33,35,51,.96) 0%,rgba(24,25,39,.96) 100%);border:1px solid rgba(255,255,255,.08);border-radius:26px;box-shadow:0 24px 60px rgba(0,0,0,.28)}.card-header{background:transparent;border-bottom:1px solid rgba(255,255,255,.08);padding:1.5rem 1.5rem 1rem}.card-body{padding:1.5rem}.page-title{color:#fff!important}.page-subtitle{color:rgba(255,255,255,.7)!important}.btn-outline-primary,.btn-outline-secondary{border-color:rgba(255,255,255,.18);color:#fff}.btn-outline-primary:hover,.btn-outline-secondary:hover{background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.28);color:#fff}.inventory-shell{margin-top:.35rem;padding:1.35rem;border-radius:22px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.08)}.inventory-header{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;margin-bottom:1rem}.inventory-title{margin:0;color:#fff;font-size:1.12rem;font-weight:700}.inventory-description{margin:.4rem 0 0;color:rgba(255,255,255,.68)}.btn-add-flavor{min-width:132px;border-radius:14px;border:1px solid rgba(110,99,255,.52);color:#a79eff;background:rgba(110,99,255,.08);font-weight:600}.btn-add-flavor:hover{color:#fff;background:rgba(110,99,255,.18);border-color:rgba(130,120,255,.72)}.inventory-grid{border-radius:18px;overflow:hidden;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.05)}.inventory-grid-head,.flavor-inventory-row{display:grid;grid-template-columns:minmax(0,1.9fr) minmax(140px,.72fr) 72px;gap:1rem;align-items:center;padding:1rem 1.1rem}.inventory-grid.with-puffs .inventory-grid-head,.inventory-grid.with-puffs .flavor-inventory-row{grid-template-columns:minmax(0,1.6fr) minmax(150px,.82fr) minmax(140px,.72fr) 72px}.inventory-grid-head{background:rgba(255,255,255,.08);color:rgba(255,255,255,.88);font-size:.82rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase}.inventory-puffs-heading,.variant-puff-cell{display:none}.inventory-grid.with-puffs .inventory-puffs-heading,.inventory-grid.with-puffs .variant-puff-cell{display:block}.inventory-grid-body .flavor-inventory-row+.flavor-inventory-row{border-top:1px solid rgba(255,255,255,.08)}.inventory-remove-btn{width:42px;height:42px;border:none;border-radius:12px;background:rgba(255,77,109,.08);color:#ff5f7d;font-size:1rem;font-weight:700}.inventory-remove-btn:hover{background:rgba(255,77,109,.18);color:#ffd8df}.inventory-hint{display:block;margin-top:.9rem;color:rgba(255,255,255,.62)}.puff-shortcut-list{display:flex;flex-wrap:wrap;gap:.55rem;margin-top:.75rem}.puff-shortcut-btn{padding:.42rem .8rem;border-radius:999px;border:1px solid rgba(110,99,255,.38);background:rgba(110,99,255,.08);color:#d8d3ff;font-size:.82rem;font-weight:700}.puff-shortcut-btn:hover,.puff-shortcut-btn.is-active{background:rgba(110,99,255,.22);border-color:rgba(130,120,255,.72);color:#fff}@media (max-width:767.98px){.inventory-header{flex-direction:column;align-items:stretch}.btn-add-flavor{width:100%}.inventory-grid-head{display:none}.flavor-inventory-row,.inventory-grid.with-puffs .flavor-inventory-row{grid-template-columns:1fr}.inventory-remove-btn{width:100%}.variant-puff-cell{display:block}}
</style>
<style>
.inventory-grid-head,.flavor-inventory-row{grid-template-columns:minmax(0,1.65fr) minmax(140px,.8fr) minmax(140px,.72fr) 72px}
.inventory-grid.with-puffs .inventory-grid-head,.inventory-grid.with-puffs .flavor-inventory-row{grid-template-columns:minmax(0,1.4fr) minmax(150px,.82fr) minmax(140px,.82fr) minmax(140px,.72fr) 72px}
.inventory-grid-head,.flavor-inventory-row,.inventory-grid.with-puffs .inventory-grid-head,.inventory-grid.with-puffs .flavor-inventory-row{grid-template-columns:minmax(0,1.9fr) minmax(140px,.72fr) 72px}
.inventory-puffs-heading,.variant-puff-cell,.inventory-price-heading,.variant-price-cell{display:none!important}
</style>

<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Add Product</h1>
                <p class="page-subtitle">Create a new inventory item</p>
            </div>
            <a href="<?= site_url('/products') ?>" class="btn btn-outline-primary"><i class="fas fa-arrow-left me-2"></i>Back to Stock</a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0" style="color:#fff!important;"><i class="fas fa-plus me-2"></i>Product Information</h5>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('/products/store') ?>" method="POST" enctype="multipart/form-data" id="createProductForm">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required maxlength="255" placeholder="Enter product name" value="<?= old('name') ?>">
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
                                        <?php foreach ($brands as $brandItem): ?>
                                            <option value="<?= esc($brandItem['brand']) ?>" <?= old('brand') === $brandItem['brand'] ? 'selected' : '' ?>><?= esc($brandItem['brand']) ?></option>
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
                                <input type="number" class="form-control" id="price" name="price" required step="0.01" min="0" placeholder="0.00" value="<?= old('price', '0.00') ?>">
                                <small class="text-muted" id="priceHelp"><?= $usesFlavorInventory ? 'Applied to all flavor rows below.' : 'Price for this product.' ?></small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stock_qty" class="form-label" id="stockQtyLabel"><?= $usesFlavorInventory ? 'Total Stock Quantity *' : 'Stock Quantity *' ?></label>
                                <input type="number" class="form-control" id="stock_qty" name="stock_qty" required step="1" min="0" placeholder="0" value="<?= esc((string) $stockValue) ?>" <?= $usesFlavorInventory ? 'readonly' : '' ?>>
                                <small class="text-muted" id="stockQtyHelp"><?= $usesFlavorInventory ? 'Total stock is based on the sum of all flavor quantities below.' : 'Current stock for this product.' ?></small>
                            </div>
                        </div>

                        <div class="row" id="defaultPuffsFieldContainer" style="display:<?= $usesManagedPuffs ? 'flex' : 'none' ?>;">
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
                                       value="<?= esc((string) $defaultVariantPuffs) ?>">
                                <datalist id="puffChoiceList">
                                    <?php foreach ($availablePuffChoices as $puffChoice): ?>
                                        <option value="<?= (int) $puffChoice ?>"><?= esc(number_format((int) $puffChoice) . ' puffs') ?></option>
                                    <?php endforeach; ?>
                                </datalist>
                                <div class="puff-shortcut-list" id="puffShortcutButtons">
                                    <?php foreach ($availablePuffChoices as $puffChoice): ?>
                                        <?php $shortcutLabel = ((int) $puffChoice % 1000 === 0) ? ((int) $puffChoice / 1000) . 'k' : number_format((int) $puffChoice); ?>
                                        <button type="button" class="btn puff-shortcut-btn <?= (string) $defaultVariantPuffs === (string) $puffChoice ? 'is-active' : '' ?>" data-puff-value="<?= (int) $puffChoice ?>"><?= esc($shortcutLabel) ?></button>
                                    <?php endforeach; ?>
                                </div>
                                <small class="text-muted" id="defaultPuffsHelp">Type one puff count for all flavor rows or use the quick buttons.</small>
                            </div>
                        </div>

                        <div class="inventory-shell" id="flavorInventoryPanel" style="display:<?= $usesFlavorInventory ? 'block' : 'none' ?>;">
                            <div class="inventory-header">
                                <div>
                                    <h6 class="inventory-title">Flavor Inventory</h6>
                                    <p class="inventory-description">Add one row per flavor. The price and puff count above apply to all flavor rows, and total stock is calculated automatically.</p>
                                </div>
                                <button type="button" class="btn btn-add-flavor" id="addFlavorRowButton">+ Add Flavor</button>
                            </div>
                            <div class="inventory-grid <?= $usesManagedPuffs ? 'with-puffs' : '' ?>">
                                <div class="inventory-grid-head">
                                    <div>Flavor Name</div>
                                    <div>Flavor Stock</div>
                                    <div class="text-end">Action</div>
                                </div>
                                <div class="inventory-grid-body" id="flavorRowsContainer">
                                    <?php foreach ($inventoryRows as $row): ?>
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
                            <input type="file" class="form-control" id="image_file" name="image_file" accept="image/jpeg,image/png,image/webp,image/gif">
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

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= site_url('/products') ?>" class="btn btn-outline-secondary"><i class="fas fa-times me-2"></i>Cancel</a>
                            <button type="submit" class="btn btn-primary" form="createProductForm"><i class="fas fa-save me-2"></i>Save Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const availablePuffChoices = <?= json_encode(array_values($availablePuffChoices), JSON_UNESCAPED_SLASHES) ?>;

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

document.getElementById('createProductForm').addEventListener('submit', function(event) {
    const categoryValue = getSelectedCategoryValue();

    if (isFlavorInventoryCategory(categoryValue)) {
        const rows = getFlavorRows().filter((row) => {
            const flavorInput = row.querySelector('.variant-flavor-input');
            const stockInput = row.querySelector('.variant-stock-input');
            return !flavorInput.disabled || !stockInput.disabled;
        });

        if (rows.length === 0) {
            event.preventDefault();
            alert('Add at least one flavor row before saving.');
            return;
        }

        for (const row of rows) {
            const flavorInput = row.querySelector('.variant-flavor-input');
            const stockInput = row.querySelector('.variant-stock-input');
            if (flavorInput.value.trim() === '' || stockInput.value.trim() === '') {
                event.preventDefault();
                alert('Complete every flavor row before saving.');
                return;
            }

            if (categoryRequiresPuffs(categoryValue) && getResolvedVariantPuffValue(row) === '') {
                event.preventDefault();
                alert('Enter a puff count for every flavor row, or set a default puff count.');
                return;
            }
        }

        syncTotalStockQuantity();
    }

    const submitBtn = document.querySelector('button[form="createProductForm"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    submitBtn.disabled = true;
});

syncCategoryInputState();
syncBrandInputState();
</script>

<?= $this->include('layouts/footer') ?>
