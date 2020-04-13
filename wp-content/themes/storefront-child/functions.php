<?php
/**
 * Richbee functions and definitions
 *
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
//add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );
/**
 * Dequeue the Storefront Parent theme core CSS
 */
function sf_child_theme_dequeue_style()
{
    wp_dequeue_style('storefront-style');
    wp_dequeue_style('storefront-woocommerce-style');
}

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */
function enqueue_child_theme_styles()
{
// load bootstrap css
    wp_enqueue_style('bootstrap-css', get_stylesheet_directory_uri() . '/inc/assets/css/bootstrap.min.css', false, NULL, 'all');
// fontawesome cdn
    wp_enqueue_style('wp-bootstrap-pro-fontawesome-cdn', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/fontawesome.min.css');
// load bootstrap css
// load AItheme styles
// load WP Bootstrap Starter styles
    wp_enqueue_style('wp-bootstrap-starter-style', get_stylesheet_uri(), array('theme'));
    if (get_theme_mod('theme_option_setting') && get_theme_mod('theme_option_setting') !== 'default') {
        wp_enqueue_style('wp-bootstrap-starter-' . get_theme_mod('theme_option_setting'), get_template_directory_uri() . '/inc/assets/css/presets/theme-option/' . get_theme_mod('theme_option_setting') . '.css', false, '');
    }
    wp_enqueue_style('wp-bootstrap-starter-robotoslab-roboto', 'https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap');

    wp_enqueue_script('jquery');

    // Internet Explorer HTML5 support
    wp_enqueue_script('html5hiv', get_template_directory_uri() . '/inc/assets/js/html5.js', array(), '3.7.0', false);
    wp_script_add_data('html5hiv', 'conditional', 'lt IE 9');

// load bootstrap js
    wp_enqueue_script('wp-bootstrap-starter-popper', get_stylesheet_directory_uri() . '/inc/assets/js/popper.min.js', array(), '', true);
    wp_enqueue_script('wp-bootstrap-starter-bootstrapjs', get_stylesheet_directory_uri() . '/inc/assets/js/bootstrap.min.js', array(), '', true);
    wp_enqueue_script('wp-bootstrap-starter-themejs', get_stylesheet_directory_uri() . '/inc/assets/js/theme-script.min.js', array(), '', true);
    wp_enqueue_script('wp-bootstrap-starter-skip-link-focus-fix', get_stylesheet_directory_uri() . '/inc/assets/js/skip-link-focus-fix.min.js', array(), '', true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
//enqueue my child theme stylesheet
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('theme'));
}

add_action('wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);

remove_action('wp_head', 'feed_links_extra', 3); // убирает ссылки на rss категорий
remove_action('wp_head', 'feed_links', 2); // минус ссылки на основной rss и комментарии
remove_action('wp_head', 'rsd_link');  // сервис Really Simple Discovery
remove_action('wp_head', 'wlwmanifest_link'); // Windows Live Writer
remove_action('wp_head', 'wp_generator');  // скрыть версию wordpress

/**
 * Load custom WordPress nav walker.
 */
if (!class_exists('wp_bootstrap_navwalker')) {
    require_once(get_stylesheet_directory() . '/inc/wp_bootstrap_navwalker.php');
}

/**
 * Удаление json-api ссылок
 */
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('template_redirect', 'rest_output_link_header', 11);

/**
 * Cкрываем разные линки при отображении постов блога (следующий, предыдущий, короткий url)
 */
remove_action('wp_head', 'start_post_rel_link', 10);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
remove_action('wp_head', 'wp_shortlink_wp_head', 10);

/**
 * `Disable Emojis` Plugin Version: 1.7.2
 */
if ('Отключаем Emojis в WordPress') {

    /**
     * Disable the emoji's
     */
    function disable_emojis()
    {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
        add_filter('wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2);
    }

    add_action('init', 'disable_emojis');

    /**
     * Filter function used to remove the tinymce emoji plugin.
     *
     * @param array $plugins
     * @return   array             Difference betwen the two arrays
     */
    function disable_emojis_tinymce($plugins)
    {
        if (is_array($plugins)) {
            return array_diff($plugins, array('wpemoji'));
        }

        return array();
    }

    /**
     * Remove emoji CDN hostname from DNS prefetching hints.
     *
     * @param array $urls URLs to print for resource hints.
     * @param string $relation_type The relation type the URLs are printed for.
     * @return array                 Difference betwen the two arrays.
     */
    function disable_emojis_remove_dns_prefetch($urls, $relation_type)
    {

        if ('dns-prefetch' == $relation_type) {

            // Strip out any URLs referencing the WordPress.org emoji location
            $emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
            foreach ($urls as $key => $url) {
                if (strpos($url, $emoji_svg_url_bit) !== false) {
                    unset($urls[$key]);
                }
            }

        }

        return $urls;
    }

}

/**
 * Удаляем стили для recentcomments из header'а
 */
function remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}

add_action('widgets_init', 'remove_recent_comments_style');

/**
 * Удаляем ссылку на xmlrpc.php из header'а
 */
remove_action('wp_head', 'wp_bootstrap_starter_pingback_header');

/**
 * Удаляем стили для #page-sub-header из  header'а
 */
remove_action('wp_head', 'wp_bootstrap_starter_customizer_css');

/*
*Обновление количества товара
*/
add_filter('woocommerce_add_to_cart_fragments', 'header_add_to_cart_fragment');

function header_add_to_cart_fragment($fragments)
{
    global $woocommerce;
    ob_start();
    ?>
    <span class="basket-btn__counter"><?php echo sprintf($woocommerce->cart->cart_contents_count); ?></span>
    <?php
    $fragments['.basket-btn__counter'] = ob_get_clean();
    return $fragments;
}

/**
 * Замена надписи на кнопке Добавить в корзину
 */
add_filter('woocommerce_product_single_add_to_cart_text', 'woocust_change_label_button_add_to_cart_single');
function woocust_change_label_button_add_to_cart_single($label)
{

    $label = 'Добавить в корзину';

    return $label;
}

/**
 * Удаляем поля адрес и телефон, если нет доставки
 */

add_filter('woocommerce_checkout_fields', 'new_woocommerce_checkout_fields', 10, 1);

function new_woocommerce_checkout_fields($fields)
{
    unset($fields['billing']['billing_address_2']); //удаляем Населённый пункт
    unset($fields['billing']['billing_address_1']); //удаляем Населённый пункт
    unset($fields['billing']['billing_city']); //удаляем Населённый пункт
    unset($fields['billing']['billing_postcode']); //удаляем Населённый пункт
    unset($fields['billing']['billing_country']); //удаляем Населённый пункт
    unset($fields['billing']['billing_state']); //удаляем Населённый пункт
    unset($fields['billing']['billing_company']); //удаляем Населённый пункт
    unset($fields['billing']['billing_last_name']); //удаляем Населённый пункт
    unset($fields['billing']['billing_phone']); //удаляем Населённый пункт
    unset($fields['order']['order_comments']); //удаляем Населённый пункт
    return $fields;
}

add_filter('woocommerce_cart_needs_shipping_address', '__return_false');

add_filter('woocommerce_enable_order_notes_field', '__return_false');

//Замена placeholder
add_filter('woocommerce_default_address_fields', 'override_default_address_checkout_fields', 20, 1);
function override_default_address_checkout_fields($address_fields)
{
    $address_fields['first_name']['placeholder'] = 'Как к вам обращаться?';
    $address_fields['address_1']['placeholder'] = 'Где вы проживаете?';
    $address_fields['postcode']['placeholder'] = 'Postnummer';
    return $address_fields;
}

add_filter('woocommerce_checkout_fields', 'override_billing_checkout_fields', 20, 1);
function override_billing_checkout_fields($fields)
{
    $fields['billing']['billing_email']['placeholder'] = 'Укажите Email';
    return $fields;
}

add_filter('woocommerce_billing_fields', 'ts_unrequire_wc_phone_field');
function ts_unrequire_wc_phone_field($fields)
{
    $fields['billing_phone']['required'] = true;
    return $fields;
}


remove_action('storefront_footer', 'storefront_credit', 20);

/**
 * Remove product data tabs
 */
add_filter('woocommerce_product_tabs', 'woo_remove_product_tabs', 98);

function woo_remove_product_tabs($tabs)
{

    unset($tabs['description']);        // Remove the description tab
    unset($tabs['reviews']);            // Remove the reviews tab
    unset($tabs['additional_information']);    // Remove the additional information tab

    return $tabs;
}

//Количество товаров для вывода на странице магазина
add_filter('loop_shop_per_page', 'wg_view_all_products');

function wg_view_all_products()
{
    return '9999';
}

//Удаление сортировки
add_action('init', 'bbloomer_delay_remove');

function bbloomer_delay_remove()
{
    remove_action('woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    remove_action('woocommerce_after_shop_loop', 'woocommerce_result_count', 20);

}

/*
*Изменение количетсва товара на строку на страницах woo
*/
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
    function loop_columns()
    {
        return 4; // 3 products per row
    }
}


//Удаление сторфронт-кредит
add_action('init', 'custom_remove_footer_credit', 10);

function custom_remove_footer_credit()
{
    remove_action('storefront_footer', 'storefront_credit', 20);
}


//Добавление favicon
function favicon_link()
{
    echo '<link rel="icon" type="image/x-icon" href="https://marina-eldenbert.ru/favicon.ico" />' . "\n";
    echo '<link rel="shortcut icon" type="image/x-icon" href="https://marina-eldenbert.ru/favicon.ico" />' . "\n";
}

add_action('wp_head', 'favicon_link');

//Изменение entry-content
function storefront_page_content()
{
    ?>
    <div class="row">
        <?php the_content(); ?>
        <?php
        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . __('Pages:', 'storefront'),
                'after' => '</div>',
            )
        );
        ?>
    </div>
    <?php
}

add_filter('woocommerce_sale_flash', 'my_custom_sale_flash', 10, 3);
function my_custom_sale_flash($text, $post, $_product)
{
    return '<span class="onsale">SALE!</span>';
}

// Колонки related
add_filter('woocommerce_output_related_products_args', 'jk_related_products_args', 30);
function jk_related_products_args($args)
{
    $args['posts_per_page'] = 6; // количество "Похожих товаров"
    $args['columns'] = 4; // количество колонок
    return $args;
}


/**
 * Выводим содержимое записи - часть книги (главу)
 */
function article_content($articleId)
{
    global $post;
    $baseUrl = get_permalink();
    $prevArticleId = nearestArticleId($articleId, 'prev');
    $nextArticleId = nearestArticleId($articleId, 'next');

    $query = new WP_Query('p=' . $articleId);
    $bookId = get_post_meta($post->ID, 'book_id', true);
    if ($query->have_posts()) {

        while ($query->have_posts()) {
            $query->the_post();
            $tags = get_the_tags();
            $isFree = false;
            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    if ($tag->slug == 'free-article') {
                        $isFree = true;
                    }
                }
            }

            if (!$isFree && !isBookBought($bookId) && !(is_user_logged_in() && hasAbonement(get_current_user_id())) && !isAdmin()) {
                $GLOBALS['showBuyScreen'] = true;
                wp_reset_query();
                return;
            }
            setBookmarkMeta($bookId, $articleId);

            global $numpages;
            $pageToLoad = 1;
            $lastPage = getBookmarkPageMeta($articleId);
            $lastArticle = getBookmarkMeta($bookId);
            if ($lastArticle && $lastPage) {
                if ($lastArticle != $articleId) {
                    $pageToLoad = 1;
                } else {
                    $pageToLoad = intval($lastPage);
                }
            }
            if (!$lastPage && isset($_COOKIE['a_' . $articleId])) {
                $pageToLoad = intval($_COOKIE['a_' . $articleId]);
            }

            if ($pageToLoad > $numpages || $pageToLoad < 1) {
                $pageToLoad = 1;
            }
            $GLOBALS['page'] = $pageToLoad;
            ?>
            <p class="h3 reader-h3"><?php the_title(); ?></p>
            <?php
            echo '<ul class="article-btns pagination mb-3 mt-3 pb-0">';
            echo '</ul>';
            wp_custom_link_pages(array(
                'before' => '<nav><ul class="pagination mb-4 mt-3 pb-0" data-pages="' . $numpages . '">',
                'after' => '</ul></nav>',
                'link_before' => '<span>',
                'link_after' => '</span>',
                'prev_article_id' => $prevArticleId,
                'next_article_id' => $nextArticleId,
                'base_url' => $baseUrl,
            ));
            ?>
            <div id="articleText">
                <?php the_content(); ?>
            </div>
            <div id="articleSpinner">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <?php
            wp_custom_link_pages(array(
                'before' => '<nav><ul class="pagination mb-3 mt-4 pb-0" data-pages="' . $numpages . '">',
                'after' => '</ul></nav>',
                'link_before' => '<span>',
                'link_after' => '</span>',
                'prev_article_id' => $prevArticleId,
                'next_article_id' => $nextArticleId,
                'base_url' => $baseUrl,
            ));
            echo '<ul class="article-btns pagination mt-3 pb-0">';
            echo '</ul>';
            ?>
            <?php
        }
    }
    wp_reset_query();
}

/**
 * Собственная функция вывода пагинации
 * @param string $args
 * @return mixed|void
 */
function wp_custom_link_pages($args = '')
{
    global $page, $numpages, $multipage, $more;

    $defaults = array(
        'before' => '<p class="post-nav-links">' . __('Pages:'),
        'after' => '</p>',
        'link_before' => '',
        'link_after' => '',
        'aria_current' => 'page',
        'next_or_number' => 'number',
        'separator' => ' ',
        'nextpagelink' => __('Next page'),
        'previouspagelink' => __('Previous page'),
        'pagelink' => '%',
        'echo' => 1,
    );

    $params = wp_parse_args($args, $defaults);

    /**
     * Filters the arguments used in retrieving page links for paginated posts.
     *
     * @param array $params An array of arguments for page links for paginated posts.
     * @since 3.0.0
     *
     */
    $r = apply_filters('wp_link_pages_args', $params);

    $output = '';
    $prevPageText = '&laquo;';
    $nextPageText = '&raquo;';
    $prevArticleText = 'Пред. часть';
    $nextArticleText = 'След. часть';
    $multipage = 1; // Считаем все записи мультистраничными для отображения сслыок на соседние главы
    if ($multipage) {
        if ('number' == $r['next_or_number']) {
            $output .= $r['before'];
            if ($params['prev_article_id']) {
                $prevArticleId = $params['prev_article_id'];
            } else {
                $prevArticleId = 0;
            }
            $prevPageClass = '';
            if ($page == 1) {
                $prevText = $prevArticleText;
                if ($prevArticleId == 0) {
                    $prevPageClass .= ' d-none';
                }
            } else {
                $prevText = $prevPageText;
            }

            $output .= '<li class="page-item' . $prevPageClass . '">
                    <a data-link="' . $params['base_url'] . '?a=' . $prevArticleId . '" data-article-id="' . $prevArticleId . '" data-for-page="' . $prevPageText . '" data-for-article="' . $prevArticleText . '" class="page-link prev-page-btn" aria-label="Previous">
                        <span aria-hidden="true">' . $prevText . '</span>
                    </a>
                </li>';

            for ($i = 1; $i <= $numpages; $i++) {

                $firstDots = '';
                $lastDots = '';
                $firstDotsClass = ($page < 3) ? ' d-none' : '';
                $lastDotsClass = ($page > $numpages - 2) ? ' d-none' : '';
                if ($i == 1) {
                    $firstDots = '<li><div class="dots first-dots' . $firstDotsClass . '">...</div></li>';
                }
                if ($i == $numpages) {
                    $lastDots = '<li><div class="dots last-dots' . $lastDotsClass . '">...</div></li>';
                }

                $activeClass = ($i == $page) ? ' active' : '';
                $nonVisibleClass = ($i != $page && $i != $page - 1 && $i != $page + 1 && $i != 1 && $i != $numpages) ? ' d-none' : '';
                if ($numpages > 1) {
                    $link = '<li class="page-item' . $activeClass . $nonVisibleClass . '"><a class="post-page-numbers page-link" data-page="' . $i . '">' . $i . '</a></li>';
                    $output .= $lastDots;
                    $output .= $link;
                    $output .= $firstDots;
                }

            }

            if ($params['next_article_id']) {
                $nextArticleId = $params['next_article_id'];
            } else {
                $nextArticleId = 0;
            }
            $nextPageClass = '';
            if ($page == $numpages) {
                $nextText = $nextArticleText;
                if ($nextArticleId == 0) {
                    $nextPageClass .= ' d-none';
                }
            } else {
                $nextText = $nextPageText;
            }
            $output .= '<li class="page-item' . $nextPageClass . '">
                <a data-link="' . $params['base_url'] . '?a=' . $nextArticleId . '" data-article-id="' . $nextArticleId . '" data-for-page="' . $nextPageText . '" data-for-article="' . $nextArticleText . '" class="page-link next-page-btn" aria-label="Next">
                    <span aria-hidden="true">' . $nextText . '</span>
                </a>
            </li>';

            $output .= $r['after'];
        }
    }

    /**
     * Filters the HTML output of page links for paginated posts.
     *
     * @param string $output HTML output of paginated posts' page links.
     * @param array $args An array of arguments.
     * @since 3.6.0
     *
     */
    $html = apply_filters('wp_link_pages', $output, $args);

    if ($r['echo']) {
        echo $html;
    }
    return $html;
}

/**
 * Добавляем скрипт счетчика уведомлений
 */
if (is_user_logged_in()) {
    wp_enqueue_script('notification-script', get_stylesheet_directory_uri() . '/inc/assets/js/notifications.js', array('jquery'));
}
/**
 * Добавляем ajax-обработчик счетчика уведомлений
 */
if (is_user_logged_in()) {
    add_action('wp_ajax_update_notification', 'notificationAjax');
}
/**
 * ajax-обработчик вывода страниц главы
 */
function notificationAjax()
{
    echo countNewNotifications();
    wp_die();
}

/**
 * Добавляем скрипт пагинации
 */
wp_enqueue_script('pagination-script', get_stylesheet_directory_uri() . '/inc/assets/js/pagination.js', array('jquery'));

add_action('wp_enqueue_scripts', 'myajax_data', 99);
function myajax_data()
{
    $articleId = 0;
    if (isset($_GET['a'])) {
        $articleId = intval($_GET['a']);
    }
    // Первый параметр означает, что код будет прикреплен к скрипту с таким ID
    // Этот ID должен быть добавлен в очередь на вывод, иначе WP не поймет куда вставлять код локализации
    // Заметка: обычно этот код нужно добавлять в functions.php в том месте где подключаются скрипты, после указанного скрипта
    wp_localize_script('pagination-script', 'myajax',
        array(
            'articleId' => intval($articleId),
            'url' => admin_url('admin-ajax.php')
        )
    );
}

/**
 * Добавляем ajax-обработчик вывода страниц главы
 */
add_action('wp_ajax_custom_pagination', 'custom_pagination');
add_action('wp_ajax_nopriv_custom_pagination', 'custom_pagination');

/**
 * ajax-обработчик вывода страниц главы
 */
function custom_pagination()
{
    $pageNumber = intval($_POST['page']);
    $articleId = intval($_POST['article']);
    $query = new WP_Query('p=' . $articleId);
    $content = '';
    if ($query->have_posts()) {

        while ($query->have_posts()) {
            $query->the_post();
            $GLOBALS['page'] = $pageNumber;
            ob_start();
            the_content();
            $content = ob_get_clean();
        }
    }
    setcookie('a_' . $articleId, $pageNumber, strtotime('+1 year'), '/');
    setBookmarkPageMeta($articleId, $pageNumber);
    echo $content;
    wp_die();
}

/**
 * Выводит модальное окно подтверждения возраста,
 * если пользователь не авторизован и возраст еще не был подтвержден
 */
function adultModal()
{
    global $post;
    $bookId = get_post_meta($post->ID, 'book_id', true);
    if ($bookId) {
        $isBookForAdult = has_term('adult-18', 'product_tag', $bookId);
    } else {
        $isBookForAdult = false;
    }

    $isForAdult = has_term('adult-18', 'post_tag') || (is_product() && has_term('adult-18', 'product_tag')) || $isBookForAdult;
    if (!$isForAdult || is_user_logged_in() || (isset($_COOKIE['adult']) && $_COOKIE['adult'] == 1)) {
        return;
    }
    ?>
    <!--noindex-->
    <div class="modal fade" id="disclaimerModal" tabindex="-1" role="dialog" aria-labelledby="disclaimerModalLabel"
         data-keyboard="false" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 text-center">
                                <p class="disclaimerModal__header">Проверка возраста</p>
                                <div class="row">
                                    <div class="col-lg-8 col-12 offset-lg-2 offset-0">
                                        <p class="disclaimerModal__text">Содержимое раздела предназначено для
                                            просмотра лицами старше 18 лет.</p>
                                    </div>
                                </div>
                                <p class="disclaimerModal__que">Вам уже есть 18 лет?</p>
                                <div class="disclaimerModal__agree" id="adultYes">Да</div>
                                <div class="disclaimerModal__disagree" id="adultNo">Нет</div>
                                <p class="disclaimerModal__disclaimer">Сайт содержит информацию для лиц
                                    совершеннолетнего возраста.<br>
                                    Нажимая кнопку "Да", вы даёте cогласие на обработку персональных данных</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/noindex-->
    <script>jQuery(function ($) {
            $('#disclaimerModal').modal('show');
            $('#adultYes').on('click', function () {
                var date = new Date();
                date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
                document.cookie = "adult=1; expires=" + date.toUTCString() + "; path=/";
                $('#disclaimerModal').modal('hide');
            });
            $('#adultNo').on('click', function () {
                window.location.href = '/';
            });
        })</script>
    <?php
}

/**
 * Проверяет, куплен ли пользователем товар(книга)
 * @param $bookId id товара (книги)
 * @return bool true если куплена, false если нет или если пользователь не залогинен
 */
function isBookBought($bookId)
{
    if (is_user_logged_in() && $bookId != '') {
        $current_user = wp_get_current_user();
        return wc_customer_bought_product($current_user->user_email, $current_user->ID, intval($bookId));
    }
    return false;
}

/**
 * Выводит содержание книги
 * @param $isArticle
 */
function contentList($isArticle)
{
    global $post;
    $baseUrl = get_permalink();
    $bookId = get_post_meta($post->ID, 'book_id', true);

    $currentArticle = 0;
    if (isset($_GET['a']) && intval($_GET['a'] > 0)) {
        $currentArticle = intval($_GET['a']);
    }
    $query = new WP_Query(array(
        'cat' => get_post_meta($post->ID, 'cat_id', true),
        'order' => 'asc',
        'orderby' => 'date',
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ));

    if ($query->have_posts()) {
        echo '<hr>';
        if ($isArticle) {
            echo '<p><a href="' . $baseUrl . '">О книге</a></p>';
        } else {
            echo '<p>О книге</p>';
        }
        while ($query->have_posts()) {
            $query->the_post();

            $tags = get_the_tags();
            $isFree = false;
            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    if ($tag->slug == 'free-article') {
                        $isFree = true;
                    }
                }
            }


            $buyLinkClass = ' buy-link';
            if ($isFree || isBookBought($bookId) || hasAbonement(get_current_user_id()) || isAdmin()) {
                $buyLinkClass = '';
            }
            if ($currentArticle > 0 && $currentArticle == $post->ID) {
                echo '<p class="active-title">' . $post->post_title . '</p>';

            } else {
                echo '<p><a class="' . $buyLinkClass . '" href="' . $baseUrl . '?a=' . $post->ID . '">' . $post->post_title . '</a></p>';
            }
        }
        echo '<hr>';
    }
    wp_reset_query();
}

/**
 * Выводит карточку товара для читалки
 */
function bookCardInReader()
{
    global $post;
    $bookId = get_post_meta($post->ID, 'book_id', true);

    $product = wc_get_product($bookId);
    ?>
    <div class="text-center"><img src="<?php echo wp_get_attachment_url($product->get_image_id()); ?>"/></div>
    <div class="text-center"><p class="h3"><?php echo $product->get_name() ?></p>
        <?php
        $user_id = get_current_user_id();
        $downloads = wc_get_customer_available_downloads($user_id);
        $hasDownloads = false;
        if (!empty($downloads)) {
            foreach ($downloads as $download) {
                if ($download['product_id'] == $bookId) { ?>
                    <div>
                        <a class=" mb-3" href="<?php $download['download_url'] ?>">Скачать в
                            формате <?php echo $download['file']['name'] ?></a>
                    </div>
                    <?php
                    $hasDownloads = true;
                }
            }
        }

        if (!$hasDownloads && $product->get_status() == 'publish') {
            $buyButtonText = 'Купить';
            if ($product->get_price() == 0) {
                $buyButtonText = 'Подробнее';
            } ?>
            <a href="<?php echo $product->get_permalink(); ?>"><?php echo $buyButtonText; ?></a>
            <?php
        } elseif (!$hasDownloads && $product->get_status() == 'pending') { ?>
            <p>Книга еще не вышла</p>
            <?php
        } ?>
    </div>
    <?php
}

