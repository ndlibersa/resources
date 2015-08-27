/*
 **************************************************************************************************************************
 ** CORAL Resources Module v. 1.2
 **
 ** Copyright (c) 2010 University of Notre Dame
 **
 ** This file is part of CORAL.
 **
 ** CORAL is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 **
 ** CORAL is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 **
 ** You should have received a copy of the GNU General Public License along with CORAL.  If not, see <http://www.gnu.org/licenses/>.
 **
 **************************************************************************************************************************
 */

$(document).ready(function () {

      $(".submitResource").click(function () {
            submitResource($(this).attr("id"));
      });

      $("#search").click(function () {
            searchGokb($('#titleText').val(), $('#ISSNText').val(), $('#providerText').val());
      });
      
      $(".loupe").click(function(){
            searchGokb($('#titleText').val(), $('#ISSNText').val(), $('#providerText').val());
      });

      //do submit if enter is hit
      $('#titleText').keyup(function (e) {
            if (e.keyCode == 13) {
                  submitResource('save');
            }
      });



      //do submit if enter is hit
      $('#providerText').keyup(function (e) {
            if (e.keyCode == 13) {
                  submitResource('save');
            }
      });



      //do submit if enter is hit
      $('#resourceURL').keyup(function (e) {
            if (e.keyCode == 13) {
                  submitResource('save');
            }
      });

      //do submit if enter is hit
      $('#resourceAltURL').keyup(function (e) {
            if (e.keyCode == 13) {
                  submitResource('save');
            }
      });

      //do submit if enter is hit
      $('#resourceFormatID').keyup(function (e) {
            if (e.keyCode == 13) {
                  submitResource('save');
            }
      });



      //do submit if enter is hit
      $('#resourceTypeID').keyup(function (e) {
            if (e.keyCode == 13) {
                  submitResource('save');
            }
      });


      //do submit if enter is hit
      $('#acquisitionTypeID').keyup(function (e) {
            if (e.keyCode == 13) {
                  submitResource('save');
            }
      });





      //check this name/title to make sure it isn't already being used
      $("#titleText").keyup(function () {
            $.ajax({
                  type: "GET",
                  url: "ajax_processing.php",
                  cache: false,
                  async: true,
                  data: "action=getExistingTitle&name=" + $("#titleText").val(),
                  success: function (exists) {
                        if (exists == "0") {
                              $("#span_error_titleText").html("");
                        } else {
                              $("#span_error_titleText").html("<br />Warning: this name already exists.");
                        }
                  }
            });


      });



      $("#providerText").autocomplete('ajax_processing.php?action=getOrganizationList', {
            minChars: 2,
            max: 20,
            mustMatch: false,
            width: 223,
            delay: 10,
            matchContains: true,
            formatItem: function (row) {
                  return "<span style='font-size: 80%;'>" + row[0] + "</span>";
            },
            formatResult: function (row) {
                  return row[0].replace(/(<.+?>)/gi, '');
            }

      });


      //once something has been selected, change the hidden input value
      $("#providerText").result(function (event, data, formatted) {
            $('#organizationID').val(data[1]);
      });




      //the following are all to change the look of the inputs when they're clicked
      $('.changeDefaultWhite').live('focus', function (e) {
            if (this.value == this.defaultValue) {
                  this.value = '';
            }
      });

      $('.changeDefaultWhite').live('blur', function () {
            if (this.value == '') {
                  this.value = this.defaultValue;
            }
      });


      $('.changeInput').addClass("idleField");

      $('.changeInput').live('focus', function () {


            $(this).removeClass("idleField").addClass("focusField");

            if (this.value != this.defaultValue) {
                  this.select();
            }

      });


      $('.changeInput').live('blur', function () {
            $(this).removeClass("focusField").addClass("idleField");
      });


      $('.changeAutocomplete').live('focus', function () {
            if (this.value == this.defaultValue) {
                  this.value = '';
            }

      });


      $('.changeAutocomplete').live('blur', function () {
            if (this.value == '') {
                  this.value = this.defaultValue;
            }
      });




      $('select').addClass("idleField");
      $('select').live('focus', function () {
            $(this).removeClass("idleField").addClass("focusField");

      });

      $('select').live('blur', function () {
            $(this).removeClass("focusField").addClass("idleField");
      });



      $('textarea').addClass("idleField");
      $('textarea').focus(function () {
            $(this).removeClass("idleField").addClass("focusField");
      });

      $('textarea').blur(function () {
            $(this).removeClass("focusField").addClass("idleField");
      });


      $(".remove").live('click', function () {
            $(this).parent().parent().parent().fadeTo(400, 0, function () {
                  $(this).remove();
            });
            return false;
      });



});





