<?php
/*
Template Name: blog
Template Post Type: post, page, product
*/
$myRank = mycred_get_my_rank();
$hasVip = false;
if (!is_null($myRank) && $myRank->post->post_name == 'platinum-dragon') {
    $hasVip = true;
}
if ($post->post_name == 'club' && !$hasVip && !isAdmin()) {
    get_template_part('template-parts/club');
} else { ?>

    <?php get_header(); ?>


    <div class="container news">
        <div class="row">
            <div class="container blog">
                <div class="row">
                    <div class="col-12"><h2 class="page-title"><?php the_title() ?></h2></div>
                </div>
                <div class="row blog-row">
                    <?php
                    if ($post->post_name == 'club') {
                        $categoryId = 44; // рубрика "клуб"
                    } else {
                        $categoryId = 34; // рубрика "блог"

                    }
                    $catquery = new WP_Query('cat=' . $categoryId . '&posts_per_page=999');
                    $delay = 0;
                    ?>
                    <?php if ($catquery->have_posts()) : ?>
                        <?php while ($catquery->have_posts()) :
                            $catquery->the_post(); ?>
                            <div class="col-lg-6 col-12 blog-item wow fadeInUp"
                                 data-wow-delay="<?php echo $delay ?>s">
                                <div class="blog-card">
                                    <div class="blog-card__header">
                                        <a href="<?php the_permalink() ?>">
                                            <div class="blog-card__img">
                                                <?= get_the_post_thumbnail('', 'large' ) ?>
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
                            <?php $delay = $delay + 0.2 ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-lg-6 offset-lg-3 col-12 blog-item wow fadeInUp"
                             data-wow-delay="<?php echo $delay ?>s">
                            <div class="blog-card">
                                <div class="blog-card__body">
                                    <div class="blog-card__text text-center">Пока нет новостей</div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                    <div class="col-12 text-center wow fadeInUp"
                         data-wow-delay="<?php echo $delay ?>s">
                        <div class="load-more" style="display: none">Загрузить еще</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php }

get_footer(); ?>
