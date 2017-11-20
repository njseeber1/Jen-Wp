<?php     
/**
 * SKT Girlie functions and definitions
 *
 * @package SKT Girlie
 */
 
global $content_width;

if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */ 

// Set the word limit of post content 
function skt_girlie_content($limit) {
$content = explode(' ', get_the_content(), $limit);
if (count($content)>=$limit) {
array_pop($content);
$content = implode(" ",$content).'...';
} else {
$content = implode(" ",$content);
}	
$content = preg_replace('/\[.+\]/','', $content);
$content = apply_filters('the_content', $content);
$content = str_replace(']]>', ']]&gt;', $content);
return $content;
}

/**
 * Set the content width based on the theme's design and stylesheet.
 */

if ( ! function_exists( 'skt_girlie_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function skt_girlie_setup() {
	load_theme_textdomain( 'skt-girlie', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support('woocommerce');
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-header' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-logo', array(
		'height'      => 240,
		'width'       => 240,
		'flex-height' => true,
	) );
	add_theme_support( 'custom-header', array( 'header-text' => false ) );	
	add_image_size('skt-girlie-homepage-thumb',240,145,true);
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'skt-girlie' ),
	) );
	add_theme_support( 'custom-background', array(
		'default-color' => 'ffffff'
	) );
	add_editor_style( 'editor-style.css' );
} 
endif; // skt_girlie_setup
add_action( 'after_setup_theme', 'skt_girlie_setup' ); 

function skt_girlie_widgets_init() {	
	
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'skt-girlie' ),
		'description'   => __( 'Appears on blog page sidebar', 'skt-girlie' ),
		'id'            => 'sidebar-1',
		'before_widget' => '',		
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3><aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
	) );
}
add_action( 'widgets_init', 'skt_girlie_widgets_init' );
/*
* Use this function for Sets up theme defaults blog sidebar.
*/

function skt_girlie_font_url(){
		$font_url = '';		
		
		/* Translators: If there are any character that are not
		* supported by Roboto, trsnalate this to off, do not
		* translate into your own language.
		*/
		
		$pacifico = _x('on','pacifico:on or off','skt-girlie');		
		
		/* Translators: If there are any character that are not
		* supported by Pacifico, trsnalate this to off, do not
		* translate into your own language.
		*/
		
		$roboto = _x('on','roboto:on or off','skt-girlie');		
		
		/* Translators: If there has any character that are not supported 
		*  by Arimo, translate this to off, do not translate
		*  into your own language.
		*/
		$arimo = _x('on','arimo:on or off','skt-girlie');	
		
		/* Translators: If there has any character that are not supported 
		*  by Scada, translate this to off, do not translate
		*  into your own language.
		*/
		$scada = _x('on','Scada:on or off','skt-girlie');			
		
		if('off' !== $roboto || 'off' !== $arimo || 'off' !== $pacifico){
			$font_family = array();	
					
			if('off' !== $roboto){
				$font_family[] = 'Roboto:300,400,600,700,800,900';
			}
			
			if('off' !== $arimo){
				$font_family[] = 'Arimo:400';
			}
			
			if('off' !== $pacifico){
				$font_family[] = 'Pacifico:400';
			}			
						
			$query_args = array(
				'family'	=> urlencode(implode('|',$font_family)),
			);
			
			$font_url = add_query_arg($query_args,'//fonts.googleapis.com/css');
		}
		
	return $font_url;
	}


function skt_girlie_scripts() {
	wp_enqueue_style('skt-girlie-font', skt_girlie_font_url(), array());
	wp_enqueue_style( 'skt-girlie-basic-style', get_stylesheet_uri() );
	wp_enqueue_style( 'skt-girlie-editor-style', get_template_directory_uri()."/editor-style.css" );
	wp_enqueue_style( 'skt-girlie-nivoslider-style', get_template_directory_uri()."/css/nivo-slider.css" );
	wp_enqueue_style( 'skt-girlie-main-style', get_template_directory_uri()."/css/responsive.css" );		
	wp_enqueue_style( 'skt-girlie-base-style', get_template_directory_uri()."/css/style_base.css" );
	wp_enqueue_script( 'skt-girlie-nivo-script', get_template_directory_uri() . '/js/jquery.nivo.slider.js', array('jquery') );
	wp_enqueue_script( 'skt-girlie-custom_js', get_template_directory_uri() . '/js/custom.js' );	
	wp_enqueue_style( 'skt-girlie-animation-style', get_template_directory_uri()."/css/animation.css" );	

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'skt_girlie_scripts' );

