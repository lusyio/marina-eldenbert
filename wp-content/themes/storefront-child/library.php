<?php
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
    $count = 0;
    foreach ($libraryBooks as $libraryBook):
        $articles = getArticlesList($libraryBook->get_id());
        $bookMark = getBookmarkMeta($libraryBook->get_id());
        $currentArticle = 0;
        if ($bookMark) {
            foreach ($articles as $key => $article) {
                if ($article->ID == $bookMark) {
                    $currentArticle = $key + 1;
                    break;
                }
            }
        }
        $tags = get_the_terms($libraryBook->get_id(), 'product_tag');
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
        $totalText = count($articles) . ' ' . getNumeral(count($articles), 'глава', 'главы', 'глав');
        $imgsrc = wp_get_attachment_url($libraryBook->get_image_id());
        $libraryId = $libraryBook->get_id();
        if (empty($imgsrc)) :
            $imgsrc = '/wp-content/uploads/woocommerce-placeholder.png';
        endif; ?>
        <div class="library-card" data-num="<?= $count + 1 ?>">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                    <a href="<?= $libraryBook->get_permalink() ?>">
                        <img class="library-card__img" src="<?= $imgsrc ?>" alt="<?= $libraryBook->name ?>">
                    </a>
                </div>
                <div class="col-xl-9 col-lg-8 col-md-8 col-12">
                    <p class="library-card__header"><?= $libraryBook->name ?></p>
                    <div class="library-card-info">
                        <?php if (!$isDraft): ?>
                            <!-- книга завершена-->
                            <div class="library-card-info__status">
                                <img src="/wp-content/themes/storefront-child/svg/svg-complete-book.svg"
                                     alt="complete-book">
                                <div>
                                    <p>Книга завершена</p>
                                    <p><?php echo getNumeral($currentArticle, 'Прочитана', 'Прочитано', 'Прочитано') . ' ' . $currentArticle . ' из ' . count($articles) . ' ' . getNumeral(count($articles), 'главы', 'глав', 'глав'); ?></p>
                                </div>
                            </div>
                            <!-- книга завершена-->
                        <?php else:
                            $newCount = count($articles) - $currentArticle;
                            ?>
                            <!-- книга в процессе-->
                            <div class="library-card-info__status">
                                <img src="/wp-content/themes/storefront-child/svg/svg-process-book.svg"
                                     alt="process-book">
                                <div>
                                    <p>Книга в процессе</p>
                                    <p><?= $lastUpdate ?></p>
                                    <p><?php echo $totalText; ?><?php echo ($newCount > 0) ? '<span>+' . $newCount . ' ' . getNumeral($newCount, 'новая', 'новые', 'новых') . '</span>' : '' ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <a data-toggle="modal" data-target="#deleteModal" href="#" data-href="/my-account/library?remove=<?= $libraryId ?>" class="library-card-info__status deleteModalClick">
                            <svg width="20" height="32" viewBox="0 0 20 32" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.83899 23.4559L9.51336 23.1764L9.18773 23.4559L0.5 30.912V0.5H18.5267V30.912L9.83899 23.4559Z"
                                      stroke="#415996"/>
                                <path d="M9.5134 6.39937L11.0006 9.4128L11.117 9.64852L11.3771 9.68632L14.7025 10.1695L12.2962 12.5151L12.108 12.6986L12.1524 12.9577L12.7205 16.2697L9.74606 14.706L9.51339 14.5836L9.28072 14.706L6.30637 16.2697L6.87441 12.9577L6.91885 12.6986L6.73061 12.5151L4.32425 10.1695L7.64974 9.68632L7.90988 9.64852L8.02622 9.41279L9.5134 6.39937Z"
                                      fill="#FAFAFA" stroke="#415996"/>
                            </svg>
                            <div>
                                <p>В вашей библиотеке</p>
                                <p>удалить из библиотеки</p>
                            </div>
                        </a>
                    </div>
                    <p class="library-card__desc">
                        <?php $desc = strip_tags($libraryBook->get_short_description());
                        $size = 300;

                        echo mb_substr($desc, 0, mb_strrpos(mb_substr($desc, 0, $size, 'utf-8'), ' ', 'utf-8'), 'utf-8');
                        echo (mb_strlen($desc) > $size) ? '...' : '';
                        ?>
                    </p>
                    <div class="library-card-group">
                        <?php
                        readButton(get_permalink(getBookPageIdByBookId($libraryBook->get_id())), $libraryBook->get_id(), 'btnNewBlue library-card-group__read');
                        ?>

                        <?php
                        $downloads = [];
                        if ($libraryBook->is_downloadable('yes') && $libraryBook->has_file()) {
                            if ($libraryBook->get_price() == 0) {
                                $allDownloads = wc_get_free_downloads();
                            } else {
                                $allDownloads = wc_get_customer_available_downloads(get_current_user_id());
                            }
                            foreach ($allDownloads as $oneDownload) {
                                if ($oneDownload['product_id'] == $libraryBook->get_id()) {
                                    $downloads[] = $oneDownload;
                                }
                            }
                            $eBookDownloads = $libraryBook->get_downloads();
                            $eBookPriceHtml = $libraryBook->get_price_html();
                        }
                        if ($libraryBook->get_price() !== '0' && $libraryBook->get_price() !== 0):
                            if (!isBookBought($libraryBook->get_id()) && !in_array('draft', $tagsArray)):?>
                                <form class="cart"
                                      action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
                                      method="post" enctype='multipart/form-data'>
                                    <button type="submit" name="add-to-cart"
                                            value="<?php echo esc_attr($libraryBook->get_id()); ?>"
                                            class="btnNewOrange library-card-group__buy"><?= $isDraft ? 'Подписка за ' : 'Купить книгу зa ' ?><?php echo $libraryBook->get_price_html(); ?>
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
                                </form>
                            <?php else: ?>
                                <?php if ($downloads): ?>
                                    <div class="library-card-group__download">
                                        <span>Вам доступны файлы:</span>
                                        <p>
                                            <?php foreach ($downloads as $key => $download): ?>
                                                <a href="<?= $download['download_url'] ?>"><?= $download['download_name'] ?> <?= $key === array_key_last($downloads) ? '' : ', ' ?></a>
                                            <?php endforeach; ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php else : ?>
                            <?php if ($downloads): ?>
                                <div class="library-card-group__download">
                                    <span>Вам доступны файлы:</span>
                                    <p>
                                        <?php foreach ($downloads as $key => $download): ?>
                                            <a href="<?= $download['download_url'] ?>"><?= $download['download_name'] ?> <?= $key === array_key_last($downloads) ? '' : ', ' ?></a>
                                        <?php endforeach; ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $count++;
    endforeach;
    if ($count > 10) :?>
        <div class="library-card pag">
            <nav id="libraryPagination" aria-label="library">
                <ul class="pagination">

                </ul>
            </nav>
        </div>
    <?php endif;
else: ?>
    <p class="library-empty">Вы пока не добавили ни одной книги</p>
<?php endif;

