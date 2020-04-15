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
$inLibraryIds = array_reverse($inLibraryIds);
$libraryBooks = [];

if (count($inLibraryIds) > 0) {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post__in' => $inLibraryIds,
        'orderby' => 'post__in',
        'order' => 'ASC'
    );
    $libraryQuery = new WP_Query($args);
    while ($libraryQuery->have_posts()) :
        $libraryQuery->the_post();
        global $product;
        $libraryBooks[] = $product;
    endwhile;
    wp_reset_query();
}

$downloads = WC()->customer->get_downloadable_products();
$has_downloads = (bool)$downloads;

if (count($libraryBooks) !== 0):
    foreach ($libraryBooks as $libraryBook): ?>
        <?php
        $tags = get_the_terms($libraryBook->ID, 'product_tag');
        $tagsArray = array();
        if (!empty($tags) && !is_wp_error($tags)) {
            foreach ($tags as $tag) {
                $tagsArray[] = $tag->slug;
            }
        }
        $isDraft = in_array('draft', $tagsArray);
        if ($isDraft) {
            $lastUpdate = getLastArticleDate($libraryBook->get_id());
        }

        $imgsrc = wp_get_attachment_url($libraryBook->get_image_id());
        $libraryId = $libraryBook->get_id();
        if (empty($imgsrc)) :
            $imgsrc = '/wp-content/uploads/woocommerce-placeholder.png';
        endif; ?>
        <div class="library-card">
            <div class="row">
                <div class="col-3">
                    <a href="<?= $libraryBook->get_permalink() ?>">
                        <img class="library-card__img" src="<?= $imgsrc ?>" alt="<?= $libraryBook->name ?>">
                    </a>
                </div>
                <div class="col-9">
                    <p class="library-card__header"><?= $libraryBook->name ?></p>
                    <div class="library-card-info">
                        <?php if (!$isDraft): ?>
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
                                <img src="/wp-content/themes/storefront-child/svg/svg-process-book.svg"
                                     alt="process-book">
                                <div>
                                    <p>Книга в процессе: <?= $lastUpdate ?></p>
                                    <p>25 глав <span>+2 новые</span></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <a href="/my-account/downloads?remove=<?= $libraryId ?>" class="library-card-info__status">
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
                        <a class="library-card-group__read" href="/<?= $libraryBook->slug ?>">Читать</a>

                        <?php
                        if ($libraryBook->is_downloadable('yes') && $libraryBook->has_file()) {
                            $eBookDownloads = $libraryBook->get_downloads();
                            $eBookPriceHtml = $libraryBook->get_price_html();
                        }

                        if (!isBookBought($libraryBook->get_id()) && !in_array('draft', $tagsArray)):?>
                            <form class="cart"
                                  action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
                                  method="post" enctype='multipart/form-data'>
                                <button type="submit" name="add-to-cart"
                                        value="<?php echo esc_attr($libraryBook->get_id()); ?>"
                                        class="library-card-group__buy">Купить книгу
                                    за <?php echo $libraryBook->get_price_html(); ?>
                                    <?php if ($eBookDownloads): ?>
                                        <p>(чтение на сайте +
                                            <?php foreach ($eBookDownloads as $key => $eBookDownload) {
                                                echo $eBookDownload->get_name();
                                                if ($key === array_key_last($eBookDownloads)) {
                                                    echo '';
                                                } else {
                                                    echo ', ';
                                                }
                                            } ?>)</p>
                                    <?php else: ?>
                                        <p>(только чтение на сайте)</p>
                                    <?php endif; ?>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    <?php endforeach;
else: ?>
    <p class="library-empty">Вы пока не добавили ни одной книги</p>
<?php endif;

