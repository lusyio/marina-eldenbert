<?php
$notifications = getNotifications();
//var_dump($notifications);
if (countNewNotifications() > 0):
?>
<div class="col-12 read-notification">
<form method="POST">
    <input type="hidden" name="readNotifications" value="1">
    <button type="submit" class="woocommerce-Button m-auto">Пометить все, как прочитанные</button>
</form>
</div>
<?php endif; ?>
<?php foreach ($notifications as $notification): ?>
<?php echo getNotificationCard($notification); ?>
<?php endforeach; ?>
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
