<?php
/**
 * The template for displaying all single posts.
 *
 * @package storefront
 */

get_header(); ?>

    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-12 order-lg-1 order-2 position-relative">
                <div class="row">
                    <div class="col-12"><h1 class="page-title"><?php the_title() ?></h1></div>
                </div>
                <?php

                get_template_part('template-parts/content', 'post');

                ?>
            </div>
            <div class="col-1 order-lg-2 d-lg-flex d-none reader-hr"></div>
            <?php get_sidebar('custom'); ?>
        </div>
    </div>

<?php

get_footer();
