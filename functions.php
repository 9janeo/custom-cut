<?php
// Include Beans. Do not remove the line below.
require_once( get_template_directory() . '/lib/init.php' );

include('plugin.php');

// Customize the beans child theme
add_action('beans_before_load_document', 'custom_mods_child_theme');
function custom_mods_child_theme() {
	beans_remove_markup('beans_site');
	beans_add_attribute('beans_header', 'data-uk-sticky', 'top:0');
	beans_add_attribute('beans_post_more_link', 'class', 'uk-button' );
	beans_remove_action( 'beans_post_image' );
	
}

/*
 * Remove this action and callback function if you do not whish to use LESS to style your site or overwrite UIkit variables.
 * If you are using LESS, make sure to enable development mode via the Admin->Appearance->Settings option. LESS will then be processed on the fly.
 */
add_action( 'beans_uikit_enqueue_scripts', 'beans_child_enqueue_uikit_assets');
function beans_child_enqueue_uikit_assets() {
	//$uri = get_stylesheet_directory_uri();
	beans_uikit_enqueue_components(array('contrast'));
	beans_uikit_enqueue_components(array('sticky', 'slideshow', 'slideshow-fx'), 'add-ons');
	// beans_uikit_enqueue_components( true );
 // 	beans_uikit_enqueue_components( true, 'add-ons' );
	beans_compiler_add_fragment( 'uikit', array(
			get_stylesheet_directory_uri() . '/style.less',
		), 'less'	
	);
}

// Remove this action and callback function if you are not adding CSS in the style.css file.
add_action( 'wp_enqueue_scripts', 'beans_child_enqueue_assets' );
function beans_child_enqueue_assets() {
	// wp_enqueue_style( 'bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' ); // Get bootstrap
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css' );
	wp_enqueue_script('particles-js', get_stylesheet_directory_uri() . '/assets/particles.min.js');
	if ( is_front_page()){
		wp_enqueue_script('particle-settings', get_stylesheet_directory_uri() . '/assets/particleSettings.js', false, false, true );
	}
}

// Find A way to dynamically call nav link IDs and add Icons based on name
// add home icon to home nav link
add_action('beans_menu_item_link_170_append_markup', 'custom_add_home_link_icon');
function custom_add_home_link_icon(){ 
	?>
		<i class="uk-icon-home uk-text-muted uk-margin-small-left"></i>
	<?php
}

// add star icon to about nav link
add_action('beans_menu_item_link_172_append_markup', 'custom_add_about_link_icon');
function custom_add_about_link_icon(){ 
	?>
		<i class="uk-icon-star uk-text-muted uk-margin-small-left"></i>
	<?php
}

// add  list icon to services nav link
add_action('beans_menu_item_link_169_append_markup', 'custom_add_services_link_icon');
function custom_add_services_link_icon(){ 
	?>
		<i class="uk-icon-list uk-text-muted uk-margin-small-left"></i>
	<?php
}

// add @ icon to contact nav link
add_action('beans_menu_item_link_171_append_markup', 'custom_add_contact_link_icon');
function custom_add_contact_link_icon(){ 
	?>
		<i class="uk-icon-at uk-text-muted uk-margin-small-left"></i>
	<?php
}

// add @ icon to contact nav link
add_action('beans_menu_item_link_414_append_markup', 'custom_add_blog_link_icon');
function custom_add_blog_link_icon(){ 
	?>
		<i class="uk-icon-newspaper-o uk-text-muted uk-margin-small-left"></i>
	<?php
}
 

//Add clock Icon to post meta date
add_action('beans_post_meta_item_date_prepend_markup', 'beans_child_add_post_meta_date_icon');
function beans_child_add_post_meta_date_icon() {
  ?>
  	<i class="uk-icon-calendar-check-o uk-margin-small-right uk-text-muted"></i>
  <?php
}

// add google tag manager to all pages
add_action( 'beans_header_after_markup', 'page_tag_manager' );
function page_tag_manager() {
	?>
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P8QGFN7"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
	<?php
}
	
