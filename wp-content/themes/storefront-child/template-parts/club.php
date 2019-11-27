<?php
/*
Template Name: club
Template Post Type: post, page, product
*/

do_action('id_page_check');
?>

<?php get_header(); ?>

</div>
</div>

<div class="club-container">
    <div class="container club-bg">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-12 offset-0">
                <div class="club-header">
                    <h2 class="club-header__title">VIP-клуб
                        Марины Эльденберт</h2>
                    <p class="club-header__text">Хочешь получить доступ к неопубликованным историям и рассказам,
                        получать эксклюзивные подарки от автора, а также покупать книги с постоянной скидкой? Вступай в
                        VIP-клуб Марины Эльденберт и становись членом нашего большого сообщетсва!</p>
                    <a class="club-header__btn scrollTop"
                       href="#howToJoin">Вступить</a>
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
            <p class="club-content__text mb-5">VIP-клуб – это территория наших читателей, где:
                выкладываются бонусные рассказы к любимым историям
                и проводятся закрытые розыгрыши: книги на бумаге с автографами, абонементы на бесплатное чтение,
                сувениры от авторов (закладки, открытки, календари)
            </p>
            <p class="club-content__text"><strong>Скидка постоянного читателя
                    составляет 20%</strong></p>
        </div>
    </div>
    <div class="row" id="howToJoin">
        <div class="col-lg-8 offset-lg-1 col-12 offset-0">
            <p class="club-content__title text-left">Как стать участником?</p>
            <p class="club-content__text text-left mb-5">Чтобы получить VIP-карточку, нужно достичь уровня «Платина».
                После
                этого вам автоматически выдается VIP-карта, и вы можете посещать закрытый раздел на сайте.</p>
            <p class="club-content__text text-left mb-5">
                На нашем сайте существует система рейтингов - комментируя книги и обсуждая с другими участниками сюжеты
                и героев, вы получаете различные бонусы:
            </p>
        </div>
        <div class="col-lg-2 col-12 mb-auto mt-5 p-lg-0 p-unset">
            <img class="club-content__img" src="/wp-content/themes/storefront-child/images/club-coin.png" alt="">
        </div>
        <div class="col-1"></div>
    </div>
    <div class="row">
        <div class="col-lg-10 offset-lg-1 col-12 offset-0">
            <div class="club-ranks-row">
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col"><p>Статус</p></div>
                    <div class="col col-lg-3 text-center"><p>Условия получения</p></div>
                    <div class="col text-center"><p>Доступ</p></div>
                    <div class="col text-center"><p>Скидка</p></div>
                </div>
            </div>
            <?php
            $allRanks = mycred_get_ranks();
            ?>
            <?php foreach (array_reverse($allRanks, true) as $rank): ?>
                <div class="club-ranks-row">
                    <div class="row">
                        <div class="col-2 m-auto"><img src="<?php echo $rank->logo_url; ?>" alt=""></div>
                        <div class="col m-auto"><?php echo $rank->title; ?></div>
                        <div class="col col-lg-3 m-auto text-center"><?php echo ($rank->minimum == 0) ? 'Регистрация на сайте' : $rank->minimum . ' комментариев' ?>
                        </div>
                        <div class="col m-auto text-center">Общий</div>
                        <div class="col m-auto text-center">-</div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    $ctype = MYCRED_DEFAULT_TYPE_KEY;
    $user_id = mycred_get_user_id('current');
    $account_object = mycred_get_account($user_id);
    $balance = $account_object->total_balance;
    $myRank = mycred_get_my_rank();
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
                            <div class="col-lg-7 col-12 m-auto pr-lg-0 pr-unset">
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
    <?php endif; ?>

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
</div>

<div class="container">
    <div class="row">


        <?php get_footer(); ?>
