<?php
/**
 * Downloads
 *
 * Shows downloads on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/downloads.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see    https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.2.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if (is_user_logged_in()) {
    if (isset($_GET['add'])) {
        $inLibraryIds = get_user_meta(get_current_user_id(), 'library', false);
        $product = wc_get_product(intval($_GET['add']));
        if ($product && !in_array(intval($_GET['add']), $inLibraryIds)) {
            add_user_meta(get_current_user_id(), 'library', intval($_GET['add']));
        }
    }
    if (isset($_GET['remove'])) {
        delete_user_meta(get_current_user_id(), 'library', intval($_GET['remove']));
    }
}

$inLibraryIds = get_user_meta(get_current_user_id(), 'library', false);
$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'post__in' => $inLibraryIds,
);
$libraryQuery = new WP_Query($args);
$libraryBooks = [];
while ($libraryQuery->have_posts()) :
    $libraryQuery->the_post();
    global $product;
    $libraryBooks[] = $product;
endwhile;
wp_reset_query();

$downloads = WC()->customer->get_downloadable_products();
$has_downloads = (bool)$downloads;

//do_action( 'woocommerce_before_account_downloads', $has_downloads ); ?>
    <!---->
<?php //if ( $has_downloads ) : ?>
    <!---->
    <!--	--><?php //do_action( 'woocommerce_before_available_downloads' ); ?>
    <!---->
    <!--	--><?php //do_action( 'woocommerce_available_downloads', $downloads ); ?>
    <!---->
    <!--	--><?php //do_action( 'woocommerce_after_available_downloads' ); ?>
    <!---->
<?php //else : ?>
    <!--	<div class="woocommerce-Message woocommerce-Message--info woocommerce-info">-->
    <!--		<a class="btn btn-primary" href="--><?php //echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?><!--">-->
    <!--			--><?php //esc_html_e( 'Go shop', 'woocommerce' ); ?>
    <!--		</a>-->
    <!--		--><?php //esc_html_e( 'No downloads available yet.', 'woocommerce' ); ?>
    <!--	</div>-->
<?php //endif; ?>
    <!---->
<?php //do_action( 'woocommerce_after_account_downloads', $has_downloads ); ?>


<?php
foreach ($libraryBooks as $libraryBook): ?>

    <?php $imgsrc = wp_get_attachment_url($libraryBook->get_image_id());
    if (empty($imgsrc)) :
        $imgsrc = '/wp-content/uploads/woocommerce-placeholder.png';
    endif; ?>
    <div class="library-card">
        <div class="row">
            <div class="col-3">
                <img class="library-card__img" src="<?= $imgsrc ?>" alt="<?= $libraryBook->name ?>">
            </div>
            <div class="col-9">
                <p class="library-card__header"><?= $libraryBook->name ?></p>
                <div class="library-card-info">
                    <?php if (true): ?>
                        <!-- книга завершена-->
                        <div class="library-card-info__status">
                            <img src="/wp-content/themes/storefront-child/svg/svg-complete-book.svg"
                                 alt="complete-book">
                            <div>
                                <p>Книга завершена</p>
                                <p>25 глав | 87200 символов</p>
                            </div>
                        </div>
                        <!-- книга завершена-->
                    <?php else: ?>
                        <!-- книга в процессе-->
                        <div class="library-card-info__status">
                            <img src="/wp-content/themes/storefront-child/svg/svg-process-book.svg" alt="process-book">
                            <div>
                                <p>Книга в процессе: Обновление - 31 марта</p>
                                <p>25 глав <span>+2 новые</span></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <a href="#" class="library-card-info__status">
                        <img src="/wp-content/themes/storefront-child/svg/svg-addToLibrary.svg"
                             alt="add-to-library">
                        <div>
                            <p>В вашей библиотеке</p>
                            <p>удалить из библиотеки</p>
                        </div>
                    </a>
                </div>
                <p class="library-card__desc">
                    <?php $desc = strip_tags($libraryBook->get_short_description());
                    $size = 395;

                    echo mb_substr($desc, 0, mb_strrpos(mb_substr($desc, 0, $size, 'utf-8'), ' ', 'utf-8'), 'utf-8');
                    echo (strlen($desc) > $size) ? '...' : '';
                    ?>
                </p>
                <div class="library-card-group">
                    <a class="library-card-group__read" href="#">Читать</a>
                    <a class="library-card-group__buy" href="#">Купить
                        <p>(только чтение на сайте)</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php endforeach;
