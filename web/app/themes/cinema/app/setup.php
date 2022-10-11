<?php

namespace App;

use Roots\Sage\Container;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {

    $version = '1.1.4';
    if(defined('WP_DEBUG') && WP_DEBUG === true){
        $version = time();
    }

    wp_enqueue_style('sage/main.css', asset_path('styles/main.css'), false, $version);
    wp_enqueue_script('sage/main.js', asset_path('scripts/main.js'), ['jquery'], $version, true);

    if (is_single() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // localize script voor het laden van AJAX in WP
    wp_localize_script( 'sage/main.js', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}, 100);

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from Soil when plugin is activated
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil-clean-up');
    add_theme_support('soil-jquery-cdn');
    add_theme_support('soil-nav-walker');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-relative-urls');

    /**
     * Enable plugins to manage the document title
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');
 

    /**
     * Register navigation menus
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
        'top_navigation' => __('Top Navigation', 'sage'),
        'mobile_navigation' => __('Mobiele Navigation', 'sage'),
        'footer1_navigation' => __('Footer links Navigation', 'sage'),
        'footer2_navigation' => __('Footer midden Navigation', 'sage'),
        'footer3_navigation' => __('Footer rechts Navigation', 'sage'),
        'footer4_navigation' => __('Footer bodem Navigatie', 'sage')
    ]);

    /**
     * Enable post thumbnails
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    add_post_type_support( 'page', 'excerpt' );

    /**
     * Responsive embeds
     * @link https://thrivewp.com/youtube-embed-wordpress/
     * 
     */
    add_theme_support( 'responsive-embeds' );

    
    /**
     * Enable HTML5 markup support
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    /**
     * Enable selective refresh for widgets in customizer
     * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
     */
    add_theme_support('customize-selective-refresh-widgets');
    
    /**
     * Register support for Gutenberg wide images in your theme
     */
    add_theme_support( 'align-wide' );

    /**
     * Use main stylesheet for visual editor
     * @see resources/assets/styles/layouts/_tinymce.scss
     */
    add_theme_support('editor-styles');

    add_editor_style(asset_path('styles/style-editor.css'));

    //remove_theme_support( 'core-block-patterns' );

    /**
     * toevoegen van de vertaling
     * @link https://roots.io/docs/sage/9.x/localization/#generating-language-files
     */
    load_theme_textdomain('sage', get_template_directory() . '/lang');
    
    // // Media & Text Article Title.
	// register_block_pattern(
	// 	'indrukwekkend/media-text-article-title',
	// 	array(
	// 		'title'         => esc_html__( 'Media and text article title', 'indrukwekkend' ),
	// 		'categories'    => array( 'indrukwekkend' ),
	// 		'viewportWidth' => 1440,
	// 		'description'   => esc_html_x( 'A Media & Text block with a big image on the left and a heading on the right. The heading is followed by a separator and a description paragraph.', 'Block pattern description', 'indrukwekkend' ),
	// 		'content'       => '<!-- wp:media-text {"mediaId":1752,"mediaLink":"' . asset_path('images/playing-in-the-sand.jpg') . '","mediaType":"image","className":"is-style-indrukwekkend-border"} --><div class="wp-block-media-text alignwide is-stacked-on-mobile is-style-indrukwekkend-border"><figure class="wp-block-media-text__media"><img src="' . asset_path('images/playing-in-the-sand.jpg') . '" alt="' . esc_attr__( '&#8220;Playing in the Sand&#8221; by Berthe Morisot', 'indrukwekkend' ) . '" class="wp-image-1752"/></figure><div class="wp-block-media-text__content"><!-- wp:heading {"align":"center"} --><h2 class="has-text-align-center">' . esc_html__( 'Playing in the Sand', 'indrukwekkend' ) . '</h2><!-- /wp:heading --><!-- wp:separator {"className":"is-style-dots"} --><hr class="wp-block-separator is-style-dots"/><!-- /wp:separator --><!-- wp:paragraph {"align":"center","fontSize":"small"} --><p class="has-text-align-center has-small-font-size">' . wp_kses_post( __( 'Berthe Morisot<br>(French, 1841-1895)', 'indrukwekkend' ) ) . '</p><!-- /wp:paragraph --></div></div><!-- /wp:media-text -->',
	// 	)
	// );


}, 20);

/**
 * Register Block Patterns.
 */
if ( function_exists( 'register_block_pattern' ) ) {

	
}

/**
 * Register sidebars
 */
add_action('widgets_init', function () {    
    register_sidebar([
        'name'          => __('Footer', 'sage'),
        'id'            => 'sidebar-footer'
    ]);
});

/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action('the_post', function ($post) {
    sage('blade')->share('post', $post);
});

/**
 * Setup Sage options
 */
add_action('after_setup_theme', function () {
    /**
     * Add JsonManifest to Sage container
     */
    sage()->singleton('sage.assets', function () {
        return new JsonManifest(config('assets.manifest'), config('assets.uri'));
    });

    /**
     * Add Blade to Sage container
     */
    sage()->singleton('sage.blade', function (Container $app) {
        $cachePath = config('view.compiled');
        if (!file_exists($cachePath)) {
            wp_mkdir_p($cachePath);
        }
        (new BladeProvider($app))->register();
        return new Blade($app['view']);
    });

    /**
     * Create @asset() Blade directive
     */
    sage('blade')->compiler()->directive('asset', function ($asset) {
        return "<?= " . __NAMESPACE__ . "\\asset_path({$asset}); ?>";
    });
});

/**
 * Add ACF JSON
 */
add_filter(
    'acf/settings/save_json',
    function ($path) {
        $targetDir = get_template_directory() . '/acf-json';
        return (file_exists($targetDir) && is_dir($targetDir)) ? $targetDir : $path;
    }
);

add_filter(
    'acf/settings/load_json',
    function ($paths) {
        $paths[] = get_stylesheet_directory() . '/acf-json';
        return $paths;
    }
);

/**
 * Add ACF options page
 */
if (function_exists('acf_add_options_page')) {
    $parent = acf_add_options_page(__('Options', 'od'));

    // add sub page
    acf_add_options_page(
        array(
            'page_title' => __('General', 'od'),
            'menu_title' => __('General', 'od'),
            'parent_slug' => $parent['menu_slug'],
        )
    );

    acf_add_options_sub_page(
        array(
            'page_title' => __('Header', 'od'),
            'menu_title' => __('Header', 'od'),
            'parent_slug' => $parent['menu_slug'],
        )
    );

    acf_add_options_sub_page(
        array(
            'page_title' => __('Footer', 'od'),
            'menu_title' => __('Footer', 'od'),
            'parent_slug' => $parent['menu_slug'],
        )
    );
}

/**
 * Add Favicon
 */
add_action('wp_head', function () {
    $favicon = get_field('opt_general_img_fav', 'option');
    if (!$favicon) $favicon = get_template_directory() . '/favicon.png';
    echo '<link rel="shortcut icon" href="' . $favicon . '" />';
});

// /**
//  * Change Template Default Name
//  */
// add_filter('default_page_template_title', function () {
//     return __('Default Template', 'startertheme');
// });

// /**
//  * Add support svg file on WP
//  */
// add_filter('upload_mimes', function ($mimes) {
//     $mimes['svg'] = 'image/svg+xml';
//     return $mimes;
// });
