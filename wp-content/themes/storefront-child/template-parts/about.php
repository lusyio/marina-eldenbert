<?php
/*
Template Name: about
Template Post Type: post, page, product
*/
?>

<?php get_header(); ?>


<div class="container popular">
    <div class="row">
        <div class="col-12"><h2 class="page-title"><?php the_title() ?></h2></div>
    </div>
    <div class="row">
        <div class="col">
            <?php the_content(); ?>
        </div>
    </div>
</div>


<?php get_footer(); ?>
