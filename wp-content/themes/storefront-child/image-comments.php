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

<div class="grid">
    <div class="grid-sizer"></div>

    <?php
    wp_list_comments(
        array(
            'style' => 'ol',
            'short_ping' => true,
            'callback' => 'images_comment',
        )
    );
    ?>
</div><!-- .comment-list -->


<?php

$comments_args = array(
    'label_submit' => 'Загрузить изображение',
    'comment_field' => '',
    'title_reply_before' => '',
    'title_reply' => '',
);

echo do_shortcode('[Sassy_Social_Share]');
comment_form($comments_args);
?>