if (!function_exists("array_key_last")) {
    function array_key_last($array)
    {
        if (!is_array($array) || empty($array)) {
            return NULL;
        }

        return array_keys($array)[count($array) - 1];
    }
}

/**
 * Дополняем хлебные крошки в читалке названием главы
 */
add_filter('woocommerce_get_breadcrumb', function ($args) {
    if (isset($_GET['a'])) {
        $title = get_the_title(intval($_GET['a']));
        if ($title != '') {
            $lastUrl = $args[array_key_last($args)][1];
            $args[] = [$title, $lastUrl . '?a=' . intval($_GET['a'])];
        }
    }
    return $args;
});

/**
 * Делаем редирект после добавления комментария на страницу конкретной главы,
 * где был добавлен комментарий вместо основной страницы книги
 */
add_filter('comment_post_redirect', function ($url) {
    $urlParts = preg_split('~#~', $url, 2);
    $newUrl = $_SERVER['HTTP_REFERER'] . '#' . $urlParts[1];
    return $newUrl;
});

/**
 * Добавляет кнопку со ссылкой на первую главу книги
 */
function readButton()
{
    global $post;
    $bookId = get_post_meta($post->ID, 'book_id', true);

    $baseUrl = get_permalink();
    $lastBookmark = getBookmarkMeta($bookId);
    if ($lastBookmark) {
        echo '<a class="club-header__btn" href="' . $baseUrl . '?a=' . $lastBookmark . '">Продолжить чтение</a>';
        return;
    } elseif (isset($_COOKIE['b_' . $bookId])) {
        echo '<a class="club-header__btn" href="' . $baseUrl . '?a=' . $_COOKIE['b_' . $bookId] . '">Продолжить чтение</a>';
        return;
    }

    $query = new WP_Query(array(
        'cat' => get_post_meta($post->ID, 'cat_id', true),
        'order' => 'asc',
        'orderby' => 'date',
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 1,
    ));

    if ($query->have_posts()) {
        echo '<hr>';
        while ($query->have_posts()) {
            $query->the_post();
            echo '<a class="load-more" href="' . $baseUrl . '?a=' . $post->ID . '">Читать</a>';
        }
    }
    wp_reset_query();
}

/* Add Next Page Button in First Row */
add_filter('mce_buttons', 'my_add_next_page_button', 1, 2); // 1st row

/**
 * Добавляем в визуальный редактор кнопки вставки тега nextpage - разрыв страницы, выравнивания по ширине и подчеркивания
 *
 */
function my_add_next_page_button($buttons, $id)
{

    /* only add this for content editor */
    if ('content' != $id)
        return $buttons;

    /* add next page after more tag button */
    array_splice($buttons, 13, 0, 'wp_page');
    array_splice($buttons, 9, 0, 'alignjustify');
    array_splice($buttons, 2, 0, 'underline');

    return $buttons;
}

/**
 * Замена стандартных крошек от вукомерса, крошки по центру для страницы about
 */
add_filter('woocommerce_breadcrumb_defaults', 'jk_woocommerce_breadcrumbs', 20);
function jk_woocommerce_breadcrumbs()
{
    global $post;
    if (is_account_page()) {
        return array(
            'delimiter' => ' / ',
            'wrap_before' => '<nav class="woocommerce-breadcrumb d-none container text-center breadcrumb-container" itemprop="breadcrumb">',
            'wrap_after' => '</nav>',
            'before' => '',
            'after' => '',
            'home' => _x('Home', 'breadcrumb', 'woocommerce'),
        );
    }

    if ($post->ID == 39) {
        return array(
            'delimiter' => ' / ',
            'wrap_before' => '<nav class="woocommerce-breadcrumb container text-center breadcrumb-container" itemprop="breadcrumb">',
            'wrap_after' => '</nav>',
            'before' => '',
            'after' => '',
            'home' => _x('Home', 'breadcrumb', 'woocommerce'),
        );
    } else {
        return array(
            'delimiter' => ' / ',
            'wrap_before' => '<nav class="woocommerce-breadcrumb container breadcrumb-container" itemprop="breadcrumb">',
            'wrap_after' => '</nav>',
            'before' => '',
            'after' => '',
            'home' => _x('Home', 'breadcrumb', 'woocommerce'),
        );
    }
}

remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

/**
 * Удаляем галерею товара
 */
remove_action('woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20);

//Убираем зум на фото продукта
function remove_image_zoom_support()
{
    remove_theme_support('wc-product-gallery-zoom');
}

add_action('wp', 'remove_image_zoom_support', 100);

//удаляем количество на странице продукта
function custom_remove_all_quantity_fields($return, $product)
{
    return true;
}

add_filter('woocommerce_is_sold_individually', 'custom_remove_all_quantity_fields', 10, 2);

/**
 * Вывод атрибутов на странице товара
 */

// Функция вывода атрибута
function productAuthor()
{
    global $product;
    // Получаем атрибуты товара
    $attributes = $product->get_attributes();
    // Если атрибута Автор нет, или он не должен выводиться на странице товара, то выходим
    if (!key_exists('pa_author-book', $attributes) || !$attributes['pa_author-book']->get_visible()) {
        return;
    }
    $attribute_names = get_the_terms($product->get_id(), 'pa_author-book');
    if ($attribute_names) {
        if (count($attribute_names) > 1) {
            $attribute_name = "Авторы: ";
        } else {
            $attribute_name = "Автор: ";
        }
// Вывод имени атрибута
        echo '<p class="attr-label">';
        echo wc_attribute_label($attribute_name);

// Выборка значения заданного атрибута
        foreach ($attribute_names as $attribute_name):
// Вывод значений атрибута
            echo '<span>';
            echo $attribute_name->name;
            echo '</span> ';
        endforeach;
        echo '</p>';
    }
}

// Определяем место вывода атрибута
//add_action('woocommerce_single_product_summary', 'productAuthor', 15);

// Функция вывода атрибута
function productSeries()
{
    global $product;
// Получаем элементы таксономии атрибута цикл
    $attribute_names = get_the_terms($product->get_id(), 'pa_cycle-book');
    $attribute_name = "Цикл: ";
    if ($attribute_names) {
// Вывод имени атрибута
        echo '<p class="attr-label">';
        echo wc_attribute_label($attribute_name);

// Выборка значения заданного атрибута
        foreach ($attribute_names as $attribute_name):
// Вывод значений атрибута
            echo '<a href="/shop/?filter=cycle-' . $attribute_name->slug . '">';
            echo $attribute_name->name;
            echo '</a>';
            echo '</p>';
            break;
        endforeach;
    }
    // Получаем элементы таксономии атрибута серия
    $attribute_names = get_the_terms($product->get_id(), 'pa_series-book');
    $attribute_name = "Серия: ";
    if ($attribute_names) {
// Вывод имени атрибута
        echo '<p class="attr-label">';
        echo wc_attribute_label($attribute_name);

// Выборка значения заданного атрибута
        foreach ($attribute_names as $attribute_name):
// Вывод значений атрибута
            echo $attribute_name->name;
            echo '</p>';
            break;
        endforeach;
    }
}

// Определяем место вывода атрибута
//add_action('woocommerce_single_product_summary', 'productSeries', 15);

//Удаляем цену
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);

/**
 * Добавляем ссылки на магазины с бумажными книгами
 */
add_action('woocommerce_after_add_to_cart_form', 'add_links_to_book_stores');

function add_links_to_book_stores()
{
    global $product;
    $links = get_post_custom_values('buy_paper_book', $product->get_id());
    if (!is_array($links)) {
        return;
    }
    foreach ($links as $link) {
        $link_parts = preg_split('~\(:\)~', $link, 2);
        echo '<p class="book-store-link"><a href="' . $link_parts[1] . '" target="_blank">Купить бумажную версию ' . $link_parts[0] . '</a></p>';
    }
}

/**
 * Добавляем ссылки на аудио
 */
add_action('woocommerce_after_add_to_cart_form', 'add_links_to_audiobook_stores');

function add_links_to_audiobook_stores()
{
    global $product;
    $links = get_post_custom_values('buy_audio_book', $product->get_id());
    if (!is_array($links)) {
        return;
    }
    foreach ($links as $link) {
        $link_parts = preg_split('~\(:\)~', $link, 2);
        echo '<p class="book-store-link"><a href="' . $link_parts[1] . '" target="_blank">Купить аудиокнигу ' . $link_parts[0] . '</a></p>';
    }
}

/**
 * Удаляем uncategorized из хлебных крошек
 *
 * @param Array $crumbs Breadcrumb crumbs for WooCommerce breadcrumb.
 * @return Array   WooCommerce Breadcrumb crumbs with default category removed.
 */
function your_prefix_wc_remove_uncategorized_from_breadcrumb($crumbs)
{
    $category = get_option('default_product_cat');
    $category_link = get_category_link($category);

    foreach ($crumbs as $key => $crumb) {
        if (in_array($category_link, $crumb)) {
            unset($crumbs[$key]);
        }
    }
    return array_values($crumbs);
}

add_filter('woocommerce_get_breadcrumb', 'your_prefix_wc_remove_uncategorized_from_breadcrumb');


/**
 * Меняет сслыку на блог/клуб в хлебных крошках записи блога/клуба
 * @param $crumbs
 * @return mixed
 */
function changeBreadcrumbLinkAuthorBlog($crumbs)
{
    $cat = new WPSEO_Primary_Term('category', get_the_ID());
    $cat_id = $cat->get_primary_term();
    $category = get_category($cat_id);

    if ($category->slug == 'author-blog') {
        $link = get_permalink(get_page_by_path('blog'));
        $crumbs[1][1] = $link;
    } elseif ($category->slug == 'club') {
        $link = get_permalink(get_page_by_path('club'));
        $crumbs[1][1] = $link;
    } elseif ($category->slug == 'announcement') {
        $link = get_permalink(get_page_by_path('anonsy-knig'));
        $crumbs[1][1] = $link;
    } elseif ($category->slug == 'news-n-events') {
        $link = get_permalink(get_page_by_path('novosti-i-sobytiya'));
        $crumbs[1][1] = $link;
    }
    return $crumbs;
}

add_filter('woocommerce_get_breadcrumb', 'changeBreadcrumbLinkAuthorBlog');

add_action('init', 'jk_remove_storefront_handheld_footer_bar');

function jk_remove_storefront_handheld_footer_bar()
{
    remove_action('storefront_footer', 'storefront_handheld_footer_bar', 999);
}

/**
 * Проверяет, является ли пользователь админом
 * @return bool
 */
function isAdmin()
{
    if (current_user_can('manage_options')) {
        return true;
    }
    return false;
}

add_action('template_redirect', 'setBookmarkCookies', 10);
/**
 * Добавляет в куки запись с id открытой книги и id открытой главы в книге
 */
function setBookmarkCookies()
{
    $post_id = get_the_ID();
    if (get_page_template_slug() == 'template-parts/book-reader.php') {
        $bookId = get_post_meta($post_id, 'book_id', true);
        $bookCategoryId = get_post_meta($post_id, 'cat_id', true);
        $articleId = intval($_GET['a']);
        $query = new WP_Query('p=' . $articleId);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $articleCategories = wp_get_post_categories($articleId);
                $tags = get_the_tags();
                $isFree = false;
                if (is_array($tags)) {
                    foreach ($tags as $tag) {
                        if ($tag->slug == 'free-article') {
                            $isFree = true;
                        }
                    }
                }
                if (in_array($bookCategoryId, $articleCategories) && $isFree) {
                    setcookie('b_' . $bookId, $articleId, strtotime('+1 year'), '/');
                }
            }
        }
        wp_reset_query();
    }
}

/**
 * Устанавливает для пользователя последнюю просмотренную главу книги
 * @param $bookId
 * @param $articleId
 */
function setBookmarkMeta($bookId, $articleId)
{
    if (!is_user_logged_in()) {
        return;
    }
    $userId = get_current_user_id();
    update_user_meta($userId, 'b_' . $bookId, $articleId);
}

/**
 * Устанавливает для пользователя последнюю просмотренную страницу главы
 * @param $articleId
 * @param $page
 */
function setBookmarkPageMeta($articleId, $page)
{
    if (!is_user_logged_in()) {
        return;
    }
    $userId = get_current_user_id();
    update_user_meta($userId, 'a_' . $articleId, $page);
}

/**
 * Получает последнюю просмотренную пользователем главу книги
 * @param $bookId
 * @return bool|mixed
 */
function getBookmarkMeta($bookId)
{
    if (!is_user_logged_in()) {
        return false;
    }
    $userId = get_current_user_id();
    $articleId = get_user_meta($userId, 'b_' . $bookId, true);
    if ($articleId == '') {
        return false;
    }
    return $articleId;
}

/**
 * Получает последнюю просмотренную пользователем страницу главы
 * @param $articleId
 * @return bool|mixed
 */
function getBookmarkPageMeta($articleId)
{
    if (!is_user_logged_in()) {
        return false;
    }
    $userId = get_current_user_id();
    $page = get_user_meta($userId, 'a_' . $articleId, true);
    if ($page == '') {
        return false;
    }
    return $page;
}

//Удаление рейтинга
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);


//удаление чекбокса в комментах на запоминаение
remove_action('set_comment_cookies', 'wp_set_comment_cookies');

add_action('wp_enqueue_scripts', 'addIsotopeScript', 10);
/**
 * Добавляет скрипты изотопа и работы с изотопом
 */
function addIsotopeScript()
{
    if (is_shop()) {
        wp_enqueue_script('isotope', get_stylesheet_directory_uri() . '/inc/assets/js/isotope.pkgd.min.js', array('jquery'));
        wp_enqueue_script('filter-script', get_stylesheet_directory_uri() . '/inc/assets/js/filter.js', array('jquery', 'isotope'));
        wp_enqueue_script('imagesloaded', get_bloginfo('stylesheet_directory') . '/inc/assets/js/imagesloaded.pkgd.min.js', array('jquery'), false, true);
    } else if (get_the_ID() == 35) { // ID страницы Иллюстрации
        wp_enqueue_script('isotope', get_stylesheet_directory_uri() . '/inc/assets/js/isotope.pkgd.min.js', array('jquery'));
        wp_enqueue_script('filter-images-script', get_stylesheet_directory_uri() . '/inc/assets/js/filter-images.js', array('jquery', 'isotope'));
        wp_enqueue_script('imagesloaded', get_bloginfo('stylesheet_directory') . '/inc/assets/js/imagesloaded.pkgd.min.js', array('jquery'), false, true);
        wp_enqueue_script('fancybox', get_stylesheet_directory_uri() . '/inc/assets/js/fancybox-custom.js', array('jquery'));
    }
}

/**
 * Выводит фильтр товаров
 */
function addFilterBar()
{
    $bookTypeFilters = [

    ];
    $otherFilters = [
        'free-books',
        'new',
        'bestseller',
        'subscription'
    ];

    $tags = get_terms('product_tag');
    $series = get_terms('pa_series-book');
    $cycles = get_terms('pa_cycle-book');
    $nonEmptyTags = [];
    foreach ($tags as $tag) {
        $nonEmptyTags[$tag->slug] = $tag->name;
    }
    ?>
    <aside id="secondary" class="widget-area col-sm-12 col-lg-3 mb-5" role="complementary">
        <div class="filter-collapse-btn d-lg-none d-block" data-toggle="collapse" data-target="#collapseFilter"
             aria-expanded="false" aria-controls="collapseFilter">
            Фильтры
        </div>
        <div class="collapse d-lg-block" id="collapseFilter">
            <button class="button clear-filters wow fadeInUp animated"
                    data-wow-delay="0.6s" data-filter="*"><i class="fas fa-times mr-2"></i> Сбросить фильтры
            </button>
            <div class="filter-button-group wow fadeInUp animated"
                 data-wow-delay="0s">
                <?php if (count($bookTypeFilters) > 0) : ?>
                    <div class="button-group mb-5" data-filter-group="type">
                        <?php foreach ($bookTypeFilters as $filter): ?>
                            <?php if (!key_exists($filter, $nonEmptyTags)) {
                                continue;
                            } ?>
                            <button class="button filter-btn"
                                    data-filter=".product_tag-<?php echo $filter ?>"><?php echo $nonEmptyTags[$filter] ?></button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="button-group mb-5 wow fadeInUp animated"
                     data-wow-delay="0.2s" data-filter-group="other">
                    <?php foreach ($otherFilters as $filter): ?>
                        <?php if (!key_exists($filter, $nonEmptyTags)) {
                            continue;
                        } ?>
                        <button class="button filter-btn"
                                data-filter=".product_tag-<?php echo $filter ?>"><?php echo $nonEmptyTags[$filter] ?></button>
                    <?php endforeach; ?>
                </div>
                <div class="button-group mb-5 wow fadeInUp animated"
                     data-wow-delay="0.4s" data-filter-group="cycles">
                    <?php foreach ($cycles as $cycle): ?>
                        <button class="button filter-btn"
                                data-filter=".cycle-<?php echo $cycle->slug ?>"><?php echo $cycle->name ?></button>
                    <?php endforeach; ?>
                    <button class="button filter-btn"
                            data-filter=".cycle-no-cycle">Книги вне циклов
                    </button>
                </div>
            </div>
        </div>
    </aside>
    <?php
}

/**
 * Добавляем к инпуту почты подпись для незалогиненных пользователей
 */
add_filter('woocommerce_form_field', function ($form) {
    if (!is_user_logged_in() && preg_match('~name="billing_email"~', $form)) {
        return $form . '<p class="text-center small">На этот адрес будет отправлен пароль для входа в личный кабинет</p>';
    }
    return $form;
});

add_filter('woocommerce_show_page_title', '__return_null');

add_action('woocommerce_before_shop_loop_item_title', 'my_theme_wrapper_start', 9);
add_action('woocommerce_before_shop_loop_item_title', 'my_theme_wrapper_end', 10);

function my_theme_wrapper_start()
{
    echo '<div class="before-block">';

    global $product;
    $tags = wc_get_object_terms($product->get_id(), 'product_tag');
    $tagSlugList = [];
    foreach ($tags as $tag) {
        $tagSlugList[] = $tag->slug;
    }

    if (in_array('bestseller', $tagSlugList)) {
        echo '<div class="product-label-container">';
        echo '<span class="product-label product-label__bestseller">Бестселлер</span>';
        echo '</div>';
    } elseif (in_array('new', $tagSlugList)) {
        echo '<div class="product-label-container">';
        echo '<span class="product-label product-label__new">Новинка</span>';
        echo '</div>';
    } elseif (in_array('subscription', $tagSlugList)) {
        echo '<div class="product-label-container">';
        echo '<span class="product-label product-label__subscription">Подписка</span>';
        echo '</div>';
    }
}

function my_theme_wrapper_end()
{
    echo '</div>';
}

/**
 * Выводит комментарии
 * @param $comment
 * @param $args
 * @param $depth
 */
function storefront_comment($comment, $args, $depth)
{
    woocommerce_comments($comment, $args, $depth);
}

