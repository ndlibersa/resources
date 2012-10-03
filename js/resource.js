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

$(document).ready(function(){
 	updateProduct();
 	updateRightPanel();
 	updateAttachmentsNumber();



	$(".showProduct").click(function () {
	  $('.resource_tab_content').hide();
		$('#div_product').show();
		$('#div_fullRightPanel').show();
		updateProduct();	
		return false;
	});


	$(".showAcquisitions").click(function () {
	  $('.resource_tab_content').hide();
		$('#div_acquisitions').show();
		$('#div_fullRightPanel').show();
		updateAcquisitions();
		return false;
	});

	$(".showAccess").click(function () {
	  $('.resource_tab_content').hide();
		$('#div_access').show();
		$('#div_fullRightPanel').show();
		updateAccess();
		return false;
	 });

	$(".showContacts").click(function () {
	  $('.resource_tab_content').hide();
		$('#div_contacts').show();
		$('#div_fullRightPanel').show();
		updateContacts();
		updateArchivedContacts(0);
		return false;
	});

	$(".showAccounts").click(function () {
	  $('.resource_tab_content').hide();
		$('#div_product').hide();
		$('#div_acquisitions').hide();
		$('#div_access').hide();
		$('#div_contacts').hide();
		$('#div_accounts').show();
		$('#div_attachments').hide();
		$('#div_routing').hide();
		$('#div_fullRightPanel').show();
		updateAccounts();
		return false;
	});


	$(".showAttachments").click(function () {
	  $('.resource_tab_content').hide();
		$('#div_attachments').show();
		$('#div_fullRightPanel').show();
		updateAttachments();
		return false;
	});

	$(".showRouting").click(function () {
	  $('.resource_tab_content').hide();
		$('#div_routing').show();
		$('#div_fullRightPanel').hide();
		updateRouting();
		return false;
	});
	
	$(".showCataloging").click(function () {
	  $('.resource_tab_content').hide();
		$('#div_cataloging').show();
		$('#div_fullRightPanel').show();
		updateCataloging();
		return false;
	});
	 


	$(function(){
		$('.date-pick').datePicker({startDate:'01/01/1996'});
	});


	// empty the new message span in 10 seconds
	setTimeout("emptyNewMessage();",10000); 
 

 });
 

var showArchivedContacts = 0; 

function updateProduct(){

  $("#icon_product").html("<img src='images/littlecircle.gif'>");
  
  $.ajax({
	 type:       "GET",
	 url:        "ajax_htmldata.php",
	 cache:      false,
	 data:       "action=getProductDetails&resourceID=" + $("#resourceID").val(),
	 success:    function(html) {
		$(".div_mainContent").html(html);
		bind_removes();
		tb_reinit();
		$("#icon_product").html("<img src='images/butterflyfishicon.jpg'>");
	 }


  });

}



function updateAcquisitions(){
  $("#icon_acquisitions").html("<img src='images/littlecircle.gif'>");

  $.ajax({
	 type:       "GET",
	 url:        "ajax_htmldata.php",
	 cache:      false,
	 data:       "action=getAcquisitionsDetails&resourceID=" + $("#resourceID").val(),
	 success:    function(html) {
		$(".div_mainContent").html(html);
		bind_removes();
		tb_reinit();
		$("#icon_acquisitions").html("<img src='images/acquisitions.gif'>");
	 }


  });

}


function updateAccess(){
  $("#icon_access").html("<img src='images/littlecircle.gif'>");

  $.ajax({
	 type:       "GET",
	 url:        "ajax_htmldata.php",
	 cache:      false,
	 data:       "action=getAccessDetails&resourceID=" + $("#resourceID").val(),
	 success:    function(html) {
		$(".div_mainContent").html(html);
		bind_removes();
		tb_reinit();
		$("#icon_access").html("<img src='images/key.gif'>");
	 }


  });

}




