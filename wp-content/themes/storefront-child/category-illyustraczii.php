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
            <div id="primary" class="col-12 position-relative archive-page">
                <main id="main" class="site-main" role="main">
                    <?php
                    $currentCategory = get_category($cat);
                    ?>
                    <header class="page-header">
                        <h1 class="page-title"><?php echo $currentCategory->name ?></h1>
                    </header><!-- .page-header -->
                    <div>
                        <div class="grid grid-images">
                            <div class="grid-sizer"></div>
                    <?php $subCategories =  get_categories('child_of=' . $cat);
                    foreach ($subCategories as $subCategory): ?>
                        <div class=" grid-item<?php echo $categoryClass ?> text-center">
                            <a data-category="<?php echo $categoryClass ?>" class="album-img images-link"
                               href="<?php echo get_category_link($subCategory) ?>">
                                <?php
                                $tax_term_id = $subCategory->term_taxonomy_id;
                                $images = get_option('taxonomy_image_plugin');
                                echo '<img src="' . wp_get_attachment_image_url($images[$tax_term_id], 'full') . '" class="attachment-medium size-medium mb-3">';
                                ?>
                                <span><?php echo $subCategory->name?></span>
                            </a>
                        </div>
                    <?php
                        endforeach; ?>
                        </div>
                    </div>
                </main><!-- #main -->
            </div><!-- #primary -->
        </div>
    </div>

<?php
get_footer();