function woocommerce_comments($comment, $args, $depth)
{
    if ('div' === $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li ';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo esc_attr($tag); ?><?php comment_class(empty($args['has_children']) ? 'comment' : 'parent comment'); ?> id="comment-<?php comment_ID(); ?>">
    <div class="comment-body">
    <div class="comment-meta commentmetadata">
        <div class="comment-author vcard">
            <div class="avatar-status-box position-relative">
                <?php echo get_avatar($comment, 300); ?>
                <?php echo do_shortcode('[mycred_my_rank user_id=' . $comment->user_id . ' show_title=0 show_logo=1 logo_size="rank"]'); ?>
            </div>
            <div class="text-center">
                <?php printf(wp_kses_post('<cite class="comment-body__author fn">%s</cite>', 'storefront'), get_comment_author_link()); ?>
                <cite><?php echo do_shortcode('[custom_my_rank user_id=' . $comment->user_id . ' show_title=1 show_logo=0]'); ?></cite>
            </div>
        </div>
        <?php if ('0' === $comment->comment_approved) : ?>
            <em class="comment-awaiting-moderation"><?php esc_attr_e('Your comment is awaiting moderation.', 'storefront'); ?></em>
            <br/>
        <?php endif; ?>

        <a href="<?php echo esc_url(htmlspecialchars(get_comment_link($comment->comment_ID))); ?>"
           class="comment-date">

        </a>
    </div>
    <?php if ('div' !== $args['style']) : ?>
    <div id="div-comment-<?php comment_ID(); ?>" class="comment-content">
<?php endif; ?>
    <div class="comment-text">
        <div class="comment-container">
            <?php comment_text(); ?>
        </div>
        <div class="d-flex justify-content-between">
            <div class="d-sm-block d-flex">
                <?php wp_ulike_comments(); ?>
                <?php
                comment_reply_link(
                    array_merge(
                        $args, array(
                            'add_below' => $add_below,
                            'depth' => $depth,
                            'max_depth' => 9999,
                        )
                    )
                );
                ?>
            </div>
            <time datetime="<?php echo get_comment_date('c'); ?> "><?php echo renameMonth(get_comment_date()); ?></time>
        </div>
    </div>
    <div class="reply">

        <?php edit_comment_link(__('Edit', 'storefront'), '  ', ''); ?>

    </div>
    </div>
    <?php if ('div' !== $args['style']) : ?>
    </div>
<?php endif; ?>
    <?php
}

/**
 * Добавляем новый размер изображений для статуса
 */
add_image_size('rank', 40, 40);

/**
 * Изменяет дату вида 21.04.2019 на 21 апр 2019
 * @param $date
 */
function renameMonth($date)
{
    $dateParts = preg_split('~\.~', $date);
    $months = [
        '01' => 'янв',
        '02' => 'фев',
        '03' => 'мар',
        '04' => 'апр',
        '05' => 'мая',
        '06' => 'июня',
        '07' => 'июля',
        '08' => 'авг',
        '09' => 'сен',
        '10' => 'окт',
        '11' => 'нояб',
        '12' => 'дек',
    ];
    if (key_exists($dateParts[1], $months)) {
        echo $dateParts[0] . ' ' . $months[$dateParts[1]] . ' ' . $dateParts[2];
    } else {
        echo $date;
    }
}

/**
 * Удаляет символ + в переданной строке
 * @param $string
 * @return string|string[]|null
 */
function removePlusInLikes($string)
{
    $string = preg_replace('~\+~', '', $string);
    return $string;
}

/**
 * Удаляем отображение плюса в блоке количества лайков
 */
add_filter('wp_ulike_respond_for_not_liked_data', 'removePlusInLikes');
add_filter('wp_ulike_respond_for_unliked_data', 'removePlusInLikes');
add_filter('wp_ulike_respond_for_liked_data', 'removePlusInLikes');
add_filter('wp_ulike_count_box_template', 'removePlusInLikes');


remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

/**
 * используем свой cart.js вместо стандартного - в нем добавлено обновление свайпера после аякс-запроса
 */
wp_dequeue_script('wc-cart');
wp_enqueue_script('wc-cart', get_bloginfo('stylesheet_directory') . '/inc/assets/js/cart.js', array('jquery'), false, true);

//Изменение полей комментариев WP
function modify_comment_fields($fields)
{

    $fields = array(
        'author' => '<div class="row mb-5"><div class="col-lg-6 col-12"><label for="author">Автор</label><input class="form-control" id="author" name="author" maxlength="100" type="text"/></div>',
        'email' => '<div class="col-lg-6 col-12"><label for="email">Email</label><input class="form-control" id="email" name="email" maxlength="100" type="text"/></div></div>',
        'url' => ''
    );

    return $fields;

}

add_filter('comment_form_default_fields', 'modify_comment_fields');


function modify_comment_textarea($fields)
{
    global $post;

    if ($post->ID == 35) {
        $fields = '<div class="row mb-5">
                    <div class="col-12">
                        <textarea placeholder="Введите текст сообщения" id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required"></textarea>
                      ' . get_cir_upload_field() . '
                    </div>
                </div>';
        return $fields;
    } else {
        $fields = '<div class="row mb-5">
                    <div class="col-12">
                        <textarea placeholder="Введите текст сообщения" id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required"></textarea>
                    </div>
                </div>';
        return $fields;
    }
}

add_filter('comment_form_field_comment', 'modify_comment_textarea');

wp_enqueue_script('wc-cart', get_bloginfo('stylesheet_directory') . '/inc/assets/js/cart.js', array('jquery'), false, true);

/**
 * Перенаправление с отдельной страницы главы книги в читалку
 */
add_action('wp', 'redirectIfHiddenPage');
function redirectIfHiddenPage()
{
    global $post;
    if (is_admin() || is_search() || $post->post_type != 'post') {
        return;
    }
    $categories = wp_get_post_categories($post->ID);

    if (count($categories) == 0) {
        return;
    }

    foreach ($categories as $category) {
        $bookPageId = getBookPageIdByCategoryId($category);
        if ($bookPageId) {
            $bookLink = get_permalink($bookPageId);
            $articleId = $post->ID;
            wp_redirect($bookLink . '?a=' . $articleId);
        }
    }
}

/**
 * Возвращает id страницы книги по id категории записей (страницы, где в произвольном поле указан cat_id) или false
 * если такой страницы нет
 * @param $categoryId
 * @return bool|int
 */
function getBookPageIdByCategoryId($categoryId)
{
    $query_args = array(
        'meta_key' => 'cat_id',
        'meta_value' => $categoryId,
    );
    $pages = get_pages($query_args);
    if (count($pages) > 0) {
        return $pages[0]->ID;
    }
    return false;
}

/**
 * Добавляем в карточку товара класс "series-{слаг атрибута серия}", если серии нет, то добавляем "series-no-series"
 */
add_filter('post_class', 'addSeriesToClass', 12);
function addSeriesToClass($args)
{
    if (!is_shop()) {
        return $args;
    }
    global $product;
    $seriesTerms = get_the_terms($product->get_id(), 'pa_series-book');
    if (!$seriesTerms) {
        return array_merge($args, ['series-no-series']);
    }
    $series = [];
    foreach ($seriesTerms as $seriesTerm) {
        $series[] = 'series-' . $seriesTerm->slug;
    }
    $result = array_merge($args, $series);
    return $result;
}

/**
 * Добавляем в карточку товара цикл "cycle-{слаг атрибута серия}", если цикла нет, то добавляем "cycle-no-cycle"
 */
add_filter('post_class', 'addCycleToClass', 13);
function addCycleToClass($args)
{
    if (!is_shop()) {
        return $args;
    }
    global $product;
    $cycleTerms = get_the_terms($product->get_id(), 'pa_cycle-book');
    if (!$cycleTerms) {
        return array_merge($args, ['cycle-no-cycle']);
    }
    $cycles = [];
    foreach ($cycleTerms as $cycleTerm) {
        $cycles[] = 'cycle-' . $cycleTerm->slug;
    }
    $result = array_merge($args, $cycles);
    return $result;
}


//Заменя ссылок в комментах и в инпуте коммента
add_filter('comment_form_defaults', function ($fields) {
    $fields['must_log_in'] = sprintf(
        '<p class="must-log-in">
                 Для отправки комментария вам необходимо
                 <a href="%s">авторизоваться</a></p>'
        ,
        get_permalink(wc_get_page_id('myaccount'))
    );
    return $fields;
});

add_filter('login_url', function ($link) {
    $link = get_permalink(wc_get_page_id('myaccount'));
    return $link;
});

/**
 * Задает для стандартного поиска вывод в результатах только товаров
 * @param $query
 * @return mixed
 */
function search_product($query)
{
    if ($query->is_search) {
        $query->set('post_type', 'product');
    }
    return $query;
}

add_action('pre_get_posts', 'search_product');


/**
 * Добавляет скидку в зависимости от статуса пользователя из плагина mycred
 * @param WC_Cart $cart
 */
function rank_discount_total(WC_Cart $cart)
{
    //тип баллов плагина - вибираем по умолчанию
    $ctype = MYCRED_DEFAULT_TYPE_KEY;

    $user_id = mycred_get_user_id('current');
    if ($user_id === false) {
        return;
    }
    $account_object = mycred_get_account($user_id);
    $rank_object = $account_object->balance[$ctype]->rank;
    if ($rank_object === false) {
        return;
    }
    $rankSlug = $account_object->balance[$ctype]->rank->post->post_name;
    $rankDiscount = getRankDiscount($rankSlug);
    if ($rankDiscount != 0) {
        $discount = $cart->subtotal * $rankDiscount / 100;
        // Название ранга
        $rankName = getRankTitle($rank_object, true);
        // Текст, выводимый в корзине
        $feeText = $rankName . ' - скидка ' . $rankDiscount . '%';
        if ($discount != 0) {
            $cart->add_fee($feeText, -$discount);
        }
    }
}

add_action("woocommerce_cart_calculate_fees", "rank_discount_total");

/**
 * Возвращает размер скидки в зависимости от статуса, 0 если переданного статуса не существует
 * @param $rankSlug
 * @return int
 */
function getRankDiscount($rankSlug)
{
    $statusDiscounts = [
        'metal-dragon' => 0,
        'copper-dragon' => 3,
        'bronze-dragon' => 5,
        'silver-dragon' => 10,
        'golden-dragon' => 15,
        'platinum-dragon' => 20,
    ];
    if (key_exists($rankSlug, $statusDiscounts)) {
        return $statusDiscounts[$rankSlug];
    }
    return 0;
}

/** Возвращает html-код с изображением переданного ранга
 * @param $rank_object
 * @param string $logo_size
 * @return mixed
 */
function getRankLogo($rank_object, $logo_size = 'post-thumbnail')
{
    if (!is_user_logged_in()) {
        return;
    }
    $user_id = get_current_user_id();
    $content = '<div class="mycred-my-rank">' . mycred_get_rank_logo($rank_object->post_id, $logo_size) . '</div>';
    return apply_filters('mycred_my_rank', $content, $user_id, $rank_object);
}

/**
 * Возвращает html-код с именем переданного ранга
 * @param $rank_object
 * @return mixed
 */
function getRankTitle($rank_object, $textOnly = false)
{
    if (!is_user_logged_in()) {
        return;
    }
    $user_id = get_current_user_id();
    $userSex = get_user_meta($user_id, 'sex', true);
    $titles = explode(':', $rank_object->title);
    if ($userSex == 'male' && count($titles) > 1) {
        $title = $titles[1];
    } else {
        $title = $titles[0];
    }
    if ($textOnly) {
        return $title;
    }
    $content = '<div class="mycred-my-rank">' . $title . '</div>';
    return apply_filters('mycred_my_rank', $content, $user_id, $rank_object);
}

/**
 * Добавляет мета-поле vipStatus для пользователей, получивших ранг platinum-dragon
 * @param $user_id
 * @param $rank_id
 * @param $results
 * @param $point_type
 */
function giveVipStatus($user_id, $rank_id, $results, $point_type)
{
    $newRank = mycred_get_rank($rank_id);
    if ($newRank->post->post_name == 'platinum-dragon') {
        update_user_meta($user_id, 'vipStatus', 1);
    }
}

add_action('mycred_user_got_promoted', 'giveVipStatus', 10, 4);

/**
 * Добавляем колонку VIP в таблицу пользователей в админке
 * @param $column
 * @return mixed
 */
function addVipStatusColumn($column)
{
    $column['VIP'] = 'VIP';
    return $column;
}

add_filter('manage_users_columns', 'addVipStatusColumn');

/**
 * Заполняем колонку VIP в таблицу пользователей в админке
 * @param $val
 * @param $column_name
 * @param $user_id
 * @return string
 */
function addVipStatusColumnValue($val, $column_name, $user_id)
{
    $abonementUntil = hasAbonement($user_id);
    ob_start();
    if ($column_name == 'VIP') {
        if (get_user_meta($user_id, 'vipStatus', true) == 1) {
            echo '<p>VIP</p>';
        }
        if ($abonementUntil) {
            $date = date('d.m.Y', strtotime($abonementUntil));
            ?>
            <p>Есть абонемент до <?php echo $date ?></p>
            <button type="button" class="btn btn-outline-primary btn-sm addAbonement"
                    data-user-id="<?php echo $user_id ?>"
                    data-abonement-until="<?php echo date('Y-m-d', strtotime($abonementUntil)); ?>">
                Изменить абонемент
            </button>
        <?php } else { ?>
            <button type="button" class="btn btn-outline-primary btn-sm addAbonement"
                    data-user-id="<?php echo $user_id ?>" data-abonement-until="0">
                Подарить абонемент
            </button>
            <?php
        }
        if ($abonementUntil) {

        }
    }
    return ob_get_clean();

}

add_filter('manage_users_custom_column', 'addVipStatusColumnValue', 10, 3);


/**
 * Регистрирует виджет гостевого вип-статуса в консоли админки
 */
function VipStatusWidget()
{
    global $wp_meta_boxes;

    wp_add_dashboard_widget(
        'VipStatusWidget', //Слаг виджета
        'Управление статусами', //Заголовок виджета
        'vipStatusControl' //Функция вывода
    );
}

add_action('wp_dashboard_setup', 'VipStatusWidget');


/**
 * Виджет управления вип-статусами в консоли админки
 */
function vipStatusControl()
{
    $doesNewUserGetVipStatus = get_option('vipForNewUsers', 0);
    $text = get_option('freeAccessText', '');

    if ($doesNewUserGetVipStatus) {
        echo '<p><strong>Сейчас все новые пользователи получают статус Платиновая драконесса</strong></p>';
    } else {
        echo '<p>Включить присвоение статуса Платиновая драконесса всем новым пользователям?</p>';
    }
    ?>
    <form method="post">
        <input type="hidden" name="vipForNewUsers" value="<?php echo ($doesNewUserGetVipStatus) ? 0 : 1 ?>">
        <div><label for="textForClub">Текст на странице Клуба</label>
            <textarea class="w-100" id="textForClub" name="textForClub" rows="5"><?php echo $text ?></textarea>
            <small class="form-text text-muted">Если поместить текст или его часть внутрь тройных скобок, то этот текст
                будет выделен <strong>жирным</strong></small>
            <small class="form-text text-muted">Например, (((Внимание!))) превратится в
                <strong>Внимание!</strong></small>
        </div>
        <button class="button mt-3" type="submit"><?php echo ($doesNewUserGetVipStatus) ? 'Выключить' : 'Включить' ?>
            присвоение статуса
        </button>
    </form>
    <?php
}

/**
 * Изменяет настройку присвоения статуса Платиновая драконесса новым пользователям
 */
function changeNewUserVipStatus()
{
    if (is_admin() && isset($_POST['vipForNewUsers'])) {
        $text = filter_var($_POST['textForClub'], FILTER_SANITIZE_STRING);
        if (intval($_POST['vipForNewUsers']) === 1) {
            update_option('vipForNewUsers', 1);
            update_option('freeAccessText', $text);
        } elseif (intval($_POST['vipForNewUsers']) === 0) {
            update_option('vipForNewUsers', 0);
            update_option('freeAccessText', $text);
        }
        header("Location:" . $_SERVER['PHP_SELF']);
    }
}

add_action('init', 'changeNewUserVipStatus', 10);

/**
 * Добавляем 500 баллов при регистрации, если включена соответствующая настройка
 * @param $user_id
 */
function setVipStatus($user_id)
{
    $doesNewUserGetVipStatus = get_option('vipForNewUsers', 0);
    if ($doesNewUserGetVipStatus) {
        $user_id = $user_id;
        $mycred = mycred();
        $amount = 500;

// Update the balance if the user is not excluded
        if (!$mycred->exclude_user($user_id))
            $mycred->update_users_balance($user_id, $amount);
    }
}

add_action('user_register', 'setVipStatus');

/**
 * Обновляем пол пользователя при изменении профиля в личном кабинете
 * @param $userId
 */
function updateUserSex($userId)
{
    if (isset($_POST['account_sex'])) {
        if ($_POST['account_sex'] == 'male') {
            update_user_meta($userId, 'sex', 'male');

        } elseif ($_POST['account_sex'] == 'female') {
            update_user_meta($userId, 'sex', 'female');
        }
    }
}

add_action('woocommerce_save_account_details', 'updateUserSex');

/**
 * Удаляем имя и фамилию из списка обязательных полей в аккаунте
 * @param $fields
 * @return mixed
 */
function removeRequiredAccountFields($fields)
{
    if (key_exists('account_first_name', $fields)) {
        unset($fields['account_first_name']);
    }
    if (key_exists('account_last_name', $fields)) {
        unset($fields['account_last_name']);
    }
    return $fields;
}

add_filter('woocommerce_save_account_details_required_fields', 'removeRequiredAccountFields');

/**
 * Шорткод на базе mycred_my_rank - отличие в выводе гендерного ранга
 * (разделяет название ранга по двоеточию, если пользователь мужчина возвращается вторая часть, иначе - первая)
 */
function custom_render_my_rank($atts, $content = '')
{

    extract(shortcode_atts(array(
        'user_id' => 'current',
        'ctype' => MYCRED_DEFAULT_TYPE_KEY,
        'show_title' => 1,
        'show_logo' => 0,
        'logo_size' => 'post-thumbnail',
        'first' => 'logo'
    ), $atts, MYCRED_SLUG . '_my_rank'));

    if ($user_id == '' && !is_user_logged_in()) return;

    if (!mycred_point_type_exists($ctype))
        $ctype = MYCRED_DEFAULT_TYPE_KEY;

    $show = array();
    $user_id = mycred_get_user_id($user_id);
    if ($user_id === false) return;

    $account_object = mycred_get_account($user_id);
    $rank_object = $account_object->balance[$ctype]->rank;

    if ($rank_object !== false) {

        if ($show_logo == 1 && $rank_object->has_logo)
            $show[] = mycred_get_rank_logo($rank_object->post_id, $logo_size);

        if ($show_title == 1) {
            $userSex = get_user_meta($user_id, 'sex', true);
            $titles = explode(':', $rank_object->title);
            if ($userSex == 'male' && count($titles) > 1) {
                $show[] = $titles[1];
            } else {
                $show[] = $titles[0];
            }
        }
        if ($first != 'logo')
            $show = array_reverse($show);

    }

    if (!empty($show))
        $content = '<div class="mycred-my-rank">' . implode(' ', $show) . '</div>';

    return apply_filters('mycred_my_rank', $content, $user_id, $rank_object);

}

add_shortcode('custom_my_rank', 'custom_render_my_rank');

// Добавляем скрипты и стили bootstrap в админку
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('bootstrap-css', get_stylesheet_directory_uri() . '/inc/assets/css/bootstrap.min.css', false, NULL, 'all');
    wp_enqueue_script('wp-bootstrap-starter-popper', get_stylesheet_directory_uri() . '/inc/assets/js/popper.min.js', array(), '', true);
    wp_enqueue_script('wp-bootstrap-starter-bootstrapjs', get_stylesheet_directory_uri() . '/inc/assets/js/bootstrap.min.js', array(), '', true);
});

/**
 * Выводит код модального окна управления абонементом
 */
function abonementModal()
{
    global $pagenow;
    if ($pagenow != 'users.php') {
        return;
    }
    ?>
    <!--noindex-->
    <div class="modal fade" id="abonementModal" tabindex="-1" role="dialog" aria-labelledby="abonementModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Подарить абонемент на чтение</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Укажите дату, до которой пользователь сможет читать все книги</p>
                    <p>Для отмены абонемента укажите любую прошедшую дату</p>
                    <input type="hidden" id="userId">
                    <input class="form-control" type="date" name="abonement" id="abonementDate"
                           data-trigger="manual" data-content="Это текущий срок абонемента">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" id="saveAbonement">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
    <!--/noindex-->
    <script>
        jQuery(function ($) {
            let abonementUntil = 0;
            $('.addAbonement').on('click', function () {
                abonementUntil = $(this).data('abonement-until');
                console.log(abonementUntil);
                $('#abonementModal').modal('show');
                $('#userId').val($(this).data('user-id'));
                if (abonementUntil !== '0') {
                    $('#abonementDate').val(abonementUntil)
                }
            });
            $('#saveAbonement').on('click', function () {
                let userId = $('#userId').val();
                let date = $('#abonementDate').val();
                if (date !== '' && date !== abonementUntil) {
                    $.ajax({
                        url: ajaxurl,
                        // dataType: 'json',
                        data: {
                            'action': 'add_abonement',
                            'userId': userId,
                            'date': date,
                        },
                        type: 'POST',
                        beforeSend: function () {

                        },
                        success: function (data) {
                            if (data) {
                                location.reload();
                            }
                        }
                    });
                }
                if (date === abonementUntil) {
                    $('#abonementDate').popover('show');
                    setTimeout(function () {
                        $('#abonementDate').popover('hide');

                    }, 2000)
                }
            })
        });
    </script>
    <?php
}

add_action('admin_footer', 'abonementModal');

/**
 * ajax-обработчик изменения абонемента
 */
function add_abonement()
{
    if (!is_admin()) {
        return;
    }
    $userId = intval($_POST['userId']);
    $date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
    update_user_meta($userId, 'abonement', $date);
    echo 1;
    wp_die();
}

add_action('wp_ajax_add_abonement', 'add_abonement');

/**
 * Проверяет наличие абонемента у пользователя, если есть - возвращает дату его окончания, если нет - false
 * @param $userId
 * @return bool|mixed
 */
function hasAbonement($userId)
{
    $abonementUntil = get_user_meta($userId, 'abonement', true);
    if ($abonementUntil) {
        if (strtotime($abonementUntil) >= strtotime('midnight')) {
            return $abonementUntil;
        }
    }
    return false;
}

/**
 * Возвращает id страницы книги по id товара или false
 * если такой страницы нет
 * @param $categoryId
 * @return bool|int
 */
function getBookPageIdByBookId($bookId)
{
    $query_args = array(
        'meta_key' => 'book_id',
        'meta_value' => $bookId,
    );
    $pages = get_pages($query_args);
    if (count($pages) > 0) {
        return $pages[0]->ID;
    }
    return false;
}

/**
 * Возвращает ID заказов, содержащих товар с указанным ID
 *
 * @param integer $product_id (required)
 * @param array $order_status (optional) Default is 'wc-completed
 * @return array
 */
function get_orders_ids_by_product_id($product_id, $order_status = array('wc-completed'))
{
    global $wpdb;

    $results = $wpdb->get_col("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_status IN ( '" . implode("','", $order_status) . "' )
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value = '$product_id'
    ");

    return $results;
}

/**
 * Пересоздает загрузки для заказа
 * @param Integer $order_id
 */
function regen_woo_downloadable_product_permissions($order_id)
{

    // Remove all existing download permissions for this order.
    // This uses the same code as the "regenerate download permissions" action in the WP admin (https://github.com/woocommerce/woocommerce/blob/3.5.2/includes/admin/meta-boxes/class-wc-meta-box-order-actions.php#L129-L131)
    // An instance of the download's Data Store (WC_Customer_Download_Data_Store) is created and
    // uses its method to delete a download permission from the database by order ID.
    $data_store = WC_Data_Store::load('customer-download');
    $data_store->delete_by_order_id($order_id);

    // Run WooCommerce's built in function to create the permissions for an order (https://docs.woocommerce.com/wc-apidocs/function-wc_downloadable_product_permissions.html)
    // Setting the second "force" argument to true makes sure that this ignores the fact that permissions
    // have already been generated on the order.
    wc_downloadable_product_permissions($order_id, true);

}

add_action('save_post', 'updateProductsInOrders', 99, 3);
function updateProductsInOrders($post_id, $post, $update)
{
    if ($update && $post->post_type == 'product') {
        $ordersWithProduct = get_orders_ids_by_product_id($post_id);
        foreach ($ordersWithProduct as $orderId) {
            regen_woo_downloadable_product_permissions($orderId);
        }
    }
}

/**
 * Проверяет товар текущей страницы на наличие в корзине
 * @return bool
 */
function is_product_in_cart()
{
    foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
        $cart_product = $values['data'];

        if (get_the_ID() == $cart_product->id) {
            return true;
        }
    }

    return false;
}

/**
 * Sticky Add to Cart
 *
 * @since 2.3.0
 */
function storefront_sticky_single_add_to_cart()
{
    global $product;

    if (class_exists('Storefront_Sticky_Add_to_Cart') || true !== get_theme_mod('storefront_sticky_add_to_cart')) {
        return;
    }

    if (!is_product()) {
        return;
    }

    $show = false;

    if ($product->is_purchasable() && $product->is_in_stock()) {
        $show = true;
    } else if ($product->is_type('external')) {
        $show = true;
    }

    if (!$show) {
        return;
    }

    $params = apply_filters(
        'storefront_sticky_add_to_cart_params', array(
            'trigger_class' => 'entry-summary',
        )
    );

    wp_localize_script('storefront-sticky-add-to-cart', 'storefront_sticky_add_to_cart_params', $params);

    wp_enqueue_script('storefront-sticky-add-to-cart');
    ?>
    <section class="storefront-sticky-add-to-cart">
        <div class="col-full">
            <div class="storefront-sticky-add-to-cart__content">
                <?php echo wp_kses_post(woocommerce_get_product_thumbnail()); ?>
                <div class="storefront-sticky-add-to-cart__content-product-info">
                    <span class="storefront-sticky-add-to-cart__content-title"><?php esc_attr_e('You\'re viewing:', 'storefront'); ?> <strong><?php the_title(); ?></strong></span>
                    <span class="storefront-sticky-add-to-cart__content-price"><?php echo wp_kses_post($product->get_price_html()); ?></span>
                    <?php echo wp_kses_post(wc_get_rating_html($product->get_average_rating())); ?>
                </div>
                <?php if (is_product_in_cart()): ?>
                    <a href="<?php echo get_permalink(wc_get_page_id('cart')); ?>"
                       class="single_add_to_cart_button button alt">Товар в корзине</a>
                <?php
                else: ?>
                    <a href="<?php echo esc_url($product->add_to_cart_url()); ?>"
                       class="storefront-sticky-add-to-cart__content-button button alt">
                        <?php echo esc_attr($product->add_to_cart_text()); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section><!--1 .storefront-sticky-add-to-cart -->
    <?php
}


/**
 * Удаляю крошки на странице клуба
 */
add_action('id_page_check', 'wc_remove_storefront_breadcrumbs');

function wc_remove_storefront_breadcrumbs()
{
    global $post;
    if ($post->ID == 37) {
        remove_action('storefront_before_content', 'woocommerce_breadcrumb', 10);
    }
}

/**
 * Выводит комментарии на страницу иллюстрации
 * @param $comment
 * @param $args
 * @param $depth
 */
function images_comment($comment, $args, $depth)
{
    ?>
    <div class="grid-item">
        <?php comment_text(); ?>
    </div>
    <?php
}

/**
 * Вывод кнопки предыдущей или следующей главф
 * @param $currentArticleId
 * @param $direction ('next', 'prev')
 * @return false|string
 */
function nearestArticleId($currentArticleId, $direction)
{
    global $post;
    $query = new WP_Query('p=' . $currentArticleId);
    $currentArticleDate = null;
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $currentArticleDate = $post->post_date;
        }
    }
    wp_reset_query();

    if (is_null($currentArticleDate)) {
        return false;
    }
    if ($direction == 'next') {
        $order = 'asc';
        $dateQuery = [
            'after' => $currentArticleDate,
        ];
    } elseif ($direction == 'prev') {
        $order = 'desc';
        $dateQuery = [
            'before' => $currentArticleDate,
        ];
    } else {
        return false;
    }

    $query = new WP_Query(array(
        'cat' => get_post_meta($post->ID, 'cat_id', true),
        'order' => $order,
        'orderby' => 'date',
        'post_type' => 'post',
        'post_status' => 'publish',
        'date_query' => $dateQuery,
        'posts_per_page' => 1
    ));

    $articleId = false;
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $articleId = $post->ID;
        }
    }
    wp_reset_query();
    return $articleId;
}


/** Получение html-кода кнопок предыдущей и следующей главы
 * @param $url
 * @param $text
 * @return false|string
 */
function articleButtonHtml($url, $text)
{
    ob_start();
    ?>
    <li class="page-item mobile-visible"><a href="<?php echo $url ?>" class="page-link next-page-btn" aria-label="Next">
            <span aria-hidden="true"><?php echo $text ?></span>
        </a>
    </li>
    <?php
    $content = ob_get_clean();
    return $content;
}

//отключение магнифика для плагина комментов
function cir_js_file()
{

    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (wp_script_is('comment-images-reloaded') && is_plugin_active('comment-images-reloaded/comment-image-reloaded.php')) {

        wp_dequeue_script('comment-images-reloaded');
        wp_enqueue_script('my-comment-images-reloaded', plugins_url('comment-images-reloaded/js/cir.min.js'), array('jquery'), false, true);
        wp_dequeue_style('magnific');
    }
}

add_action('wp_enqueue_scripts', 'cir_js_file', 999);


/**
 * Добавление библиотек на странице иллюстраций
 */
add_action('wp_enqueue_scripts', 'add_cdn_images');


