<?php
/* Events shortcode */
add_shortcode( 'wptp_testimonial', 'wptp_testimonial_shortcode' );
function wptp_testimonial_shortcode( $atts ){
	
	$options = shortcode_atts( array (
		'type' => ''
	), $atts );
	
	$args = array( 
		'post_type' => 'wptp_testimonial', 
		'order' => 'ASC',
		'posts_per_page' => -1
	); 

	$args['tax_query'] = array(
							array(
								'taxonomy' 	=> 'wptp_type',
								'field'		=> 'slug',
								'terms'		=> $options['type']
							),
						);
		
	$query_identifier = md5(serialize($args));
	delete_transient( 'wptp_testimonial_query_'.$query_identifier );
	if ( false === ( $testimonials_query = get_transient( 'wptp_testimonial_query_'.$query_identifier ) ) ) {
		
		//delete_transient( 'wptp_testimonial_query' );
		
		if ( current_user_can('manage_options') ):
			 print_r('---- QUERY ---- ');print_r($testimonials_query);
		endif;
		
		
		$testimonials_query = new WP_Query ( $args );
		
		// Set transient so the query doesn't have to be called again for 12 hours:
		set_transient( 'wptp_testimonial_query_'.$query_identifier, $testimonials_query, 12 * 60 * 60  );
		
	}
	

	if ( $testimonials_query->have_posts() ): 

		$testimonial_list = '<section id="testimonial">';
		$testimonial_list .= '<h2>User Stories</h2>';
		$testimonial_list .= '<ul>';

		while ( $testimonials_query->have_posts() ) : $testimonials_query->the_post();
			
			$testimonial_pdf = get_post_meta( get_the_ID(), 'wptp_pdf' , true );
			if ( empty($testimonial_pdf) ):
				$testimonial_link = get_the_permalink( get_the_ID() );
			else:
				$testimonial_link = $testimonial_pdf;
			endif;
	
			$testimonial_list .= '
						<li class="testimonial box">
							<a target="_blank" href="'.$testimonial_link.'">
								<div class="photo">
									'.get_the_post_thumbnail( get_the_ID(), "small").'
								</div>
								<div class="description">
									<div class="content">
										<h4>'.get_the_title( get_the_ID() ).'</h4>
										<p>'.get_post_meta( get_the_ID(), 'wptp_location' , true ).'</p>
									</div>
								</div>
							</a>
						</li>';
		endwhile; 

		$testimonial_list .= '</ul>';
		$testimonial_list .= '</section>';

		wp_reset_postdata(); 
		return $testimonial_list;
	endif; 
	
	return false;
	
	
}