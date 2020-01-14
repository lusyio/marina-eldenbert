jQuery(function ($) {

    let updater = setInterval(() => update(), 3000);
    function update() {
        $.ajax({
            url: myajax.url,
            data: {
                'action': 'update_notification',
            },
            type: 'POST',
            success: function (data) {
                data = +data;
                    let counter = $('#notificationCounter');
                    if (data > 0) {
                        console.log(data);
                        counter.show();
                        counter.text(data);
                    } else {
                        counter.hide();
                    }
            }
        });
        return false;
    }
})