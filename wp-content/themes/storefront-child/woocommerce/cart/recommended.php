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
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
$related_products = array();
$args = array(
    'post_type' => 'product',
    'stock' => 1,
    'posts_per_page' => 12,
    'post__not_in' => $product_ids_in_cart,
    'orderby' => 'date',
    'order' => 'DESC');
$loop = new WP_Query($args);
while ($loop->have_posts()) :
    $loop->the_post();
    global $product;
    $related_products[] = $product;
endwhile;
wp_reset_query();
if ($related_products) : ?>


    <div class="container-related bg-white">
        <div class="container position-relative">
            <div class="container-related__prev"><img src="/wp-content/themes/storefront-child/svg/related-next.svg"
                                                      alt="">
            </div>
            <div class="container-related__next"><img src="/wp-content/themes/storefront-child/svg/related-next.svg"
                                                      alt="">
            </div>
            <div class="row">
                <div class="col-lg-10 offset-lg-1 col-12 offset-0 text-center">
                    <div class="swiper-container-related">
                        <div class="swiper-wrapper">

                            <?php foreach ($related_products
                                           as $related_product) : ?>
                                <div class="swiper-slide">
                                    <a href="<?php echo $related_product->get_permalink(); ?>">
                                        <div class="related-img-container">
                                            <?php echo $related_product->get_image('woocommerce_thumbnail'); ?>
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

<?php endif;

wp_reset_postdata();
