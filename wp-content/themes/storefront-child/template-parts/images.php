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
        <div class="grid-item"><img src="/wp-content/themes/storefront-child/images/image1.jpg" alt=""></div>
        <div class="grid-item"><img src="/wp-content/themes/storefront-child/images/image2.jpg" alt=""></div>
        <div class="grid-item"><img src="/wp-content/themes/storefront-child/images/image3.jpg" alt=""></div>
        <div class="grid-item"><img src="/wp-content/themes/storefront-child/images/image4.jpg" alt=""></div>
        <div class="grid-item"><img src="/wp-content/themes/storefront-child/images/image5.jpg" alt=""></div>
        <div class="grid-item"><img src="/wp-content/themes/storefront-child/images/image6.jpg" alt=""></div>
    </div>
</div>

<script src="/wp-content/themes/storefront-child/inc/assets/js/masonry.pkgd.min.js"></script>
<script>
    jQuery(document).ready(function ($) {
        var $grid = $('.grid').masonry({
            itemSelector: '.grid-item',
            percentPosition: true,
            columnWidth: '.grid-sizer',
            gutter: 30,
        });
    })
</script>
<?php get_footer(); ?>
