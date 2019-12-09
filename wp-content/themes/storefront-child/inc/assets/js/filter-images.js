jQuery(function ($) {
    var isoGrid = $('.grid').imagesLoaded(function () {
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
            if (loadFilter.match(/^images/) === null) {
                filterClass = '.images-' + loadFilter;
            } else {
                filterClass = '.' + loadFilter;
            }
        }
        let filterButton = $('button[data-filter="' + filterClass + '"]');

        let params = {
            itemSelector: '.grid-item',
            layoutMode: 'fitRows',
        };

        if (loadFilter !== undefined && filterButton.length > 0) {
            filterButton.addClass('active');
            params.filter = filterClass;
        }

        isoGrid = $('.grid').isotope(params);

        let $categoryName = [];

        $('.images-link').each(function () {
            $categoryName.push($(this).data('category'));
        });

        $('.filter-btn').on('click', function () {
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
            let filterValue = $(this).attr('data-filter');
            let filterData = {filter: filterValue};
            isoGrid.isotope(filterData);
            $('.images-link').each(function (index) {
                $(this).attr('data-fancybox', $categoryName[index]);
            })
        });

        $('.clear-filters').on('click', function () {
            let filterValue = $(this).attr('data-filter');
            $('.filter-btn').removeClass('active');
            let filterData = {filter: filterValue};
            isoGrid.isotope(filterData);
            $('.images-link').attr('data-fancybox', 'images-all');
        });
    })
});