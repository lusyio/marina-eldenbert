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
                <?php $catquery = new WP_Query('cat=35&posts_per_page=999'); // portfolio  ?>
                <?php $delay = 0; ?>
                <?php while ($catquery->have_posts()) :
                    $catquery->the_post(); ?>
                    <div class="col-lg-4 col-12 news-n-events-item wow fadeInUp"
                         data-wow-delay="<?php echo $delay ?>s">
                        <div class="news-n-events-card">
                            <div class="news-n-events-card-body">
                                <p class="news-n-events-card__date"><?= get_the_date() ?></p>
                                <div class="news-n-events-card__text">
                                    <?php the_content(); ?>
                                </div>
                                <a href="<?php the_permalink() ?>" class="news-n-events-card__link">Подробнее</a>
                                <p class="news-n-events-card__author"><?php the_author(); ?></p>
                            </div>
                            <div class="news-n-events-card__avatar">
                                <?php echo get_avatar(get_the_author_meta($user_id)); ?>
                            </div>
                        </div>
                    </div>
                    <?php $delay = $delay + 0.2 ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
                <div class="col-12 text-center wow fadeInUp"
                     data-wow-delay="<?php echo $delay ?>s">
                    <div class="load-more">Загрузить еще</div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php get_footer(); ?>
