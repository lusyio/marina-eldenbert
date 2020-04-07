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
            <?php if (true): ?>
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
                    <p>Книга в процессе <span>Обновление - 31 марта</span></p>
                    <!-- книга в процессе-->
                </div>
            <?php endif; ?>
        </div>
        <div class="col-4">
            <p class="info-card__meta-cycle">Цикл: <a href="#">Огенное сердце Аронгары</a></p>
            <p class="info-card__meta-series">Серия: <a href="#">Поющая для дракона</a></p>
        </div>
        <div class="col-4">
            <a class="add-to-library" href="#">
                <img src="/wp-content/themes/storefront-child/svg/svg-addToLibrary.svg" alt="add-to-library">
                <span>Добавить в библиотеку</span>
            </a>
        </div>
    </div>
</div>

