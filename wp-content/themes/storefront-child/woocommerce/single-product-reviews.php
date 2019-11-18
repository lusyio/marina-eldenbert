<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.5.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $product;

if (!comments_open()) {
    return;
}

?>
<div id="reviews" class="woocommerce-Reviews">
    <div id="comments">
        <h2 class="woocommerce-Reviews-title"><?php
            if (get_option('woocommerce_enable_review_rating') === 'yes' && ($count = $product->get_review_count())) {
                /* translators: 1: reviews count 2: product name */
                printf('Комментарии' . ' (' . esc_html($count) . ')');
            } else {
                _e('Reviews', 'woocommerce');
            }
            ?></h2>

        <?php if (have_comments()) : ?>

            <ol class="comment-list">
                <?php wp_list_comments(apply_filters('woocommerce_product_review_list_args', array('callback' => 'woocommerce_comments'))); ?>
            </ol>

            <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) :
                echo '<nav class="woocommerce-pagination">';
                paginate_comments_links(apply_filters('woocommerce_comment_pagination_args', array(
                    'prev_text' => '&larr;',
                    'next_text' => '&rarr;',
                    'type' => 'list',
                )));
                echo '</nav>';
            endif; ?>

        <?php else : ?>

            <p class="woocommerce-noreviews"><?php _e('There are no reviews yet.', 'woocommerce'); ?></p>

        <?php endif; ?>
    </div>

    <?php if (get_option('woocommerce_review_rating_verification_required') === 'no' || wc_customer_bought_product('', get_current_user_id(), $product->get_id())) : ?>

        <div id="review_form_wrapper" class="mt-4">
            <div id="review_form">
                <?php

                $commenter = wp_get_current_commenter();

                $comment_form = array(
                    'submit_button' => '<input name="%1$s" type="submit" id="%2$s" class="%3$s load-more" value="Оставить комментарий" />',
                    'submit_field' => '<div class="col-12 text-center mb-5 mt-5 pb-5"><p class="form-submit">%1$s %2$s</p></div>',
                    'title_reply' => 'Оставить комментарий',
                    'title_reply_to' => '',
                    'title_reply_before' => '<span id="reply-title" class="comment-reply-title">',
                    'title_reply_after' => '</span>',
                    'comment_notes_before' => '',
                    'comment_notes_after' => '',
                    'fields' => array(
                        'author' => '<div class="col-12 col-lg-5"><div class="row mb-15px"><div class="col">' .
                            '<input class="form-control mb-2" id="author" placeholder="Ваше имя" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" required /></div></div>',
                        'email' => '<div class="row mb-15px"><div class="col">' . '
										<input class="form-control mb-3" id="email" placeholder="Ваш Email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" required /> </div></div><div class="row"><div class="col-12 col-lg-9 offset-0 offset-lg-1"><p class="email-notes mb-0">Email опубликован не будет</p><p class="email-notes mb-0">Нажимая кнопку Оставить комментарий вы даете согласие с <a href="/terms/">Политикой обработки персональных данных</a></p></div></div></div></div>',
                    ),
                    'label_submit' => __('Submit', 'woocommerce'),
                    'logged_in_as' => '',
                    'comment_field' => '',
                );

                if ($account_page_url = wc_get_page_permalink('myaccount')) {
                    $comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf(__('You must be <a href="%s">logged in</a> to post a review.', 'woocommerce'), esc_url($account_page_url)) . '</p>';
                }

                $comment_form['comment_field'] .= '<div class="row"><div class="col-lg-7 col-12"><p class="comment-form-comment"><label  for="comment">' . '<span class="required"></span></label><textarea placeholder="Ваш комментарий" id="comment" name="comment" cols="45" rows="6" required></textarea></p></div>';

                comment_form(apply_filters('woocommerce_product_review_comment_form_args', $comment_form));
                ?>
            </div>
        </div>


    <?php else : ?>

        <p class="woocommerce-verification-required"><?php _e('Only logged in customers who have purchased this product may leave a review.', 'woocommerce'); ?></p>

    <?php endif; ?>

    <div class="clear"></div>
</div>
