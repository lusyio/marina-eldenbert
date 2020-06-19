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
    wp_enqueue_script('wp-bootstrap-starter-themejs', get_stylesheet_directory_uri() . '/inc/assets/js/theme-script.min.js', array(), '1', true);
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
            $book = wc_get_product($bookId);
            $bookPrice = $book->get_price();
            if ($bookPrice > 0 && !$isFree && !isBookBought($bookId) && !(is_user_logged_in() && hasAbonement(get_current_user_id())) && !isAdmin()) {
                $GLOBALS['showBuyScreen'] = true;
                wp_reset_query();
                return;
            }
            setBookmarkMeta($bookId, $articleId);
            $inOnePage = false;
            if (isset($_GET['op']) && $_GET['op'] == 0) {
                $inOnePage = false;
                setcookie('op', 0, strtotime('+1 year'), '/');
            } elseif (isset($_COOKIE['op']) && $_COOKIE['op'] == 1) {
                $inOnePage = true;
            } elseif (isset($_GET['op']) && $_GET['op'] == 1) {
                $inOnePage = true;
                setcookie('op', 1, strtotime('+1 year'), '/');
            }
            global $numpages;
            $pageToLoad = 1;
            $hasBookmarkPage = isset($_GET['p']) && $_GET['p'] == 'bookmark';
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

            if ($pageToLoad > $numpages || $pageToLoad < 1 || !$hasBookmarkPage) {
                setBookmarkPageMeta($articleId, 1);
                $pageToLoad = 1;
            }
            $GLOBALS['page'] = $pageToLoad;
            ?>
            <p class="h3 d-flex justify-content-between reader-h3"><?php the_title(); ?> <img class=""
                                                                                              data-toggle="modal"
                                                                                              id="settingsModalTrigger"
                                                                                              data-target="#settingsModal"
                                                                                              src="/wp-content/themes/storefront-child/svg/svg-settings.svg"
                                                                                              alt="settings"></p>
            <?php
            $paginationArgs = array(
                'before' => '<nav><ul class="reader-pagination pagination mb-4 mt-3 pb-0" data-pages="' . $numpages . '">',
                'after' => '</ul></nav>',
                'link_before' => '<span>',
                'link_after' => '</span>',
                'prev_article_id' => $prevArticleId,
                'next_article_id' => $nextArticleId,
                'base_url' => $baseUrl,
            );
            echo '<ul class="article-btns pagination mb-3 mt-3 pb-0">';
            echo '</ul>';
            if ($inOnePage) {
                if (isset($GLOBALS['numpages']) && $GLOBALS['numpages'] > 1) { ?>
                    <a id="reader-display-option" href="<?php echo addOrUpdateUrlParam('op', 0); ?>">
                        <span aria-hidden="true">Читать главу постранично</span>
                    </a>
                    <?php
                }
                wp_custom_link_articles($paginationArgs);
            } else {
                if (isset($GLOBALS['numpages']) && $GLOBALS['numpages'] > 1) {
                    ?>
                    <a id="reader-display-option" href="<?php echo addOrUpdateUrlParam('op', 1); ?>">
                        <span aria-hidden="true">Читать главу целиком</span>
                    </a>
                    <?php
                }
                wp_custom_link_pages($paginationArgs);
            }
            ?>
            <div id="articleText">
                <?php if ($inOnePage) {
                    $pages = get_pages();
                    for ($i = 1; $i <= $numpages; $i++) {
                        $GLOBALS['page'] = $i;
                        the_content();
                    }
                } else {
                    the_content();
                } ?>
            </div>
            <div id="articleSpinner">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <?php
            if ($inOnePage) {
                wp_custom_link_articles($paginationArgs);
            } else {
                wp_custom_link_pages($paginationArgs);
            }
            echo '<ul class="article-btns pagination mt-3 pb-0">';
            echo '</ul>';
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
                $firstDotsClass = ($page <= 3) ? ' d-none' : '';
                $lastDotsClass = ($page >= $numpages - 2) ? ' d-none' : '';
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

