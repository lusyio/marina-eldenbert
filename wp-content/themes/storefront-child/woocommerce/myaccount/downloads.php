<?php
/**
 * Downloads
 *
 * Shows downloads on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/downloads.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'post__in' => $inLibraryIds,
);
$libraryQuery = new WP_Query($args);
$libraryBooks = [];
while ($libraryQuery->have_posts()) :
    $libraryQuery->the_post();
    global $product;
    $libraryBooks[] = $product;
endwhile;
wp_reset_query();

$downloads     = WC()->customer->get_downloadable_products();
$has_downloads = (bool) $downloads;

do_action( 'woocommerce_before_account_downloads', $has_downloads ); ?>

<?php if ( $has_downloads ) : ?>

	<?php do_action( 'woocommerce_before_available_downloads' ); ?>

	<?php do_action( 'woocommerce_available_downloads', $downloads ); ?>

	<?php do_action( 'woocommerce_after_available_downloads' ); ?>

<?php else : ?>
	<div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="btn btn-primary" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			<?php esc_html_e( 'Go shop', 'woocommerce' ); ?>
		</a>
		<?php esc_html_e( 'No downloads available yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_downloads', $has_downloads ); ?>
