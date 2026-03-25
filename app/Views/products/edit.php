<?= $this->include('layouts/header') ?>

<?php
$selectedCategoryOption = old('category', $product['category']);
$selectedCategoryValue = $selectedCategoryOption === '__new__' ? old('new_category') : $selectedCategoryOption;
$normalizedSelectedCategory = strtolower(trim((string) $selectedCategoryValue));
$usesFlavorInventory = in_array($normalizedSelectedCategory, ['disposable', 'pods', 'e-liquid', 'e liquid', 'eliquid'], true);
$usesManagedPuffs = in_array($normalizedSelectedCategory, ['disposable', 'pods'], true);
$requiresPuffs = $normalizedSelectedCategory === 'disposable';
$existingGroupPuffValues = [];
foreach (($flavorVariants ?? []) as $variant) {
    $variantPuffs = trim((string) ($variant['puffs'] ?? ''));
    if ($variantPuffs !== '') {
        $existingGroupPuffValues[$variantPuffs] = $variantPuffs;
    }
}
$hasMixedPuffValues = count($existingGroupPuffValues) > 1;
$resolvedGroupPuffs = '';
if ($existingGroupPuffValues !== []) {
    $resolvedGroupPuffs = $hasMixedPuffValues ? '' : (string) reset($existingGroupPuffValues);
} else {
    $resolvedGroupPuffs = (string) ($product['puffs'] ?? '');
}
$existingGroupPriceValues = [];
foreach (($flavorVariants ?? []) as $variant) {
    $variantPrice = trim((string) ($variant['price'] ?? ''));
    if ($variantPrice !== '') {
        $existingGroupPriceValues[$variantPrice] = $variantPrice;
    }
}
$hasMixedPriceValues = count($existingGroupPriceValues) > 1;
$resolvedGroupPrice = '';
if ($existingGroupPriceValues !== []) {
    $resolvedGroupPrice = $hasMixedPriceValues ? '' : (string) reset($existingGroupPriceValues);
} else {
    $resolvedGroupPrice = (string) ($product['price'] ?? '');
}
$defaultVariantPuffs = old('default_variant_puffs', $resolvedGroupPuffs);
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
        $inventoryRows[] = ['id' => $rowId, 'flavor' => $rowFlavor, 'stock_qty' => $rowStock, 'puffs' => $rowPuffs, 'price' => $rowPrice];
    }
} elseif (!empty($flavorVariants ?? [])) {
    foreach ($flavorVariants as $variant) {
        $inventoryRows[] = [
            'id' => (string) ($variant['id'] ?? ''),
            'flavor' => (string) ($variant['flavor'] ?? ''),
            'stock_qty' => (string) ($variant['stock_qty'] ?? ''),
            'puffs' => (string) ($variant['puffs'] ?? ''),
            'price' => (string) ($variant['price'] ?? ''),
        ];
    }
} elseif ($usesFlavorInventory || !empty($product['flavor'])) {
    $inventoryRows[] = [
        'id' => (string) ($product['id'] ?? ''),
        'flavor' => (string) ($product['flavor'] ?? ''),
        'stock_qty' => (string) ($product['stock_qty'] ?? ''),
        'puffs' => (string) ($product['puffs'] ?? ''),
        'price' => (string) ($product['price'] ?? ''),
    ];
}

if ($usesFlavorInventory && $inventoryRows === []) {
    $inventoryRows[] = ['id' => '', 'flavor' => '', 'stock_qty' => '', 'puffs' => '', 'price' => ''];
}

