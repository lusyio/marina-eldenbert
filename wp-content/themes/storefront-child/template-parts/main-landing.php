<?php
/*
Template Name: main-landing
Template Post Type: post, page, product
*/
?>

<?php get_header(); ?>


<div class="container popular">
    <div class="row">
        <div class="col-12">

            <div class="swiper-container-popular">
                <div class="swiper-wrapper">
                    <?php
                    $query = new WC_Product_Query(array(
                        'status' => 'publish',
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'limit' => 5,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_tag',
                                'field' => 'slug',
                                'terms' => 'bestseller',
                            )
                        )
                    ));
                    $terms = get_the_terms(get_the_ID(), 'product_cat');
                    $products = $query->get_products();
                    foreach ($products as $product): ?>
                        <div class="swiper-slide">
                            <div class="row">
                                <div class="col-12 col-lg-4 offset-lg-1 offset-0 wow fadeInUp"
                                     data-wow-delay="0s">
                                    <p class="popular-header d-block d-lg-none text-center">Популярное</p>
                                    <div class="d-table m-auto">
                                        <a href="<?php echo $product->get_permalink(); ?>">
                                            <div class="popular-img-container">
                                                <?php echo $product->get_image(385); ?>
                                                <span class="popular-img-container__price"><?php echo $product->get_price_html(); ?></span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-7 wow fadeInUp"
                                     data-wow-delay="0.2s">
                                    <div class="row">
                                        <div class="col-lg-11 offset-lg-1 offset-0 col-12">
                                            <p class="popular-header d-lg-block d-none">Популярное</p>
                                            <h2 class="popular-title"><?php echo $product->get_name(); ?>
                                            </h2>
                                            <p class="popular-content">
                                                <?php
                                                $desc = $product->get_short_description();
                                                echo mb_strimwidth($desc, 0, 550, '...'); ?>
                                            </p>
                                            <div class="row">
                                                <div class="col-lg-5 col-12">
                                                    <a href="<?php echo $product->get_permalink(); ?>"
                                                       class="popular-btn">
                                                        Подробнее
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination popular-pagination">
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<div class="container-related">
    <div class="container position-relative">
        <div class="container-related__prev"><img src="/wp-content/themes/storefront-child/svg/related-next.svg" alt="">
        </div>
        <div class="container-related__next"><img src="/wp-content/themes/storefront-child/svg/related-next.svg" alt="">
        </div>
        <div class="row">
            <div class="col-lg-10 offset-lg-1 col-12 offset-0 text-center">
                <div class="swiper-container-related">
                    <div class="swiper-wrapper">
                        <?php
                        $delay = 0;
                        $query = new WC_Product_Query(array(
                            'status' => 'publish',
                            'orderby' => 'order_clause',
                            'order' => 'DESC',
                            'meta_query' => array(
                                'order_clause' => array(
                                    'key' => 'total_sales',
                                    'value' => 'some_value',
                                    'type' => 'NUMERIC' // unless the field is not a number
                                )),
                            'limit' => 12,
                        ));
                        $products = $query->get_products();
                        foreach ($products as $product):
                            ?>
                            <div class="swiper-slide wow fadeInUp" data-wow-delay="<?php echo $delay ?>s">
                                <a href="<?php echo $product->get_permalink(); ?>">
                                    <div class="related-img-container">
                                        <?php echo $product->get_image('medium'); ?>
                                        <span class="related-img-container__price"><?php echo $product->get_price_html(); ?></span>
                                    </div>
                                    <p class="related-img-container__header"><?php echo $product->get_name(); ?></p>
                                </a>
                            </div>
                            <?php $delay = $delay + 0.2; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="container announcement">
            <div class="row announcement-hr-block wow fadeInUp" data-wow-delay="0s">
                <div class="col-12">
                    <p class="announcement-hr-block__text">Анонсы</p>
                </div>
            </div>
            <div class="row">
                <?php $catquery = new WP_Query('cat=33&posts_per_page=2'); // portfolio  ?>
                <?php
                $delay = 0.2;
                $portfolio_counter = 1; ?>
                <?php while ($catquery->have_posts()) :
                    $catquery->the_post(); ?>
                    <?php if ($portfolio_counter == 1): ?>
                    <div class="col-lg-6 col-12 mb-5 wow fadeInUp" data-wow-delay="<?php echo $delay ?>s">
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
                                    <p>
                                        <?php
                                        $desc = get_the_content();
                                        echo mb_strimwidth($desc, 0, 100, '...');
                                        ?>
                                    </p>
                                </div>
                                <a href="<?php the_permalink() ?>" class="announcement-btn">Подробнее</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                    <?php if ($portfolio_counter == 2): ?>
                    <div class="col-lg-6 col-12 mb-5 d-lg-block d-none wow fadeInUp"
                         data-wow-delay="<?php echo $delay ?>s">
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
                                    <p>
                                        <?php
                                        $desc = get_the_content();
                                        echo mb_strimwidth($desc, 0, 100, '...');
                                        ?>
                                    </p>
                                </div>
                                <a href="<?php the_permalink() ?>" class="announcement-btn">Подробнее</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-center wow fadeInUp"
                         data-wow-delay="<?php echo $delay ?>s"><a class="announcement__link"
                                                       href="<?php echo get_permalink($post = 42) ?>">Смотреть все
                            анонсы</a>
                    </div>

                    <?php break; ?>
                <?php endif; ?>
                    <?php $portfolio_counter++; ?>
                    <?php $delay = $delay + 0.2; ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
        <div class="container blog">
            <div class="row blog-hr-block wow fadeInUp" data-wow-delay="0s">
                <div class="col-12">
                    <p class="blog-hr-block__text">Авторский блог</p>
                </div>
            </div>
            <div class="row">
                <?php $catquery = new WP_Query('cat=34&posts_per_page=2'); // portfolio  ?>
                <?php $portfolio_counter = 1; ?>
                <?php $delay = 0.2; ?>
                <?php while ($catquery->have_posts()) :
                    $catquery->the_post(); ?>
                    <?php if ($portfolio_counter == 1): ?>
                    <div class="col-lg-6 col-12 mb-5 wow fadeInUp" data-wow-delay="<?php echo $delay ?>s">
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
                <?php endif; ?>
                    <?php if ($portfolio_counter == 2): ?>
                    <div class="col-lg-6 col-12 mb-5 d-lg-block d-none wow fadeInUp"
                         data-wow-delay="<?php echo $delay ?>s">
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

                    <div class="col-12 text-center wow fadeInUp"
                         data-wow-delay="<?php echo $delay ?>s"><a class="blog__link"
                                                       href="<?php echo get_permalink($post = 33) ?>">Смотреть все
                            посты</a></div>
                    <?php break; ?>
                <?php endif; ?>
                    <?php $portfolio_counter++; ?>
                    <?php $delay = $delay + 0.2; ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
        <div class="container news-n-events">
            <div class="row news-n-events-hr-block wow fadeInUp" data-wow-delay="0s">
                <div class="col-12">
                    <p class="news-n-events-hr-block__text">Новости и события</p>
                </div>
            </div>
            <div class="row">
                <?php $catquery = new WP_Query('cat=35&posts_per_page=3'); // portfolio  ?>
                <?php $portfolio_counter = 1; ?>
                <?php $delay = 0.2; ?>
                <?php while ($catquery->have_posts()) :
                    $catquery->the_post(); ?>
                    <?php if ($portfolio_counter < 3): ?>
                    <div class="col-lg-4 col-12 mb-lg-5 mb-2 wow fadeInUp" data-wow-delay="<?php echo $delay ?>s">
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
                <?php endif; ?>
                    <?php if ($portfolio_counter == 3): ?>
                    <div class="col-lg-4 col-12 mb-lg-5 mb-2 d-lg-block d-none wow fadeInUp"
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

                    <div class="col-12 text-center wow fadeInUp"
                         data-wow-delay="<?php echo $delay ?>s"><a class="news-n-events__link"
                                                       href="<?php echo get_permalink($post = 44) ?>">Смотреть все
                            новости</a>
                    </div>
                    <?php break; ?>
                <?php endif; ?>
                    <?php $portfolio_counter++; ?>
                    <?php $delay = $delay + 0.2; ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>

        <?php get_footer(); ?>
