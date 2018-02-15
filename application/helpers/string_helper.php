<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('replace_ampersant')){
    function replace_ampersant($str){
    	return str_replace('&', " AND ", $str);
    }
}

if ( ! function_exists('pad_zeros')){
    function pad_zeros($str,$number_of_zeros){
    	return str_pad($str,$number_of_zeros,"0",STR_PAD_LEFT);
    }
}

if ( ! function_exists('convert_to_utf8')){
    function convert_to_utf8($content) {
    if(!mb_check_encoding($content, 'UTF-8')
        OR !($content === mb_convert_encoding(mb_convert_encoding($content, 'UTF-32', 'UTF-8' ), 'UTF-8', 'UTF-32'))) {

        $content = mb_convert_encoding($content, 'UTF-8');

        if (mb_check_encoding($content, 'UTF-8')) {
            // log('Converted to UTF-8');
        } else {
            // log('Could not converted to UTF-8');
        }
    }
    return $content;
    } 
}

if ( ! function_exists('format_ppr_no')){
    function format_ppr_no($str){
        return sprintf('%05d',$str);
    }
}

