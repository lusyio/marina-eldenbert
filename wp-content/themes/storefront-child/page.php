<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package storefront
 */

get_header(); ?>
    <div class="container">
        <div class="row">
            <div class="col-12 position-relative">
                <div class="row">
                    <div class="col-12"><h2 class="page-title"><?php the_title() ?></h2></div>
                </div>
                <?php

                the_content();

                ?>
            </div>
        </div>
    </div>
<?php
get_footer();
