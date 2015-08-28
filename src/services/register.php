<?php

namespace Honeycomb\Services;

class Register {
  public function register( $classes ) {
    foreach( $classes as $class_name ) {
      ( new $class_name )->run();
    }
  }
}