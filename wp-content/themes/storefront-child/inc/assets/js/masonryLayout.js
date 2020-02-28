jQuery(function ($) {
    var $grid = $('.grid-images').imagesLoaded(function () {
        $grid.masonry({
            itemSelector: '.grid-item',
            gutter: 30
        });
    });
});