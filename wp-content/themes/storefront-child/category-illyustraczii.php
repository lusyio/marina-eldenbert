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

                    <?php $subCategories =  get_categories('child_of=' . $cat);
                    $catCount = count($subCategories);
                    $elCount = 1;

                    foreach ($subCategories as $subCategory):
                        if ($elCount % 3 == 1):?>
                        <div class="row">
                        <?php endif; ?>
                            <div class="col-lg-4 col-md-6 col-12 text-center mb-4">
                                <a class="album-img images-link" href="<?php echo get_category_link($subCategory) ?>">
                                <div class="album-card">
                        <?php
                        $tax_term_id = $subCategory->term_taxonomy_id;
                        $images = get_option('taxonomy_image_plugin');
                        echo '<img src="' . wp_get_attachment_image_url($images[$tax_term_id], 'full') . '" class="attachment-medium size-medium mb-3">';
                        ?>
                                    <div class="album-name">
                                        <div class="album-title"><?php echo $subCategory->name?></div>
                                        <div><?php echo $subCategory->category_count?></div>
                                    </div>
                                </div>
                                </a>
                            </div>
                        <?php if ($elCount % 3 == 0 || $elCount == $catCount):?>
                    </div>
                    <?php endif; ?>
                    <?php $elCount++; ?>
                    <?php endforeach; ?>
                </main><!-- #main -->
            </div><!-- #primary -->
        </div>
    </div>

<?php
get_footer();

