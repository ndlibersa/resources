$(document).ajaxError(function (event, request, settings) {
      alert("An error occured with this request, please retry later");
      console.debug("Ajax error: event = " + event.toString());
      goBack();
});


$(document).ready(function () {

      $("#customFilter").keyup(function () {
            console.debug("Change custom filter !");
            filterCustomContent();
      });

});

/*******************************************************************************************************/

/**
 * Send a SPARQL request to filter results with criteria
 * @param: 	s_name		string 		content of "Name" field (new resource form)
 * @param: 	s_pub		string 		content of "Provider" field (new resource form)
 * @param: 	s_type		int 		searchType (0 = all ; -1 = packages only; 1=titles only)
 *
 * @return: 	nothing but display results thanks to ajax and php treatment
 */
function allResults(s_name, s_pub, s_type) {
      displayLoadBar();
      $.ajax({
            type: "POST",
            url: "ajax_htmldata.php?action=getKBSearchResults&height=503&width=775&resourceID=&modal=true",
            cache: false,
            data: {name: s_name, issn: '', publisher: s_pub, type: s_type, paginate: true},
            success: function (res) {

                  document.getElementById("TB_ajaxContent").innerHTML = "";
                  $('#TB_ajaxContent').append(res);
            }
      });

      var parameters = [s_name, s_pub, s_type];
      var currentState = window.history.state;

      if ((currentState.funcName == 'allResults') && compareParamArray(currentState.param, parameters)) {
            console.debug("Same state as before !!, don't push");
      } else {
            window.history.pushState({funcName: 'allResults', param: [s_name, s_pub, s_type]}, 'test', null);
            console.debug("pushState(allResults(" + s_name + "," + s_pub + "," + s_type + "))");
      }


}

/*******************************************************************************************************/

/**
 * Send an OAI GetRecord request and display results
 * @param: 	s_type 		string 		type of the resource (title or package)
 * @param: 	s_gokbID 	string 		identifier of the searched resource
 *
 * @return: 	nothing but display results thanks to ajax and treatment
 */
function getDetails(s_type, s_gokbID) {
      displayLoadBar();
      $.ajax({
            type: "POST",
            url: "ajax_htmldata.php?action=getGokbResourceDetails&modal=true",
            cache: false,
            data: {type: s_type, id: s_gokbID},
            success: function (res) {
                  document.getElementById("TB_ajaxContent").innerHTML = "";
                  $('#TB_ajaxContent').append(res);
            }

      });
      var currentState = window.history.state;
      if ((currentState.funcName == 'getDetails') && (currentState.param[1] == s_gokbID)) {
            console.debug("Same state as before !!, don't push");
      } else {
            window.history.pushState({funcName: 'getDetails', param: [s_type, s_gokbID]}, 'test', null);
            console.debug("pushState(getDetails(" + s_type + "," + s_gokbID + "))");
      }
}

/*******************************************************************************************************/

/**
 * Import the resource from GOKb to DB
 * @param {string}       s_type           'package' OR 'title"
 * @param {string}      s_gokbID        GOKb ID of resource
 */
function selectResource(s_type, s_gokbID) {
      displayLoadBar();
      console.debug("fonction select(" + s_type + "," + s_gokbID + ")");
      $.ajax({
            type: "POST",
            url: "ajax_processing.php?action=importFromGOKb&height=503&width=775&resourceID=&modal=true",
            cache: false,
            data: {type: s_type, id: s_gokbID},
            success: function (res) {
                  console.debug("SELECT: ajax ok");
                  document.getElementById("TB_ajaxContent").innerHTML = "";
                  $('#TB_ajaxContent').append(res);
            }
      });
}

/*******************************************************************************************************/

/**
 * Manage the details tabs (global detail or TIPPs)
 * @param: 	element_nb 	int 	index of selected tab
 * 
 * @return: nothing but display the right content
 */
function loadDetailsContent(element_nb) {
      console.debug("loadDetailsContent");
      var tabs = document.getElementById("detailsTabs").getElementsByTagName("li");
      var divs = document.getElementById("detailsContainer").getElementsByTagName("div");

      for (var i = 0; i < tabs.length; i++) {
            if (i == element_nb) {
                  tabs[i].className = "selected";
                  divs[i].className = "";
            } else {
                  tabs[i].className = "";
                  divs[i].className = "invisible";
            }

      }

      if (element_nb == 1) {
            document.getElementById("paginationDiv").className = "";
      } else {
            document.getElementById("paginationDiv").className = "invisible";
      }
}

/*******************************************************************************************************/

/**
 * Manage pagination of results
 * @param: 	page 	int 	the page of results to display
 */
