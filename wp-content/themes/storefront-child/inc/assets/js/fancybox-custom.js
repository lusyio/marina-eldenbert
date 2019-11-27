jQuery(function ($) {
    $('.cir-image-link').fancybox(
        {
            buttons: [
                'download',
                'thumbs',
                'close'
            ],
            caption: function () {
                return $(this).parents('.grid-item').children('p').text();
            },
        });
    var $grid = $('.grid').masonry({
        itemSelector: '.grid-item',
        percentPosition: true,
        columnWidth: '.grid-sizer',
        gutter: 30,
    });
});