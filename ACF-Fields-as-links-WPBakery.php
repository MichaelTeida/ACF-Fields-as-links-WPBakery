<?php
/*
Plugin Name: ACF-Fields-as-links-WPBakery
Description: Custom WPBakery elements for displaying ACF fields
Version: 1.3
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
        array(
          'type' => 'textfield',
          'heading' => 'Text Size',
          'param_name' => 'text_size',
          'description' => 'Enter the text size for the field value (e.g., "16px", "1rem")',
        ),
        array(
          'type' => 'textfield',
          'heading' => 'CSS Class',
          'param_name' => 'css_class',
          'description' => 'Enter a CSS class to be added to the element',
        ),
      ),
    )
  );
}

add_action('vc_before_init', 'load_custom_acf_elements');

function render_acf_field_display_element($atts) {
  extract(
    shortcode_atts(
      array(
        'acf_field' => '',
        'prefix' => '',
        'text_size' => '16px',
        'css_class' => '',
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
      $output = '<a href="' . $prefix . $field_value . '"';
    } else {
      $output = '<span';
    }

    if ($css_class) {
      $output .= ' class="' . $css_class . '"';
    }

    $output .= ' style="font-size:' . $text_size . ';">' . $field_value;

    if ($prefix && $acf_field) {
      $output .= '</a>';
    } else {
      $output .= '</span>';
    }

    return $output;
  }

  return '';
}

add_shortcode('acf_field_display', 'render_acf_field_display_element');
