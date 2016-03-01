/*
**************************************************************************************************************************
** CORAL Resources Module v. 1.0
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

//image preloader
(function($) {
  var cache = [];
  // Arguments are image paths relative to the current page.
  $.preLoadImages = function() {
    var args_len = arguments.length;
    for (var i = args_len; i--;) {
      var cacheImage = document.createElement('img');
      cacheImage.src = arguments[i];
      cache.push(cacheImage);
    }
  }
})(jQuery)


//Required for date picker
Date.firstDayOfWeek = 0;

//suggested: mm/dd/yyyy OR dd-mm-yyyy
Date.format = 'mm/dd/yyyy';


$(function(){
	$('.date-pick').datePicker({startDate:'01/01/1996'});
	



	$("#search_organization").autocomplete('ajax_processing.php?action=getOrganizationList', {
		minChars: 2,
		max: 20,
		mustMatch: true,
		width: 142,
		delay: 20,
		matchContains: false,
		formatItem: function(row) {
			return "<span style='font-size: 80%;'>" + row[0] + "</span>";
		},
		formatResult: function(row) {
			return row[0].replace(/(<.+?>)/gi, '');
		}

	 });


	 //once something has been selected, go directly to that page
	 $("#search_organization").result(function(event, data, formatted) {
	 	if (data[0] != null){
			replacedOrg = data[0].replace(/&/g, "&amp;");
			window.location  = 'orgDetail.php?organizationID=' + data[1] + '&search_organization=' + escape(data[0]);
		}
	 });


	 function log(event, data, formatted) {
		$("<li>").html( !data ? _("No match!") : _("Selected: ") + formatted).html("#result");

	 }

	//used for swapping the value on the search box
	 swapValues = [];
	 $(".swap_value").each(function(i){
		swapValues[i] = $(this).val();
		$(this).focus(function(){
		    if ($(this).val() == swapValues[i]) {
			$(this).val("");
		    }
		}).blur(function(){
		    if ($.trim($(this).val()) == "") {
			$(this).val(swapValues[i]);
		    }
		});
	 });


	 
	 //for swapping menu images
	$('.rollover').hover(function() {
		var currentImg = $(this).attr('src');
		$(this).attr('src', $(this).attr('hover'));
		$(this).attr('hover', currentImg);
		
		if ($(this).attr('id') == 'menu-last'){
			var endImg = $("#menu-end").attr('src');
			$('#menu-end').attr('src', $("#menu-end").attr('hover'));
			$('#menu-end').attr('hover', endImg);
		}
	    }, function() {
		var currentImg = $(this).attr('src');
		$(this).attr('src', $(this).attr('hover'));
		$(this).attr('hover', currentImg);
		
		if ($(this).attr('id') == 'menu-last'){
			var endImg = $("#menu-end").attr('src');
			$('#menu-end').attr('src', $("#menu-end").attr('hover'));
			$('#menu-end').attr('hover', endImg);
		}
		
	 });


	 //for the Change Module drop down
	 $('.coraldropdown').each(function () {
		$(this).parent().eq(0).hover(function () {
			$('.coraldropdown:eq(0)', this).slideDown(100);
			}, function () {
			$('.coraldropdown:eq(0)', this).slideUp(100);
		});
	 });	


	
});





// 1 visible, 0 hidden
function toggleDivState(divID, intDisplay) {
	if(document.layers){
	   document.layers[divID].display = intDisplay ? "block" : "none";
	}
	else if(document.getElementById){
		var obj = document.getElementById(divID);
		obj.style.display = intDisplay ? "block" : "none";
	}
	else if(document.all){
		document.all[divID].style.display = intDisplay ? "block" : "none";
	}
}

//if (typeof expressionTypeId == 'undefined') expressionTypeId = '';


//This prototype is provided by the Mozilla foundation and
//is distributed under the MIT license.
//http://www.ibiblio.org/pub/Linux/LICENSES/mit.license

if (!Array.prototype.indexOf)
{
  Array.prototype.indexOf = function(elt /*, from*/)
  {
    var len = this.length;

    var from = Number(arguments[1]) || 0;
    from = (from < 0)
         ? Math.ceil(from)
         : Math.floor(from);
    if (from < 0)
      from += len;

    for (; from < len; from++)
    {
      if (from in this &&
          this[from] === elt)
        return from;
    }
    return -1;
  };
}

function getCheckboxValue(field){
	if ($('#' + field + ':checked').attr('checked')) {
		return 1;
	}else{
		return 0;
	}
}

function validateEmail(email) {
	console.log("Validating email.");
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

function validateRequired(field,alerttxt){
	fieldValue=$("#" + field).val();

	  if (fieldValue==null || fieldValue=="") {
	    $("#span_error_" + field).html(alerttxt);
	    $("#" + field).focus();
	    return false;
	  } else {
	    $("#span_error_" + field).html('');
	    return true;
	  }
}



function validateRadioChecked(field,alerttxt,defaulttxt){
	fieldValue=$('input:radio[name=' + field + ']:checked').val()

	  if (fieldValue==null || fieldValue=="") {
	    $("#span_error_" + field).html(alerttxt);
	    $("#" + field).focus();
	    return false;
	  } else {
	    $("#span_error_" + field).html(defaulttxt);
	    return true;
	  }
}



function validateDate(field,alerttxt) {
     $("#span_error_" + field).html('');
     sDate =$("#" + field).val(); 
   
     if (sDate){
   
	   var re = /^\d{1,2}\/\d{1,2}\/\d{4}$/
	   if (re.test(sDate)) {
	      var dArr = sDate.split("/");
	      var d = new Date(sDate);

	      if (!(d.getMonth() + 1 == dArr[0] && d.getDate() == dArr[1] && d.getFullYear() == dArr[2])) {
		$("#span_error_" + field).html(alerttxt);
	       $("#" + field).focus();   
		return false;
	      }else{
		return true;
	      }

	   } else {
	      $("#span_error_" + field).html(alerttxt);
	      $("#" + field).focus();   
	      return false;
	   }
     }
     
     return true;
}



function isAmount(pAmount){

	pAmount = pAmount.replace('$','');
	pAmount = pAmount.replace(',','');
	
	if (isNaN(pAmount)){
		return false;
	}else{
		return true;
	}


}



function postwith (to,p) {
  var myForm = document.createElement("form");
  myForm.method="post" ;
  myForm.action = to ;
  for (var k in p) {
    var myInput = document.createElement("input") ;
    myInput.setAttribute("name", k) ;
    myInput.setAttribute("value", p[k]);
    myForm.appendChild(myInput) ;
  }
  document.body.appendChild(myForm) ;
  myForm.submit() ;
  document.body.removeChild(myForm) ;
}

