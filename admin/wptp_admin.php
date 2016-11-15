<?php

/* Meta Boxes */

add_action( 'add_meta_boxes_wptp_testimonial', 'wptp_add_custom_meta_boxes' );
function wptp_add_custom_meta_boxes( $post ) {
    add_meta_box( 
        'wptp-extras',
        __( 'Extra Code (HTML/CSS)', 'wp-testimonial-pages' ),
        'wptp_meta_box_render',
        'wptp_testimonial',
        'normal',
        'default'
    );
	
	add_meta_box(
		'wptp-info',
		__( 'Testimonial Info' ,'wp-testimonial-pages'),
		'wptp_meta_box_info_render',
		'wptp_testimonial',
		'side',
		'default'
	);
	
}

function wptp_meta_box_render( $post ) {
	$values = get_post_custom( $post->ID );
	$wptp_title = array_key_exists('wptp_title',$values) ? $values['wptp_title'][0] : '';
	$wptp_menu = array_key_exists('wptp_menu',$values) ? $values['wptp_menu'][0] : '';
	$wptp_style = array_key_exists('wptp_style',$values) ? $values['wptp_style'][0] : '';
	$wptp_video = array_key_exists('wptp_video',$values) ? $values['wptp_video'][0] : '';
	
	wp_nonce_field( plugin_basename( __FILE__ ), 'wptp_text_box_content_nonce' );
		 
	echo '<h2>Background Video</h2>';
	echo '<input id="wptp_video" name="wptp_video" style="width:100%; max-width:600px;" value="'.esc_attr($wptp_video).'" placeholder="Video URL" />';	
	
	echo '<h2>Title Code (HTML/CSS)</h2>';
	echo '<textarea align="top" id="wptp_title" name="wptp_title" value="'.esc_attr($wptp_title).'" style="width:100%;height:200px;margin:5px -20px 3px 0;" placeholder="<h1>Title & custom html content like images goes here</h1>" />'.$wptp_title.'</textarea>';	

	echo '<h2>Menu Code (HTML/CSS)</h2>';
	echo '<textarea align="top" id="wptp_menu" name="wptp_menu" value="'.esc_attr($wptp_menu).'" style="width:100%;height:200px;margin:5px -20px 3px 0;" placeholder="<li><a href=\'#\'>menu 1<a/></li><li><a href=\'#\'>menu 2<a/></li>" />'.$wptp_menu.'</textarea>';	
		 
	echo '<h2>Custom Style</h2>';
	echo '<textarea align="top" id="wptp_style" name="wptp_style" value="'.esc_attr($wptp_style).'" style="width:100%;height:200px;margin:5px -20px 3px 0;" placeholder="div.name { margin: 0 4px; }" />'.$wptp_style.'</textarea>';
		 
}

function wptp_meta_box_info_render ($post) {
	$values = get_post_custom( $post->ID );
	$wptp_location = array_key_exists('wptp_location',$values) ? $values['wptp_location'][0] : '';
	
	wp_nonce_field( plugin_basename( __FILE__ ), 'wptp_text_box_info_nonce' );
	
	
	echo '<h2>Location</h2>';
	echo '<input id="wptp_location" name="wptp_location" style="width:100%; max-width:600px;" value="'.esc_attr($wptp_location).'" placeholder="City, State, Country" />';	

}

	
add_action( 'save_post', 'wptp_text_box_save' );
function wptp_text_box_save( $post_id ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    return;

    if ( !$_POST )
    return;

    if ( 'wptp_testimonial' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
        return;
    } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
        return;
    }
	
	if ( wp_verify_nonce( $_POST['wptp_text_box_content_nonce'], plugin_basename( __FILE__ ) )  ){
		$wptp_title = $_POST['wptp_title'];
		update_post_meta( $post_id, 'wptp_title', $wptp_title );

		$wptp_menu = $_POST['wptp_menu'];
		update_post_meta( $post_id, 'wptp_menu', $wptp_menu );

		$wptp_style = $_POST['wptp_style'];
		update_post_meta( $post_id, 'wptp_style', $wptp_style );

		$wptp_video = $_POST['wptp_video'];
		update_post_meta( $post_id, 'wptp_video', $wptp_video);
	}
	
	if ( wp_verify_nonce( $_POST['wptp_text_box_info_nonce'], plugin_basename( __FILE__ ) )  ){
		$wptp_location = $_POST['wptp_location'];
		update_post_meta( $post_id, 'wptp_location', $wptp_location );
	}
	
}

/* Custom Columns */ 
add_filter( 'manage_wptp_testimonial_posts_columns', 'set_custom_edit_wptp_testimonial_columns' );
add_action( 'manage_wptp_testimonial_posts_custom_column' , 'custom_wptp_testimonial_column', 10, 2 );

function set_custom_edit_wptp_testimonial_columns($columns) {
    $columns['location'] = __( 'Location', 'wp-testimonial-pages' );

    return $columns;
}

function custom_wptp_testimonial_column( $column, $post_id ) {
    switch ( $column ) {

        case 'location' :
			$values = get_post_custom( $post_id );
			$wptp_location = array_key_exists('wptp_location',$values) ? $values['wptp_location'][0] : '';
            echo $wptp_location; 
            break;

    }
}