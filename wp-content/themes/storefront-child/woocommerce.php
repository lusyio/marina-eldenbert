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


<?php if (!is_product()): ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if (is_shop()): ?>
                    <span class="page-title">
                            <?php woocommerce_page_title(); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (is_product() || is_cart() || is_checkout()): ?>
    <section id="primary" class="content-area col-sm-12 <?= is_product() ? '' : 'mt-5' ?>">
        <main id="main" class="site-main" role="main">

            <?php woocommerce_content(); ?>
            <?php
            if (is_product()) {
                global $post;
                $currentPost = $post;
                $bookPageId = getBookPageIdByBookId($product->get_id());
                var_dump($bookPageId);
                $GLOBALS['post'] = get_post($bookPageId);
                comments_template();
                $GLOBALS['post'] = $currentPost;
            } ?>
        </main><!-- #main -->
    </section><!-- #primary -->
<?php else: ?>
    <?php addFilterBar(); ?>
    <section id="primary" class="content-area col-sm-12 col-lg-9 archive-product-page">
        <main id="main" class="site-main" role="main">
            <?php woocommerce_content(); ?>
            <div class="isotope-empty">
                <p>По заданным параметрам не найдено ни одной книги</p>
            </div>
        </main><!-- #main -->
    </section><!-- #primary -->
<?php endif; ?>

<?php
get_footer();
