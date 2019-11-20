<?php
/*
Template Name: blog
Template Post Type: post, page, product
*/
?>

<?php get_header(); ?>


<div class="container news">
    <div class="row">
        <div class="container blog">
            <div class="row">
                <div class="col-12"><h2 class="page-title"><?php the_title() ?></h2></div>
            </div>
            <div class="row blog-row">
                <?php $catquery = new WP_Query('cat=34&posts_per_page=999'); // portfolio  ?>
                <?php while ($catquery->have_posts()) :
                    $catquery->the_post(); ?>
                    <div class="col-lg-6 col-12 blog-item">
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
                                <div class="blog-card__text"><?php the_content(); ?></div>
                                <a class="blog-card__link" href="<?php the_permalink() ?>">Подробнее</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
                <div class="col-12 text-center">
                    <div class="load-more">Загрузить еще</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
