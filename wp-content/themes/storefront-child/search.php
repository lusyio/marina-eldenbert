<?php
/**
 * The template for displaying search results pages.
 *
 * @package storefront
 */

get_header(); ?>
    <div class="container">
        <div class="row">
            <div id="primary" class="col-lg-7 col-12 order-lg-1 order-2 position-relative">
                <main id="main" class="site-main" role="main">

                    <?php if (have_posts()) : ?>

                        <header class="page-header">
                            <h1 class="page-title">
                                <?php
                                /* translators: %s: search term */
                                printf(esc_attr__('Search Results for: %s', 'storefront'), '<span>' . get_search_query() . '</span>');
                                ?>
                            </h1>
                        </header><!-- .page-header -->

                        <?php
                        while ( have_posts() ) :
                            the_post();

                            /**
                             * Include the Post-Format-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                             */
                            get_template_part('template-parts/content-search', 'post');

                        endwhile;

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
