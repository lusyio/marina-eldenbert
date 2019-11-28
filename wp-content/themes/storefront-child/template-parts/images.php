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
    <div class="grid">
        <div class="grid-sizer"></div>
        <?php
        $query = new WP_Query('category_name=images');
        while ($query->have_posts()) {
            $query->the_post();
            $categories = get_the_category();
            $categoryClass = '';
            foreach ($categories as $category) {
                $categoryClass .= ' images-' . $category->slug;
            }
            ?>
            <div class="grid-item<?php echo $categoryClass ?>">
                <p><?php the_title(); ?></p>
                <a class="cir-image-link image-id-<?php echo get_the_ID() ?>"
                   href="<?php echo get_the_post_thumbnail_url(); ?>"><?php echo get_the_post_thumbnail(null, 'small'); ?></a>

            </div>
            <?php
        }
        wp_reset_postdata();
        ?>
    </div>

    <!--    --><?php //if (comments_open() || get_comments_number()) :
    //        comments_template('/image-comments.php');
    //    endif; ?>
</div>

<?php get_footer(); ?>
