<?php
/*
Template Name: images
Template Post Type: post, page, product
*/
?>

<?php get_header(); ?>
<?php
$categories = get_the_category();
$subCategoryName = $categories[0]->name;
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12"><h2 class="page-title"><?php echo $subCategoryName ?></h2></div>
            </div>
        </div>
    </div>
    <div class="grid grid-images">
        <div class="grid-sizer"></div>
        <?php
        $subCategorySlug = $categories[0]->slug;
//        $query = new WP_Query('category_name=' . $subCategorySlug . '&posts_per_page=9999');
        $hasImages = false;
        while (have_posts()) {
            the_post();
            if (!has_post_thumbnail()) {
//                continue;
            }
            $hasImages = true;
            $categories = get_the_category();
            $categoryClass = '';
            foreach ($categories as $category) {
                $categoryClass .= ' images-' . $category->slug;
            }
            ?>
            <div class="grid-item<?php echo $categoryClass ?>">
                <a data-category="<?php echo $categoryClass ?>" data-fancybox="images-all" class="images-link image-id-<?php echo get_the_ID() ?>"
                   href="<?php echo get_the_post_thumbnail_url(); ?>" data-caption="<?php the_title(); ?>">
                    <?php echo get_the_post_thumbnail(null, 'small'); ?>
                </a>
            </div>
            <?php
        }
        wp_reset_postdata(); ?>
    </div>
    <?php if (!$hasImages): ?>
        <div class="isotope-empty" style="display: block">
            <p>Нет иллюстраций</p>
        </div>
    <?php endif; ?>
    <?php kama_pagenavi(); ?>
</div>

<?php get_footer(); ?>