function wp_custom_link_articles($args = '')
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
            $prevText = $prevArticleText;
            if ($prevArticleId == 0) {
                $prevPageClass .= ' d-none';
            }

            $output .= '<li class="page-item' . $prevPageClass . '">
                    <a href="' . $params['base_url'] . '?a=' . $prevArticleId . '" data-article-id="' . $prevArticleId . '" data-for-page="' . $prevPageText . '" data-for-article="' . $prevArticleText . '" class="page-link prev-page-btn" aria-label="Previous">
                        <span aria-hidden="true">' . $prevText . '</span>
                    </a>
                </li>';

            if ($params['next_article_id']) {
                $nextArticleId = $params['next_article_id'];
            } else {
                $nextArticleId = 0;
            }
            $nextPageClass = '';
            $nextText = $nextArticleText;
            if ($nextArticleId == 0) {
                $nextPageClass .= ' d-none';
            }
            $output .= '<li class="page-item' . $nextPageClass . '">
                <a href="' . $params['base_url'] . '?a=' . $nextArticleId . '" data-article-id="' . $nextArticleId . '" data-for-page="' . $nextPageText . '" data-for-article="' . $nextArticleText . '" class="page-link next-page-btn" aria-label="Next">
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
if (is_user_logged_in() && !is_admin()) {
    wp_enqueue_script('notification-script', get_stylesheet_directory_uri() . '/inc/assets/js/notifications.js', array('jquery'), '1');
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
$pagination_js_ver = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . '/inc/assets/js/pagination.js'));
wp_enqueue_script('pagination-script', get_stylesheet_directory_uri() . '/inc/assets/js/pagination.js', array('jquery'), $pagination_js_ver);

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
    $book = wc_get_product($bookId);
    $bookPrice = $book->get_price();

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
            $isFree = $bookPrice == 0;
            if (!$isFree && is_array($tags)) {
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
    // выводим сылки на скачивание книги
    $downloads = array();
    $user_id = get_current_user_id();
// Если цена 0 то вывести разрешения пользователя 0
    if ($product->get_price() == 0) {
        $allDownloads = wc_get_free_downloads();
    } else {
        $allDownloads = wc_get_customer_available_downloads(get_current_user_id());
    }
    $downloads = [];
    foreach ($allDownloads as $oneDownload) {
        if ($oneDownload['product_id'] == $product->get_id()) {
            $downloads[] = $oneDownload;
        }
    }
    ?>
    <div class="text-center"><img src="<?php echo wp_get_attachment_url($product->get_image_id()); ?>"/></div>
    <div class="text-center"><p class="h3"><?php echo $product->get_name() ?></p>
        <?php
        $hasDownloads = false;
        if (!empty($downloads)) {
            foreach ($downloads as $download) {
                ?>
                <div>
                    <a class=" mb-3" href="<?php echo $download['download_url'] ?>">Скачать в
                        формате <?php echo $download['file']['name'] ?></a>
                </div>
                <?php
                $hasDownloads = true;
            }
        }

        if (!$hasDownloads && $product->get_status() == 'publish') {
            if ($product->get_price() == 0 || isBookBought($product->get_id())): ?>
                <a href="<?php echo $product->get_permalink(); ?>">Подробнее</a>
            <?php else: ?>
                <a href="<?php echo get_site_url(); ?>/checkout/?add-to-cart=<?php echo $product->get_id(); ?>">Купить</a>
            <?php endif;
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
 * @param bool $baseUrl
 * @param bool $id
 * @param bool $class
 */
function readButton($baseUrl = false, $id = false, $class = false)
{
    global $post;

    if ($id) {
        $bookId = strval($id);
    } else {
        $bookId = get_post_meta($post->ID, 'book_id', true);
    }

    if (!$baseUrl) {
        $baseUrl = get_permalink();
    }

    if (!$class) {
        $classRead = 'load-more';
        $classContinue = 'club-header__btn';
    } else {
        $classRead = $class;
        $classContinue = $class;
    }

    $lastBookmark = getBookmarkMeta($bookId);
    if ($lastBookmark) {
        echo '<a class="' . $classContinue . '" href="' . $baseUrl . '?a=' . $lastBookmark . '&p=bookmark">Продолжить чтение</a>';
        return;
    } elseif (isset($_COOKIE['b_' . $bookId])) {
        echo '<a class="' . $classContinue . '" href="' . $baseUrl . '?a=' . $_COOKIE['b_' . $bookId] . '&p=bookmark">Продолжить чтение</a>';
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
        if (!$id) {
            echo '<hr>';
        }
        while ($query->have_posts()) {
            $query->the_post();
            if ($id) {
                echo '<a class="' . $classRead . '" href="' . $baseUrl . '">Читать</a>';
            } else {
                echo '<a class="' . $classRead . '" href="' . $baseUrl . '?a=' . $post->ID . '">Читать</a>';
            }
            if ($id) {
                break;
            }
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
        echo '<p class="info-card__meta-cycle">';
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
        echo '<p class="info-card__meta-series">';
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
    if (isset($category->slug)) {
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


add_action('wp_enqueue_scripts', 'addLibraryScript', 10);
/**
 * Добавляет скрипты изотопа и работы с изотопом
 */
function addLibraryScript()
{
    if (is_account_page()) {
        wp_enqueue_script('library', get_bloginfo('stylesheet_directory') . '/inc/assets/js/library.js', array('jquery'), false, true);
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
            if (in_array($user_id, [9, 10])) {
                $show[] = 'Автор';
            } elseif ($userSex == 'male' && count($titles) > 1) {
                $show[] = $titles[1];
            } else {
                $show[] = $titles[0];
            }
        }
        if ($first != 'logo')
            $show = array_reverse($show);

    }

    if (!empty($show))
        if (in_array($user_id, [9, 10])) {
            $content = '<div class="mycred-my-rank rank-author">' . implode(' ', $show) . '</div>';
        } else {
            $content = '<div class="mycred-my-rank">' . implode(' ', $show) . '</div>';
        }

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
                <!--                --><?php //if (is_product_in_cart()):
                ?>
                <!--                    <a href="--><?php //echo get_permalink(wc_get_page_id('cart'));
                ?><!--"-->
                <!--                       class="single_add_to_cart_button button alt">Товар в корзине</a>-->
                <!--                --><?php
                //                else:
                ?>
                <a href="<?php echo esc_url($product->add_to_cart_url()); ?>"
                   class="storefront-sticky-add-to-cart__content-button button alt">
                    <?php echo esc_attr($product->add_to_cart_text()); ?>
                </a>
                <!--                --><?php //endif;
                ?>
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
    wp_enqueue_script('swiper-js', '/wp-content/themes/storefront-child/inc/assets/js/swiper.js', array(), '1', true);
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
                                    <?= get_the_post_thumbnail(null, [300, 300]) ?>
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
//add_action('init', 'createNotificationTable');
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
function article_send_notification($new_status, $old_status, WP_Post $post)
{
    if ($new_status === 'publish' && $post->post_type === 'post') {
        if ($old_status === $new_status) {
            if (isset($GLOBALS['beforeEdit'])) {
                $beforeWithoutTags = preg_replace('~<.+>~U', '', $GLOBALS['beforeEdit']);
                $afterWithoutTags = preg_replace('~<.+>~U', '', $post->post_content);
                if (abs(mb_strlen($beforeWithoutTags) - mb_strlen($afterWithoutTags)) >= 1000) {
                    updateArticleNotificationAdd($post->ID);
                }
            }
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

    if (isset($_POST['notificationEnabled']) && $_POST['notificationEnabled'] === 'on') {
        if (isset($_POST['notificationType'])) {
            if (in_array($_POST['notificationType'], ['subscribe_open', 'book_finish', 'sale_open'])) {
                newPostNotificationAdd($post->ID, $_POST['notificationType']);
            }
        }
    }
}

add_action('transition_post_status', 'article_send_notification', 10, 3);

function newPostNotificationAdd($postId, $type)
{
    $possibleTypes = ['news', 'announcement', 'new_book', 'subscribe_open', 'book_finish', 'sale_open'];
    if (!in_array($type, $possibleTypes)) {
        return;
    }
    if (in_array($type, ['news', 'announcement', 'new_book'])) {
        $users = get_users(['fields' => ['ID']]); // Отправляем всем
    } elseif ($type == 'subscribe_open') {
        $users = getUserIdsWithBookInLibrary($postId); // Отправляем тем у кого в библиотеке
    } elseif ($type == 'book_finish') {
        $users = array_merge(getUserIdsWithBookInLibrary($postId), getCustomerIdsWhoBoughtBook($postId)); // у кого в библиотеке или куплена
        $users = array_unique($users);
    } elseif ($type == 'sale_open') {
        $users = array_diff(getUserIdsWithBookInLibrary($postId), getCustomerIdsWhoBoughtBook($postId)); // у кого в библиотеке, кроме тех у кого куплена
        $users = array_unique($users);
    }
    global $wpdb;
    $table_name = $wpdb->get_blog_prefix() . 'me_notifications';
    foreach ($users as $user) {
        if (in_array($type, ['news', 'announcement', 'new_book'])) {
            $user = $user->ID;
        }
        $wpdb->get_row($wpdb->prepare("INSERT INTO {$table_name} (user_id, notification_type, article_page_id, notification_date) VALUES (%d, %s, %d, NOW());", $user, $type, $postId));
    }
}

//Запись уведомления о добавлении главы
function newArticleNotificationAdd($articlePageId)
{
    $bookData = getBookInfoByArticleId($articlePageId);
    if (!is_array($bookData) || count($bookData) == 0) {
        return;
    }
    $userIds = getUserIdsWithBookInLibrary($bookData['bookId']);
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
    $userIds = getUserIdsWithBookInLibrary($bookData['bookId']);
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
        $icon = 'wp-content/themes/storefront-child/svg/book-new.svg';
        $isValid = true;
    } elseif ($type == 'news' || $type == 'announcement') {
        $link = get_permalink($notification->article_page_id);
        $post = get_post($notification->article_page_id);
        if ($type == 'news') {
            $content = 'Добавлена новость - ' . $post->post_title;
            $icon = 'wp-content/themes/storefront-child/svg/newspaper.svg';
        } else {
            $content = 'Новый анонс - ' . $post->post_title;
            $icon = 'wp-content/themes/storefront-child/svg/guide.svg';
        }
        $isValid = true;
    } elseif ($type == 'new_article') {
        $bookData = getBookInfoByArticleId($notification->article_page_id);
        if (count($bookData) == 0) {
            return '';
        }
        $lastBookmark = getBookmarkMeta($bookData['bookId']);
        if ($lastBookmark) {
            $link = get_permalink($bookData['bookPageId']) . '?a=' . $lastBookmark . '&p=bookmark';
        } elseif (isset($_COOKIE['b_' . $bookData['bookId']])) {
            $link = get_permalink($bookData['bookPageId']) . '?a=' . $_COOKIE['b_' . $bookData['bookId']] . '&p=bookmark';
        } else {
            $link = $bookData['bookLink'];
        }
        $bookName = $bookData['product']->get_name();
        $content = 'Новая глава в книге "' . $bookName . '"';
        $icon = 'wp-content/themes/storefront-child/svg/svg-bookNewChapter.svg';
        $isValid = true;
    } elseif ($type == 'update_article') {
        $bookData = getBookInfoByArticleId($notification->article_page_id);
        if (count($bookData) == 0) {
            return '';
        }
        $link = $bookData['bookLink'];
        $lastBookmark = getBookmarkMeta($bookData['bookId']);
        $isMatchWithBookmark = $lastBookmark == $notification->article_page_id;
        $isMatchWithCookieBookmark = isset($_COOKIE['b_' . $bookData['bookId']]) && $_COOKIE['b_' . $bookData['bookId']] == $notification->article_page_id;
        if ($isMatchWithBookmark || $isMatchWithCookieBookmark) {
            $link = get_permalink($bookData['bookPageId']) . '?a=' . $notification->article_page_id . '&p=bookmark';
        } else {
            $link = get_permalink($bookData['bookPageId']) . '?a=' . $notification->article_page_id;
        }
        $bookName = $bookData['product']->get_name();
        $content = 'Обновление главы в книге "' . $bookName . '"';
        $icon = 'wp-content/themes/storefront-child/svg/book-update.svg';
        $isValid = true;
    } elseif ($type == 'subscribe_open' || $type == 'sale_open') {
        $post = get_post($notification->article_page_id);
        $link = get_permalink($notification->article_page_id);
        $bookName = $post->post_title;
        if ($type == 'subscribe_open') {
            $content = 'Открылась подписка на книгу "' . $bookName . '"';
            $icon = 'wp-content/themes/storefront-child/svg/book-sell.svg';
        } else {
            $content = 'Открылась продажа книги "' . $bookName . '"';
            $icon = 'wp-content/themes/storefront-child/svg/book-sell.svg';
        }
        $isValid = true;
    } elseif ($type == 'book_finish') {
        $bookPageArgs = [
            'numberposts' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => 'page',
            'post_status' => 'publish',
            'meta_key' => 'book_id',
            'meta_value' => $notification->article_page_id,
        ];
        $post = get_post($notification->article_page_id);
        $bookName = $post->post_title;
        $content = 'Книга "' . $bookName . '" завершена';
        $icon = 'wp-content/themes/storefront-child/svg/book-finish.svg';
        $bookPage = get_posts($bookPageArgs);
        if (!is_array($bookPage) || count($bookPage) == 0) {
            $link = get_permalink($notification->article_page_id);
        } else {
            $link = get_permalink($bookPage[0]->ID);
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
            $icon = 'wp-content/themes/storefront-child/svg/reply.svg';
            $isValid = true;
        } else {
            $feminitive = ($userSex === '1' || $userSex === '') ? 'а' : '';
            $content = $replyUser . ' оценил' . $feminitive . ' ваш комментарий';
            $icon = 'wp-content/themes/storefront-child/svg/like-notification.svg';
            $isValid = true;
        }
    }
    if ($isValid):
        ob_start(); ?>
        <a href="<?= $link ?>">
            <div class="notification-card"
                 style="display: none">
                <div class="row">
                    <div class="col-lg-1 col-2"><?= file_get_contents($icon) ?></div>
                    <div class="col-lg-11 col-10 pl-lg-3 m-auto pl-0">
                        <div class="row">
                            <div class="col-lg-9 col-12">
                                <?= ($notification->view_status == 0) ? '<p class="notification-card__new">Новое уведомление</p>' : null; ?>
                                <p class="notification-card__text"><?= $content ?></p>
                            </div>
                            <div class="col-lg-3 col-12 notification-card__date m-auto text-left text-lg-right">
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

function markBookNotificationAsRead($pageId)
{
    global $post;
    $bookId = get_post_meta($post->ID, 'book_id', true);
    if (!is_user_logged_in() || !$bookId) {
        return;
    }
    $userId = get_current_user_id();
    global $wpdb;
    $table_name = $wpdb->get_blog_prefix() . 'me_notifications';
    $wpdb->get_results($wpdb->prepare("UPDATE {$table_name} SET view_status = 1 WHERE user_id = %d AND notification_type = 'book_finish' AND article_page_id = %d AND view_status = 0;", $userId, $bookId));
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


// Добавляем эндпоинт страницы уведомлений и библиотеки
add_action('init', 'my_account_new_endpoints');

function my_account_new_endpoints()
{
    add_rewrite_endpoint('notifications', EP_ROOT | EP_PAGES);
    add_rewrite_endpoint('library', EP_ROOT | EP_PAGES);
}

// Определяем шаблон страницы уведомлений для эндпоинта страницы уведомлений
add_action('woocommerce_account_notifications_endpoint', 'notifications_endpoint_content');
function notifications_endpoint_content()
{
    get_template_part('notifications');
}

// Определяем шаблон страницы библиотеки для эндпоинта страницы библиотеки
add_action('woocommerce_account_library_endpoint', 'library_endpoint_content');
function library_endpoint_content()
{
    get_template_part('library');
}

// Добавляем в меню личного кабинета пункт "Уведомления" и "Библиотека"
add_filter('woocommerce_account_menu_items', 'addNotificationsPage', 10, 2);

function wpb_woo_my_account_order()
{
    $myorder = array(
        'dashboard' => 'Мой аккаунт',
        'notifications' => 'Мои уведомления',
        'library' => 'Моя библиотека',
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
    $vars[] = 'library';
    return $vars;
}

// Добавляем переменную запроса для страницы уведомлений и библиотеки
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
// Добавляем хлебную крошку для страницы библиотеки
add_filter('woocommerce_get_breadcrumb', function ($args) {
    global $wp_query;
    $is_endpoint = isset($wp_query->query_vars['library']);
    if ($is_endpoint && !is_admin() && is_main_query() && is_account_page()) {
        $args[] = ['Библиотека', get_page_link() . 'library/'];
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

// Изменяем заголовок для страницы библиотеки
add_filter('the_title', 'libraryEndpointTitle');

function libraryEndpointTitle($title)
{
    global $wp_query;
    $is_endpoint = isset($wp_query->query_vars['library']);
    if ($is_endpoint && !is_admin() && is_main_query() && in_the_loop() && is_account_page()) {                // New page title.				'
        $title = "Уведомления";
        remove_filter('the_title', 'libraryEndpointTitle');
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
    $pages = ['notifications' => 'Уведомления', 'library' => 'Библиотека'];
    array_splice_assoc($args, 2, 0, $pages);
    $endpoints['notifications'] = 'notifications';
    $endpoints['library'] = 'library';
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
        $result['bookPageId'] = $bookPageId;

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
        markBookNotificationAsRead($post->ID);
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

    if ($bookId) {
        $book = wc_get_product($bookId);
        $ogTitle = $book->get_name();
        $originalImageUrl = wp_get_attachment_url(get_post_thumbnail_id($book->get_id()));
        unset($book);

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
    if ($path) {
        $finalUrl = preg_replace('~^(.){0,}/wp-content/uploads~', $uploads['baseurl'], $path);
        if ($secure) {
            $finalUrl = preg_replace('~http:~', 'https:', $finalUrl);
        }
        return $finalUrl;
    } else {
        $ogUrl = WPSEO_Options::get('og_default_image');
        if ($size == 'twitter' && $ogUrl != '') {
            return $ogUrl;
        }
        return $img;
    }
}

add_filter('wpseo_og_og_image_width', function ($width) {
    if (!is_product()) {
        return $width;
    }
//    if (!extension_loaded('imagick')) {
//        return $width;
//    }
    require_once __DIR__ . '/evaSocialImgGenerator/evaSocialImgGenerator.php';
    return imgGenerator::getWidth();
});

add_filter('wpseo_og_og_image_height', function ($height) {
    if (!is_product()) {
        return $height;
    }
//    if (!extension_loaded('imagick')) {
//        return $height;
//    }
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

add_action('woocommerce_order_status_completed', function ($order) {
    $order = wc_get_order($order);
    $userId = $order->get_user_id();
    $inLibraryIds = getLibraryBookIds($userId);
    $items = $order->get_items();
    foreach ($items as $item) {
        $bookId = $item->get_product_id();
        if (!in_array($bookId, $inLibraryIds)) {
            add_user_meta($userId, 'library', $bookId);
        }
    }
});

function getLastArticleDate($productId)
{
    global $post;
    // Ищем страницу книги по мета-полю book_id
    $bookPageArgs = [
        'numberposts' => 1,
        'orderby' => 'date',
        'order' => 'DESC',
        'post_type' => 'page',
        'post_status' => 'publish',
        'meta_key' => 'book_id',
        'meta_value' => $productId,
    ];
    $bookPage = get_posts($bookPageArgs);
    if (!is_array($bookPage) || count($bookPage) == 0) {
        return '';
    }
    // Берем из мета-полей страницы категорию глав этой книги
    $categoryId = get_post_meta($bookPage[0]->ID, 'cat_id', true);
    if (!$categoryId) {
        return '';
    }
    // ищем последнюю запись из этой категории
    $args = [
        'numberposts' => 1,
        'orderby' => 'date',
        'order' => 'DESC',
        'cat' => $categoryId,
        'post_type' => 'post',
        'post_status' => 'publish',
    ];
    $articles = get_posts($args);
    if (!is_array($articles) || count($articles) == 0) {
        return '';
    }
    // Берем дату модификации записи и возвращаем её в формате "1 января"
    $day = date('d', strtotime($articles[0]->post_modified));
    $monthNumber = date('m', strtotime($articles[0]->post_modified));
    $months = [
        '01' => 'января',
        '02' => 'февраля',
        '03' => 'марта',
        '04' => 'апреля',
        '05' => 'мая',
        '06' => 'июня',
        '07' => 'июля',
        '08' => 'августа',
        '09' => 'сентября',
        '10' => 'октября',
        '11' => 'ноября',
        '12' => 'декабря',
    ];
    return 'Обновление - ' . $day . ' ' . $months[$monthNumber];
}

function getLibraryBookIds($userId)
{
    return get_user_meta($userId, 'library', false);
}

function isBookInLibrary($userId, $bookId)
{
    $library = getLibraryBookIds($userId);
    return in_array($bookId, $library);
}

add_action('post_submitbox_misc_actions', function ($post) {
    global $post;
    if ($post->post_type !== 'product') {
        return;
    }
    ?>
    <div class="misc-pub-section" id="catalog-visibility">
        <label>
            Разослать уведомления:
            <input type="checkbox" name="notificationEnabled" id="notificationEnabled" class="ml-1">
        </label>
        <select name="notificationType" class="w-100" id="notificationType" disabled>
            <option disabled selected></option>
            <option value="subscribe_open">Открытие подписки</option>
            <option value="book_finish">Завершение книги</option>
            <option value="sale_open">Открытие продаж</option>
        </select>
    </div>
    <script>
        jQuery(function ($) {
            $('#notificationEnabled').on('click', function () {
                if ($('#notificationEnabled').attr('checked')) {
                    $('#notificationType').attr('disabled', false);
                } else {
                    $('#notificationType').attr('disabled', true);

                }
            })
        })
    </script>
    <?php

}, 1, 1);

function getUserIdsWithBookInLibrary($bookId)
{
    $args = [
        'fields' => ['ID'],
        'meta_key' => 'library',
        'meta_value' => $bookId,
    ];
    $users = get_users($args);
    $result = [];
    foreach ($users as $user) {
        $result[] = $user->ID;
    }
    return $result;

}

// в корзине всегда не больше одного товара
add_filter('woocommerce_add_to_cart_validation', 'remove_cart_item_before_add_to_cart', 20, 3);
function remove_cart_item_before_add_to_cart($passed, $product_id, $quantity)
{
    if (!WC()->cart->is_empty()) {
        WC()->cart->empty_cart();
    }
    return $passed;
}

// Покупка бесплатной книги в 1 клик
add_action('template_redirect', function () {
    if (!is_checkout()) {
        return;
    }
    $cart = WC()->cart;
    $cartContents = $cart->get_cart_contents_count();
    if (is_user_logged_in() && $cartContents > 0) {
        if (intval($cart->get_total(false)) === 0) {
            $current_user = wp_get_current_user();
            $cart = WC()->cart;
            $checkout = WC()->checkout();
            $data = [
                'billing_email' => $current_user->user_email,
                'billing_first_name' => $current_user->user_firstname,
            ];
            $order_id = $checkout->create_order($data);
            $order = wc_get_order($order_id);
            update_post_meta($order_id, '_customer_user', get_current_user_id());
            $order->calculate_totals();
            $order->payment_complete();
            $cart->empty_cart();
            exit(wp_redirect(home_url('/my-account/library')));
        }
    }
});

add_filter('mycred_parse_tags_general', function ($content) {
    if ($content == 'Reward with %plural%') {
        return '%plural% за покупку';
    }
    return $content;
});

function mycred_woo_add_product_metabox()
{

    add_meta_box(
        'mycred_woo_sales_setup',
        'Баллы',
        'mycred_woo_product_metabox',
        'product',
        'side',
        'high'
    );
}


add_filter('wc_add_to_cart_message_html', function ($message) {
    return '';
});

function plural_form($number, $after)
{
    $cases = array(2, 0, 1, 1, 1, 2);
    echo $number . ' ' . $after[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
}

function getArticlesList($bookId)
{
    $bookPageArgs = [
        'numberposts' => 1,
        'orderby' => 'date',
        'order' => 'DESC',
        'post_type' => 'page',
        'post_status' => 'publish',
        'meta_key' => 'book_id',
        'meta_value' => $bookId,
    ];
    $bookPage = get_posts($bookPageArgs);
    if (!is_array($bookPage) || count($bookPage) == 0) {
        return [];
    }
    // Берем из мета-полей страницы категорию глав этой книги
    $categoryId = get_post_meta($bookPage[0]->ID, 'cat_id', true);
    if (!$categoryId) {
        return [];
    }
    $args = [
        'numberposts' => -1,
        'orderby' => 'date',
        'order' => 'ASC',
        'cat' => $categoryId,
        'post_type' => 'post',
        'post_status' => 'publish',
    ];
    $articles = get_posts($args);
    return $articles;
}

function getNumeral($number, $_1, $_2, $_5)
{
    if ($number % 100 > 10 && $number % 100 < 15) {
        return $_5;
    } elseif ($number % 10 == 1) {
        return $_1;
    } elseif ($number % 10 > 1 && $number % 10 < 5) {
        return $_2;
    } else {
        return $_5;
    }
}

// При изменении товара
// если цена 0 то создать разрешение на все файлы для пользователя 0 с e-mail free@marina-eldenbert.ru
// если цена не 0, то отозвать разрешение на все файлы для пользователя 0 с e-mail free@marina-eldenbert.ru

add_action('save_post', 'updateFreeBookPermission', 99, 3);
function updateFreeBookPermission($post_id, $post, $update)
{
    if ($post->post_type == 'product') {
        $product = wc_get_product($post_id);
        if ($product && $product->exists() && $product->is_downloadable()) {
            if ($product->get_price() == '0') {
                $downloads = $product->get_downloads();
                foreach (array_keys($downloads) as $download_id) {
                    free_file_permission($download_id, $product);
                }
            } else {
                global $wpdb;
                $table_name = $wpdb->get_blog_prefix() . 'woocommerce_downloadable_product_permissions';
                $params = [
                    'user_id' => 0,
                    'product_id' => intval($product->get_id()),
                ];
                $wpdb->delete($table_name, $params);
            }
        }
    }
}

function free_file_permission($download_id, $product)
{
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    $data_store = WC_Data_Store::load('customer-download');
    $download_ids = $data_store->get_downloads(
        array(
            'user_email' => sanitize_email('free@marina-eldenbert.ru'),
            'order_key' => wc_clean(1), // WPCS: input var ok, CSRF ok.
            'product_id' => $product->get_id(),
            'download_id' => wc_clean(preg_replace('/\s+/', ' ', wp_unslash($download_id))), // WPCS: input var ok, CSRF ok, sanitization ok.
            'orderby' => 'downloads_remaining',
            'order' => 'DESC',
            'limit' => 1,
            'return' => 'ids',
        )
    );
    if (count($download_ids) == 0) {
        $download = new WC_Customer_Download(intval($download_ids[0]));
        $download->set_download_id($download_id);
    } else {
        $download = new WC_Customer_Download(intval($download_ids[0]));
    }
    $download->set_product_id($product->get_id());
    $download->set_user_id(0);
    $download->set_order_id(1);
    $download->set_user_email('free@marina-eldenbert.ru');
    $download->set_order_key('1');
    $download->set_downloads_remaining('');
    $download->set_access_granted(current_time('timestamp', true));
    $download->set_download_count(0);

//    $download = apply_filters( 'woocommerce_downloadable_file_permission', $download, $product, $order, $qty );

    return $download->save();
}

function wc_get_free_downloads()
{
    $downloads = array();
    $_product = null;
    $order = null;
    $file_number = 0;

    $data_store = WC_Data_Store::load('customer-download');
    $results = $data_store->get_downloads_for_customer(0);

    if ($results) {
        foreach ($results as $result) {
            $order_id = intval($result->order_id);

            if (!$order || $order->get_id() !== $order_id) {
                // New order.
                $order = wc_get_order($order_id);
                $_product = null;
            }

            $product_id = intval($result->product_id);

            if (!$_product || $_product->get_id() !== $product_id) {
                // New product.
                $file_number = 0;
                $_product = wc_get_product($product_id);
            }

            // Check product exists and has the file.
            if (!$_product || !$_product->exists() || !$_product->has_file($result->download_id)) {
                continue;
            }

            $download_file = $_product->get_file($result->download_id);

            // Download name will be 'Product Name' for products with a single downloadable file, and 'Product Name - File X' for products with multiple files.
            $download_name = apply_filters(
                'woocommerce_downloadable_product_name',
                $download_file['name'],
                $_product,
                $result->download_id,
                $file_number
            );

            $downloads[] = array(
                'download_url' => add_query_arg(
                    array(
                        'download_file' => $product_id,
                        'order' => $result->order_key,
                        'email' => rawurlencode($result->user_email),
                        'key' => $result->download_id,
                    ),
                    home_url('/')
                ),
                'download_id' => $result->download_id,
                'product_id' => $_product->get_id(),
                'product_name' => $_product->get_name(),
                'product_url' => $_product->is_visible() ? $_product->get_permalink() : '', // Since 3.3.0.
                'download_name' => $download_name,
                'order_id' => 1,
                'order_key' => 1,
                'downloads_remaining' => $result->downloads_remaining,
                'access_expires' => $result->access_expires,
                'file' => array(
                    'name' => $download_file->get_name(),
                    'file' => $download_file->get_file(),
                ),
            );

            $file_number++;
        }
    }

    return $downloads;
}

add_filter(/**
 * @param $msg
 * @param $msg_code
 * @param $coupon WC_Coupon
 */
    'woocommerce_coupon_message', function ($msg, $msg_code, $coupon) {
    if ($msg_code == WC_Coupon::WC_COUPON_SUCCESS) {
        return 'Абонемент успешно применен';
    }
}, 20, 3);


add_filter('wp_insert_post_data', 'filter_post_data', '99', 2);

function filter_post_data($data, $postarr)
{
    $oldPost = get_post($postarr['ID']);
    $GLOBALS['beforeEdit'] = $oldPost->post_content;
    return $data;
}

function addOrUpdateUrlParam($name, $value)
{
    $params = $_GET;
    unset($params[$name]);
    $params[$name] = $value;
    return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']
        . explode('?', $_SERVER['REQUEST_URI'], 2)[0] . '?' . http_build_query($params);
}


// Разрешаем загружать файлы с перечисленными расширениями
add_filter('upload_mimes', 'upload_allow_types');
function upload_allow_types($mimes)
{
    // разрешаем новые типы
    $mimes['azw3'] = 'application/octet-stream';
    $mimes['epub'] = 'application/epub+zip';
    $mimes['fb2'] = 'application/xml';
    $mimes['mobi'] = 'application/octet-stream';
    $mimes['pdf'] = 'application/pdf';

    // отключаем имеющиеся
//    unset($mimes['mp4a']);

    return $mimes;
}

// Делаем запоминание авторизации на 30 дней
add_filter('auth_cookie_expiration', function () {
    return 30 * DAY_IN_SECONDS;
});

function prevent_deleting_pTags($init){
    $init['wpautop'] = false;
    return $init;
}
// Отключает удаление пустых тегов <p> - нужно для сохраненния переносов после форматирования текста
add_filter('tiny_mce_before_init', 'prevent_deleting_pTags');