function iterator(page) {
      var divId = $("#currentDiv").val();
      var old = parseInt($("#currentPage").val());
      var pagination = document.getElementById("pageIterator").getElementsByTagName("li");

      var lines = document.getElementById(divId).getElementsByTagName("tr");
      var nbTipps = lines.length;
      var nbPages = pagination.length - 4;

      document.getElementById('currentPage').value = page;

      var itStart = page * 10;
      var stop = itStart + 10;

      //Display/hide prev and next buttons
      if (itStart == 0) {
            document.getElementById('previousTipps').className = 'invisible';
      }
      else {
            document.getElementById('previousTipps').className = '';
      }

      if (page == (nbPages - 1)) {
            document.getElementById('nextTipps').className = 'invisible';
      }
      else {
            document.getElementById('nextTipps').className = '';
      }

      if (stop >= nbTipps) {
            stop = nbTipps;
      }


      //hide previous page
      var disableStart = old * 10;
      var disableStop = (old + 1) * 10;
      if (disableStop > nbTipps)
            disableStop = nbTipps;

      console.debug("disable lines " + disableStart + " to " + disableStop);
      for (var i = disableStart; i < disableStop; i++) {
            lines[i].className = "invisible";
      }

      //display current page
      console.debug("display lines " + itStart + " to " + stop);
      for (var i = itStart; i < stop; i++) {
            lines[i].className = "";
      }

      //Display up to 13 pages number, if there are more --> truncate
      if (nbPages > 13) {
            //case 1: current page is at the beginning
            if (page < 7) {
                  document.getElementById("beginning").className = "invisible";
                  document.getElementById("end").className = "";
                  for (var i = 0; i < nbPages; i++) {
                        if (i < 13)
                              pagination[i + 2].className = '';
                        else
                              pagination[i + 2].className = 'invisible';
                  }
            }
            //case 2: current page is at the end
            else if (page > nbPages - 6) {
                  document.getElementById("beginning").className = "";
                  document.getElementById("end").className = "invisible";
                  for (var i = 0; i < nbPages; i++) {
                        if (i > nbPages - 14)
                              pagination[i + 2].className = "";
                        else
                              pagination[i + 2].className = 'invisible';
                  }
            }
            //case 3: current page is in the middle
            else {
                  document.getElementById("beginning").className = "";
                  document.getElementById("end").className = "";
                  for (var i = 0; i < nbPages; i++) {
                        if ((i >= page - 6) && (i <= page + 6))
                              pagination[i + 2].className = "";
                        else
                              pagination[i + 2].className = 'invisible';
                  }
            }
      } else {
            for (var i = 0; i < nbPages; i++) {
                  pagination[i + 2].className = '';
            }
      }
      pagination[page + 2].className = "active";

}
/*******************************************************************************************************/

/**
 * Manage the next/prev button of pagination
 * @param: 	op 	char 	next = '+', prev = '-'
 */
function navIterator(op) {
      var current = parseInt($("#currentPage").val());
      var param = 0;

      switch (op) {
            case '+':
                  param = current + 1;
                  break;
            case '-':
                  param = current - 1;
                  break;
            default:
                  break;
      }

      iterator(param);
}
/*******************************************************************************************************/

/**
 * Recall of the search function when the button 'back' is pressed
 * @param: 	s_name 		string		Name field content
 * @param: 	s_pub 		string 		Provoder field content
 */
function searchGokbBack(s_name, s_pub) {
      displayLoadBar();
      console.debug("appel à searchGokbBack");
      $.ajax({
            type: "POST",
            url: "ajax_htmldata.php?action=getKBSearchResults&height=503&width=775&resourceID=&modal=true",
            cache: false,
            data: {name: s_name, issn: "", publisher: s_pub, type: 0},
            success: function (res) {
                  document.getElementById("TB_ajaxContent").innerHTML = "";
                  $('#TB_ajaxContent').append(res);
            }
      });
      window.history.pushState(null, null, null);
      console.debug("pushState NULL");

      window.history.pushState({funcName: 'searchGokbBack', param: [s_name, s_pub]}, 'test', null);
      console.debug("pushState searchGokbBack(" + s_name + "," + s_pub + ")");

}
/*******************************************************************************************************/

/**
 * Manage the 'back' button
 */