function skt_girlie_ie_stylesheet(){
	global $wp_styles;
	
	/** Load our IE-only stylesheet for all versions of IE.
	*   <!--[if lt IE 9]> ... <![endif]-->
	*
	*  Note: It is also possible to just check and see if the $is_IE global in WordPress is set to true before
	*  calling the wp_enqueue_style() function. If you are trying to load a stylesheet for all browsers
	*  EXCEPT for IE, then you would HAVE to check the $is_IE global since WordPress doesn't have a way to
	*  properly handle non-IE conditional comments.
	*/
	wp_enqueue_style('skt-girlie-ie', get_template_directory_uri().'/css/ie.css', array('skt-girlie-style'));
	$wp_styles->add_data('skt-girlie-ie','conditional','IE');
	}
add_action('wp_enqueue_scripts','skt_girlie_ie_stylesheet');


function skt_girlie_pagination() {
    /*Set this function for pagination links*/
	global $wp_query;
	$big = 12345678;
	$page_format = paginate_links( array(
	    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	    'format' => '?paged=%#%',
	    'current' => max( 1, get_query_var('paged') ),
	    'total' => $wp_query->max_num_pages,
	    'type'  => 'array'
	) );
	if( is_array($page_format) ) {
		$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
		echo '<div class="pagination"><div><ul>';
		echo '<li><span>'. $paged . ' of ' . $wp_query->max_num_pages .'</span></li>';
		foreach ( $page_format as $page ) {
			echo "<li>$page</li>";
		}
		echo '</ul></div></div>';
	}
}


define('SKT_URL','http://www.sktthemes.net','skt-girlie');
define('SKT_THEME_URL','http://www.sktthemes.net/themes','skt-girlie');
define('SKT_THEME_URL_DIRECT','http://www.sktthemes.net/shop/girlie-girly-wordpress-theme/','skt-girlie');
define('SKT_THEME_DOC','http://sktthemesdemo.net/documentation/skt-girlie-doc/','skt-girlie');
define('SKT_PRO_THEME_URL','http://www.sktthemes.net/shop/girlie-girly-wordpress-theme/','skt-girlie');
define('SKT_PRO_FONT_AWESOME_URL','http://fortawesome.github.io/Font-Awesome/icons/','skt-girlie');
define('SKT_THEME_FEATURED_SET_VIDEO_URL','https://www.youtube.com/watch?v=310YGYtGLIM','skt-girlie');
define('SKT_LIVE_DEMO','http://sktthemesdemo.net/girlie/','skt-girlie');
define('SKT_FREE_URL','http://www.sktthemes.net/product-category/free-wordpress-themes/','skt-girlie');

function skt_girlie_themebytext(){
		return "<a href=".esc_url(SKT_FREE_URL)." target='_blank' rel='nofollow'>SKT Girlie Theme</a>";
}
/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template for about theme.
 */
require get_template_directory() . '/inc/about-themes.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';



// get slug by id
function skt_girlie_get_slug_by_id($id) {
	$post_data = get_post($id, ARRAY_A);
	$slug = $post_data['post_name'];
	return $slug; 
}

if ( ! function_exists( 'skt_construction_the_custom_logo' ) ) :
/**
 * Displays the optional custom logo.
 *
 * Does nothing if the custom logo is not available.
 *
 */
function skt_girlie_the_custom_logo() {
	if ( function_exists( 'the_custom_logo' ) ) {
		the_custom_logo();
	}
}
endif;