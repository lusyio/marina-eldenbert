<?php
/*
Template Name: news
Template Post Type: post, page, product
*/
?>

<?php get_header(); ?>


<div class="container news">
    <div class="row">
        <div class="container announcement">
            <div class="row">
                <div class="col-12"><h2 class="page-title"><?php the_title() ?></h2></div>
            </div>
            <div class="row announcement-row">
                <?php
                getBlockPart('announcement', 999, 0.2, 999, 'announcement-item')
                ?>
                <div class="col-12 text-center wow fadeInUp"
                     data-wow-delay="<?php echo $delay ?>s">
                    <div class="load-more">Загрузить еще</div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php get_footer(); ?>
