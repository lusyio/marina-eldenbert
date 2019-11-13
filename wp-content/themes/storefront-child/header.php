<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.0/css/swiper.min.css">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php do_action('storefront_before_site'); ?>

<div id="page" class="hfeed site">
    <?php do_action('storefront_before_header'); ?>

    <header id="masthead" class="site-header" role="banner" style="<?php storefront_header_styles(); ?>">

        <div class="container">
            <nav class="navbar navbar-light navbar-expand-xl p-0 justify-content-between">
                <div class="navbar-brand">
                    <?php if (get_custom_logo()): ?>
                        <?php echo get_custom_logo(); ?>
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
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container' => 'div',
                        'container_id' => '',
                        'container_class' => 'collapse navbar-collapse justify-content-end mr-5',
                        'menu_id' => false,
                        'menu_class' => 'navbar-nav',
                        'depth' => 3,
                        'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
                        'walker' => new wp_bootstrap_navwalker()
                    ));
                    ?>
                    <?php get_search_form() ?>
                    <?php if (class_exists('WooCommerce')): ?>
                        <div class="header-profile position-relative mr-3 mr-sm-4 mt-auto mb-auto">
                            <a href="<?php echo get_permalink(wc_get_page_id('myaccount')) ?>">
                                <img src="/wp-content/themes/storefront-child/svg/avatar.svg" alt="">
                            </a>
                        </div>
                        <div class="s-header__basket-wr woocommerce mr-3 mr-sm-3 mr-xl-0 mt-auto mb-auto z-5 position-relative">
                            <?php
                            global $woocommerce; ?>
                            <a href="<?php echo $woocommerce->cart->get_cart_url() ?>"
                               class="basket-btn basket-btn_fixed-xs text-decoration-none position-relative">
                                <span class="basket-btn__label"><img
                                            src="/wp-content/themes/storefront-child/svg/cart-black.svg"
                                            alt=""></span>
                                <?php if (sprintf($woocommerce->cart->cart_contents_count) != 0): ?>
                                    <span class="basket-btn__counter"><?php echo sprintf($woocommerce->cart->cart_contents_count); ?></span>
                                <?php endif; ?>
                            </a>
                        </div>

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
                                    wp_nav_menu(array(
                                        'theme_location' => 'primary',
                                        'container' => 'div',
                                        'container_id' => 'main-nav',
                                        'container_class' => 'collapse navbar-collapse justify-content-end',
                                        'menu_id' => false,
                                        'menu_class' => 'navbar-nav',
                                        'depth' => 3,
                                        'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
                                        'walker' => new wp_bootstrap_navwalker()
                                    ));
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
        <div class="modal fade" id="disclaimerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <p class="disclaimerModal__header">Проверка возраста</p>
                                    <div class="row">
                                        <div class="col-lg-8 col-12 offset-lg-2 offset-0">
                                            <p class="disclaimerModal__text">Содержимое раздела предназначено для
                                                просмотра лицами старше 18 лет.</p>
                                        </div>
                                    </div>
                                    <p class="disclaimerModal__que">Вам уже есть 18 лет?</p>
                                    <div class="disclaimerModal__agree">Да</div>
                                    <div class="disclaimerModal__disagree">Нет</div>
                                    <p class="disclaimerModal__disclaimer">Сайт содержит информацию для лиц
                                        совершеннолетнего возраста.<br>
                                        Нажимая кнопку "Да", вы даёте cогласие на обработку персональных данных</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">

<?php
do_action('storefront_content_top');
