<?php
/**
 * Template used to display post content.
 *
 * @package storefront
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="row">
        <div class="col-lg-4 col-12">
            <div class="post-thumbnail mt-0">
                <a href="<?php
                global $product;
                echo get_permalink(); ?>">
                    <?php echo $product->get_image(385); ?>
                </a>
            </div>
        </div>
        <div class="col-lg-8 col-12">
            <div class="entry-content">
                <h2 class="popular-title"><?php echo $product->get_name(); ?>
                </h2>
                <p class="popular-content">
                    <?php echo $product->get_short_description(); ?>
                </p>
                <div class="row">
                    <div class="col-lg-5 col-12">
                        <a href="<?php echo get_permalink(); ?>"
                           class="popular-btn">
                            Подробнее
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


</article>
