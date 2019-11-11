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
                <div class="col-lg-6 col-12">
                    <div class="row">
                        <div class="col-lg-6 col-12 m-auto">
                            <a href="#">
                                <div class="announcement-img">
                                    <img src="/wp-content/themes/storefront-child/images/announcement-example.jpg"
                                         alt="">
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-12">
                            <h3 class="announcement-header">
                                Аудиокнига “Девушка в цепях”
                            </h3>
                            <p class="announcement-content">
                                🎧 Совсем скоро! Аудиокнига "Девушка в цепях" на Литрес!
                            </p>
                            <a href="#" class="announcement-btn">Подробнее</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="row">
                        <div class="col-lg-6 col-12 m-auto">
                            <a href="">
                                <div class="announcement-img">
                                    <img src="/wp-content/themes/storefront-child/images/announcement-example.jpg"
                                         alt="">
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-12">
                            <h3 class="announcement-header">
                                Аудиокнига “Девушка в цепях”
                            </h3>
                            <p class="announcement-content">
                                🎧 Совсем скоро! Аудиокнига "Девушка в цепях" на Литрес!
                            </p>
                            <a href="#" class="announcement-btn">Подробнее</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-center"><a class="announcement__link" href="#">Смотреть все анонсы</a></div>
            </div>
        </div><div class="container blog">
            <div class="row blog-hr-block">
                <div class="col-12">
                    <p class="blog-hr-block__text">Авторский блог</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="blog-card">
                        <div class="blog-card__header">
                            <a href="#">
                                <img class="blog-card__img"
                                     src="/wp-content/themes/storefront-child/images/blog-example.jpg"
                                     alt="">
                            </a>
                        </div>
                        <div class="blog-card__body">
                            <p class="blog-card__date">31.10.2019</p>
                            <p class="blog-card__text">Название найдено! Из предложенных вариантов издательство
                                остановилось
                                на
                                варианте, предложенном Оксаной Тимофеевой 😉 Итак, вторая часть будет называться...
                            </p>
                            <a class="blog-card__link" href="#">Подробнее</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="blog-card">
                        <div class="blog-card__header">
                            <a href="#">
                                <img class="blog-card__img"
                                     src="/wp-content/themes/storefront-child/images/blog-example.jpg"
                                     alt="">
                            </a>
                        </div>
                        <div class="blog-card__body">
                            <p class="blog-card__date">31.10.2019</p>
                            <p class="blog-card__text">Название найдено! Из предложенных вариантов издательство
                                остановилось
                                на
                                варианте, предложенном Оксаной Тимофеевой 😉 Итак, вторая часть будет называться...
                            </p>
                            <a class="blog-card__link" href="#">Подробнее</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-center"><a class="blog__link" href="#">Смотреть все посты</a></div>
            </div>
        </div>

        <div class="container news-n-events">
            <div class="row news-n-events-hr-block">
                <div class="col-12">
                    <p class="news-n-events-hr-block__text">Новости и события</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-12">
                    <div class="news-n-events-card">
                        <div class="news-n-events-card-body">
                            <p class="news-n-events-card__date">31.10.2019</p>
                            <p class="news-n-events-card__text">Скоро будем обсуждать обложку для Фервернской
                                истории с
                                Ириной Косулиной </p>
                            <a href="#" class="news-n-events-card__link">Подробнее</a>
                            <a href="#" class="news-n-events-card__author">Марина Эльденбер</a>
                        </div>
                        <img class="news-n-events-card__avatar"
                             src="/wp-content/themes/storefront-child/images/avatar.jpg" alt="">
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="news-n-events-card">
                        <div class="news-n-events-card-body">
                            <p class="news-n-events-card__date">31.10.2019</p>
                            <p class="news-n-events-card__text">Скоро будем обсуждать обложку для Фервернской
                                истории с
                                Ириной Косулиной </p>
                            <a href="#" class="news-n-events-card__link">Подробнее</a>
                            <a href="#" class="news-n-events-card__author">Марина Эльденбер</a>
                        </div>
                        <img class="news-n-events-card__avatar"
                             src="/wp-content/themes/storefront-child/images/avatar.jpg" alt="">
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="news-n-events-card">
                        <div class="news-n-events-card-body">
                            <p class="news-n-events-card__date">31.10.2019</p>
                            <p class="news-n-events-card__text">Скоро будем обсуждать обложку для Фервернской
                                истории с
                                Ириной Косулиной </p>
                            <a href="#" class="news-n-events-card__link">Подробнее</a>
                            <a href="#" class="news-n-events-card__author">Марина Эльденбер</a>
                        </div>
                        <img class="news-n-events-card__avatar"
                             src="/wp-content/themes/storefront-child/images/avatar.jpg" alt="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-center"><a class="news-n-events__link" href="#">Смотреть все новости</a></div>
            </div>
        </div>
    </div>
</div>


<?php get_footer(); ?>
