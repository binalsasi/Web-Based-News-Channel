<?php
	$pageTitle = "Custom News Interface";
	$curPath = "> Custom News Interface";
	$uname = "dovahkin";
	$level = "master";

	include '../header.php';
?>
<link rel='stylesheet' type='text/css' href='../general_interfaces.css' />
<script src='../jquery-3.2.1.min.js'></script>
<div id='main'>

<div id='controls'>
	<button id='see_history_button' onclick="see_history();" disabled>See History</button>
	<button id='add_new_button' onclick="add_new();">Add New</button>
	<button id='toggle_multiple_button' onclick="toggleMultiple();">Toggle Status : Multiple</button>
	<button id='remove_multiple_button' onclick="removeMultiple();">Remove Multiple</button>
	<button id='rearrange_button' onclick="rearrange();">Rearrange</button>
</div>

<div id='list'>
</div>

</div>

<div id='infobox' class='popup'>
<div id='infobox_title'>Info :</div>
<div id='info_text'></div>
<button id='close_info' onclick="$('#infobox').slideUp(100);">close</div>
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
var texts = [], pubdates = [], statuses = [], sources = [], ids = [];
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
			if(selected.length != 0){
				$.post('./cn_processor.php', {'uname':username, 'action' : 'blockMultiple', 'selection' : selected.toString()}, function(result){
					console.log("blockMultiple selection : " +selected + " : result : " + result);
					fetchDataAndPopulate('../../getCnlist.php');
				});
			}
		}
		else if(see_history_action == "remove"){
			if(selected.length != 0){
				confirmation = confirm("You are about to remove " + selected.length + " items. Proceed?");
				if(confirmation){
					$.post('./cn_processor.php', {'uname' : username, 'action' : 'removeMultiple', 'selection' : selected.toString()}, function (result){
						console.log("edit multiple selection : " + selected + " : result : " + result);
						fetchDataAndPopulate('../../getCnlist.php');
					});
				}
			}
		}
		else if(see_history_action == "rearrange" ){
			if(ids.length != 0){
				$.post("./cn_processor.php", {"uname" : username, "action" : 'rearrange', 'newOrder' : ids.toString() }, function(result){
					console.log("rearrange new order : " + ids + " result : " + result);
					fetchDataAndPopulate('../../getCnlist.php');
				});
			}

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
		pubdates = tpubdates.slice();
		sources = tsources.slice();
		statuses = tstatuses.slice();
//TODO UNCHECKED CODE
		ids = tids.slice();

		exitMultipleMode();
	}
	else{
		$("button").attr('disabled', true);
		$("#inputbox_positive, #inputbox_negative").attr('disabled',false);
		$("#inputbox_title").text("Add Custom Sliding News");
		$("#inputbox_single_input").val("");
		$("#inputbox_single_input").attr('placeholder', "Enter A Line Of News");
		$("#inputbox").slideDown(200);
		$("#inputbox_single_input").focus();
		inputMode = "add";
	}
}

function exitMultipleMode(){
		$("input.multiple_selection").css({'display':'none'});
		$("button").attr('disabled', false);
		$("#see_history_button").text("See History");
		$("#see_history_button").attr('disabled',true);
		$("#add_new_button").text("Add New");
		see_history_action = "see_history";
		fetchDataAndPopulate('../../getCnlist.php');
		multipleMode = false;
}

function editNews(index){
		$("button").attr('disabled', true);
		$("#inputbox_positive, #inputbox_negative").attr('disabled',false);
		$("#inputbox_title").text("Edit Custom Sliding News");
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
			$.post('./cn_processor.php', {'uname' : username, 'action' : 'addSingle', 'data' : newNews}, function(result){
				console.log("add news : result " + result);
				
				fetchDataAndPopulate('../../getCnlist.php');
			});
		}

		else if(inputMode == "edit"){
			$.post('./cn_processor.php', {'uname':username, 'action' : 'editSingle', 'index' : selectedIndex, 'data' : newNews },
				function(result){
					console.log("edit single : index " + selectedIndex + " : result : " + result);
					fetchDataAndPopulate('../../getCnlist.php');
			});
		}

		$('#inputbox').slideUp(100, function(){$('button').attr('disabled', false);});
	}
}

function block(index){
	$.post('./cn_processor.php', {'uname' : username, 'action' : 'blockSingle', 'index' : index }, function(result){
		console.log("block single : index = " + index + " :  : " + result);
		fetchDataAndPopulate('../../getCnlist.php');
	});
}

