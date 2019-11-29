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
    let filterClass = '';
    if (loadFilter !== undefined) {
        if (loadFilter.match(/^series/) === null) {
            filterClass = '.product_tag-' + loadFilter;
        } else {
            filterClass = '.' + loadFilter;
        }
    }
    let filterButton = $('button[data-filter="' + filterClass + '"]');

    let params = {
        itemSelector: '.product',
        layoutMode: 'fitRows',
        getSortData: {
            bookOrder: '[data-book-order]',
        },
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
        isoGrid.isotope({
            filter: filterValue,
            sortBy: 'original-order'
        });
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
        console.log(filterGroup);
        // set filter for group
        filters[filterGroup] = $this.attr('data-filter');
        // combine filters
        let filterValue = concatValues(filters);
        let filterData = {filter: filterValue};
        if (filterGroup === 'cycles') {
            filterData['sortBy'] = 'bookOrder';
        }
        isoGrid.isotope(filterData);
    });

    function concatValues(obj) {
        let value = '';
        for (let prop in obj) {
            value += obj[prop];
        }
        return value;
    }

    // display message box if no filtered items
    $('.filter-btn, .clear-filters').on('click', function () {
        setTimeout(function () {
            if ($('.product:visible').length === 0) {
                $('.isotope-empty').show();
            } else {
                $('.isotope-empty').hide();
            }
        }, 450)

    });

});