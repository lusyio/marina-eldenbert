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
        if ($(this).parent('li').hasClass('active')) {
            return;
        }

        let pageToLoad = $(this).data('page');
        // $(this).parent('li').removeClass('active');
        $('.post-page-numbers').parent('li').removeClass('active');
        $('.post-page-numbers[data-page=' + pageToLoad + ']').parent('li').addClass('active');

        let totalPages = $(this).closest('.pagination').data('pages');
        console.log(totalPages);

        if (totalPages > 5) {

            let mobileVisiblePages = [];
            if (pageToLoad < 3) {
                mobileVisiblePages = [1, 2, 3, 4, 5]
            } else if (pageToLoad > (totalPages - 2)) {
                for (let i = totalPages; i > totalPages - 5; i--) {
                    mobileVisiblePages.push(i);
                }
            } else {
                for (let i = pageToLoad - 2; i <= pageToLoad + 2; i++) {
                    mobileVisiblePages.push(i);
                }
            }
            $('.mobile-visible').each(function (i, el) {
                $(el).removeClass('mobile-visible');
            });
            $(mobileVisiblePages).each(function (i, va) {
                console.log(va);
                $('.post-page-numbers[data-page=' + va + ']').parent('li').addClass('mobile-visible');
            });
        }


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