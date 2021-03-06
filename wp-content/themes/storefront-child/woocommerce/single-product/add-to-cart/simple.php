<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

global $product;

if (!$product->is_purchasable()) {
    return;
}

echo wc_get_stock_html($product); // WPCS: XSS ok.

if ($product->is_downloadable('yes') && $product->has_file()) {
    $eBookDownloads = $product->get_downloads();
    $eBookPriceHtml = $product->get_price_html();
}

$linksAudio = get_post_custom_values('buy_audio_book', $product->get_id());
$linksPaper = get_post_custom_values('buy_paper_book', $product->get_id());

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
$hasDownloads = false;

?>
<div class="add-to-cart-block">
    <div class="row">
        <div class="col-lg-8 col-md-8 order-md-1 order-2 pr-lg-0 pr-unset col-12 br m-auto">
            <div class="add-to-cart-block__body">
                <div id="ebookTarget" class="add-to-cart-block__target active">
                    <?php if ($product->is_in_stock()) : ?>
                        <?php do_action('woocommerce_before_add_to_cart_form'); ?>
                        <form class="cart"
                              action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
                              method="post" enctype='multipart/form-data'>
                            <?php if (is_user_logged_in()): ?>


                                <?php
                                do_action('woocommerce_before_add_to_cart_quantity');

                                woocommerce_quantity_input(array(
                                    'min_value' => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                                    'max_value' => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                                    'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                                ));

                                do_action('woocommerce_after_add_to_cart_quantity');

                                //выводим ссылку на чтение книги

                                readButton(get_permalink(getBookPageIdByBookId($product->get_id())), $product->get_id(), 'product-read');
                                $bookPageId = getBookPageIdByBookId($product->get_id());
                                $tags = get_the_terms($product->get_id(), 'product_tag');
                                $tagsArray = array();
                                if (!empty($tags) && !is_wp_error($tags)) {
                                    foreach ($tags as $tag) {
                                        $tagsArray[] = $tag->slug;
                                    }
                                }
                                $isDraft = in_array('draft', $tagsArray);

                                ?>

                                <?php if ($product->get_price() !== '0' && $product->get_price() !== 0): ?>
                                    <?php
                                    if (!empty($downloads) && isBookBought($product->get_id())) : ?>
                                        <div class="add-to-cart-block__download">
                                            <span>Вам доступны файлы:</span>
                                            <p>
                                                <?php
                                                foreach ($downloads as $key => $download) {
                                                    if ($download['product_id'] == $product->get_id()) { ?>
                                                        <a href="<?php echo $download['download_url'] ?>">
                                                            <?php echo $download['file']['name']; ?><?php
                                                            if ($key === array_key_last($downloads)) {
                                                                echo '';
                                                            } else {
                                                                echo ', ';
                                                            }
                                                            ?>
                                                        </a>
                                                        <?php
                                                        $hasDownloads = true;
                                                    }
                                                }
                                                ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                    <?php
                                    if (!isBookBought($product->get_id())):
                                        ?>

                                        <button type="submit" name="add-to-cart"
                                                value="<?php echo esc_attr($product->get_id()); ?>"
                                                class="single_add_to_cart_button button alt"><?= $isDraft ? 'Подписка за ' : 'Купить книгу зa ' ?><?php echo $product->get_price_html(); ?>
                                            <?php if ($eBookDownloads): ?>
                                                <p>(
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
                                    <?php endif;
                                    do_action('woocommerce_after_add_to_cart_button');
                                    ?>
                                <?php else: ?>
                                    <?php if ($downloads): ?>
                                        <div class="add-to-cart-block__download">
                                            <span>Вам доступны файлы:</span>
                                            <p>
                                                <?php foreach ($downloads as $key => $eBookDownload): ?>
                                                    <a href="<?= $eBookDownload['download_url'] ?>"><?= $eBookDownload['download_name'] ?> <?= $key === array_key_last($downloads) ? '' : ', ' ?></a>
                                                <?php endforeach; ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php readButton(get_permalink(getBookPageIdByBookId($product->get_id())), $product->get_id(), 'product-read'); ?>
                                <?php if ($product->get_price() !== '0' && $product->get_price() !== 0): ?>
                                    <button type="submit" name="add-to-cart"
                                            value="<?php echo esc_attr($product->get_id()); ?>"
                                            class="single_add_to_cart_button button alt"><?= $isDraft ? 'Подписка за ' : 'Купить книгу за ' ?> <?php echo $product->get_price_html(); ?>
                                        <?php if ($eBookDownloads): ?>
                                            <p>(
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
                                <?php else: ?>
                                    <?php if ($downloads): ?>
                                        <div class="add-to-cart-block__download">
                                            <span>Вам доступны файлы:</span>
                                            <p>
                                                <?php foreach ($downloads as $key => $eBookDownload): ?>
                                                    <a data-toggle="modal" data-target="#loginForLink" href="#"><?= $eBookDownload['download_name'] ?> <?= $key === array_key_last($downloads) ? '' : ', ' ?></a>
                                                <?php endforeach; ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>

                    <p class="add-to-cart-block__support">Если у вас возникли какие-либо вопросы - напишите на почту <a href="mailto:support@marina-eldenbert.ru">support@marina-eldenbert.ru</a></p>
                    <p class="add-to-cart-block__support-link"><a href="mailto:support@marina-eldenbert.ru">support@marina-eldenbert.ru</a>
                    </p>
                    <p class="add-to-cart-block__ref">Так же ознакомьтесь с подробной <a href="/spravka">справкой для пользователей</a></p>
                </div>
                <div id="paperBookTarget" class="add-to-cart-block__target">
                    <p class="add-to-cart-block__partners">Бумажные издания книги можно приобрести только в
                        магазинах-партнерах. Данную книгу вы можете найти в следующих интернет-магазинах:</p>

                    <?php do_action('woocommerce_after_add_to_cart_form'); ?>
                    <p class="add-to-cart-block__partners-links">
                        <?php
                        if (is_array($linksPaper)) {
                            foreach ($linksPaper as $link) {
                                $link_parts = preg_split('~\(:\)~', $link, 2);
                                echo '<a href="' . $link_parts[1] . '" target="_blank">' . $link_parts[0] . '</a>';
                            }
                        }
                        ?>
                    </p>
                </div>
                <div id="audioBookTarget" class="add-to-cart-block__target">
                    <p class="add-to-cart-block__partners">Аудиокниги можно приобрести только в магазинах-партнерах.
                        Данную книгу вы можете найти в следующих интернет-магазинах:</p>
                    <p class="add-to-cart-block__partners-links">
                        <?php
                        if (is_array($linksAudio)) {
                            foreach ($linksAudio as $link) {
                                $link_parts = preg_split('~\(:\)~', $link, 2);
                                echo '<a href="' . $link_parts[1] . '" target="_blank">' . $link_parts[0] . '</a>';
                            }
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 order-md-2 order-1 col-12 pl-lg-0 pl-unset">
            <div class="add-to-cart-block__info-body">
                <div class="add-to-cart-block__type">
                    <div data-id="ebook" class="card-payment active">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0)">
                                <path d="M27.6141 0H8.41415C6.75797 0.00192261 5.41599 1.3439 5.41406 3.00009V32.9999C5.41599 34.6561 6.75797 35.9981 8.41415 36H27.6141C29.2701 35.9981 30.612 34.6561 30.614 32.9999V3.00009C30.612 1.3439 29.2701 0.00192261 27.6141 0V0ZM29.414 32.9999C29.414 33.9942 28.6081 34.8 27.6141 34.8H8.41415C7.41989 34.8 6.61404 33.9942 6.61404 32.9999V3.00009C6.61404 2.00583 7.41989 1.19998 8.41415 1.19998H27.6141C28.6081 1.19998 29.414 2.00583 29.414 3.00009V32.9999Z"
                                      fill="#626262"/>
                                <path d="M27.0144 2.40002H9.01443C8.35168 2.40002 7.81445 2.93726 7.81445 3.60001V30.0002C7.81445 30.6629 8.35168 31.2001 9.01443 31.2001H27.0144C27.6772 31.2001 28.2144 30.6629 28.2144 30.0002V3.60001C28.2144 2.93726 27.6772 2.40002 27.0144 2.40002ZM9.01443 30.0002V3.60001H27.0144V30.0002H9.01443Z"
                                      fill="#626262"/>
                                <path d="M18.6146 32.4H17.4146C17.0831 32.4 16.8145 32.6686 16.8145 32.9999C16.8145 33.3314 17.0831 33.6 17.4146 33.6H18.6146C18.9458 33.6 19.2144 33.3314 19.2144 32.9999C19.2144 32.6686 18.9458 32.4 18.6146 32.4Z"
                                      fill="#626262"/>
                                <path d="M10.814 7.19998H13.214C13.5452 7.19998 13.8138 6.93137 13.8138 6.60013C13.8138 6.26862 13.5452 6 13.214 6H10.814C10.4825 6 10.2139 6.26862 10.2139 6.60013C10.2139 6.93137 10.4825 7.19998 10.814 7.19998Z"
                                      fill="#626262"/>
                                <path d="M25.2139 6H15.6138C15.2823 6 15.0137 6.26862 15.0137 6.60013C15.0137 6.93137 15.2823 7.20026 15.6138 7.20026H25.2139C25.5452 7.20026 25.8138 6.93137 25.8138 6.60013C25.8138 6.26862 25.5452 6 25.2139 6Z"
                                      fill="#626262"/>
                                <path d="M25.2141 9.60022H10.814C10.4825 9.60022 10.2139 9.86884 10.2139 10.2001C10.2139 10.5316 10.4825 10.8002 10.814 10.8002H25.2141C25.5453 10.8002 25.8139 10.5316 25.8139 10.2001C25.8139 9.86884 25.5453 9.60022 25.2141 9.60022Z"
                                      fill="#626262"/>
                                <path d="M25.2141 13.2001H10.814C10.4825 13.2001 10.2139 13.4687 10.2139 13.7999C10.2139 14.1314 10.4825 14.4001 10.814 14.4001H25.2141C25.5453 14.4001 25.8139 14.1314 25.8139 13.7999C25.8139 13.4687 25.5453 13.2001 25.2141 13.2001Z"
                                      fill="#626262"/>
                                <path d="M10.814 17.9999H21.6138C21.9453 17.9999 22.214 17.7313 22.214 17.4001C22.214 17.0685 21.9453 16.7999 21.6138 16.7999H10.814C10.4825 16.7999 10.2139 17.0685 10.2139 17.4001C10.2139 17.7313 10.4825 17.9999 10.814 17.9999Z"
                                      fill="#626262"/>
                                <path d="M25.2142 16.7999H24.0139C23.6827 16.7999 23.4141 17.0685 23.4141 17.4001C23.4141 17.7313 23.6827 17.9999 24.0139 17.9999H25.2142C25.5454 17.9999 25.814 17.7313 25.814 17.4001C25.814 17.0685 25.5454 16.7999 25.2142 16.7999Z"
                                      fill="#626262"/>
                                <path d="M25.2141 20.3999H10.814C10.4825 20.3999 10.2139 20.6685 10.2139 21C10.2139 21.3313 10.4825 21.5999 10.814 21.5999H25.2141C25.5453 21.5999 25.8139 21.3313 25.8139 21C25.8139 20.6685 25.5453 20.3999 25.2141 20.3999Z"
                                      fill="#626262"/>
                                <path d="M12.014 24H10.814C10.4825 24 10.2139 24.2686 10.2139 24.6001C10.2139 24.9314 10.4825 25.2 10.814 25.2H12.014C12.3452 25.2 12.6141 24.9314 12.6141 24.6001C12.6141 24.2686 12.3452 24 12.014 24Z"
                                      fill="#626262"/>
                                <path d="M25.2137 24H14.4136C14.0824 24 13.8135 24.2686 13.8135 24.6001C13.8135 24.9314 14.0824 25.2 14.4136 25.2H25.2137C25.545 25.2 25.8136 24.9314 25.8136 24.6001C25.8136 24.2686 25.545 24 25.2137 24Z"
                                      fill="#626262"/>
                            </g>
                            <defs>
                                <clipPath id="clip0">
                                    <rect width="36" height="36" fill="white"/>
                                </clipPath>
                            </defs>
                        </svg>
                        <input data-target="ebookTarget" id="paymentBook" type="radio" name="variation_id">
                        <p>Электронная книга</p>
                    </div>
                    <div data-id="paperBook" class="card-payment <?= !is_array($linksPaper) ? 'disabled' : null ?>">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M31.2002 0H7.2002C5.5442 0.00195 4.20215 1.344 4.2002 3V33C4.20215 34.656 5.5442 35.9981 7.2002 36H31.2002C31.5315 36 31.8002 35.7314 31.8002 35.4V0.6C31.8002 0.26865 31.5315 0 31.2002 0ZM24.0002 1.2H27.6002V6.8292L26.0684 6.063C25.8996 5.97862 25.7008 5.97862 25.532 6.063L24.0002 6.8292V1.2ZM5.4002 3C5.4002 2.00588 6.20607 1.2 7.2002 1.2H7.8002V28.8H7.2002C6.5489 28.8022 5.91642 29.0186 5.4002 29.4156V3ZM30.6002 31.8H7.8002V33H30.6002V34.8H7.2002C6.20607 34.8 5.4002 33.9941 5.4002 33V31.8C5.4002 30.8059 6.20607 30 7.2002 30H30.6002V31.8ZM30.6002 28.8H9.0002V1.2H22.8002V7.8C22.8 8.13135 23.0685 8.40015 23.3999 8.4003C23.4931 8.40038 23.585 8.3787 23.6684 8.337L25.8002 7.2708L27.932 8.34C28.2284 8.48813 28.5888 8.3679 28.7369 8.0715C28.779 7.9872 28.8007 7.8942 28.8002 7.8V1.2H30.6002V28.8V28.8Z"
                                  fill="#626262"/>
                        </svg>
                        <input data-target="paperBookTarget" id="paymentBook" type="radio" name="variation_id">
                        <p>Бумажная книга</p>
                    </div>
                    <div data-id="audioBook" class="card-payment <?= !is_array($linksAudio) ? 'disabled' : null ?>">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M34.6639 18.0001C34.6675 15.5463 34.1275 13.1221 33.0828 10.9018C32.038 8.68153 30.5145 6.7202 28.6216 5.15876C25.6334 2.68802 21.8774 1.3363 18.0002 1.3363C14.1229 1.3363 10.3669 2.68802 7.37875 5.15876C4.70394 7.3703 2.78677 10.3621 1.89507 13.7163C1.00338 17.0704 1.1817 20.6194 2.40512 23.8672C1.86083 24.4544 1.50548 25.1915 1.38512 25.9831C1.26477 26.7746 1.38496 27.5841 1.73012 28.3065L3.69268 32.3978C3.91878 32.871 4.23603 33.2949 4.62626 33.6453C5.01648 33.9956 5.47201 34.2655 5.96673 34.4395C6.46145 34.6135 6.98564 34.6881 7.50926 34.6591C8.03288 34.6302 8.54564 34.4982 9.01815 34.2707L9.43328 34.0715C9.4688 34.054 9.50205 34.0322 9.53228 34.0066C9.89532 34.1259 10.2855 34.1358 10.6541 34.035C11.0227 33.9343 11.3536 33.7273 11.6054 33.4398C11.8572 33.1524 12.019 32.7972 12.0704 32.4186C12.1219 32.0399 12.0608 31.6544 11.8948 31.3102L7.40293 21.9553C7.27529 21.6875 7.08812 21.4525 6.85572 21.2682C6.62333 21.0838 6.35186 20.9551 6.06207 20.8917C5.77229 20.8284 5.47185 20.8321 5.18374 20.9027C4.89563 20.9733 4.62746 21.1088 4.39974 21.2989C3.79273 18.79 3.89214 16.1622 4.68695 13.7064C5.48177 11.2506 6.94096 9.0628 8.90284 7.38542C9.10383 7.49115 9.32712 7.54745 9.55421 7.54967C9.85848 7.54906 10.155 7.45338 10.4022 7.27601C12.6218 5.69894 15.2772 4.85165 18 4.85165C20.7229 4.85165 23.3782 5.69894 25.5978 7.27601C25.8135 7.43141 26.0679 7.52416 26.3329 7.54402C26.598 7.56387 26.8633 7.51005 27.0997 7.38851C29.0606 9.06601 30.519 11.2535 31.3132 13.7088C32.1075 16.1641 32.2066 18.7913 31.5997 21.2994C31.372 21.1093 31.1038 20.9738 30.8157 20.9033C30.5276 20.8327 30.2272 20.8289 29.9374 20.8923C29.6476 20.9556 29.3761 21.0844 29.1437 21.2687C28.9113 21.4531 28.7242 21.6881 28.5965 21.9559L24.1058 31.3102C23.9378 31.6544 23.8753 32.0405 23.9259 32.4201C23.9766 32.7997 24.1383 33.1558 24.3906 33.4439C24.643 33.7319 24.9748 33.939 25.3444 34.0392C25.7141 34.1393 26.105 34.1281 26.4683 34.0069C26.4986 34.0323 26.5318 34.054 26.5673 34.0715L26.9824 34.2709C27.937 34.7287 29.0344 34.7885 30.0331 34.4372C31.0318 34.086 31.85 33.3524 32.3079 32.3978L34.2705 28.3065C34.6156 27.5841 34.7358 26.7746 34.6155 25.9831C34.4951 25.1915 34.1397 24.4544 33.5955 23.8672C34.3049 21.9926 34.667 20.0044 34.6639 18.0001V18.0001ZM6.29284 33.5088C5.91982 33.3786 5.5763 33.1759 5.28215 32.9121C4.98801 32.6484 4.74908 32.329 4.57918 31.9723L2.6169 27.8807C2.27498 27.1664 2.22834 26.3462 2.48711 25.5978C2.74587 24.8494 3.28918 24.2332 3.99924 23.8827L8.56253 33.3968C7.85025 33.7317 7.0346 33.7719 6.29284 33.5088V33.5088ZM5.22409 21.9283C5.35407 21.8655 5.49656 21.8329 5.6409 21.8329C5.82333 21.8329 6.00207 21.8843 6.15656 21.9813C6.31105 22.0784 6.43502 22.217 6.51418 22.3814L11.0066 31.7363C11.0615 31.8508 11.0934 31.975 11.1003 32.1018C11.1073 32.2286 11.0892 32.3556 11.047 32.4754C11.0049 32.5952 10.9396 32.7055 10.8549 32.8001C10.7701 32.8947 10.6676 32.9716 10.5531 33.0266C10.4386 33.0815 10.3144 33.1133 10.1876 33.1203C10.0608 33.1272 9.93384 33.1091 9.81403 33.067C9.69422 33.0249 9.58388 32.9596 9.48931 32.8749C9.39473 32.7901 9.31778 32.6875 9.26284 32.573L4.78112 23.2355L4.77296 23.2181C4.71795 23.1037 4.68602 22.9795 4.67902 22.8528C4.67202 22.726 4.69007 22.5991 4.73216 22.4793C4.77424 22.3595 4.83952 22.2492 4.92426 22.1546C5.00901 22.0601 5.11156 21.9832 5.22606 21.9283H5.22409ZM3.02331 18.0001C3.02261 19.5283 3.25603 21.0476 3.71546 22.5051C3.69347 22.6475 3.68744 22.7918 3.69746 22.9354L3.60212 22.981C3.46628 23.0468 3.33425 23.1203 3.20668 23.2009C2.16921 20.2487 2.03916 17.0535 2.83327 14.0267C3.62739 10.9999 5.30933 8.28005 7.66253 6.21739L8.15612 6.73039C6.54335 8.13166 5.25043 9.86305 4.36488 11.8074C3.47933 13.7517 3.02182 15.8636 3.02331 18.0001V18.0001ZM26.1685 6.47417C23.7822 4.77838 20.9273 3.86729 17.9999 3.86729C15.0724 3.86729 12.2175 4.77838 9.83125 6.47417C9.74109 6.54019 9.6301 6.57137 9.51876 6.56196C9.40741 6.55255 9.30323 6.50319 9.22543 6.42298L8.4205 5.58542C11.1651 3.46851 14.5337 2.32036 17.9999 2.32036C21.466 2.32036 24.8346 3.46851 27.5792 5.58542L26.7749 6.4227C26.6971 6.5031 26.5928 6.55261 26.4813 6.56207C26.3699 6.57153 26.2587 6.54031 26.1685 6.47417V6.47417ZM27.8436 6.73039L28.3366 6.21739C30.69 8.27992 32.3722 10.9997 33.1665 14.0265C33.9608 17.0533 33.831 20.2486 32.7936 23.2009C32.666 23.1203 32.534 23.0468 32.3982 22.981L32.3028 22.9352C32.3129 22.7916 32.3068 22.6474 32.2848 22.5051C33.16 19.7288 33.2059 16.7574 32.417 13.9554C31.6281 11.1533 30.0387 8.64233 27.8436 6.73039V6.73039ZM25.4457 33.0273C25.2148 32.9161 25.0374 32.7178 24.9524 32.476C24.8675 32.2342 24.8819 31.9686 24.9926 31.7374L29.4836 22.3825C29.5628 22.2182 29.6867 22.0795 29.8412 21.9825C29.9957 21.8854 30.1744 21.834 30.3569 21.8341C30.5202 21.8343 30.6808 21.876 30.8237 21.9551C30.9666 22.0342 31.0871 22.1483 31.174 22.2866C31.2609 22.425 31.3113 22.583 31.3205 22.7461C31.3297 22.9092 31.2975 23.072 31.2268 23.2192L31.2186 23.2367L26.7358 32.5742C26.6809 32.6887 26.6039 32.7912 26.5094 32.876C26.4148 32.9607 26.3044 33.026 26.1846 33.0681C26.0648 33.1102 25.9379 33.1282 25.8111 33.1212C25.6843 33.1142 25.5601 33.0823 25.4457 33.0273V33.0273ZM33.3823 27.8804L31.42 31.972C31.0775 32.6863 30.4671 33.2365 29.7212 33.5033C28.9754 33.77 28.1544 33.7316 27.4367 33.3965L32.0005 23.8827C32.7108 24.2329 33.2544 24.8491 33.5135 25.5975C33.7725 26.3459 33.7261 27.1663 33.3842 27.8807L33.3823 27.8804Z"
                                  fill="#626262"/>
                        </svg>
                        <input data-target="audioBookTarget" id="paymentAudioBook" type="radio" name="variation_id">
                        <p>Аудиокнига</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
