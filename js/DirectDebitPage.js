var formExists = ($('#FoundationForm_DirectDebitForm').length == 0) ? false : true;
//console.log(formExists);
if (formExists) {  // submitted form missing this element
    var parsleyFormInstance = $('#FoundationForm_DirectDebitForm').parsley({
        errorsWrapper: '<div class=\"parsley-errors-list\"></div>',
        errorTemplate: '<div></div>',
        trigger: 'change'
    });

    $(document).ready(function () {
        var setByBranch = false; // for resetting bank number

        var widget = new AddressFinder.Widget(document.getElementById('FoundationForm_DirectDebitForm_Street'), "V3YULPXNHT49F8BCE7DG",
                {
                    manual_style: true,
                    empty_content: "",
                    empty_class: "af_empty",
                    show_addresses: true,
                    show_locations: false,
                    address_params: {
                        street: 1,
                        city: 1,
                        region: 1
                    }
                });

        widget.on("result:select", function (value, data) {
            //console.log(data);
            var city = data.city;
            if (data.mailtown && data.city != data.mailtown) {
                city = data.mailtown;
            }
            var RD = (data.rd_number) ? ' ' + data.rd_number : '';
            var Street = (data.postal_line_1 || '' ) + ((data.postal_line_2) ? ', ' + data.postal_line_2 : '');

            $("#FoundationForm_DirectDebitForm_Street").val(Street || '').trigger('change');
            $("#FoundationForm_DirectDebitForm_Suburb").val(data.suburb || '').trigger('change');
            $("#FoundationForm_DirectDebitForm_City").val(city + RD || '').trigger('change');
            $("#FoundationForm_DirectDebitForm_Postcode").val(data.postcode || '').trigger('change');


            $("#Form_SignUpForm_postal_suburb").val(data.postal_suburb || '');

            //   $("#Form_SignUpForm_region").val(data.region || '');


        });

        widget.on("results:empty", function (value, data) {
            //console.log('no address');
        });


        // Global request boolean, used to check if an ajax request is currently underway
        var isRequesting = false;

        var currentUrl = location.href;
        if (currentUrl.indexOf('?') != -1) {
            currentUrl = currentUrl.substr(0, currentUrl.indexOf('?'));
        }
        if (currentUrl.substr(-1) == '/') {
            currentUrl = currentUrl.substr(0, currentUrl.length - 1);
        }
        //console.log(currentUrl);
        //show branch info on input maxlength
        var bankURL = currentUrl + '/getBankBranch';
        $parsleyFormBankBranchNum = $("#FoundationForm_DirectDebitForm_BankBranchNum").parsley();
        $parsleyFormBankCodeNum = $("#FoundationForm_DirectDebitForm_BankCodeNum").parsley();

        $parsleyFormBankBranchNum.subscribe('parsley:field:error', function (field) {
            window.ParsleyUI.removeError($parsleyFormBankCodeNum, 'BankCodeNum');
            window.ParsleyUI.removeError($parsleyFormBankBranchNum, 'BankBranchNum');
        });

        $parsleyFormBankBranchNum.subscribe('parsley:field:success', function (field) {
            var formfield = $("#FoundationForm_DirectDebitForm_BankBranchNum")[0];
            window.ParsleyUI.removeError($parsleyFormBankCodeNum, 'BankCodeNum');
            window.ParsleyUI.removeError($parsleyFormBankBranchNum, 'BankBranchNum');
      // this is the server side validation being removed
            $("#DummyBankServerValidation .parsley-errors-list").removeClass('filled');

            if (formfield.value.length == formfield.getAttribute('maxlength')) {

                $.getJSON(bankURL, {'BankBranchNum': formfield.value}, function (data) {
                    if (data) {
                        //console.log(data);
                        if ($("#FoundationForm_DirectDebitForm_BankCodeNum").val() && $("#FoundationForm_DirectDebitForm_BankCodeNum").val() != data.Bank_Number) {
                            window.ParsleyUI.addError($parsleyFormBankBranchNum, 'BankBranchNum',
                                    "Your branch number and bank number have been altered to match."
                                  //  "Your bank code has been changed to match, please check against your records"
                            );

                            //alert("Your branch number and bank number would appear to be inconsistent.\n" +
                            //"Your bank code will be changed.\n" +
                            //"Please check against your records");

                            //$("#FoundationForm_DirectDebitForm_BankCodeNum").val(data.Bank_Number || '').attr('readonly', 'readonly').parsley().validate();
                            $("#FoundationForm_DirectDebitForm_BankCodeNum").val(data.Bank_Number || '').parsley().validate();

                            setByBranch = data.Bank_Number;
                        }
                        if (!$("#FoundationForm_DirectDebitForm_BankCodeNum").val()) {
                            $("#FoundationForm_DirectDebitForm_BankCodeNum").val(data.Bank_Number || '').parsley().validate();
                            setByBranch = data.Bank_Number;
                        }

                        $("#FoundationForm_DirectDebitForm_BankName").val(data.Bank_Name || '').trigger('change').parsley().validate();
                        var branchname;
                        if (data.Physical_Address3) branchname = data.Physical_Address3;
                        else if (data.Physical_Address2) branchname = data.Physical_Address2
                        else branchname = data.City;

                        $("#FoundationForm_DirectDebitForm_BankBranchName").val(branchname).parsley().validate();
                        var address = (data.Physical_Address1 || '')
                                + ((data.Physical_Address2) ? ', ' + data.Physical_Address2 : '')
                                + ((data.City) ? ', ' + data.City : '');
                        +((data.Post_CodeCity) ? ', ' + data.Post_Code : '');
                        $("#FoundationForm_DirectDebitForm_BankAddress").val(address).parsley().validate(); //.trigger('change');
                    }else{
                        window.ParsleyUI.removeError($parsleyFormBankBranchNum, 'BankBranchNum');
                        //console.log('no bank number');
                        // VW style fix: Crazy but multiple key hits by silly user can overload parsley and produce a stack of ParsleyUI to remove and it doesn't look good
                        window.ParsleyUI.addError($parsleyFormBankBranchNum, 'BankBranchNum', "Your Branch Number does not appear to be a valid one");
                    }
                }).fail(function () {
                    window.ParsleyUI.removeError($parsleyFormBankBranchNum, 'BankBranchNum');
                    //console.log('no bank number');
                // VW style fix: Crazy but multiple key hits by silly user can overload parsley and produce a stack of ParsleyUI to remove and it doesn't look good
                    window.ParsleyUI.addError($parsleyFormBankBranchNum, 'BankBranchNum', "There was an error getting Branch Details");
                });

            }


        });


        //show bank info on input maxlength
        var bankURL2 = currentUrl + '/getBank';

        $parsleyFormBankCodeNum.subscribe('parsley:field:error', function (field) {
            window.ParsleyUI.removeError($parsleyFormBankCodeNum, 'BankCodeNum');
            window.ParsleyUI.removeError($parsleyFormBankBranchNum, 'BankBranchNum');
        });


        $parsleyFormBankCodeNum.subscribe('parsley:field:success', function (field) {
            window.ParsleyUI.removeError($parsleyFormBankCodeNum, 'BankCodeNum');
            window.ParsleyUI.removeError($parsleyFormBankBranchNum, 'BankBranchNum');
            var formfield = $("#FoundationForm_DirectDebitForm_BankCodeNum")[0];

            if (formfield.value.length == formfield.getAttribute('maxlength')) {
//            console.log(setByBranch);
//            console.log($("#FoundationForm_DirectDebitForm_BankCodeNum").val());

                if ((setByBranch) && ($("#FoundationForm_DirectDebitForm_BankCodeNum").val() != setByBranch)) {
                    window.ParsleyUI.addError(parsleyFormInstance.fields[11], 'BankBranchNum',
                            "Your branch number and bank number would appear to be inconsistent."
                    );
                    //alert("The Bank Code you have enter would appear to be inconsistent with the Branch Number you have already entered\n" +
                    //"Your Bank Details will be reset.\n" +
                    //"Please check against your records");
                    $("#FoundationForm_DirectDebitForm_BankName").val('').trigger('change').parsley().validate();//.trigger('change');
                    $("#FoundationForm_DirectDebitForm_BankBranchName").val('').parsley().validate();//.trigger('change');
                    $("#FoundationForm_DirectDebitForm_BankAddress").val('').parsley().validate();//.trigger('change');
                    setByBranch = false;
                }
                else {

                    $.getJSON(bankURL2, {'BankCodeNum': formfield.value}, function (data) {
                        if (data) {
                            $("#FoundationForm_DirectDebitForm_BankName").val(data.Bank_Name || '').trigger('change').parsley().validate();//.trigger('change');
                        }
                        else {
                            $("#FoundationForm_DirectDebitForm_BankName").val('').parsley().validate();//.trigger('change');
                        }
                    })
                            .fail(function () {
                                $("#FoundationForm_DirectDebitForm_BankName").val('').parsley().validate();//.trigger('change');
                                window.ParsleyUI.addError($parsleyFormBankCodeNum, 'BankCodeNum', "Your Bank Number does not appear to be a valid one");
                            });
                }
            }
        });
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


