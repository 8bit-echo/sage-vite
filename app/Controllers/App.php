<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class App extends Controller {
  public function siteName() {
    return get_bloginfo('name');
  }

  public static function title() {
    if (is_home()) {
      if (get_option('page_for_posts', true)) {
        return get_bloginfo('name');
      }
      return __('Latest Posts', 'sage');
    }
    if (is_archive()) {
      return get_the_archive_title();
    }
    if (is_search()) {
      return sprintf(__('Search Results for %s', 'sage'), get_search_query());
    }
    if (is_404()) {
      return __('Not Found', 'sage');
    }
    return get_the_title();
  }

  public static function custom_logo() {
    return preg_replace("/(width|height)=\"\d+\"/", "", get_custom_logo());
  }
}
