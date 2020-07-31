<?php

namespace App\Classes;

use WP_POST;

class API {

  public function __construct() {
    $this->addTaxonomies();
    $this->addFeaturedMedia();
    $this->addCustomFields();
    $this->addPrettyDateField();
  }

  /**
   * Gets a list of sane post types excluding those that are built into WP.
   */
  public static function getPostTypes() {
    return array_filter(get_post_types(), function ($post_type) {
      return !in_array($post_type, self::getExtraneousPostTypes());
    });
  }

  /**
   * Get a list of all the post types in WP that are not commonly used or visible.
   */
  public static function getExtraneousPostTypes() {
    return [
      'attachment',
      'revision',
      'nav_menu_item',
      'custom_css',
      'customize_changeset',
      'oembed_cache',
      'user_request',
      'wp_block',
      'acf-field-group',
      'acf-field',
    ];
  }

  /**
   * Register Taxonomies and terms to the WP REST API post response.
   */
  public function addTaxonomies() {
    foreach (self::getPostTypes() as $post_type) {
      register_rest_field($post_type, 'taxonomies', [
        'get_callback' => function ($post) use ($post_type) {
          return self::getTermSchema($post_type, $post);
        },
      ]);
    }
  }

  /**
   * Return the data structure of taxonomies, labels and terms in the REST response.
   */
  private static function getTermSchema($post_type, $post) {
    $schema = [];
    $taxonomies = get_object_taxonomies($post_type, 'objects');
    foreach ($taxonomies as $tax) {
      $schema[$tax->name] = [
        'label' => $tax->label,
        'terms' => []
      ];

      $terms = get_the_terms($post['id'], $tax->name);

      if (is_array($terms)) {
        $schema[$tax->name]['terms'] = array_map(
          function ($term) {
            return $term->name;
          },
          $terms
        );
      }
    }
    return $schema;
  }

  /**
   * Add featured media URL to REST API response
   */
  private function addFeaturedMedia() {
    foreach (self::getPostTypes() as $post_type) {
      register_rest_field($post_type, 'featured_media_url', [
        'get_callback' => function ($post) {
          if ($post instanceof WP_POST) {
            return get_the_post_thumbnail_url($post->ID);
          } elseif (is_array($post)) {
            return get_the_post_thumbnail_url($post['id']);
          }
        },
      ]);
    }
  }

  /**
   * Add all ACF fields to each post type
   * @return Fields?
   * @return false
   */
  private function addCustomFields() {
    if (function_exists('get_fields')) {
      foreach (self::getPostTypes() as $post_type) {
        register_rest_field($post_type, 'fields', [
          'get_callback' => function ($post) {
            if (is_object($post)) {
              return get_fields($post->id);
            } elseif (is_array($post) && array_key_exists('id', $post)) {
              return get_fields($post['id']);
            }
          },
        ]);
      }
    }
  }

  private function addPrettyDateField() {
    foreach (self::getPostTypes() as $post_type) {
      register_rest_field($post_type, 'pretty_date', [
        'get_callback' => function ($post) {
          return get_the_date(get_option('date_format'), $post['id']);
        },
      ]);
    }
  }
}
