<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package storefront
 */

get_header(); ?>

    <div id="primary" class="content-area col-12">

        <main id="main" class="site-main" role="main">

            <div class="error-404 not-found">

                <div class="page-content">

                    <div class="row">

                        <div class="col-lg-7 col-12 order-lg-1 order-2 position-relative">

                            <header class="page-header">
                                <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'storefront'); ?></h1>
                            </header>

                            <p><?php esc_html_e('Nothing was found at this location. Try searching, or check out the links below.', 'storefront'); ?></p>

                            <?php

                            if (storefront_is_woocommerce_activated()) {
                                the_widget('WC_Widget_Product_Search');
                            } else {
                                get_search_form();

                                echo '</div>';

                                echo '</div>';
                            }

                            if (storefront_is_woocommerce_activated()) {
                                
                                echo '</div>';

                                echo '<div class="col-1 order-lg-2 d-lg-flex d-none reader-hr">';

                                echo '</div>';

                                get_sidebar('custom');

                                echo '</div>';

                                echo '</div>';
                            }
                            ?>

                        </div><!-- .page-content -->
                    </div><!-- .error-404 -->
        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