function add_cdn_images()
{
    $masonryLayout_js_ver = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . '/inc/assets/js/masonryLayout.js'));
    wp_enqueue_script('fancybox-script', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js', array('jquery'), '', true);
    wp_enqueue_script('maconry-script', '/wp-content/themes/storefront-child/inc/assets/js/masonry.pkgd.min.js', array('jquery'), '', true);
    wp_enqueue_script('maconry-custom', '/wp-content/themes/storefront-child/inc/assets/js/masonryLayout.js', array('jquery'), $masonryLayout_js_ver, true);
    wp_enqueue_script('image-loaded', '/wp-content/themes/storefront-child/inc/assets/js/imagesloaded.pkgd.min.js', array('jquery'), '', true);
    wp_enqueue_style('fancybox-style', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css');
}

// Изменяем название Детали на Настройки, Консоль на Мой аккаунт, убираем из меню пункт Адреса
add_filter('woocommerce_account_menu_items', function ($args) {
    if (key_exists('edit-account', $args)) {
        $args['edit-account'] = 'Настройки';
    }
    if (key_exists('dashboard', $args)) {
        $args['dashboard'] = 'Мой аккаунт';
    }
    if (key_exists('edit-address', $args)) {
        unset($args['edit-address']);
    }
    return $args;
});

// Убираем столбцы осталось загрузок и истекает в таблице Загрузки
add_filter('woocommerce_account_downloads_columns', function ($args) {
    if (key_exists('download-remaining', $args)) {
        unset($args['download-remaining']);
    }
    if (key_exists('download-expires', $args)) {
        unset($args['download-expires']);
    }
    return $args;
});

//перенос поделиться под кнопку в корзину
//add_action('woocommerce_after_add_to_cart_form', 'add_sassy', 1);
//function add_sassy()
//{
//    echo do_shortcode('[Sassy_Social_Share]');
//}


// функция проверки используемого шаблона внутри цикла
function filter_template_include($t)
{
    $GLOBALS['current_template'] = basename($t);
    return $t;
}

add_filter('template_include', 'filter_template_include', 1000);

function get_current_template()
{
    if (!isset($GLOBALS['current_template']))
        return false;
    return $GLOBALS['current_template'];
}

// функция ограничения кол-ва слов
function do_excerpt($string, $word_limit)
{
    $words = explode(' ', $string, ($word_limit + 1));
    if (count($words) > $word_limit)
        array_pop($words);
    echo implode(' ', $words) . ' ...';
}

function true_apply_tags_for_pages()
{
    add_meta_box('tagsdiv-post_tag', 'Теги', 'post_tags_meta_box', 'page', 'side', 'normal'); // сначала добавляем метабокс меток
    register_taxonomy_for_object_type('post_tag', 'page'); // затем включаем их поддержку страницами wp
}

add_action('admin_init', 'true_apply_tags_for_pages');

function true_expanded_request_post_tags($q)
{
    if (isset($q['tag'])) // если в запросе присутствует параметр метки
        $q['post_type'] = array('post', 'page');
    return $q;
}

add_filter('request', 'true_expanded_request_post_tags');

//Добавление кастомного js
add_action('wp_enqueue_scripts', 'add_custom_js');
function add_custom_js()
{
    $custom_js_ver = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . '/inc/assets/js/custom.js'));
    wp_enqueue_script('swiper-js', '/wp-content/themes/storefront-child/inc/assets/js/swiper.min.js', array(), '', true);
    wp_enqueue_script('custom-js', '/wp-content/themes/storefront-child/inc/assets/js/custom.js', array(), $custom_js_ver, true);
}

// Удаление инлайн-скриптов из хедера
add_filter('storefront_customizer_css', '__return_false');
add_filter('storefront_customizer_woocommerce_css', '__return_false');
add_filter('storefront_gutenberg_block_editor_customizer_css', '__return_false');

add_action('wp_print_styles', function () {
    wp_styles()->add_data('woocommerce-inline', 'after', '');
});

add_action('init', function () {
    global $heateor_sss;
    remove_action('wp_head', 'wc_gallery_noscript');
    remove_action('wp_enqueue_scripts', array($heateor_sss->plugin_public, 'frontend_inline_style'));
    add_action('wp_footer', function () {
        global $heateor_sss;
        echo '<style type="text/css">';
        $heateor_sss->plugin_public->frontend_inline_style();
        echo '</style>';
    });

});
// Конец удаления инлайн-скриптов из хедера

/**
 * Помечаем все новые комментарии на странице Иллюстрацци как требующие модерации
 * @param $approved
 * @param $commentdata
 * @return int
 */
function imagesCommentCheck($approved, $commentdata)
{
    if ($commentdata['comment_post_ID'] == 35) { //id страницы "Иллюстрации"
        $approved = 0;
    }
    return $approved;
}

add_filter('pre_comment_approved', 'imagesCommentCheck', 20, 2);


/**
 * Выводит фильтр для иллюстраций
 */
function addImageFilter()
{
    $otherFilters = [
        'new',
        'bestseller',
        'subscription'
    ];
    $subCatIds = get_term_children(get_category_by_slug('images')->term_id, 'category');
    $subCats = [];
    foreach ($subCatIds as $subCatId) {
        $subCats[] = get_category($subCatId);
    }
    $tags = get_terms('product_tag');
    $series = get_terms('pa_series-book');
    $cycles = get_terms('pa_cycle-book');
    $nonEmptyTags = [];
    foreach ($tags as $tag) {
        $nonEmptyTags[$tag->slug] = $tag->name;
    }
    ?>
    <div class="d-flex flex-wrap">
        <?php foreach ($subCats as $subCat):
            $hasImages = false;
            $catQuery = new WP_Query('cat=' . $subCat->term_id);
            if ($catQuery->have_posts()) {
                while ($catQuery->have_posts()) {
                    $catQuery->the_post();
                    if (has_post_thumbnail()) {
                        $hasImages = true;
                    }
                }
            }
            if ($subCat->category_count > 0 && $hasImages) { ?>
                <button class="button filter-btn filter-btn-images"
                        data-filter=".images-<?php echo $subCat->slug ?>"><?php echo $subCat->name ?></button>
                <?php
            };
            wp_reset_postdata();
        endforeach; ?>

        <button class="button clear-filters clear-filters-images" data-filter="*"><i class="fas fa-times mr-2"></i>Сбросить
            фильтры
        </button>
    </div>
    <?php
}

function add_columns($columns)
{
    $num = 2; // после какой по счету колонки вставлять новые
    $new_columns = array(
        'id' => 'ID',
    );
    return array_slice($columns, 0, $num) + $new_columns + array_slice($columns, $num);
}

add_filter("manage_edit-post_tag_columns", 'add_columns');


function fill_columns($out, $column_name, $id)
{
    switch ($column_name) {
        case 'id':
            $out .= $id;
            break;
        default:
            break;
    }
    return $out;
}

add_filter("manage_category_custom_column", 'fill_columns', 10, 3);

wp_enqueue_style('animate', get_stylesheet_directory_uri() . '/inc/assets/css/animate.css');
wp_enqueue_script('wow-js', get_stylesheet_directory_uri() . '/inc/assets/js/wow.min.js', array(), '', true);

// Скрываем товары для клуба от непопавших в клуб

function excludeVipProductsFromProductsPage($q)
{
    $myRank = mycred_get_my_rank();
    if (!is_null($myRank) && $myRank->post->post_name == 'platinum-dragon') {
        $hasVip = true;
    } else {
        $hasVip = false;
    }

    if (!$hasVip && !isAdmin()) {
        $q->query_vars['tax_query'][] = [
            'taxonomy' => 'product_tag',
            'terms' => ['vip'],
            'field' => 'slug',
            'operator' => 'NOT IN',
        ];
    }
}

add_action('woocommerce_product_query', 'excludeVipProductsFromProductsPage');

// Скрываем товары для клуба от непопавших в клуб
add_filter('woocommerce_related_products', function ($relatedBookIds) {
    $myRank = mycred_get_my_rank();
    if (!is_null($myRank) && $myRank->post->post_name == 'platinum-dragon') {
        $hasVip = true;
    } else {
        $hasVip = false;
    }
    if (!$hasVip && !isAdmin()) {
        $args = array(
            'tag' => array('vip'),
            'return' => 'ids',
        );
        $vipBooks = wc_get_products($args);
        $relatedBookIds = array_diff($relatedBookIds, $vipBooks);

    }
    return $relatedBookIds;
});

add_action('template_redirect', 'club_redirect');

/**
 * Редирект со страниц клуба, товаров и читалок для клуба на 404
 */
function club_redirect()
{
    global $post, $wp_query, $product, $page;
    $myRank = mycred_get_my_rank();
    if (!is_null($myRank) && $myRank->post->post_name == 'platinum-dragon') {
        $hasVip = true;
    } else {
        $hasVip = false;
    }
    if (!$hasVip && !isAdmin()) {
        if (is_product()) {
            $terms = wc_get_product_terms($post->ID, 'product_tag');
            foreach ($terms as $term) {
                if ($term->slug == 'vip') {
                    wp_redirect(get_permalink(get_page_by_path('club')));
                }
            }
        } elseif (is_single()) {
            $categories = get_the_category();
            foreach ($categories as $category) {
                if ($category->slug == 'club') {
                    wp_redirect(get_permalink(get_page_by_path('club')));
                }
            }
        } elseif (is_page()) {
            $bookId = get_post_meta($post->ID, 'book_id', true);
            if ($bookId) {
                $terms = wc_get_product_terms($bookId, 'product_tag');
                foreach ($terms as $term) {
                    if ($term->slug == 'vip') {
                        wp_redirect(get_permalink(get_page_by_path('club')));
                    }
                }
            }
        }
    }
}

add_filter('wpua_profile_title', function ($title) {
    return '<label class="bg-white wpua-title">Аватар</label>';
});

add_filter('wpua_is_author_or_above', function ($title) {
    return false;
});

function getBlockPart($type, $postQue, $startDelay, $postNumberHideMobile, $customClass, $catId = '')
{
    if ($type === 'blog'):
        $catquery = new WP_Query('cat=' . $catId . '&posts_per_page=' . $postQue . '');
        $portfolio_counter = 1;
        $delay = $startDelay;
        if ($catquery->have_posts()) :
            while ($catquery->have_posts()) :
                $catquery->the_post();
                ?>
                <div class="col-lg-6 col-12 <?= $customClass ?> mb-5 wow fadeInUp <?= ($portfolio_counter === $postNumberHideMobile) ? 'd-lg-block d-none' : ''; ?> "
                     data-wow-delay="<?php echo $delay ?>s">
                    <div class="blog-card">
                        <div class="blog-card__header">
                            <a href="<?php the_permalink() ?>">
                                <div class="blog-card__img">
                                    <?= get_the_post_thumbnail('', 'large') ?>
                                </div>
                            </a>
                        </div>
                        <div class="blog-card__body">
                            <p class="blog-card__date"><?= get_the_date() ?></p>
                            <div class="blog-card__text">
                                <p>
                                    <?php
                                    $desc = strip_tags(get_the_content());
                                    $size = 150;
                                    echo mb_substr($desc, 0, mb_strrpos(mb_substr($desc, 0, $size, 'utf-8'), ' ', 'utf-8'), 'utf-8');
                                    echo (strlen($desc) > $size) ? '...' : '';
                                    ?>
                                </p>
                            </div>
                            <a class="blog-card__link" href="<?php the_permalink() ?>">Подробнее</a>
                        </div>
                    </div>
                </div>
                <?php if ($portfolio_counter == $postQue): ?>
                <div class="col-12 text-center wow fadeInUp"
                     data-wow-delay="<?php echo $delay ?>s">
                    <a class="blog__link"
                       href="<?php echo get_permalink($post = 33) ?>">Смотреть все посты</a>
                </div>
                <?php break;
            endif;
                $portfolio_counter++;
                $delay = $delay + 0.2;
            endwhile;
            wp_reset_postdata();
        else:?>
            <div class="col-lg-6 offset-lg-3 mb-5 col-12 wow fadeInUp"
                 data-wow-delay="<?php echo $delay ?>s">
                <div class="blog-card">
                    <div class="blog-card__body">
                        <div class="blog-card__text empty-card text-center">
                            <p>
                                Пока нет Блогов
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif;
    endif;
    if ($type === 'announcement'):
        $catquery = new WP_Query('cat=33&posts_per_page=' . $postQue . '');
        $delay = $startDelay;
        $portfolio_counter = 1;
        if ($catquery->have_posts()) :
            while ($catquery->have_posts()) :
                $catquery->the_post(); ?>
                <div class="col-lg-6 col-12 <?= $customClass ?> wow fadeInUp <?= ($portfolio_counter === $postNumberHideMobile) ? 'd-lg-block d-none' : ''; ?>"
                     data-wow-delay="<?php echo $delay ?>s">
                    <div class="row">
                        <div class="col-sm-6 col-12">
                            <a href="<?php the_permalink() ?>">
                                <div class="announcement-img">
                                    <?= get_the_post_thumbnail('', 'large') ?>
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
                                    $desc = strip_tags(get_the_content());
                                    $size = 100;
                                    echo mb_substr($desc, 0, mb_strrpos(mb_substr($desc, 0, $size, 'utf-8'), ' ', 'utf-8'), 'utf-8');
                                    echo (strlen($desc) > $size) ? '...' : '';
                                    ?>
                                </p>
                            </div>
                            <a href="<?php the_permalink() ?>" class="announcement-btn">Подробнее</a>
                        </div>
                    </div>
                </div>
                <?php if ($portfolio_counter == $postQue): ?>

                <div class="col-12 text-center wow fadeInUp"
                     data-wow-delay="<?php echo $delay ?>s">
                    <a class="announcement__link"
                       href="<?php echo get_permalink($post = 42) ?>">Смотреть все анонсы</a>
                </div>

                <?php break;
            endif;
                $portfolio_counter++;
                $delay = $delay + 0.2;
            endwhile;
            wp_reset_postdata();
        else: ?>
            <div class="col-lg-6 offset-lg-3 mb-5 col-12 wow fadeInUp"
                 data-wow-delay="<?php echo $delay ?>s">
                <div class="blog-card">
                    <div class="blog-card__body">
                        <div class="blog-card__text empty-card text-center">
                            <p>Пока нет Анонсов</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif;
    endif;
    if ($type === 'news-n-events'):
        $catquery = new WP_Query('cat=35&posts_per_page=' . $postQue . '');
        $portfolio_counter = 1;
        $delay = $startDelay;
        if ($catquery->have_posts()) :
            while ($catquery->have_posts()) :
                $catquery->the_post();
                ?>
                <div class="col-lg-4 col-12 <?= $customClass ?> wow fadeInUp <?= ($portfolio_counter === $postNumberHideMobile) ? 'd-lg-block d-none' : ''; ?>"
                     data-wow-delay="<?php echo $delay ?>s">
                    <div class="news-n-events-card">
                        <div class="news-n-events-card-body">
                            <p class="news-n-events-card__date"><?= get_the_date() ?></p>
                            <div class="news-n-events-card__text">
                                <p>
                                    <?php
                                    $desc = strip_tags(get_the_content());
                                    $size = 80;
                                    echo mb_substr($desc, 0, mb_strrpos(mb_substr($desc, 0, $size, 'utf-8'), ' ', 'utf-8'), 'utf-8');
                                    echo (strlen($desc) > $size) ? '...' : '';
                                    ?>
                                </p>
                            </div>
                            <a href="<?php the_permalink() ?>" class="news-n-events-card__link">Подробнее</a>
                            <p class="news-n-events-card__author"><?php the_author(); ?></p>
                        </div>
                        <div class="news-n-events-card__avatar">
                            <?php echo get_avatar(get_the_author_meta('ID'), 300); ?>
                        </div>
                    </div>
                </div>
                <?php if ($portfolio_counter == $postQue): ?>
                <div class="col-12 text-center wow fadeInUp"
                     data-wow-delay="<?php echo $delay ?>s">
                    <a class="news-n-events__link"
                       href="<?php echo get_permalink($post = 44) ?>">Смотреть все новости</a>
                </div>
                <?php break;
            endif;
                $portfolio_counter++;
                $delay = $delay + 0.2;
            endwhile;
            wp_reset_postdata();
        else: ?>
            <div class="col-lg-6 offset-lg-3 mb-5 col-12 wow fadeInUp"
                 data-wow-delay="<?php echo $delay ?>s">
                <div class="news-n-events-card mr-0">
                    <div class="news-n-events-card-body">
                        <div class="news-n-events-card__text empty-card text-center">
                            <p>
                                Пока нет Новостей
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif;
    endif;
}

/**
 * Если запись $post_ID - глава книги , то отправляет письмо всем пользователям купившим эту книгу,
 * @param $post_ID
 * @return mixed
 */
function sendNotificationAboutNewArticle($post_ID)
{
    $emailNotificationStatus = get_option('newArticleMailNotification', 0);
    if (!$emailNotificationStatus) {
        return;
    }

    // берем категории поста
    $categories = wp_get_post_categories($post_ID);
    // для каждой категории проверяем есть ли страница с мета-полем cat_id = id категории
    foreach ($categories as $category_id) {
        $bookPageId = getBookPageIdByCategoryId($category_id);
        if (!$bookPageId) {
            continue;
        }
        // берем ссылку на страницу, добавляем id записи - получается ссылка на чтение новой главы
        $bookLink = get_permalink($bookPageId) . '?a=' . $post_ID;
        // проверяем по book_id есть ли товар
        $bookId = get_post_meta($bookPageId, 'book_id', true);
        $product = wc_get_product($bookId);
        if (!$product) {
            continue;
        }
        // Берем с товара название и ссылку на картинку
        $bookName = $product->get_name();
        $imgLink = wp_get_attachment_url($product->get_image_id());

        // Выбираем пользователей, купивших эту книгу
        $user_emails = implode(",", getCustomersWhoBoughtBook($bookId));
        ob_start();
        ?>
        <html lang="en" style="margin:0;padding:0">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1"/>
            <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
            <meta name="format-detection" content="telephone=no"/>
            <title>Новая глава в книге <?= $bookName ?></title>
            <style type="text/css"> @media screen and (max-width: 480px) {
                    .mailpoet_button {
                        width: 100% !important;
                    }
                }

                @media screen and (max-width: 599px) {
                    .mailpoet_header {
                        padding: 10px 20px;
                    }

                    .mailpoet_button {
                        width: 100% !important;
                        padding: 5px 0 !important;
                        box-sizing: border-box !important;
                    }

                    div, .mailpoet_cols-two, .mailpoet_cols-three {
                        max-width: 100% !important;
                    }
                }
            </style>

        </head>
        <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"
              style="margin:0;padding:0;background-color:#eeeeee">
        <table class="mailpoet_template" border="0" width="100%" cellpadding="0" cellspacing="0"
               style="border-collapse:collapse;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0">
            <tbody>
            <tr>
                <td class="mailpoet_preheader"
                    style="border-collapse:collapse;display:none;visibility:hidden;mso-hide:all;font-size:1px;color:#333333;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;-webkit-text-size-adjust:none"
                    height="1">

                </td>
            </tr>
            <tr>
                <td align="center" class="mailpoet-wrapper" valign="top"
                    style="border-collapse:collapse;background-color:#eeeeee"><!--[if mso]>
                    <table align="center" border="0" cellspacing="0" cellpadding="0"
                           width="660">
                        <tr>
                            <td class="mailpoet_content-wrapper" align="center" valign="top" width="660">
                    <![endif]-->
                    <table class="mailpoet_content-wrapper" border="0" width="660" cellpadding="0" cellspacing="0"
                           style="border-collapse:collapse;background-color:#ffffff;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0;max-width:660px;width:100%">
                        <tbody>

                        <tr>
                            <td class="mailpoet_content" align="center"
                                style="border-collapse:collapse;background-color:#ffffff!important" bgcolor="#ffffff">
                                <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                       style="border-collapse:collapse;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0">
                                    <tbody>
                                    <tr>
                                        <td style="border-collapse:collapse;padding-left:0;padding-right:0">
                                            <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                                   class="mailpoet_cols-one"
                                                   style="border-collapse:collapse;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0;table-layout:fixed;margin-left:auto;margin-right:auto;padding-left:0;padding-right:0">
                                                <tbody>
                                                <tr>
                                                    <td class="mailpoet_spacer" height="30" valign="top"
                                                        style="border-collapse:collapse"></td>
                                                </tr>
                                                <tr>
                                                    <td class="mailpoet_image mailpoet_padded_vertical mailpoet_padded_side"
                                                        align="center" valign="top"
                                                        style="border-collapse:collapse;padding-top:10px;padding-bottom:10px;padding-left:20px;padding-right:20px">
                                                        <img src="http://marina-eldenbert.ru/wp-content/uploads/2019/11/logo.png"
                                                             width="165" alt="logo"
                                                             style="height:auto;max-width:100%;-ms-interpolation-mode:bicubic;border:0;display:block;outline:none;text-align:center"/>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="mailpoet_content-cols-two" align="left" style="border-collapse:collapse">
                                <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                       style="border-collapse:collapse;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0">
                                    <tbody>
                                    <tr>
                                        <td align="center" style="border-collapse:collapse;font-size:0"><!--[if mso]>
                                            <table border="0" width="100%" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                <tr>
                                                    <td width="220" valign="top">
                                            <![endif]-->
                                            <div style="display:inline-block; max-width:220px; vertical-align:top; width:100%;">
                                                <table width="220" class="mailpoet_cols-two" border="0" cellpadding="0"
                                                       cellspacing="0" align="left"
                                                       style="border-collapse:collapse;width:100%;max-width:220px;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0;table-layout:fixed;margin-left:auto;margin-right:auto;padding-left:0;padding-right:0">
                                                    <tbody>
                                                    <tr>
                                                        <td class="mailpoet_image mailpoet_padded_vertical mailpoet_padded_side"
                                                            align="center" valign="top"
                                                            style="border-collapse:collapse;padding-top:10px;padding-bottom:10px;padding-left:20px;padding-right:20px">
                                                            <img src="<?= $imgLink ?>" width="180"
                                                                 alt="<?= $bookName ?>"
                                                                 style="height:auto;max-width:100%;-ms-interpolation-mode:bicubic;border:0;display:block;outline:none;text-align:center"/>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!--[if mso]>
                                            </td>
                                            <td width="440" valign="top">
                                            <![endif]-->
                                            <div style="display:inline-block; max-width:440px; vertical-align:top; width:100%;">
                                                <table width="440" class="mailpoet_cols-two" border="0" cellpadding="0"
                                                       cellspacing="0" align="left"
                                                       style="border-collapse:collapse;width:100%;max-width:440px;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0;table-layout:fixed;margin-left:auto;margin-right:auto;padding-left:0;padding-right:0">
                                                    <tbody>
                                                    <tr>
                                                        <td class="mailpoet_text mailpoet_padded_vertical mailpoet_padded_side"
                                                            valign="top"
                                                            style="border-collapse:collapse;padding-top:10px;padding-bottom:10px;padding-left:20px;padding-right:20px;word-break:break-word;word-wrap:break-word">
                                                            <h1 style="margin:0 0 9px;color:#111111;font-family:'Trebuchet MS','Lucida Grande','Lucida Sans Unicode','Lucida Sans',Tahoma,sans-serif;font-size:30px;line-height:48px;margin-bottom:0;text-align:center;padding:0;font-style:normal;font-weight:normal">
                                                                <strong>В книге "<?= $bookName ?>" появилась новая
                                                                    глава</strong></h1>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="mailpoet_text mailpoet_padded_vertical mailpoet_padded_side"
                                                            valign="top"
                                                            style="border-collapse:collapse;padding-top:10px;padding-bottom:10px;padding-left:20px;padding-right:20px;word-break:break-word;word-wrap:break-word">
                                                            <table style="border-collapse:collapse;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0"
                                                                   width="100%" cellpadding="0">
                                                                <tr>
                                                                    <td class="mailpoet_paragraph"
                                                                        style="border-collapse:collapse;color:#000000;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;font-size:16px;line-height:25.6px;word-break:break-word;word-wrap:break-word;text-align:left">
                                                                        Чтобы скорее прочесть новую главу переходите по
                                                                        ссылке <a
                                                                                href="<?= $bookLink ?>"><?= $bookLink ?></a>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!--[if mso]>
                                            </td>
                                            </tr>
                                            </tbody>
                                            </table>
                                            <![endif]--></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="mailpoet_content" align="center" style="border-collapse:collapse">
                                <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                       style="border-collapse:collapse;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0">
                                    <tbody>
                                    <tr>
                                        <td style="border-collapse:collapse;padding-left:0;padding-right:0">
                                            <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                                   class="mailpoet_cols-one"
                                                   style="border-collapse:collapse;border-spacing:0;mso-table-lspace:0;mso-table-rspace:0;table-layout:fixed;margin-left:auto;margin-right:auto;padding-left:0;padding-right:0">
                                                <tbody>
                                                <tr>
                                                    <td class="mailpoet_header_footer_padded mailpoet_footer"
                                                        style="border-collapse:collapse;padding:10px 20px;line-height:19.2px;text-align:center;color:#222222;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;font-size:12px">
                                                        <a href="https://marina-eldenbert.ru">marina-eldenbert.ru</a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <!--[if mso]>
                    </td>
                    </tr>
                    </table>
                    <![endif]--></td>
            </tr>
            </tbody>
        </table>
        </body>
        </html>
        <?php
        $message = ob_get_clean();
        $subject = "Доступна новая глава книги " . $bookName; // тема
        $headers = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: " . get_bloginfo('name') . " <info@marina-eldenbert.ru>\r\n";
        mail($user_emails, $subject, $message, $headers);
    }
    return $post_ID;
}

// Добавляем отправку уведомления о новой главе книги при добавлении записи
add_action('publish_post', 'sendNotificationAboutNewArticle');

/**
 * Получаем массив e-mail'ов пользователей, купивших товар
 * @param $product_id
 * @return array
 */

