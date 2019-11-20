<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package storefront
 */

get_header(); ?>
    <div class="container">
        <div class="row">
            <div id="primary" class="col-lg-7 col-12 order-lg-1 order-2 position-relative archive-page">
                <main id="main" class="site-main" role="main">

                    <?php if (have_posts()) : ?>

                        <header class="page-header">
                            <?php
                            the_archive_title('<h1 class="page-title">', '</h1>');
                            the_archive_description('<div class="taxonomy-description">', '</div>');
                            ?>
                        </header><!-- .page-header -->

                        <?php
                        get_template_part('loop');

                    else :

                        get_template_part('content', 'none');

                    endif;
                    ?>

                </main><!-- #main -->
            </div><!-- #primary -->
            <div class="col-1 order-lg-2 d-lg-flex d-none reader-hr"></div>
            <?php get_sidebar('custom'); ?>
        </div>
    </div>

<?php
get_footer();
