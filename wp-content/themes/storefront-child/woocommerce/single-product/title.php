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

$productId = $product->get_id();
$authors = '';
// Получаем элементы таксономии атрибута
$attribute_names = get_the_terms($productId, 'pa_author-book');
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
the_title('<h1 class="product_title entry-title">', '</h1>');

?>

<div class="info-card">

    <?php if (!$isDraft):
        $articles = getArticlesList($product->get_id());
        $totalText = count($articles) . ' ' . getNumeral(count($articles), 'глава', 'главы', 'глав');
        ?>
        <!-- книга завершена-->
        <div class="info-card__status">
            <img src="/wp-content/themes/storefront-child/svg/svg-complete-book.svg"
                 alt="complete-book">
            <p>Книга завершена <span><?php echo $totalText?></span></p>
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
    <div>
        <?php productSeries(); ?>
    </div>
    <div>
        <?php if ($isBookInLibrary): ?>
            <a class="add-to-library bookInLibrary" href="/my-account/library/">
                <svg width="20" height="32" viewBox="0 0 20 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.83899 23.4559L9.51336 23.1764L9.18773 23.4559L0.5 30.912V0.5H18.5267V30.912L9.83899 23.4559Z"
                          stroke="#415996"/>
                    <path d="M9.5134 6.39937L11.0006 9.4128L11.117 9.64852L11.3771 9.68632L14.7025 10.1695L12.2962 12.5151L12.108 12.6986L12.1524 12.9577L12.7205 16.2697L9.74606 14.706L9.51339 14.5836L9.28072 14.706L6.30637 16.2697L6.87441 12.9577L6.91885 12.6986L6.73061 12.5151L4.32425 10.1695L7.64974 9.68632L7.90988 9.64852L8.02622 9.41279L9.5134 6.39937Z"
                          fill="#FAFAFA" stroke="#415996"/>
                </svg>

                <span>В вашей библиотеке</span>
            </a>
        <?php else: ?>
            <a class="add-to-library" href="/my-account/library?add=<?= $product->get_id() ?>">
                <svg width="20" height="32" viewBox="0 0 20 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.83899 23.4559L9.51336 23.1764L9.18773 23.4559L0.5 30.912V0.5H18.5267V30.912L9.83899 23.4559Z"
                          stroke="#415996"/>
                    <path d="M9.5134 6.39937L11.0006 9.4128L11.117 9.64852L11.3771 9.68632L14.7025 10.1695L12.2962 12.5151L12.108 12.6986L12.1524 12.9577L12.7205 16.2697L9.74606 14.706L9.51339 14.5836L9.28072 14.706L6.30637 16.2697L6.87441 12.9577L6.91885 12.6986L6.73061 12.5151L4.32425 10.1695L7.64974 9.68632L7.90988 9.64852L8.02622 9.41279L9.5134 6.39937Z"
                          fill="#FAFAFA" stroke="#415996"/>
                </svg>

                <span><?= is_user_logged_in() ? 'Добавить в библиотеку' : 'Необходимо авторизоваться' ?></span>
            </a>
        <?php endif; ?>
    </div>
</div>

