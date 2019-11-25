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
    echo '<link rel="shortcut icon" type="image/x-icon" href="/wp-content/themes/storefront-child/favicon.ico" />' . "\n";
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
add_filter('woocommerce_output_related_products_args', 'jk_related_products_args');
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
    $prevArticleButton = articleButton($articleId, 'prev');
    $nextArticleButton = articleButton($articleId, 'next');
    $query = new WP_Query('p=' . $articleId);
    $bookId = get_post_meta($post->ID, 'book_id', true);
    $content = '';
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
                $GLOBALS['isArticle'] = false;
                wp_reset_query();
                return;
            }
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
            echo $prevArticleButton;
            echo $nextArticleButton;
            echo '</ul>';
            wp_custom_link_pages(array(
                'before' => '<nav><ul class="pagination mb-4 mt-3 pb-0" data-pages="' . $numpages . '">',
                'after' => '</ul></nav>',
                'link_before' => '<span>',
                'link_after' => '</span>',
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
            ));
            echo '<ul class="article-btns pagination mt-3 pb-0">';
            echo $prevArticleButton;
            echo $nextArticleButton;
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
    if ($multipage) {
        if ('number' == $r['next_or_number']) {
            $output .= $r['before'];
            if ($numpages > 1) {
                $output .= '<li class="page-item">
                    <a class="page-link prev-page-btn" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>';
            }
            $mobileVisiblePages = [1, 2, 3, 4, 5];
            if ($numpages > 5) {
                if ($page > $numpages - 2) {
                    $mobileVisiblePages = [];
                    for ($i = $numpages; $i > $numpages - 5; $i--) {
                        $mobileVisiblePages[] = $i;
                    }
                } elseif ($page > 3) {
                    $mobileVisiblePages = [];
                    for ($i = $page - 2; $i <= $page + 2; $i++) {
                        $mobileVisiblePages[] = $i;
                    }
                }
            }
            for ($i = 1; $i <= $numpages; $i++) {
                $nearestClass = '';
                if (in_array($i, $mobileVisiblePages)) {
                    $nearestClass = ' mobile-visible';
                }
                $activeClass = ($i == $page) ? ' active' : '';

                $link = '<li class="page-item' . $activeClass . $nearestClass . '"><a class="post-page-numbers page-link" data-page="' . $i . '">' . $i . '</a></li>';
                $output .= $link;
            }
            if ($numpages > 1) {
                $output .= '<li class="page-item">
                    <a class="page-link next-page-btn" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>';
            }
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
    $isForAdult = get_post_meta($post->ID, '18+', true);

    if ($isForAdult != 1 || is_user_logged_in() || (isset($_COOKIE['adult']) && $_COOKIE['adult'] == 1)) {
        return;
    }
    ?>
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
            if ($isFree || isBookBought($bookId) || hasAbonement(get_current_user_id()) || isAdmin()) {
                if ($currentArticle > 0 && $currentArticle == $post->ID) {
                    echo '<p class="active-title">' . $post->post_title . '</p>';

                } else {
                    echo '<p><a href="' . $baseUrl . '?a=' . $post->ID . '">' . $post->post_title . '</a></p>';
                }
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
                    <div class="mb-3 ml-5">
                        <a class="download-link mb-3" href="<?php $download['download_url'] ?>">Скачать в
                            формате <?php echo $download['file']['name'] ?></a>
                    </div>
                    <?php
                    $hasDownloads = true;
                }
            }
        }

        if (!$hasDownloads && $product->get_status() == 'publish') { ?>
            <a href="<?php echo $product->get_permalink(); ?>">Купить</a>
            <?php
        } elseif (!$hasDownloads && $product->get_status() == 'pending') { ?>
            <p>Книга еще не вышла</p>
            <?php
        } ?>
    </div>
    <?php
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
 * Добавляем в визуальный редактор кнопку вставки тега nextpage - разрыв страницы
 *
 */
function my_add_next_page_button($buttons, $id)
{

    /* only add this for content editor */
    if ('content' != $id)
        return $buttons;

    /* add next page after more tag button */
    array_splice($buttons, 13, 0, 'wp_page');

    return $buttons;
}

/**
 * Замена стандартных крошек от вукомерса
 */
