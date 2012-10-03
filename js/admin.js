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

      	 updateUserTable();
      
	 $(".AdminLink").click(function () {
		updateTable($(this).attr("id"));
	 });

	 $(".UserAdminLink").click(function () {
		  updateUserTable();
	 });

	 $(".AlertAdminLink").click(function () {
		  updateAlertTable();
	 });


	 $(".WorkflowAdminLink").click(function () {
		  updateWorkflowTable();
	 });

	 $(".SubjectsAdminLink").click(function () {
		  updateSubjectsTable();
	 });	 

	 
	 $(".CurrencyLink").click(function () {
		  updateCurrencyTable();
	 });
	 
	 
 	 $('.removeData').live('click', function () {
 		  deleteData($(this).attr("cn"), $(this).attr("id"));
 	 });

 
 
      
 });
 


 function updateTable(className){

	$(".UserAdminLink").parent().parent().removeClass('selected');
	$(".AlertAdminLink").parent().parent().removeClass('selected');
	$(".WorkflowAdminLink").parent().parent().removeClass('selected');
	$(".AdminLink").parent().parent().removeClass('selected');
	$(".CurrencyLink").parent().parent().removeClass('selected');
	$(".SubjectsAdminLink").parent().parent().removeClass('selected'); 
	$("#" + className).parent().parent().addClass('selected');

	  $.ajax({
		  type:       "GET",
		  url:        "ajax_htmldata.php",
		  cache:      false,
		  data:       "action=getAdminDisplay&className=" + className,
		  success:    function(html) { 
			$('#div_AdminContent').html(html);
			tb_reinit();
		  }
	   });
	   
	   //make sure error is empty
	   $('#div_error').html("");
	   
      
 }





 function updateCurrencyTable(){

  $(".AlertAdminLink").parent().parent().removeClass('selected'); 
  $(".AdminLink").parent().parent().removeClass('selected'); 
  $(".WorkflowAdminLink").parent().parent().removeClass('selected');
  $(".UserAdminLink").parent().parent().removeClass('selected');
  $(".CurrencyLink").parent().parent().addClass('selected');
  $(".SubjectsAdminLink").parent().parent().removeClass('selected'); 
  
       $.ajax({
          type:       "GET",
          url:        "ajax_htmldata.php",
          cache:      false,
          data:       "action=getAdminCurrencyDisplay",
          success:    function(html) { 
          	$('#div_AdminContent').html(html);
          	tb_reinit();
          }
	});

   //make sure error is empty
   $('#div_error').html("");      
            
 }



 function updateUserTable(){

  $(".AlertAdminLink").parent().parent().removeClass('selected'); 
  $(".AdminLink").parent().parent().removeClass('selected'); 
  $(".WorkflowAdminLink").parent().parent().removeClass('selected');
  $(".CurrencyLink").parent().parent().removeClass('selected');
  $(".UserAdminLink").parent().parent().addClass('selected');
  $(".SubjectsAdminLink").parent().parent().removeClass('selected'); 
  
       $.ajax({
          type:       "GET",
          url:        "ajax_htmldata.php",
          cache:      false,
          data:       "action=getAdminUserDisplay",
          success:    function(html) { 
          	$('#div_AdminContent').html(html);
          	tb_reinit();
          }
	});

   //make sure error is empty
   $('#div_error').html("");      
            
 }



 function updateAlertTable(){

  $(".UserAdminLink").parent().parent().removeClass('selected'); 
  $(".AdminLink").parent().parent().removeClass('selected'); 
  $(".WorkflowAdminLink").parent().parent().removeClass('selected'); 
  $(".CurrencyLink").parent().parent().removeClass('selected'); 
  $(".AlertAdminLink").parent().parent().addClass('selected');
  $(".SubjectsAdminLink").parent().parent().removeClass('selected'); 

       $.ajax({
          type:       "GET",
          url:        "ajax_htmldata.php",
          cache:      false,
          data:       "action=getAdminAlertDisplay",
          success:    function(html) { 
          	$('#div_AdminContent').html(html);
          	tb_reinit();
          }
	});

   //make sure error is empty
   $('#div_error').html("");      
            
 }




 function updateWorkflowTable(){

  $(".UserAdminLink").parent().parent().removeClass('selected'); 
  $(".AdminLink").parent().parent().removeClass('selected'); 
  $(".AlertAdminLink").parent().parent().removeClass('selected');
  $(".CurrencyLink").parent().parent().removeClass('selected');
  $(".WorkflowAdminLink").parent().parent().addClass('selected');
  $(".SubjectsAdminLink").parent().parent().removeClass('selected'); 
  
       $.ajax({
          type:       "GET",
          url:        "ajax_htmldata.php",
          cache:      false,
          data:       "action=getAdminWorkflowDisplay",
          success:    function(html) { 
          	$('#div_AdminContent').html(html);
          	tb_reinit();
          }
	});

   //make sure error is empty
   $('#div_error').html("");      
            
 }