function validateNewResource() {
      myReturn = 0;

      var title = $('#titleText').val();
      var fmtID = $('#resourceFormatID').val();
      var typeID = $('#resourceTypeID').val();

      //also perform same checks on the current record in case add button wasn't clicked
      if (title == '' || title == null) {
            $('#span_error_titleText').html('A title must be entered to continue.');
            myReturn = 1;
      }

      if (fmtID == '' || fmtID == null) {
            $('#span_error_resourceFormatID').html('The resource format is required.');
            myReturn = 1;
      }

      if (typeID == '' || typeID == null) {
            $('#span_error_resourceTypeID').html('The resource type is required.');
            myReturn = 1;
      }

      if (myReturn == 1) {
            return false;
      } else {
            return true;
      }
}





function submitResource(status) {

      orderTypeList = '';
      $(".orderTypeID").each(function (id) {
            orderTypeList += $(this).val() + ":::";
      });

      fundNameList = '';
      $(".fundName").each(function (id) {
            fundNameList += $(this).val() + ":::";
      });


      paymentAmountList = '';
      $(".paymentAmount").each(function (id) {
            paymentAmountList += $(this).val() + ":::";
      });


      currencyCodeList = '';
      $(".currencyCode").each(function (id) {
            currencyCodeList += $(this).val() + ":::";
      });



      if (validateNewResource() === true) {
            $('.submitResource').attr("disabled", "disabled");
            $.ajax({
                  type: "POST",
                  url: "ajax_processing.php?action=submitNewResource",
                  cache: false,
                  data: {resourceID: $("#editResourceID").val(), resourceTypeID: $("input:radio[name='resourceTypeID']:checked").val(), resourceFormatID: $("input:radio[name='resourceFormatID']:checked").val(), acquisitionTypeID: $("input:radio[name='acquisitionTypeID']:checked").val(), titleText: $("#titleText").val(), descriptionText: $("#descriptionText").val(), providerText: $("#providerText").val(), organizationID: $("#organizationID").val(), resourceURL: $("#resourceURL").val(), resourceAltURL: $("#resourceAltURL").val(), noteText: $("#noteText").val(), orderTypes: orderTypeList, fundNames: fundNameList, paymentAmounts: paymentAmountList, currencyCodes: currencyCodeList, resourceStatus: status},
                  success: function (resourceID) {
                        //go to the new resource page if this was submitted
                        if (status == 'progress') {
                              window.parent.location = ("resource.php?ref=new&resourceID=" + resourceID);
                              tb_remove();
                              return false;
                              //otherwise go to queue
                        } else {
                              window.parent.location = ("queue.php?ref=new");
                              tb_remove();
                              return false;
                        }

                  }


            });

      }

}


function searchGokb(s_name, s_issn, s_pub) {
      if (validateSearchFields() === true) {
            $.ajax({
                  type: "POST",
                  url: "ajax_htmldata.php?action=getKBSearchResults&height=503&width=775&resourceID=&modal=true",
                  cache: false,
                  data: {name: s_name, issn: s_issn, publisher: s_pub, type: 0},
                  success: function (res) {
                        document.getElementById("TB_ajaxContent").innerHTML = "";
                        $('#TB_ajaxContent').append(res);

                  }
            });
            window.history.pushState(null, null, null);
            console.debug("pushState NULL");
            window.history.pushState({funcName: 'searchGokbBack', param: [s_name, s_pub]}, 'test', null);
            console.debug("pushState searchGokbBack(" + s_name + "," + s_issn + "," + s_pub + ")");
      }
      else {
            $("#span_error_search").html("At least one search field is required to search");
      }
}

function validateSearchFields() {
      var name = $('#titleText').val();
      var publisher = $('#providerText').val();
      var issn = $('#ISSNText').val();

      if (((name != null) && (name != '')) || ((publisher != '') && (publisher != null)) || ((issn != null) && (issn != ''))) {
            return true;
      } else {
            return false;
      }
}

//kill all binds done by jquery live
function kill() {

      $('.remove').die('click');
      $('.changeAutocomplete').die('blur');
      $('.changeAutocomplete').die('focus');
      $('.changeDefault').die('blur');
      $('.changeDefault').die('focus');
      $('.changeInput').die('blur');
      $('.changeInput').die('focus');
      $('.select').die('blur');
      $('.select').die('focus');

}
