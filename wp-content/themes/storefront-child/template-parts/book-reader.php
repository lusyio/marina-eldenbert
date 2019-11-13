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

        if (isset($_GET['a'])) {
            $articleId = intval($_GET['a']);//This is page id or post id
            $content_post = get_post($articleId);
            $articleCategories = wp_get_post_categories($articleId);
            if (in_array($bookCategoryId, $articleCategories)) {
                $isArticle = true; ?>
                <div class="col-lg-7 col-12 order-lg-1 order-2 position-relative">
                    <?php article_content($articleId); ?>
                </div>
                <?php
            }
        }
        if (!$isArticle): ?>
            <div class="col-lg-7 col-12 order-lg-1 order-2 position-relative">
                <div class="reader-content"><?php the_content(); ?></div>
                <div class="text-center"><?php readButton(); ?></div>
            </div>
        <?php endif; ?>
        <div class="col-1 order-lg-2 d-lg-flex d-none reader-hr"></div>
        <div class="col-12 col-lg-4 order-lg-3 order-1">
            <div class="reader-sidebar">
                <div class="reader-sidebar__image"><?php bookCardInReader(); ?></div>
                <div class="reader-sidebar__menu"><?php contentList($isArticle); ?></div>
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
