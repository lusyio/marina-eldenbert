jQuery(function ($) {
    let bookBarHeight = $('.storefront-sticky-add-to-cart').height();
    let adminBarHeight = $('#wpadminbar').height();
    if (bookBarHeight === null) {
        bookBarHeight = 0;
    }
    if (adminBarHeight === null) {
        adminBarHeight = 0;
    }
    let rootComments = $('.comment-list > li.parent');
    const defaultVisibleChildren = 3;
    let scrollToComment =  window.location.hash.substr(1);
    $(rootComments).each(function (i, rootComment) {
        let childrenCommments = $(rootComment).find('.comment');
        let childrenCount = childrenCommments.length;
        let hasHiddenCommentToScroll = false;
        if (scrollToComment !== '') {
            $(childrenCommments).each(function (j, el) {
                if ($(el).attr('id') !== '' && $(el).attr('id') === scrollToComment && j > defaultVisibleChildren - 1) {
                    hasHiddenCommentToScroll = true;
                }
            });
        }
        for (let j = 0; j < childrenCount; j++) {
            if (!hasHiddenCommentToScroll) {
                if (j === defaultVisibleChildren - 1 && childrenCount > defaultVisibleChildren) {
                    $(childrenCommments[j]).after('<div class="comments-toggle comments-show text-center"><button class="btn expand-comments">Показать все ответы</button></div>');
                }
                if (j > defaultVisibleChildren - 1) {
                    $(childrenCommments[j]).hide()
                }
                if (j === childrenCount - 1) {
                    $(childrenCommments[j]).after('<div class="comments-toggle comments-hide text-center"><button class="btn collapse-comments" style="display: none">Скрыть ответы</button></div>');
                }
            } else {
                if (j === defaultVisibleChildren - 1 && childrenCount > defaultVisibleChildren) {
                    $(childrenCommments[j]).after('<div class="comments-toggle comments-show text-center"><button class="btn expand-comments" style="display: none">Показать все ответы</button></div>');
                }
                if (j === childrenCount - 1) {
                    $(childrenCommments[j]).after('<div class="comments-toggle comments-hide text-center"><button class="btn collapse-comments">Скрыть ответы</button></div>');
                }
            }
        }
    });

    $('.expand-comments').on('click', function () {
        let rootComment = $(this).closest('.comment-list > li.parent');
        $(rootComment).find('.comment').show(100);
        $(this).hide(100);
        $(rootComment).find('.collapse-comments').show(100);
    });
    $('.collapse-comments').on('click', function () {
        let rootComment = $(this).closest('.comment-list > li.parent');
        $(rootComment).find('.comment').show(100)
        $(this).hide(100);
        $(rootComment).find('.expand-comments').show(100);
        let childrenCommments = $(rootComment).find('.comment');
        let childrenCount = childrenCommments.length;
        for (let j = 0; j < childrenCount; j++) {
            if (j > defaultVisibleChildren - 1) {
                $(childrenCommments[j]).hide(100)
            }
        }

        $([document.documentElement, document.body]).animate({
            scrollTop: $(rootComment).offset().top - bookBarHeight - adminBarHeight - 30
        }, 100);
    })
});