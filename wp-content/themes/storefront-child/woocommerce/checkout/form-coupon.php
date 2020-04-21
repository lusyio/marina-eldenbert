<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined('ABSPATH') || exit;

if (!wc_coupons_enabled()) { // @codingStandardsIgnoreLine.
    return;
}

?>
<div class="col-12">

    <div class="woocommerce-form-coupon-toggle">
        <?php wc_print_notice('У вас есть абонемент? <a href="#" class="showcoupon">Нажмите здесь, чтобы его применить</a>', 'notice'); ?>
    </div>

    <form class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none">


        <div class="row">
            <div class="col-lg-7 col-12">
                <label for="coupon_code"><?php esc_html_e('Введите код абонемента:', 'woocommerce'); ?></label>
                <input type="text" name="coupon_code" class="input-text"
                       placeholder="Код абонемента" id="coupon_code" value=""/>
            </div>
            <div class="col-lg-5 col-12 text-center mt-auto">
                <button class="apply-coupon button w-100" type="submit" name="apply_coupon"
                        value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>">Применить
                </button>
            </div>
        </div>

        <div class="clear"></div>
    </form>
</div>