<?php

add_filter('xmlrpc_enabled', '__return_false');
add_filter('login_display_language_dropdown', '__return_false');

/**
* Init
*/

add_action( 
  'init', 
  function () {

    /**
      * Categories for pages
      */  

    register_taxonomy_for_object_type( 
      'category', 
      'page' 
    );
  }
);

/**
 * Analytics
 */ 

add_action(  
  'wp_head', 
  function () { 
    ?>
      <!-- Google tag (gtag.js) -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=G-ET6MBWP16N"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-ET6MBWP16N');
      </script>
    <?php
  }
);   

/**
 * Admin enqueue
 */

add_action( 
	'admin_enqueue_scripts', 
	function () {

    wp_enqueue_script(
      'astra-child-noshibari-theme-admin-js', 
      get_stylesheet_directory_uri() . '/js-css/admin.js',
      array(
        'wp-data',
        'jquery'
      ), 
      filemtime(get_stylesheet_directory() . '/js-css/admin.js'),
      true
    );
	}, 
	999 
);

/* Theme setup */

add_post_type_support( 'page', 'excerpt' );

/* Clean head */

require_once(dirname(__FILE__) . '/cleanhead.php');

/* Woocommerce */

// texts

add_filter(
  'woocommerce_product_add_to_cart_text',
  function($text) {

    return 'Reservar'; 
  }
);

add_filter( 
  'woocommerce_product_single_add_to_cart_text', 
  function() {

    return 'Reservar';
  }
);

// Enable gutenberg for woocommerce

add_filter( 
  'use_block_editor_for_post_type', 
  function ($can_edit, $post_type) {

    if ( $post_type == 'product' ) {

      $can_edit = true;
    }
    
    return $can_edit;
  }, 
  10, 
  2 
);

// Disable Product comments

add_filter( 
  'woocommerce_product_tabs', 
  function ($tabs) {

    unset($tabs['reviews']);
    return $tabs;
  }, 
  98
);

/* Query loop */

add_action(
  'pre_get_posts',
  function ( $query ) {
    
    if (
      !is_admin() 
      && 
      $query->is_main_query() 
      && 
      is_post_type_archive( 'page' 
    ) ) {
            
      $query->set('orderby', 'menu_order');
      $query->set('order', 'ASC'); 
    }
  }
);
