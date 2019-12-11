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
            <div class="row announcement-hr-block wow fadeInUp"
                 data-wow-delay="0s">
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
            <div class="row blog-hr-block wow fadeInUp"
                 data-wow-delay="0s">
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
            <div class="row news-n-events-hr-block wow fadeInUp"
                 data-wow-delay="0s">
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
    </div>
</div>


<?php get_footer(); ?>
