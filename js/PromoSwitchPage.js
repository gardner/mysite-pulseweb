var formExists = ($('#FoundationForm_PromoSwitchForm').length == 0) ? false : true;
//console.log(formExists);
if (formExists) {  // submitted form missing this element
    var parsleyFormInstance = $('#FoundationForm_PromoSwitchForm').parsley({
        errorsWrapper: '<div class=\"parsley-errors-list\"></div>',
        errorTemplate: '<div></div>',
        trigger: 'change',
        excluded: ':hidden'
    });

    $(document).ready(function () {

        //$("#ApplicantName").hide();
        //// gotta test for click on whole label. Its a foundation for where original elements are hidden
        //$("#FoundationForm_ConsumptionInfoRequestForm_accept_authority_1").parent('label').click(function () {
        //    console.log("1");
        //            $("#ApplicantName").hide();
        //
        //});
        //$("#FoundationForm_ConsumptionInfoRequestForm_accept_authority_2").parent('label').click(function () {
        //            $("#ApplicantName").show();
        //    console.log("2");
        //
        //
        //});

    });
}

String.prototype.toTitleCase = function () {
    var smallWords = /^(a|an|and|as|at|but|by|en|for|if|in|nor|of|on|or|per|the|to|vs?\.?|via)$/i;

    return this.replace(/[A-Za-z0-9\u00C0-\u00FF]+[^\s-]*/g, function (match, index, title) {
        if (index > 0 && index + match.length !== title.length &&
                match.search(smallWords) > -1 && title.charAt(index - 2) !== ":" &&
                (title.charAt(index + match.length) !== '-' || title.charAt(index - 1) === '-') &&
                title.charAt(index - 1).search(/[^\s-]/) < 0) {
            return match.toLowerCase();
        }

        if (match.substr(1).search(/[A-Z]|\../) > -1) {
            return match;
        }

        return match.charAt(0).toUpperCase() + match.substr(1);
    });
};


/*!
 * Cross-Browser Split 1.1.1
 * Copyright 2007-2012 Steven Levithan <stevenlevithan.com>
 * Available under the MIT License
 * ECMAScript compliant, uniform cross-browser split method
 */


function logf(msg) {
    if (typeof window.console != 'undefined' && typeof window.console.log == 'function') {
        console.log(msg);
    }
}

// Firefox table shim according to http://stackoverflow.com/questions/5148041/does-firefox-support-position-relative-on-table-elements
// And

$(function () {


});

//This prototype is provided by the Mozilla foundation and
//is distributed under the MIT license.
//http://www.ibiblio.org/pub/Linux/LICENSES/mit.license

//if (!Array.prototype.indexOf)
//{
//  Array.prototype.indexOf = function(elt /*, from*/)
//  {
//    var len = this.length;
//
//    var from = Number(arguments[1]) || 0;
//    from = (from < 0)
//         ? Math.ceil(from)
//         : Math.floor(from);
//    if (from < 0)
//      from += len;
//
//    for (; from < len; from++)
//    {
//      if (from in this &&
//          this[from] === elt)
//        return from;
//    }
//    return -1;
//  };
//}

if (!String.prototype.trim) {
    String.prototype.trim = function () {
        return this.replace(/^\s+|\s+$/g, '');
    };
}


