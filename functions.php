<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}


define('TA_ARTICLES_COMPATIBLE_POST_TYPES', ['ta_article', 'ta_audiovisual']);
define('TA_THEME_PATH', get_template_directory());
define('TA_THEME_URL', get_template_directory_uri());
define('TA_ASSETS_PATH', TA_THEME_PATH . "/assets");
define('TA_ASSETS_URL', TA_THEME_URL . "/assets");
define('TA_IMAGES_URL', TA_ASSETS_URL . "/img");
define('TA_ASSETS_CSS_URL', TA_THEME_URL . "/css");
define('TA_ASSETS_JS_URL', TA_THEME_URL . "/js");

require_once TA_THEME_PATH . '/inc/gen-base-theme/gen-base-theme.php';
require_once TA_THEME_PATH . '/inc/rewrite-rules.php';
require_once TA_THEME_PATH . '/inc/widgets.php';

class TA_Theme{
	static private $initialized = false;

	static public function initialize()
	{
		if (self::$initialized)
			return false;
		self::$initialized = true;

		self::add_themes_supports();
		self::register_menues();


		if (is_admin()) {
			require_once TA_THEME_PATH . '/inc/attachments.php';
		}

		require_once TA_THEME_PATH . '/inc/functions.php';
		require_once TA_THEME_PATH . '/inc/classes/TA_Blocks_Container.php';
		require_once TA_THEME_PATH . '/inc/classes/TA_Blocks_Container_Manager.php';
		require_once TA_THEME_PATH . '/inc/classes/Data_Manager.php';
		require_once TA_THEME_PATH . '/inc/classes/TA_Article_Factory.php';
		require_once TA_THEME_PATH . '/inc/classes/TA_Article_Data.php';
		require_once TA_THEME_PATH . '/inc/classes/TA_Article.php';
		require_once TA_THEME_PATH . '/inc/classes/TA_Author_Factory.php';
		require_once TA_THEME_PATH . '/inc/classes/TA_Author_Data.php';
		require_once TA_THEME_PATH . '/inc/classes/TA_Author.php';
		require_once TA_THEME_PATH . '/inc/classes/TA_Tag_Factory.php';
		require_once TA_THEME_PATH . '/inc/classes/TA_Tag_Data.php';
		require_once TA_THEME_PATH . '/inc/classes/TA_Tag.php';
		require_once TA_THEME_PATH . '/inc/classes/TA_Section_Factory.php';
		require_once TA_THEME_PATH . '/inc/classes/TA_Section_Data.php';
		require_once TA_THEME_PATH . '/inc/classes/TA_Section.php';
		add_action('gen_modules_loaded', array(self::class, 'register_gutenberg_categories'));
		add_action('wp_enqueue_scripts', array(self::class, 'enqueue_scripts'));

		add_filter('gen_check_post_type_name_dash_error', function ($check, $post_type) {
			if ($post_type == 'tribe-ea-record')
				return false;
			return $check;
		}, 10, 2);

		self::customizer();

		if( is_admin() ){
			require_once TA_THEME_PATH . '/inc/menu-items.php';
		}

		require_once TA_THEME_PATH . '/inc/classes/TA_Micrositio.php';
		require_once TA_THEME_PATH . '/inc/micrositios.php';
		self::get_plugins_assets();
		add_action( 'admin_menu', [__CLASS__,'remove_posts'] );
	}

	static public function add_themes_supports() {
        add_theme_support('post-thumbnails');

        //svg support
        function cc_mime_types($mimes) {
            $mimes['svg'] = 'image/svg+xml';
            return $mimes;
        }
        add_filter('upload_mimes', 'cc_mime_types');
    }

	static private function register_menues() {
        RB_Wordpress_Framework::load_module('menu');
        register_nav_menus(
            array(
                'sections-menu' => __('Secciones'),
                'special-menu' => __('Especiales'),
				'extra-menu' => __('Extra'),
            )
        );
    }

	static public function enqueue_scripts(){
		wp_enqueue_style( 'bootstrap', TA_ASSETS_CSS_URL . '/libs/bootstrap/bootstrap.css' );
		wp_enqueue_style( 'fontawesome', TA_ASSETS_CSS_URL . '/libs/fontawesome/css/all.min.css' );
		wp_enqueue_style( 'ta_style', TA_ASSETS_CSS_URL . '/src/style.css' );
		wp_enqueue_script( 'bootstrap', TA_ASSETS_JS_URL . '/libs/bootstrap/bootstrap.min.js', ['jquery'] );
	}

	static public function register_gutenberg_categories()
	{
		rb_add_gutenberg_category('ta-blocks', 'Tiempo Argentino', null);
	}

	static public function customizer(){
		RB_Wordpress_Framework::load_module('fields');
        RB_Wordpress_Framework::load_module('customizer');
        add_action('customize_register', array(self::class, 'require_customizer_panel'), 1000000);
    }

    static public function require_customizer_panel($wp_customize){
        require TA_THEME_PATH . '/customizer.php';
    }
	/**
	 * Plugins
	 */
	static public function get_plugins_assets()
	{
		require_once TA_THEME_PATH . '/user-panel/functions.php';
		require_once TA_THEME_PATH . '/subscriptions-theme/functions.php';
		require_once TA_THEME_PATH . '/mailtrain/functions.php';
	}

	/**
	 * Menus remove
	 */
	static public function remove_posts() {
		remove_menu_page( 'edit.php' );
	}

}

TA_Theme::initialize();


function get_etiquetas($request)
{
	// init the resource
	$ch = curl_init();

	curl_setopt_array($ch, array(
		CURLOPT_URL               	=> "http://190.105.238.93:5000/api/textrank",
		CURLOPT_RETURNTRANSFER    	=> true,
		CURLOPT_POST				=> true,
		CURLOPT_HTTPHEADER			=> 'Content-Type: application/json',
		CURLOPT_POSTFIELDS			=> $request->get_body(),
	));

	// execute
	$output = curl_exec($ch);

	// free
	curl_close($ch);

	return $output;
}

add_action('rest_api_init', function () {
	register_rest_route('ta/v1', '/etiquetador', array(
		'methods' 				=> 'POST',
		'callback' 				=> 'get_etiquetas',
		'permission_callback'	=> '__return_true',
	) );
} );

add_action( 'rest_api_init', function () {
	register_rest_route( 'ta/v1', '/articles', array(
		'methods' 				=> 'POST',
		'callback' 				=> function($request){
			$params = $request->get_json_params();
			$articles = [];
			$result = rb_get_posts($params['args']);
			$posts = $result['posts'];
			$query = $result['wp_query'];

			if($posts && !empty($posts)){
				foreach ($posts as $article_post) {
					$article = TA_Article_Factory::get_article($article_post, 'article_post');
					$article->populate(true);
					$articles[] = $article;
				}
			}

			$response = new WP_REST_Response($articles, 200);
			$response->header('X-WP-TotalPages', $result['total_pages']);
			return $response;
		},
		'permission_callback'	=> '__return_true',
	) );
} );

function ta_print_header(){
	include(TA_THEME_PATH . '/markup/partes/header.php');
};