function updateContacts(){
  $("#icon_contacts").html("<img src='images/littlecircle.gif'>");
  
  $.ajax({
	 type:       "GET",
	 url:        "ajax_htmldata.php",
	 cache:      false,
	 data:       "action=getContactDetails&resourceID=" + $("#resourceID").val(),
	 success:    function(html) {
		$(".div_mainContent").html(html);
		bind_removes();
		tb_reinit();
		$("#icon_contacts").html("<img src='images/contacts.gif'>");
	 }


  });

}


 
function updateArchivedContacts(showArchivedPassed){
  if (typeof(showArchivedPassed) != 'undefined'){
	showArchivedContacts = showArchivedPassed;
  }

  
  $("#div_archivedContactDetails").append("<img src='images/circle.gif'>  Refreshing Contents...");
  $.ajax({
	 type:       "GET",
	 url:        "ajax_htmldata.php",
	 cache:      false,
	 data:       "action=getContactDetails&resourceID=" + $("#resourceID").val() + "&archiveInd=1&showArchivesInd=" + showArchivedContacts,
	 success:    function(html) {
		$("#div_archivedContactDetails").html(html);
		bind_removes();
		tb_reinit();
	 }


  });

}


function updateAccounts(){
  $("#icon_accounts").html("<img src='images/littlecircle.gif'>");
  $.ajax({
	 type:       "GET",
	 url:        "ajax_htmldata.php",
	 cache:      false,
	 data:       "action=getAccountDetails&resourceID=" + $("#resourceID").val(),
	 success:    function(html) {
		$(".div_mainContent").html(html);
		bind_removes();
		tb_reinit();
		$("#icon_accounts").html("<img src='images/lock.gif'>");
	 }


  });

}


function updateAttachments(){
  $("#icon_attachments").html("<img src='images/littlecircle.gif'>");
  $.ajax({
	 type:       "GET",
	 url:        "ajax_htmldata.php",
	 cache:      false,
	 data:       "action=getAttachmentDetails&resourceID=" + $("#resourceID").val(),
	 success:    function(html) {
		$(".div_mainContent").html(html);
		bind_removes();
		tb_reinit();
		$("#icon_attachments").html("<img src='images/attachment.gif'>");
	 }


  });

}


function updateAttachmentsNumber(){
  $.ajax({
	 type:       "GET",
	 url:        "ajax_htmldata.php",
	 cache:      false,
	 data:       "action=getAttachmentsNumber&resourceID=" + $("#resourceID").val(),
	 success:    function(remaining) {
		if (remaining == "1"){
			$(".span_AttachmentNumber").html("(" + remaining + " record)");
		}else{
			$(".span_AttachmentNumber").html("(" + remaining + " records)");
		}
	 }
 });
}
 

function updateRouting(){
  $("#icon_routing").html("<img src='images/littlecircle.gif'>");
  $.ajax({
	 type:       "GET",
	 url:        "ajax_htmldata.php",
	 cache:      false,
	 data:       "action=getRoutingDetails&resourceID=" + $("#resourceID").val(),
	 success:    function(html) {
		$(".div_mainContent").html(html);
		tb_reinit();
		bind_routing();
		$("#icon_routing").html("<img src='images/routing.gif'>");
	 }


  });

} 

function updateCataloging(){
  $("#icon_accounts").html("<img src='images/littlecircle.gif'>");
  $.ajax({
	 type:       "GET",
	 url:        "resources/cataloging.php",
	 cache:      false,
	 data:       "resourceID=" + $("#resourceID").val(),
	 success:    function(html) {
		$(".div_mainContent").html(html);
		bind_removes();
		tb_reinit();
		$("#icon_cataloging").html("<img src='images/cataloging.gif'>");
	 }

  });

}


function updateRightPanel(){
  $("#div_rightPanel").append("<img src='images/circle.gif'>  Refreshing Contents...");
  $.ajax({
	 type:       "GET",
	 url:        "ajax_htmldata.php",
	 cache:      false,
	 data:       "action=getRightPanel&resourceID=" + $("#resourceID").val(),
	 success:    function(html) {
		$("#div_rightPanel").html(html + "&nbsp;");
		
	 }


  });

} 




function updateTitle(){
  $.ajax({
	 type:       "GET",
	 url:        "ajax_htmldata.php",
	 cache:      false,
	 data:       "action=getTitle&resourceID=" + $("#resourceID").val(),
	 success:    function(html) {
		$("#span_resourceName").html(html);
	 }


  });

} 








