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
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( '' ),
            'menu_icon' => plugins_url( 'book-16x16.png', __FILE__ ),
            'has_archive' => true,
            'rewrite' => array( 'slug' => 'awesome_book_reviews' )
        )
    );
}

?>