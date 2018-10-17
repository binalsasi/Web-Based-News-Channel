<?php
	$pageTitle = "Feed Page Interface";
	$curPath = "> Feed Page Interface";
	$uname = "dovahkin";
	$level = "master";

	include '../header.php';
?>
<link rel='stylesheet' type='text/css' href='./fp.css' />
<script src='../jquery-3.2.1.min.js'></script>
<div id='main'>

<div id='controls'>
	<button id='toggle_autofeeds' onclick='toggleAutoFeeds();' disabled>Enable/Disable Auto Feeds</button>
	<button id='see_history_button' onclick="see_history();" disabled>See History</button>
	<button id='add_new_button' onclick="add_new();">Add New</button>
	<button id='toggle_multiple_button' onclick="toggleMultiple();">Toggle Status : Multiple</button>
	<button id='remove_multiple_button' onclick="removeMultiple();">Remove Multiple</button>
	<button id='rearrange_button' onclick="rearrange();">Rearrange</button>
	<button id='see_autofeeds' onclick='seeAutoFeeds();' disabled>See Auto Feeds</button>
</div>

<div id='listcontainer'>
<div id='tabcontainer'>
<div id='active_tab' onclick="changeTab('active');">Active</div>
<div id='all_news_tab' onclick="changeTab('allcustom');">All Feeds</div>
</div>
<div id='list'>
</div>
</div>

</div>

<div id='infobox' class='popup'>
<div id='infobox_title'>Info :</div>
<div id='info_body'>
<b>Title :</b>
<div id='info_title'></div>
<b>Description :</b>
<div id='info_desc'></div>
<b>Source :</b>
<div id='info_source'></div>
<b>Image :</b>
<div id='info_img_div'></div>
<b>Published Date :</b>
<div id='info_pubdate'></div>
<b>Status :</b>
<div id='info_status'></div>
</div>
<button id='close_info' onclick="$('#infobox').slideUp(100);">close</div>
</div>

<form id='redirectForm' method="post" action='../ad/ad.php'>
<input id='addedit_mode_input' type='hidden' name='mode'>
<input id='addedit_tab_input' type='hidden' name='tab'>
<input id='addedit_id_input' type='hidden' name='id'>
<input name='backlink' value='../fp/fp.php'>
</form>


<script>

var username = <?php echo "\"$uname\""; ?>;
var level = <?php echo "\"$level\""; ?>;
var texts = [], pubdates = [], statuses = [], sources = [], ids = [];
var atitles = [], astatuses = [], asources = [], aids = [], adescs = [], apubdates = [], aimgLinks = [];
var btitles = [], bstatuses = [], bsources = [], bids = [], bdescs = [], bpubdates = [], bimgLinks = [];

var multipleMode = false;
var see_history_action = "see_history";
var inputMode = "add";
var selectedIndex;
var currentTab = "active";

function changeTab(tab){
	if(tab != currentTab){
		if(tab == "active"){
			$("#active_tab").css({'background-color': '#ccc', 'box-shadow' : '2px -2px 2px black'});
			$("#all_news_tab").css({'background-color' : 'gray', 'box-shadow' : 'none'});
			$("#rearrange_button").attr('disabled', false);
			$("#toggle_multiple_button").attr('disabled', false);
		}
		else{
			$("#all_news_tab").css({'background-color': '#ccc', 'box-shadow' : '2px -2px 2px black'});
			$("#active_tab").css({'background-color' : 'gray', 'box-shadow' : 'none'});
			$("#rearrange_button").attr('disabled', true);
			$("#toggle_multiple_button").attr('disabled', true);
		}
		currentTab = tab;
		populate();
	}
}



