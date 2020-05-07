<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_account_navigation');
?>
<div class="row">
    <nav class="col-lg-3 col-12 navigation-my-account-br">
        <div class="filter-collapse-btn d-lg-none d-block" data-toggle="collapse" data-target="#collapseMyaccount" aria-expanded="false" aria-controls="collapseMyaccount">
            Сайдбар
        </div>
        <ul class="navigation-my-account collapse d-lg-block" id="collapseMyaccount">
            <?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
                <?php if ($endpoint === 'notifications'): ?>
                    <?php $notificationsCount = countNewNotifications(); ?>
                    <li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?>">
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>"><?php echo esc_html($label); ?>
                            <span class="menu-profile__counter"<?= (sprintf($notificationsCount) != 0) ? '' : ' style="display: none"' ?>><?php echo sprintf($notificationsCount); ?></span>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?>">
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>"><?php echo esc_html($label); ?></a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </nav>


<?php do_action('woocommerce_after_account_navigation'); ?>
