<?php
	$pageTitle = "KeywordFilter Manager Interface";
	$curPath = "> KeywordFilter Manager Interface";
	$uname = "dovahkin";
	$level = "master";

	include '../header.php';
?>

<link rel='stylesheet' type='text/css' href='../general_interfaces.css' />
<script src='../jquery-3.2.1.min.js'></script>
<div id='main'>

<div id='controls'>
	<button id='see_history_button' onclick="see_history();">See History</button>
	<button id='add_new_button' onclick="add_new();">Add New</button>
	<button id='toggle_multiple_button' onclick="toggleMultiple();">Toggle Status : Multiple</button>
	<button id='remove_multiple_button' onclick="removeMultiple();">Remove Multiple</button>
</div>

<div id='list'>
</div>

</div>

<div id='inputbox' class='popup'>
<div id='inputbox_title'>Title</div>
<div id='inputbox_body'>
<div id='inputbox_inputarea'>
<input id='inputbox_single_input' name='text_input_single' size=40>
</div>
</div>
<div id='inputbox_buttonarea'>
<button id='inputbox_positive' onclick="submitNews();">OK</button>
<button id='inputbox_negative' onclick="$('#inputbox').slideUp(100, function(){$('button').attr('disabled', false);});">Cancel</button>
</div>
</div>

<script>

var username = <?php echo "\"$uname\""; ?>;
var level = <?php echo "\"$level\""; ?>;
var texts = [], statuses = [];

var multipleMode = false;
var see_history_action = "see_history";
var inputMode = "add";
var selectedIndex;

function see_history(){
	if(multipleMode){
		selected = [];
		$("input.multiple_selection:checked").each(function(){
			selected.push($(this).attr('id'));
		});

		if(see_history_action == "block"){
			$.post('./kwm_processor.php', {'uname':username, 'action' : 'blockMultiple', 'selection' : selected.toString()}, function(result){
				console.log("blockMultiple selection : " +selected + " : result : " + result);
				fetchDataAndPopulate('../../conf/keywords.xml');
			});
		}
		else if(see_history_action == "remove"){
			$.post('./kwm_processor.php', {'uname' : username, 'action' : 'removeMultiple', 'selection' : selected.toString()}, function (result){
				console.log("edit multiple selection : " + selected + " : result : " + result);
				fetchDataAndPopulate('../../conf/keywords.xml');
			});
		}

		//restore the visual changes
		exitMultipleMode();
	}
	else{
		//TODO make see history action
	}
}

function add_new(){
	if(multipleMode){
		//cancel multiple selection
		texts = ttexts.slice();
		statuses = tstatuses.slice();

		exitMultipleMode();
	}
	else{
		$("button").attr('disabled', true);
		$("#inputbox_positive, #inputbox_negative").attr('disabled',false);
		$("#inputbox_title").text("Add Keyword");
		$("#inputbox_single_input").val("");
		$("#inputbox_single_input").attr('placeholder', "Enter A String");
		$("#inputbox").slideDown(200);
		$("#inputbox_single_input").focus();
		inputMode = "add";
	}
}

function exitMultipleMode(){
		$("input.multiple_selection").css({'display':'none'});
		$("button").attr('disabled', false);
		$("#see_history_button").text("See History");
		$("#add_new_button").text("Add New");
		see_history_action = "see_history";
		fetchDataAndPopulate('../../conf/keywords.xml');
		multipleMode = false;
}

function editNews(index){
		$("button").attr('disabled', true);
		$("#inputbox_positive, #inputbox_negative").attr('disabled',false);
		$("#inputbox_title").text("Edit Keyword");
		$("#inputbox_single_input").val(texts[index]);
		$("#inputbox").slideDown(200);
		$("#inputbox_single_input").focus();
		inputMode = "edit";
		selectedIndex = index;
}

