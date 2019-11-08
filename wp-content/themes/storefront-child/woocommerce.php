<?php
/**
 * The template for displaying Woocommerce Product
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

get_header(); ?>

<?php if (is_product() || is_cart() || is_checkout()): ?>
    <section id="primary" class="content-area col-sm-12 mt-5">
        <main id="main" class="site-main" role="main">

            <?php woocommerce_content(); ?>
            <?php
            if (is_product()) {
                comments_template();
            } ?>
        </main><!-- #main -->
    </section><!-- #primary -->
<?php else: ?>
    <?php get_sidebar(); ?>
    <section id="primary" class="content-area col-sm-12 col-lg-9 archive-product-page">
        <main id="main" class="site-main" role="main">
            <?php woocommerce_content(); ?>
            <div class="row pb-5 mb-5">
                <div class="col text-center">
                    <div class="load-more">Загрузить еще</div>
                </div>
            </div>
        </main><!-- #main -->
    </section><!-- #primary -->
    <script>
        var num = 0;

        function loadProducts() {
            num += 12;
            var products = jQuery("li.product:visible").slice(0, num);
            jQuery('.product').hide();
            products.show();
        }

        function loadProductPage() {
            num += 12;
            var products = jQuery("li.product").slice(0, num);
            jQuery.when(jQuery('.product').fadeOut(150)).done(function () {
                jQuery.when(products.fadeIn(300)).done(function () {
                    if (jQuery('li.product').length - jQuery('li.product:visible').length === 0) {
                        jQuery(".load-more").hide();
                    }
                });
            });

        }

        jQuery(document).ready(function ($) {
            loadProducts();
            if (jQuery('li.product').length - jQuery('li.product:visible').length === 0) {
                jQuery(".load-more").hide();
            }
            $('.load-more').on('click', function () {
                loadProductPage();
            });
        });
    </script>
<?php endif; ?>

<?php
get_footer();