function getCustomersWhoBoughtBook($product_id)
{
// Access WordPress database
    global $wpdb;

// Find billing emails in the DB order table
    $statuses = array_map('esc_sql', wc_get_is_paid_statuses());
    $customer_emails = $wpdb->get_col("
   SELECT DISTINCT pm.meta_value FROM {$wpdb->posts} AS p
   INNER JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id
   INNER JOIN {$wpdb->prefix}woocommerce_order_items AS i ON p.ID = i.order_id
   INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS im ON i.order_item_id = im.order_item_id
   WHERE p.post_status IN ( 'wc-" . implode("','wc-", $statuses) . "' )
   AND pm.meta_key IN ( '_billing_email' )
   AND im.meta_key IN ( '_product_id', '_variation_id' )
   AND im.meta_value = $product_id
");

    return $customer_emails;
}

function getCustomerIdsWhoBoughtBook($product_id)
{
// Access WordPress database
    global $wpdb;

// Find user ids in the DB order table
    $statuses = array_map('esc_sql', wc_get_is_paid_statuses());
    $customer_ids = $wpdb->get_col("
   SELECT DISTINCT pm.meta_value FROM {$wpdb->posts} AS p
   INNER JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id
   INNER JOIN {$wpdb->prefix}woocommerce_order_items AS i ON p.ID = i.order_id
   INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS im ON i.order_item_id = im.order_item_id
   WHERE p.post_status IN ( 'wc-" . implode("','wc-", $statuses) . "' )
   AND pm.meta_key IN ( '_customer_user' )
   AND im.meta_key IN ( '_product_id', '_variation_id' )
   AND im.meta_value = $product_id
");

    return $customer_ids;
}


/**
 * Регистрирует виджет управления рассылкой о новых главах в консоли админки
 */
function newArticleMailWidget()
{
    global $wp_meta_boxes;

    wp_add_dashboard_widget(
        'newArticleMailWidget', //Слаг виджета
        'Управление рассылкой о новых главах', //Заголовок виджета
        'newArticleMail' //Функция вывода
    );
}

add_action('wp_dashboard_setup', 'newArticleMailWidget');


/**
 * Виджет управления рассылкой о новых главах
 */
function newArticleMail()
{
    $doesNotificationsEnabled = get_option('newArticleMailNotification', 0);

    if ($doesNotificationsEnabled) {
        echo '<p><strong>Сейчас рассылка работает - все пользователи, купившие книгу, получат письмо при публикации новой главы в книге</strong></p>';
    } else {
        echo '<p>Включить уведомления читателей при публикации новых глав книги?</p>';
    }
    ?>
    <form method="post">
        <input type="hidden" name="newArticleMail" value="<?php echo ($doesNotificationsEnabled) ? 0 : 1 ?>">
        <button class="button mt-3" type="submit"><?php echo ($doesNotificationsEnabled) ? 'Выключить' : 'Включить' ?>
            уведомления о новых главах
        </button>
    </form>
    <?php
}

/**
 * Изменяет настройку рассылки уведомления о новых главах
 */
function changeArticleMailStatus()
{
    if (is_admin() && isset($_POST['newArticleMail'])) {
        if (intval($_POST['newArticleMail']) === 1) {
            update_option('newArticleMailNotification', 1);
        } elseif (intval($_POST['newArticleMail']) === 0) {
            update_option('newArticleMailNotification', 0);
        }
        header("Location:" . $_SERVER['PHP_SELF']);
    }
}

add_action('init', 'changeArticleMailStatus', 10);

// ФУНКЦИИ УВЕДОМЛЕНИЙ

// Создание таблицы
add_action('init', 'createNotificationTable');
add_action('init', 'getNotifications');

function createNotificationTable()
{
    global $wpdb;
    $table_name = $wpdb->get_blog_prefix() . 'me_notifications'; //me - аббревиатура Марина Эльденберт
    $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $sql = "CREATE TABLE {$table_name} (
    id int auto_increment,
	user_id int not null,
	notification_type varchar(255) not null,
	page_id int null,
	article_page_id int null,
	reply_user_id int null,
	comment_id int null,
    notification_date datetime,
    view_status int not null default 0,
    PRIMARY KEY (id)
) {$charset_collate};";

// Создать таблицу.
    dbDelta($sql);
}

/**
 * Отправка уведомлений при публикации поста
 */
function article_send_notification($new_status, $old_status, $post)
{
    if ($new_status === 'publish' && $post->post_type === 'post') {
        if ($old_status === $new_status) {
            //обновление
            updateArticleNotificationAdd($post->ID);
        } else {
            // создание
            $categories = wp_get_post_categories($post->ID, array('fields' => 'all'));
            foreach ($categories as $category) {
                if ($category->slug == 'news-n-events') {
                    //Новости и события
                    newPostNotificationAdd($post->ID, 'news');
                    break;
                } elseif ($category->slug == 'announcement') {
                    // Анонсы
                    newPostNotificationAdd($post->ID, 'announcement');
                    break;
                }
            }
            newArticleNotificationAdd($post->ID);
        }
    }
    if ($post->post_type == 'product' && $old_status !== $new_status && $new_status === 'publish') {
        newPostNotificationAdd($post->ID, 'new_book');
    }
}

add_action('transition_post_status', 'article_send_notification', 10, 3);

function newPostNotificationAdd($postId, $type)
{
    $possibleTypes = ['news', 'announcement', 'new_book'];
    if (!in_array($type, $possibleTypes)) {
        return;
    }
    $users = get_users(['fields' => ['ID']]);
    global $wpdb;
    $table_name = $wpdb->get_blog_prefix() . 'me_notifications';
    foreach ($users as $user) {
        $wpdb->get_row($wpdb->prepare("INSERT INTO {$table_name} (user_id, notification_type, article_page_id, notification_date) VALUES (%d, %s, %d, NOW());", $user->ID, $type, $postId));
    }
}

//Запись уведомления о добавлении главы
function newArticleNotificationAdd($articlePageId)
{
    $bookData = getBookInfoByArticleId($articlePageId);
    if (!is_array($bookData) || count($bookData) == 0) {
        return;
    }
    $userIds = getCustomerIdsWhoBoughtBook($bookData['bookId']);
    if (is_user_logged_in()) {
        $userIds[] = get_current_user_id();
        $userIds = array_unique($userIds);
    }
    global $wpdb;
    $table_name = $wpdb->get_blog_prefix() . 'me_notifications';
    foreach ($userIds as $userId) {
        $wpdb->get_row($wpdb->prepare("INSERT INTO {$table_name} (user_id, notification_type, article_page_id, notification_date) VALUES (%d, %s, %d, NOW());", $userId, "new_article", $articlePageId));
    }
}

// Запись уведомления об обновлении главы
function updateArticleNotificationAdd($articlePageId)
{
    $bookData = getBookInfoByArticleId($articlePageId);
    if (!is_array($bookData) || count($bookData) == 0) {
        return;
    }
    $userIds = getCustomerIdsWhoBoughtBook($bookData['bookId']);
    if (is_user_logged_in()) {
        $userIds[] = get_current_user_id();
        $userIds = array_unique($userIds);
    }
    global $wpdb;
    $table_name = $wpdb->get_blog_prefix() . 'me_notifications';
    foreach ($userIds as $userId) {
        $wpdb->get_row($wpdb->prepare("INSERT INTO {$table_name} (user_id, notification_type, article_page_id, notification_date) VALUES (%d, %s, %d, NOW());", $userId, "update_article", $articlePageId));
    }
}

// Запись уведомления об ответе на комментарий (userId, replyUserId, pageId, commentId)
function commentReplyNotificationAdd($comment)
{
    if (is_null($comment->comment_parent) || $comment->comment_parent == '' || $comment->comment_parent == 0) {
        return;
    }
    $replyUserId = $comment->user_id;
    $pageId = $comment->comment_post_ID;
    $commentId = $comment->comment_ID;
    $parentComment = WP_Comment::get_instance($comment->comment_parent);
    $userId = $parentComment->user_id;
    if ($userId == $replyUserId) {
        return;
    }
    global $wpdb;
    $table_name = $wpdb->get_blog_prefix() . 'me_notifications';
    $wpdb->get_row($wpdb->prepare("INSERT INTO {$table_name} (user_id, notification_type, page_id, reply_user_id, comment_id, notification_date) VALUES (%d, %s, %d, %d, %d, NOW());", $userId, "reply_comment", $pageId, $replyUserId, $commentId));
}

// Запись уведомления о лайке комментария (userId, replyUserId, pageId, commentId)
function commentLikeNotificationAdd($commentId)
{
    if (!is_user_logged_in()) {
        return;
    }
    $replyUserId = get_current_user_id();
    $comment = WP_Comment::get_instance($commentId);
    $userId = $comment->user_id;
    $pageId = $comment->comment_post_ID;
    if ($userId == $replyUserId) {
        return;
    }
    global $wpdb;
    $table_name = $wpdb->get_blog_prefix() . 'me_notifications';

    $wpdb->get_row($wpdb->prepare("INSERT INTO {$table_name} (user_id, notification_type, page_id, reply_user_id, comment_id, notification_date) VALUES (%d, %s, %d, %d, %d, NOW());", $userId, "like_comment", $pageId, $replyUserId, $commentId));
}


// Возвращает все уведомления
function getNotifications()
{
    if (!is_user_logged_in()) {
        return [];
    }
    $userId = get_current_user_id();

    global $wpdb;
    $table_name = $wpdb->get_blog_prefix() . 'me_notifications';
    $notifications = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$table_name} WHERE user_id = %d ORDER BY notification_date DESC;", $userId));
    return $notifications;
}

// Считает количество непрочитанных уведомлений
function countNewNotifications()
{
    if (!is_user_logged_in()) {
        return 0;
    }
    global $wp_query;
    $is_endpoint = isset($wp_query->query_vars['notifications']);
    if ($is_endpoint && !is_admin() && is_main_query() && is_account_page() && isset($_POST['readNotifications']) && $_POST['readNotifications'] == '1') {
        markAllNotificationsAsRead();
    }

    global $wpdb;
    $userId = get_current_user_id();
    $table_name = $wpdb->get_blog_prefix() . 'me_notifications';
    $notifications = $wpdb->get_results($wpdb->prepare("SELECT COUNT(*) as `count` FROM {$table_name} WHERE user_id = %d AND view_status = 0;", $userId));
    return $notifications[0]->count;
}

