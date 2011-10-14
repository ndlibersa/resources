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

 $(document).ready(function(){


	 updateSavedRequestsNumber(); 
	 updateOutstandingTasksNumber();
	 updateSubmittedRequestsNumber();
      

      	 updateOutstandingTasks();
      
 	 $('.deleteRequest').live('click', function () {
 		  deleteRequest($(this).attr("id"));
 	 });

 
 	 $("#SubmittedRequests").click(function () {
 		  updateSubmittedRequests();
	 });

 	 $("#OutstandingTasks").click(function () {
 		  updateOutstandingTasks();
	 });

 	 $("#SavedRequests").click(function () {
 		  updateSavedRequests();
	 });	 
	 
 });
 


 function updateSavedRequests(){
 
  	$("#SubmittedRequests").parent().parent().removeClass('selected');
	$("#OutstandingTasks").parent().parent().removeClass('selected');
 	$('#SavedRequests').parent().parent().addClass('selected');
 
	$('#div_feedback').html("<img src = 'images/circle.gif' />Refreshing...");

	  $.ajax({
		  type:       "GET",
		  url:        "ajax_htmldata.php",
		  cache:      false,
		  data:       "action=getSavedQueue",
		  success:    function(html) { 
			$('#div_QueueContent').html(html);
			tb_reinit();
		  }
	   });
	   
	   //make sure error is empty
	   $('#div_error').html("");
	   
	   //also reset feedback div
	   $('#div_feedback').html("&nbsp;");
	   

	 updateSavedRequestsNumber(); 
	 updateOutstandingTasksNumber();
	 updateSubmittedRequestsNumber();      
 }




 function updateSubmittedRequests(){
 
  	$("#SubmittedRequests").parent().parent().addClass('selected');
	$("#OutstandingTasks").parent().parent().removeClass('selected');
 	$('#SavedRequests').parent().parent().removeClass('selected');
 
	$('#div_feedback').html("<img src = 'images/circle.gif' />Refreshing...");

	  $.ajax({
		  type:       "GET",
		  url:        "ajax_htmldata.php",
		  cache:      false,
		  data:       "action=getSubmittedQueue",
		  success:    function(html) { 
			$('#div_QueueContent').html(html);
		  }
	   });
	   
	   //make sure error is empty
	   $('#div_error').html("");
	   
	   //also reset feedback div
	   $('#div_feedback').html("&nbsp;");
	   

	 updateSavedRequestsNumber(); 
	 updateOutstandingTasksNumber();
	 updateSubmittedRequestsNumber();      
 }
 
 
 
 
 
  function updateOutstandingTasks(){
  
   	$("#SubmittedRequests").parent().parent().removeClass('selected');
 	$("#OutstandingTasks").parent().parent().addClass('selected');
  	$('#SavedRequests').parent().parent().removeClass('selected');
  
 	$('#div_feedback').html("<img src = 'images/circle.gif' />Refreshing...");
 
 	  $.ajax({
 		  type:       "GET",
 		  url:        "ajax_htmldata.php",
 		  cache:      false,
 		  data:       "action=getOutstandingQueue",
 		  success:    function(html) { 
 			$('#div_QueueContent').html(html);
 		  }
 	   });
 	   
 	   //make sure error is empty
 	   $('#div_error').html("");
 	   
 	   //also reset feedback div
 	   $('#div_feedback').html("&nbsp;");
 	   

	 updateSavedRequestsNumber(); 
	 updateOutstandingTasksNumber();
	 updateSubmittedRequestsNumber();
   }




 //currently you can only delete saved requests, updateSavedRequests() is hardcoded
 function deleteRequest(deleteID){
 
 	if (confirm("Do you really want to delete this request?") == true) {

	       $('#div_feedback').html("<img src = 'images/circle.gif' />Refreshing...");

	       $.ajax({
		  type:       "GET",
		  url:        "ajax_processing.php",
		  cache:      false,
		  data:       "action=deleteResource&resourceID=" + deleteID,
		  success:    function(html) { 
  			  	
			showError(html);  

			// close the div in 3 secs
			setTimeout("emptyError();",3000); 

			updateSavedRequests();

			return false;	
			
		  }
	      });
	      
	      //also reset feedback div
	      $('#div_feedback').html("&nbsp;");

	}
 }
 
 
 function updateSavedRequestsNumber(){
 
 
   $.ajax({
 	 type:       "GET",
 	 url:        "ajax_htmldata.php",
 	 cache:      false,
 	 data:       "action=getSavedRequestsNumber",
 	 success:    function(remaining) {
 		if (remaining == 1){
 			$(".span_SavedRequestsNumber").html("(" + remaining + " record)");
 		}else{
 			$(".span_SavedRequestsNumber").html("(" + remaining + " records)");
 		}
 	 }
  });
 
 }
	 


 function updateOutstandingTasksNumber(){
 
 
   $.ajax({
 	 type:       "GET",
 	 url:        "ajax_htmldata.php",
 	 cache:      false,
 	 data:       "action=getOutstandingTasksNumber",
 	 success:    function(remaining) {
 		if (remaining == 1){
 			$(".span_OutstandingTasksNumber").html("(" + remaining + " record)");
 		}else{
 			$(".span_OutstandingTasksNumber").html("(" + remaining + " records)");
 		}
 	 }
  });
 
 }



 function updateSubmittedRequestsNumber(){
 
 
   $.ajax({
 	 type:       "GET",
 	 url:        "ajax_htmldata.php",
 	 cache:      false,
 	 data:       "action=getSubmittedRequestsNumber",
 	 success:    function(remaining) {
 		if (remaining == 1){
 			$(".span_SubmittedRequestsNumber").html("(" + remaining + " record)");
 		}else{
 			$(".span_SubmittedRequestsNumber").html("(" + remaining + " records)");
 		}
 	 }
  });
 
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