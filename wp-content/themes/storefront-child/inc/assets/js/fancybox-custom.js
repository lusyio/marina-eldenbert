jQuery(function ($) {
    $('.images-link').fancybox(
        {
            buttons: [
                'download',
                'thumbs',
                'close'
            ],
        });
    var $grid = $('.grid').masonry({
        itemSelector: '.grid-item',
        percentPosition: true,
        columnWidth: '.grid-sizer',
        gutter: 30,
    });
});