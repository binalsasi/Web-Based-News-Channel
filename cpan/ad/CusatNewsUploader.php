<?php
	$pageTitle = "Add Soe News Interface";
	$curPath = "> Add Soe News Interface";
	$uname = "dovahkin";
	$level = "master";

	include '../header.php';
?>



















<link rel="stylesheet" type="text/css" href="ad.css"/>
<script>
var title, desc, author, img = "none", imgType="none";
var uid = <?php echo "$auth"; ?>;

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
		img = "none";
		$("#image_response").text("No Image Specified");
	}
}

// submit the new news item to the processor php file
function submit(){
	title= $("#inp_title").val();
	desc = $("#inp_desc").val();
	author = $("#inp_author").val();
	console.log("img : " + img);
	if( img == "" || img.trim() == ""){
		img = "none";
		imgType = "none";
	}
	if( title == "" || title.trim() == "" || desc == "" || desc.trim() == "" || author == "" || author.trim() == ""){
		alert("Title, Description and Author must not be empty!");
	}
	else{
		$.post("./CusatNewsUploader_processor.php", {'uid' : uid, 'title' : title, 'desc' : desc, 'author': author, 'img' : img, 'imgType':imgType }, function(result){
			alert(result);
		});
	}
}

// when the document is ready, initialize the functions for showing preview and others
$(function(){
	$("#img_upload_box").slideUp(1);

	// when submit is clicked in the upload image box
	$("#uploadimage").on('submit',(function(e) {
		e.preventDefault();
		$("#upload_response").text("Loading...");
		$.ajax({
			url: "CusatNewsImageUploader.php", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			success: function(data)   // A function to be called if request succeeds
			{
				$("#upload_response").html(data);
				img = $("#file").val().replace(/C:\\fakepath\\/i,'');
				imgType = "upload";
				$("#image_response").text(img + " uploaded");
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

<div id='work_area'>
	<div id="form_box">
		<div id="text_form">Add A News</div>
		<div id='form_input_part'>
			<div id='data_div'>
				<div id='text_title'>Title</div>
				<textarea id="inp_title" type="text" name="title" rows="2" cols="50" placeholder="Enter Title of news" ></textarea>
				<div id='text_desc'>Description</div>
				<textarea id="inp_desc" type="text" name="desc" rows="4" cols="50" placeholder="Enter a small description of the news" ></textarea>
				<div id='text_author'>Author</div>
				 <input id="inp_author" type="text" name="author" placeholder="Enter Author Name" > 
			</div>
			<div id='img_div'>
				<div id='text_img'>Background Image</div> <div id="image_response">No Image Specified</div>
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

<?php
	include '../footer.php';
?>
