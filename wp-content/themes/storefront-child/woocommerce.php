<?php
/**
 * The template for displaying Woocommerce Product
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

get_header(); ?>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if (is_shop()): ?>
                    <span class="page-title h2">
                            <?php woocommerce_page_title(); ?>
                    </span>
                <?php else: ?>
                    <h2 class="page-title"><?php if (is_product()) {
                            echo get_the_title();
                        } else {
                            woocommerce_page_title();
                        } ?>
                    </h2>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php if (is_product() || is_cart() || is_checkout()): ?>
    <section id="primary" class="content-area col-sm-12 mt-5">
        <main id="main" class="site-main" role="main">

            <?php woocommerce_content(); ?>
            <?php
            if (is_product()) {
                comments_template();
            } ?>
        </main><!-- #main -->
    </section><!-- #primary -->
<?php else: ?>
    <?php addFilterBar(); ?>
    <section id="primary" class="content-area col-sm-12 col-lg-9 archive-product-page">
        <main id="main" class="site-main" role="main">
            <?php woocommerce_content(); ?>
            <div class="isotope-empty">
                <p>По заданным параметрам не найдено ни одной книги</p>
            </div>
            <?php if (is_shop()): ?>
                <div class="row">
                    <div class="col">
                        <h1 class="page-title">Марина Эльденберт: читать все книги онлайн бесплатно</h1>
                        <p class="after-shop">Всем привет! Я - Марина Эльденберт, а это мой авторский сайт, на котором я
                            размещаю свои старые и
                            новые книги. Больше всего на свете мы любим писать книги, поэтому не расстаемся с героями
                            наших
                            историй ни на минуту.</p>
                        <p class="after-shop">Вы можете скачать и купить любую книгу, которая есть на это странице, а
                            так же читать их онлайн.
                            Книги разбиты на циклы, такие как: Огненное сердце Аронгары, Леди Энгерии, Глубина,
                            МежМировая
                            няня, Маги нашего времени.</p>
                    </div>
                </div>
            <?php endif; ?>
        </main><!-- #main -->
    </section><!-- #primary -->
<?php endif; ?>

<?php
get_footer();