//Возвращает html-код карточки уведомления
function getNotificationCard($notification)
{
    $htmlOutput = '';
    $icon = '';
    $isValid = false;
    $type = $notification->notification_type;
    if ($type == 'new_book') {
        $link = get_permalink($notification->article_page_id);
        $post = get_post($notification->article_page_id);
        $content = 'Новая книга - ' . $post->post_title;
        $icon = '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0)">
                    <path d="M5.634 14.0426C5.65903 14.0426 5.68448 14.0408 5.71003 14.0371L14.5455 12.7683C15.2319 12.6692 15.9346 12.619 16.6342 12.619C17.9552 12.619 19.2701 12.7974 20.5425 13.1492C20.8244 13.2271 21.1168 13.0617 21.1948 12.7794C21.2729 12.4972 21.1073 12.2051 20.8251 12.1271C19.4606 11.7498 18.0506 11.5586 16.6342 11.5586C15.884 11.5586 15.1304 11.6125 14.3944 11.7187L5.55935 12.9874C5.26955 13.0291 5.06828 13.2978 5.10996 13.5876C5.14781 13.8519 5.37452 14.0426 5.634 14.0426Z" fill="black"/>
                    <path d="M27.4576 13.1493C29.4209 12.6065 31.4385 12.4784 33.455 12.7685L42.29 14.0372C42.3156 14.0409 42.3409 14.0427 42.366 14.0427C42.6255 14.0427 42.8523 13.852 42.8902 13.5878C42.9319 13.2979 42.7306 13.0292 42.4408 12.9876L33.6059 11.7189C31.444 11.4078 29.2801 11.5452 27.1751 12.1273C26.8928 12.2053 26.7273 12.4973 26.8053 12.7796C26.8834 13.0619 27.1756 13.2273 27.4576 13.1493Z" fill="black"/>
                    <path d="M5.634 17.6864C5.65903 17.6864 5.68448 17.6846 5.71003 17.6809L14.5455 16.4121C15.2319 16.3129 15.9346 16.2628 16.6342 16.2628C17.9552 16.2628 19.2701 16.4412 20.5425 16.793C20.8244 16.871 21.1168 16.7055 21.1948 16.4232C21.2729 16.141 21.1073 15.8489 20.8251 15.7709C19.4606 15.3936 18.0506 15.2024 16.6342 15.2024C15.884 15.2024 15.1304 15.2563 14.3944 15.3625L5.55935 16.6312C5.26955 16.6728 5.06828 16.9416 5.10996 17.2314C5.14781 17.4957 5.37452 17.6864 5.634 17.6864Z" fill="black"/>
                    <path d="M27.4576 16.7931C29.4209 16.2503 31.4387 16.1223 33.455 16.4123L42.29 17.681C42.3156 17.6847 42.3409 17.6865 42.366 17.6865C42.6255 17.6865 42.8523 17.4958 42.8902 17.2316C42.9319 16.9418 42.7306 16.6731 42.4408 16.6314L33.6059 15.3627C31.444 15.0516 29.2803 15.1889 27.1751 15.7711C26.8928 15.8491 26.7273 16.1412 26.8053 16.4234C26.8834 16.7057 27.1756 16.8711 27.4576 16.7931Z" fill="black"/>
                    <path d="M5.634 21.3302C5.65903 21.3302 5.68448 21.3284 5.71003 21.3247L14.5455 20.0559C15.2319 19.9569 15.9346 19.9066 16.6342 19.9066C17.9552 19.9066 19.2702 20.0849 20.5425 20.4368C20.8244 20.5148 21.1168 20.3493 21.1948 20.067C21.2729 19.7848 21.1073 19.4927 20.8251 19.4147C19.4608 19.0374 18.0507 18.8462 16.6342 18.8462C15.8841 18.8462 15.1304 18.9001 14.3944 19.0063L5.55935 20.275C5.26955 20.3166 5.06828 20.5854 5.10996 20.8752C5.14781 21.1395 5.37452 21.3302 5.634 21.3302Z" fill="black"/>
                    <path d="M27.4576 20.4369C29.421 19.894 31.4391 19.7661 33.455 20.0561L42.29 21.3247C42.3156 21.3284 42.3409 21.3302 42.366 21.3302C42.6255 21.3302 42.8523 21.1396 42.8902 20.8753C42.9319 20.5855 42.7306 20.3168 42.4408 20.2751L33.6059 19.0065C31.4437 18.6954 29.2801 18.8328 27.1751 19.4149C26.8928 19.4929 26.7273 19.7849 26.8053 20.0672C26.8834 20.3495 27.1756 20.5149 27.4576 20.4369Z" fill="black"/>
                    <path d="M5.634 24.974C5.65903 24.974 5.68448 24.9722 5.71003 24.9685L14.5455 23.6997C15.2319 23.6005 15.9346 23.5504 16.6342 23.5504C17.9552 23.5504 19.2701 23.7287 20.5425 24.0806C20.8244 24.1585 21.1168 23.9931 21.1948 23.7108C21.2729 23.4285 21.1073 23.1365 20.8251 23.0585C19.4606 22.6812 18.0506 22.49 16.6342 22.49C15.884 22.49 15.1304 22.5439 14.3944 22.6501L5.55935 23.9188C5.26955 23.9604 5.06828 24.2292 5.10996 24.519C5.14781 24.7833 5.37452 24.974 5.634 24.974Z" fill="black"/>
                    <path d="M27.4576 24.0807C29.4209 23.5378 31.4385 23.4098 33.455 23.6999L42.29 24.9685C42.3156 24.9723 42.3409 24.9741 42.366 24.9741C42.6255 24.9741 42.8523 24.7834 42.8902 24.5191C42.9319 24.2293 42.7306 23.9606 42.4408 23.919L33.6059 22.6503C31.444 22.3392 29.2801 22.4766 27.1751 23.0587C26.8928 23.1367 26.7273 23.4287 26.8053 23.711C26.8834 23.9932 27.1756 24.1586 27.4576 24.0807Z" fill="black"/>
                    <path d="M5.63399 28.6178C5.65912 28.6178 5.68446 28.616 5.71002 28.6123L14.5454 27.3435C15.2318 27.2445 15.9345 27.1942 16.634 27.1942C17.9551 27.1942 19.27 27.3726 20.5423 27.7244C20.8243 27.8023 21.1167 27.6369 21.1947 27.3546C21.2727 27.0724 21.1072 26.7803 20.8249 26.7023C19.4605 26.325 18.0505 26.1338 16.634 26.1338C15.884 26.1338 15.1303 26.1877 14.3943 26.2939L5.55923 27.5627C5.26942 27.6044 5.06816 27.8731 5.10983 28.1629C5.1478 28.4271 5.37451 28.6178 5.63399 28.6178Z" fill="black"/>
                    <path d="M27.4576 27.7245C29.421 27.1816 31.4391 27.0537 33.455 27.3437L42.29 28.6123C42.3156 28.6161 42.3409 28.6179 42.366 28.6179C42.6255 28.6179 42.8523 28.4272 42.8902 28.1629C42.9319 27.8731 42.7306 27.6044 42.4408 27.5628L33.6059 26.2941C31.4437 25.983 29.2801 26.1204 27.1751 26.7025C26.8928 26.7805 26.7273 27.0725 26.8053 27.3548C26.8834 27.637 27.1756 27.8024 27.4576 27.7245Z" fill="black"/>
                    <path d="M5.634 32.2617C5.65903 32.2617 5.68448 32.2599 5.71003 32.2562L14.5455 30.9874C15.2321 30.8883 15.9348 30.8381 16.6342 30.8381C17.9552 30.8381 19.2701 31.0165 20.5425 31.3683C20.8244 31.4463 21.1168 31.2808 21.1948 30.9985C21.2729 30.7163 21.1073 30.4242 20.8251 30.3462C19.4606 29.9689 18.0506 29.7776 16.6342 29.7776C15.8842 29.7776 15.1306 29.8315 14.3944 29.9377L5.55935 31.2064C5.26955 31.248 5.06828 31.5167 5.10996 31.8066C5.14781 32.071 5.37452 32.2617 5.634 32.2617Z" fill="black"/>
                    <path d="M27.4577 31.3683C29.4208 30.8255 31.4385 30.6974 33.455 30.9875L42.29 32.2561C42.3156 32.2599 42.3409 32.2617 42.366 32.2617C42.6255 32.2617 42.8523 32.071 42.8902 31.8067C42.9319 31.5169 42.7306 31.2482 42.4408 31.2066L33.6059 29.9379C31.444 29.6268 29.2802 29.7641 27.175 30.3463C26.8928 30.4243 26.7273 30.7163 26.8053 30.9986C26.8834 31.2808 27.1753 31.4462 27.4577 31.3683Z" fill="black"/>
                    <path d="M16.634 34.4818C17.9551 34.4818 19.27 34.6601 20.5423 35.012C20.8243 35.09 21.1167 34.9245 21.1947 34.6422C21.2727 34.3599 21.1072 34.0679 20.8249 33.9899C19.4605 33.6126 18.0505 33.4214 16.634 33.4214C15.8839 33.4214 15.1303 33.4753 14.3943 33.5815L5.55923 34.8502C5.26942 34.8918 5.06816 35.1605 5.10983 35.4504C5.1478 35.7146 5.37451 35.9053 5.63399 35.9053C5.65901 35.9053 5.68446 35.9035 5.71002 35.8997L14.5455 34.631C15.2319 34.532 15.9345 34.4818 16.634 34.4818Z" fill="black"/>
                    <path d="M42.4407 34.8504L33.6057 33.5817C31.4439 33.2706 29.28 33.4079 27.175 33.9901C26.8927 34.0681 26.7272 34.3602 26.8052 34.6424C26.8832 34.9247 27.1756 35.0901 27.4575 35.0122C29.4208 34.4694 31.4384 34.3414 33.4549 34.6314L42.2899 35.9001C42.3154 35.9038 42.3408 35.9056 42.3659 35.9056C42.6254 35.9056 42.8522 35.7149 42.8901 35.4507C42.9317 35.1607 42.7306 34.892 42.4407 34.8504Z" fill="black"/>
                    <path d="M47.4698 12.4669H46.2234V9.92389C46.2234 9.66017 46.0296 9.43653 45.7686 9.3991L33.6057 7.65188C32.8642 7.54531 32.1166 7.49378 31.3697 7.49473V3.40234C31.3697 3.20287 31.2578 3.02038 31.0802 2.92982C30.9024 2.83926 30.689 2.85623 30.5278 2.97351L29.0899 4.01917L27.652 2.97351C27.4909 2.85623 27.2774 2.83937 27.0996 2.92982C26.9219 3.02027 26.81 3.20287 26.81 3.40234V8.16989C25.8404 8.46181 24.8979 8.84621 24 9.32106C21.0727 7.7733 17.6737 7.18085 14.3943 7.65188L2.2314 9.3991C1.97043 9.43664 1.77659 9.66017 1.77659 9.92389V12.4669H0.530199C0.237423 12.4669 0 12.7044 0 12.9971V44.5973C0 44.7446 0.061291 44.8853 0.169239 44.9857C0.267644 45.0772 0.396801 45.1275 0.530199 45.1275C0.543136 45.1275 0.555967 45.1271 0.568903 45.1262L21.8277 43.5724H26.1724L47.4313 45.1262C47.4442 45.1271 47.457 45.1275 47.4699 45.1275C47.6034 45.1275 47.7325 45.0772 47.8308 44.9857C47.9388 44.8853 48.0001 44.7446 48.0001 44.5973V12.9971C48 12.7043 47.7627 12.4669 47.4698 12.4669ZM33.4549 8.70147L45.163 10.3833V39.5684L33.6057 37.9082C30.5225 37.4653 27.3335 37.9627 24.5302 39.3103V10.2404C27.2525 8.81037 30.4089 8.26394 33.4549 8.70147ZM4.33279 40.4249L14.5451 38.9579C17.5909 38.5205 20.7473 39.0667 23.4698 40.4969V41.09C21.8127 40.3357 19.9407 39.9663 17.7744 39.9663C16.7235 39.9663 15.6182 40.0516 14.3845 40.2288L4.33279 41.8754V40.4249ZM22.3383 41.77C22.8268 41.9507 23.2948 42.1638 23.7433 42.4121C23.9031 42.5004 24.0968 42.5004 24.2567 42.4121C24.7059 42.1635 25.1733 41.9489 25.6616 41.7677V42.512H22.3383V41.77ZM24.5302 41.0888V40.4969C27.2528 39.0667 30.4093 38.5204 33.4549 38.9579L43.6672 40.4249V41.8755L33.6061 40.2272C29.8971 39.6952 26.9849 39.9686 24.5302 41.0888ZM27.8704 4.44354L28.7781 5.10364C28.9639 5.23884 29.216 5.23884 29.4018 5.10364L30.3095 4.44354V7.53185C29.4886 7.58815 28.6726 7.70819 27.8705 7.89047V4.44354H27.8704ZM2.83699 10.3834L14.5451 8.70157C17.5909 8.26405 20.7473 8.81037 23.4698 10.2405V39.3116C21.3343 38.285 18.9756 37.7507 16.6094 37.7507C15.8696 37.7507 15.1288 37.8028 14.3943 37.9083L2.83699 39.5685V10.3834ZM1.0604 44.0269V13.5273H1.77659V40.1802C1.77659 40.334 1.84329 40.4802 1.95962 40.581C2.07573 40.6816 2.23012 40.7269 2.38218 40.7051L3.27239 40.5773V42.4997C3.27239 42.6552 3.34078 42.803 3.45934 42.9038C3.57789 43.0045 3.73462 43.0481 3.88827 43.023L14.5455 41.277C15.7183 41.1086 16.7744 41.0269 17.7743 41.0269C19.0498 41.0269 20.2111 41.1634 21.2778 41.4389V42.5495L1.0604 44.0269ZM46.9396 44.0269L26.722 42.5493V41.4363C28.6153 40.9461 30.8117 40.8976 33.4451 41.2752L44.1116 43.0228C44.1402 43.0274 44.1689 43.0297 44.1974 43.0297C44.3222 43.0297 44.4441 42.9856 44.5406 42.9037C44.6592 42.8029 44.7276 42.6552 44.7276 42.4995V40.5772L45.6179 40.705C45.643 40.7086 45.6683 40.7104 45.6933 40.7104C45.82 40.7104 45.9434 40.665 46.0405 40.581C46.1567 40.4802 46.2235 40.3341 46.2235 40.1802V13.5273H46.9397V44.0269H46.9396Z" fill="black"/>
                    </g>
                    <defs>
                    <clipPath id="clip0">
                    <rect width="48" height="48" fill="white"/>
                    </clipPath>
                    </defs>
                </svg>';
        $isValid = true;
    } elseif ($type == 'news' || $type == 'announcement') {
        $link = get_permalink($notification->article_page_id);
        $post = get_post($notification->article_page_id);
        if ($type == 'news') {
            $content = 'Добавлена новость - ' . $post->post_title;
            $icon = 'newspaper.svg';
        } else {
            $content = 'Новый анонс - ' . $post->post_title;
            $icon = 'guide.svg';
        }
        $isValid = true;
    } elseif ($type == 'new_article' || $type == 'update_article') {
        $bookData = getBookInfoByArticleId($notification->article_page_id);
        if (count($bookData) == 0) {
            return '';
        }
        $link = $bookData['bookLink'];
        $bookName = $bookData['product']->get_name();
        if ($type == 'new_article') {
            $content = 'Новая глава в книге "' . $bookName . '"';
            $icon = '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M36.938 0.000324954H4.2946C4.29038 0.000324954 4.28626 0 4.28204 0C3.56313 0 2.88744 0.279786 2.37943 0.787906C1.87055 1.29668 1.59033 1.9728 1.59033 2.69171V38.3976C1.59033 38.6967 1.83286 38.9392 2.13192 38.9392H3.93975V47.4584C3.93975 47.7575 4.18228 48 4.48134 48H36.9757C37.2748 48 37.5173 47.7575 37.5173 47.4584V45.9249H39.0875C39.3865 45.9249 39.6291 45.6824 39.6291 45.3834V2.69138C39.6291 1.20753 38.4219 0.000324954 36.938 0.000324954ZM6.97288 42.3507H38.546V43.0546H6.97288V42.3507ZM6.97288 41.2675V6.28245H38.546V41.2674H6.97288V41.2675ZM3.93986 3.95361V37.856H2.67362V2.69171C2.67362 2.26222 2.84119 1.85809 3.14546 1.55382C3.44886 1.25032 3.85245 1.08329 4.28182 1.08318V1.08351H4.28843C5.17187 1.08697 5.8897 1.80686 5.8897 2.69171V3.41202H4.48156C4.18228 3.41202 3.93986 3.65455 3.93986 3.95361ZM36.4341 46.9168H5.02304V4.4952H5.88959L5.8897 45.3834C5.8897 45.6824 6.13222 45.9249 6.43129 45.9249H36.4342V46.9168H36.4341ZM6.97288 44.8418V44.1378H38.546V44.8418H6.97288ZM38.5459 5.19927H6.97288V2.69138H6.97277C6.97266 2.08913 6.7739 1.53238 6.43844 1.08351H36.938C37.8247 1.08351 38.546 1.8048 38.546 2.69138V5.19927H38.5459Z" fill="black"/>
                        <path d="M10.0326 13.7559C10.0326 14.055 10.2751 14.2975 10.5742 14.2975C10.882 14.2975 11.0083 14.4054 11.2376 14.6011C11.5159 14.8387 11.8971 15.1641 12.6044 15.1641C13.3117 15.1641 13.6929 14.8387 13.9713 14.6011C14.2007 14.4053 14.327 14.2975 14.6349 14.2975C14.943 14.2975 15.0694 14.4054 15.2988 14.6011C15.5772 14.8387 15.9584 15.1641 16.6658 15.1641C17.3732 15.1641 17.7543 14.8387 18.0326 14.6011C18.2621 14.4053 18.3884 14.2975 18.6962 14.2975C19.0043 14.2975 19.1306 14.4054 19.36 14.6011C19.6382 14.8387 20.0195 15.1641 20.7268 15.1641C21.4342 15.1641 21.8154 14.8388 22.0938 14.6011C22.3232 14.4053 22.4496 14.2975 22.7576 14.2975C23.0657 14.2975 23.1921 14.4054 23.4215 14.6011C23.6999 14.8387 24.0811 15.1641 24.7886 15.1641C25.4959 15.1641 25.877 14.8387 26.1554 14.6011C26.3848 14.4053 26.5111 14.2975 26.819 14.2975C27.1271 14.2975 27.2536 14.4054 27.483 14.6013C27.7614 14.8388 28.1426 15.1641 28.8502 15.1641C29.5577 15.1641 29.9389 14.8388 30.2172 14.6013C30.4468 14.4054 30.5732 14.2975 30.8813 14.2975C31.1896 14.2975 31.316 14.4054 31.5456 14.6013C31.8239 14.8388 32.2053 15.1641 32.9129 15.1641C33.6204 15.1641 34.0017 14.8388 34.2802 14.6013C34.5097 14.4054 34.6362 14.2975 34.9445 14.2975C35.2435 14.2975 35.4861 14.055 35.4861 13.7559C35.4861 13.4569 35.2435 13.2144 34.9445 13.2144C34.2369 13.2144 33.8557 13.5396 33.5772 13.7772C33.3476 13.973 33.2211 14.0809 32.913 14.0809C32.6048 14.0809 32.4783 13.973 32.2488 13.7772C31.9704 13.5396 31.589 13.2144 30.8815 13.2144C30.1739 13.2144 29.7926 13.5396 29.5143 13.7772C29.2847 13.973 29.1583 14.0809 28.8503 14.0809C28.5422 14.0809 28.4157 13.973 28.1863 13.7772C27.9079 13.5396 27.5266 13.2144 26.8191 13.2144C26.1118 13.2144 25.7306 13.5397 25.4522 13.7773C25.2228 13.9731 25.0965 14.0809 24.7887 14.0809C24.4806 14.0809 24.3542 13.973 24.1248 13.7773C23.8464 13.5397 23.4651 13.2144 22.7577 13.2144C22.0503 13.2144 21.6691 13.5396 21.3907 13.7773C21.1613 13.9731 21.0349 14.0809 20.727 14.0809C20.419 14.0809 20.2926 13.973 20.0633 13.7773C19.7849 13.5397 19.4037 13.2144 18.6963 13.2144C17.989 13.2144 17.6078 13.5397 17.3294 13.7773C17.1 13.9731 16.9737 14.0809 16.6658 14.0809C16.3578 14.0809 16.2314 13.973 16.002 13.7773C15.7236 13.5397 15.3425 13.2144 14.635 13.2144C13.9277 13.2144 13.5465 13.5397 13.2682 13.7773C13.0387 13.9731 12.9124 14.0809 12.6046 14.0809C12.2968 14.0809 12.1704 13.973 11.941 13.7773C11.6628 13.5397 11.2816 13.2144 10.5743 13.2144C10.275 13.2144 10.0326 13.4568 10.0326 13.7559Z" fill="black"/>
                        <path d="M34.9445 17.0864C34.237 17.0864 33.8557 17.4117 33.5772 17.6492C33.3477 17.8451 33.2212 17.953 32.913 17.953C32.6048 17.953 32.4783 17.8451 32.2488 17.6492C31.9704 17.4117 31.589 17.0864 30.8815 17.0864C30.1739 17.0864 29.7927 17.4117 29.5143 17.6492C29.2848 17.8451 29.1584 17.953 28.8503 17.953C28.5422 17.953 28.4157 17.8451 28.1863 17.6492C27.9079 17.4117 27.5266 17.0864 26.8191 17.0864C26.1118 17.0864 25.7306 17.4118 25.4522 17.6494C25.2228 17.8452 25.0965 17.953 24.7887 17.953C24.4806 17.953 24.3542 17.8451 24.1248 17.6494C23.8464 17.4118 23.4651 17.0864 22.7577 17.0864C22.0503 17.0864 21.6691 17.4117 21.3907 17.6494C21.1613 17.8452 21.0349 17.953 20.727 17.953C20.419 17.953 20.2926 17.8451 20.0633 17.6494C19.7849 17.4118 19.4038 17.0864 18.6963 17.0864C17.989 17.0864 17.6078 17.4118 17.3295 17.6494C17.1 17.8452 16.9737 17.953 16.6658 17.953C16.3578 17.953 16.2314 17.8451 16.002 17.6494C15.7236 17.4118 15.3425 17.0864 14.635 17.0864C13.9277 17.0864 13.5466 17.4118 13.2682 17.6494C13.0388 17.8452 12.9125 17.953 12.6046 17.953C12.2968 17.953 12.1704 17.8451 11.9411 17.6494C11.6628 17.4118 11.2816 17.0864 10.5743 17.0864C10.2752 17.0864 10.0327 17.329 10.0327 17.628C10.0327 17.9271 10.2752 18.1696 10.5743 18.1696C10.8821 18.1696 11.0084 18.2775 11.2378 18.4732C11.516 18.7108 11.8972 19.0362 12.6045 19.0362C13.3118 19.0362 13.693 18.7108 13.9714 18.4732C14.2008 18.2774 14.3271 18.1696 14.635 18.1696C14.9431 18.1696 15.0695 18.2775 15.2989 18.4732C15.5773 18.7108 15.9585 19.0362 16.6659 19.0362C17.3733 19.0362 17.7544 18.7108 18.0328 18.4732C18.2622 18.2774 18.3885 18.1696 18.6963 18.1696C19.0044 18.1696 19.1307 18.2775 19.3601 18.4732C19.6384 18.7108 20.0197 19.0362 20.727 19.0362C21.4343 19.0362 21.8156 18.7109 22.0939 18.4732C22.3234 18.2774 22.4498 18.1696 22.7577 18.1696C23.0658 18.1696 23.1922 18.2775 23.4216 18.4732C23.7 18.7108 24.0813 19.0362 24.7887 19.0362C25.496 19.0362 25.8772 18.7108 26.1556 18.4732C26.385 18.2774 26.5113 18.1696 26.8191 18.1696C27.1273 18.1696 27.2537 18.2775 27.4831 18.4733C27.7615 18.7109 28.1428 19.0362 28.8503 19.0362C29.5578 19.0362 29.939 18.7109 30.2174 18.4733C30.4469 18.2775 30.5733 18.1696 30.8815 18.1696C31.1898 18.1696 31.3162 18.2775 31.5457 18.4733C31.8241 18.7109 32.2055 19.0362 32.913 19.0362C33.6205 19.0362 34.0018 18.7109 34.2803 18.4733C34.5098 18.2775 34.6363 18.1696 34.9446 18.1696C35.2437 18.1696 35.4862 17.9271 35.4862 17.628C35.4862 17.329 35.2437 17.0864 34.9445 17.0864Z" fill="black"/>
                        <path d="M34.9445 20.959C34.237 20.959 33.8557 21.2843 33.5772 21.5218C33.3477 21.7176 33.2212 21.8255 32.913 21.8255C32.6048 21.8255 32.4783 21.7176 32.2488 21.5218C31.9704 21.2843 31.589 20.959 30.8815 20.959C30.1739 20.959 29.7927 21.2843 29.5143 21.5218C29.2848 21.7176 29.1584 21.8255 28.8503 21.8255C28.5422 21.8255 28.4157 21.7176 28.1863 21.5218C27.9079 21.2843 27.5266 20.959 26.8191 20.959C26.1118 20.959 25.7306 21.2844 25.4522 21.5219C25.2228 21.7178 25.0965 21.8255 24.7887 21.8255C24.4806 21.8255 24.3542 21.7176 24.1248 21.5219C23.8464 21.2844 23.4651 20.959 22.7577 20.959C22.0503 20.959 21.6691 21.2843 21.3907 21.5219C21.1613 21.7178 21.0349 21.8255 20.727 21.8255C20.419 21.8255 20.2926 21.7176 20.0633 21.5219C19.7849 21.2844 19.4038 20.959 18.6963 20.959C17.989 20.959 17.6078 21.2844 17.3295 21.5219C17.1 21.7178 16.9737 21.8255 16.6658 21.8255C16.3578 21.8255 16.2314 21.7176 16.002 21.5219C15.7236 21.2844 15.3425 20.959 14.635 20.959C13.9277 20.959 13.5466 21.2844 13.2682 21.5219C13.0388 21.7178 12.9125 21.8255 12.6046 21.8255C12.2968 21.8255 12.1704 21.7176 11.9411 21.5219C11.6628 21.2844 11.2816 20.959 10.5743 20.959C10.2752 20.959 10.0327 21.2015 10.0327 21.5006C10.0327 21.7996 10.2752 22.0422 10.5743 22.0422C10.8821 22.0422 11.0084 22.1501 11.2378 22.3458C11.516 22.5833 11.8972 22.9087 12.6045 22.9087C13.3118 22.9087 13.693 22.5833 13.9714 22.3458C14.2008 22.1499 14.3271 22.0422 14.635 22.0422C14.9431 22.0422 15.0695 22.1501 15.2989 22.3458C15.5773 22.5833 15.9585 22.9087 16.6659 22.9087C17.3733 22.9087 17.7544 22.5833 18.0328 22.3458C18.2622 22.1499 18.3885 22.0422 18.6963 22.0422C19.0044 22.0422 19.1307 22.1501 19.3601 22.3458C19.6384 22.5833 20.0197 22.9087 20.727 22.9087C21.4343 22.9087 21.8156 22.5834 22.0939 22.3458C22.3234 22.1499 22.4498 22.0422 22.7577 22.0422C23.0658 22.0422 23.1922 22.1501 23.4216 22.3458C23.7 22.5833 24.0813 22.9087 24.7887 22.9087C25.496 22.9087 25.8772 22.5833 26.1556 22.3458C26.385 22.1499 26.5113 22.0422 26.8191 22.0422C27.1273 22.0422 27.2537 22.1501 27.4831 22.3459C27.7615 22.5834 28.1428 22.9087 28.8503 22.9087C29.5578 22.9087 29.939 22.5834 30.2174 22.3459C30.4469 22.1501 30.5733 22.0422 30.8815 22.0422C31.1898 22.0422 31.3162 22.1501 31.5457 22.3459C31.8241 22.5834 32.2055 22.9087 32.913 22.9087C33.6205 22.9087 34.0018 22.5834 34.2803 22.3459C34.5098 22.1501 34.6363 22.0422 34.9446 22.0422C35.2437 22.0422 35.4862 21.7996 35.4862 21.5006C35.4862 21.2015 35.2437 20.959 34.9445 20.959Z" fill="black"/>
                        <path d="M35.4861 25.3726C35.4861 25.0736 35.2435 24.8311 34.9445 24.8311C34.2369 24.8311 33.8557 25.1563 33.5772 25.3939C33.3476 25.5897 33.2211 25.6976 32.913 25.6976C32.6048 25.6976 32.4783 25.5897 32.2488 25.3939C31.9704 25.1563 31.589 24.8311 30.8815 24.8311C30.1739 24.8311 29.7926 25.1563 29.5143 25.3939C29.2847 25.5897 29.1583 25.6976 28.8503 25.6976C28.5422 25.6976 28.4157 25.5897 28.1863 25.3939C27.9079 25.1563 27.5266 24.8311 26.8191 24.8311C26.1118 24.8311 25.7306 25.1564 25.4522 25.394C25.2228 25.5898 25.0965 25.6976 24.7887 25.6976C24.4806 25.6976 24.3542 25.5897 24.1248 25.394C23.8464 25.1564 23.4651 24.8311 22.7577 24.8311C22.0503 24.8311 21.6691 25.1563 21.3907 25.394C21.1613 25.5898 21.0349 25.6976 20.727 25.6976C20.419 25.6976 20.2926 25.5897 20.0633 25.394C19.7849 25.1564 19.4037 24.8311 18.6963 24.8311C17.989 24.8311 17.6078 25.1564 17.3295 25.394C17.1 25.5898 16.9737 25.6976 16.6658 25.6976C16.3578 25.6976 16.2314 25.5897 16.002 25.394C15.7236 25.1564 15.3425 24.8311 14.635 24.8311C13.9277 24.8311 13.5466 25.1564 13.2682 25.394C13.0388 25.5898 12.9125 25.6976 12.6046 25.6976C12.2968 25.6976 12.1704 25.5897 11.9411 25.394C11.6628 25.1564 11.2816 24.8311 10.5743 24.8311C10.2752 24.8311 10.0327 25.0736 10.0327 25.3726C10.0327 25.6717 10.2752 25.9142 10.5743 25.9142C10.8821 25.9142 11.0084 26.0221 11.2378 26.2179C11.516 26.4554 11.8972 26.7808 12.6045 26.7808C13.3118 26.7808 13.693 26.4554 13.9714 26.2179C14.2008 26.022 14.3271 25.9142 14.635 25.9142C14.9431 25.9142 15.0695 26.0221 15.2989 26.2179C15.5773 26.4554 15.9585 26.7808 16.6659 26.7808C17.3733 26.7808 17.7544 26.4554 18.0328 26.2179C18.2622 26.022 18.3885 25.9142 18.6963 25.9142C19.0044 25.9142 19.1307 26.0221 19.3601 26.2179C19.6384 26.4554 20.0196 26.7808 20.727 26.7808C21.4343 26.7808 21.8156 26.4555 22.0939 26.2179C22.3234 26.022 22.4498 25.9142 22.7577 25.9142C23.0658 25.9142 23.1922 26.0221 23.4216 26.2179C23.7 26.4554 24.0812 26.7808 24.7887 26.7808C25.496 26.7808 25.8772 26.4554 26.1555 26.2179C26.385 26.022 26.5113 25.9142 26.8191 25.9142C27.1273 25.9142 27.2537 26.0221 27.4831 26.218C27.7615 26.4555 28.1427 26.7808 28.8503 26.7808C29.5578 26.7808 29.939 26.4555 30.2174 26.218C30.4469 26.0221 30.5733 25.9142 30.8815 25.9142C31.1897 25.9142 31.3161 26.0221 31.5457 26.218C31.824 26.4555 32.2054 26.7808 32.913 26.7808C33.6205 26.7808 34.0018 26.4555 34.2803 26.218C34.5098 26.0221 34.6363 25.9142 34.9446 25.9142C35.2436 25.9142 35.4861 25.6717 35.4861 25.3726Z" fill="black"/>
                        <path d="M24.7887 29.5697C24.4806 29.5697 24.3542 29.4618 24.1248 29.2661C23.8464 29.0285 23.4652 28.7031 22.7577 28.7031C22.0503 28.7031 21.6691 29.0284 21.3908 29.2661C21.1613 29.4619 21.0349 29.5697 20.727 29.5697C20.419 29.5697 20.2926 29.4618 20.0633 29.2661C19.7849 29.0285 19.4038 28.7031 18.6963 28.7031C17.989 28.7031 17.6078 29.0285 17.3295 29.2661C17.1 29.4619 16.9737 29.5697 16.6658 29.5697C16.3579 29.5697 16.2314 29.4618 16.002 29.2661C15.7236 29.0285 15.3425 28.7031 14.635 28.7031C13.9277 28.7031 13.5466 29.0285 13.2682 29.2661C13.0388 29.4618 12.9125 29.5697 12.6046 29.5697C12.2968 29.5697 12.1704 29.4618 11.9411 29.2661C11.6628 29.0285 11.2816 28.7031 10.5743 28.7031C10.2752 28.7031 10.0327 28.9456 10.0327 29.2447C10.0327 29.5438 10.2752 29.7863 10.5743 29.7863C10.8821 29.7863 11.0084 29.8942 11.2378 30.0899C11.516 30.3275 11.8972 30.6529 12.6045 30.6529C13.3118 30.6529 13.693 30.3275 13.9714 30.0899C14.2008 29.8941 14.3271 29.7863 14.635 29.7863C14.9431 29.7863 15.0695 29.8942 15.2989 30.0899C15.5773 30.3275 15.9585 30.6529 16.6659 30.6529C17.3733 30.6529 17.7544 30.3275 18.0328 30.0899C18.2622 29.8942 18.3885 29.7863 18.6963 29.7863C19.0044 29.7863 19.1307 29.8942 19.3601 30.0899C19.6384 30.3275 20.0197 30.6529 20.727 30.6529C21.4344 30.6529 21.8156 30.3276 22.094 30.0899C22.3234 29.8941 22.4498 29.7863 22.7577 29.7863C23.0658 29.7863 23.1922 29.8942 23.4216 30.0899C23.7 30.3275 24.0813 30.6529 24.7887 30.6529C25.0878 30.6529 25.3303 30.4103 25.3303 30.1113C25.3303 29.8122 25.0878 29.5697 24.7887 29.5697Z" fill="black"/>
                        <path d="M20.0785 3.41211H8.38082C8.08176 3.41211 7.83923 3.65463 7.83923 3.9537C7.83923 4.25277 8.08176 4.49529 8.38082 4.49529H20.0786C20.3777 4.49529 20.6202 4.25277 20.6202 3.9537C20.6202 3.65463 20.3776 3.41211 20.0785 3.41211Z" fill="black"/>
                        <path d="M23.7829 3.41211H22.1583C21.8592 3.41211 21.6167 3.65463 21.6167 3.9537C21.6167 4.25277 21.8592 4.49529 22.1583 4.49529H23.7829C24.082 4.49529 24.3245 4.25277 24.3245 3.9537C24.3245 3.65463 24.082 3.41211 23.7829 3.41211Z" fill="black"/>
                        <path d="M8.25082 6.82397C7.95175 6.82397 7.70923 7.0665 7.70923 7.36557V12.7521C7.70923 13.0512 7.95175 13.2937 8.25082 13.2937C8.54989 13.2937 8.79241 13.0512 8.79241 12.7521V7.36557C8.79241 7.06639 8.54999 6.82397 8.25082 6.82397Z" fill="black"/>
                        <path d="M10.5796 8.85266V7.36557C10.5796 7.0665 10.3371 6.82397 10.0381 6.82397C9.73898 6.82397 9.49646 7.0665 9.49646 7.36557V8.85266C9.49646 9.15173 9.73898 9.39426 10.0381 9.39426C10.3371 9.39426 10.5796 9.15173 10.5796 8.85266Z" fill="black"/>
                        <path d="M36.9754 28.4324C36.6764 28.4324 36.4338 28.6749 36.4338 28.974V40.1844C36.4338 40.4834 36.6764 40.7259 36.9754 40.7259C37.2745 40.7259 37.517 40.4834 37.517 40.1844V28.974C37.517 28.6749 37.2746 28.4324 36.9754 28.4324Z" fill="black"/>
                        <path d="M35.1883 33.7937C34.8893 33.7937 34.6467 34.0362 34.6467 34.3353V35.21C34.6467 35.509 34.8893 35.7516 35.1883 35.7516C35.4874 35.7516 35.7299 35.509 35.7299 35.21V34.3353C35.7299 34.0362 35.4874 33.7937 35.1883 33.7937Z" fill="black"/>
                        <path d="M35.1883 36.3005C34.8893 36.3005 34.6467 36.5431 34.6467 36.8421V40.1843C34.6467 40.4834 34.8893 40.7259 35.1883 40.7259C35.4874 40.7259 35.7299 40.4834 35.7299 40.1843V36.8421C35.7299 36.5431 35.4874 36.3005 35.1883 36.3005Z" fill="black"/>
                        <path d="M46.3781 7.03796L44.7917 2.59388C44.7148 2.37833 44.5105 2.23438 44.2816 2.23438C44.0527 2.23438 43.8484 2.37833 43.7715 2.59388L42.1852 7.03796C42.1644 7.09634 42.1537 7.15798 42.1537 7.22004V45.1982C42.1537 46.3716 43.1083 47.3261 44.2816 47.3261C45.4549 47.3261 46.4096 46.3715 46.4096 45.1982V7.22004C46.4096 7.15798 46.3989 7.09634 46.3781 7.03796ZM44.2817 4.38698L45.0997 6.67845H43.4637L44.2817 4.38698ZM43.237 41.7527V7.76163H45.3265V41.7527H43.237ZM45.3264 42.8359V43.2692H43.2369V42.8359H45.3264ZM44.2817 46.2429C43.7057 46.2429 43.237 45.7742 43.237 45.1981V44.3523H45.3265V45.1981C45.3264 45.7743 44.8577 46.2429 44.2817 46.2429Z" fill="black"/>
                    </svg>';
        } else {
            $content = 'Обновление главы в книге "' . $bookName . '"';
            $icon = '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0)">
                        <path d="M22.0521 10.266C18.3518 13.9665 18.3518 19.9872 22.0521 23.6877C23.9023 25.5379 26.3326 26.463 28.7629 26.463C31.1932 26.463 33.6235 25.5379 35.4737 23.6877C39.1741 19.9872 39.1741 13.9665 35.4737 10.266C31.7735 6.56564 25.7525 6.56564 22.0521 10.266ZM35.8939 21.1744V12.7792C37.4124 15.36 37.4124 18.5938 35.8939 21.1744ZM34.6771 11.1933V22.7604C34.6556 22.7825 34.6354 22.8055 34.6136 22.8274C31.3875 26.0532 26.1388 26.0534 22.9125 22.8274C22.4315 22.3464 22.024 21.8198 21.6865 21.2624H22.3881C22.7241 21.2624 22.9964 20.99 22.9964 20.654C22.9964 20.3181 22.7241 20.0456 22.3881 20.0456H21.0804C20.6027 18.8448 20.4177 17.5544 20.5236 16.2835H25.3082C25.6443 16.2835 25.9166 16.0111 25.9166 15.6751C25.9166 15.3392 25.6443 15.0668 25.3082 15.0668H20.716C21.0388 13.6967 21.7153 12.3947 22.7431 11.3046H30.5402C30.8763 11.3046 31.1486 11.0322 31.1486 10.6962C31.1486 10.3603 30.8763 10.0879 30.5402 10.0879H24.1804C25.5628 9.16775 27.1627 8.70698 28.7629 8.70698C30.8816 8.70698 33.0005 9.51343 34.6135 11.1265C34.6354 11.1482 34.6556 11.1712 34.6771 11.1933Z" fill="black"/>
                        <path d="M47.3585 32.475L38.5445 23.6609C39.8856 21.7072 40.6078 19.3977 40.6078 16.9768C40.6078 13.813 39.3756 10.8385 37.1384 8.60136C36.2158 7.67871 35.1764 6.94368 34.0687 6.3897V0.942842C34.0687 0.606901 33.7964 0.334473 33.4603 0.334473H6.77602C6.61468 0.334473 6.45991 0.398595 6.3459 0.512603L0.178252 6.67904C0.0641222 6.79317 0 6.94794 0 7.10928V47.0573C0 47.3932 0.272306 47.6656 0.60837 47.6656H33.4603C33.7964 47.6656 34.0687 47.3932 34.0687 47.0573V27.564C34.541 27.3278 35.0005 27.0582 35.444 26.755L44.2613 35.5723C44.6883 35.9993 45.249 36.2128 45.81 36.2128C46.3707 36.2128 46.9317 35.9993 47.3587 35.5723C47.7724 35.1588 48.0001 34.6087 48.0001 34.0237C48 33.4387 47.7722 32.8887 47.3585 32.475ZM39.391 16.9768C39.391 19.8157 38.2854 22.4846 36.2781 24.4919C32.134 28.6358 25.3918 28.6357 21.2478 24.4919C19.2404 22.4845 18.1349 19.8156 18.1349 16.9768C18.1349 14.138 19.2404 11.469 21.2478 9.46172C23.3198 7.38986 26.0413 6.35393 28.763 6.35393C31.4847 6.35393 34.2061 7.38986 36.2782 9.46172C38.2854 11.4691 39.391 14.138 39.391 16.9768ZM6.16765 2.4112V6.50079H2.07734L6.16765 2.4112ZM32.852 46.4489H1.21674V7.71765H6.77602C7.11208 7.71765 7.38439 7.44522 7.38439 7.10928V1.55121H32.852V5.86431C28.6529 4.32659 23.752 5.23696 20.3876 8.60149C19.6379 9.35112 19.0018 10.1839 18.4864 11.0784H12.776C12.4399 11.0784 12.1676 11.3508 12.1676 11.6868C12.1676 12.0227 12.4399 12.2951 12.776 12.2951H17.8768C17.5335 13.093 17.2794 13.9285 17.1195 14.7895H5.90119C5.56512 14.7895 5.29282 15.0619 5.29282 15.3978C5.29282 15.7338 5.56512 16.0062 5.90119 16.0062H16.9578C16.9319 16.3274 16.9182 16.6512 16.9182 16.977C16.9182 17.4907 16.9511 17.9992 17.0151 18.5005H11.3157C10.9796 18.5005 10.7073 18.7729 10.7073 19.1089C10.7073 19.4448 10.9796 19.7173 11.3157 19.7173H17.2358C17.4398 20.5822 17.7402 21.418 18.1318 22.2116H5.90119C5.56512 22.2116 5.29282 22.484 5.29282 22.8199C5.29282 23.1559 5.56512 23.4283 5.90119 23.4283H18.8256C19.2732 24.1145 19.7946 24.7595 20.3874 25.3525C20.5872 25.5522 20.7942 25.7401 21.0043 25.9226H18.9203C18.5842 25.9226 18.3119 26.1951 18.3119 26.531C18.3119 26.8669 18.5842 27.1394 18.9203 27.1394H22.6788C24.5447 28.2562 26.6535 28.8161 28.763 28.8161C30.1493 28.8161 31.5348 28.5721 32.852 28.0896V46.4489ZM37.1384 25.3523C37.3695 25.1213 37.5892 24.8819 37.7986 24.6357L44.0374 30.8745L42.6608 32.2511L36.4196 26.0099C36.6653 25.8013 36.9064 25.5842 37.1384 25.3523ZM46.4982 34.712C46.1187 35.0915 45.5011 35.0915 45.1216 34.712L43.5211 33.1115L44.8977 31.7349L46.4982 33.3354C46.682 33.5192 46.7833 33.7637 46.7833 34.0237C46.7833 34.2837 46.682 34.5281 46.4982 34.712Z" fill="black"/>
                        <path d="M5.90122 12.2949H10.7683C11.1044 12.2949 11.3767 12.0224 11.3767 11.6865C11.3767 11.3506 11.1044 11.0781 10.7683 11.0781H5.90122C5.56515 11.0781 5.29285 11.3506 5.29285 11.6865C5.29285 12.0224 5.56515 12.2949 5.90122 12.2949Z" fill="black"/>
                        <path d="M5.90122 19.717H9.24737C9.58343 19.717 9.85574 19.4446 9.85574 19.1086C9.85574 18.7727 9.58343 18.5002 9.24737 18.5002H5.90122C5.56515 18.5002 5.29285 18.7727 5.29285 19.1086C5.29285 19.4446 5.56515 19.717 5.90122 19.717Z" fill="black"/>
                        <path d="M5.90122 27.1391H17.1318C17.4679 27.1391 17.7402 26.8667 17.7402 26.5307C17.7402 26.1948 17.4679 25.9224 17.1318 25.9224H5.90122C5.56515 25.9224 5.29285 26.1948 5.29285 26.5307C5.29285 26.8667 5.56515 27.1391 5.90122 27.1391Z" fill="black"/>
                        <path d="M28.1676 29.6335H10.525C10.1889 29.6335 9.91663 29.906 9.91663 30.2419C9.91663 30.5779 10.1889 30.8503 10.525 30.8503H28.1676C28.5037 30.8503 28.776 30.5779 28.776 30.2419C28.776 29.906 28.5037 29.6335 28.1676 29.6335Z" fill="black"/>
                        <path d="M5.90122 30.8503H8.57816C8.91423 30.8503 9.18653 30.5779 9.18653 30.2419C9.18653 29.906 8.91423 29.6335 8.57816 29.6335H5.90122C5.56515 29.6335 5.29285 29.906 5.29285 30.2419C5.29285 30.5779 5.56515 30.8503 5.90122 30.8503Z" fill="black"/>
                        <path d="M28.1676 37.0557H16.6695C16.3335 37.0557 16.0612 37.3281 16.0612 37.664C16.0612 38 16.3335 38.2724 16.6695 38.2724H28.1676C28.5037 38.2724 28.776 38 28.776 37.664C28.776 37.3281 28.5037 37.0557 28.1676 37.0557Z" fill="black"/>
                        <path d="M5.90122 38.2724H14.7592C15.0953 38.2724 15.3676 38 15.3676 37.664C15.3676 37.3281 15.0953 37.0557 14.7592 37.0557H5.90122C5.56515 37.0557 5.29285 37.3281 5.29285 37.664C5.29285 38 5.56515 38.2724 5.90122 38.2724Z" fill="black"/>
                        <path d="M28.1675 40.7666H21.6579C21.3219 40.7666 21.0496 41.039 21.0496 41.375C21.0496 41.7109 21.3219 41.9833 21.6579 41.9833H28.1675C28.5035 41.9833 28.7759 41.7109 28.7759 41.375C28.7759 41.039 28.5035 40.7666 28.1675 40.7666Z" fill="black"/>
                        <path d="M19.8452 40.7666H8.95542C8.61935 40.7666 8.34705 41.039 8.34705 41.375C8.34705 41.7109 8.61935 41.9833 8.95542 41.9833H19.8452C20.1813 41.9833 20.4536 41.7109 20.4536 41.375C20.4536 41.039 20.1812 40.7666 19.8452 40.7666Z" fill="black"/>
                        <path d="M7.11808 40.7666H5.90122C5.56515 40.7666 5.29285 41.039 5.29285 41.375C5.29285 41.7109 5.56515 41.9833 5.90122 41.9833H7.11808C7.45414 41.9833 7.72645 41.7109 7.72645 41.375C7.72645 41.039 7.45414 40.7666 7.11808 40.7666Z" fill="black"/>
                        <path d="M28.1676 33.3445H24.4566C24.1206 33.3445 23.8483 33.6169 23.8483 33.9529C23.8483 34.2888 24.1206 34.5612 24.4566 34.5612H28.1676C28.5036 34.5612 28.7759 34.2888 28.7759 33.9529C28.7759 33.6169 28.5036 33.3445 28.1676 33.3445Z" fill="black"/>
                        <path d="M5.90122 34.5612H22.3272C22.6633 34.5612 22.9356 34.2888 22.9356 33.9529C22.9356 33.6169 22.6633 33.3445 22.3272 33.3445H5.90122C5.56515 33.3445 5.29285 33.6169 5.29285 33.9529C5.29285 34.2888 5.56515 34.5612 5.90122 34.5612Z" fill="black"/>
                        <path d="M30.5401 15.0667H28.4109C28.0748 15.0667 27.8025 15.3391 27.8025 15.675C27.8025 16.011 28.0748 16.2834 28.4109 16.2834H30.5401C30.8762 16.2834 31.1485 16.011 31.1485 15.675C31.1485 15.3391 30.8762 15.0667 30.5401 15.0667Z" fill="black"/>
                        <path d="M30.5402 20.0457H24.9432C24.6071 20.0457 24.3348 20.3181 24.3348 20.654C24.3348 20.99 24.6071 21.2624 24.9432 21.2624H30.5402C30.8763 21.2624 31.1486 20.99 31.1486 20.654C31.1486 20.3181 30.8763 20.0457 30.5402 20.0457Z" fill="black"/>
                        </g>
                        <defs>
                        <clipPath id="clip0">
                        <rect width="48" height="48" fill="white"/>
                        </clipPath>
                        </defs>
                    </svg>';
        }
        $isValid = true;
    } elseif ($type == 'reply_comment' || $type == 'like_comment') {
        $comment = WP_Comment::get_instance($notification->comment_id);
        if ($type == 'reply_comment') {
            $replyUserId = $comment->user_id;
        } else {
            $replyUserId = $notification->reply_user_id;
        }
        $user = get_userdata($replyUserId);
        $replyUser = $user->display_name;
        $userSex = get_user_meta($replyUserId, 'sex', true);
        $link = get_comment_link($comment);
        if ($type == 'reply_comment') {
            $feminitive = ($userSex === '1' || $userSex === '') ? 'а' : '';
            $content = $replyUser . ' ответил' . $feminitive . ' на ваш комментарий';
            $icon = '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M36.7759 16.635H7.4668C7.07839 16.635 6.76367 16.9498 6.76367 17.3381C6.76367 17.7265 7.07839 18.0413 7.4668 18.0413H36.7759C37.1643 18.0413 37.479 17.7265 37.479 17.3381C37.479 16.9498 37.1643 16.635 36.7759 16.635Z" fill="black"/>
                        <path d="M26.2547 12.8774H7.4668C7.07839 12.8774 6.76367 13.1923 6.76367 13.5806C6.76367 13.9689 7.07839 14.2837 7.4668 14.2837H26.2547C26.6431 14.2837 26.9578 13.9689 26.9578 13.5806C26.9578 13.1922 26.6431 12.8774 26.2547 12.8774Z" fill="black"/>
                        <path d="M36.7757 12.8774H29.2606C28.8722 12.8774 28.5575 13.1923 28.5575 13.5806C28.5575 13.9689 28.8722 14.2837 29.2606 14.2837H36.7757C37.1642 14.2837 37.4789 13.9689 37.4789 13.5806C37.4789 13.1922 37.1642 12.8774 36.7757 12.8774Z" fill="black"/>
                        <path d="M17.2364 20.3923H7.4668C7.07839 20.3923 6.76367 20.7071 6.76367 21.0955C6.76367 21.4838 7.07839 21.7986 7.4668 21.7986H17.2364C17.6248 21.7986 17.9396 21.4838 17.9396 21.0955C17.9396 20.7072 17.6248 20.3923 17.2364 20.3923Z" fill="black"/>
                        <path d="M36.7759 20.3923H21.7452C21.3568 20.3923 21.0421 20.7071 21.0421 21.0955C21.0421 21.4838 21.3568 21.7986 21.7452 21.7986H36.7759C37.1643 21.7986 37.479 21.4838 37.479 21.0955C37.479 20.7072 37.1643 20.3923 36.7759 20.3923Z" fill="black"/>
                        <path d="M11.2244 9.11987H7.4668C7.07839 9.11987 6.76367 9.43469 6.76367 9.823C6.76367 10.2113 7.07849 10.5261 7.4668 10.5261H11.2244C11.6128 10.5261 11.9275 10.2113 11.9275 9.823C11.9275 9.43478 11.6128 9.11987 11.2244 9.11987Z" fill="black"/>
                        <path d="M36.776 9.11987H14.2308C13.8424 9.11987 13.5277 9.43469 13.5277 9.823C13.5277 10.2113 13.8424 10.5261 14.2308 10.5261H36.776C37.1644 10.5261 37.4791 10.2113 37.4791 9.823C37.4791 9.43478 37.1644 9.11987 36.776 9.11987Z" fill="black"/>
                        <path d="M29.2605 24.1501H7.4668C7.07839 24.1501 6.76367 24.465 6.76367 24.8533C6.76367 25.2416 7.07839 25.5564 7.4668 25.5564H29.2605C29.6489 25.5564 29.9636 25.2416 29.9636 24.8533C29.9636 24.4649 29.6489 24.1501 29.2605 24.1501Z" fill="black"/>
                        <path d="M36.7759 24.1501H32.2665C31.8781 24.1501 31.5634 24.465 31.5634 24.8533C31.5634 25.2416 31.8781 25.5564 32.2665 25.5564H36.7759C37.1643 25.5564 37.479 25.2416 37.479 24.8533C37.479 24.4649 37.1643 24.1501 36.7759 24.1501Z" fill="black"/>
                        <path d="M44.291 7.61668H43.4912V6.81681C43.4912 4.77155 41.8273 3.10767 39.782 3.10767H3.70914C1.66398 3.10767 0 4.77155 0 6.81671V27.8592C0 29.9044 1.66398 31.5683 3.70914 31.5683H4.50902V32.3682C4.50902 34.4134 6.17299 36.0773 8.21816 36.0773H13.809L13.2269 38.5997C13.091 39.188 13.3256 39.7881 13.8243 40.1284C14.0734 40.2983 14.3588 40.3834 14.6442 40.3833C14.9302 40.3833 15.2162 40.2979 15.4656 40.1273L20.7049 36.5426L32.5344 44.6364C32.7838 44.8071 33.0698 44.8924 33.3558 44.8924C33.6412 44.8924 33.9266 44.8075 34.1757 44.6375C34.6744 44.2972 34.909 43.6971 34.7731 43.1088L33.1505 36.0773H44.2909C46.3361 36.0773 48 34.4134 48 32.3682V11.3259C48.0002 9.28066 46.3363 7.61668 44.291 7.61668ZM8.21825 34.6714C6.9484 34.6713 5.91537 33.6381 5.91537 32.3684V31.5685H14.8496L14.1336 34.6714H8.21825ZM14.6716 38.967C14.657 38.977 14.6445 38.9857 14.617 38.967C14.5897 38.9483 14.5932 38.9335 14.5971 38.9162L16.4185 31.0234C16.4668 30.8147 16.417 30.5953 16.2837 30.4276C16.1503 30.2599 15.9477 30.1622 15.7335 30.1622H3.70914C2.43938 30.1621 1.40626 29.129 1.40626 27.8593V6.81671C1.40626 5.54686 2.43938 4.51383 3.70914 4.51383H39.7818C41.0517 4.51383 42.0847 5.54696 42.0847 6.81671V27.8592C42.0847 29.129 41.0516 30.1621 39.7818 30.1621H27.7577C27.616 30.1621 27.4777 30.2049 27.3607 30.2849L14.6716 38.967ZM44.291 34.6711H32.2668C32.0526 34.6711 31.85 34.7688 31.7166 34.9365C31.5832 35.1042 31.5335 35.3235 31.5818 35.5323L33.4032 43.425C33.4071 43.4423 33.4106 43.4572 33.3833 43.4758C33.356 43.4944 33.3434 43.4858 33.3287 43.4758L21.9501 35.6908L27.9752 31.5684H39.7819C41.8272 31.5684 43.4911 29.9044 43.4911 27.8593V9.02294H44.291C45.5608 9.02294 46.5938 10.0561 46.5938 11.3258V32.3683H46.5939C46.5939 33.6381 45.5608 34.6711 44.291 34.6711Z" fill="black"/>
                    </svg>';
            $isValid = true;
        } else {
            $feminitive = ($userSex === '1' || $userSex === '') ? 'а' : '';
            $content = $replyUser . ' оценил' . $feminitive . ' ваш комментарий';
            $icon = '<svg width="48" height="48" viewBox="0 0 48 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M47.1818 19.5408C46.3417 18.0263 44.9618 16.9298 43.2967 16.4532C41.6317 15.9767 39.8805 16.1769 38.3663 17.0177L36.0585 18.2988L34.7679 16.1832L35.6648 14.7133C38.3325 10.338 36.9448 4.60897 32.5715 1.94206C31.1119 1.05153 29.4409 0.580811 27.7393 0.580811C24.4683 0.580811 21.4996 2.246 19.7978 5.03526L17.5614 8.7007L13.8072 6.61692C12.4292 5.85201 10.8725 5.44757 9.30539 5.44757C5.92654 5.44757 2.81038 7.28114 1.17303 10.2325C-0.0325949 12.4024 -0.320314 14.9117 0.362937 17.2982C1.046 19.6838 2.61725 21.6612 4.78776 22.8665L8.5701 24.9651L23.4993 33.254C23.6162 33.3189 23.743 33.3498 23.8683 33.3498C24.1253 33.3498 24.3757 33.2193 24.5188 32.9848L27.1728 28.6337L31.0893 35.054C31.2321 35.2884 31.4825 35.4189 31.7397 35.4189C31.8649 35.4189 31.9917 35.388 32.1087 35.3232L38.0228 32.0396C38.3903 31.8355 38.5229 31.372 38.3189 31.0045C38.1147 30.6371 37.6515 30.5046 37.2838 30.7085L32.0085 33.6373L27.8239 26.7772C27.8235 26.7766 27.8233 26.7759 27.8228 26.7753L25.6139 23.1544L24.9128 22.0052C24.4816 21.298 24.2386 20.5125 24.193 19.7083C24.1657 19.2258 24.2094 18.7366 24.3264 18.2537C24.6382 16.966 25.4328 15.8768 26.5643 15.1869C26.6264 15.1491 26.6891 15.1133 26.7523 15.0784C26.7722 15.0675 26.7922 15.0567 26.8122 15.046C26.8585 15.0212 26.9052 14.9973 26.9521 14.9741C26.9725 14.9641 26.9926 14.9535 27.0131 14.9436C27.0692 14.9168 27.1256 14.8914 27.1823 14.8668C27.2181 14.8513 27.2542 14.8366 27.2902 14.8219C27.3141 14.8122 27.3382 14.8027 27.3623 14.7934C27.5062 14.7377 27.6524 14.6884 27.8006 14.6465C27.8034 14.6457 27.8063 14.6449 27.8091 14.6441C28.1846 14.5385 28.5719 14.4776 28.9635 14.4633C28.9711 14.4631 28.9789 14.4629 28.9864 14.4626C29.0387 14.461 29.0909 14.4599 29.1432 14.4599C29.2395 14.4599 29.3355 14.4632 29.4309 14.4687C29.446 14.4696 29.4612 14.4707 29.4763 14.4717C29.5543 14.4768 29.6318 14.4842 29.7091 14.4931C29.733 14.4958 29.7571 14.4982 29.7811 14.5014C29.8519 14.5105 29.9221 14.5218 29.992 14.5338C30.0169 14.5382 30.042 14.5415 30.0668 14.5462C30.1535 14.5625 30.2392 14.5814 30.3244 14.6021C30.3616 14.6111 30.3981 14.6218 30.4349 14.6317C30.4885 14.6462 30.542 14.6607 30.595 14.6768C30.6296 14.6873 30.6638 14.6987 30.6981 14.7099C30.7571 14.7293 30.8158 14.7494 30.8738 14.771C30.8995 14.7805 30.9251 14.79 30.9506 14.7998C31.0292 14.8305 31.1071 14.8624 31.184 14.897C31.1886 14.8991 31.1933 14.901 31.1978 14.903C31.8248 15.1871 32.39 15.601 32.8537 16.1226C32.8646 16.1349 32.8755 16.1473 32.8862 16.1597C32.9376 16.2187 32.9875 16.2793 33.0363 16.3411C33.0494 16.3577 33.0628 16.3741 33.0756 16.3908C33.1301 16.4615 33.1829 16.5337 33.2339 16.6078C33.2855 16.683 33.3359 16.7596 33.3838 16.8381L35.1392 19.7154C35.3519 20.0641 35.8017 20.1826 36.1584 19.9845L39.1052 18.3488C40.2637 17.7055 41.6032 17.5521 42.8777 17.9169C44.1517 18.2816 45.2075 19.1206 45.8506 20.2797C46.4941 21.438 46.6476 22.7774 46.283 24.0513C45.9183 25.325 45.0793 26.3809 43.9207 27.0242L39.5031 29.476C39.1356 29.6801 39.003 30.1435 39.2071 30.511C39.4111 30.8787 39.8744 31.0113 40.2421 30.807L44.6597 28.3554C46.1738 27.5146 47.2702 26.1349 47.7467 24.4704C48.2235 22.8053 48.0227 21.0544 47.1818 19.5408ZM33.7782 14.8817C33.7765 14.8799 33.7745 14.8783 33.7729 14.8765C33.2495 14.3421 32.6392 13.9047 31.964 13.5809C31.9622 13.5799 31.9605 13.5791 31.9587 13.5782C31.876 13.5387 31.7922 13.5012 31.7075 13.4651C31.6887 13.4569 31.67 13.4486 31.651 13.4406C31.5843 13.4128 31.5166 13.3867 31.4487 13.3611C31.4112 13.3469 31.3741 13.3324 31.3363 13.3189C31.2852 13.3007 31.2334 13.2839 31.1818 13.267C31.1275 13.2491 31.0734 13.2311 31.0186 13.2146C30.9792 13.2028 30.9392 13.1921 30.8995 13.181C30.8338 13.1626 30.7682 13.1445 30.7019 13.1281C30.6924 13.1257 30.6833 13.1229 30.674 13.1206C30.646 13.1137 30.618 13.1087 30.5901 13.1024C30.524 13.0873 30.4578 13.0724 30.3911 13.0593C30.3427 13.0498 30.2942 13.0419 30.2456 13.0336C30.1921 13.0243 30.1387 13.0148 30.0848 13.0068C30.0139 12.9963 29.9428 12.9881 29.8719 12.9799C29.8411 12.9765 29.8103 12.9722 29.7794 12.9691C29.6785 12.9591 29.5777 12.9516 29.4768 12.9462C29.4747 12.9461 29.4728 12.9459 29.4708 12.9458C29.2869 12.9362 29.1035 12.9352 28.9205 12.9413C28.9204 12.9413 28.9203 12.9413 28.9202 12.9413C28.1965 12.9657 27.4831 13.112 26.8044 13.3768C26.7786 13.3868 26.7528 13.3969 26.7272 13.4073C26.6763 13.428 26.6254 13.4487 26.575 13.4705C26.5066 13.5001 26.4386 13.531 26.3711 13.563C26.3381 13.5787 26.3056 13.5951 26.2728 13.6115C26.2136 13.6409 26.1547 13.671 26.0962 13.7023C26.0693 13.7167 26.0424 13.7311 26.0157 13.746C25.9334 13.7914 25.8515 13.8381 25.771 13.8872C24.2925 14.7888 23.2537 16.2123 22.8463 17.8956C22.4388 19.5784 22.7111 21.3194 23.6127 22.7982L26.2808 27.1721L23.5993 31.5684L9.30886 23.6339L5.52651 21.5355C3.7116 20.5277 2.39769 18.8741 1.82656 16.8792C1.25544 14.8842 1.49591 12.7862 2.5041 10.9717C3.87341 8.50355 6.47938 6.97026 9.3052 6.97026C10.6144 6.97026 11.9156 7.30851 13.0681 7.94826L17.4611 10.3866C17.8182 10.5846 18.2677 10.466 18.4803 10.1175L21.0973 5.82829C22.5203 3.49588 25.0032 2.10341 27.739 2.10341C29.1608 2.10341 30.5575 2.49716 31.7784 3.242C35.4351 5.47195 36.5952 10.2625 34.3647 13.9205L33.7782 14.8817Z" fill="black"/>
                    </svg>';
            $isValid = true;
        }
    }
    if ($isValid):
        ob_start(); ?>
        <a href="<?= $link ?>">
            <div class="notification-card"
                 style="display: none">
                <div class="row">
                    <div class="col-lg-1 col-2"><?= $icon ?></div>
                    <div class="col-lg-11 col-10 pl-lg-3 m-auto pl-0">
                        <div class="row">
                            <div class="col-lg-8 col-12">
                                <?= ($notification->view_status == 0) ? '<p class="notification-card__new">Новое уведомление</p>' : null; ?>
                                <p class="notification-card__text"><?= $content ?></p>
                            </div>
                            <div class="col-lg-4 col-12 notification-card__date m-auto text-left text-lg-right">
                                <p><?= date('d.m.Y H:i', strtotime($notification->notification_date)); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <?php
        $htmlOutput = ob_get_clean();
    endif;
    return $htmlOutput;
}

