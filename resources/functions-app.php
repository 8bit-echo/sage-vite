<?php
// define site-specific functions

// Custom Color Palette.
add_action('after_setup_theme', function() {
  $colors = [
    [
      'name'    => 'White',
      'slug'    => 'white',
      'color'   => '#fff',
    ],
    [
      'name'    => 'Black',
      'slug'    => 'black',
      'color'   => '#222',
    ],
  ];

  add_theme_support('editor-color-palette', $colors);
});


// Custom WYSIWYG FORMATS
function cusom_tinymce_formats($init_array) {
  $style_formats = [
    [
      'title' => 'wrapper',
      'block' => 'span',
      'classes' => '',
      'wrapper' => true // is this a wrapper element?
    ],
  ];
  $init_array['style_formats'] = json_encode($style_formats);

  return $init_array;
}
// Attach callback to 'tiny_mce_before_init' 
add_filter('tiny_mce_before_init', 'cusom_tinymce_formats');
