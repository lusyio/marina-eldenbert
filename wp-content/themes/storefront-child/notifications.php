<?php
$notifications = getNotifications();
if (countNewNotifications() > 0):
    ?>

<?php endif; ?>
<?php if (count($notifications) > 0): ?>
    <?php usort($notifications, function ($a, $b) {
        if ($a->view_status == $b->view_status) {
            return strtotime($b->notification_date) - strtotime($a->notification_date);
        }
        else {
            return $a->view_status - $b->view_status;
        }
    }); ?>
    <?php
    $hasNew = false;
    foreach ($notifications as $notification):
        if ($notification->view_status == 0) {
            $hasNew = true;
            echo getNotificationCard($notification);
        }?>
    <?php endforeach;
    if ($hasNew): ?>
        <div class="notification-card" style="">
            <div class="row">
                <div class="col-lg-1 col-2">
                </div>
                <div class="col-lg-11 col-10 pl-lg-3 m-auto pl-0">
                    <div class="row">
                        <div class="col-lg-8 col-12">
                            <div class="notification-card__text">
                                <form method="POST">
                                    <input type="hidden" name="readNotifications" value="1">
                                    <button type="submit" class="woocommerce-Button">Пометить все, как прочитанные
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-4 col-12 notification-card__date m-auto text-left text-lg-right">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php
    foreach ($notifications as $notification){
        if ($notification->view_status > 0) {
            echo getNotificationCard($notification);
        }
    }
    ?>
<?php else: ?>
    <p class="library-empty">Новый уведомлений нет</p>
<?php endif; ?>

<button id="more" class="woocommerce-Button m-auto" style="display: none">Загрузить еще</button>

<script>
    jQuery(function ($) {
        const perPage = 10;
        let cards = $('.notification-card');
        let count = cards.length;
        let visible = 0;
        let moreButton = $('#more');

        cards.each(function (i, card) {
            if (i < perPage) {
                $(card).show();
                visible++;
                console.log('visible ' + visible);
                console.log('count ' + count);
                if (visible < count) {
                    $(moreButton).show();
                } else {
                    $(moreButton).hide();
                }
            } else {
                return false;
            }
        });
        $('#more').on('click', function () {
            let limit = visible + perPage;
            cards.each(function (i, card) {
                if (i < (limit)) {
                    $(card).show();
                    if (i >= visible) {
                        visible++;
                    }
                    if (visible == count) {
                        $(moreButton).hide();
                    }
                } else {
                    return false;
                }
            });
        })
    })
</script>
