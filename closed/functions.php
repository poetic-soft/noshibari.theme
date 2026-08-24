<?php

/**
 * 
 * @package WordPress
 * @subpackage AlbertoMoral
 * @since AlbertoMoral 1.0
 */

add_action(
  'after_setup_theme', 
  function () {
  
    add_theme_support('title-tag');
    add_theme_support(
      'custom-logo',
      array(
        'height'      => 240,
        'width'       => 240,
        'flex-height' => true,
      )
    );
  }
);
