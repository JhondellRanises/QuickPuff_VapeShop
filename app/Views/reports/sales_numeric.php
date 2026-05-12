<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(1);
?>

<nav aria-label="Sales pagination">
    <ul class="pagination sales-pagination mb-0">
        <li class="page-item <?= $pager->hasPrevious() ? '' : 'disabled' ?>">
            <?php if ($pager->hasPrevious()) : ?>
                <a class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </a>
            <?php else : ?>
                <span class="page-link"><i class="fas fa-chevron-left"></i></span>
            <?php endif; ?>
        </li>

        <?php foreach ($pager->links() as $link) : ?>
            <?php if ($link['title'] === lang('Pager.ellipsis')) : ?>
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            <?php else : ?>
                <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                    <a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>

        <li class="page-item <?= $pager->hasNext() ? '' : 'disabled' ?>">
            <?php if ($pager->hasNext()) : ?>
                <a class="page-link" href="<?= $pager->getNext() ?>" aria-label="Next">
                    <i class="fas fa-chevron-right"></i>
                </a>
            <?php else : ?>
                <span class="page-link"><i class="fas fa-chevron-right"></i></span>
            <?php endif; ?>
        </li>
    </ul>
</nav>

