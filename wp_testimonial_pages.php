<?php 
/**
 * Plugin Name: WP Testimonial Pages
 * Plugin URI: http://adriantoro.infoeplus.com
 * Description: Display Testimonials in your site and create landing pages with full width testominal images.
 * Version: 0.0.4
 * Author: Adrian Toro
 * Domain Path: /languages
 * Text Domain: wp-testimonial-pages

**/

/* Add Style and Scripts: */
if ( !function_exists('wptp_add_admin_scripts_styles') ){
	add_action( 'admin_enqueue_scripts', 'wptp_add_admin_scripts_styles' );
	function wptp_add_admin_scripts_styles(){
		wp_enqueue_style( 'wptp_admin_style', plugin_dir_url( __FILE__ ) . 'css/wptp_admin.css' );
	}
}
if ( !function_exists('wptp_add_scripts_styles') ){
	add_action( 'wp_enqueue_scripts', 'wptp_add_scripts_styles' );
	function wptp_add_scripts_styles(){
		wp_enqueue_style( 'wptp_style', plugin_dir_url( __FILE__ ) . 'css/wptp_style.css' );
		
		$wptp_single_style = get_post_meta( get_the_ID(), 'wptp_style', true ); 
		if ( is_singular ('wptp_testimonial') ):  
		 	echo '<style>';
			echo $wptp_single_style;
			echo 'body.single-wptp_testimonial section#intro { background-image: url('.get_the_post_thumbnail_url( get_the_ID(), "full" ).'); }';
			echo '</style>';
		endif; 
	}
}

/* Create Custom Post Type: */
if ( !function_exists('wptp_create_post_types' ) ) {

	add_action('init', 'wptp_create_post_types');
	function wptp_create_post_types() {
		$args = array(
		  	'public' 					=> true,
		  	'hierarchical' 				=> true,
		  	'label'  					=> 'Testimonials',
			'rewrite' 					=> array('slug' => 'about-ross/testimonial'),
			  'capability_type' => 'testimonial',
			  'map_meta_cap' => true,
			  'capabilities' => array(

				// meta caps (don't assign these to roles)
				'edit_post'              => 'edit_testimonial',
				'read_post'              => 'read_testimonial',
				'delete_post'            => 'delete_testimonial',

				// primitive/meta caps
				'create_posts'           => 'create_testimonials',

				// primitive caps used outside of map_meta_cap()
				'edit_posts'             => 'edit_testimonials',
				'edit_others_posts'      => 'manage_testimonials',
				'publish_posts'          => 'publish_testimonials',
				'read_private_posts'     => 'read',

				// primitive caps used inside of map_meta_cap()
				'read'                   => 'read',
				'delete_posts'           => 'manage_testimonials',
				'delete_private_posts'   => 'manage_testimonials',
				'delete_published_posts' => 'manage_testimonials',
				'delete_others_posts'    => 'manage_testimonials',
				'edit_private_posts'     => 'edit_testimonials',
				'edit_published_posts'   => 'edit_testimonials'
			  ),
		  	'supports'					=> array ( 'title', 'editor', 'thumbnail', 'revisions' )
		);
		register_post_type( 'wptp_testimonial', apply_filters( 'wptp_testimonial_post_type_args', $args ) );
	}
}
/* Create Custom Post Type Taxonomies: */
if ( !function_exists('wptp_create_testimonials_tax') ){
	add_action( 'init', 'wptp_create_testimonials_tax' );

	function wptp_create_testimonials_tax() {
		register_taxonomy(
				'wptp_type',
				'wptp_testimonial',
				array(
					'label' => __( 'Type', 'wp-testimonial-pages' ),
					'rewrite' => array( 'slug' => 'testimonial/type' ),
					'hierarchical' => true,
				)
		);
	}
}
/* Add Taxonomy to Custom Post Type List: */
add_filter( 'manage_taxonomies_for_wptp_testimonial_columns', 'wptp_testimonial_type_columns');
function wptp_testimonial_type_columns( $taxonomies ){
	$taxonomies[] = 'wptp_type';
	return $taxonomies;
	
}


if ( is_admin() ) {
     // We are in admin mode
     require_once( dirname(__file__).'/admin/wptp_admin.php' );
}

require_once( dirname(__file__).'/inc/wptp_shortcodes.php' );

