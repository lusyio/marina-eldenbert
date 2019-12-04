new WOW().init();
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