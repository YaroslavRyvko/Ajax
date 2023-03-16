<?php /* Template Name: Test-Blog */ ?>

<?php get_header('small'); ?>

<?php
global $wp;
$current_url = home_url(add_query_arg(NULL, NULL));
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$action = '/' . $post->post_name . '/?paged=1';

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

$categories = get_categories();
$category = NULL;
if (isset($_GET['category'])) {
    $category = $_GET['category'];
    if ($_GET['category'] !== '') {
        $args['category_name'] = $category;
    }
}

$query = new WP_Query();
$posts = $query->query($args);
$posts_amount = $query->found_posts ;
?>

<main class="blog-main">
    <?php get_template_part('views/partials/block-loader'); ?>
    <section class="section-filters">
        <div class="filters-panel">
            <form class="filter-date">
                <input type="hidden" name="search" value="<?php echo $search ?>" />
                <input type="hidden" name="category" value="<?php echo $category ?>" />
                <input type="hidden" name="paged" value="1" />
                <?php if (!empty($dates)) : ?>
                    <select class="filter-select" name="date">
                        <?php foreach ($dates as $key => $value) : ?>
                            <option value="<?php echo $key ?>" <?php if ($key == $date) : ?> selected <?php endif ?>><?php _e($value, 'theme') ?></option>
                        <?php endforeach ?>
                    </select>
                <?php endif ?>
            </form>
            <form class="filter-search" action="<?php echo $current_url ?>">
                <input type="hidden" name="date" value="<?php echo $date ?>" />
                <input type="hidden" name="category" value="<?php echo $category ?>" />
                <input type="hidden" name="paged" value="1" />
                <input class="filter-input" type="text" name="search" placeholder="<?php _e('Rechercher...', 'theme') ?>" value="<?php echo $search ?>" />
                <button class="filter-btn"><?php insertImage('/icons/search.svg') ?></button>
            </form>
            <form class="filter-category">
                <input type="hidden" name="search" value="<?php echo $search ?>" />
                <input type="hidden" name="date" value="<?php echo $date ?>" />
                <input type="hidden" name="paged" value="1" />
                <?php if (!empty($categories)) : ?>
                    <select class="filter-select" name="category">
                        <option value="" selected>All categories</option>
                        <?php foreach ($categories as $cat) : ?>
                            <option value="<?php echo $cat->name; ?>" <?php if ($cat->name == $category) : ?> selected <?php endif ?>><?php _e($cat->name, 'theme') ?></option>
                        <?php endforeach ?>
                    </select>
                <?php endif ?>
            </form>
        </div>
    </section>
    <section class="section-posts">
        <h4 class="section-posts__sub-title sub-title_default">Tout savoir sur</h4>
        <h3 class="section-posts__title title_default">Nos actualités & évenements</h3>
        <?php if (!empty($posts)) : ?>
            <div class="posts-wrapper">
                <?php foreach ($posts as $post) : ?>
                    <?php get_template_part('views/partials/post-card'); ?>
                <?php endforeach ?>
            </div>
            <?php if ($posts_amount > 3) : ?>
                <div class="btn__wrapper">
                    <a href="#!" class="contact-btn contact-btn_black" data-search="<?php echo $search; ?>" data-date="<?php echo $date; ?>" data-category="<?php echo $category; ?>" id="load-more">Load more</a>
                </div>
            <?php endif ?>
        <?php else : ?>
            <div class="posts-nothing">
                <h2 class="events-nothing__title">Rien n'a été trouvé</h2>
            </div>
        <?php endif ?>
    </section>

    <?php wp_reset_postdata(); ?>
</main>

<?php get_footer(); ?>