$lockedPuffChoices = array_values(array_map('intval', $existingGroupPuffValues));
sort($lockedPuffChoices, SORT_NUMERIC);
$basePuffChoices = [12000, 25000];
$availablePuffChoiceMap = [];
foreach (($lockedPuffChoices !== [] ? $lockedPuffChoices : $basePuffChoices) as $basePuffChoice) {
    $availablePuffChoiceMap[(int) $basePuffChoice] = (int) $basePuffChoice;
}
if ($lockedPuffChoices === [] && (int) $defaultVariantPuffs > 0) {
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
$showPuffPricingPanel = $usesManagedPuffs && count($availablePuffChoices) > 1;
$derivedPuffGroupPrices = [];
$puffGroupPriceMixed = [];
foreach ($inventoryRows as $inventoryRow) {
    $rowPuffValue = (int) ($inventoryRow['puffs'] ?? 0);
    $rowPriceValue = trim((string) ($inventoryRow['price'] ?? ''));

    if ($rowPuffValue <= 0 || $rowPriceValue === '') {
        continue;
    }

    $normalizedRowPrice = number_format((float) $rowPriceValue, 2, '.', '');
    if (!array_key_exists($rowPuffValue, $derivedPuffGroupPrices)) {
        $derivedPuffGroupPrices[$rowPuffValue] = $normalizedRowPrice;
        $puffGroupPriceMixed[$rowPuffValue] = false;
        continue;
    }

    if ($derivedPuffGroupPrices[$rowPuffValue] !== $normalizedRowPrice) {
        $puffGroupPriceMixed[$rowPuffValue] = true;
    }
}

$oldPuffGroupPrices = old('puff_group_prices');
$oldPuffGroupPrices = is_array($oldPuffGroupPrices) ? $oldPuffGroupPrices : [];
$puffGroupPrices = [];
foreach ($availablePuffChoices as $puffChoice) {
    $puffChoiceKey = (string) $puffChoice;

    if (array_key_exists($puffChoiceKey, $oldPuffGroupPrices)) {
        $puffGroupPrices[$puffChoice] = trim((string) $oldPuffGroupPrices[$puffChoiceKey]);
        continue;
    }

    $puffGroupPrices[$puffChoice] = ($puffGroupPriceMixed[$puffChoice] ?? false)
        ? ''
        : (string) ($derivedPuffGroupPrices[$puffChoice] ?? '');
}

$puffHelperMessage = $lockedPuffChoices !== []
    ? 'This product currently uses these puff groups: ' . implode(' / ', array_map(static fn (int $value): string => number_format($value) . ' puffs', $lockedPuffChoices)) . '. New flavor rows must use one of them.'
    : 'Choose a puff preset or use the quick buttons. Clicking a quick button fills the default and any empty puff rows.';

$inventoryTotalStock = 0;
foreach ($inventoryRows as $inventoryRow) {
    $inventoryTotalStock += max(0, (int) ($inventoryRow['stock_qty'] ?? 0));
}

$stockValue = old('stock_qty', $usesFlavorInventory ? $inventoryTotalStock : ($product['stock_qty'] ?? 0));
$currentImageSrc = null;
if (!empty($product['image_url'])) {
    $currentImageUrl = (string) $product['image_url'];
    $currentImageSrc = preg_match('#^(?:https?:)?//#i', $currentImageUrl) || strpos($currentImageUrl, 'data:image') === 0 ? $currentImageUrl : base_url(ltrim($currentImageUrl, '/'));
}
?>

<style>
.form-label{color:#fff!important;font-weight:600}.form-control,.form-select{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);border-radius:14px;color:#fff!important;min-height:48px}.form-control:focus,.form-select:focus{background:rgba(255,255,255,.12);border-color:rgba(110,99,255,.85);box-shadow:0 0 0 .22rem rgba(110,99,255,.18)}.form-control[readonly]{background:rgba(255,255,255,.12)}.form-control::placeholder{color:rgba(255,255,255,.55)!important}.form-select option{background:#232436;color:#fff}.text-muted{color:rgba(255,255,255,.68)!important}.card{background:linear-gradient(180deg,rgba(33,35,51,.96) 0%,rgba(24,25,39,.96) 100%);border:1px solid rgba(255,255,255,.08);border-radius:26px;box-shadow:0 24px 60px rgba(0,0,0,.28)}.card-header{background:transparent;border-bottom:1px solid rgba(255,255,255,.08);padding:1.5rem 1.5rem 1rem}.card-body{padding:1.5rem}.page-title,.meta-block strong{color:#fff!important}.page-subtitle{color:rgba(255,255,255,.7)!important}.btn-outline-primary,.btn-outline-secondary{border-color:rgba(255,255,255,.18);color:#fff}.btn-outline-primary:hover,.btn-outline-secondary:hover{background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.28);color:#fff}.current-image{width:132px;height:132px;object-fit:cover;border-radius:18px;border:2px solid rgba(255,255,255,.16)}.inventory-shell{margin-top:.35rem;padding:1.35rem;border-radius:22px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.08)}.inventory-header{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;margin-bottom:1rem}.inventory-title{margin:0;color:#fff;font-size:1.12rem;font-weight:700}.inventory-description{margin:.4rem 0 0;color:rgba(255,255,255,.68)}.btn-add-flavor{min-width:132px;border-radius:14px;border:1px solid rgba(110,99,255,.52);color:#a79eff;background:rgba(110,99,255,.08);font-weight:600}.btn-add-flavor:hover{color:#fff;background:rgba(110,99,255,.18);border-color:rgba(130,120,255,.72)}.inventory-grid{border-radius:18px;overflow:hidden;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.05)}.inventory-grid-head,.flavor-inventory-row{display:grid;grid-template-columns:minmax(0,1.9fr) minmax(140px,.72fr) 72px;gap:1rem;align-items:center;padding:1rem 1.1rem}.inventory-grid.with-puffs .inventory-grid-head,.inventory-grid.with-puffs .flavor-inventory-row{grid-template-columns:minmax(0,1.6fr) minmax(150px,.82fr) minmax(140px,.72fr) 72px}.inventory-grid-head{background:rgba(255,255,255,.08);color:rgba(255,255,255,.88);font-size:.82rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase}.inventory-puffs-heading,.variant-puff-cell{display:none}.inventory-grid.with-puffs .inventory-puffs-heading,.inventory-grid.with-puffs .variant-puff-cell{display:block}.inventory-grid-body .flavor-inventory-row+.flavor-inventory-row{border-top:1px solid rgba(255,255,255,.08)}.inventory-remove-btn{width:42px;height:42px;border:none;border-radius:12px;background:rgba(255,77,109,.08);color:#ff5f7d;font-size:1rem;font-weight:700}.inventory-remove-btn:hover{background:rgba(255,77,109,.18);color:#ffd8df}.inventory-hint{display:block;margin-top:.9rem;color:rgba(255,255,255,.62)}.puff-shortcut-list{display:flex;flex-wrap:wrap;gap:.55rem;margin-top:.75rem}.puff-shortcut-btn{padding:.42rem .8rem;border-radius:999px;border:1px solid rgba(110,99,255,.38);background:rgba(110,99,255,.08);color:#d8d3ff;font-size:.82rem;font-weight:700}.puff-shortcut-btn:hover,.puff-shortcut-btn.is-active{background:rgba(110,99,255,.22);border-color:rgba(130,120,255,.72);color:#fff}@media (max-width:767.98px){.inventory-header{flex-direction:column;align-items:stretch}.btn-add-flavor{width:100%}.inventory-grid-head{display:none}.flavor-inventory-row,.inventory-grid.with-puffs .flavor-inventory-row{grid-template-columns:1fr}.inventory-remove-btn{width:100%}.variant-puff-cell{display:block}}
</style>
<style>
.inventory-grid-head,.flavor-inventory-row{grid-template-columns:minmax(0,1.9fr) minmax(140px,.72fr) 72px}
.inventory-grid.with-puffs .inventory-grid-head,.inventory-grid.with-puffs .flavor-inventory-row{grid-template-columns:minmax(0,1.6fr) minmax(150px,.82fr) minmax(140px,.72fr) 72px}
.inventory-price-heading,.variant-price-cell{display:none}
.puff-price-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem}
.puff-price-card{display:block;padding:1rem 1.1rem;border-radius:18px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08)}
.puff-price-label{display:block;margin-bottom:.55rem;color:rgba(255,255,255,.82);font-size:.82rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase}
.puff-price-note{display:block;margin-top:.55rem;color:rgba(255,255,255,.62);font-size:.78rem;line-height:1.45}
</style>

