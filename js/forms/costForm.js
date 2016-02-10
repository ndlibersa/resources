/*
**************************************************************************************************************************
** CORAL Resources Module v. 1.0
**
** Copyright (c) 2010-2014 University of Notre Dame
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

$(function(){
	$('.date-pick').datePicker({startDate:'01/01/1996'});

	//bind all of the inputs

	 $("#submitCost").click(function () {
		submitCostForm();
	 });


	//the following are all to change the look of the inputs when they're clicked
	$('.changeDefault').live('focus', function(e) {
		if (this.value == this.defaultValue){
			this.value = '';
		}
	});

	 $('.changeDefault').live('blur', function() {
		if(this.value == ''){
			this.value = this.defaultValue;
		}		
	 });

	
    	$('.changeInput').addClass("idleField");
    	
	$('.changeInput').live('focus', function() {


		$(this).removeClass("idleField").addClass("focusField");

		if(this.value != this.defaultValue){
			this.select();
		}

	 });


	 $('.changeInput').live('blur', function() {
		$(this).removeClass("focusField").addClass("idleField");
	 });




	$('select').addClass("idleField");
	$('select').live('focus', function() {
		$(this).removeClass("idleField").addClass("focusField");

	});

	$('select').live('blur', function() {
		$(this).removeClass("focusField").addClass("idleField");
	});



	$('textarea').addClass("idleField");
	$('textarea').focus(function() {
		$(this).removeClass("idleField").addClass("focusField");
	});
	    
	$('textarea').blur(function() {
		$(this).removeClass("focusField").addClass("idleField");
	});




	$(".remove").live('click', function () {
	    $(this).parent().parent().parent().fadeTo(400, 0, function () { 
		$(this).remove();
	    });
	    return false;
	});



	$(".addPayment").live('click', function () {

		var y         = $('.newPaymentTable').children().children().children().children('.year').val();
		var ssd       = $('.newPaymentTable').children().children().children().children('.susbcriptionStartDate').val();
		var sed       = $('.newPaymentTable').children().children().children().children('.susbcriptionEndDate').val();
		var fName     = $('.newPaymentTable').children().children().children().children('.fundName').val();
		var typeID    = $('.newPaymentTable').children().children().children().children('.orderTypeID').val();
		var detailsID = $('.newPaymentTable').children().children().children().children('.costDetailsID').val();
		var pAmount   = $('.newPaymentTable').children().children().children().children('.paymentAmount').val();
		var cNote     = $('.newPaymentTable').children().children().children().children('.costNote').val();
						
		if ((pAmount == '' || pAmount == null) && (fName == '' || fName == null))
		{
			$('#div_errorPayment').html('Error - Either amount or fund is required');
			return false;		
		}
		else if((typeID == '') || (typeID == null))
		{
			$('#div_errorPayment').html('Error - order type is a required field');
			return false;
		}
		else if ((pAmount != '') && (pAmount != null) && (isAmount(pAmount) === false))
		{
			$('#div_errorPayment').html('Error - price is not numeric');
			return false;		
		}
		else
		{

			//we're going to strip out the $ of the payment amount
			pAmount = pAmount.replace('$','');
		
			$('#div_errorPayment').html('');
			
			var newPaymentTR = $('.newPaymentTR')
			var duplicateTR = newPaymentTR.clone(); //copy the payment being added
			var selectedOptions=newPaymentTR.find('select'); //get selected options
			duplicateTR.find('select').map(function(index, item) {
				item.value = selectedOptions[index].value;
			});
			duplicateTR.removeClass('newPaymentTR'); //remove newPaymentTR class from duplicate
			junk=duplicateTR;
			//console.log(duplicateTR.getAttribute('style'));
			//duplicateTR.setAttribute('style','');

			duplicateTR.find('.dp-choose-date').remove(); //remove date pickers from clone
			duplicateTR.find('.date-pick').datePicker({startDate:'01/01/1996'}); //add new date pickers to clone
			replaceImage=duplicateTR.children().last().find('img');
			replaceImage.attr({
				'src': 'images/cross.gif',
				'alt': 'remove this payment',
				'title': 'remove this payment'
			});
			replaceImage.removeClass('addPayment');
			replaceImage.addClass('remove');
			console.log(replaceImage);

			duplicateTR.appendTo('.paymentTable');
			/*

			$('.newPaymentTR').children().children('.paymentAmount').val(pAmount);
			$('.paymentTypeID').addClass('changeSelect idleField').css("background-color","");
			//$('.paymentName').addClass('changeInput idleField');

			
			$('.addPayment').removeClass('addPayment');
			$('.newPaymentTR').removeClass('newPaymentTR');

			*/
			//next put the original clone back, we just need to reset the values
			$('.newPaymentTable').children().children().children().children('.year').val('');
			$('.newPaymentTable').children().children().children().children('.subscriptionStartDate').val('');
			$('.newPaymentTable').children().children().children().children('.subscriptionEndDate').val('');
			$('.newPaymentTable').children().children().children().children('.fundName').val('');
			$('.newPaymentTable').children().children().children().children('.paymentAmount').val('');
			$('.newPaymentTable').children().children().children().children('.orderTypeID').val('');
			$('.newPaymentTable').children().children().children().children('.costDetailsID').val('');
			$('.newPaymentTable').children().children().children().children('.costNote').val('');
			$('.newPaymentTable').children().children().children().children('.invoiceNum').val('');
			
			return true;
			
		}
	});


});



