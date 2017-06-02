<?php
/*
Plugin Name: Chapter 4 - Book Reviews
Plugin URI:
Description: Declares a plugin that will be visible in the WordPress admin interface
Version: 1.0
Author: Yannick Lefebvre
Author URI: http://ylefebvre.ca
License: GPLv2
*/

// 1- create the new post type when WP is initialized
add_action( 'init', 'ch4_br_create_book_post_type' );
function ch4_br_create_book_post_type() {
    register_post_type( 'book_reviews',
        array(
            'labels' => array(
                'name' => 'Book Reviews',
                'singular_name' => 'Book Review',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Book Review',
                'edit' => 'Edit',
                'edit_item' => 'Edit Book Review',
                'new_item' => 'New Book Review',
                'view' => 'View',
                'view_item' => 'View Book Review',
                'search_items' => 'Search Book Reviews',
                'not_found' => 'No Book Reviews found',
                'not_found_in_trash' =>
                'No Book Reviews found in Trash',
                'parent' => 'Parent Book Review'),
            'public' => true,
            'menu_position' => 20,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail' ),
            'taxonomies' => array( '' ),
            'menu_icon' => plugins_url( 'book-16x16.png', __FILE__ ),
            'has_archive' => true,
            'rewrite' => array( 'slug' => 'awesome_book_reviews' )
        )
    );

    register_taxonomy(
        'book_reviews_book_type',
        'book_reviews',
        array(
            'labels' => array(
                'name' => 'Book Type',
                'add_new_item' => 'Add New Book Type',
                'new_item_name' => "New Book Type Name" ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
            )
        );
}

// 2- create a meta box for custom fields when the admin page is visited
add_action( 'admin_init', 'ch4_br_admin_init' );
function ch4_br_admin_init() {
    add_meta_box( 'ch4_br_review_details_meta_box',
        'Book Review Details',
        'ch4_br_display_review_details_meta_box',
        'book_reviews', 'normal', 'high' );
}
function ch4_br_display_review_details_meta_box( $book_review ) {
    // Retrieve current author and rating based on review ID
    $book_author = esc_html( get_post_meta( $book_review->ID, 'book_author', true ) );
    $book_rating = intval( get_post_meta( $book_review->ID,  'book_rating', true ) );

    ?>
        <table>
            <tr>
                <td style="width: 100%">Book Author</td>
                <td><input type="text" size="80" name="book_review_author_name" value="<?php echo $book_author; ?>" /></td>
            </tr>
            <tr>
                <td style="width: 150px">Book Rating</td>
                <td>
                    <select style="width: 100px"  name="book_review_rating">
                        <?php  for ( $rating = 5; $rating >= 1; $rating -- ) { ?>
                            <option value="<?php echo $rating; ?>"
                            <?php echo selected( $rating, $book_rating ); ?>>
                            <?php echo $rating; ?> stars
                        <?php } ?>
                    </select>
                </td>
            </tr>
        </table>
    <?php 
}

// 3- save custom fields when saving book reviews
add_action( 'save_post', 'ch4_br_add_book_review_fields', 10, 2 );
function ch4_br_add_book_review_fields( $book_review_id, $book_review ) {
    // Check post type for book reviews
    if ( $book_review->post_type == 'book_reviews' ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['book_review_author_name'] ) &&  $_POST['book_review_author_name'] != '' ) {
            update_post_meta( $book_review_id, 'book_author', $_POST['book_review_author_name'] );
        }
        if ( isset( $_POST['book_review_rating'] ) &&  $_POST['book_review_rating'] != '' ) {
            update_post_meta( $book_review_id, 'book_rating', $_POST['book_review_rating'] );
        }
    }
}

// 4-  load custom book review template before WordPress includes the predetermined template file
add_filter( 'template_include', 'ch4_br_template_include', 1 );
function ch4_br_template_include( $template_path ) {
    if ( get_post_type() == 'book_reviews' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first, otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-book_reviews.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-book_reviews.php';
            }
        } elseif ( is_archive() ) {
            if ( $theme_file = locate_template( array ( 'archive-book_reviews.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/archive-book_reviews.php';
            }
        }

    }
    return $template_path;
}

// 5- add a shortcode to display book reviews
add_shortcode( 'book-review-list', 'ch4_br_book_review_list' );
function ch4_br_book_review_list() {
    // Preparation of query array to retrieve 5 book reviews
    $query_params = array( 
        'post_type' => 'book_reviews',
        'post_status' => 'publish',
        'posts_per_page' => 5 );

    // Retrieve page query variable, if present
    $page_num = ( get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1 );
    // If page number is higher than 1, add to query array
    if ( $page_num != 1 )
        $query_params['paged'] = $page_num;

    // Execution of post query
    $book_review_query = new WP_Query;
    $book_review_query->query( $query_params );

    // Check if any posts were returned by the query
    if ( $book_review_query->have_posts() ) {

        // Display posts in table layout
        $output = '<table>';
        $output .= '<tr><th style="width: 350px"><strong>';
        $output .= 'Title</strong></th>';
        $output .= '<th><strong>Author</strong></th></tr>';

        // Cycle through all items retrieved
        while ( $book_review_query->have_posts() ) {
            $book_review_query->the_post();
            $output .= '<tr><td><a href="' . get_permalink();
            $output .= '">';
            $output .= get_the_title( get_the_ID() ) . '</a></td>';
            $output .= '<td>';
            $output .= esc_html( get_post_meta( get_the_ID(), 'book_author', true ) );
            $output .= '</td></tr>';
        }

        $output .= '</table>';

        // Display page navigation links
        if ( $book_review_query->max_num_pages > 1 ) {
            $output .= '<nav id="nav-below">';
            $output .= '<div class="nav-previous">';
            $output .= get_next_posts_link( '<span class="meta-nav">&larr;</span>Older reviews', $book_review_query->max_num_pages );
            $output .= '</div>';
            $output .= '<div class="nav-next">';
            $output .= get_previous_posts_link( 'Newer reviews <span class="meta-nav">&rarr;</span>', $book_review_query->max_num_pages );
            $output .= '</div>';
            $output .= '</nav>';
        }

        // Reset post data query
        wp_reset_postdata();
    }
    return $output;
}

?>