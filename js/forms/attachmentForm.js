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

 $(function(){



	//bind all of the inputs
	 $("#submitAttachmentForm").click(function () {
		submitAttachment();
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



  	 
 });




var fileName = $("#upload_button").val();
var exists = '';

//verify filename isn't already used
function checkUploadAttachment (file, extension){
	$("#div_file_message").html("");
	$.ajax({
		type:       "GET",
		url:        "ajax_processing.php",
		cache:      false,
		async: 	 false,
		data:       "action=checkUploadAttachment&uploadAttachment=" + file,
		success:    function(response) {
		  exists = "";
			if (response == "1"){
				exists = "1";
				$("#div_file_message").html("  <font color='red'>File name is already being used...</font>");
				return false;
			} else if (response == "2"){
				exists = "2";
				$("#div_file_message").html("  <font color='red'>File name may not contain special characters - ampersand, single quote, double quote or less than/greater than characters</font>");
				return false;				
			} else if (response == "3"){
				exists = "3";
				$("#div_file_message").html("  <font color='red'>The attachments directory is not writable.</font>");
				return false;
			}
			
		}

	});
}

//do actual upload
new AjaxUpload('upload_button',
	{action: 'ajax_processing.php?action=uploadAttachment',
			name: 'myfile',
			onChange : function (file, extension){checkUploadAttachment(file, extension);},
			onComplete : function(data,response){
				fileName=data;

				if (exists == ""){
          var errorMessage = $(response).filter('#error');
          if (errorMessage.size() > 0) {
            $("#div_file_message").html("<font color='red'>" + errorMessage.html() + "</font>");
          } else {
            $("#div_file_message").html("<img src='images/paperclip.gif'>" + fileName + " successfully uploaded.");
				}
      }
		}
});


 function replaceFile(){
 	fileName = $("#upload_button").val();
 	//used for the Attachment Edit form - defaults to show current uploaded file with an option to replace
 	//replace html contents with browse for uploading attachment.
 	$('#div_uploadFile').html("<div id='uploadFile'><input type='file' name='upload_button' id='upload_button'></div>");

 	//also reinitialize the code for uploading the file
	new AjaxUpload('upload_button',
		{action: 'ajax_processing.php?action=uploadAttachment',
				name: 'myfile',
				onChange : function (file, extension){checkUploadAttachment(file, extension);},
				onComplete : function(data){
					fileName=data;

					if (exists == ""){
						$("#div_file_message").html("<img src='images/paperclip.gif'>" + fileName + " successfully uploaded.");
						$("#div_uploadFile").html("<br />");

					}

			}
	});

 }


 function validateForm (){
 	myReturn=0;
 	if (!validateRequired('shortName','<br />Name must be entered to continue.')) myReturn="1";
 	if (!validateRequired('attachmentTypeID','<br />Attachment Type must be selected to continue.')) myReturn="1";
 	
 
 	if (myReturn == "1"){
		return false; 	
 	}else{
 		return true;
 	}
}
 





function submitAttachment(){

	if (fileName == ''){
		$("#div_file_message").html("A file must be uploaded");
	}else{
		if (validateForm() === true) {
			$('#submitAttachment').attr("disabled", "disabled"); 
			  $.ajax({
				 type:       "POST",
				 url:        "ajax_processing.php?action=submitAttachment",
				 cache:      false,
				 data:       { resourceID: $("#resourceID").val(), attachmentID: $("#editAttachmentID").val(), shortName: $("#shortName").val(), uploadDocument: fileName, descriptionText: $("#descriptionText").val(), attachmentTypeID: $("#attachmentTypeID").val()  },
				 success:    function(html) {
					if (html){
						$("#span_errors").html(html);
						$("#submitAttachment").removeAttr("disabled");
					}else{
						window.parent.tb_remove();
						window.parent.updateAttachments();
						window.parent.updateAttachmentsNumber();
						return false;
					}					

				 }


			 });

		}
	}

}


//kill all binds done by jquery live
function kill(){

	$('.changeInput').die('blur');
	$('.changeInput').die('focus');
	$('.select').die('blur');
	$('.select').die('focus');

}