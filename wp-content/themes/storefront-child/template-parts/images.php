<?php
/*
Template Name: images
Template Post Type: post, page, product
*/
?>

<?php get_header(); ?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12"><h2 class="page-title"><?php the_title() ?></h2></div>
            </div>
            <div class="row">
                <div class="col-12"><?php addImageFilter() ?></div>
            </div>
        </div>
    </div>
    <div class="grid grid-images">
        <div class="grid-sizer"></div>
        <?php
        $query = new WP_Query('category_name=images');
        $hasImages = false;
        while ($query->have_posts()) {
            $hasImages = true;
            $query->the_post();
            $categories = get_the_category();
            $categoryClass = '';
            foreach ($categories as $category) {
                $categoryClass .= ' images-' . $category->slug;
            }
            ?>
            <div class="grid-item<?php echo $categoryClass ?>">
                <a data-fancybox="<?php echo $categoryClass ?>" class="images-link image-id-<?php echo get_the_ID() ?>"
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
</div>

<?php get_footer(); ?>
