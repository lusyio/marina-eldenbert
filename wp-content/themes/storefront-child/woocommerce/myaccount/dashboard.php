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
    if ($rankRelativeProgress == 0) {
        $rankRelativeProgress = 1;
    }
    $pointsForNextRank = $myRank->maximum + 1 - $balance;
    $currentRankTotalProgress = $myRank->maximum + 1 - $myRank->minimum;
    $progress = round(($rankRelativeProgress / $currentRankTotalProgress) * 100);
    ?>
    <div class="row">
        <div class="col-12">
            <p class="club-content__title">Ваш текущий статус</p>
            <div class="row mb-4">
                <div class="col-5 m-auto text-left">
                    <div class="row">
                        <div class="col-5 m-auto d-lg-block d-none">
                            <?php echo getRankLogo($myRank, '100'); ?>
                        </div>
                        <div class="col-lg-7 col-12 m-auto pl-lg-0 pl-unset">
                            <?php echo getRankTitle($myRank); ?>
                        </div>
                    </div>
                </div>
                <div class="col-2 m-auto text-center p-lg-unset p-0">
                    <span class="status-count"><?php echo $balance; ?> из <?php echo $myRank->maximum + 1; ?></span>
                </div>
                <div class="col-5 m-auto text-right">
                    <div class="row">
                        <div class="col-lg-7 col-12 m-auto pr-lg-unset pr-unset">
                            <?php echo getRankTitle($nextRank); ?>
                        </div>
                        <div class="col-5 m-auto d-lg-block d-none">
                            <?php echo getRankLogo($nextRank, '100'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="progress progress-status">
                        <div class="progress-bar" role="progressbar" style="width: <?php echo $progress ?>%"
                             aria-valuenow="0" aria-valuemin="0"
                             aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row mb-5">
        <div class="col-12">
            <p class="club-content__title">Поздравляем! Вам предоставлен доступ в закрытый клуб</p>
        </div>
        <div class="col-12 m-auto text-left">
            <div class="row">
                <div class="col-12 mb-3">
                    <?php echo getRankLogo($myRank, '100'); ?>
                </div>
                <div class="col-12">
                    <?php echo getRankTitle($myRank); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif;
$abonement = hasAbonement($user_id);
if ($abonement):
?>
<div class="text-center mb-3"><p>У вас есть абонемент на чтение книг до <?php echo date('d.m.Y', strtotime($abonement)) ?>. Перейдите в <a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>">магазин</a>, откройте любую понравившуюся книгу и нажмите кнопку "Читать"</p></div>
<?php endif; ?>
    <p><?php
        /* translators: 1: user display name 2: logout url */
        printf(
            __('Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce'),
            '<strong>' . esc_html($current_user->display_name) . '</strong>',
            esc_url(wc_logout_url(wc_get_page_permalink('myaccount')))
        );
        ?></p>

    <p><?php
        ob_start();
        printf(
            __('From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce'),
            esc_url(wc_get_endpoint_url('orders')),
            esc_url(wc_get_endpoint_url('edit-address')),
            esc_url(wc_get_endpoint_url('edit-account'))
        );
        $noticeHtml = ob_get_clean();
        $noticeHtml = preg_replace('~Из главной страницы аккаунта~', 'На главной странице аккаунта', $noticeHtml);
        echo $noticeHtml;
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
