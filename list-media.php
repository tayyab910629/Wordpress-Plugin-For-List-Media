<?php 
/*
Plugin Name: List Media
Description: This plugin gives you the possibility to list all of your Media files. :)
Version: 1.0
Author: Haifz Tahir
Author URI: https://github.com/websking
License: GPLv2
*/

class list_media {
	public function list_media_controller( $atts, $content = "" ) {
		//Get the Shortcode attributes
		extract( 
			shortcode_atts( 
				array( 
				'order' => 'ASC',
				'order_by' => 'publish_date',
				'posts_per_page' => -1,
				'post_status' => null,
				'post_parent' => null,
				'default_styles' => true,
				'date_format' => "Y/m/d"
				), $atts 
			)
		);

		//Default table styles
	    if ( $default_styles == true ) {
	    	$default_styles = "
	    		<style>
	    			.list-media-table {
	    				display: block;
	    				width: 100%;
	    				height: auto;
	    				border-collapse: collapse;
	    			}

	    			.list-media-table th,
	    			.list-media-table td {
	    				border: 1px solid black;
	    				vertical-align: top;
	    			}

	    			.list-media-table .header {
	    				font-size: 16px;
	    				font-weight: bold;
	    				color: #000;
	    				padding: 5px 5px;
	    			}

	    			.list-media-table td {
						font-size: 14px;
						padding: 5px 5px;
						border: 1px solid black;
					}

					.list-media-table .attachment img {
						width: 72px;
						height: 72px;
					}
	    		</style>
	    	";
	    }

	    //Query ARGS
	    $args = array(
			'posts_per_page' => $posts_per_page,
			'numberposts' => $numberposts,
		    'post_type' => 'attachment',
		    'post_status' => $post_status,
		    'post_parent' => $post_parent, 
			'orderby' => $order_by,
			'order' => $order	
	    );

	    //Print the first part of the Table
	    echo "
	    <table class='list_media'>
			<thead>
				<tr>
					<th class='header'></th>
					<th class='header'>File</th>
					<th class='header'>Author</th>
					<th class='header'>Uploaded To</th>
					<th class='header'>Date</th>
				</tr>
			</thead>
			<tbody>
	    ";

	    //Get the Media files
	    $attachments = get_posts( $args );

	    if ( !empty( $attachments ) ) {
	    	foreach ( $attachments as $attachment ) {
	    		$attachment_id = $attachment->ID;
	    		$attachment_title = get_the_title( $attachment_id );
				$attachment_url = wp_get_attachment_url( $attachment_id );
				$attachment_author_id = $attachment->post_author;
				$attachment_author_url = get_author_posts_url( $attachment_id );
				$attachment_author_name = get_the_author_meta( "user_nicename", $attachment_author_id ); 
				$attachment_publish_date = get_the_date( $date_format, $attachment_id );

				$attachment_post_parent_id = $attachment->post_parent;

				if ( !empty( $attachment_post_parent_id ) ) {
					$attachment_post_parent_title = get_the_title( $attachment_post_parent_id );
					$attachment_post_parent_url = get_permalink( $attachment_post_parent_id );
				} else {
					$attachment_post_parent_url = "#!";
					$attachment_post_parent_title = "Unattached";
				}

				echo "
				<tr>
					<td class='attachment'>
						<img src='$attachment_url' alt='Broken Image' />
					</td>
					<td class='title'>
						<a href='$attachment_url' target='_blank'>
							$attachment_title
						</a>
					</td>
					<td class='author'>
						<a href='$attachment_author_url' target='_blank'>
							$attachment_author_name
						</a>
					</td>
					<td class='uploaded-to'>
						<a href='$attachment_post_parent_url' target='_blank'>
							$attachment_post_parent_title
						</a>
					</td>
					<td class='date'>
						$attachment_publish_date
					</td>
				</tr>
				";
	    	}
	    }

	    //Print end of the Table
	    echo "
			</tbody>
		</table>
		";
	}
}
add_shortcode( 'list_media', array( 'list_media', 'list_media_controller') );

?>