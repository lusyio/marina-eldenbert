jQuery(function ($) {
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        let nameEQ = name + "=";
        let ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    let fontSize
    let fontWeight

    function setFont() {
        if (!getCookie('settings_font_size')) {
            setCookie('settings_font_size', 16, 30)
            fontSize = 16;
            return fontSize
        } else {
            fontSize = Number(getCookie('settings_font_size'));
            return fontSize
        }
    }

    function setWeight() {
        if (!getCookie('settings_font_weight')) {
            setCookie('settings_font_weight', 300, 30)
            fontWeight = 300;
            return fontWeight
        } else {
            fontWeight = Number(getCookie('settings_font_weight'))
            if (fontWeight === 700) {
                $('#increaseWeight').addClass('active')
            }
            return fontWeight
        }
    }

    $('.next-page-btn').on('click', function () {
        let totalPages = $(this).closest('.pagination').data('pages');
        let currentPage = $('.page-item.active').find('.post-page-numbers').data('page') || 1;
        if (currentPage == totalPages) {
            window.location.href = $(this).data('link');
        } else {
            let nextPage = $('.post-page-numbers[data-page=' + ++currentPage + ']');
            nextPage.trigger('click');
        }

    });
    $('.prev-page-btn').on('click', function () {
        let currentPage = $('.page-item.active').find('.post-page-numbers').data('page') || 1;
        if (currentPage == 1) {
            window.location.href = $(this).data('link');
        } else {
            let prevPage = $('.post-page-numbers[data-page=' + --currentPage + ']');
            prevPage.trigger('click');
        }
    });

    $('.post-page-numbers').on('click', function (e) {
        e.preventDefault();
        if ($(this).parent('li').hasClass('active')) {
            return;
        }
        let totalPages = $(this).closest('.pagination').data('pages');
        let pageToLoad = $(this).data('page');
        let prevPageButton = $('.prev-page-btn');
        let nextPageButton = $('.next-page-btn');

        if (pageToLoad == 1) {
            if ($(prevPageButton).data('article-id') == 0) {
                $(prevPageButton).parent('li').addClass('d-none');
            } else {
                $(prevPageButton).find('span').text($(prevPageButton).data('for-article'))
            }
        } else {
            if (totalPages > 1) {
                $(prevPageButton).parent('li').removeClass('d-none');
                $(prevPageButton).find('span').text($(prevPageButton).data('for-page'))
            }
        }

        if (pageToLoad === totalPages) {
            if ($(nextPageButton).data('article-id') == 0) {
                $(nextPageButton).parent('li').addClass('d-none');
            } else {
                $(nextPageButton).find('span').text($(nextPageButton).data('for-article'))
            }
        } else {
            if (totalPages > 1) {
                $(nextPageButton).parent('li').removeClass('d-none');
                $(nextPageButton).find('span').text($(nextPageButton).data('for-page'))
            }
        }

        $('.post-page-numbers').parent('li').removeClass('active');
        $('.post-page-numbers').parent('li').addClass('d-none');
        $('.post-page-numbers[data-page=' + pageToLoad + ']').parent('li').addClass('active');

        let firstDots = $('.first-dots');
        let lastDots = $('.last-dots');
        if (pageToLoad > 3) {
            firstDots.removeClass('d-none');
        } else {
            firstDots.addClass('d-none');
        }

        if (pageToLoad < totalPages - 2) {
            lastDots.removeClass('d-none');
        } else {
            lastDots.addClass('d-none');
        }

        let visiblePages = [pageToLoad - 1, pageToLoad, pageToLoad + 1];

        $(visiblePages).each(function (i, value) {
            $('.post-page-numbers[data-page=' + value + ']').parent('li').removeClass('d-none');
        });
        $('.post-page-numbers[data-page=1]').parent('li').removeClass('d-none');
        $('.post-page-numbers[data-page=' + totalPages + ']').parent('li').removeClass('d-none');

        $.ajax({
            url: myajax.url,
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
                $('#articleText p, #settingsModal .font-size').css('font-size', setFont)
                $('#articleText p, #settingsModal .font-size').css('font-weight', setWeight)
                $('#articleText').css('opacity', 1);
                $('#articleSpinner').css('opacity', 0);
            }
        });
        return false;
    });
});