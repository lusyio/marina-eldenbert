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
        </div>
    </div>
    <div class="grid">
        <div class="grid-sizer"></div>
        <?php
        $query = new WP_Query('category_name=images');
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <div class="grid-item">
                <a data-fancybox class="images-link image-id-<?php echo get_the_ID() ?>"
                   href="<?php echo get_the_post_thumbnail_url(); ?>" data-caption="<?php the_title(); ?>">
                    <?php echo get_the_post_thumbnail(null, 'small'); ?>
                </a>
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