// Пометка уведомления прочитанным для добавления и изменения главы

function markArticleNotificationAsRead($articleId)
{
    if (!is_user_logged_in()) {
        return;
    }
    $userId = get_current_user_id();
    global $wpdb;
    $table_name = $wpdb->get_blog_prefix() . 'me_notifications';
    $wpdb->get_results($wpdb->prepare("UPDATE {$table_name} SET view_status = 1 WHERE user_id = %d AND article_page_id = %d AND view_status = 0;", $userId, $articleId));
}

// Пометка уведомления прочитанным для ответов и лайков к комментариям
function markCommentNotificationAsRead($pageId)
{
    if (!is_user_logged_in()) {
        return;
    }
    $userId = get_current_user_id();
    global $wpdb;
    $table_name = $wpdb->get_blog_prefix() . 'me_notifications';
    $wpdb->get_results($wpdb->prepare("UPDATE {$table_name} SET view_status = 1 WHERE user_id = %d AND page_id = %d AND view_status = 0;", $userId, $pageId));
}

// Пометка всех уведомлений прочитанными
function markAllNotificationsAsRead()
{
    if (!is_user_logged_in()) {
        return;
    }
    $userId = get_current_user_id();

    global $wpdb;
    $table_name = $wpdb->get_blog_prefix() . 'me_notifications';
    $wpdb->get_results($wpdb->prepare("UPDATE {$table_name} SET view_status = 1 WHERE user_id = %d AND view_status = 0;", $userId));
}