add_filter('woocommerce_breadcrumb_defaults', 'jk_woocommerce_breadcrumbs', 20);
function jk_woocommerce_breadcrumbs()
{
    return array(
        'delimiter' => ' / ',
        'wrap_before' => '<nav class="woocommerce-breadcrumb container breadcrumb-container" itemprop="breadcrumb">',
        'wrap_after' => '</nav>',
        'before' => '',
        'after' => '',
        'home' => _x('Home', 'breadcrumb', 'woocommerce'),
    );
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
// Получаем элементы таксономии атрибута
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
            echo '<a href="/author-book/' . $attribute_name->slug . '/">';
            echo $attribute_name->name;
            echo '</a> ';
        endforeach;
        echo '</p>';
    }
}

// Определяем место вывода атрибута
add_action('woocommerce_single_product_summary', 'productAuthor', 15);

// Функция вывода атрибута
function productSeries()
{
    global $product;
// Получаем элементы таксономии атрибута
    $attribute_names = get_the_terms($product->get_id(), 'pa_series-book');
    $attribute_name = "Цикл: ";
    if ($attribute_names) {
// Вывод имени атрибута
        echo '<p class="attr-label">';
        echo wc_attribute_label($attribute_name);

// Выборка значения заданного атрибута
        foreach ($attribute_names as $attribute_name):
// Вывод значений атрибута
            echo '<a href="/shop/?series=' . $attribute_name->slug . '/">';
            echo $attribute_name->name;
            echo '</a>';
            echo '</p>';
            break;
        endforeach;
    }
}

// Определяем место вывода атрибута
add_action('woocommerce_single_product_summary', 'productSeries', 15);

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
                if (in_array($bookCategoryId, $articleCategories)) {
                    setcookie('b_' . $bookId, $articleId, strtotime('+1 year'), '/');
                    setBookmarkMeta($bookId, $articleId);
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
        wp_enqueue_script('isotope', 'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js', array('jquery'));
        wp_enqueue_script('filter-script', get_stylesheet_directory_uri() . '/inc/assets/js/filter.js', array('jquery', 'isotope'));

    }
}

/**
 * Выводит фильтр товаров
 */
