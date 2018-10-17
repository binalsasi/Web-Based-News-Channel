<?php
	$pageTitle = "Add Soe News Interface";
	$curPath = "> Add Soe News Interface";
	$uname = "dovahkin";
	$level = "master";

	include '../header.php';
	$mode = htmlspecialchars($_POST['mode']);
	$tab = htmlspecialchars($_POST['tab']);
	$id = htmlspecialchars($_POST['id']);
	$backlink = htmlspecialchars($_POST['backlink']);
?>

<div id='work_area'>
	<div id="form_box">
		<?php
			if(isset($backlink)){
		?>
				<input id='backlink' type='button' onclick="location.href='../fp/fp.php'" value='back' style='float:left;margin-right:10px;'>
		<?php
			}
		?>
		<div id="text_form">
		<?php
			if($mode === "edit")
				echo "Edit News";
			else
				echo "Add News";
		?>
		</div>
		<div id='form_input_part'>
			<div id='data_div'>
				<div id='text_title'>Title</div>
				<textarea id="inp_title" type="text" name="title" rows="2" cols="50" placeholder="Enter Title of news" ></textarea>
				<div id='text_desc'>Description</div>
				<textarea id="inp_desc" type="text" name="desc" rows="4" cols="50" placeholder="Enter a small description of the news" ></textarea>
				<div id='text_author'>Author</div>
			 	<input id="inp_author" type="text" name="author" placeholder="Enter Author Name" size='57'>
				<div id='text_pubdate'>Publish Date</div> 
				<input id="inp_pubdate" type="text" name="pubdate" placeholder="Enter Publish Date. Leave this blank to set it as current date" size='57'>
			</div>
			<div id='img_div'>
				<div id='text_img'>Background Image</div>
				<div id='imgbox'></div>
				<div id="image_response">No Image Specified</div>
				<button id="img_upload" onclick="showDialogBox('uploader');">Upload Image</button>
				or
				<button id="img_no_specify" onclick="cancelDialogBox('none');">Specify No Image</button>
			</div>
		</div>
		<button id="submit_form" onclick="submit();">Submit News</button>
	</div>

	<div id="img_upload_box">
		<text id="box_title">Image Upload</text>
		<hr>
		<form id="uploadimage" mehod="post" enctype="multipart/form-data">
			<div id="preview_box"><img id="preview" src="noimage.png" /></div>
			<hr>
			<div id="upload_response"></div>
			<hr>
			<div id="interact">
				Select Image <br>
				<input type="file" name="file" id="file"> <br> 
				<input type="submit" value="Upload" class="submit">
			</div>
		</form>
		<button id="img_upload_cancel" onclick="cancelDialogBox('uploader');">Close</button>
	</div>	
</div>





<link rel="stylesheet" type="text/css" href="ad.css"/>
<script src='../jquery-3.2.1.min.js'></script>
<script>
var mode = <?php echo "\"$mode\""; ?>;
var tab = <?php echo "\"$tab\""; ?>;
var id = <?php echo "\"$id\""; ?>;
var changed = 'n';

var title, desc, author, imgName = "none", img="n";
var username = <?php echo "\"$uname\""; ?>;
var level = <?php echo "\"$level\""; ?>;

// make the upload box appear on to the screen and make the older screen inactive while this box is visible
function showDialogBox(type){
	if(type == "uploader"){
		$("#img_upload_box").slideToggle();
		$("#form_box *").attr("disabled", true);
	}
}

// make the upload box disappear and make the older screen active again
// the second type is used to specify that no image is given (part of user control)
function cancelDialogBox(type){
	if(type == "uploader"){
		$("#img_upload_box").slideUp();
		$("#form_box *").attr("disabled", false);
	}
	else{
		imgName = "none";
		$("#image_response").text("No Image Specified");
	}
}