function updateSubjectsTable(){

  $(".UserAdminLink").parent().parent().removeClass('selected'); 
  $(".AdminLink").parent().parent().removeClass('selected'); 
  $(".AlertAdminLink").parent().parent().removeClass('selected');
  $(".CurrencyLink").parent().parent().removeClass('selected');
  $(".WorkflowAdminLink").parent().parent().removeClass('selected');
  $(".SubjectsAdminLink").parent().parent().addClass('selected');  

       $.ajax({
          type:       "GET",
          url:        "ajax_htmldata.php",
          cache:      false,
          data:       "action=getAdminSubjectDisplay",
          success:    function(html) { 
          	$('#div_AdminContent').html(html);
          	tb_reinit();
          }
	});

   //make sure error is empty
   $('#div_error').html("");      
            
 }







 function submitData(){
 
	$.ajax({
          type:       "POST",
          url:        "ajax_processing.php?action=updateData",
          cache:      false,
          data:       { className: $("#editClassName").val(), updateID: $("#editUpdateID").val(), shortName: $('#updateVal').val() },
          success:    function(html) { 
          	updateTable($("#editClassName").val());
          	window.parent.tb_remove();
          }
       });

 }



 function submitUserData(){
	$.ajax({
          type:       "POST",
          url:        "ajax_processing.php?action=updateUserData",
          cache:      false,
          data:       { orgloginID: $('#editLoginID').val(), loginID: $('#loginID').val(), firstName: $('#firstName').val(), lastName: $('#lastName').val(), emailAddress: $('#emailAddress').val(), privilegeID: $('#privilegeID').val(), accountTabIndicator: getCheckboxValue('accountTab') },
          success:    function(html) { 
		  updateUserTable();
		  window.parent.tb_remove();
          }
       });

 }





 function submitCurrencyData(){
	$.ajax({
          type:       "POST",
          url:        "ajax_processing.php?action=updateCurrency",
          cache:      false,
          data:       { editCurrencyCode: $('#editCurrencyCode').val(), currencyCode: $('#currencyCode').val(), shortName: $('#shortName').val() },
          success:    function(html) { 
		  updateCurrencyTable();
		  window.parent.tb_remove();
          }
       });

 }


 function submitAdminAlertEmail(){
	$.ajax({
          type:       "POST",
          url:        "ajax_processing.php?action=updateAdminAlertEmail",
          cache:      false,
          data:       { alertEmailAddressID: $('#editAlertEmailAddressID').val(), emailAddress: $('#emailAddress').val() },
          success:    function(html) { 
		  updateAlertTable();
		  window.parent.tb_remove();
          }
       });

 }





 function submitAdminAlertDays(){
 
 	numberOfDays = $('#daysInAdvanceNumber').val();
 	
 	if (parseInt(numberOfDays) != numberOfDays-0){
 		$('#div_form_error').html("Number of days must be a number");
 		return false;
 	}else if ((numberOfDays < 1) || (numberOfDays > 365)){
 		$('#div_form_error').html("Number of days should be between 1 and 365");
 		return false;
 	}else{
 		$('#div_form_error').html("&nbsp;");
		$.ajax({
		  type:       "POST",
		  url:        "ajax_processing.php?action=updateAdminAlertDays",
		  cache:      false,
		  data:       { alertDaysInAdvanceID: $('#editAlertDaysInAdvanceID').val(), daysInAdvanceNumber: $('#daysInAdvanceNumber').val() },
		  success:    function(html) { 
			  updateAlertTable();
			  window.parent.tb_remove();
		  }
	       });
	}
 }



 function deleteData(className, deleteID){
 
 	if (confirm("Do you really want to delete this data?") == true) {

	       $.ajax({
		  type:       "GET",
		  url:        "ajax_processing.php",
		  cache:      false,
		  data:       "action=deleteInstance&class=" + className + "&id=" + deleteID,
		  success:    function(html) { 

			if (html){		  			  	
				showError(html);  

				// close the div in 3 secs
				setTimeout("emptyError();",3000); 
			}else{
				updateTable(className);  
				tb_reinit();
			}
			
		  }
	      });

	}
 }

   function deleteGeneralSubject(className, deleteID){
 
 	if (confirm("Do you really want to remove this data?") == true) {
	   
	       $.ajax({
		  type:       "GET",
		  url:        "ajax_processing.php",
		  cache:      false,
		  data:       "action=deleteGeneralSubject&class=" + className + "&id=" + deleteID,
		  success:    function(html) { 

			if (html){		  			  	
				showError(html);  

				// close the div in 3 secs
				setTimeout("emptyError();",3000); 
			}else{
				updateSubjectsTable();  
				tb_reinit();
			}
			
		  }
	      });

	}
 }
 
 
   function deleteDetailedSubject(className, deleteID){
 
 	if (confirm("Do you really want to remove this data?") == true) {
	   
	       $.ajax({
		  type:       "GET",
		  url:        "ajax_processing.php",
		  cache:      false,
		  data:       "action=deleteDetailedSubject&class=" + className + "&id=" + deleteID,
		  success:    function(html) { 

			if (html){		  			  	
				showError(html);  

				// close the div in 3 secs
				setTimeout("emptyError();",3000); 
			}else{
				updateSubjectsTable();  
				tb_reinit();
			}
			
		  }
	      });

	}
 }

 
  function deleteGeneralDetailSubject(className, deleteID){
 
 	if (confirm("Do you really want to remove this data?") == true) {
	   
	       $.ajax({
		  type:       "GET",
		  url:        "ajax_processing.php",
		  cache:      false,
		  data:       "action=deleteInstance&class=" + className + "&id=" + deleteID,
		  success:    function(html) { 

			if (html){		  			  	
				showError(html);  

				// close the div in 3 secs
				setTimeout("emptyError();",3000); 
			}else{
				updateSubjectsTable();  
				tb_reinit();
			}
			
		  }
	      });

	}
 }
 
 


 function deleteUser(deleteId){
 
 	if (confirm("Do you really want to delete this user?") == true) {

	       $('#span_User_response').html('<img src = "images/circle.gif">&nbsp;&nbsp;Processing...');
	       $.ajax({
		  type:       "GET",
		  url:        "ajax_processing.php",
		  cache:      false,
		  data:       "action=deleteInstance&class=User&id=" + deleteId,
		  success:    function(html) { 
			if (html){		  			  	
				showError(html);  

				// close the div in 3 secs
				setTimeout("emptyError();",3000); 
			}else{
				updateUserTable();  
				tb_reinit();
			}
		  }
	      });

	}
 } 
 
  



 function deleteAlert(className, deleteID){
 
 	if (confirm("Do you really want to remove this alert setting?") == true) {
	   
	       $.ajax({
		  type:       "GET",
		  url:        "ajax_processing.php",
		  cache:      false,
		  data:       "action=deleteInstance&class=" + className + "&id=" + deleteID,
		  success:    function(html) { 

			if (html){		  			  	
				showError(html);  

				// close the div in 3 secs
				setTimeout("emptyError();",3000); 
			}else{
				updateAlertTable();  
				tb_reinit();
			}
			
		  }
	      });

	}
 }



 function deleteWorkflow(className, deleteID){
 
 	if (confirm("Do you really want to remove this data?") == true) {
	   
	       $.ajax({
		  type:       "GET",
		  url:        "ajax_processing.php",
		  cache:      false,
		  data:       "action=deleteInstance&class=" + className + "&id=" + deleteID,
		  success:    function(html) { 

			if (html){		  			  	
				showError(html);  

				// close the div in 3 secs
				setTimeout("emptyError();",3000); 
			}else{
				updateWorkflowTable();  
				tb_reinit();
			}
			
		  }
	      });

	}
 }




 function deleteCurrency(className, deleteID){
 
 	if (confirm("Do you really want to delete this currency?") == true) {

	       $.ajax({
		  type:       "GET",
		  url:        "ajax_processing.php",
		  cache:      false,
		  data:       "action=deleteInstance&class=" + className + "&id=" + deleteID,
		  success:    function(html) { 

			if (html){		  			  	
				showError(html);  

				// close the div in 3 secs
				setTimeout("emptyError();",3000); 
			}else{
				updateCurrencyTable();  
				tb_reinit();
			}
			
		  }
	      });

	}
 }
 
 


  
 
 function showError(html){
 
     $('#div_error').fadeTo(0, 5000, function () { 
 	$('#div_error').html(html);
     });
  	
 }


 
 function emptyError(){

    $('#div_error').fadeTo(500, 0, function () { 
	$('#div_error').html("");
    });
 	
 }