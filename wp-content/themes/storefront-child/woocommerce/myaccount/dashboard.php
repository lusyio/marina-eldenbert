<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$hasVipStatus = get_user_meta(get_current_user_id(), 'vipStatus', true);

$ctype = MYCRED_DEFAULT_TYPE_KEY;
$user_id = mycred_get_user_id('current');
$account_object = mycred_get_account($user_id);
$balance = $account_object->total_balance;
$myRank = mycred_get_my_rank();
$allRanks = mycred_get_ranks();
$nextRank = null;
foreach ($allRanks as $rank) {
    if ($myRank->maximum + 1 == $rank->minimum) {
        $nextRank = $rank;
    }
}
if (!is_null($nextRank)):
    $rankRelativeProgress = $balance - $myRank->minimum;
    $pointsForNextRank = $myRank->maximum + 1 - $balance;
    $currentRankTotalProgress = $myRank->maximum + 1 - $myRank->minimum;
    $progress = round(($rankRelativeProgress / $currentRankTotalProgress) * 100);
?>
    <div class="row mb-5">
        <div class="col-12 progress-dashboard">
            <div class="row">
                <div class="col left">
                    <?php  ?>
                    <?php echo getRankLogo($myRank, '100'); ?>
                </div>
                <div class="col right text-right">
                    <?php echo getRankLogo($nextRank, '100'); ?>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: <?php echo $progress ?>%" aria-valuenow="0" aria-valuemin="0"
                     aria-valuemax="100"><?php echo $balance; ?>/<?php echo $myRank->maximum + 1; ?></div>
            </div>
            <div class="row">
                <div class="col">
                    <p><?php echo getRankTitle($myRank); ?></p>
                </div>
                <div class="col text-right">
                    <p><?php echo getRankTitle($nextRank); ?></p>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row mb-5">
        <div class="col-12 progress-dashboard">
            <div class="row">
                <div class="col left">
                    <p class="progress-dashboard__text">
                        Поздравляем! У вам предоставлен доступ в закрытый клуб
                    </p>
                    <?php echo getRankLogo($myRank, '100'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p><?php echo getRankTitle($myRank); ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endif;

?>
    <p><?php
        /* translators: 1: user display name 2: logout url */
        printf(
            __('Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce'),
            '<strong>' . esc_html($current_user->display_name) . '</strong>',
            esc_url(wc_logout_url(wc_get_page_permalink('myaccount')))
        );
        ?></p>

    <p><?php
        printf(
            __('From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce'),
            esc_url(wc_get_endpoint_url('orders')),
            esc_url(wc_get_endpoint_url('edit-address')),
            esc_url(wc_get_endpoint_url('edit-account'))
        );
        ?></p>

<?php
/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action('woocommerce_account_dashboard');

/**
 * Deprecated woocommerce_before_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_before_my_account');

/**
 * Deprecated woocommerce_after_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_after_my_account');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
