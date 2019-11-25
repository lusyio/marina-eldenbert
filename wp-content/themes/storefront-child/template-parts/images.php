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
    <?php comments_template('/image-comments.php'); ?>
</div>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script src="/wp-content/themes/storefront-child/inc/assets/js/masonry.pkgd.min.js"></script>
<script>

    jQuery(function ($) {
        $('[data-fancybox').fancybox({
            buttons: [
                'download',
                'thumbs',
                'close'
            ]
        });
        var $grid = $('.grid').masonry({
            itemSelector: '.grid-item',
            percentPosition: true,
            columnWidth: '.grid-sizer',
            gutter: 30,
        });
    })
</script>

<?php get_footer(); ?>
