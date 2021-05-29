<?php

namespace App\Controllers;

use App\Classes\Vite;
use Sober\Controller\Controller;

class FrontPage extends Controller {
  public function __before() {
    // Vite::register('vue-example.ts');
    // Vite::register('react-example.tsx');
  }
}
