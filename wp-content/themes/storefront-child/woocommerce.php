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

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="page-title"><?php if (is_product()) {
                        echo get_the_title();
                    } else {
                        woocommerce_page_title();
                    } ?>
                </h2>
            </div>
        </div>
    </div>

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
    <?php addFilterBar(); ?>
    <section id="primary" class="content-area col-sm-12 col-lg-9 archive-product-page">
        <main id="main" class="site-main" role="main">
            <?php woocommerce_content(); ?>
            <div class="row pb-5 mb-5">
                <div class="col text-center">
                    <div class="load-more" style="display: none">Загрузить еще</div>
                </div>
            </div>
        </main><!-- #main -->
    </section><!-- #primary -->
    <script>

    </script>
<?php endif; ?>

<?php
get_footer();
