<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

global $product;

if (!$product->is_purchasable()) {
    return;
}

echo wc_get_stock_html($product); // WPCS: XSS ok.

if ($product->is_in_stock()) : ?>
    <?php do_action('woocommerce_before_add_to_cart_form'); ?>

    <form class="cart"
          action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
          method="post" enctype='multipart/form-data'>
        <?php if (is_product_in_cart()): ?>
            <a href="<?php echo get_permalink(wc_get_page_id('cart')); ?>"
               class="single_add_to_cart_button button alt">Товар
                в корзине</a>
            <?php do_action('woocommerce_before_add_to_cart_button'); ?>

        <?php
        else:
            do_action('woocommerce_before_add_to_cart_quantity');

            woocommerce_quantity_input(array(
                'min_value' => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                'max_value' => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
            ));

            do_action('woocommerce_after_add_to_cart_quantity');

            //выводим ссылку на чтение книги
            $bookPageId = getBookPageIdByBookId($product->get_id());
            if ($bookPageId) {
                $link = get_permalink($bookPageId); ?>

                <a class="product-read" href="<?php echo $link ?>">Читать</a>
                <?php
            }
            // выводим сылки на скачивание книги
            $downloads = array();
            $user_id = get_current_user_id();
            $downloads = wc_get_customer_available_downloads($user_id);
            $hasDownloads = false;
            if (!empty($downloads)) {
                foreach ($downloads as $download) {
                    if ($download['product_id'] == $product->get_id()) { ?>
                        <div class="mb-2">
                            <a class="download-link" href="<?php echo $download['download_url'] ?>">Скачать в
                                формате <?php echo $download['file']['name']; ?></a>
                        </div>
                        <?php
                        $hasDownloads = true;
                    }
                }
            }
            if (!isBookBought($product->get_id())):
                ?>
                <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>"
                        class="single_add_to_cart_button button alt"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>
            <?php endif;
            do_action('woocommerce_after_add_to_cart_button');
        endif; ?>
    </form>

    <?php do_action('woocommerce_after_add_to_cart_form'); ?>

<?php endif; ?>
