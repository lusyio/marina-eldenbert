<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="row">
    <div class="col">
        <div class="order-total d-flex justify-content-between">
            <div class="d-flex order-total__text"><?php esc_html_e( 'Total', 'woocommerce' ); ?></div>
            <div class="d-flex order-total__price"><?php wc_cart_totals_order_total_html(); ?></div>
        </div>
    </div>
</div>

<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

