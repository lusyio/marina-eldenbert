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
            <div class="row announcement-hr-block">
                <div class="col-12">
                    <p class="announcement-hr-block__text">Анонсы</p>
                </div>
            </div>
            <div class="row">
                <?php $catquery = new WP_Query('cat=33&posts_per_page=2'); // portfolio  ?>
                <?php $portfolio_counter = 1; ?>
                <?php while ($catquery->have_posts()) :
                    $catquery->the_post(); ?>
                    <div class="col-lg-6 col-12 mb-5">
                        <div class="row">
                            <div class="col-sm-6 col-12">
                                <a href="<?php the_permalink() ?>">
                                    <div class="announcement-img">
                                        <?= get_the_post_thumbnail() ?>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-6 col-12 mb-sm-0 mb-4 position-relative">
                                <h3 class="announcement-header">
                                    <?php the_title(); ?>
                                </h3>
                                <div class="announcement-content">
                                    <?php the_content(); ?>
                                </div>
                                <a href="<?php the_permalink() ?>" class="announcement-btn">Подробнее</a>
                            </div>
                        </div>
                    </div>
                    <?php if ($portfolio_counter == 2): ?>

                    <div class="col-12 text-center"><a class="announcement__link" href="#">Смотреть все анонсы</a></div>

                    <?php break; ?>
                <?php endif; ?>
                    <?php $portfolio_counter++; ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>

        <div class="container blog">
            <div class="row blog-hr-block">
                <div class="col-12">
                    <p class="blog-hr-block__text">Авторский блог</p>
                </div>
            </div>
            <div class="row">
                <?php $catquery = new WP_Query('cat=34&posts_per_page=2'); // portfolio  ?>
                <?php $portfolio_counter = 1; ?>
                <?php while ($catquery->have_posts()) :
                    $catquery->the_post(); ?>
                    <div class="col-lg-6 col-12 mb-5">
                        <div class="blog-card">
                            <div class="blog-card__header">
                                <a href="<?php the_permalink() ?>">
                                    <div class="blog-card__img">
                                        <?= get_the_post_thumbnail() ?>
                                    </div>
                                </a>
                            </div>
                            <div class="blog-card__body">
                                <p class="blog-card__date"><?= get_the_date() ?></p>
                                <div class="blog-card__text"><?php the_content(); ?>
                                </div>
                                <a class="blog-card__link" href="<?php the_permalink() ?>">Подробнее</a>
                            </div>
                        </div>
                    </div>
                    <?php if ($portfolio_counter == 2): ?>
                    <div class="col-12 text-center"><a class="blog__link" href="#">Смотреть все посты</a></div>
                    <?php break; ?>
                <?php endif; ?>
                    <?php $portfolio_counter++; ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>

        <div class="container news-n-events">
            <div class="row news-n-events-hr-block">
                <div class="col-12">
                    <p class="news-n-events-hr-block__text">Новости и события</p>
                </div>
            </div>
            <div class="row">
                <?php $catquery = new WP_Query('cat=35&posts_per_page=3'); // portfolio  ?>
                <?php $portfolio_counter = 1; ?>
                <?php while ($catquery->have_posts()) :
                    $catquery->the_post(); ?>
                    <div class="col-lg-4 col-12 mb-5">
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
                    <?php if ($portfolio_counter == 3): ?>
                    <div class="col-12 text-center"><a class="news-n-events__link" href="#">Смотреть все новости</a>
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
