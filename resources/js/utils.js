/*
 * ----------------- H E A D E R ---------------------
 *
 * 	Author: John Paul M. Alcabasa
 * 	Date Created: August 15, 2017
 * 	System: AP System
 * 	Description: Utilities Class for common validation usage
 *
 * ---------------------------------------------------
 */


var utils = {
		
	format_number: function (nStr){
	    nStr += '';
	    x = nStr.split('.');
	    x1 = x[0];
	    x2 = x.length > 1 ? '.' + x[1] : '';
	    var rgx = /(\d+)(\d{3})/;
	    while (rgx.test(x1)) {
	        x1 = x1.replace(rgx, '$1' + ',' + '$2');
	    }
	    return x1 + x2;
	}

};