function submitCostForm(){
	
	//check if anything is on the add line
	var y          = $('.newPaymentTR').find('.year').val();
	var ssd        = $('.newPaymentTR').find('.subscriptionStartDate').val();
	var sed        = $('.newPaymentTR').find('.subscriptionEndDate').val();
	var fName      = $('.newPaymentTR').find('.fundID').val();
	var payment    = $('.newPaymentTR').find('.paymentAmount').val();
	var typeID     = $('.newPaymentTR').find('.orderTypeID').val();
	var detailsID  = $('.newPaymentTR').find('.costDetailsID').val();
	var pAmount    = $('.newPaymentTR').find('.paymentAmount').val();
	var cNote      = $('.newPaymentTR').find('.costNote').val();
	var invoiceNum = $('.newPaymentTR').find('.invoiceNum').val();

	if(y != '' || ssd != '' || sed != '' || fName != '' || payment != '' || typeID != '' || detailsID != '' || pAmount != '' || cNote != '' || invoiceNum != '')
	{
		if(confirm('There is unsaved information on the add line. To discard this information, click OK, otherwise click Cancel.')==false)
		{
			return;
		}
	}

	purchaseSitesList ='';
	$(".paymentTable").find(".check_purchaseSite:checked").each(function(id) {
	      purchaseSitesList += $(this).val() + ":::";
	}); 
	
	yearList ='';
	$(".paymentTable").find(".year").each(function(id) {
	      yearList += $(this).val() + ":::";
	}); 

	subStartList ='';
	$(".paymentTable").find(".subscriptionStartDate").each(function(id) {
	      subStartList += $(this).val() + ":::";
	}); 

	subEndList ='';
	$(".paymentTable").find(".subscriptionEndDate").each(function(id) {
	      subEndList += $(this).val() + ":::";
	}); 

	fundNameList ='';
	$(".paymentTable").find(".fundID").each(function(id) {
	      fundNameList += $(this).val() + ":::";
	}); 

	paymentAmountList ='';
	$(".paymentTable").find(".paymentAmount").each(function(id) {
	      paymentAmountList += $(this).val() + ":::";
	}); 

	currencyCodeList ='';
	$(".paymentTable").find(".currencyCode").each(function(id) {
	      currencyCodeList += $(this).val() + ":::";
	}); 
	
	orderTypeList ='';
	$(".paymentTable").find(".orderTypeID").each(function(id) {
	      orderTypeList += $(this).val() + ":::";
	}); 

	detailsList ='';
	$(".paymentTable").find(".costDetailsID").each(function(id) {
	      detailsList += $(this).val() + ":::";
	}); 

	costNoteList ='';
	$(".paymentTable").find(".costNote").each(function(id) {
	      costNoteList += $(this).val() + ":::";
	}); 

	invoiceList ='';
	$(".paymentTable").find(".invoiceNum").each(function(id) {
	      invoiceList += $(this).val() + ":::";
	}); 

	//if (validateForm() === true) {
		$('#submitCost').attr("disabled", "disabled"); 
		  $.ajax({
			 type:  "POST",
			 url:   "ajax_processing.php?action=submitCost",
			 cache: false,
			 data:  { resourceID: $("#editResourceID").val(),
                      years: yearList,
                      subStarts: subStartList,
                      subEnds: subEndList,
                      fundIDs: fundNameList,
                      paymentAmounts: paymentAmountList,
                      currencyCodes: currencyCodeList,
                      orderTypes: orderTypeList,
                      costDetails: detailsList,
                      costNotes: costNoteList,
                      invoices: invoiceList
                    },
			 success:   function(html) {
				if (html){
					$("#span_errors").html(html);
					$("#submitCost").removeAttr("disabled");
				}else{
					kill();
					window.parent.tb_remove();
					window.parent.updateAcquisitions();
					return false;
				}					

			 }


		 });
	 //}

}






 function validateForm (){
 	myReturn=0;

	var y = $('.newPaymentTable').children().children().children().children('.year').val();
	var fName = $('.newPaymentTable').children().children().children().children('.fundName').val();
	var typeID = $('.newPaymentTable').children().children().children().children('.orderTypeID').val();
	var detailsID = $('.newPaymentTable').children().children().children().children('.costDetailsID').val();
	var pAmount = $('.newPaymentTable').children().children().children().children('.paymentAmount').val();
	var cNote = $('.newPaymentTable').children().children().children().children('.costNote').val();

	//also perform same checks on the current record in case add button wasn't clicked
	if ((((pAmount == '') || (pAmount == null)) && ((fName == '') || (fName == null))) && ((pAmount != '') || (fName != ''))){
		$('#div_errorPayment').html('Error - Either price or fund is required');
		myReturn="1";		

	}

	if(((typeID == '') || (typeID == null)) && ((pAmount != '') || (fName != ''))){
		$('#div_errorPayment').html('Error - order type is a required field');
		myReturn="1";
	}

	
	if ((pAmount != '') && (pAmount != null) && (isAmount(pAmount) === false)){
		$('#div_errorPayment').html('Error - price is not numeric');
		myReturn="1";		
	}
 	
 	 
 	if (myReturn == "1"){
		return false; 	
 	}else{
 		return true;
 	}
}
 





//kill all binds done by jquery live
function kill(){

	$('.addPayment').die('click'); 
	$('.changeDefault').die('blur');
	$('.changeDefault').die('focus');
	$('.changeInput').die('blur');
	$('.changeInput').die('focus');
	$('.select').die('blur');
	$('.select').die('focus');
	$('.remove').die('click');

}
