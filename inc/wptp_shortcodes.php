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
	$testimonials_query = new WP_Query ( $args );

	if ( $testimonials_query->have_posts() ): 

		$testimonial_list = '<section id="testimonial">';
		$testimonial_list .= '<h2>User Stories</h2>';
		$testimonial_list .= '<ul>';

		while ( $testimonials_query->have_posts() ) : $testimonials_query->the_post();
			$testimonial_list .= '
						<li class="testimonial box">
							<a target="_blank" href="'.get_the_permalink( get_the_ID() ).'">
								<div class="photo">
									<img src="'.get_the_post_thumbnail( get_the_ID(), "small").'" />
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