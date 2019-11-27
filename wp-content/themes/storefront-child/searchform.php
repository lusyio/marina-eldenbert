<div class="search-div position-relative mr-3 mr-sm-3 mt-auto mb-auto">
    <div id="searchBtn" class="search-icon">
        <div class="search-btn">
            <img src="/wp-content/themes/storefront-child/svg/search-black.svg" alt="">
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