<?php
/*
Plugin Name: RSS Import
Description: RSS post import from third party website
Version: 1.0.0
Author: Gnanasekaran
Author URI: https://github.com/gnanasekaranl
*/

// Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function rss_post_import( $atts ) {
		//Thirs party website URL
		$client_url = "{$atts['url']}";
		//gets count of post
		$count	    = "{$atts['count']}";
		//return var
		$return_val = '';

		//replace with your URL
		$rss = fetch_feed("$client_url");

		if (!is_wp_error($rss)) :
			$maxitems = $rss -> get_item_quantity($count); 
			$rss_items = $rss -> get_items(0, $maxitems);
		endif;
			
		//grabs our post thumbnail image
		function get_first_image_url($html) {
			if (preg_match('/<img.+?src="(.+?)"/', $html, $matches)) {
				return $matches[1];
			}
		}
					
		//shortens description
		function shorten($string, $length) {
			$suffix = '&hellip;';
			$short_desc = trim(str_replace(array("\r", "\n", "\t"), ' ', strip_tags($string)));
			$desc = trim(substr($short_desc, 0, $length));
			$lastchar = substr($desc, -1, 1);
				if ($lastchar == '.' || $lastchar == '!' || $lastchar == '?')
						$suffix = '';
						$desc .= $suffix;
					return $desc;
		}
					
			//start of displaying our feeds
			$return_val .= '<div class="container-fluid">';
			$return_val .= '<div class="row">';
			$return_val .= '<div class="sixteen columns">';	

				if ($maxitems == 0) echo '<li>No items.</li>';
				else foreach ( $rss_items as $item ) :
			
					$return_val .= '<div class="four columns">';
						$return_val .= '<div class="margined">';
							$return_val .= '<div class="pic">';
								$return_val .= '<a href="'. esc_url($item -> get_permalink()).'">';
									$return_val .= '<img width="460" height="290" src="'. get_first_image_url($item -> get_content()).' " class="attachment-portfolio-medium size-portfolio-medium wp-post-image" alt="girl_bike-460x290"  sizes="(max-width: 460px) 100vw, 460px">';
									
								$return_val .= '</a>';
							$return_val .= '</div>';
							$return_val .= '<h4>';
								$return_val .= '<a href="'. esc_url($item -> get_permalink()).'">'.esc_html($item -> get_title()).'</a>';
							$return_val .= '</h4>';
							$return_val .= '<p>'. shorten($item -> get_description(), '150').'</p>';
							$return_val .= '<p><a href="'. esc_url($item -> get_permalink()).'" class="more-link">Read more</a></p>';
	
						$return_val .= '</div>';
					$return_val .= '</div>';
				endforeach;
					
			$return_val .= '</div>';
			$return_val .= '</div>';
			$return_val .= '</div>';

		return $return_val;
	}
	add_shortcode( 'rsspostimport', 'rss_post_import' );
?>