function addFilterBar()
{
    $bookTypeFilters = [
        'free-books',
        'paper-books',
        'audio-books',
    ];
    $otherFilters = [
        'new',
        'bestseller',
        'pre-order'
    ];

    $tags = get_terms('product_tag');
    $series = get_terms('pa_series-book');
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
            <button class="button clear-filters" data-filter="*"><i class="fas fa-times mr-2"></i> Сбросить фильтры
            </button>
            <div class="filter-button-group">
                <div class="button-group mb-5" data-filter-group="type">
                    <?php foreach ($bookTypeFilters as $filter): ?>
                        <?php if (!key_exists($filter, $nonEmptyTags)) {
                            continue;
                        } ?>
                        <button class="button filter-btn"
                                data-filter=".product_tag-<?php echo $filter ?>"><?php echo $nonEmptyTags[$filter] ?></button>
                    <?php endforeach; ?>
                </div>
                <div class="button-group mb-5" data-filter-group="other">
                    <?php foreach ($otherFilters as $filter): ?>
                        <?php if (!key_exists($filter, $nonEmptyTags)) {
                            continue;
                        } ?>
                        <button class="button filter-btn"
                                data-filter=".product_tag-<?php echo $filter ?>"><?php echo $nonEmptyTags[$filter] ?></button>
                    <?php endforeach; ?>
                </div>
                <div class="button-group" data-filter-group="category">
                    <button class="button filter-btn"
                            data-filter=".series-no-series">Книги вне циклов
                    </button>
                    <?php foreach ($series as $ser): ?>
                        <button class="button filter-btn"
                                data-filter=".series-<?php echo $ser->slug ?>"><?php echo $ser->name ?></button>
                    <?php endforeach; ?>
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
    if ('div' === $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li ';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo esc_attr($tag); ?><?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">
    <div class="comment-body">
    <div class="comment-meta commentmetadata">
        <div class="comment-author vcard">
            <div class="avatar-status-box position-relative">
                <?php echo get_avatar($comment, 100); ?>
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
            <?php if ($comment->comment_parent != 0):
                $comment = get_comment($comment->comment_parent);
                $comment_text = get_comment_text($comment);
                ?>
                <div class="quote-comment">
                    <?php echo $comment_text; ?>
                </div>
            <?php endif; ?>
            <?php comment_text(); ?>
        </div>
        <div class="d-flex justify-content-between">
            <div>
                <?php wp_ulike_comments(); ?>
                <?php
                comment_reply_link(
                    array_merge(
                        $args, array(
                            'add_below' => $add_below,
                            'depth' => $depth,
                            'max_depth' => $args['max_depth'],
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
                <?php echo get_avatar($comment, 100); ?>
                <?php echo do_shortcode('[mycred_my_rank user_id=' . $comment->user_id . ' show_title=0 show_logo=1 logo_size="rank"]'); ?>
            </div>
            <div class="text-center">
                <?php printf(wp_kses_post('<cite class="comment-body__author fn">%s</cite>', 'storefront'), get_comment_author_link()); ?>
                <cite><?php echo do_shortcode('[mycred_my_rank user_id=' . $comment->user_id . ' show_title=1 show_logo=0]'); ?></cite>
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
            <?php if ($comment->comment_parent != 0):
                $comment = get_comment($comment->comment_parent);
                $comment_text = get_comment_text($comment);
                ?>
                <div class="quote-comment">
                    <?php echo $comment_text; ?>
                </div>
            <?php endif; ?>
            <?php comment_text(); ?>
        </div>
        <div class="d-flex justify-content-between">
            <div>
                <?php wp_ulike_comments(); ?>
                <?php
                comment_reply_link(
                    array_merge(
                        $args, array(
                            'add_below' => $add_below,
                            'depth' => $depth,
                            'max_depth' => $args['max_depth'],
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
add_filter('post_class', 'addSeriesToClass');
function addSeriesToClass($args)
{
    if (!is_shop()) {
        return $args;
    }
    global $product;
    $seriesTerms = get_the_terms($product->get_id(), 'pa_series-book');
    if (!$seriesTerms) {
        return ['series-no-series'];
    }
    $series = [];
    foreach ($seriesTerms as $seriesTerm) {
        $series[] = 'series-' . $seriesTerm->slug;
    }
    $result = array_merge($args, $series);
    return $result;
}

;


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
    //Скидки в зависимости от статуса
    $statusDiscounts = [
        'metal-dragon' => 0,
        'copper-dragon' => 3,
        'bronze-dragon' => 5,
        'silver-dragon' => 10,
        'golden-dragon' => 15,
        'platinum-dragon' => 20,
    ];
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
    if (key_exists($rankSlug, $statusDiscounts) && $statusDiscounts[$rankSlug] != 0) {

        $discount = $cart->subtotal * $statusDiscounts[$rankSlug] / 100;
        // Название ранга
        $rankName = getRankTitle($rank_object, true);
        // Текст, выводимый в корзине
        $feeText = $rankName . ' - скидка ' . $statusDiscounts[$rankSlug] . '%';
        $cart->add_fee($feeText, -$discount);
    }
}

add_action("woocommerce_cart_calculate_fees", "rank_discount_total");

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
    if ($newRank->post->post_name = 'platinum-dragon') {
        update_user_meta($user_id, 'vipStatus', true);
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
    $status = '';
    $abonementUntil = hasAbonement($user_id);
    ob_start();
    if ($column_name == 'VIP') {
        if (get_user_meta($user_id, 'vipStatus', true)) {
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
    $doesNewUserGetVipStatus = get_option('vipForNewUsers', false);
    if ($doesNewUserGetVipStatus) {
        echo '<p><strong>Сейчас все новые пользователи получают статус Платиновая драконесса</strong></p>';
    } else {
        echo '<p>Включить присвоение статуса Платиновая драконесса всем новым пользователям?</p>';
    }
    ?>
    <form method="post">
        <input type="hidden" name="vipForNewUsers" value="<?php echo ($doesNewUserGetVipStatus) ? 0 : 1 ?>">
        <button class="button" type="submit"><?php echo ($doesNewUserGetVipStatus) ? 'Выключить' : 'Включить' ?>
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
        if (intval($_POST['vipForNewUsers']) === 1) {
            update_option('vipForNewUsers', true);
        } elseif (intval($_POST['vipForNewUsers']) === 0) {
            update_option('vipForNewUsers', false);
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
    $doesNewUserGetVipStatus = get_option('vipForNewUsers', false);
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
    echo 12345;
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
    ?>
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
function is_product_in_cart() {
    foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
        $cart_product = $values['data'];

        if( get_the_ID() == $cart_product->id ) {
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
function storefront_sticky_single_add_to_cart() {
    global $product;

    if ( class_exists( 'Storefront_Sticky_Add_to_Cart' ) || true !== get_theme_mod( 'storefront_sticky_add_to_cart' ) ) {
        return;
    }

    if ( ! is_product() ) {
        return;
    }

    $show = false;

    if ( $product->is_purchasable() && $product->is_in_stock() ) {
        $show = true;
    } else if ( $product->is_type( 'external' ) ) {
        $show = true;
    }

    if ( ! $show ) {
        return;
    }

    $params = apply_filters(
        'storefront_sticky_add_to_cart_params', array(
            'trigger_class' => 'entry-summary',
        )
    );

    wp_localize_script( 'storefront-sticky-add-to-cart', 'storefront_sticky_add_to_cart_params', $params );

    wp_enqueue_script( 'storefront-sticky-add-to-cart' );
    ?>
    <section class="storefront-sticky-add-to-cart">
        <div class="col-full">
            <div class="storefront-sticky-add-to-cart__content">
                <?php echo wp_kses_post( woocommerce_get_product_thumbnail() ); ?>
                <div class="storefront-sticky-add-to-cart__content-product-info">
                    <span class="storefront-sticky-add-to-cart__content-title"><?php esc_attr_e( 'You\'re viewing:', 'storefront' ); ?> <strong><?php the_title(); ?></strong></span>
                    <span class="storefront-sticky-add-to-cart__content-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
                    <?php echo wp_kses_post( wc_get_rating_html( $product->get_average_rating() ) ); ?>
                </div>
                <?php if (is_product_in_cart()): ?>
                    <a href="<?php echo get_permalink( wc_get_page_id( 'cart' ) ); ?>" class="button">Товар в корзине</a>
                <?php
                else: ?>
                    <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="storefront-sticky-add-to-cart__content-button button alt">
                        <?php echo esc_attr( $product->add_to_cart_text() ); ?>
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
function articleButton($currentArticleId, $direction)
{
    global $post;
    $baseUrl = get_permalink();
    $bookId = get_post_meta($post->ID, 'book_id', true);
    $query = new WP_Query('p=' . $currentArticleId);
    $currentArticleDate = null;
    $content = '';
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $currentArticleDate = $post->post_date;
        }
    }
    wp_reset_query();

    if (is_null($currentArticleDate)) {
        return '';
    }
    if ($direction == 'next') {
        $order = 'asc';
        $dateQuery = [
            'after' => $currentArticleDate,
        ];
        $text = 'След. глава';
    } elseif ($direction == 'prev') {
        $order = 'desc';
        $dateQuery = [
            'before' => $currentArticleDate,
        ];
        $text = 'Пред. глава';
    } else {
        return '';
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
            if ($isFree || isBookBought($bookId) || hasAbonement(get_current_user_id()) || isAdmin()) {
                $content = articleButtonHtml($baseUrl . '?a=' . $post->ID, $text);
            }
        }
    }
    wp_reset_query();
    return $content;
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
    <li class="page-item mobile-visible"><a href="<?php echo $url?>" class="page-link next-page-btn" aria-label="Next">
        <span aria-hidden="true"><?php echo $text?></span>
    </a>
    </li>
    <?php
    $content = ob_get_clean();
    return $content;
}

//отключение магнифика для плагина комментов
function cir_js_file(){

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if ( wp_script_is('comment-images-reloaded') && is_plugin_active( 'comment-images-reloaded/comment-image-reloaded.php' ) ) {

        wp_dequeue_script( 'comment-images-reloaded' );
        wp_enqueue_script( 'my-comment-images-reloaded', plugins_url( 'comment-images-reloaded/js/cir.min.js' ), array( 'jquery' ), false, true );
        wp_dequeue_style( 'magnific' );
    }
}
add_action( 'wp_enqueue_scripts', 'cir_js_file', 999 );


/**
 * Добавление библиотек на странице иллюстраций
 */
add_action('wp_enqueue_scripts', 'add_cdn_images');

function add_cdn_images()
{
    global $post;
    if ($post->ID == 35) {
        wp_enqueue_script( 'fancybox-script', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js', array('jquery'));
        wp_enqueue_script( 'maconry-script', '/wp-content/themes/storefront-child/inc/assets/js/masonry.pkgd.min.js', array('jquery'));
        wp_enqueue_style( 'fancybox-style', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css' );
    }
}

// Изменяем название Детали на Настройки, убираем из меню пункт Адреса
add_filter('woocommerce_account_menu_items', function ($args) {
    if (key_exists('edit-account', $args)) {
        $args['edit-account'] = 'Настройки';
    }
    if (key_exists('edit-address', $args)) {
        unset($args['edit-address']);
    }
    return $args;
});
