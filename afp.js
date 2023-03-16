jQuery(document).ready(function ($) {
    $('.filters-panel').on('change', 'select', function () {
        $(this).closest('form').submit();
        return false;
    });

    let newPage = 1;
    let date = 0;
    let search = '';
    let category = '';

    $('#load-more').on('click', function () {
        date = this.getAttribute('data-date');
        search = this.getAttribute('data-search');
        category = this.getAttribute('data-category');
        loadMore(newPage + 1);
        newPage++;
    });

    function loadMore(paged) {
        $.ajax({
            type: 'POST',
            url: '/wp-admin/admin-ajax.php',
            dataType: 'json',
            data: {
                action: 'weichie_load_more',
                paged,
                date,
                search,
                category
            },
            success: function (res) {
                if (paged >= res.max) {
                    $('#load-more').hide();
                }
                $('.posts-wrapper').append(res.html);
            }
        });
    }
});