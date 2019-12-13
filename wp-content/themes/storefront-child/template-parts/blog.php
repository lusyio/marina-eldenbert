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
                <?php
                if ($post->post_name == 'club') {
                    $categoryId = 44; // рубрика "клуб"
                } else {
                    $categoryId = 34; // рубрика "блог"
                }
                if ($categoryId == 44):
                    ?>
                    <div class="container-related bg-white pt-5">
                        <div class="container position-relative">
                            <div class="container-related__prev"><img
                                        src="/wp-content/themes/storefront-child/svg/related-next.svg" alt="">
                            </div>
                            <div class="container-related__next"><img
                                        src="/wp-content/themes/storefront-child/svg/related-next.svg" alt="">
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <p class="container-related__title">Эксклюзивные книги</p>
                                </div>
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
                                                'tag' => 'vip',
                                            ));
                                            $products = $query->get_products();
                                            foreach ($products as $product):
                                                ?>
                                                <div class="swiper-slide wow fadeInUp"
                                                     data-wow-delay="<?php echo $delay ?>s">
                                                    <a href="<?php echo $product->get_permalink(); ?>">
                                                        <div class="related-img-container">
                                                            <?php echo $product->get_image('medium');
                                                            if ($product->price == 0):
                                                            ?>
                                                            <span class="related-img-container__free"><span class="woocommerce-Price-amount amount">Бесплатно<span class="woocommerce-Price-currencySymbol"></span></span></span>
                                                            <?php else: ?>
                                                            <span class="related-img-container__price"><?php echo $product->get_price_html(); ?></span>
                                                            <?php endif;?>
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
                <?php endif; ?>
                <div class="row blog-row">
                    <?php
                    getBlockPart('blog', 999, 0, 999, 'blog-item', $categoryId)
                    ?>
                    <div class="col-12 text-center wow fadeInUp"
                         data-wow-delay="<?php echo $delay ?>s">
                        <div class="load-more">Загрузить еще</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php }

get_footer(); ?>
