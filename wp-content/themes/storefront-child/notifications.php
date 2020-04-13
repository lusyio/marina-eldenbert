<?php
$notifications = getNotifications();
if (countNewNotifications() > 0):
    ?>
    <div class="col-12 read-notification">
        <form method="POST">
            <input type="hidden" name="readNotifications" value="1">
            <button type="submit" class="woocommerce-Button m-auto">Пометить все, как прочитанные</button>
        </form>
    </div>
<?php endif; ?>
<?php if (count($notifications) > 0): ?>
    <?php uasort($notifications, function ($a, $b) {
        if ($a->view_status == 0) {
            return -1;
        }
    }); ?>
    <?php foreach ($notifications as $notification): ?>
        <?php echo getNotificationCard($notification); ?>
    <?php endforeach; ?>
<?php else: ?>
    <div class="col-md-8 offset-md-2 p-5 text-center">
        <p>Здесь будут появляться уведомления о новых главах, ответах и лайках к вашим комментариям</p>
    </div>
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
