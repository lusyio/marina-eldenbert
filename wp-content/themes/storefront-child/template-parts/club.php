<?php
/*
Template Name: club
Template Post Type: post, page, product
*/
?>

<?php get_header(); ?>

</div>
</div>

<div class="club-container">
    <div class="container club-bg">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-12 offset-0">
                <div class="club-header">
                    <h2 class="club-header__title">Закрытый клуб
                        Марины Эльденберт</h2>
                    <p class="club-header__text">Закрытый тайный клуб, доступный только для избранных читателей.
                        Участвуй в жизни сайта, комментируй. общайся, рассказывай друзьям о новостях и событиях в
                        соцсетях и зарабатывай за это балы. Члены клуба получают эксклюзивные материалы по книгам Марины
                        Эльденберт.</p>
                    <a class="club-header__btn" href="<?php echo get_permalink(wc_get_page_id('myaccount')) ?>">Вступить</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container club-content">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 col-12 offset-0">
            <p class="club-content__title">Что дает членство в клубе
                и как стать участником?</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 offset-1 col-12 offset-0">
            <p class="club-content__text">Всем членам VIP клуба открывается доступ к закрытому разделу сайта, в котором
                публкиуются эксклюзивные материалы, бонусные рассказы к любимым историям, проводятся розыгрыши книг с
                автографом, абонементов на бесплатное чтение и сувениров от автора. Скидка постоянного читателя
                составляет 20%</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 offset-lg-3 col-12 offset-0">
            <img class="club-content__img" src="/wp-content/themes/storefront-child/images/club-coin.png" alt="">
        </div>
    </div>
    <div class="row club-footer">
        <div class="col-lg-8 offset-lg-2 col-12 offset-0">
            <p class="club-footer__text">Зарегистрируйся на сайте
                <strong>до 31 декабря 2019</strong>
                и получи моментальный доступ</p>
            <a class="club-footer__btn" href="<?php echo get_permalink(wc_get_page_id('myaccount')) ?>">Вступить в закрытый клуб</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">


        <?php get_footer(); ?>