<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Edit Product</h1>
                <p class="page-subtitle">Update product details and stock levels</p>
            </div>
            <a href="<?= site_url('/products') ?>" class="btn btn-outline-primary"><i class="fas fa-arrow-left me-2"></i>Back to Stock</a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0" style="color:#fff!important;"><i class="fas fa-edit me-2"></i>Product Information</h5>
                    <span class="badge bg-info">ID: <?= (int) $product['id'] ?></span>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('/products/update/' . $product['id']) ?>" method="POST" enctype="multipart/form-data" id="editProductForm">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required maxlength="255" placeholder="Enter product name" value="<?= old('name', $product['name']) ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Select category...</option>
                                    <?php if (!empty($categories ?? [])): ?>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= esc($cat['category']) ?>" <?= old('category', $product['category']) === $cat['category'] ? 'selected' : '' ?>><?= esc($cat['category']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <option value="__new__" <?= old('category') === '__new__' ? 'selected' : '' ?>>+ Add New Category</option>
                                </select>
                                <input type="text" class="form-control mt-2" id="new_category" name="new_category" placeholder="Enter new category name" value="<?= old('new_category') ?>" style="display:none;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="brand" class="form-label">Brand</label>
                                <select class="form-select" id="brand" name="brand">
                                    <option value="">Select brand...</option>
                                    <?php if (!empty($brands ?? [])): ?>
                                        <?php foreach ($brands as $brandItem): ?>
                                            <option value="<?= esc($brandItem['brand']) ?>" <?= old('brand', $product['brand']) === $brandItem['brand'] ? 'selected' : '' ?>><?= esc($brandItem['brand']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <option value="__new__" <?= old('brand') === '__new__' ? 'selected' : '' ?>>+ Add New Brand</option>
                                </select>
                                <input type="text" class="form-control mt-2" id="new_brand" name="new_brand" placeholder="Enter new brand name" value="<?= old('new_brand') ?>" style="display:none;">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label" id="priceLabel"><?= $usesFlavorInventory ? 'Default Price *' : 'Price *' ?></label>
                                <input type="number" class="form-control" id="price" name="price" required step="0.01" min="0" placeholder="0.00" value="<?= old('price', $resolvedGroupPrice) ?>">
                                <small class="text-muted" id="priceHelp"><?= $usesFlavorInventory ? 'Used as the default price for any flavor row without its own price.' : 'Price for this product.' ?></small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stock_qty" class="form-label" id="stockQtyLabel"><?= $usesFlavorInventory ? 'Total Stock Quantity *' : 'Stock Quantity *' ?></label>
                                <input type="number" class="form-control" id="stock_qty" name="stock_qty" required step="1" min="0" placeholder="0" value="<?= esc((string) $stockValue) ?>" <?= $usesFlavorInventory ? 'readonly' : '' ?>>
                                <small class="text-muted" id="stockQtyHelp"><?= $usesFlavorInventory ? 'Total stock is based on the sum of all flavor quantities below.' : 'Current stock for this product.' ?></small>
                            </div>
                        </div>

                        <div class="row" id="defaultPuffsFieldContainer" style="display:<?= $usesManagedPuffs ? 'flex' : 'none' ?>;">
                            <div class="col-md-6 mb-3">
                                <label for="default_variant_puffs" class="form-label" id="defaultPuffsLabel">Default Puff Count</label>
                                <select class="form-select" id="default_variant_puffs" name="default_variant_puffs">
                                    <option value="">No default puff count</option>
                                    <?php foreach ($availablePuffChoices as $puffChoice): ?>
                                        <option value="<?= (int) $puffChoice ?>" <?= (string) $defaultVariantPuffs === (string) $puffChoice ? 'selected' : '' ?>><?= esc(number_format((int) $puffChoice) . ' puffs') ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="puff-shortcut-list" id="puffShortcutButtons">
                                    <?php foreach ($availablePuffChoices as $puffChoice): ?>
                                        <?php $shortcutLabel = ((int) $puffChoice % 1000 === 0) ? ((int) $puffChoice / 1000) . 'k' : number_format((int) $puffChoice); ?>
                                        <button type="button" class="btn puff-shortcut-btn <?= (string) $defaultVariantPuffs === (string) $puffChoice ? 'is-active' : '' ?>" data-puff-value="<?= (int) $puffChoice ?>"><?= esc($shortcutLabel) ?></button>
                                    <?php endforeach; ?>
                                </div>
                                <small class="text-muted" id="defaultPuffsHelp"><?= esc($puffHelperMessage) ?></small>
                            </div>
                        </div>

                        <?php if ($showPuffPricingPanel): ?>
                            <div class="inventory-shell" id="puffPricePanel" style="display:<?= $usesManagedPuffs ? 'block' : 'none' ?>;">
                                <div class="inventory-header">
                                    <div>
                                        <h6 class="inventory-title">Puff Pricing</h6>
                                        <p class="inventory-description">Set one price per puff group. Matching flavor rows update automatically when you change these values.</p>
                                    </div>
                                </div>
                                <div class="puff-price-grid">
                                    <?php foreach ($availablePuffChoices as $puffChoice): ?>
                                        <?php
                                        $puffPriceValue = (string) ($puffGroupPrices[$puffChoice] ?? '');
                                        $hasMixedPuffPrice = ($puffGroupPriceMixed[$puffChoice] ?? false) && $oldPuffGroupPrices === [];
                                        ?>
                                        <label class="puff-price-card">
                                            <span class="puff-price-label"><?= esc(number_format((int) $puffChoice) . ' puffs') ?></span>
                                            <input type="number"
                                                   class="form-control puff-price-input"
                                                   name="puff_group_prices[<?= (int) $puffChoice ?>]"
                                                   data-puff-value="<?= (int) $puffChoice ?>"
                                                   min="0"
                                                   step="0.01"
                                                   placeholder="<?= $hasMixedPuffPrice ? 'Mixed prices' : '0.00' ?>"
                                                   value="<?= esc($puffPriceValue) ?>">
                                            <span class="puff-price-note">
                                                <?= $hasMixedPuffPrice
                                                    ? 'Current flavor rows use mixed prices. Save a value here to sync them.'
                                                    : 'Applies to every flavor row with this puff count.' ?>
                                            </span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="inventory-shell" id="flavorInventoryPanel" style="display:<?= $usesFlavorInventory ? 'block' : 'none' ?>;">
                            <div class="inventory-header">
                                <div>
                                    <h6 class="inventory-title">Flavor Inventory</h6>
                                    <p class="inventory-description">Add one row per exact flavor and puff combination. Stock is tracked separately per row, and total stock is calculated automatically.</p>
                                </div>
                                <button type="button" class="btn btn-add-flavor" id="addFlavorRowButton">+ Add Flavor</button>
                            </div>
                            <div class="inventory-grid <?= $usesManagedPuffs ? 'with-puffs' : '' ?>">
                                <div class="inventory-grid-head">
                                    <div>Flavor Name</div>
                                    <div class="inventory-puffs-heading">Puffs</div>
                                    <div>Flavor Stock</div>
                                    <div class="text-end">Action</div>
                                </div>
                                <div class="inventory-grid-body" id="flavorRowsContainer">
                                    <?php foreach ($inventoryRows as $row): ?>
                                        <div class="flavor-inventory-row">
                                            <input type="hidden" name="variant_ids[]" value="<?= esc((string) ($row['id'] ?? '')) ?>">
                                            <div><input type="text" class="form-control variant-flavor-input" name="variant_flavors[]" placeholder="e.g. Bacteria Monster (Yakult)" value="<?= esc((string) ($row['flavor'] ?? '')) ?>"></div>
                                            <div class="variant-puff-cell">
                                                <select class="form-select variant-puff-input" name="variant_puffs[]">
                                                    <option value="">Select puffs...</option>
                                                    <?php foreach ($availablePuffChoices as $puffChoice): ?>
                                                        <option value="<?= (int) $puffChoice ?>" <?= (string) ($row['puffs'] ?? '') === (string) $puffChoice ? 'selected' : '' ?>><?= esc(number_format((int) $puffChoice) . ' puffs') ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div><input type="number" class="form-control variant-stock-input" name="variant_stocks[]" min="0" step="1" placeholder="0" value="<?= esc((string) ($row['stock_qty'] ?? '')) ?>"></div>
                                            <div class="text-md-end"><button type="button" class="inventory-remove-btn" aria-label="Remove flavor row">x</button></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 mt-4">
                            <label for="image_file" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="image_file" name="image_file" accept="image/jpeg,image/png,image/webp,image/gif">
                            <small class="text-muted">Optional. Choose a new file to replace the current image (max 4MB).</small>
                        </div>

                        <?php if ($currentImageSrc !== null): ?>
                            <div class="mb-3">
                                <label class="form-label d-block">Current Image</label>
                                <img src="<?= esc($currentImageSrc) ?>" alt="<?= esc($product['name']) ?> current image" class="current-image">
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="is_active" class="form-label">Status *</label>
                            <select class="form-select" id="is_active" name="is_active" required>
                                <option value="1" <?= old('is_active', (string) $product['is_active']) === '1' ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= old('is_active', (string) $product['is_active']) === '0' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                            <small class="text-muted">Inactive products are hidden from POS.</small>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 meta-block"><small class="text-muted d-block">Created</small><strong><?= !empty($product['created_at']) ? date('M d, Y H:i', strtotime($product['created_at'])) : 'N/A' ?></strong></div>
                            <div class="col-md-6 meta-block"><small class="text-muted d-block">Last Updated</small><strong><?= !empty($product['updated_at']) ? date('M d, Y H:i', strtotime($product['updated_at'])) : 'N/A' ?></strong></div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= site_url('/products') ?>" class="btn btn-outline-secondary"><i class="fas fa-times me-2"></i>Cancel</a>
                            <button type="submit" class="btn btn-primary" form="editProductForm"><i class="fas fa-save me-2"></i>Update Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const availablePuffChoices = <?= json_encode(array_values($availablePuffChoices), JSON_UNESCAPED_SLASHES) ?>;
const lockedPuffChoices = <?= json_encode(array_values($lockedPuffChoices), JSON_UNESCAPED_SLASHES) ?>;

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

function getAllowedPuffChoices() {
    return availablePuffChoices.map((value) => String(value));
}

function buildPuffSelectMarkup(selectedValue = '', useDefaultLabel = false) {
    const normalizedSelectedValue = String(selectedValue ?? '').trim();
    const blankLabel = useDefaultLabel ? 'No default puff count' : 'Select puffs...';
    let options = `<option value="">${blankLabel}</option>`;

    getAllowedPuffChoices().forEach((value) => {
        options += `<option value="${value}" ${normalizedSelectedValue === value ? 'selected' : ''}>${formatPuffLabel(Number(value))}</option>`;
    });

    return options;
}

function getDefaultVariantPuffValue() {
    const puffInput = document.getElementById('default_variant_puffs');
    return puffInput && !puffInput.disabled ? puffInput.value.trim() : '';
}

function getDefaultVariantPriceValue() {
    const priceInput = document.getElementById('price');
    return priceInput ? priceInput.value.trim() : '';
}

function getPuffPriceInput(puffValue) {
    const normalizedPuffValue = String(puffValue ?? '').trim();
    if (normalizedPuffValue === '') {
        return null;
    }

    return document.querySelector(`.puff-price-input[data-puff-value="${normalizedPuffValue}"]`);
}

function getPuffGroupPriceValue(puffValue) {
    const puffPriceInput = getPuffPriceInput(puffValue);
    return puffPriceInput && !puffPriceInput.disabled ? puffPriceInput.value.trim() : '';
}

function getResolvedVariantPuffValue(row) {
    const puffInput = row.querySelector('.variant-puff-input');
    if (!puffInput || puffInput.disabled) {
        return '';
    }

    return puffInput.value.trim() || getDefaultVariantPuffValue();
}

function getResolvedVariantPriceValue(row) {
    const resolvedPuffValue = getResolvedVariantPuffValue(row);
    return getPuffGroupPriceValue(resolvedPuffValue) || getDefaultVariantPriceValue();
}

function syncRowPriceFromPuff(row, force = false) {
    const priceInput = row.querySelector('.variant-price-input');
    if (!priceInput || priceInput.disabled) {
        return;
    }

    if (!force && priceInput.value.trim() !== '') {
        return;
    }

    const resolvedPriceValue = getResolvedVariantPriceValue(row);
    if (resolvedPriceValue !== '') {
        priceInput.value = resolvedPriceValue;
    }
}

function syncRowsForPuffPrice(puffValue) {
    const normalizedPuffValue = String(puffValue ?? '').trim();
    if (normalizedPuffValue === '') {
        return;
    }

    const groupPriceValue = getPuffGroupPriceValue(normalizedPuffValue);
    if (groupPriceValue === '') {
        return;
    }

    getFlavorRows().forEach((row) => {
        if (getResolvedVariantPuffValue(row) !== normalizedPuffValue) {
            return;
        }

        const priceInput = row.querySelector('.variant-price-input');
        if (priceInput && !priceInput.disabled) {
            priceInput.value = groupPriceValue;
        }
    });
}

function createFlavorRow(row = {}) {
    const usesManagedPuffs = categoryUsesManagedPuffs(getSelectedCategoryValue());
    const puffValue = row.puffs ?? (usesManagedPuffs ? getDefaultVariantPuffValue() : '');

    return `
        <div class="flavor-inventory-row">
            <input type="hidden" name="variant_ids[]" value="${escapeHtml(row.id ?? '')}">
            <div><input type="text" class="form-control variant-flavor-input" name="variant_flavors[]" placeholder="e.g. Bacteria Monster (Yakult)" value="${escapeHtml(row.flavor ?? '')}"></div>
            <div class="variant-puff-cell"><select class="form-select variant-puff-input" name="variant_puffs[]">${buildPuffSelectMarkup(puffValue)}</select></div>
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
    const puffPricePanel = document.getElementById('puffPricePanel');
    const usesManagedPuffs = categoryUsesManagedPuffs(categoryValue);

    container.style.display = usesManagedPuffs ? 'flex' : 'none';
    if (puffPricePanel) {
        puffPricePanel.style.display = usesManagedPuffs ? 'block' : 'none';
    }
    puffInput.disabled = !usesManagedPuffs;
    document.querySelectorAll('.puff-price-input').forEach((input) => {
        input.disabled = !usesManagedPuffs;
    });

    if (!usesManagedPuffs) {
        return;
    }

    puffHelp.textContent = lockedPuffChoices.length
        ? `This product currently uses these puff groups: ${lockedPuffChoices.map((value) => formatPuffLabel(value)).join(' / ')}. New flavor rows must use one of them.`
        : 'Choose a puff preset or use the quick buttons. Clicking a quick button fills the default and any empty puff rows.';
}

function refreshPuffControls() {
    const usesManagedPuffs = categoryUsesManagedPuffs(getSelectedCategoryValue());
    const defaultPuffInput = document.getElementById('default_variant_puffs');
    const currentDefaultValue = defaultPuffInput ? defaultPuffInput.value.trim() : '';

    if (defaultPuffInput) {
        defaultPuffInput.innerHTML = buildPuffSelectMarkup(currentDefaultValue, true);
        defaultPuffInput.value = getAllowedPuffChoices().includes(currentDefaultValue) ? currentDefaultValue : '';
    }

    getFlavorRows().forEach((row) => {
        const puffInput = row.querySelector('.variant-puff-input');
        if (!puffInput) {
            return;
        }

        const currentValue = puffInput.value.trim();
        puffInput.innerHTML = buildPuffSelectMarkup(currentValue);
        puffInput.value = getAllowedPuffChoices().includes(currentValue) ? currentValue : '';
    });

    const shortcutButtons = Array.from(document.querySelectorAll('#puffShortcutButtons [data-puff-value]'));
    shortcutButtons.forEach((button) => {
        const isActive = usesManagedPuffs && button.dataset.puffValue === getDefaultVariantPuffValue();
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
    priceInput.required = !usesInventory;
    stockQtyInput.readOnly = usesInventory;
    stockQtyLabel.textContent = usesInventory ? 'Total Stock Quantity *' : 'Stock Quantity *';
    stockQtyHelp.textContent = usesInventory
        ? 'Total stock is based on the sum of all flavor quantities below.'
        : 'Current stock for this product.';
    priceLabel.textContent = usesInventory ? 'Default Price *' : 'Price *';
    priceHelp.textContent = usesInventory
        ? 'Used as the default price for any flavor row without its own price.'
        : 'Price for this product.';

    if (usesInventory && getFlavorRows().length === 0) {
        document.getElementById('flavorRowsContainer').insertAdjacentHTML('beforeend', createFlavorRow());
    }

    getFlavorRows().forEach((row) => {
        const flavorInput = row.querySelector('.variant-flavor-input');
        const puffInput = row.querySelector('.variant-puff-input');
        const stockInput = row.querySelector('.variant-stock-input');
        const removeButton = row.querySelector('.inventory-remove-btn');

        flavorInput.disabled = !usesInventory;
        flavorInput.required = usesInventory;
        puffInput.disabled = !usesInventory || !usesManagedPuffs;
        puffInput.required = usesInventory && usesManagedPuffs && requiresPuffs;
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
document.getElementById('default_variant_puffs').addEventListener('change', function() {
    refreshPuffControls();
    getFlavorRows().forEach((row) => syncRowPriceFromPuff(row));
});
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
    syncRowsForPuffPrice(puffValue);
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

document.getElementById('flavorRowsContainer').addEventListener('change', function(event) {
    if (!event.target.classList.contains('variant-puff-input')) {
        return;
    }

    const row = event.target.closest('.flavor-inventory-row');
    if (row) {
        syncRowPriceFromPuff(row, true);
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

const puffPricePanel = document.getElementById('puffPricePanel');
if (puffPricePanel) {
    puffPricePanel.addEventListener('input', function(event) {
        if (!event.target.classList.contains('puff-price-input')) {
            return;
        }

        syncRowsForPuffPrice(event.target.dataset.puffValue);
    });
}

document.getElementById('price').addEventListener('focus', function() {
    if (this.value === '0.00') {
        this.value = '';
    }
});

document.getElementById('price').addEventListener('change', function() {
    if (!isFlavorInventoryCategory(getSelectedCategoryValue())) {
        return;
    }
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

document.getElementById('editProductForm').addEventListener('submit', function(event) {
    if (isFlavorInventoryCategory(getSelectedCategoryValue())) {
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

            if (categoryRequiresPuffs(getSelectedCategoryValue()) && getResolvedVariantPuffValue(row) === '') {
                event.preventDefault();
                alert('Choose a puff preset for every flavor row, or set a default puff count.');
                return;
            }

            if (lockedPuffChoices.length && getResolvedVariantPuffValue(row) === '') {
                event.preventDefault();
                alert(`Choose one of the existing puff groups for every flavor row: ${lockedPuffChoices.map((value) => formatPuffLabel(value)).join(' / ')}.`);
                return;
            }
        }

        syncTotalStockQuantity();
    }

    const submitBtn = document.querySelector('button[form="editProductForm"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
    submitBtn.disabled = true;

    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 5000);
});

document.addEventListener('DOMContentLoaded', function() {
    syncCategoryInputState();
    syncBrandInputState();
    syncFlavorInventoryState();
});
</script>

<?= $this->include('layouts/footer') ?>
