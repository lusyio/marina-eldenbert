<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?><!doctype html>
<html lang="ru-RU">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="yandex-verification" content="9e80d18053519ed4"/>
    <meta name="interkassa-verification" content="041b99c37a6b4837f3fe5e5559864f9b"/>
    <meta name="google-site-verification" content="dVQCJ0p00oGk_GZkiUkJ_KQYySviBlD6l7Nl3Ed4vvc"/>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="https://marina-eldenbert.ru/xmlrpc.php">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.0/css/swiper.min.css">
    <?php wp_head(); ?>
    <!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-N4TVJG5');</script>
    <!-- End Google Tag Manager -->

</head>

<body <?php body_class(); ?>>

<?php do_action('storefront_before_site'); ?>
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N4TVJG5"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<div id="page" class="hfeed site">
    <?php do_action('storefront_before_header'); ?>

    <header id="masthead" class="site-header" role="banner" style="<?php storefront_header_styles(); ?>">

        <div class="container">
            <nav class="navbar navbar-light navbar-expand-xl p-0 justify-content-between">
                <div class="navbar-brand">
                    <?php $customLogo = get_custom_logo(); ?>
                    <?php if ($customLogo): ?>
                        <?php echo $customLogo; ?>
                    <?php else : ?>
                        <a class="site-title text-decoration-none"
                           href="<?php echo esc_url(home_url('/')); ?>">
                            <?= esc_url(bloginfo('name')); ?>
                            <p class="mb-0 site-description"><?php bloginfo('description'); ?></p>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="d-flex">
                    <?php
                    $menu = wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container' => 'div',
                        'container_id' => '',
                        'container_class' => 'collapse navbar-collapse justify-content-end mr-3',
                        'menu_id' => false,
                        'menu_class' => 'navbar-nav',
                        'depth' => 3,
                        'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
                        'walker' => new wp_bootstrap_navwalker(),
                        'echo' => false,
                    ));
                    echo $menu;
                    ?>
                    <?php get_search_form() ?>
                    <?php if (class_exists('WooCommerce')): ?>
                        <?php if (is_user_logged_in()):
                            $user = wp_get_current_user();
                            $userName = $user->user_firstname . ' ' . $user->user_lastname;
                            $size = 15;
                            $notificationsCount = countNewNotifications();
                            ?>
                            <div class="menu-profile">
                                <div class="menu-profile__body menu-profile-trigger" data-trigger="dropdown">
                                    <div>
                                        <img class="menu-profile__avatar"
                                             src="<?= esc_url(get_avatar_url($user->ID)); ?>"
                                             alt="<?= $userName ?>">
                                        <span class="menu-profile__counter"<?= (sprintf($notificationsCount) != 0) ? '' : ' style="display: none"' ?>><?php echo sprintf($notificationsCount); ?></span>
                                    </div>

                                    <p>
                                        <?php
                                        if (trim($userName) !== '') {
                                            echo mb_substr($userName, 0, $size, 'utf-8');
                                            echo (strlen($userName) > $size) ? '...' : '';
                                        } else {
                                            echo mb_substr($user->display_name, 0, $size, 'utf-8');
                                            echo (strlen($user->display_name) > $size) ? '...' : '';
                                        }
                                        ?>
                                    </p>
                                    <img src="/wp-content/themes/storefront-child/svg/svg-menuProfile.svg" alt="">
                                </div>
                                <div class="menu-profile-submenu">
                                    <?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
                                        <?php if ($endpoint === 'notifications'): ?>
                                            <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>"><?php echo esc_html($label); ?>
                                                <span class="menu-profile__counter"<?= (sprintf($notificationsCount) != 0) ? '' : ' style="display: none"' ?>><?php echo sprintf($notificationsCount); ?></span>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>"><?php echo esc_html($label); ?></a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="header-profile position-relative mr-3 mr-sm-4 mt-auto mb-auto">
                                <a class="header-login" href="<?php echo get_permalink(wc_get_page_id('myaccount')) ?>">
                                    <img src="/wp-content/themes/storefront-child/svg/login.svg" alt="login">
                                    <span>Войти в аккаунт</span></a>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>

                    <div class="outer-menu">
                        <button class="navbar-toggler position-relative" type="button" style="z-index: 1">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <input class="checkbox-toggle" data-toggle="collapse" data-target="#main-nav"
                               aria-controls="" aria-expanded="false" aria-label="Toggle navigation" type="checkbox"/>
                        <div class="menu">
                            <div>
                                <div class="border-header">
                                    <?php
                                    echo $menu;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

    </header><!-- #masthead -->

    <?php
    /**
     * Functions hooked in to storefront_before_content
     *
     * @hooked storefront_header_widget_region - 10
     * @hooked woocommerce_breadcrumb - 10
     */
    do_action('storefront_before_content');
    ?>

    <div id="content" class="site-content">
        <?php adultModal(); ?>
        <div class="container">
            <div class="row">

<?php
do_action('storefront_content_top');
