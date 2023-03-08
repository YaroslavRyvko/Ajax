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
        category = this.getAttribute('data-cat');
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
                $('.posts-wrapper__test').append(res.html);
            }
        });
    }

    $('.cat-list_item').on('click', function () {
        $('.cat-list_item').removeClass('active');
        $(this).addClass('active');
        $('#load-more').removeAttr('data-search data-date');
        $('#load-more').attr("data-cat", $(this).data('slug'));
        $('#load-more').show();
        newPage = 1;
        $.ajax({
            type: 'POST',
            url: '/wp-admin/admin-ajax.php',
            dataType: 'json',
            data: {
                action: 'filter_projects',
                category: $(this).data('slug'),
            },
            success: function (res) {
                $('.posts-wrapper__test').html(res.html);
            }
        })
    });
});