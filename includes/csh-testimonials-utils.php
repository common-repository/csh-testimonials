<?php 
// Get all posts (post type = product) after filter.
function cshtm_get_testimonial_posts($cat = "default") {
    $tax_query = "";
    if ($cat != "default") {
        $query_cat  =  array(
                            'taxonomy' => 'cshtm_cat',
                            'field' => 'slug',
                            'terms' => $cat,
                            'include_children' => true,
                        );

        $tax_query = array(
            $query_cat,
        );
    }

	$return_posts = array(); // Return value.
    $args = array(
        'post_type' => 'csh_testimonial',
        'tax_query' => $tax_query,
    );
    
    $the_query = new WP_Query( $args );
    // The Loop
    if ( $the_query->have_posts() ) :
    while ( $the_query->have_posts() ) : $the_query->the_post();
      array_push($return_posts, $the_query->post->ID);
    endwhile;
    endif;
    // Reset Post Data
    wp_reset_postdata();
    return  $return_posts;
}

?>