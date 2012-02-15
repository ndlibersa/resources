$(document).ready(function(){
  $('#catalogingForm').submit(submitCataloging);
});

function submitCataloging(){
  $('#submitCatalogingChanges').attr("disabled", "disabled");
  var form = $('#catalogingForm');
  $.post(
    form.attr('action'),
    form.serialize(),
    function(html) {
			if (html){
				$("#span_errors").html(html);
				$("#submitCatalogingChanges").removeAttr("disabled");
			}else{
				kill();
				window.parent.tb_remove();
				window.parent.updateCataloging();
			}
		}
  );
  
  
  return false;
}

//kill all binds done by jquery live
function kill(){

	$('.changeDefault').die('blur');
	$('.changeDefault').die('focus');
	$('.changeInput').die('blur');
	$('.changeInput').die('focus');
	$('.select').die('blur');
	$('.select').die('focus');

}

//the following are all to change the look of the inputs when they're clicked
$('.changeDefaultWhite').live('focus', function(e) {
	if (this.value == this.defaultValue){
		this.value = '';
	}
});

 $('.changeDefaultWhite').live('blur', function() {
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


$('.changeAutocomplete').live('focus', function() {
	if (this.value == this.defaultValue){
		this.value = '';
	}

 });


 $('.changeAutocomplete').live('blur', function() {
	if(this.value == ''){
		this.value = this.defaultValue;
	}	
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
