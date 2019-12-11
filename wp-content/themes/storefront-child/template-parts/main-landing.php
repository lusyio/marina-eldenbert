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
                    $args = [
                        'status' => 'publish',
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'limit' => 5,
                        'tax_query' => [
                            [
                                'taxonomy' => 'product_tag',
                                'field' => 'slug',
                                'terms' => 'bestseller',
                            ],
                        ]
                    ];
                    $myRank = mycred_get_my_rank();
                    if (!is_null($myRank) && $myRank->post->post_name == 'platinum-dragon') {
                        $hasVip = true;
                    } else {
                        $hasVip = false;
                    }
                    if (!$hasVip && !isAdmin()) {
                        $args['tax_query']['relation'] = 'AND';
                        $args['tax_query'][] = [
                            'taxonomy' => 'product_tag',
                            'terms' => ['vip'],
                            'field' => 'slug',
                            'operator' => 'NOT IN',
                        ];
                    }
                    $query = new WC_Product_Query($args);
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
                                                <?php echo $product->get_image('large');
                                                if ($product->price == 0) : ?>
                                                    <span class="popular-img-container__free"><span
                                                                class="woocommerce-Price-amount amount">Бесплатно<span
                                                                    class="woocommerce-Price-currencySymbol"></span></span></span>
                                                <?php else: ?>
                                                    <span class="popular-img-container__price"><?php echo $product->get_price_html(); ?></span>
                                                <?php endif; ?>
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
                                                $desc = strip_tags($product->get_short_description());
                                                $size = 550;
                                                echo mb_substr($desc, 0, mb_strrpos(mb_substr($desc, 0, $size, 'utf-8'), ' ', 'utf-8'), 'utf-8');
                                                echo (strlen($desc) > $size) ? '...' : '';
                                                ?>
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
                        $args = array(
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
                        );
                        if (!$hasVip && !isAdmin()) {
                            $args['tax_query']['relation'] = 'AND';
                            $args['tax_query'][] = [
                                'taxonomy' => 'product_tag',
                                'terms' => ['vip'],
                                'field' => 'slug',
                                'operator' => 'NOT IN',
                            ];
                        }
                        $query = new WC_Product_Query($args);
                        $products = $query->get_products();
                        foreach ($products as $product):
                            ?>
                            <div class="swiper-slide wow fadeInUp" data-wow-delay="<?php echo $delay ?>s">
                                <a href="<?php echo $product->get_permalink(); ?>">
                                    <div class="related-img-container">
                                        <?php echo $product->get_image('medium');
                                        if ($product->price == 0):
                                            ?>
                                            <span class="related-img-container__free"><span
                                                        class="woocommerce-Price-amount amount">Бесплатно<span
                                                            class="woocommerce-Price-currencySymbol"></span></span></span>
                                        <?php else: ?>
                                            <span class="related-img-container__price"><?php echo $product->get_price_html(); ?></span>
                                        <?php endif; ?>
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
                <?php
                getBlockPart('announcement', 2, 0.2, 2, 'mb-5')
                ?>
            </div>
        </div>
        <div class="container blog">
            <div class="row blog-hr-block wow fadeInUp" data-wow-delay="0s">
                <div class="col-12">
                    <p class="blog-hr-block__text">Авторский блог</p>
                </div>
            </div>
            <div class="row">
                <?php
                getBlockPart('blog', 2, 0.2, 2, 'mb-5', 34)
                ?>
            </div>
        </div>
        <div class="container news-n-events">
            <div class="row news-n-events-hr-block wow fadeInUp" data-wow-delay="0s">
                <div class="col-12">
                    <p class="news-n-events-hr-block__text">Новости и события</p>
                </div>
            </div>
            <div class="row">
                <?php
                getBlockPart('news-n-events', 3, 0.2, 3, 'mb-lg-5 mb-2')
                ?>
            </div>
        </div>

        <?php get_footer(); ?>
