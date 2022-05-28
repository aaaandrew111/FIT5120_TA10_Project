<?php
/*
  Plugin Name: Custom Database Applications by Caspio
  Plugin URI: http://www.caspio.com
  Description: This plugin allows you to embed Caspio apps seamlessly on any WordPress post or page, simply by pasting the provided shortcode.
  Author: Caspio
  Version: 2.1
  Author URI: http://www.caspio.com
 */
define( 'CASPIOBRIDGEPATH', trailingslashit(dirname(__FILE__)) );

require_once( CASPIOBRIDGEPATH . 'classes/class-shortcode-parser.php' );
require_once( CASPIOBRIDGEPATH . 'classes/class-datapage-loader.php' );
?>