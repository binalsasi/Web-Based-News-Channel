<?php
	if(isset($_FILES["file"]["type"]))
	{
		$validextensions = array("jpeg", "jpg", "png");
		$temporary = explode(".", $_FILES["file"]["name"]);
		$file_extension = end($temporary);
		$dotpos = strpos($_FILES['file']['name'], ".".$file_extension);
		$name = substr($_FILES['file']['name'], 0, $dotpos);
		if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")) && ($_FILES["file"]["size"] < 5000000)//Approx. 5mb files can be uploaded.
	&& in_array($file_extension, $validextensions)) {
			if ($_FILES["file"]["error"] > 0)
			{
				echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
			}
			else
			{
//				$targetPath = "../../res/custom/".$_FILES['file']['name']; // Target path where file is to be stored

				$curdate = date('d-m-Y');
				$type = end(explode("/", $file_extension));
//				$files = glob("../../res/custom/*");
//				print_r($files);
//				sort($files);
//				print_r($files);
//				$last = end($files);
//				$number = explode("_", $last);
//				$newnumber = $number[1] + 1;
//				$targetPath = "../../res/custom/".$curdate."_".$newnumber."_".$_FILES['file']['name'];

				$targetPath = "../../res/custom/".$curdate."_".$_FILES['file']['name'];
				if(file_exists($targetPath)){
					$nn = 1;
					while(true){
						$targetPath = "../../res/custom/".$curdate."_".$name."_".$nn.".".$type;
						if(!file_exists($targetPath))
							break;
						++$nn;
					}
				}


				if(file_exists($targetPath)) {
					echo $_FILES["file"]["name"] . "already exists.";
				}
				else
				{
					$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
					if(file_exists($sourcePath)){
						move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
						if(file_exists($targetPath)){
							echo "<span id='success'>Image Uploaded Successfully...!!</span><br/>";
							echo "<br/><b>File Name:</b> " . $_FILES["file"]["name"] . "<br>";
							echo "<b>Type:</b> " . $_FILES["file"]["type"] . "<br>";
							echo "<b>Size:</b> " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
							echo "<b>Temp file:</b> " . $_FILES["file"]["tmp_name"] . "<br>";
							echo "<text style='display:none'>$targetPath</text>";
						}
						else{
							echo "$targetPath was not created";
						}
					}
					else{
						echo $sourcePath." doesnt exist";
					}
				}
			}
		}
		else
		{
			echo "<span id='invalid'>***Invalid file Size or Type***<span>";
		}
	}
?>
