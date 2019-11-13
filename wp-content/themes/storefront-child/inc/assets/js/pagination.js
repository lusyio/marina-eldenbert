jQuery(function ($) {
    $('.next-page-btn').on('click', function () {
        let currentPage = $('.page-item.active').find('.post-page-numbers').data('page');
        let nextPage = $('.post-page-numbers[data-page=' + ++currentPage + ']');
        nextPage.trigger('click');

    });
    $('.prev-page-btn').on('click', function () {
        let currentPage = $('.page-item.active').find('.post-page-numbers').data('page');
        let prevPage = $('.post-page-numbers[data-page=' + --currentPage + ']');
        prevPage.trigger('click');

    });
    $('.post-page-numbers').on('click', function (e) {
        e.preventDefault();
        let pageToLoad = $(this).data('page');
        if ($(this).parent('li').hasClass('active')) {
            return;
        }
        $('.post-page-numbers').parent('li').removeClass('active');
        $('.post-page-numbers[data-page=' + pageToLoad + ']').parent('li').addClass('active');

        $.ajax({
            url: myajax.url,
            // dataType: 'json',
            data: {
                'action': 'custom_pagination',
                'article': myajax.articleId,
                'page': pageToLoad,
            },
            type: 'POST',
            beforeSend: function () {
                $([document.documentElement, document.body]).animate({
                    scrollTop: $('.entry-title').offset().top - 50
                }, 500);
                $('#articleText').css('opacity', 0);
                $('#articleSpinner').css('opacity', 1);
            },
            success: function (data) {
                $('#articleText').html(data);
                $('#articleText').css('opacity', 1);
                $('#articleSpinner').css('opacity', 0);
            }
        });
        return false;
    });
});