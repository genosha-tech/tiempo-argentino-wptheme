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
		require_once TA_THEME_PATH . '/inc/rest.php';
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
		RB_Filters_Manager::add_action('ta_theme_admin_scripts', 'admin_enqueue_scripts', array(self::class, 'admin_scripts'));

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
		self::increase_curl_timeout();
	}

	static private function increase_curl_timeout(){
		$timeout = 15;
		RB_Filters_Manager::add_filter('ta_curl_http_request_args', 'http_request_args', function($request) use ($timeout){
			$request['timeout'] = $timeout;
			return $request;
		}, array(
			'priority'	=> 100,
			'args'		=> 1,
		));
		RB_Filters_Manager::add_action('ta_curl_http_api', 'http_api_curl', function($handle) use ($timeout){
			curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, $timeout );
			curl_setopt( $handle, CURLOPT_TIMEOUT, $timeout );
		}, array(
			'priority'	=> 100,
			'args'		=> 1,
		));
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

	static public function admin_scripts(){
		wp_enqueue_style('ta_theme_admin_css', TA_ASSETS_CSS_URL . '/src/admin.css');
		wp_enqueue_script( 'ta_theme_admin_js', TA_ASSETS_JS_URL . '/src/admin.js', ['jquery'] );
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

// Gutenberg block script enqueue outside post edition screen
add_action('admin_enqueue_scripts', function(){
	wp_enqueue_script( "ta-index-block-js" );
});

function ta_print_header(){
	include(TA_THEME_PATH . '/markup/partes/header.php');
};

function ta_article_image_control($post, $meta_key, $image_url, $args = array()){
	$default_args = array(
		'title'			=> '',
		'description'	=> '',
	);
	extract( array_merge($default_args, $args) );
	$image_url = is_string($image_url) ? $image_url : '';
	$empty = !$image_url;
	?>
	<div id="test" class="ta-articles-images-controls" data-id="<?php echo esc_attr($post->ID); ?>" data-type="<?php echo esc_attr($post->post_type); ?>" data-metakey="<?php echo esc_attr($meta_key); ?>">
        <div class="image-selector">
			<?php if($title): ?>
            <p class="title"><?php echo esc_html($title); ?></p>
			<?php endif; ?>
			<?php if($description): ?>
            <p class="description"><?php echo esc_html($description); ?></p>
			<?php endif; ?>
            <div class="image-box">
                <div class="bkg" style="background-image: url(<?php echo esc_attr($image_url); ?>);"></div>
                <div class="content">
	    			<div class="controls when-not-empty">
					    <div class="remove-btn">x</div>
					</div>
				    <div class="text when-empty">Seleccionar imagen</div>
					<div class="text when-not-empty">Cambiar imagen</div>
				</div>
            </div>
        </div>
    </div>
	<?php
}

// POST COLUMN - Adding column to core post type
rb_add_posts_list_column('ta_article_images_column', 'ta_article', 'Test Column', function($column, $post){
	$article = TA_Article_Factory::get_article($post);
	if(!$article)
		return;
	$featured_img_url = $article->thumbnail_common['is_default'] ? '' : $article->thumbnail_common['url'];
	$featured_alt_url = $article->thumbnail_alt_common['is_default'] ? '' : $article->thumbnail_alt_common['url'];

	ta_article_image_control($post, '_thumbnail_id', $featured_img_url, array(
		'title'			=> 'Imagen Principal',
	));
	ta_article_image_control($post, 'ta_article_thumbnail_alt', $featured_alt_url, array(
		'title'			=> 'Imagen Portada',
		'description'	=> 'Pisa la imagen principal en la portada',
	));
}, array(
    'position'      => 4,
    'column_class'  => 'test-class',
));



?>