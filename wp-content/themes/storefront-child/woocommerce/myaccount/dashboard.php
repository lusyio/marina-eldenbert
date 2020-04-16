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
$user = wp_get_current_user();
$inLibraryIds = getLibraryBookIds($user->ID);

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

?>
    <div class="my-account-card">
        <?php
        if (!is_null($nextRank)):
            $rankRelativeProgress = $balance - $myRank->minimum;
            if ($rankRelativeProgress == 0) {
                $rankRelativeProgress = 1;
            }
            $pointsForNextRank = $myRank->maximum + 1 - $balance;
            $currentRankTotalProgress = $myRank->maximum + 1 - $myRank->minimum;
            $progress = round(($rankRelativeProgress / $currentRankTotalProgress) * 100);
            ?>
            <div class="my-account-status">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-12 rank-status">
                        <div class="d-flex">
                            <?php echo getRankLogo($myRank, '70'); ?>
                            <div class="mt-auto mb-auto">
                                <p>Ваш текущий статус:</p>
                                <?php echo getRankTitle($myRank); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12 m-auto">
                        <p class="my-account-status__discount progressbar">Ваша скидка:
                            <span><?= getRankDiscount($myRank->post->post_name) ?>%</span></p>
                        <div class="progress progress-status">
                            <div class="progress-bar" role="progressbar" style="width: <?php echo $progress ?>%"
                                 aria-valuenow="0" aria-valuemin="0"
                                 aria-valuemax="100"></div>
                            <p>До получения следующего
                                статуса:<span><?php echo $balance; ?> из <?php echo $myRank->maximum + 1; ?></span></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="my-account-status">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-12 rank-status">
                        <div class="d-flex">
                            <?php echo getRankLogo($myRank, '70'); ?>
                            <div class="mt-auto mb-auto">
                                <p>Ваш текущий статус:</p>
                                <?php echo getRankTitle($myRank); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12 m-auto text-lg-right text-left">
                        <p class="my-account-status__discount">Ваша скидка:
                            <span><?= getRankDiscount($myRank->post->post_name) ?>%</span></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="my-account-vip">
                        <p class="my-account-card__header">VIP-клуб</p>
                        <p>Поздравляем! Теперь вам предоставлен <br>
                        доступ к закрытым разделам сайта</p>
                        <a href="/club">Перейти в клуб</a>
                    </div>
                </div>
            </div>
        <?php endif;

        $abonement = hasAbonement($user_id);
        if ($abonement):
            ?>
            <div class="my-account-subscription">
                <p>У вас есть абонемент на чтение книг до <?php echo date('d.m.Y', strtotime($abonement)) ?>.
                    Перейдите в <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>">магазин</a>, откройте
                    любую понравившуюся книгу и нажмите кнопку "Читать"</p>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-12">
                <div class="my-account-noty">
                    <p class="my-account-card__header">Уведомления</p>
                    <?php if (sprintf(countNewNotifications()) != 0): ?>
                        <p>
                            <?php
                            plural_form(
                                sprintf(countNewNotifications()),
                                /* варианты написания для количества 1, 2 и 5 */
                                array('новое уведомление', 'новых уведомления', 'новых уведомлений')
                            );
                            ?>
                        </p>
                    <?php else: ?>
                        <p>Новых уведомлений нет</p>
                    <?php endif; ?>
                    <a href="/my-account/notifications">Посмотреть уведомления</a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12">
                <div class="my-account-library">
                    <p class="my-account-card__header">Библиотека</p>
                    <?php if (count($inLibraryIds) !== 0): ?>
                        <p>Вы добавили в свою</p>
                        <p>библиотеку <?php
                            plural_form(
                                count($inLibraryIds),
                                /* варианты написания для количества 1, 2 и 5 */
                                array('книгу', 'книги', 'книг')
                            );
                            ?></p>
                    <?php else: ?>
                        <p>Вы еще не добавили</p>
                        <p>книги в библиотеку</p>
                    <?php endif; ?>
                    <a href="/my-account/downloads">Перейти в библиотеку</a>
                </div>
            </div>
        </div>
    </div>
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
