jQuery(function ($) {
    let getUrlParameter = function getUrlParameter(sParam) {
        let sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    };
    let loadFilter = getUrlParameter('filter');
    let filterClass = '.product_tag-' + loadFilter;
    let filterButton = $('button[data-filter="' + filterClass + '"]');

    let params = {
        itemSelector: '.product',
        layoutMode: 'fitRows',
    };

    if (loadFilter !== undefined && filterButton.length > 0) {
        filterButton.addClass('active');
        params.filter = filterClass;
    }

    let isoGrid = $('.products').isotope(params);


    $('.button-group').each(function (i, buttonGroup) {
        let $buttonGroup = $(buttonGroup);
        $buttonGroup.on('click', 'button', function () {
            $buttonGroup.find('.active').removeClass('active');
            $(this).addClass('active');
        });
    });
    $('.clear-filters').on('click', function () {
        let filterValue = $(this).attr('data-filter');
        isoGrid.isotope({filter: filterValue});
        filters = {};
        $('.button-group').each(function (i, buttonGroup) {
            $(buttonGroup).find('.active').removeClass('active');
        });

    });

    let filters = {};

    $('.filter-button-group').on('click', '.button', function () {
        let $this = $(this);
        // get group key
        let $buttonGroup = $this.parents('.button-group');
        let filterGroup = $buttonGroup.attr('data-filter-group');
        // set filter for group
        filters[filterGroup] = $this.attr('data-filter');
        // combine filters
        let filterValue = concatValues(filters);
        isoGrid.isotope({filter: filterValue});
    });

    function concatValues(obj) {
        let value = '';
        for (let prop in obj) {
            value += obj[prop];
        }
        return value;
    }

    var num = 0;

    function loadProducts() {
        num += 12;
        var products = $("li.product:visible").slice(0, num);
        $('.product').hide();
        products.show();
    }

    function loadProductPage() {
        num += 12;
        var products = $("li.product").slice(0, num);
        $.when($('.product').fadeOut(150)).done(function () {
            $.when(products.fadeIn(300)).done(function () {
                checkForEmptyHiddenProducts();
            });
        });
    }

    // loadProducts();
    // checkForEmptyHiddenProducts();
    function checkForEmptyHiddenProducts() {
        if ($('li.product:hidden').length === 0) {
            $(".load-more").hide();
        }
    }

    $('.load-more').on('click', function () {
        loadProductPage();
    });

    // display message box if no filtered items
    $('.filter-btn').on('click', function () {
        setTimeout(function () {
            if ($('.product:visible').length === 0) {
                $('.isotope-empty').show();
            }
        }, 450)

    });

});