<?php  

/**
 * Admin enqueue
 */

add_action( 
	'admin_enqueue_scripts', 
	function () {

    wp_enqueue_script(
      'astra-child-noshibari-theme-admin-js', 
      get_stylesheet_directory_uri() . '/front/admin/main.js',
      array(
        'wp-data',
        'jquery'
      ), 
      filemtime(get_stylesheet_directory() . '/front/admin/main.js'),
      true
    );

		wp_enqueue_style( 
			'astra-child-noshibari-theme-admin-css',
			get_stylesheet_directory_uri() . '/front/admin/main.css', 
			[], 
			filemtime(get_stylesheet_directory() . '/front/admin/main.css'),
			'all' 
		);
	}, 
	999 
);  

/**
 * Front enqueue
 */

add_action( 
	'wp_enqueue_scripts', 
	function () {

    wp_enqueue_style('dashicons');

    wp_enqueue_script(
      'astra-child-noshibari-theme-flickity-js', 
      get_stylesheet_directory_uri() . '/front/utils/flickity.pkgd.min.js',
      [], 
      filemtime(get_stylesheet_directory() . '/front/utils/flickity.pkgd.min.js'),
      true
    );

    wp_enqueue_script(
      'astra-child-noshibari-theme-jquery-validate-js', 
      get_stylesheet_directory_uri() . '/front/utils/jquery.validate.min.js',
      [
        'jquery',
        'jquery-form'
      ], 
      filemtime(get_stylesheet_directory() . '/front/utils/jquery.validate.min.js'),
      true
    );

    wp_enqueue_script(
      'astra-child-noshibari-theme-app-js', 
      get_stylesheet_directory_uri() . '/front/app/main.js',
      [
        'astra-child-noshibari-theme-flickity-js',
        'astra-child-noshibari-theme-jquery-validate-js'
      ], 
      filemtime(get_stylesheet_directory() . '/front/app/main.js'),
      true
    );

    wp_enqueue_script(
      'astra-child-noshibari-theme-jitsi-js', 
      get_stylesheet_directory_uri() . '/front/jitsi/main.js',
      [], 
      filemtime(get_stylesheet_directory() . '/front/jitsi/main.js'),
      true
    );

		wp_enqueue_style( 
			'astra-child-noshibari-theme-flickity-css',
			get_stylesheet_directory_uri() . '/front/utils/flickity.css', 
			[
        'astra-theme-css'
      ], 
			filemtime(get_stylesheet_directory() . '/front/utils/flickity.css'),
			'all' 
		);

		wp_enqueue_style( 
			'astra-child-noshibari-theme-app-css',
			get_stylesheet_directory_uri() . '/front/app/main.css', 
			[
        'dashicons',
        'astra-child-noshibari-theme-flickity-css'
      ], 
			filemtime(get_stylesheet_directory() . '/front/app/main.css'),
			'all' 
		);

		wp_enqueue_style( 
			'astra-child-noshibari-theme-jitsi-css',
			get_stylesheet_directory_uri() . '/front/jitsi/main.css', 
			[], 
			filemtime(get_stylesheet_directory() . '/front/jitsi/main.css'),
			'all' 
		);
	}, 
	999 
);