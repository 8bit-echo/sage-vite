<?php

use App\Classes\API;
new API();

/*========================*/
/*   Define Functions     */
/*========================*/
/** Remove non-essential items from the WP Admin bar  */
function clean_admin_bar() {
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('wp-logo');
    // $wp_admin_bar->remove_menu('customize');
  $wp_admin_bar->remove_menu('updates');
  $wp_admin_bar->remove_menu('comments');
  $wp_admin_bar->remove_menu('itsec_admin_bar_menu');
  $wp_admin_bar->remove_menu('wpseo-menu');
}

/** Global custom stylesheet for WP back-end. */
function get_sage_admin_styles() {
  wp_register_style('sage-admin-styles', get_theme_file_uri() . '/resources/sage-admin.css');
  wp_enqueue_style('sage-admin-styles');
}

/** Allow uploads of SVGs to the media library */
function allow_svg_upload($mimes) {
  $mimes['svg'] = 'image/svg+xml';

  return $mimes;
}

/** fixes improper display of svg thumbnails in media library */
function fix_svg_thumb_display() {
  echo '<style>
    td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail { 
        width: 100% !important; 
        height: auto !important; 
    }
    </style>';
}

/** Hide pages for CPTUI and ACF if the user isn't privileged. */
function remove_menu_items_from_admin() {
  remove_menu_page('cptui_main_menu');
  remove_menu_page('edit.php?post_type=acf-field-group');
}

/** Browser detection function for Last 3 Versions of IE */
function is_ie() {
  return boolval(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/') !== false);
}

/** opinionated theme default setup */
function theme_setup() {
  add_theme_support('align-wide');
  add_theme_support('disable-custom-colors');
}

/** lazyload images from wysiwyg.  */
function lazy_load_wysiwyg_images($content) {
  // parse DOM
  if (!strlen($content)) return $content;
  $content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
  $document = new DOMDocument();
  libxml_use_internal_errors(true);
  $document->loadHTML(utf8_decode($content));

  // replace image src with data-src
  $imgs = $document->getElementsByTagName('img');
  foreach ($imgs as $img) {
    $existing_class = $img->getAttribute('class');
    $img->setAttribute('class', "$existing_class lazy");
    $img->setAttribute('data-src', $img->getAttribute('src'));
    $img->setAttribute('src', '');
  }

  $html = $document->saveHTML();
  return $html;
}

// change default excerpt text from "Continued" to "Read More"
function custom_excerpt_link_text($more) {
  $post = get_post();
  if (is_object($post)) {
    return '&hellip;<a class="read-more-link" href="' . get_the_permalink($post->ID) . '">Read More</a>';
  }
}

function strip_archive_title( $title ) {
  if ( is_category() ) {
    $title = single_cat_title( '', false );
  } elseif ( is_tag() ) {
    $title = single_tag_title( '', false );
  } elseif ( is_author() ) {
    $title = '<span class="vcard">' . get_the_author() . '</span>';
  } elseif ( is_post_type_archive() ) {
    $title = post_type_archive_title( '', false );
  } elseif ( is_tax() ) {
    $title = single_term_title( '', false );
  } elseif (is_month()) {
    $title = single_month_title('', false);
  }

  return $title;
}



/*============================*/
/*      Admin Functions       */
/*============================*/
if (is_admin()) {
  $current_user = wp_get_current_user();
  add_action('admin_head', 'get_sage_admin_styles');

  // User is not an admin
  if (!in_array('administrator', $current_user->roles)) {
    add_action('admin_init', 'remove_menu_items_from_admin');
  }
}

/*===========================*/
/*          Actions          */
/*===========================*/

add_action('wp_before_admin_bar_render', 'clean_admin_bar');
add_action('admin_head', 'fix_svg_thumb_display');
add_action('after_setup_theme', 'theme_setup');


/*===========================*/
/*          Filters          */
/*===========================*/

add_filter('upload_mimes', 'allow_svg_upload');
add_filter('excerpt_more', 'custom_excerpt_link_text', 21);
add_filter('the_content', 'lazy_load_wysiwyg_images', 10, 1);
add_filter( 'get_the_archive_title', 'strip_archive_title' );
