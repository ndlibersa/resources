function saveConfiguration()
{	
	var configuration = {};
	configuration.title = $("#title").val();
	configuration.alt_title = $("#alt_title").val();
	configuration.url = $("#url").val();
	configuration.alt_url = $("#alt_url").val();
	configuration.parent_resource = $("#parent_resource").val();
	configuration.publisher = $("#publisher").val();
	configuration.pub_as_org = $("#pub_as_org").attr('checked');
	configuration.issn = $("#issn").val();
	configuration.multiple_issn = $("#multiple_issn").attr('checked');
	configuration.issn_delimiter = $("#issn_delimiter").val();
	configuration.isbn = $("#isbn").val();
	configuration.multiple_isbn = $("#multiple_isbn").attr('checked');
	configuration.isbn_delimiter = $("#isbn_delimiter").val();
	configuration.format = $("#format").val();
	configuration.create_format = $("#create_format").attr('checked');
	configuration.restype = $("#restype").val();
	configuration.create_restype = $("#create_restype").attr('checked');
	configuration.subject = $("#subject").val();

	$.ajax({
		type: "POST",
		url: "ajax_processing.php?action=saveImportConfiguration",
		cache:	false,
		data: "configName=" + $("#saveConfigName").val() + "&configData=" + encodeURIComponent(JSON.stringify(configuration)),
		success: function(html) {
			window.parent.tb_remove();
			$("#configuration").empty().html(html);
		}
	});
}

function loadConfiguration(configName)
{
	if(configName=="__NEW")
	{
		return;
	}

	$.ajax({
		type: "POST",
		url: "ajax_processing.php?action=loadImportConfiguration",
		cache:	false,
		data: "configName=" + configName,
		success: function(jsonconfig) {
			var configuration = JSON.parse(jsonconfig);
			$("#title").val(configuration.title);
			$("#alt_title").val(configuration.alt_title);
			$("#url").val(configuration.url);
			$("#alt_url").val(configuration.alt_url);
			$("#parent_resource").val(configuration.parent_resource);
			$("#publisher").val(configuration.publisher);
			$("#pub_as_org").attr('checked', configuration.pub_as_org);
			$("#issn").val(configuration.issn);
			$("#multiple_issn").attr('checked', configuration.multiple_issn);
			$("#issn_delimiter").val(configuration.issn_delimiter);
			$("#isbn").val(configuration.isbn);
			$("#multiple_isbn").attr('checked', configuration.multiple_isbn);
			$("#isbn_delimiter").val(configuration.isbn_delimiter);
			$("#format").val(configuration.format);
			$("#create_format").attr('checked', configuration.create_format);
			$("#restype").val(configuration.restype);
			$("#create_restype").attr('checked', configuration.create_restype);
			$("#subject").val(configuration.subject);
		}
	});
}