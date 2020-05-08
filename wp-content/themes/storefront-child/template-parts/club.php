<?php
/*
Template Name: club
Template Post Type: post, page, product
*/

do_action('id_page_check');
?>

<?php get_header(); ?>

<?php
//Получаем контент страницы
$allText = get_the_content();
//Разбиваем на 3 части по (:)
$text_parts = preg_split('~\(:\)~', $allText, 10);
?>
</div>
</div>

<div class="club-container">
    <div class="container club-bg">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-12 offset-0 wow fadeInUp"
                 data-wow-delay="<?php echo $delay ?>s">
                <div class="club-header">
                    <h1 class="club-header__title">VIP-клуб <br>
                        Марины Эльденберт</h1>
                    <a class="club-header__btn scrollTop"
                       href="#howToJoin">Вступить</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container club-content">
    <div class="row">
        <div class="col-lg-10 offset-lg-1 col-12 offset-0 p-lg-0 p-unset wow fadeInUp"
             data-wow-delay="0s">
            <p class="club-content__text mb-5"><?= $text_parts[0] ?>
            </p>
            <p class="club-content__text"><strong>Скидка постоянного читателя
                    составляет 20%</strong></p>
        </div>
    </div>
    <div class="row" id="howToJoin">
        <div class="col-lg-8 offset-lg-1 col-12 offset-0 wow fadeInUp"
             data-wow-delay="0s">
            <h2 class="club-content__title text-left">Как стать участником?</h2>
            <p class="club-content__text text-left mb-5"><?= $text_parts[1] ?></p>
        </div>
        <div class="col-lg-2 col-12 mb-auto mt-5 p-lg-0 p-unset d-md-block d-none wow fadeInUp"
             data-wow-delay="0.2s">
            <img class="club-content__img" src="/wp-content/themes/storefront-child/images/club-coin.png" alt="">
        </div>
        <div class="col-1"></div>
        <div class="col-lg-10 offset-lg-1 col-12 offset-0 wow fadeInUp">
            <?php
            $j = 2;
            foreach ($text_parts as $key => $part):?>
                <?php if ($key >= $j): ?>
                    <p class="club-content__text text-left <?= array_key_last($text_parts) === $key ? 'mb-5' : 'mb-3' ?>">
                        <?= $part ?>
                    </p>
                    <?php $j++; endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 offset-lg-1 col-12 offset-0">
            <div class="club-ranks-row wow fadeInUp"
                 data-wow-delay="0s">
                <div class="row">
                    <div class="col-2 d-md-block d-none"></div>
                    <div class="col-5 col-md"><p>Статус</p></div>
                    <div class="col col-md-3 text-left text-md-center"><p>Условия <span class="d-md-inline d-none">получения</span>
                        </p></div>
                    <div class="col text-center d-md-block d-none"><p>Доступ</p></div>
                    <div class="col text-right text-md-center"><p>Скидка</p></div>
                </div>
            </div>
            <?php
            $allRanks = mycred_get_ranks();
            $ctype = MYCRED_DEFAULT_TYPE_KEY;
            $delay = 0.2;
            ?>
            <?php foreach (array_reverse($allRanks, true) as $rank): ?>
                <?php
                $titles = explode(':', $rank->title);
                ?>
                <div class="club-ranks-row wow fadeInUp"
                     data-wow-delay="<?php echo $delay ?>s">
                    <div class="row">
                        <div class="col-2 m-auto d-md-block d-none"><img src="<?php echo $rank->logo_url; ?>" alt="">
                        </div>
                        <div class="col-5 col-md m-auto"><?php echo $titles[0]; ?></div>
                        <div class="col col-md-3 m-auto text-left text-md-center"><?php echo ($rank->minimum == 0) ? 'Регистрация на сайте' : $rank->minimum . ' Баллов' ?>
                        </div>
                        <div class="col m-auto text-center d-md-block d-none"><?php echo ($rank->minimum == 500) ? '<strong>VIP</strong>' : ' Общий' ?></div>
                        <div class="col m-auto text-right text-md-center"><?php echo (getRankDiscount($rank->post->post_name) == 0) ? '-' : getRankDiscount($rank->post->post_name) . '%' ?></div>
                    </div>
                </div>
                <?php $delay = $delay + 0.2; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
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
        <div class="row wow fadeInUp"
             data-wow-delay="0s">
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

    <?php elseif (get_option('vipForNewUsers', 0) == 1) :
        $text = get_option('freeAccessText', '');
        $formattedText = preg_replace('~\(\(\(~', '<strong>', $text);
        $formattedText = preg_replace('~\)\)\)~', '</strong>', $formattedText);
        ?>
        <div class="row club-footer wow fadeInUp"
             data-wow-delay="0s">
            <div class="col-lg-6 offset-lg-3 col-12 offset-0">

                <p class="club-footer__text"><?php echo $formattedText ?></p>
                <a class="club-footer__btn" href="<?php echo get_permalink(wc_get_page_id('myaccount')) ?>">Вступить в
                    закрытый клуб</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="container">
    <div class="row">

        <?php get_footer(); ?>
