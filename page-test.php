<?php /* Template Name: Test */ ?>

<?php get_header('small'); ?>

<?php
global $wp;
$current_url = home_url(add_query_arg(NULL, NULL));
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
    'posts_per_page' => 3,
    'post_status' => 'publish',
    'suppress_filters' => false,
    'paged' => $paged,
    'relation' => 'OR',
    'post_type' => 'post',
);

$search = NULL;
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $args['s'] = $search;
}

$date = NULL;
if (isset($_GET['date'])) {
    $date = $_GET['date'];
    if ($_GET['date'] !== '') {
        $args['date_query']['month'] = $date;
    }
}

$dates = [
    '' => 'Date',
    '1' => 'January',
    '2' => 'February',
    '3' => 'March',
    '4' => 'April',
    '5' => 'May',
    '6' => 'June',
    '7' => 'July',
    '8' => 'August',
    '9' => 'September',
    '10' => 'October',
    '11' => 'November',
    '12' => 'December',
];

$query = new WP_Query();
$posts = $query->query($args);
?>

<main class="blog-main">
    <?php get_template_part('views/partials/block-loader'); ?>
    <section class="section-filters">
        <div class="filters-panel">
            <form class="filter-date">
                <?php if (!empty($dates)) : ?>
                    <select class="filter-select" name="date">
                        <?php foreach ($dates as $key => $value) : ?>
                            <option value="<?php echo $key ?>" <?php if ($key == $date) : ?> selected <?php endif ?>><?php _e($value, 'theme') ?></option>
                        <?php endforeach ?>
                    </select>
                <?php endif ?>
            </form>

            <form class="filter-search">
                <input class="filter-input" type="text" name="search" placeholder="<?php _e('Rechercher...', 'theme') ?>" />
                <button class="filter-btn"><?php insertImage('/icons/search.svg') ?></button>
            </form>

            <?php $categories = get_categories(); ?>
            <ul class="cat-list">
                <li><a class="cat-list_item active" href="#!" data-slug="">All cars</a></li>
                <?php foreach ($categories as $category) : ?>
                    <li>
                        <a class="cat-list_item" href="#!" data-slug="<?= $category->slug; ?>">
                            <?= $category->name; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
    <?php if (!empty($posts)) : ?>
        <div class="posts-wrapper__test">
            <?php foreach ($posts as $post) : ?>
                <?php get_template_part('views/partials/post-card'); ?>
            <?php endforeach ?>
        </div>
    <?php else : ?>
        <div class="posts-nothing">
            <h2 class="events-nothing__title title-regular"><?php _e('Nothing found', 'theme') ?></h2>
        </div>
    <?php endif ?>
    <?php wp_reset_postdata(); ?>
    <div class="btn__wrapper">
        <a href="#!" class="contact-btn contact-btn_black" data-search="<?php echo $search; ?>" data-date="<?php echo $date; ?>" id="load-more">Load more</a>
    </div>
</main>

<?php get_footer(); ?>