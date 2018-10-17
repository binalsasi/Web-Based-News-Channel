<?php
	if($_POST){
		$id = $_POST['id'];
		$conn = mysqli_connect("localhost", "", "", "") or die("Error while connecting " + mysql_error());
		$ret = mysqli_query($conn, "select * from clients where id='$id'");
		if($ret){
			$ret = mysqli_fetch_assoc($ret);
			if($ret['seen'] === "N"){
				echo $ret['invalidate'];
				mysqli_query($conn, "update clients set seen='Y' where id='$id'");
			}
			else{
				echo "none";
			}
		}
		else{
			echo "error";
		}
		mysqli_close($conn);
	}
?>
