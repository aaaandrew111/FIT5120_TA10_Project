<?php
    class DataPage_Loader{
        public static function caspio_session(){
            if ( ! $_SESSION ) {
                session_start();
            }
        }
        public static function process_caspio_shortcode ( $atts = NULL, $content = '' ) {
            $parser = new Shortcode_Parser($atts, $content);
            return $parser->parse_shortcode() ? $parser->load_datapage() : '';
        }
    }

    add_shortcode( 'caspio', array( 'DataPage_Loader', 'process_caspio_shortcode' ) );
    add_action( 'init', array( 'DataPage_Loader', 'caspio_session' ) );
?>