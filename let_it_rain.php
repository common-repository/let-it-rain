<?php
defined('ABSPATH') or die("nope");
/**
 * Plugin Name: Let it Rain
 * Plugin URI: 
 * Description: Lets the Conent you choose rain down on your Website.
 * Version: 1.1
 * Author: Dennis Hartenfels
 * Author URI: 
 * License: 
 * License URI:  
 * Text Domain:  let_it_rain-plugin
 */


class Let_it_rain_Plugin
{
  public function __construct()
  {
    // Hook into the admin menu
    add_action('admin_menu', array($this, 'create_plugin_settings_page'));
    add_action( 'admin_init', array( $this, 'setup_sections' ) );
    add_action( 'admin_init', array( $this, 'setup_fields' ) );



  }

  public function create_plugin_settings_page()
  {
    // Add the menu item and page
    $page_title = 'Let it Rain Settings Page';
    $menu_title = 'Let it Rain Plugin';
    $capability = 'manage_options';
    $slug = 'smashing_fields';
    $callback = array($this, 'plugin_settings_page_content');
    $icon = 'dashicons-admin-plugins';
    $position = 100;

    add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
  }

  public function plugin_settings_page_content()
  { ?>
    <div class="wrap">
      <h1 style="text-align:center;">Let it Rain Settings Page</h1>
      <form method="post" action="options.php">
        <?php
        settings_fields('smashing_fields');
        do_settings_sections('smashing_fields');
        submit_button();
        ?>
      </form>
    </div><?php

  }

  public function setup_sections() {
    add_settings_section( 'our_first_section', 'Emoji Selection', array( $this, 'section_callback' ), 'smashing_fields' );
  }

    public function section_callback( $arguments ) {
      switch( $arguments['id'] ){
          case 'our_first_section':
              echo 'Here you can select your wanted Emoji to be displayed!';
              break;
      }
  }

  public function setup_fields() {
    $fields = array(
        array(
            'uid' => 'our_first_field',
            'label' => 'Emoji Selection',
            'section' => 'our_first_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => 'Enter the Symbol you want to display',
            'helper' => 'Check https://emojiterra.com/ for Emojis',
            'supplemental' => 'You can get the HTML Code for Emojis here: https://emojiterra.com/',
            'default' => 'schnee'
        )
    );
    foreach( $fields as $field ){
        add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'smashing_fields', $field['section'], $field );
        register_setting( 'smashing_fields', $field['uid'] );
    }
  }

  public function field_callback( $arguments ) {
    $value = get_option( $arguments['uid'] ); // Get the current value, if there is one
    if( ! $value ) { // If no value exists
        $value = $arguments['default']; // Set to our default
    }

    // Check which type of field we want
    switch( $arguments['type'] ){
        case 'text': // If it is a text field
            printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
            break;
    }

    // If there is help text
    if( $helper = $arguments['helper'] ){
        printf( '<span class="helper"> %s</span>', $helper ); // Show it
    }

    // If there is supplemental text
    if( $supplimental = $arguments['supplemental'] ){
        printf( '<p class="description">%s</p>', $supplimental ); // Show it
    }
  }
    
}

new Let_it_rain_Plugin();

function snowflakes_style()
{
  echo '<div class="container"><div class="snowfall">';
  for ($i = 1; $i <= 120; $i++) {
    echo '<div class="snowflake">'. get_option('our_first_field') .'</div>';
  }
  echo '</div></div>';
  wp_register_style('snowflake', plugins_url('css\snowflake.css', __FILE__), false, '1.0.0', 'all');
  wp_enqueue_style('snowflake');
}

add_action('wp_enqueue_scripts', 'snowflakes_style');