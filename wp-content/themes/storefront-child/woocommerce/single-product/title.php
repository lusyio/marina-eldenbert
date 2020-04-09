<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author     WooThemes
 * @package    WooCommerce/Templates
 * @version    1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
global $product;
$authors = '';
// Получаем элементы таксономии атрибута
$attribute_names = get_the_terms($product->get_id(), 'pa_author-book');
$attribute_name = "Автор: ";
if ($attribute_names) {
    foreach ($attribute_names as $attribute_name):
        $fullName = preg_split('~ ~', $attribute_name->name);
        $authors .= mb_strtoupper(mb_substr($fullName[0], 0, 1)) . '. ';
        $authors .= mb_strtoupper(mb_substr($fullName[1], 0, 1)) . mb_substr($fullName[1], 1);
// Вывод значений атрибута
    endforeach;
}

$tags = get_the_terms($product->ID, 'product_tag');
$tagsArray = array();
if (!empty($tags) && !is_wp_error($tags)) {
    foreach ($tags as $tag) {
        $tagsArray[] = $tag->slug;
    }
}
$isDraft = in_array('draft', $tagsArray);
if ($isDraft) {
    $lastUpdate = getLastArticleDate($product->get_id());
}
if (is_user_logged_in() && isBookInLibrary(get_current_user_id(), $product->get_id())) {
    $isBookInLibrary = true;
} else {
    $isBookInLibrary = false;
}
if ($product->is_downloadable('yes')) {
    $customTitle = "Скачать книгу ";
} else {
    $customTitle = "Читать книгу ";
}
the_title('<h1 class="product_title entry-title">' . $customTitle . $authors . ' "', '"</h1>');

?>

<div class="info-card">
    <div class="row">
        <div class="col-4">
            <?php if (!$isDraft): ?>
                <!-- книга завершена-->
                <div class="info-card__status">
                    <img src="/wp-content/themes/storefront-child/svg/svg-complete-book.svg"
                         alt="complete-book">
                    <p>Книга завершена <span>322 стр</span></p>
                </div>
                <!-- книга завершена-->
            <?php else: ?>
                <!-- книга в процессе-->
                <div class="info-card__status">
                    <img src="/wp-content/themes/storefront-child/svg/svg-process-book.svg" alt="process-book">
                    <p>Книга в процессе <span><?= $lastUpdate ?></span></p>
                    <!-- книга в процессе-->
                </div>
            <?php endif; ?>
        </div>
        <div class="col-4">
            <?php productSeries(); ?>
        </div>
        <div class="col-4">
            <?php if ($isBookInLibrary): ?>
            <a class="add-to-library" href="/my-account/downloads/">
                <img src="/wp-content/themes/storefront-child/svg/svg-addToLibrary.svg" alt="add-to-library">
                <span>В библиотеке</span>
            </a>
            <?php else: ?>
            <a class="add-to-library" href="/my-account/downloads?add=<?=$product->get_id()?>">
                <img src="/wp-content/themes/storefront-child/svg/svg-addToLibrary.svg" alt="add-to-library">
                <span>Добавить в библиотеку</span>
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

