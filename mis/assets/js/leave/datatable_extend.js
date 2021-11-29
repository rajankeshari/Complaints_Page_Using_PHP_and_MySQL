/**
 * Created by nishant raj on 2/7/15.
 */

jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "monthYear-pre": function ( s ) {
        var d = s.split('<br>');
        var q = d[0].split("<center>")[1] + " " + d[1].split("</center>")[0];
        var a = new Date(q);
        return a.getTime()/1000;
    },

    "monthYear-asc": function ( a, b ) {
        var ta = new Date(a);
        var tb = new Date(b);
        b = tb.getTime();
        a = ta.getTime();
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },

    "monthYear-desc": function ( a, b ) {
        var ta = new Date(a);
        var tb = new Date(b);
        b = tb.getTime();
        a = ta.getTime();
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
} );

jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "monthYearDate-pre": function ( s ) {
        var d = s.split('<center>');
        var q = d[1].split("</center>")[0];
        var a = new Date(q);
        return a.getTime()/1000;
    },

    "monthYearDate-asc": function ( a, b ) {
        var ta = new Date(a);
        var tb = new Date(b);
        b = tb.getTime();
        a = ta.getTime();
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },

    "monthYearDate-desc": function ( a, b ) {
        var ta = new Date(a);
        var tb = new Date(b);
        b = tb.getTime();
        a = ta.getTime();
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
} );
jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "num-html-pre": function ( a ) {
        var x = String(a).replace( /<[\s\S]*?>/g, "" );
        return parseFloat( x );
    },

    "num-html-asc": function ( a, b ) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },

    "num-html-desc": function ( a, b ) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
} );
jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "period-pre": function ( a ) {
        var x = a.split("<center>")[1].split("</center>")[0].split(" ");
        x = parseInt(x[0])*24 + parseInt(x[2]);
        return parseInt( x );
    },

    "period-asc": function ( a, b ) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },

    "period-desc": function ( a, b ) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
} );
