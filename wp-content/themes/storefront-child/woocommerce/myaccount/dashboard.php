<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$user_id = get_current_user_id();
?>

    <div class="row mb-5">
        <div class="col-12 progress-dashboard">
            <div class="row">
                <div class="col left">
                    <?php echo do_shortcode('[mycred_my_rank user_id=' . $user_id . ' show_title=0 show_logo=1 logo_size="100"]'); ?>
                </div>
                <div class="col right text-right">
                    <?php echo do_shortcode('[mycred_my_rank user_id=' . $user_id . ' show_title=0 show_logo=1 logo_size="100"]'); ?>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                     aria-valuemax="100"></div>
            </div>
            <div class="row">
                <div class="col">
                    <p><?php echo do_shortcode('[mycred_my_rank user_id=' . $user_id . ' show_title=1 show_logo=0]'); ?></p>
                </div>
                <div class="col text-right">
                    <p><?php echo do_shortcode('[mycred_my_rank user_id=' . $user_id . ' show_title=1 show_logo=0]'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <p><?php
        /* translators: 1: user display name 2: logout url */
        printf(
            __('Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce'),
            '<strong>' . esc_html($current_user->display_name) . '</strong>',
            esc_url(wc_logout_url(wc_get_page_permalink('myaccount')))
        );
        ?></p>

    <p><?php
        printf(
            __('From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce'),
            esc_url(wc_get_endpoint_url('orders')),
            esc_url(wc_get_endpoint_url('edit-address')),
            esc_url(wc_get_endpoint_url('edit-account'))
        );
        ?></p>

<?php
/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action('woocommerce_account_dashboard');

/**
 * Deprecated woocommerce_before_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_before_my_account');

/**
 * Deprecated woocommerce_after_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_after_my_account');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