// submit the new news item to the processor php file
function submit(){
	title= $("#inp_title").val().trim();
	desc = $("#inp_desc").val().trim();
	author = $("#inp_author").val().trim();
	pubdate = $("#inp_pubdate").val().trim();
	if(pubdate == "")
		pubdate = 'default';
	console.log("img : " + img);
	imgName = imgName.trim();
	if( imgName == ""){
		imgName = "none";
		img = "n";
	}

	$.post("./ad_processor.php", {'action' : mode , 'uname' : username, 'title' : title, 'desc' : desc, 'author': author, 'pubdate' : pubdate, 'imgName' : imgName, 'img':img , 'id' : id , "change" : changed, 'tab' : tab }, 
		function(result){
		console.log("submit : " + result);
		alert(result);
	});
}

// when the document is ready, initialize the functions for showing preview and others
$(function(){
	console.log("MODE " + mode + " : " + tab + " : " + id);

	if(mode == "edit"){
		$.post("./ad_processor.php", {'action' : 'getFeed', 'tab' : tab, 'id' : id }, function (result){
			console.log("edit mode  " + tab + " id : " + id + " : " + result);

			
			var xml = $.parseXML(result);
			x1 = xml.getElementsByTagName("item");
			x2 = xml.getElementsByTagName("title");
			x3 = xml.getElementsByTagName("source");
			x4 = xml.getElementsByTagName("pubdate");
			x5 = xml.getElementsByTagName("desc");
			x6 = xml.getElementsByTagName("img_link");
			title = x2[0].childNodes[0].nodeValue;
			source = x3[0].childNodes[0].nodeValue;
			pubdate = x4[0].childNodes[0].nodeValue;
			desc = x5[0].childNodes[0].nodeValue;
			imglink = x6[0].childNodes[0].nodeValue;
			status = x1[0].getAttribute("status");

			$("#inp_title").val(title);
			$("#inp_desc").val(desc);
			$("#inp_author").val(source);
			$("#inp_pubdate").val(pubdate);

			$("#imgbox").html("<img id='bgimg' src='../"+imglink+"' />");
			$("#imgbox").css('display', 'block');
			imgName = imglink;
			img = 'y';

			$("#image_response").text("current : " + imgName);

			console.log("im " + imglink + " : " + status);
		});

	}

	// when submit is clicked in the upload image box
	$("#uploadimage").on('submit',(function(e) {
		e.preventDefault();
		$("#upload_response").text("Loading...");
		$.ajax({
			url: "ad_ImageUploader.php", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			success: function(data)   // A function to be called if request succeeds
			{
				$("#upload_response").html(data);
				starttag = "<text style='display:none'>";
				endtag = "</text>";
				startpath = "../../res/custom/";
				spos = data.indexOf(starttag);
				imgName = data.substring(spos + starttag.length, data.indexOf(endtag,spos));
				imgName = imgName.substring(imgName.indexOf(startpath) + startpath.length);
				console.log(imgName);
				img = "y";

				$("#imgbox").html("<img id='bgimg' src='../../res/custom/"+imgName+"' />");
				$("#imgbox").css('display', 'block');
				$("#image_response").text(imgName + " uploaded");
				if(mode == "edit")
					changed='y';
			}
		});
	}));

	// Function to preview image after validation
	$("#file").change(function() {
		console.log("image changed");

		$("#upload_response").empty(); // To remove the previous error message
		var file = this.files[0];
		var imagefile = file.type;
		var match= ["image/jpeg","image/png","image/jpg"];
		if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
		{
			$('#preview').attr('src','noimage.png');
			$("#upload_response").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
			return false;
		}
		else
		{
	console.log("image valid");
			var reader = new FileReader();
			reader.onload = imageIsLoaded;
			reader.readAsDataURL(this.files[0]);
	console.log("image previewed");
		}
	});
});

// to preview the image
function imageIsLoaded(e) {
	console.log("imageisloaded called");
	$("#file").css("color","green");
	$('#preview_box').css("display", "block");
	$('#preview').attr('src', e.target.result);
	$('#preview').attr('width', '250px');
	$('#preview').attr('height', '230px');
	console.log("imageisloaded ended");
}

</script>

<?php
	include '../footer.php';
?>
