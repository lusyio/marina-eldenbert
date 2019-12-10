<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package storefront
 */

?>

<div class="col-12 col-lg-4 order-lg-3 order-1 mb-5">
    <div class="filter-collapse-btn d-lg-none d-block" data-toggle="collapse" data-target="#collapseSidebar"
         aria-expanded="false" aria-controls="collapseSidebar">
        Сайдбар
    </div>
    <div class="collapse d-lg-block" id="collapseSidebar">
        <?php $catquery = new WP_Query('cat=33&posts_per_page=1'); // portfolio  ?>
        <?php while ($catquery->have_posts()) :
            $catquery->the_post(); ?>
            <div class="sidebar-announcement">
                <a href="<?php the_permalink() ?>">
                    <div class="sidebar-announcement__img">
                        <?= get_the_post_thumbnail() ?>
                    </div>
                </a>
                <h3 class="sidebar-announcement__title"><?php the_title(); ?></h3>
                <div class="sidebar-announcement__text">
                    <p>
                        <?php
                        $desc = get_the_content();
                        echo mb_strimwidth($desc, 0, 105, '...');
                        ?>
                    </p>
                </div>
                <a href="<?php the_permalink() ?>" class="sidebar-announcement__btn">Подробнее</a>
            </div>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
        <hr class="sidebar-announcement__hr">

        <div class="sidebar-news">
            <?php $catquery = new WP_Query('cat=35&posts_per_page=3'); // portfolio  ?>
            <?php while ($catquery->have_posts()) :
                $catquery->the_post(); ?>
                <div class="row">
                    <div class="col-3">
                        <div class="sidebar-news__img">
                            <?php echo get_avatar(get_the_author_meta('ID'), 300); ?>
                        </div>
                    </div>
                    <div class="col">
                        <div class="sidebar-news__text">
                            <p>
                                <?php
                                $desc = get_the_content();
                                echo mb_strimwidth($desc, 0, 105, '...');
                                ?>
                            </p>
                        </div>
                        <a href="<?php the_permalink() ?>" class="sidebar-news__link">Подробнее</a>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        <hr class="sidebar-announcement__hr">

        <?php $catquery = new WP_Query('cat=34&posts_per_page=1'); // portfolio  ?>
        <?php while ($catquery->have_posts()) :
            $catquery->the_post(); ?>
            <div class="sidebar-blog">
                <div class="sidebar-blog-card">
                    <div class="sidebar-blog-card__header">
                        <a href="<?php the_permalink() ?>">
                            <div class="blog-card__img">
                                <?= get_the_post_thumbnail() ?>
                            </div>
                        </a>
                    </div>
                    <div class="sidebar-blog-card__body">
                        <div class="sidebar-blog-card__text">
                            <p>
                                <?php
                                $desc = get_the_content();
                                echo mb_strimwidth($desc, 0, 140, '...');
                                ?>
                            </p>
                        </div>
                        <a href="<?php the_permalink() ?>" class="sidebar-blog-card__link">Подробнее</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
    </div>
</div>
