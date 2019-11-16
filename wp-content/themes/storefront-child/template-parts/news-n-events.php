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
                <?php $catquery = new WP_Query('cat=35&posts_per_page=9'); // portfolio  ?>
                <?php $portfolio_counter = 1; ?>
                <?php while ($catquery->have_posts()) :
                    $catquery->the_post(); ?>
                    <div class="col-lg-4 col-12">
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
                    <?php if ($portfolio_counter == 9): ?>
                    <div class="col-12 text-center">
                        <div class="load-more">Загрузить еще</div>
                    </div>
                    <?php break; ?>
                <?php endif; ?>
                    <?php $portfolio_counter++; ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
</div>


<?php get_footer(); ?>
