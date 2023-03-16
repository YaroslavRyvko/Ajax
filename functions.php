<?php

/**
 * wordpress-project functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wordpress-project
 */

if (!defined('_S_VERSION')) {
	define('_S_VERSION', '1.0.0');
}

/* defines START */
define('_TP_', get_stylesheet_directory_uri()); //theme path
define('_IMAGES_', _TP_ . '/src/images'); //images path
/* defines END */


/**
 * Enqueue scripts and styles.
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function kaminusok_setup()
{
	foreach (glob(get_template_directory() . "/inc/*.php") as $file) {
		require $file;
	}
}

add_action('init', 'register_post_types');

function register_post_types()
{
	register_post_type('services', [
		'labels' => array(
			'name' => 'Services',
			'singular_name' => 'Service',
		),
		'menu_icon' => 'dashicons-list-view',
		'hierarchical' => true,
		'has_archive ' => false,
		'taxonomies'  => array('category'),
		'public' => true,
		'supports' => array('title')
	]);

	register_post_type('vehicles', [
		'labels' => array(
			'name' => 'Vehicles',
			'singular_name' => 'Vehicle',
		),
		'menu_icon' => 'dashicons-car',
		'hierarchical' => true,
		'has_archive ' => false,
		'taxonomies'  => array('category'),
		'public' => true,
		'supports' => array('title')
	]);
}

add_action('after_setup_theme', 'kaminusok_setup');

add_filter('wpcf7_autop_or_not', '__return_false');

function shorten_yoast_breadcrumb_title($link_info)
{
	$limit = 29;
	if (strlen($link_info['text']) > ($limit)) {
		$link_info['text'] = substr($link_info['text'], 0, $limit) . '...';
	}

	return $link_info;
}

add_filter('wpseo_breadcrumb_single_link_info', 'shorten_yoast_breadcrumb_title', 10);

function filter_posts_scripts()
{
	global $wp_query;
	wp_register_script('afp', _TP_ . '/src/js/afp.js', array('jquery'), false, null, false);
	wp_enqueue_script('afp');
	wp_localize_script(
		'afp',
		'afp_vars',
		array(
			'query' => json_encode($wp_query->query),
			'url' => admin_url('admin-ajax.php'),
		)
	);
}

add_action('wp_enqueue_scripts', 'filter_posts_scripts', 1);


function weichie_load_more()
{
	$ajaxposts = new WP_Query([
		'post_type' => 'post',
		'posts_per_page' => 3,
		'paged' => $_POST['paged'],
		's' => $_POST['search'],
		'date_query' => [
			'month' => $_POST['date'],
		],
		'category_name' => $_POST['category'],
	]);

	$response = '';
	$max_pages = $ajaxposts->max_num_pages;

	if ($ajaxposts->have_posts()) {
		ob_start();
		while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
			$response .= get_template_part('views/partials/post-card');
		endwhile;
		$output = ob_get_contents();
		ob_end_clean();
	} else {
		$response = '';
	}

	$result = [
		'max' => $max_pages,
		'html' => $output,
	];

	echo json_encode($result);
	exit;
}

add_action('wp_ajax_weichie_load_more', 'weichie_load_more');
add_action('wp_ajax_nopriv_weichie_load_more', 'weichie_load_more');
