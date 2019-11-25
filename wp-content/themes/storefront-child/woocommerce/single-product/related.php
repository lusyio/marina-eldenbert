<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce/Templates
 * @version     3.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if ($related_products) : ?>

    <section class="related products">

        <?php woocommerce_product_loop_start(); ?>

        <div class="container-related pt-0 bg-white">
            <div class="container position-relative">
                <div class="container-related__prev"><img src="/wp-content/themes/storefront-child/svg/related-next.svg"
                                                          alt="">
                </div>
                <div class="container-related__next"><img src="/wp-content/themes/storefront-child/svg/related-next.svg"
                                                          alt="">
                </div>
                <div class="row">
                    <div class="col-lg-10 offset-lg-1 col-12 offset-0 text-center">
                        <h2 class="related-product text-left"><?php esc_html_e('Вместе с книгой ', 'woocommerce');
                            echo '"';
                            echo the_title();
                            echo '"';
                            echo '<br/>';
                            echo ' также покупают:' ?></h2>
                        <div class="swiper-container-related">
                            <div class="swiper-wrapper">

                                <?php foreach ($related_products
                                               as $related_product) : ?>
                                    <div class="swiper-slide">
                                        <a href="<?php echo $related_product->get_permalink(); ?>">
                                            <div class="related-img-container">
                                                <?php echo $related_product->get_image('medium'); ?>
                                                <span class="related-img-container__price"><?php echo $related_product->get_price_html(); ?></span>
                                            </div>
                                            <p class="related-img-container__header"><?php echo $related_product->get_name(); ?></p>
                                        </a>
                                    </div>

                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php woocommerce_product_loop_end(); ?>

    </section>


<?php endif;

wp_reset_postdata();
