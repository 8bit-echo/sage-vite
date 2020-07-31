<?php 

namespace App\Controllers\Partials;
use WP_Query;
use WP_Post;

trait PostTrait {


  public static function getRelatedByTerms($post, $term) {
    $custom_taxterms = wp_get_object_terms($post->ID, $term, ['fields' => 'ids']);
    $args = [
      'post_type'      => get_post_type($post),
      'post_status'    => 'publish',
      'posts_per_page' => 1,
      'orderby'        => 'term_id',
      'post__not_in'   => [$post->ID],
      'tax_query'      => [
        [
          'taxonomy' => $term,
          'field' => 'id',
          'terms' => $custom_taxterms
        ]
      ],
      
    ];
    $related_items = new WP_Query($args);

    if ($related_items->have_posts()) {
      return $related_items->posts;
    } else {
      return [];
    }
  }

  public static function getByTerm($post_type, $taxonomy, $term, $args = []) {
      $default_args = [
          'post_type'      => $post_type,
          'post_status'    => 'publish',
          'posts_per_page' => -1,
          'tax_query'      => [
            'relation' => 'AND',
            [
              'taxonomy'  => $taxonomy,
              'field'     => 'slug',
              'terms'     => $term,
              'operator'  => 'IN'
            ]
          ],
      ];
      $args = array_merge($default_args, $args);

      $the_posts = new WP_Query($args);

      if ($the_posts->have_posts()) {
        $posts = self::addACFFields($the_posts->posts);
        return $posts;
      } else {
        return [];
      }

  }

  public static function getByCategory($post_type, $category_slug, $args = []) {
    $args = [
      'post_type' => $post_type,
      'posts_per_page' => -1,
      'lang' => '',
      'category_name' => $category_slug,
    ];

    $resources = new WP_Query($args);
    if ($resources->have_posts()) {
      return self::addACFFields($resources->posts);
    } else {
      return [];
    }
  }

  public static function getByMeta($post_type, $key, $value, $limit = -1) {
    $query = new WP_Query([
      'post_type'      => $post_type,
      'post_status'    => 'publish',
      'posts_per_page' => $limit,
      'meta_query'     => [
        [
          'meta_key'   => $key,
          'meta_value' => $value,
          'compare'    => '='
        ]
      ]
    ]);

    if ($query->have_posts()) {
      return $query->posts;
    } else {
      return [];
    }
  }

  public static function getByPostType($slug, $optional_args = []) {

    $args = [
      'post_type'      => $slug,
      'post_status'    => 'publish',
      'posts_per_page' => -1,
      'orderby'        => 'title',
      'order'          => 'ASC'
    ];

    $args = array_merge($args, $optional_args);

    $query = new WP_Query($args);

    if ($query->have_posts()) {
      return $query->posts;
    } else {
      return [];
    }
  }

  public static function addACFFields($posts) {
    if (is_array($posts)) {
      foreach ($posts as $post) {
        // add ACF fields to the original post object.
        $fields = get_fields($post->ID);
        foreach ($fields as $key => $value) {
          // prefer object notation when possible.
          if (is_array($value)) {
            $post->{$key} = (object)$value;
          } else {
            $post->{$key} = $value;
          }
        }
      }
      return $posts;
    } elseif ($posts instanceof WP_Post || is_object($posts)) {
      $fields = get_fields($posts->ID);
      foreach ($fields as $key => $value) {
        // prefer object notation when possible.
        if (is_array($value)) {
          $posts->{$key} = (object)$value;
        } else {
          $posts->{$key} = $value;
        }
      }
      return $posts;
    } else {
      return $posts;
    }
  }

  public static function getChildPages() {
    global $post;
    $query = new WP_Query([
      'post_type'      => 'page',
      'posts_per_page' => -1,
      'post_parent'    => $post->ID,
      'order'          => 'ASC',
      'orderby'        => 'menu_order'
    ]);

    if ($query->have_posts()) {
      return self::addACFFields($query->posts);
    } else {
      return [];
    }
  }

}