<div class="search-div position-relative mr-1 mr-sm-4 mt-auto mb-auto">
    <div id="searchBtn" class="search-icon">
        <div class="search-btn">
            <img src="/wp-content/themes/storefront-child/svg/search.svg" alt="">
        </div>
    </div>
    <form id="searchForm" role="search" method="get" class="search-form"
          action="<?php echo esc_url(home_url('/')); ?>">
        <input type="search" class="search-field form-control"
               placeholder="Поиск..."
               value="<?php echo esc_attr(get_search_query()); ?>" name="s"
               title="<?php _ex('Search for:', 'label', 'wp-bootstrap-starter'); ?>">
    </form>
</div>

<script>
    jQuery(document).ready(function ($) {
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
    });
</script>