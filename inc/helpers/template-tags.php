<?php
/**
 * Custom template tags for the theme.
 *
 * @package FutureWordPressScratchProject
 */
if( ! function_exists( 'is_FwpActive' ) ) {
  function is_FwpActive( $opt ) {
    if( ! defined( 'STOCK_PHOTO_DUPLICATOR_OPTIONS' ) ) {return false;}
    return ( isset( STOCK_PHOTO_DUPLICATOR_OPTIONS[ $opt ] ) && STOCK_PHOTO_DUPLICATOR_OPTIONS[ $opt ] == 'on' );
  }
}
if( ! function_exists( 'get_FwpOption' ) ) {
  function get_FwpOption( $opt, $def = false ) {
    if( ! defined( 'STOCK_PHOTO_DUPLICATOR_OPTIONS' ) ) {return false;}
    return isset( STOCK_PHOTO_DUPLICATOR_OPTIONS[ $opt ] ) ? STOCK_PHOTO_DUPLICATOR_OPTIONS[ $opt ] : $def;
  }
}