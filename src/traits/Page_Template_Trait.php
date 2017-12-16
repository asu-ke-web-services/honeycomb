<?php

namespace Honeycomb\Traits;

/**
 * Page Template Trait
 *
 * @see http://www.wpexplorer.com/wordpress-page-templates-plugin/
 */
trait Page_Template_Trait {
  public function load_dependencies() {
    // No dependencies for now
    if ( method_exists( $this, 'load_additional_dependencies' ) ) {
      $this->load_additional_dependencies();
    }
  }

  public function define_hooks() {
    $this->add_filter( 'wp_insert_post_data', $this, 'wp_insert_post_data' );
    $this->add_filter( 'template_include', $this, 'template_include' );

    // Add a filter to the attributes metabox to inject template into the cache.
    if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {
      // 4.6 and older
      $this->add_filter( 'page_attributes_dropdown_pages_args', $this, 'page_attributes_dropdown_pages_args' );
    } else {
      // Add a filter to the wp 4.7 version attributes metabox
      $this->add_filter( 'theme_page_templates', $this, 'add_new_template' );
    }

    if ( method_exists( $this, 'define_additional_hooks' ) ) {
      $this->define_additional_hooks();
    }
  }
  public function page_attributes_dropdown_pages_args( $atts ) {
    $this->register_project_templates();
    return $atts;
  }

  /**
   * Adds templates to the page dropdown for WordPress v4.7+
   *
   */
  public function add_new_template( $posts_templates ) {
    $posts_templates = array_merge( $posts_templates, $this->templates );
    return $posts_templates;
  }

  public function wp_insert_post_data( $atts ) {
    $this->register_project_templates();
    return $atts;
  }

  public function template_include( $template ) {
    global $post;
    if ( $post === null ) {
      return $template;
    }

    if ( ! isset( $this->templates[ get_post_meta(
        $post->ID,
        '_wp_page_template',
        true
    ) ] ) ) {
      return $template;
    }

    if ( is_search() ) {
      return $template;
    }

    $file = $this->path_to_templates
        . get_post_meta( $post->ID, '_wp_page_template', true );

    if ( file_exists( $file ) ) {
      return $file;
    } else {
      echo $file;
    }

    return $template;
  }

  /**
   * Register project templates
   */
  protected function register_project_templates () {
    // Create the key used for the themes cache
    $cache_key = 'page_templates-' . md5(
        get_theme_root() . '/' . get_stylesheet()
    );

    // Retrieve the cache list
    // If it doesn't exist, or it's emty prepare an array
    $templates = wp_get_theme()->get_page_templates();
    if ( empty( $templates ) ) {
      $templates = array();
    }

    // New cache, therefore remove the old one
    wp_cache_delete( $cache_key, 'themes' );

    // Now add our template to the list of templates by merging our templates
    // with the existing templates array frmo the cache.
    $templates = array_merge( $templates, $this->templates );

    // Add the modified cache to allow WordPress to pick it up for listing
    // available templates
    if ( ! wp_cache_add( $cache_key, $templates, 'themes', 1800 ) ) {
      error_log( 'Error adding page template wp cache '.$cache_key );
    }
  }
}