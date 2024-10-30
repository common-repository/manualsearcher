<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    function hkms_search() {
        
        //form variables
        $search = sanitize_text_field( $_POST['search'] );
        $url = esc_url( $_POST['url'] );
        $domain = esc_url( $_POST['domain'] );
        
        //create output
        $search = str_replace(' ','+',$search);
        $url = $url .'?s=' .$search .'&utm_source=' .$domain .'&utm_medium=referral&utm_campaign=widget';
        header('Location: '.esc_url_raw($url));

    }
?>