function see_history(){
	if(multipleMode){
		selected = [];
		$("input.multiple_selection:checked").each(function(){
			selected.push($(this).attr('id'));
		});

		if(see_history_action == "block"){
			if(selected.length != 0){
				$.post('./fp_processor.php', {'uname':username, 'action' : 'blockMultiple', 'selection' : selected.toString()}, function(result){
//					console.log("blockMultiple selection : " +selected + " : result : " + result);
					fetchDataAndPopulate('./fp_processor.php');
				});
			}
		}

		else if(see_history_action == "remove"){
			if(selected.length != 0){
				confirmation = confirm("You are about to remove " + selected.length + " items from the " + currentTab + " tab. Proceed?");
				if(confirmation){
					if(currentTab != "active"){
						selIds = [];
						for (z = 0; z < selected.length; ++z)
							selIds.push(bids[selected[z]]);
						selected = selIds;
					}

					$.post('./fp_processor.php', {'uname' : username, 'action' : 'removeMultiple_' + currentTab, 'selection' : selected.toString()}, function (result){
						console.log("remove multiple "+currentTab+" selection : " + selected + " : result : " + result);
						fetchDataAndPopulate('./fp_processor.php');
					});
				}
			}
		}

		else if(see_history_action == "rearrange" ){
			if(aids.length != 0){
				$.post("./fp_processor.php", {"uname" : username, "action" : 'rearrange', 'newOrder' : aids.toString() }, function(result){
//					console.log("rearrange new order : " + aids + " result : " + result);
					fetchDataAndPopulate('./fp_processor.php');
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
		if(see_history_action == "rearrange"){
			atitles = ttitles.slice();
			apubdates = tpubdates.slice();
			asources = tsources.slice();
			astatuses = tstatuses.slice();
			adescs = tdescs.slice();
			aids = tids.slice();
			aimgLinks = timglinks.slice();
		}

		exitMultipleMode();
	}
	else{
		$("#addedit_mode_input").val("add");
		$("#addedit_tab_input").val("allcustom");
		$("#addedit_id_input").val(0);
		$("#redirectForm").submit();
	}
}

function exitMultipleMode(){
		$("input.multiple_selection").css({'display':'none'});
		$("button").attr('disabled', false);
		$("#see_history_button").text("See History");
		$("#add_new_button").text("Add New");
		see_history_action = "see_history";
		fetchDataAndPopulate('./fp_processor.php');
/*
TODO		remove the following when implemented		
*/
		$("#see_history_button").attr('disabled', true);
		$("#toggle_autofeeds").attr('disabled', true);
		$("#see_autofeeds").attr('disabled', true);

		multipleMode = false;
}

function block(index){
	$.post('./fp_processor.php', {'uname' : username, 'action' : 'blockSingle', 'index' : index }, function(result){
		console.log("block single : index = " + index + " :  : " + result);
		fetchDataAndPopulate('./fp_processor.php');
	});
}

function enterMultipleMode(positiveString, negativeString){
	multipleMode = true;

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
	if(currentTab == "active"){
		title = atitles[index];
		source = asources[index];
		status = astatuses[index];
		desc = adescs[index];
		pubdate = apubdates[index];
		imglink = aimgLinks[index];
	}
	else{
		title = btitles[index];
		source = bsources[index];
		status = "In Database";
		desc = bdescs[index];
		pubdate = bpubdates[index];
		imglink = bimgLinks[index];
	}
	$("#info_title").html(title);
	$("#info_source").html(source);
	$("#info_status").html(status);
	$("#info_desc").html(desc);
	$("#info_pubdate").html(pubdate);
	if(imglink != "none")
		$("#info_img_div").html("<img id='info_img' src='../"+imglink+"' />");
	else
		$("#info_img_div").html("No image is specified. one will be used from the general images");
	$("#infobox").slideDown(100);
}

function remove(index){
	confirmation = confirm("Are you sure?");
	if(confirmation){
		if(currentTab != "active")
			index = bids[index];
		$.post('./fp_processor.php', {'uname' : username, 'action':"removeSingle_"+currentTab, 'index' : index }, function(result){
			fetchDataAndPopulate('./fp_processor.php');
		});
	}
}

function editNews(index){
		console.log("edit action " + currentTab + " : " + index);
		$("#addedit_mode_input").val("edit");
		$("#addedit_tab_input").val(currentTab);
		$("#addedit_id_input").val(index);
		$("#redirectForm").submit();
}


function makeListItem(index){
	if(currentTab == "active"){
		aorb = astatuses[index] == "active" ? "block" : "activate";
		listitemcontrols = "";
		if(see_history_action == "rearrange"){
			listitemcontrols = "<button class='info' onclick='info(\""+index+"\");'>i</button><button class='up' onclick='move(\"" +index + "\",\"up\");'>^</button><button class='down' onclick='move(\"" + index + "\", \"down\");'>v</button>";
		}
		else{
			listitemcontrols = "<button class='info' onclick='info(\""+index+"\");'>i</button><button class='block' onclick='block(\"" +index + "\");'>"+aorb+"</button><button class='edit' onclick='editNews(\"" + index + "\");'>Edit</button><button class='remove' onclick='remove(\"" + index + "\");'>X</button>";
		}

		return "<div class='listitem_"+astatuses[index]+"'><input id='"+index+"' class='multiple_selection' type='checkbox' name='multiple_selection'><div class='detail_div'><div class='listitem_text'>"+atitles[index]+"</div><div class='status_div_"+astatuses[index]+"'>"+astatuses[index]+"</div><div class='pubdate'>("+apubdates[index]+")</div></div><div class='listitem_controls'>"+listitemcontrols+"</div></div>";
	}
	else{
		return "<div class='listitem_active'><input id='"+index+"' class='multiple_selection' type='checkbox' name='multiple_selection'><div class='detail_div'><div class='listitem_text' style='max-width:90%'>"+btitles[index]+"</div><div class='pubdate'>("+bpubdates[index]+")</div></div><div class='listitem_controls'><button class='info' onclick='info(\""+index+"\");'>i</button><button class='block' onclick='approve(\""+bids[index] + "\");'>Approve</button><button class='edit' onclick='editNews(\"" + bids[index] + "\");'>Edit</button><button class='remove' onclick='remove(\"" + index + "\");'>X</button></div></div>";
	}

}

function approve(id){
	console.log("approve id " + id);

	$.post('./fp_processor.php', {'uname' : username, 'action':"approve", 'id' : id }, function(result){
		console.log(result);
		fetchDataAndPopulate('./fp_processor.php');
	});
}


function rearrange(){
	enterMultipleMode("Save Arrangement", "Cancel");
	$("input.multiple_selection").css({'display':'none'});
	see_history_action = "rearrange";

	ttitles = atitles.slice();
	tstatuses = astatuses.slice();
	tsources = asources.slice();
	tids = aids.slice();
	tdescs = adescs.slice();
	tpubdates = apubdates.slice();
	timglinks = aimgLinks.slice();

	populate();
}


function move(index, direction){
	index = parseInt(index);
	if((index != 0 || direction != "up") && (index != texts.length -1 || direction != "down")){
		if(direction == "up"){
			t = atitles[index-1];	atitles[index-1] = atitles[index];	atitles[index] = t;
			t = apubdates[index-1];	apubdates[index-1] = apubdates[index];	apubdates[index] = t;
			t = asources[index-1];	asources[index-1] = asources[index];	asources[index] = t;
			t = astatuses[index-1];	astatuses[index-1] = astatuses[index];	astatuses[index] = t;
			t = aids[index-1];	aids[index-1] = aids[index];		aids[index] = t;
			t = adescs[index-1];	adescs[index-1] = adescs[index];	adescs[index] = t;
			t = aimgLinks[index-1];	aimgLinks[index-1] = aimgLinks[index];	aimgLinks[index] = t;
		}
		else if(direction == "down"){
			t = atitles[index+1];	atitles[index+1] = atitles[index];	atitles[index] = t;
			t = apubdates[index+1];	apubdates[index+1] = apubdates[index];	apubdates[index] = t;
			t = asources[index+1];	asources[index+1] = asources[index];	asources[index] = t;
			t = astatuses[index+1];	astatuses[index+1] = astatuses[index];	astatuses[index] = t;
			t = aids[index+1];	aids[index+1] = aids[index];		aids[index] = t;
			t = adescs[index+1];	adescs[index+1] = adescs[index];	adescs[index] = t;
			t = aimgLinks[index+1];	aimgLinks[index+1] = aimgLinks[index];	aimgLinks[index] = t;
		}
		populate();
	}
}

function populate(){
	count = currentTab == "active" ? atitles.length : btitles.length;
console.log("pop : " + count);
	liststring = "";

	if(count != 0){
		for(z=0; z < count; ++z){
			liststring += makeListItem(z);
		}
	}
	else{
		liststring = "There are no items to display";
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
			'uname' : username,
			'action': 'getAllActive'
		},
		error: function(xhr){
			console.log("error occured while fetching "+url+" : " + xhr.status + " : " + xhr.statusText);
		},
		success: function(result){
//			console.log(url+" successfully fetched. result : " + result);
			if(result != "none"){
				atitles = [];
				apubdates = [];
				astatuses = [];
				aids = [];
				adescs = [];
				asources = [];
				aimgLinks = [];

				var xml = $.parseXML(result);
				x1 = xml.getElementsByTagName("title");
				x2 = xml.getElementsByTagName("pubdate");
				x3 = xml.getElementsByTagName("item");
				x4 = xml.getElementsByTagName("source");
				x5 = xml.getElementsByTagName("img_link");
				x6 = xml.getElementsByTagName("desc");
				for(z=0; z< x1.length; ++z){
					atitles.push(x1[z].childNodes[0].nodeValue);
					apubdates.push(x2[z].childNodes[0].nodeValue);
					astatuses.push(x3[z].getAttribute("status"));
					aids.push(x3[z].getAttribute('id'));
					asources.push(x4[z].childNodes[0].nodeValue);
					aimgLinks.push(x5[z].childNodes[0].nodeValue);
					adescs.push(x6[z].childNodes[0].nodeValue);
				}

				populate();
			}
		}
	});

	$.ajax({
		url : url,
		type: 'POST',
		async: true,
		cache: false,
		data: {
			'uname' : username,
			'action': 'getAllCustom'
		},
		error: function(xhr){
			console.log("error occured while fetching "+url+" : " + xhr.status + " : " + xhr.statusText);
		},
		success: function(result){
			console.log(url+" successfully fetched. result : " + result);
			if(result != "none" && result != ""){
				btitles = [];
				bpubdates = [];
				bsources = [];
				bdescs = [];
				bimgLinks = [];
				bids = [];

				var xml = $.parseXML(result);
				x1 = xml.getElementsByTagName("title");
				x2 = xml.getElementsByTagName("pubdate");
				x4 = xml.getElementsByTagName("source");
				x5 = xml.getElementsByTagName("desc");
				x6 = xml.getElementsByTagName("img_link");
				x7 = xml.getElementsByTagName("id");

				for(z=0; z< x1.length; ++z){
					if(x1[z].childNodes[0])
						btitles.push(x1[z].childNodes[0].nodeValue);
					if(x2[z].childNodes[0])
						bpubdates.push(x2[z].childNodes[0].nodeValue);
					if(x4[z].childNodes[0])
						bsources.push(x4[z].childNodes[0].nodeValue);
					if(x5[z].childNodes[0])
						bdescs.push(x5[z].childNodes[0].nodeValue);
					if(x6[z].childNodes[0])
						bimgLinks.push(x6[z].childNodes[0].nodeValue);
					if(x7[z].childNodes[0])
						bids.push(x7[z].childNodes[0].nodeValue);
				}


				console.log("bigms = " + bimgLinks);
				populate();
			}
		}
	});

}

$(function(){
	changeTab('active');
	fetchDataAndPopulate('./fp_processor.php');
});

</script>
<?php include '../footer.php'; ?>
