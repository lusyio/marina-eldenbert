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
                <?php $catquery = new WP_Query('cat=33&posts_per_page=999'); // portfolio  ?>
                <?php while ($catquery->have_posts()) :
                    $catquery->the_post(); ?>
                    <div class="col-lg-6 col-12 announcement-item">
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
                                    <?php
                                    $desc = get_the_content();
                                    echo  mb_strimwidth( $desc, 0, 100, '...');
                                    ?>
                                </div>
                                <a href="<?php the_permalink() ?>" class="announcement-btn">Подробнее</a>
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