// function beans_child_control_image() {
// 	add_image_size('max-width', 300, 9999); //300 pixels wide (and unlimited height)
// 	add_theme_support( 'post-thumbnails' );
// 	set_post_thumbnail_size( 150, 150 );
// }
// add_action('after_setup_theme', 'beans_child_control_image' );

// filter 'the_content' on home.php to exclude posts with category 'services'
function post_archive_mod_query($query) {
  if ( $query->is_home() && $query->is_main_query() ) {
    //get ID of services category
    $services_id = get_cat_ID('services');
    // exclude posts in new from query 
    $query->set('category__not_in', array( $services_id ) );
  }
}
add_action( 'pre_get_posts', 'post_archive_mod_query' );

// Remove jetpack share links from default location except on single posts
if (!is_single() ) {
	function jptweak_remove_share() {
	    remove_filter( 'the_content', 'sharing_display', 19 );
	    remove_filter( 'the_excerpt', 'sharing_display', 19 );
	    if ( class_exists( 'Jetpack_Likes' ) ) {
	        remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
	    }
	}
	add_action( 'loop_start', 'jptweak_remove_share' );

	// Customize Read more button on home.php
	add_filter( 'beans_post_more_link_text_output', 'example_modify_read_more' );
	function example_modify_read_more() {
		beans_add_attribute( 'beans_post_more_link', 'class', 'uk-button-primary' );
	  return 'Keep reading';
	}
}

add_action( 'beans_footer_credit_right_text_output', 'hyperindian_right_copyright' );

function hyperindian_right_copyright() {

  // Add your copyright html text, Dynamic date and times etc something like .
  ?>
  	<div class='beans-footer-class'>
  		<a href="<?php echo get_the_permalink(get_page_by_path('privacy-policy')) ?>">Privacy Policy</a>
  	</div>
  <?php

}

add_action( 'beans_footer_credit_text_output', 'footer_verisign_badge' );
function footer_verisign_badge() {		
	?>
		<div class='beans-footer-class'>
			<span>
				<?php 
					echo sprintf(
						// translators: Footer credits. Date followed by the name of the website.
						__( '&#x000A9; %1$s - %2$s. All rights reserved.', 'tm-beans' ),
						date( 'Y' ),
						get_bloginfo( 'name' )
					);
				?>
			</span>
			</br>
			<span id="siteseal">
				<script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=SKy8ye9W5h2KCmuHbmj4zAuLkHKweG0GwSTx4MYWKYiDnJs6CbEmG2EnENwH"></script>
			</span>
		</div>
	<?php
}

function custom_head_script() {
	$manager = "<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-P8QGFN7');</script>
	<!-- End Google Tag Manager -->";


	$google = "<script async src='https://www.googletagmanager.com/gtag/js?id=G-8MJYEEC6DC'></script>";
	$google .= "<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
	
		gtag('config', 'G-8MJYEEC6DC');
	</script>";

	$ga4 = "<!-- Global site tag (gtag.js) - Google Analytics 4 -->";
	$ga4 .= "<script async src='https://www.googletagmanager.com/gtag/js?id=UA-111255337-1'></script>";
	$ga4 .= "<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-111255337-1');
	</script>";

	$pixel = "<!-- Meta Pixel Code -->";
	$pixel .= "<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '1430179477124549');
	fbq('track', 'PageView');
	</script>
	<noscript><img height='1' width='1' style='display:none'
	src='https://www.facebook.com/tr?id=1430179477124549&ev=PageView&noscript=1'
	/></noscript>";
	$pixel .= "<!-- End Meta Pixel Code -->";
	
	// echo $google;
	echo $manager;
	echo $ga4;
	echo $pixel;

}	
add_action('wp_head', 'custom_head_script');

// Load page banner name to be called after header
// $current_page = "home";
// if (!is_page($current_page)) {
// 	print_r($current_page);
// }

// Noindex 404 pages
function noindex_not_found(){
	if ( is_404() ) {?>
		<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW" />
	<?php }
}
add_action('wp_head', 'noindex_not_found');
