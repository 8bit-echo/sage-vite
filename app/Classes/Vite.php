<?php
// Adapted from https://github.com/andrefelipe/vite-php-setup/blob/master/public/helpers.php
namespace App\Classes;

use App\Controllers\App;

define('IS_DEVELOPMENT', App::isDev());

class Vite
{

  public static function base_path()
  {
    return "/wp-content/themes/" . App::theme_name() . "/dist/";
  }

  public static function useVite(string $script = 'main.ts')
  {
    self::jsPreloadImports($script);
    self::cssTag($script);
    self::register($script);
  }

  public static function register($entry)
  {
    $url = IS_DEVELOPMENT
      ? 'http://localhost:3000/' . $entry
      : self::assetUrl($entry);

    if (!$url) {
      return '';
    }

    wp_register_script("module/sage/$entry", $url, false, true);
    wp_enqueue_script("module/sage/$entry");
  }

  private static function jsPreloadImports($entry)
  {
    if (IS_DEVELOPMENT) {
      add_action('wp_head', function () {
        echo '<script type="module">
        import RefreshRuntime from "http://localhost:3000/@react-refresh"
        RefreshRuntime.injectIntoGlobalHook(window)
        window.$RefreshReg$ = () => {}
        window.$RefreshSig$ = () => (type) => type
        window.__vite_plugin_react_preamble_installed__ = true
        </script>';
      });
      return;
    }

    $res = '';
    foreach (self::importsUrls($entry) as $url) {
      $res .= '<link rel="modulepreload" href="' . $url . '">';
    }

    add_action('wp_head', function () use (&$res) {
      echo $res;
    });
  }

  private static function cssTag(string $entry): string
  {
    // not needed on dev, it's inject by Vite
    if (IS_DEVELOPMENT) {
      return '';
    }

    $tags = '';
    foreach (self::cssUrls($entry) as $url) {
      wp_register_style("sage/$entry", $url);
      wp_enqueue_style("sage/$entry", $url);
    }
    return $tags;
  }


  // Helpers to locate files

  private static function getManifest(): array
  {
    $content = file_get_contents(get_theme_root() . '/' . App::theme_name() . '/dist/manifest.json');

    return json_decode($content, true);
  }

  private static function assetUrl(string $entry): string
  {
    $manifest = self::getManifest();

    return isset($manifest[$entry])
      ? self::base_path() . $manifest[$entry]['file']
      : self::base_path() . $entry;
  }

  private static function getPublicURLBase()
  {
    return IS_DEVELOPMENT ? '/dist/' : self::base_path();
  }

  private static function importsUrls(string $entry): array
  {
    $urls = [];
    $manifest = self::getManifest();

    if (!empty($manifest[$entry]['imports'])) {
      foreach ($manifest[$entry]['imports'] as $imports) {
        $urls[] = self::getPublicURLBase() . $manifest[$imports]['file'];
      }
    }
    return $urls;
  }

  private static function cssUrls(string $entry): array
  {
    $urls = [];
    $manifest = self::getManifest();

    if (!empty($manifest[$entry]['css'])) {
      foreach ($manifest[$entry]['css'] as $file) {
        $urls[] = self::getPublicURLBase() . $file;
      }
    }
    return $urls;
  }
}