function goBack() {
      displayLoadBar();
      //Get the previous state
      window.history.go(-1);
      var myState = window.history.state;

      if (myState == null) { //Display the "Add New resource form"
            console.debug("No previous state");
            $.ajax({
                  type: "POST",
                  url: "ajax_forms.php?action=getNewResourceForm&height=503&width=775&resourceID=&modal=true",
                  cache: false,
                  success: function (res) {
                        document.getElementById("TB_ajaxContent").innerHTML = "";
                        $('#TB_ajaxContent').append(res);
                  }
            });

      } else { //display the previous screen by calling the last function
            var toCall = myState.funcName;
            var funcParam = myState.param;
            var max = funcParam.length;

            var toDo = toCall + "(";

            for (var i = 0; i < max; i++) {
                  if (funcParam[i] == '')
                        toDo += "''";
                  else
                        toDo += "'" + funcParam[i] + "'";

                  if (i < max - 1)
                        toDo += ",";
            }

            toDo += ")";
            eval(toDo);
            console.debug("back, new state = " + myState.funcName);

      }
}

/*******************************************************************************************************/

function compareParamArray(paramArray, arrayToCompare) {
      var max = paramArray.length;
      var isEqual = true;

      if (max != arrayToCompare.length)
            return false;

      for (var i = 0; i < max; i++) {
            if (paramArray[i] != arrayToCompare[i])
                  return false;
      }

      return isEqual;
}




/*******************************************************************************************************/
/**
 * Display load bar while treatment
 */
function displayLoadBar() {
      var element = document.getElementById("TB_ajaxContent");
      if (element != null) {
            document.getElementById("TB_ajaxContent").innerHTML = "";
            $('#TB_ajaxContent').append("<div id='TB_load'><img src='images/loadingAnimation.gif' /></div>");
            $('#TB_load').show();//show loader
      }
}

/*******************************************************************************************************/

/**
 * Display package content for customization
 * @param {string}      packageID         GOKb package ID
 */
function getCustomizationScreen(packageID) {
      displayLoadBar();
      console.debug("fonction getCustomizationScreen(" + packageID + ")");
      $.ajax({
            type: "POST",
            url: "ajax_processing.php?action=customImportedPackageContent&modal=true",
            cache: false,
            data: {id: packageID},
            success: function (res) {
                  document.getElementById("TB_ajaxContent").innerHTML = "";
                  $('#TB_ajaxContent').append(res);
            }
      });
}

/*******************************************************************************************************/

/**
 * Remove unselected titles from package
 * @param {string}       packageID        GOKb package ID
 */
function submitCustom(packageID) {
      console.debug("function submitCustom");
      var checkboxes = document.getElementById("customTbody").getElementsByTagName("input");
      displayLoadBar();
      var max = checkboxes.length;
      var resToRemove = new Array();
      for (var i = 0; i < max; i++) {
            if (!(checkboxes[i].checked)) {
                  console.debug("checkboxes " + i + " is unchecked and is " + checkboxes[i].value);
                  resToRemove.push(checkboxes[i].value);
            }
      }

      $.ajax({
            type: "POST",
            url: "ajax_processing.php?action=customImportedPackageContent&modal=true",
            cache: false,
            data: {tab: resToRemove, id: packageID},
            success: function (res) {
                  document.getElementById("TB_ajaxContent").innerHTML = "";
                  $('#TB_ajaxContent').append(res);
            }

      });
}

/*******************************************************************************************************/

/**
 * Check / Uncheck all checkboxes (titles  included in package)
 * @param {checkBox}     source     main checkbox
 */
function checkAll(source) {
      var checkboxes = document.getElementsByName('cbs');
      var content = document.getElementById("customTbody").getElementsByTagName("tr");
      
      for (var i = 0, n = checkboxes.length; i < n; i++) {
            if (content[i].className != "invisible") {
                  checkboxes[i].checked = source.checked;
           }
      }
}

/*******************************************************************************************************/

/**
 * Remove resource and all its children
 * @param {int}   packageID         DB primaryKey of package
 */
function removeResAndChildren(packageID) {

      if (confirm("Do you really want to delete this resource and all its children?") == true) {
            displayLoadBar();
            $.ajax({
                  type: "GET",
                  url: "ajax_processing.php",
                  cache: false,
                  data: "action=deleteResourceAndChildren&resourceID=" + packageID,
                  success: function (html) {
                        //post return message to index
                        postwith('index.php', {message: html});
                  }
            });
      }
}

/*******************************************************************************************************/
function filterCustomContent() {
      console.debug("function filterCustomContent() ");

      var content = document.getElementById("customTbody").getElementsByTagName("tr");
      var textuals;
      var textual = "";
      var filterText = document.getElementById("customFilter").value;
      filterText = filterText.toLowerCase();

      for (var i = 0; i < content.length; i++) {
            textuals = content[i].childNodes;
            textual = textuals[1].innerHTML;
            textual = textual.toLowerCase();

            if (textual.indexOf(filterText) != -1) {
                  content[i].className = "";
            } else {
                  content[i].className = "invisible";
            }
      }
}