// Добавляем эндпоинт страницы уведомлений
add_action('init', 'my_account_new_endpoints');

function my_account_new_endpoints()
{
    add_rewrite_endpoint('notifications', EP_ROOT | EP_PAGES);
}

// Определяем шаблон страницы уведомлений для эндпоинта страницы уведомлений
add_action('woocommerce_account_notifications_endpoint', 'notifications_endpoint_content');
function notifications_endpoint_content()
{
    get_template_part('notifications');
}

// Добавляем в меню личного кабинета пункт "Уведомления"
add_filter('woocommerce_account_menu_items', 'addNotificationsPage', 10, 2);

function wpb_woo_my_account_order()
{
    $myorder = array(
        'dashboard' => 'Мой аккаунт',
        'notifications' => 'Мои уведомления',
        'downloads' => 'Моя библиотека',
        'orders' => 'Мои покупки',
        'edit-account' => 'Настройки',
        'customer-logout' => __('Logout', 'woocommerce'),
    );
    return $myorder;
}

add_filter('woocommerce_account_menu_items', 'wpb_woo_my_account_order');

function notificationPageAddQueryVar($vars)
{
    $vars[] = 'notifications';
    return $vars;
}

// Добавляем переменную запроса для страницы уведомлений
add_filter('query_vars', 'notificationPageAddQueryVar', 0);

// Добавляем хлебную крошку для страницы уведомлений
add_filter('woocommerce_get_breadcrumb', function ($args) {
    global $wp_query;
    $is_endpoint = isset($wp_query->query_vars['notifications']);
    if ($is_endpoint && !is_admin() && is_main_query() && is_account_page()) {
        $args[] = ['Уведомления', get_page_link() . 'notifications/'];
    }
    return $args;
});

// Изменяем заголовок для страницы уведомлений
add_filter('the_title', 'notificationsEndpointTitle');

function notificationsEndpointTitle($title)
{
    global $wp_query;
    $is_endpoint = isset($wp_query->query_vars['notifications']);
    if ($is_endpoint && !is_admin() && is_main_query() && in_the_loop() && is_account_page()) {                // New page title.				'
        $title = "Уведомления";
        remove_filter('the_title', 'notificationsEndpointTitle');
    }
    return $title;
}

/**
 * Добавляем эндпоинт страницы уведомлений
 * @param $args
 * @param $endpoints
 * @return mixed
 */
function addNotificationsPage($args, $endpoints)
{
    $notifications = ['notifications' => 'Уведомления'];
    array_splice_assoc($args, 2, 0, $notifications);
    $endpoints['notifications'] = 'notifications';
    return $args;
}

/**
 * Аналог php-функции array_splice, доработанный для ассоциативных массивов
 * @param $input
 * @param $offset
 * @param $length
 * @param array $replacement
 */
function array_splice_assoc(&$input, $offset, $length, $replacement = array())
{
    $replacement = (array)$replacement;
    $key_indices = array_flip(array_keys($input));
    if (isset($input[$offset]) && is_string($offset)) {
        $offset = $key_indices[$offset];
    }
    if (isset($input[$length]) && is_string($length)) {
        $length = $key_indices[$length] - $offset;
    }

    $input = array_slice($input, 0, $offset, TRUE)
        + $replacement
        + array_slice($input, $offset + $length, NULL, TRUE);
}


/** Возвращает массив с данными о книге по id записи с главой книги
 * @param $post_ID
 * @return array данные о книге, пустой массив, если запись не относится к книге
 */
function getBookInfoByArticleId($post_ID)
{
    $result = [];
    $categories = wp_get_post_categories($post_ID);
    // для каждой категории проверяем есть ли страница с мета-полем cat_id = id категории
    foreach ($categories as $category_id) {
        $bookPageId = getBookPageIdByCategoryId($category_id);
        if (!$bookPageId) {
            continue;
        }
        // берем ссылку на страницу, добавляем id записи - получается ссылка на чтение новой главы
        $result['bookLink'] = get_permalink($bookPageId) . '?a=' . $post_ID;
        // проверяем по book_id есть ли товар
        $bookId = get_post_meta($bookPageId, 'book_id', true);
        $result['bookId'] = $bookId;
        $product = wc_get_product($bookId);
        if (!$product) {
            continue;
        }
        // Берем с товара название и ссылку на картинку
        $bookName = $product->get_name();
        $result['product'] = $product;

    }
    return $result;
}

function commentNotification($location, $comment)
{
    commentReplyNotificationAdd($comment);
    return $location;
}

// Добавляем уведомление об ответе на комментарий
add_filter('comment_post_redirect', 'commentNotification', 20, 2);

function likeNotification($ulike, $post_ID)
{
    commentLikeNotificationAdd($post_ID);
    return $ulike;
}

// Добавляем уведомление о лайке комментария
add_filter('wp_ulike_respond_for_liked_data', 'likeNotification', 20, 2);

// Помечаем прочитанными уведомления о лайках и ответах на открываемой странице
add_action('wp', function () {
    global $post;
    if (isset($post->ID)) {
        markCommentNotificationAsRead($post->ID);
    }
});

// Добавляем скрипт коллапса комментариев
wp_enqueue_script('comments-collapse-script', get_stylesheet_directory_uri() . '/inc/assets/js/comments-collapse.js', array('jquery'), '1.0.1');

/**
 * Меняет сслыку на блог/клуб в хлебных крошках записи блога/клуба
 * @param $crumbs
 * @return mixed
 */
function changeBreadcrumbLinkProduct($crumbs)
{
    if (is_product()) {
        foreach (wp_get_post_terms(get_the_id(), 'product_cat') as $term) {
            if ($term) {
                $slug = $term->slug;
                if ($slug === 'knigi-vne-cziklov') {
                    $slug = 'no-cycle';
                }
                $crumbs[1][1] = '/shop/?filter=cycle-' . $slug . '">';
                $crumbs[1][0] = $term->name;
            }
        }
    }

    return $crumbs;
}

add_filter('woocommerce_get_breadcrumb', 'changeBreadcrumbLinkProduct');

// Меняем порядок вывода комментариев - от новых к старым
add_filter('comments_template_query_args', function ($comment_args) {
    $comment_args['order'] = 'desc';
    return $comment_args;
});

// Сортируем дочерние комментарии в порядке обратном порядку родительских комментариев
// (если родительские отсортированы по убыванию времени, то дочерние будут отсортированы по возрастанию)
add_filter('comments_array', function ($comments_flat) {
    $result = [];
    $commentsCount = count($comments_flat);
    for ($i = 0; $i < $commentsCount; $i++) {
        $parentId = $comments_flat[$i]->comment_parent;
        if ($parentId == 0) {
            $result[] = $comments_flat[$i];
        } else {
            $childComments = [$comments_flat[$i]];
            for ($j = $i + 1; $j < $commentsCount; $j++) {
                if ($comments_flat[$j]->comment_parent == $parentId) {
                    $childComments[] = $comments_flat[$j];
                    $i++;
                } else {
                    break;
                }
            }
            $result = array_merge($result, array_reverse($childComments));
        }
    }
    return ($result);

});

/*
 *  Исправляем номер страницы на которой отображен комментарий
 */
add_filter('get_page_of_comment', function ($page, $args, $original_args, $comment_ID) {
    global $wpdb;
    $comment = get_comment($comment_ID);
    $comment_args = array(
        'type' => $args['type'],
        'post_id' => $comment->comment_post_ID,
        'fields' => 'ids',
        'count' => true,
        'status' => 'approve',
        'parent' => 0,
        'date_query' => array(
            array(
                'column' => "$wpdb->comments.comment_date_gmt",
                'after' => $comment->comment_date_gmt,
            ),
        ),
    );

    $comment_query = new WP_Comment_Query();
    $newer_comment_count = $comment_query->query($comment_args);

    // No newer comments? Then it's page #1.
    if (0 == $newer_comment_count) {
        $page = 1;

        // Divide comments newer than this one by comments per page to get this comment's page number
    } else {
        $page = ceil(($newer_comment_count + 1) / $args['per_page']);
    }
    return $page;
}, 20, 4);


// Добавляем в комментариях <br> после </div> и </p>, удаляем все теги кроме <br>
function me_comment_post($incoming_comment)
{
    $incoming_comment['comment_content'] = preg_replace('~<p~', "<br><p", $incoming_comment['comment_content']);
    $incoming_comment['comment_content'] = preg_replace('~<div~', "<br><div", $incoming_comment['comment_content']);

    $incoming_comment['comment_content'] = strip_tags($incoming_comment['comment_content'], '<br>');

    // the one exception is single quotes, which cannot be #039; because WordPress marks it as spam
    $incoming_comment['comment_content'] = str_replace("'", '&apos;', $incoming_comment['comment_content']);
    return ($incoming_comment);
}

function me_comment_display($comment_to_display)
{
    $comment_to_display = preg_replace('~<p~', "<br><p", $comment_to_display);
    $comment_to_display = preg_replace('~<div~', "<br><div", $comment_to_display);

    $comment_to_display = strip_tags($comment_to_display, '<br>');
    // Put the single quotes back in
    $comment_to_display = str_replace('&apos;', "'", $comment_to_display);

    return $comment_to_display;
}

add_filter('preprocess_comment', 'me_comment_post', '', 1);
add_filter('comment_text', 'me_comment_display', '', 1);
add_filter('comment_text_rss', 'me_comment_display', '', 1);
add_filter('comment_excerpt', 'me_comment_display', '', 1);

add_filter('comment_text', function ($comment_text, $comment, $args) {
    $replyTo = '';
    if ($comment->comment_parent != 0) {
        $comment = get_comment($comment->comment_parent);
        $replyTo = '<b>' . get_comment_author($comment) . '</b>, ';
    }
    return $replyTo . $comment_text;

}, 10, 3);

// Определяем шаблоны подкатегорий
add_action('category_template', 'load_cat_parent_template');
function load_cat_parent_template($template)
{
    $cat_ID = absint(get_query_var('cat'));
    $category = get_category($cat_ID);
    if ($category->category_parent > 0) {
        $templates = array();
        if (!is_wp_error($category)) {
            $templates[] = "category-{$category->slug}.php";
        }
        $templates[] = "category-$cat_ID.php";
        $parentCategory = get_category($category->category_parent);
        if (!is_wp_error($parentCategory)) {
            $templates[] = "subcategory-{$parentCategory->slug}.php";
            $templates[] = "subcategory-{$parentCategory->term_id}.php";
        }
        $templates[] = "category.php";
        $template = locate_template($templates);
    }
    return $template;
}

/**
 * Альтернатива wp_pagenavi. Создает ссылки пагинации на страницах архивов.
 *
 * @param array $args Аргументы функции
 * @param object $wp_query Объект WP_Query на основе которого строится пагинация. По умолчанию глобальная переменная $wp_query
 *
 */
function kama_pagenavi($args = array(), $wp_query = null)
{

    // параметры по умолчанию
    $default = array(
        'before' => '',   // Текст до навигации.
        'after' => '',   // Текст после навигации.
        'echo' => true, // Возвращать или выводить результат.

        'text_num_page' => '',           // Текст перед пагинацией.
        // {current} - текущая.
        // {last} - последняя (пр: 'Страница {current} из {last}' получим: "Страница 4 из 60").
        'num_pages' => 10,           // Сколько ссылок показывать.
        'step_link' => 10,           // Ссылки с шагом (если 10, то: 1,2,3...10,20,30. Ставим 0, если такие ссылки не нужны.
        'dotright_text' => '…',          // Промежуточный текст "до".
        'dotright_text2' => '…',          // Промежуточный текст "после".
        'back_text' => '« назад',    // Текст "перейти на предыдущую страницу". Ставим 0, если эта ссылка не нужна.
        'next_text' => 'вперед »',   // Текст "перейти на следующую страницу".  Ставим 0, если эта ссылка не нужна.
        'first_page_text' => '« к началу', // Текст "к первой странице".    Ставим 0, если вместо текста нужно показать номер страницы.
        'last_page_text' => 'в конец »',  // Текст "к последней странице". Ставим 0, если вместо текста нужно показать номер страницы.
    );

    // Cовместимость с v2.5: kama_pagenavi( $before = '', $after = '', $echo = true, $args = array() )
    if (($fargs = func_get_args()) && is_string($fargs[0])) {
        $default['before'] = isset($fargs[0]) ? $fargs[0] : '';
        $default['after'] = isset($fargs[1]) ? $fargs[1] : '';
        $default['echo'] = isset($fargs[2]) ? $fargs[2] : true;
        $args = isset($fargs[3]) ? $fargs[3] : array();
        $wp_query = $GLOBALS['wp_query']; // после определения $default!
    }

    if (!$wp_query) {
        wp_reset_query();
        global $wp_query;
    }

    if (!$args) $args = array();
    if ($args instanceof WP_Query) {
        $wp_query = $args;
        $args = array();
    }

    $default = apply_filters('kama_pagenavi_args', $default); // чтобы можно было установить свои значения по умолчанию

    $rg = (object)array_merge($default, $args);

    //$posts_per_page = (int) $wp_query->get('posts_per_page');
    $paged = (int)$wp_query->get('paged');
    $max_page = $wp_query->max_num_pages;

    // проверка на надобность в навигации
    if ($max_page <= 1)
        return false;

    if (empty($paged) || $paged == 0)
        $paged = 1;

    $pages_to_show = intval($rg->num_pages);
    $pages_to_show_minus_1 = $pages_to_show - 1;

    $half_page_start = floor($pages_to_show_minus_1 / 2); // сколько ссылок до текущей страницы
    $half_page_end = ceil($pages_to_show_minus_1 / 2); // сколько ссылок после текущей страницы

    $start_page = $paged - $half_page_start; // первая страница
    $end_page = $paged + $half_page_end;   // последняя страница (условно)

    if ($start_page <= 0)
        $start_page = 1;
    if (($end_page - $start_page) != $pages_to_show_minus_1)
        $end_page = $start_page + $pages_to_show_minus_1;
    if ($end_page > $max_page) {
        $start_page = $max_page - $pages_to_show_minus_1;
        $end_page = (int)$max_page;
    }

    if ($start_page <= 0)
        $start_page = 1;

    // создаем базу чтобы вызвать get_pagenum_link один раз
    $link_base = str_replace(99999999, '___', get_pagenum_link(99999999));
    $first_url = get_pagenum_link(1);
    if (false === strpos($first_url, '?'))
        $first_url = user_trailingslashit($first_url);

    // собираем елементы
    $els = array();

    if ($rg->text_num_page) {
        $rg->text_num_page = preg_replace('!{current}|{last}!', '%s', $rg->text_num_page);
        $els['pages'] = sprintf('<li><span class="pages">' . $rg->text_num_page . '</span></li>', $paged, $max_page);
    }
    // назад
    if ($rg->back_text && $paged != 1)
        $els['prev'] = '<li><a class="prev page-numbers" href="' . (($paged - 1) == 1 ? $first_url : str_replace('___', ($paged - 1), $link_base)) . '">' . $rg->back_text . '</a></li>';
    // в начало
    if ($start_page >= 2 && $pages_to_show < $max_page) {
        $els['first'] = '<li><a class="first page-numbers" href="' . $first_url . '">' . ($rg->first_page_text ?: 1) . '</a></li>';
        if ($rg->dotright_text && $start_page != 2)
            $els[] = '<li><span class="extend">' . $rg->dotright_text . '</span></li>';
    }
    // пагинация
    for ($i = $start_page; $i <= $end_page; $i++) {
        if ($i == $paged)
            $els['current'] = '<li><span aria-current="page" class="page-numbers current">' . $i . '</span></li>';
        elseif ($i == 1)
            $els[] = '<li><a class="page-numbers" href="' . $first_url . '">1</a></li>';
        else
            $els[] = '<li><a class="page-numbers" href="' . str_replace('___', $i, $link_base) . '">' . $i . '</a></li>';
    }

    // ссылки с шагом
    $dd = 0;
    if ($rg->step_link && $end_page < $max_page) {
        for ($i = $end_page + 1; $i <= $max_page; $i++) {
            if ($i % $rg->step_link == 0 && $i !== $rg->num_pages) {
                if (++$dd == 1)
                    $els[] = '<li><span class="extend">' . $rg->dotright_text2 . '</span></li>';
                $els[] = '<li><a class="page-numbers" href="' . str_replace('___', $i, $link_base) . '">' . $i . '</a></li>';
            }
        }
    }
    // в конец
    if ($end_page < $max_page) {
        if ($rg->dotright_text && $end_page != ($max_page - 1))
            $els[] = '<span class="extend">' . $rg->dotright_text2 . '</span>';
        $els['last'] = '<li><a class="last page-numbers" href="' . str_replace('___', $max_page, $link_base) . '">' . ($rg->last_page_text ?: $max_page) . '</a></li>';
    }
    // вперед
    if ($rg->next_text && $paged != $end_page)
        $els['next'] = '<li><a class="next page-numbers" href="' . str_replace('___', ($paged + 1), $link_base) . '">' . $rg->next_text . '</a></li>';

    $els = apply_filters('kama_pagenavi_elements', $els);

    $out = $rg->before . '<nav class="woocommerce-pagination"><ul class="page-numbers"><li>' . implode(' ', $els) . '</ul></nav>' . $rg->after;

    $out = apply_filters('kama_pagenavi', $out);

    if ($rg->echo) echo $out;
    else return $out;
}

// Добавляем в редактор записей кнопку "Расставить разрывы страниц"
function autoPageBreakButton()
{
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }
    if ('true' == get_user_option('rich_editing')) {
        add_filter('mce_external_plugins', 'apb_add_tinymce_plugin', 100);
        add_filter('mce_buttons', 'apb_register_mce_button', 100);
    }
}

add_action('admin_head', 'autoPageBreakButton');

// Регистрируем кнопку на панели форматирования
function apb_register_mce_button($buttons)
{
    array_push($buttons, 'apb_mce_button');
    return $buttons;
}

// Объявляем событие для нажатия кнопки
function apb_add_tinymce_plugin($plugin_array)
{
    $plugin_array['apb_mce_button'] = get_stylesheet_directory_uri() . '/inc/assets/js/insert-next-page.js?2';
    return $plugin_array;
}

add_filter('wpseo_twitter_image', 'changeTwitterImage');
add_filter('wpseo_og_og_image', 'changeOGImage');
add_filter('wpseo_og_og_image_secure_url', 'changeOGImageSecure');

function changeOGImageSecure($img)
{
    return changeOGImage($img, 'autodetect', true);
}

function changeTwitterImage($img)
{
    return changeOGImage($img, 'twitter');
}

function changeOGImage($img, $size = 'autodetect', $secure = false)
{
    global $post;
    if (isset($post->ID)) {
        $bookId = get_post_meta($post->ID, 'book_id', true);
    } else {
        $bookId = false;
    }
    if (!is_product() && !$bookId) {
        $ogUrl = WPSEO_Options::get('og_default_image');
        if ($size == 'twitter' && $ogUrl != '') {
            return $ogUrl;
        }
        return $img;
    }
    if (!extension_loaded('imagick')) {
        $ogUrl = WPSEO_Options::get('og_default_image');
        if ($size == 'twitter' && $ogUrl != '') {
            return $ogUrl;
        }
        return $img;
    }

    if ($bookId) {
        $book = wc_get_product($bookId);
        $ogTitle = $book->get_name();
        $originalImageUrl = wp_get_attachment_url(get_post_thumbnail_id($book->get_id()));

    } else {
        global $product;
        $ogTitle = get_the_title();
        $originalImageUrl = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
    }

    $uploads = wp_upload_dir();
    $file_path = str_replace($uploads['baseurl'], $uploads['basedir'], $originalImageUrl);
    require_once __DIR__ . '/evaSocialImgGenerator/evaSocialImgGenerator.php';
    require_once __DIR__ . '/evaSocialImgGenerator/evaSocialImgTextGenerator.php';
    $authorGenerator = new imgTextGenerator();
    $social = 'vk';
    if ($size == "autodetect") {
        $social = imgGenerator::getSocial();
    }
    $authorTextPadding = ["15%", "0%", "0%", "45%"];
    $titleTextPadding = ["30%", "5%", "0%", "45%"];
    if ($social == 'vk') {
        $authorTextPadding = ["15%", "0%", "0%", "37%"];
        $titleTextPadding = ["30%", "5%", "0%", "37%"];
    }

    $author = $authorGenerator
        ->setCaptionPosition(imgGenerator::position_left_center)
        ->seTextShadow('#000000', 75, 1, 2, 2)
        ->setText('Марина Эльденберт', "#ffffff", imgGenerator::position_left_top, "1/15", $authorTextPadding)
        ->setFont($_SERVER["DOCUMENT_ROOT"] . '/wp-content/themes/storefront-child/inc/assets/fonts/Robotoslabregular.ttf');
    $titleGenerator = new imgTextGenerator();
    $title = $titleGenerator
        ->setCaptionPosition(imgGenerator::position_left_center)
        ->seTextShadow('#000000', 70, 1, 2, 2)
        ->setText($ogTitle, "#ffffff", imgGenerator::position_left_top, "1/8", $titleTextPadding)
        ->setLinesBeforeTrim(3)
        ->setFont($_SERVER["DOCUMENT_ROOT"] . '/wp-content/themes/storefront-child/inc/assets/fonts/Robotoslabregular.ttf');
    $generator = new imgGenerator();
    $path = $generator
        ->enableCache($uploads['basedir'])
        ->addText($author)
        ->addText($title)
        ->addOverlay(0.3, '#000000')
        ->setLogo($file_path, imgGenerator::position_left_bottom, ["10%", "0%", "10%", "5%",], 'auto')
        ->fromImg($file_path)
        ->resizeFor($size)
        ->getPath();
    $finalUrl = preg_replace('~^(.){0,}/wp-content/uploads~', $uploads['baseurl'], $path);
    if ($secure) {
        $finalUrl = preg_replace('~http:~', 'https:', $finalUrl);
    }
    return $finalUrl;
}

add_filter('wpseo_og_og_image_width', function ($width) {
    if (!is_product()) {
        return $width;
    }
    if (!extension_loaded('imagick')) {
        return $width;
    }
    require_once __DIR__ . '/evaSocialImgGenerator/evaSocialImgGenerator.php';
    return imgGenerator::getWidth();
});

add_filter('wpseo_og_og_image_height', function ($height) {
    if (!is_product()) {
        return $height;
    }
    if (!extension_loaded('imagick')) {
        return $height;
    }
    require_once __DIR__ . '/evaSocialImgGenerator/evaSocialImgGenerator.php';
    return imgGenerator::getHeight();
});

add_action('get_header', function () {
    global $post;
    if (is_user_logged_in() && !is_null($post->ID)) {
        markArticleNotificationAsRead($post->ID);
    }
});

function tt_hidetitle_class($classes)
{

    if (is_account_page()):

        $classes[] = 'hide-title';

        return $classes;

    endif;

    return $classes;

}

add_filter('post_class', 'tt_hidetitle_class');