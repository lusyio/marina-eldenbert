<?php
/*
Template Name: book-reader
Template Post Type: post, page, product
*/
?>

<?php get_header(); ?>

<div class="container pt-5 pb-5 popular">
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
            <div class="col-7">
                <?php article_content($articleId); ?>
            </div>
                <?php
            }
        }
        if (!$isArticle): ?>
            <div class="col-7">
                <div><?php the_content(); ?></div>
                <div class="text-center"><?php readButton(); ?></div>
            </div>
        <?php endif; ?>
        <div class="col-4 offset-1">
            <div class="mb-5"><?php bookCardInReader(); ?></div>
            <div><?php contentList($isArticle); ?></div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <?php storefront_display_comments(); ?>
        </div>
    </div>
</div>

<?php adultModal(); ?>
<?php get_footer(); ?>
