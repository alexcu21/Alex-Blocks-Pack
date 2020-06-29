<?php
/*
 Plugin Name: Alex's Custom Gutenberg Blocks
 Plugin URI: 
 Description: Adds Gutenberg Blocks - using standar way
 Version: 1.0
 Author: Alex Cuadra
 Author URI: https://www.twitter.com/alexcuadr4
 License: GPL2
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

 // Prevent the execution
 if(!defined('ABSPATH')) exit;

 /** Register The Gutenberg blocks and CSS */

 add_action('init', 'acg_register_gutenberg_blocks');

 function acg_register_gutenberg_blocks() {
    // Check if gutenberg is installed

    if( !function_exists('register_block_type')) {
        return;
    }

    // Register the Block editor script
    wp_register_script(
        'acg-editor-script', 
        plugins_url( 'build/index.js', __FILE__ ), // url to file
        array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'), // dependencies
        filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js') // version
    ); 

    // Gutenberg Editor CSS (Backend)
    wp_register_style(
        'acg-editor-style', // name
        plugins_url( 'build/editor.css', __FILE__ ), // file
        array(), // dependencies
        filemtime( plugin_dir_path( __FILE__ ) . 'build/editor.css') // version
    );

    // Front end Stylesheet
    wp_register_style(
        'acg-front-end-styles', // name
        plugins_url( 'build/style.css', __FILE__ ), // file
        array(), // dependencies
        filemtime( plugin_dir_path( __FILE__ ) . 'build/style.css') // version
    );

    // An Array of Blocks
    $blocks = array(
        'acg/testimonial',
        'acg/hero', 
        'acg/imagetext'
    );

    // Add the blocks and register the Stylesheets
    foreach($blocks as $block) {
        register_block_type( $block, array(
            'editor_script' => 'acg-editor-script',
            'editor_style' => 'acg-editor-style', // backend CSS
            'style' => 'acg-front-end-styles', // front end css
        ));
    }


    // Enqueue the Dynamic Block (latest posts)

    register_block_type('acg/latest', array(
        'editor_script' => 'acg-editor-script',
        'editor_style' => 'acg-editor-style',
        'style' =>  'acg-front-end-styles', 
        'render_callback' => 'acg_latest_recipes_block'
    ));
 }

 /** Custom Categories */
 add_filter('block_categories', 'acg_new_gutenberg_category', 10, 2);
 function acg_new_gutenberg_category( $categories, $post ) {
    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'alexcuadra-custom-block', 
                'title' => 'Alex Custom Blocks', 
                'icon' => 'awards'
            ),
        )
    );
 }