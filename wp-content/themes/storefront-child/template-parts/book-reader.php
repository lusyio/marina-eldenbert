<?php
/*
Template Name: book-reader
Template Post Type: post, page, product
*/
?>

<?php get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </div>
    </div>
    <div class="row">
        <?php
        $hasArticle = false;
        $bookCategoryId = get_post_meta($post->ID, 'cat_id', true);
        $bookId = get_post_meta($post->ID, 'book_id', true);

        $book = wc_get_product($bookId);

        if (isset($_GET['a'])) {
            $articleId = intval($_GET['a']);//This is page id or post id
            $content_post = get_post($articleId);
            $articleCategories = wp_get_post_categories($articleId);
            if (in_array($bookCategoryId, $articleCategories)) {
                $isArticle = true;
                $showBuyScreen = false;
                ?>
                <div class="col-lg-7 col-12 order-lg-1 order-2 position-relative">
                    <?php article_content($articleId); ?>
                </div>
                <?php
            }
        }
        if ($showBuyScreen):
            $bookmark = getBookmarkMeta($bookId);
            $link = get_permalink();
            if ($bookmark) {
                $link .= '?a=' . $bookmark;
            } elseif (isset($_COOKIE['b_' . $bookId])) {
                $link .= '?a=' . $_COOKIE['b_' . $bookId];
            }
            ?>
            <div class="col-lg-7 col-12 order-lg-1 order-2 position-relative">
                <div class="reader-content text-center h3 mb-5">Эта глава доступна только купившим книгу</div>
                <div class="text-center"><a class="club-header__btn mb-3" href="<?php echo $book->get_permalink(); ?>">Купить</a></div>
                <div class="text-center"><a class="club-header__btn" href="<?php echo $link ?>">Назад</a></div>
            </div>
        <?php else: ?>
            <div class="col-lg-7 col-12 order-lg-1 order-2 position-relative">
                <div class="reader-content"><?php the_content(); ?></div>
                <div class="text-center"><?php readButton(); ?></div>
            </div>
        <?php endif; ?>
        <div class="col-1 order-lg-2 d-lg-flex d-none reader-hr"></div>
        <div class="col-12 col-lg-4 order-lg-3 order-1 mb-5">
            <div class="filter-collapse-btn d-lg-none d-block" data-toggle="collapse" data-target="#collapseReader"
                 aria-expanded="false" aria-controls="collapseReader">
                Оглавление
            </div>
            <div class="collapse d-lg-block" id="collapseReader">
                <div class="reader-sidebar">
                    <div class="reader-sidebar__image"><?php bookCardInReader(); ?></div>
                    <div class="reader-sidebar__menu"><?php contentList($isArticle); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <?php storefront_display_comments(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
