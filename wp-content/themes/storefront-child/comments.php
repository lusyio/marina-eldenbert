<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package storefront
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}
?>

<section id="comments" class="comments-area" aria-label="<?php esc_html_e('Post Comments', 'storefront'); ?>">
    <?php if (is_page(35) || get_current_template() == 'book-reader.php') {
        echo do_shortcode('[Sassy_Social_Share]');
    }
    ?>
    <?php
    if (have_comments()) :
        ?>

        <h2 class="comments-title">
            <?php
            printf('Комментарии' . ' (' . number_format_i18n(get_comments_number()) . ')');
            ?>
        </h2>

    <?php
    endif;

    $args = apply_filters(
        'storefront_comment_form_args', array(
            'title_reply' => '',
            'title_reply_to' => '',
            'title_reply_before' => '',
            'title_reply_after' => '',
            'comment_notes_before' => '',
            'comment_notes_after' => '',
        )
    );

    comment_form($args);

    if (have_comments()) :

        if (get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through.
            ?>
            <nav id="comment-nav-above" class="comment-navigation" role="navigation"
                 aria-label="<?php esc_html_e('Comment Navigation Above', 'storefront'); ?>">
                <span class="screen-reader-text"><?php esc_html_e('Comment navigation', 'storefront'); ?></span>
                <div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'storefront')); ?></div>
                <div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'storefront')); ?></div>
            </nav><!-- #comment-nav-above -->
        <?php endif; // Check for comment navigation.
        ?>

        <ol class="comment-list">
            <?php
            wp_list_comments(
                array(
                    'style' => 'ol',
                    'short_ping' => true,
                    'callback' => 'storefront_comment',
                )
            );
            ?>
        </ol><!-- .comment-list -->

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through. ?>
        <nav id="comment-nav-below" class="comment-navigation" role="navigation"
             aria-label="<?php esc_html_e('Comment Navigation Below', 'storefront'); ?>">
            <span class="screen-reader-text"><?php esc_html_e('Comment navigation', 'storefront'); ?></span>
            <div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'storefront')); ?></div>
            <div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'storefront')); ?></div>
        </nav><!-- #comment-nav-below -->
    <?php
    endif; // Check for comment navigation.

    endif;

    if (!comments_open() && 0 !== intval(get_comments_number()) && post_type_supports(get_post_type(), 'comments')) :
        ?>
        <p class="no-comments"><?php esc_html_e('Comments are closed.', 'storefront'); ?></p>
    <?php endif; ?>


</section><!-- #comments -->
