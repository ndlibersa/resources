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