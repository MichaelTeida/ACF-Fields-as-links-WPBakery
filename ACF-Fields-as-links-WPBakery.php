<?php
/*
Plugin Name: ACF-Fields-as-links-WPBakery
Description: Custom WPBakery elements for displaying ACF PRO fields
Version: 1.1
Author: Michał Głuch
*/

// Register and load the WPBakery elements
function load_custom_acf_elements() {
  if (!defined('WPB_VC_VERSION')) {
    return;
  }

  // Register the new element
  vc_map(
    array(
      'name' => 'ACF Field Display',
      'base' => 'acf_field_display',
      'icon' => 'icon-wpb-acf',
      'category' => 'Content',
      'params' => array(
        array(
          'type' => 'textfield',
          'heading' => 'ACF Field',
          'param_name' => 'acf_field',
          'description' => 'Enter the ACF field name inside double curly braces, e.g., {{field_name}}',
        ),
        array(
          'type' => 'textfield',
          'heading' => 'Prefix',
          'param_name' => 'prefix',
          'description' => 'Enter a prefix to be added before the field value (e.g., "https://", "tel:")',
        ),
      ),
    )
  );
}

add_action('vc_before_init', 'load_custom_acf_elements');

// Render the output of the element
function render_acf_field_display_element($atts) {
  extract(
    shortcode_atts(
      array(
        'acf_field' => '',
        'prefix' => '',
      ),
      $atts
    )
  );

  $field_value = '';
  if ($acf_field && function_exists('get_field')) {
    preg_match('/\{\{(.*?)\}\}/', $acf_field, $matches);
    if (isset($matches[1])) {
      $field_value = get_field($matches[1]);
    }
  }

  if ($field_value) {
    $output = '';
    if ($prefix && $acf_field) {
      $output = $prefix . $field_value;
    } else {
      $output = $field_value;
    }

    return $output;
  }

  return '';
}

add_shortcode('acf_field_display', 'render_acf_field_display_element');
