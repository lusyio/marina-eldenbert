<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

</div><!-- .row -->
</div><!-- .container -->

<?php do_action('storefront_before_footer'); ?>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
     data-keyboard="false" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 text-center">
                            <p class="deleteModal__header">Удаление из библиотеки</p>
                            <p class="deleteModal__que">Вы действительно хотите удалить книгу из библиотеки?</p>
                            <a href="#" class="deleteModal__agree">Да</a>
                            <div class="deleteModal__disagree" data-dismiss="modal">Нет</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="loginForLink" tabindex="-1" role="dialog" aria-labelledby="loginForLink"
     data-keyboard="false" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 text-center">
                            <p class="deleteModal__header">Необходимо авторизоваться</p>
                            <p class="deleteModal__que">Вам необходимо авторизоваться, чтобы скачивать книги</p>
                            <a href="<?php echo get_permalink(wc_get_page_id('myaccount')) ?>"
                               class="deleteModal__agree">Войти</a>
                            <div class="deleteModal__disagree" data-dismiss="modal">Закрыть</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="settingsModal"
     data-keyboard="false" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 p-0 pl-lg-3 pr-lg-3 text-center">
                            <p class="deleteModal__header">Тут вы можете настроить по своему вкусу читалку</p>

                            <div class="settingsModal__font-size">
                                <p class="theme-buttons__title">Настройки темы:</p>
                                <div class="theme-buttons">
                                    <button class="litnetBtn" id="litnet1">Аа</button>
                                    <button class="litnetBtn" id="litnet2">Аа</button>
                                    <button class="litnetBtn" id="litnet3">Аа</button>
                                    <button class="litnetBtn" id="litnet4">Аа</button>
                                    <button class="litnetBtn" id="resetTheme">По умолчанию</button>
                                </div>
                                <p>Настройки шрифта:</p>
                                <div class="font-size-buttons">
                                    <button id="decreaseFont">Аа -</button>
                                    <button id="increaseFont">аА +</button>
                                    <button id="increaseWeight">Ж</button>
                                    <button id="resetFont">По умолчанию</button>
                                </div>
                                <p class="font-size">
                                    Пример текста
                                </p>
                            </div>
                            <button class="deleteModal__agree" id="saveSettings" data-dismiss="modal">Сохранить</button>
                            <div class="deleteModal__disagree" data-dismiss="modal">Отменить</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="container pt-3 pb-3">
        <div class="row">
            <div class="col-12 text-center col-xl-3 col-lg-2 p-lg-0 p-xl-unset p-unset text-lg-left footer-logo m-auto">
                <div class="site-info">
                    <?php $customLogo = get_custom_logo(); ?>

                    <?php if ($customLogo): ?>

                        <?php echo $customLogo; ?>
                    <?php else: ?>
                        <a class="site-title"
                           href="<?php echo esc_url(home_url('/')); ?>"><?php esc_url(bloginfo('name')); ?>
                            <p class="mb-0 site-description"><?php bloginfo('description'); ?></p>
                        </a>
                    <?php endif; ?>
                </div><!-- close .site-info -->
            </div>
            <div class="col-12 text-center col-xl-7 col-lg-8 text-lg-left mb-lg-0 mb-4">
                <div class="row">

                    <?php
                    if ($menu_items = wp_get_nav_menu_items('second')) {
                        $col = '';
                        $col_counter = 0;
                        $menu_list = '';
                        echo '<div class="col-12 text-center col-md-4 text-lg-left">';
                        echo '<div class="footer-menu">';
                        echo '<ul class="menu" id="menu-second">';
                        $menu_number = 0;
                        $half_count = ceil(count($menu_items) / 3);
                        foreach ((array)$menu_items as $key => $menu_item) {
                            if ($col_counter == $half_count) {
                                $col = 'col-md-3 p-0';
                            }
                            if ($col_counter == $half_count * 2) {
                                $col = 'col-md-5';
                            }
                            $title = $menu_item->title; // заголовок элемента меню (анкор ссылки)
                            $url = $menu_item->url; // URL ссылки
                            if ($menu_number != $half_count) {
                                echo '<li class="mb-lg-3 mb-3"><a href="' . $url . '">' . $title . '</a></li>';
                            } else {
                                echo '</ul>';
                                echo '</div>';
                                echo '</div>';
                                echo '<div class="col-12 text-center ' . $col . ' text-lg-left">';
                                echo '<div class="footer-menu">';
                                echo '<ul class="menu" id="menu-second_1">';
                                echo '<li class="mb-lg-3 mb-3"><a href="' . $url . '">' . $title . '</a></li>';
                                if ($menu_number == $half_count) {
                                    $menu_number = 0;
                                }
                            }
                            $col_counter++;
                            $menu_number++;
                        }
                        echo '</ul>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            <div class="col-12 footer-socials text-center col-lg-2 text-lg-right m-auto pl-0">
                <div class="mb-0 mt-lg-0 mt-2">
                    <a class="text-decoration-none socials" href="https://vk.com/meldenbert"><img
                                src="/wp-content/themes/storefront-child/svg/vk.svg" alt=""></a>
                    <a class="text-decoration-none ml-3 socials" href="https://www.facebook.com/marina.eldenbert"><img
                                src="/wp-content/themes/storefront-child/svg/facebook.svg" alt=""></a>
                    <a class="text-decoration-none ml-3 socials" href="https://www.instagram.com/marinaeldenbert/"><img
                                src="/wp-content/themes/storefront-child/svg/instagram.svg" alt=""></a>
                </div>
            </div>
        </div>
    </div>
    <hr class="footer-hr">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-4">
                <p class="mb-4 mb-lg-0 footer-disclaimer text-lg-left text-center">
                    <img src="/wp-content/themes/storefront-child/svg/18.svg" alt="">
                    Сайт может содержать материалы,
                    не предназначенные для просмотра лицами,
                    не достигшими 18 лет!
                </p>
            </div>
            <div class="col-12 col-lg-2">
                <div class="footer-logos">
                    <img src="/wp-content/themes/storefront-child/svg/visa.svg" alt="">
                    <img src="/wp-content/themes/storefront-child/svg/ms.svg" alt="">
                </div>
            </div>
            <div class="col-12 col-lg-6 text-center text-lg-right ">
                <p class="footer-name-p">&copy; <a class="footer-name" href="https://marina-eldenbert.ru">Марина Эльденберт</a>, <?php echo date('Y'); ?></p>
                <p class="mb-0 footer-credits d-block">
                    <a class="credits" href="https://richbee.ru/"
                       target="_blank"><img src="/wp-content/themes/storefront-child/svg/Richbee-black.svg?ver=1.0.1" alt=""></a>
                </p>
            </div>
        </div>


    </div>


    <div class="col-full">

        <?php
        /**
         * Functions hooked in to storefront_footer action
         *
         * @hooked storefront_footer_widgets - 10
         * @hooked storefront_credit         - 20
         */
        do_action('storefront_footer');
        ?>

    </div><!-- .col-full -->
</footer><!-- #colophon -->

<?php do_action('storefront_after_footer'); ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