function enterMultipleMode(positiveString, negativeString){
	multipleMode = true;

	ttexts = texts.slice();
	tpubdates = pubdates.slice();
	tsources = sources.slice();
	tstatuses = statuses.slice();
//TODO UNCKECHED CODE
	tids = ids.slice();

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

function info(index){
	$("#info_text").text("posted by " + sources[index]);
	$("#infobox").slideDown(100);
}
function remove(index){
	confirmation = confirm("Are you sure?");
	if(confirmation){
		$.post('./cn_processor.php', {'uname' : username, 'action':'removeSingle', 'index' : index }, function(result){
			fetchDataAndPopulate('../../getCnlist.php');
		});
	}
}
function makeListItem(index){
	aorb = statuses[index] == "active" ? "block" : "activate";
	listitemcontrols = "";
	if(see_history_action == "rearrange"){
		listitemcontrols = "<button class='info' onclick='info(\""+index+"\");'>i</button><button class='up' onclick='move(\"" +index + "\",\"up\");'>^</button><button class='down' onclick='move(\"" + index + "\", \"down\");'>v</button>";
	}
	else{
		listitemcontrols = "<button class='info' onclick='info(\""+index+"\");'>i</button><button class='block' onclick='block(\"" +index + "\");'>"+aorb+"</button><button class='edit' onclick='editNews(\"" + index + "\");'>Edit</button><button class='remove' onclick='remove(\"" + index + "\");'>X</button>";
	}

	return "<div class='listitem_"+statuses[index]+"'><input id='"+index+"' class='multiple_selection' type='checkbox' name='multiple_selection'><div class='detail_div'><div class='listitem_text'>"+texts[index]+"</div><div class='status_div_"+statuses[index]+"'>"+statuses[index]+"</div><div class='pubdate'>("+pubdates[index]+")</div></div><div class='listitem_controls'>"+listitemcontrols+"</div></div>";
}

function rearrange(){
	enterMultipleMode("Save Arrangement", "Cancel");
	$("input.multiple_selection").css({'display':'none'});
	see_history_action = "rearrange";

	populate();
}

function move(index, direction){
	index = parseInt(index);
	if((index != 0 || direction != "up") && (index != texts.length -1 || direction != "down")){
		if(direction == "up"){
			t = texts[index-1];	texts[index-1] = texts[index];		texts[index] = t;
			t = pubdates[index-1];	pubdates[index-1] = pubdates[index];	pubdates[index] = t;
			t = sources[index-1];	sources[index-1] = sources[index];	sources[index] = t;
			t = statuses[index-1];	statuses[index-1] = statuses[index];	statuses[index] = t;
			t = ids[index-1];	ids[index-1] = ids[index];		ids[index] = t;
		}
		else if(direction == "down"){
			t = texts[index+1];	texts[index+1] = texts[index];		texts[index] = t;
			t = pubdates[index+1];	pubdates[index+1] = pubdates[index];	pubdates[index] = t;
			t = sources[index+1];	sources[index+1] = sources[index];	sources[index] = t;
			t = statuses[index+1];	statuses[index+1] = statuses[index];	statuses[index] = t;
			t = ids[index+1];	ids[index+1] = ids[index];		ids[index] = t;
		}
		populate();
	}
}

function populate(){
console.log("pop : " + texts.length);
	liststring = "";

	if(texts.length != 0){
		for(z=0; z < texts.length; ++z){
			liststring += makeListItem(z);
		}
	}
	else{
		liststring = "There are no items to display.";
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
//			console.log(url+" successfully fetched. result : " + result);
			if(result != "none"){
				texts = [];
				pubdates = [];
				statuses = [];
				ids = [];
				sources = [];

				var xml = $.parseXML(result);
				x1 = xml.getElementsByTagName("text");
				x2 = xml.getElementsByTagName("pubdate");
				x3 = xml.getElementsByTagName("item");
				x4 = xml.getElementsByTagName("source");
				for(z=0; z< x1.length; ++z){
					texts.push(x1[z].childNodes[0].nodeValue);
					pubdates.push(x2[z].childNodes[0].nodeValue);
					statuses.push(x3[z].getAttribute("status"));
					ids.push(x3[z].getAttribute('id'));
					sources.push(x4[z].childNodes[0].nodeValue);
				}

				populate();
			}
		}
	});
}

$(function(){
	$("#inputbox").keyup(function(event){
		if(event.keyCode == 27){
			$('#inputbox').slideUp(100, function(){$('button').attr('disabled', false);});
		}
		else if(event.keyCode == 13){
			submitNews();
		}
	});
	fetchDataAndPopulate('../../getCnlist.php');
});
</script>
<?php include '../footer.php'; ?>
