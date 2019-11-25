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
<script>
    jQuery(function ($) {
        $('.cir-image-link').fancybox(
            {
                buttons: [
                    'download',
                    'thumbs',
                    'close'
                ],
                caption: function () {
                    return $(this).parents('.grid-item').children('p').text();
                },
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
