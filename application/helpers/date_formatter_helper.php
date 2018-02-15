<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('format_date_slash')){
    function format_date_slash($date){
 		$date = date_create($date);
		return date_format($date,"m/d/Y");
    }
}


if ( ! function_exists('get_month_name')){
    function get_month_name($month){
 		$month_name = "";
		
	    switch($month){
	        case '01' : 
	            $month_name = "January";
	        break;
	         case '02' : 
	            $month_name = "February";
	        break;
	         case '03' : 
	            $month_name = "March";
	        break;
	         case '04' : 
	            $month_name = "April";
	        break;
	         case '05' : 
	            $month_name = "May";
	        break;
	         case '06' : 
	            $month_name = "June";
	        break;
	         case '07' : 
	            $month_name = "July";
	        break;
	         case '08' : 
	            $month_name = "August";
	        break;
	         case '09' : 
	            $month_name = "September";
	        break;
	         case '10' : 
	            $month_name = "October";
	        break;
	         case '11' : 
	            $month_name = "November";
	        break;
	         case '12' : 
	            $month_name = "December";
	        break;
	    }
	    return $month_name;
    }
}


/*if ( ! function_exists('format_date_word')){
    function format_date_word($date){
 		$date = date_create($date);
		return date_format($date,"F d, Y");
    }
}*/
if ( ! function_exists('rangeMonth')){ 
	function rangeMonth($datestr) {
		date_default_timezone_set(date_default_timezone_get());
		$dt = strtotime($datestr);
		$res['start'] = date('Y-m-d', strtotime('first day of this month', $dt));
		$res['end'] = date('Y-m-d', strtotime('last day of this month', $dt));
		return $res;
	}
}
if ( ! function_exists('rangeWeek')){ 
	function rangeWeek($datestr) {
		date_default_timezone_set(date_default_timezone_get());
		$dt = strtotime($datestr);
		$res['start'] = date('N', $dt)==1 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('last monday', $dt));
		$res['end'] = date('N', $dt)==7 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('next sunday', $dt));
		return $res;
	}
}

if ( ! function_exists('format_oracle_date')){ 
	function format_oracle_date($datestr) {
	 	$date = date_create($datestr);
		return date_format($date,"d-M-Y");
	}
}



