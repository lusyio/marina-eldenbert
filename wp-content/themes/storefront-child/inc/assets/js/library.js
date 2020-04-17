jQuery(function ($) {
    const cards = $('.library-card:not(.pag)');
    let $count = cards.length;
    const count = 10
    const pageCount = Math.ceil($count / count)

    const paginator = $('#libraryPagination .pagination')
    let page = "";
    for (let i = 0; i < pageCount; i++) {
        page += '<li id="page' + (i + 1) + '" data-page="' + i * count + '" class="page-item">\n' +
            '        <span class="page-link">' + (i + 1) + '</span>\n' +
            '     </li>';
    }
    paginator.html(page)

    for (let i = 0; i < cards.length; i++) {
        if (i < count) {
            cards[i].style.display = "block";
        }
    }

    let activePage = $('#page1');
    activePage.addClass('active')

    $('.page-item').on('click', function () {
        const page = $(this).data('page')
        $('.page-item').removeClass('active')
        $(this).addClass('active')

        let j = 0;
        for (let i = 0; i < cards.length; i++) {
            let num = cards[i].dataset.num;
            if (num <= page || num >= page)
                cards[i].style.display = "none";
        }
        for (let i = page; i < cards.length; i++) {
            if (j >= count) break;
            cards[i].style.display = "block";
            j++;
        }
    })
})