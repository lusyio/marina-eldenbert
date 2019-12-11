<?php
/*
Template Name: news-n-events
Template Post Type: post, page, product
*/
?>

<?php get_header(); ?>


<div class="container">
    <div class="row">
        <div class="container news-n-events">
            <div class="row">
                <div class="col-12"><h2 class="page-title"><?php the_title() ?></h2></div>
            </div>
            <div class="row news-n-events-row">
                <?php
                getBlockPart('news-n-events', 999, 0, 999, 'news-n-events-item', '')
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
