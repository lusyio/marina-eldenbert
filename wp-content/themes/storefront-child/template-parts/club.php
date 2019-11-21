<?php
/*
Template Name: club
Template Post Type: post, page, product
*/
?>

<?php get_header(); ?>

</div>
</div>

<div class="club-container">
    <div class="container club-bg">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-12 offset-0">
                <div class="club-header">
                    <h2 class="club-header__title">Закрытый клуб
                        Марины Эльденберт</h2>
                    <p class="club-header__text">Закрытый тайный клуб, доступный только для избранных читателей.
                        Участвуй в жизни сайта, комментируй. общайся, рассказывай друзьям о новостях и событиях в
                        соцсетях и зарабатывай за это балы. Члены клуба получают эксклюзивные материалы по книгам Марины
                        Эльденберт.</p>
                    <a class="club-header__btn"
                       href="<?php echo get_permalink(wc_get_page_id('myaccount')) ?>">Вступить</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container club-content">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 col-12 offset-0">
            <p class="club-content__title">Что дает членство в клубе?</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 offset-lg-1 col-12 offset-0 p-lg-0 p-unset">
            <p class="club-content__text">Всем членам VIP клуба открывается доступ к закрытому разделу сайта, в котором
                публкиуются эксклюзивные материалы, бонусные рассказы к любимым историям, проводятся розыгрыши книг с
                автографом, абонементов на бесплатное чтение и сувениров от автора. <strong>Скидка постоянного читателя
                    составляет 20%</strong></p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 offset-lg-1 col-12 offset-0">
            <p class="club-content__title text-left">Как вступить в клуб?</p>
            <p class="club-content__text text-left">Чтобы получить VIP-карточку, нужно достичь уровня «Платиновая
                драконесса». По
                достижении этого уровня, вам автоматически выдается VIP-карта, и вы можете посещать закрытый раздел на
                сайте.
            </p>
        </div>
        <div class="col-lg-2 col-12 m-auto p-lg-0 p-unset">
            <img class="club-content__img" src="/wp-content/themes/storefront-child/images/club-coin.png" alt="">
        </div>
        <div class="col-1"></div>
    </div>
    <?php
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
        <?php if (is_user_logged_in()): ?>
        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-12 offset-0">
                <p class="club-content__title">Ваш текущий статус</p>
                <div class="row mb-4">
                    <div class="col-5 m-auto text-left">
                        <div class="row">
                            <div class="col-5 m-auto">
                                <?php echo getRankLogo($myRank, '100'); ?>
                            </div>
                            <div class="col-7 m-auto pl-lg-0 pl-unset">
                                <?php echo getRankTitle($myRank); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col m-auto text-center">
                        <span class="status-count"><?php echo $balance; ?> из <?php echo $myRank->maximum + 1; ?></span>
                    </div>
                    <div class="col-5 m-auto text-right">
                        <div class="row">
                            <div class="col-7 m-auto pr-lg-0 pr-unset">
                                <?php echo getRankTitle($nextRank); ?>
                            </div>
                            <div class="col-5 m-auto">
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
        <div class="row club-footer">
            <div class="col-lg-6 offset-lg-3 col-12 offset-0">
                <p class="club-footer__text">Зарегистрируйся на сайте
                    <strong>до 31 декабря 2019</strong>
                    и получи моментальный доступ</p>
                <a class="club-footer__btn" href="<?php echo get_permalink(wc_get_page_id('myaccount')) ?>">Вступить в
                    закрытый клуб</a>
            </div>
        </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<div class="container">
    <div class="row">


        <?php get_footer(); ?>
