<?php
/**
 * Template used to display post content.
 *
 * @package storefront
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="post-thumbnail text-lg-left text-center">
        <?php the_post_thumbnail(); ?>
    </div>
    <p class="post-time"><?= get_the_date() ?></p>
    <div class="entry-content">
        <?php
        the_content();
        ?>
    </div>
    <?php echo do_shortcode('[Sassy_Social_Share]'); ?>
</article>
