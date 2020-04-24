new WOW().init();

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

    function substringOut(value, number) {
        if (value.length > number) {
            return `${value.substring(0, number)}...`
        } else {
            return value
        }
    }

    let fontSize
    let fontWeight
    let theme

    if (document.documentElement.clientWidth <= 576) {
        $('#articleText , #settingsModal .font-size')
            .removeClass('litnet1')
            .removeClass('litnet2')
            .removeClass('litnet3')
            .removeClass('litnet4')
            .removeClass('none')
            .addClass(setTheme)

        function setTheme() {
            if (!getCookie('settings_theme')) {
                setCookie('settings_theme', 'none', 30)
                theme = 'none'
                $('#articleText , #settingsModal .font-size')
                    .removeClass('litnet1')
                    .removeClass('litnet2')
                    .removeClass('litnet3')
                    .removeClass('litnet4')
                    .removeClass('none')
                    .addClass(theme)
                return theme
            } else {
                theme = getCookie('settings_theme')
                $('#articleText , #settingsModal .font-size')
                    .removeClass('litnet1')
                    .removeClass('litnet2')
                    .removeClass('litnet3')
                    .removeClass('litnet4')
                    .removeClass('none')
                    .addClass(theme)
                return theme
            }
        }

        $('#resetTheme').on('click', () => {
            theme = 'none'
            $('#settingsModal .font-size')
                .removeClass('litnet1')
                .removeClass('litnet2')
                .removeClass('litnet3')
                .removeClass('litnet4')
                .removeClass('none')
                .addClass('none')
        })

        $('.theme-buttons button').on('click', () => {
            if ($(this).context.activeElement.id === 'resetTheme') {
                theme = 'none'
            } else {
                theme = $(this).context.activeElement.id
            }
            $('#articleText , #settingsModal .font-size')
                .removeClass('litnet1')
                .removeClass('litnet2')
                .removeClass('litnet3')
                .removeClass('litnet4')
                .removeClass('none')
                .addClass(theme)
        })

        $('#saveSettings').on('click', () => {
            setCookie('settings_theme', theme, 30)
            $('#articleText , #settingsModal .font-size')
                .removeClass('litnet1')
                .removeClass('litnet2')
                .removeClass('litnet3')
                .removeClass('litnet4')
                .removeClass('none')
                .addClass(theme)
        })
    }

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

    $('#settingsModalTrigger').on('click', () => {
        let example;
        let text = $('#articleText p')
        for (let i = 0; i < text.length; i++) {
            example = $('#articleText p')[i].innerText.trim()
            if (example !== 0 && example.length > 70) {
                $('#settingsModal .font-size').text(substringOut(example, 200))
                break
            }
        }
        $('#articleText p, #settingsModal .font-size').css('font-size', setFont)
        $('#articleText p, #settingsModal .font-size').css('font-weight', setWeight)
    })

    $('#articleText p, #settingsModal .font-size').css('font-size', setFont)
    $('#articleText p, #settingsModal .font-size').css('font-weight', setWeight)

    $('#increaseWeight').on('click', () => {
        if ($('#increaseWeight').hasClass('active')) {
            fontWeight = 300
            $('#settingsModal .font-size').css('font-weight', fontWeight)
            $('#increaseWeight').removeClass('active')
        } else {
            fontWeight = 700
            $('#settingsModal .font-size').css('font-weight', fontWeight)
            $('#increaseWeight').addClass('active')
        }
    })

    $('#increaseFont').on('click', () => {
        if (fontSize < 32) {
            fontSize++;
            $('#settingsModal .font-size').css('font-size', fontSize)
        }
    })

    $('#decreaseFont').on('click', () => {
        if (fontSize > 12) {
            fontSize--;
            $('#settingsModal .font-size').css('font-size', fontSize)
        }
    })

    $('#resetFont').on('click', () => {
        fontSize = 16
        fontWeight = 300
        $('#increaseWeight').removeClass('active')
        $('#settingsModal .font-size').css('font-size', fontSize)
        $('#settingsModal .font-size').css('font-weight', fontWeight)
    })

    $('#saveSettings').on('click', () => {
        setCookie('settings_font_size', fontSize, 30)
        setCookie('settings_font_weight', fontWeight, 30)
        $('#articleText p, #settingsModal .font-size').css('font-size', setFont)
        $('#articleText p, #settingsModal .font-size').css('font-weight', setWeight)
    })

    $(document).on('click', function (event) {
        let $target = $(event.target);
        if (!$target.closest('.menu-profile').length && $('.menu-profile-submenu').is(":visible")) {
            $('.menu-profile').removeClass('menu-active')
            $('.menu-profile-submenu').fadeOut(200)
        }
    });

    $('.menu-profile').on('click', function () {
        if ($(this).hasClass('menu-active')) {
            $(this).removeClass('menu-active')
            $(this).find('.menu-profile-submenu').fadeOut(200)
        } else {
            $(this).addClass('menu-active')
            $(this).find('.menu-profile-submenu').fadeIn(200)
        }
    })
})
jQuery(document).ready(function ($) {

    $('.checkbox-toggle').on('change', function () {
        if ($('.checkbox-toggle').is(':checked')) {
            $('body').addClass('overflow-hidden');
        } else {
            $('body').removeClass('overflow-hidden');
        }
    });

    $('#searchBtn').on('click', function () {
        $('#searchForm').show();
        if ($('#searchForm').is(':visible')) {
            $(document).on('click', function (e) {
                var div = $("#searchForm");
                var dov = $("#searchBtn");
                if (!div.is(e.target) && !dov.is(e.target) && div.has(e.target).length === 0 && dov.has(e.target).length === 0) {
                    div.hide();
                }
            });
        }
    });

    $('.card-payment:not(".disabled")').on('click', function () {
        let input = $(this).find('input');
        let target = input.data('target');
        $('input[name="variation_id"]').attr('checked', false)
        input.attr('checked', true);
        if (input.is(':checked')) {
            $('.card-payment').removeClass('active');
            $.when($('.add-to-cart-block__target').fadeOut(50)).done(() => {
                $(`#${target}`).fadeIn(150);
            });
            $(this).addClass('active')
        }
    })

    var $page = $('html, body');
    $('a.scrollTop').on('click', function () {
        $page.animate({
            scrollTop: $($.attr(this, 'href')).offset().top
        }, 400);
        return false;
    });

    var num = 0;

    function loadItems(item, number) {
        num += number;
        var items = $("" + item + ":visible").slice(0, num);
        $(item).hide();
        items.show();
    }

    function loadProductPage(item, loadBtn, number) {
        num += number;
        var products = $(item).slice(0, num);
        $.when($(item).fadeOut(150)).done(function () {
            $.when(products.fadeIn(300)).done(function () {
                checkForEmptyHiddenProducts(item, loadBtn);
            });
        });
    }

    function checkForEmptyHiddenProducts(item, loadBtn) {
        if ($("" + item + ":hidden").length === 0) {
            $(loadBtn).hide();
        } else {
            $(loadBtn).show();
        }
    }

    function loadMore(item, loadBtn, number) {
        $(loadBtn).on('click', function () {
            loadProductPage(item, loadBtn, number);
        });
    }

    if ($('.blog-item:visible').length !== 0) {
        loadItems('.blog-item', 6);
        checkForEmptyHiddenProducts('.blog-item', '.load-more');
        loadMore('.blog-item', '.load-more', 6);
    }
    if ($('.announcement-item:visible').length !== 0) {
        loadItems('.announcement-item', 6);
        checkForEmptyHiddenProducts('.announcement-item', '.load-more');
        loadMore('.announcement-item', '.load-more', 6);
    }
    if ($('.news-n-events-item:visible').length !== 0) {
        loadItems('.news-n-events-item', 9);
        checkForEmptyHiddenProducts('.news-n-events-item', '.load-more');
        loadMore('.news-n-events-item', '.load-more', 9);
    }

    $('#nep_fake_textarea').on('paste', function (e) {
        e.preventDefault();
        var text = (e.originalEvent || e).clipboardData.getData('text/plain');
        document.execCommand("insertHTML", false, text);
    });
});

var swiperPopular = new Swiper('.swiper-container-popular', {
    fadeEffect: {crossFade: true},
    effect: 'fade',
    pagination: {
        el: '.popular-pagination',
        clickable: true,
        renderBullet: function (index, className) {
            return '<span class="popular-pagination__btn ' + className + '"></span>';
        },
    },
});

var swiperRelated = new Swiper('.swiper-container-related', {
    slidesPerView: 4,
    spaceBetween: 30,
    navigation: {
        nextEl: '.container-related__next',
        prevEl: '.container-related__prev',
    },
    breakpoints: {
        576: {
            slidesPerView: 1.7,
            centeredSlides: true,
            spaceBetween: 30,
        },
        768: {
            slidesPerView: 2,
            spaceBetween: 30,
        },
        991: {
            slidesPerView: 3,
            spaceBetween: 30,
        },
        1199: {
            slidesPerView: 4,
            spaceBetween: 30,
        }
    },
});