function submitNews(){
	newNews = $("#inputbox_single_input").val().trim();
	console.log(newNews);
	if(newNews != ""){
		if(inputMode == "add"){
			$.post('./kwm_processor.php', {'uname' : username, 'action' : 'addSingle', 'data' : newNews}, function(result){
				console.log("add news : result " + result);
				
				fetchDataAndPopulate('../../conf/keywords.xml');
			});
		}

		else if(inputMode == "edit"){
			$.post('./kwm_processor.php', {'uname':username, 'action' : 'editSingle', 'index' : selectedIndex, 'data' : newNews },
				function(result){
					console.log("edit single : index " + selectedIndex + " : result : " + result);
					fetchDataAndPopulate('../../conf/keywords.xml');
			});
		}

		$('#inputbox').slideUp(100, function(){$('button').attr('disabled', false);});
	}
}

function block(index){
	$.post('./kwm_processor.php', {'uname' : username, 'action' : 'blockSingle', 'index' : index }, function(result){
		console.log("block single : index = " + index + " :  : " + result);
		fetchDataAndPopulate('../../conf/keywords.xml');
	});
}

function enterMultipleMode(positiveString, negativeString){
	multipleMode = true;

	ttexts = texts.slice();
	tstatuses = statuses.slice();

	$("input.multiple_selection").css({'display':'block'});
	$("button").attr({'disabled':'true'});
	$("button.info").attr('disabled', false);
	$("#close_info").attr('disabled', false);
	$("#see_history_button").text(positiveString);
	$("#add_new_button").text(negativeString);
	$("#see_history_button").attr('disabled', false);
	$("#add_new_button").attr('disabled', false);
}

function toggleMultiple(){
	enterMultipleMode("Toggle Selected", "Cancel");
	see_history_action = "block";
}

function removeMultiple(){
	enterMultipleMode("Remove Selected", "Cancel");
	see_history_action = "remove";
}

function remove(index){
	confirmation = confirm("Are you sure?");
	if(confirmation){
		$.post('./kwm_processor.php', {'uname' : username, 'action':'removeSingle', 'index' : index }, function(result){
			fetchDataAndPopulate('../../conf/keywords.xml');
		});
	}
}
function makeListItem(index){
	aorb = statuses[index] == "active" ? "block" : "activate";

	return "<div class='listitem_"+statuses[index]+"'><input id='"+index+"' class='multiple_selection' type='checkbox' name='multiple_selection'><div class='detail_div'><div class='listitem_text'>"+texts[index]+"</div><div class='status_div_"+statuses[index]+"'>"+statuses[index]+"</div></div><div class='listitem_controls'><button class='block' onclick='block(\"" +index + "\");'>"+aorb+"</button><button class='edit' onclick='editNews(\"" + index + "\");'>Edit</button><button class='remove' onclick='remove(\"" + index + "\");'>X</button></div></div>";
}

function populate(){
console.log("pop : " + texts.length);
	liststring = "";
	for(z=0; z < texts.length; ++z){
		liststring += makeListItem(z);
	}
	$("#list").html(liststring);
}

function fetchDataAndPopulate(url){
	$.ajax({
		url : url,
		type: 'POST',
		async: true,
		cache: false,
		data: {
			'randomGibberish': 'blaha'
		},
		error: function(xhr){
			console.log("error occured while fetching "+url+" : " + xhr.status + " : " + xhr.statusText);
		},
		success: function(result){
			console.log(url+" successfully fetched. result : " + result);
			if(result != "none"){
				texts = [];
				statuses = [];

				var xml = result;

				x1 = xml.getElementsByTagName("text");
				x3 = xml.getElementsByTagName("item");
				for(z=0; z< x1.length; ++z){
					texts.push(x1[z].childNodes[0].nodeValue);
					statuses.push(x3[z].getAttribute("status"));
				}
				populate();
			}
		}
	});
}

$(function(){
	//$("#infobox").slideUp(1);
	$("#inputbox").keyup(function(event){
		if(event.keyCode == 27){
			$('#inputbox').slideUp(100, function(){$('button').attr('disabled', false);});
		}
		else if(event.keyCode == 13){
			submitNews();
		}
	});

	fetchDataAndPopulate('../../conf/keywords.xml');
});
</script>
<?php include '../footer.php'; ?>
