<?php

/*
* Plugin Name: Infinite json
* Author: John Cashmore
* Version: 0.1.1
	plugin uri: https://github.com/JohnCashBB/wordpress-infinite-json
	description: A json plugin for wordpress
*/


add_action('template_redirect' , 'jsonrequest_template_redirect');


function jsonrequest_template_redirect() {

    global $query_string, $post, $wp_query;
    $post_id = $post->ID;

    if(isset($_REQUEST['json']) && is_category()) {
        if( have_posts()) {
        	header('Content-Type: application/json');

            $output['category'] = esc_html(single_cat_title('', false));
            $output['page'] = (get_query_var('paged')) ? get_query_var('paged') : 1;
            if (previous_posts(false)) {
            	if($output['page'] > 1) {
                	$output['previousURL'] = esc_html(previous_posts(false));
            	} else {
            		$output['previousURL'] = null;
            	}
            }

            if (next_posts($wp_query->max_num_pages, false)) {
                $output['nextURL'] = esc_html(next_posts($wp_query->max_num_pages, false));
            }



            while( have_posts() ) : the_post();
                $output['posts'][]= array( 'id' => get_the_ID(), 'title' => get_the_title(), 'excerpt' => esc_html( get_the_excerpt() ) );
            endwhile;
        }
        die ( json_encode( $output ) );
    } else {
        return;
    }
}