function bind_removes(){



   $(".removeContact").unbind('click').click(function () {
	  if (confirm("Do you really want to delete this contact?") == true) {
		  $.ajax({
			 type:       "GET",
			 url:        "ajax_processing.php",
			 cache:      false,
			 data:       "action=deleteInstance&class=Contact&id=" + $(this).attr("id"),
			 success:    function(html) {
				updateContacts();
 				updateArchivedContacts();
			 }


		 });
	  }

   });

   $(".removeAccount").unbind('click').click(function () {
	  if (confirm("Do you really want to delete this account?") == true) {
		  $.ajax({
			 type:       "GET",
			 url:        "ajax_processing.php",
			 cache:      false,
			 data:       "action=deleteInstance&class=ExternalLogin&id=" + $(this).attr("id"),
			 success:    function(html) {
				updateAccounts();
			 }


		 });
	  }

   });


   $(".removeAttachment").unbind('click').click(function () {
	  if (confirm("Do you really want to delete this attachment?") == true) {
		  $.ajax({
			 type:       "GET",
			 url:        "ajax_processing.php",
			 cache:      false,
			 data:       "action=deleteInstance&class=Attachment&id=" + $(this).attr("id"),
			 success:    function(html) {
				updateAttachments();
			 }


		 });
	  }

   });




   $(".removeResource").unbind('click').click(function () {
	  if (confirm("Do you really want to delete this resource?") == true) {
		  $.ajax({
			 type:       "GET",
			 url:        "ajax_processing.php",
			 cache:      false,
			 data:       "action=deleteResource&resourceID=" + $(this).attr("id"),
			 success:    function(html) { 
				 //post return message to index
				postwith('index.php',{message:html});
			 }



		 });
	  }			
   });
   
   
   $(".removeResourceSubjectRelationship").unbind('click').click(function () {

   	  tabName = $(this).attr("tab");
	  
	  if (confirm("Do you really want to remove this Subject?") == true) {
		  $.ajax({
			 type:       "GET",
			 url:        "ajax_processing.php",
			 cache:      false,
			 data:       "action=removeResourceSubjectRelationship&generalDetailSubjectID=" + $(this).attr("generalDetailSubjectID") + "&resourceID=" + $(this).attr("resourceID"),
			 success:    function(html) {
				eval("update" + tabName + "();");
			 }

		 });
	  }			
   });   
   
   $(".removeNote").unbind('click').click(function () {
   
   	  tabName = $(this).attr("tab");

	  if (confirm("Do you really want to delete this note?") == true) {
		  $.ajax({
			 type:       "GET",
			 url:        "ajax_processing.php",
			 cache:      false,
			 data:       "action=deleteResourceNote&resourceNoteID=" + $(this).attr("id"),
			 success:    function(html) {
				eval("update" + tabName + "();");
			 }


		 });
	  }

   });



}



function bind_routing(){


   
   $(".markComplete").unbind('click').click(function () {
	  $.ajax({
		 type:       "GET",
		 url:        "ajax_processing.php",
		 cache:      false,
		 data:       "action=markComplete&resourceStepID=" + $(this).attr("id"),
		 success:    function(html) {
			updateRouting();
		 }


	 });
   });



   $(".restartWorkflow").unbind('click').click(function () {
	  if (confirm("Warning!  You are about to remove any steps that have been started and completed.  Are you sure you wish to continue?") == true) {
		  $.ajax({
			 type:       "GET",
			 url:        "ajax_processing.php",
			 cache:      false,
			 data:       "action=restartWorkflow&resourceID=" + $(this).attr("id"),
			 success:    function(html) {
				updateRouting();
			 }


		 });
	  }
   });


   $(".markResourceComplete").unbind('click').click(function () {
	  if (confirm("Do you really want to mark this resource complete?  This action cannot be undone.") == true) {   
		  $.ajax({
			 type:       "GET",
			 url:        "ajax_processing.php",
			 cache:      false,
			 data:       "action=markResourceComplete&resourceID=" + $(this).attr("id"),
			 success:    function(html) {
				updateRouting();
			 }
		 });
	 }
   });

   
}


   
function emptyDiv(divName){
 	$('#' + divName).html("");
}



 
 function emptyNewMessage(){

    $('#div_new').fadeTo(1000, 0, function () { 
	$('#div_new').html("");
    });
 	
 }