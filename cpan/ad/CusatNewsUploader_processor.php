<?php
	if($_POST){
		$title = htmlspecialchars($_POST['title']);
		$desc = htmlspecialchars($_POST['desc']);
		$author = htmlspecialchars($_POST['author']);
		$imgName = htmlspecialchars($_POST['imgName']);
		$img = htmlspecialchars($_POST['img']);
		$uname = htmlspecialchars($_POST['uname']);

		if($img != 'y' && $imgName != 'none'){
			$imgLoc = "none";
			$img = 'n';
		}
		else{
			$imgLoc = "../../res/custom/".$imgName;
		}

		$conn = mysqli_connect('localhost', 'soenewsadmin', '12345678', 'soenews') or die ("try again some other time 1");
		$uid = mysqli_query("select uid from user where uname='$uname'");
		if($uid){
			$uid = mysqli_fetch_assoc($uid);
			$uid = $uid['uid'];

			$ret = mysqli_query($conn, "insert into all_news (uid, title, description, source, imgloc, img) values ('$uid', '$title', '$desc', '$author', '$imgLoc', '$img')");

		}
		if($ret){
			echo "Success";
			$iid = mysqli_fetch_assoc(mysqli_query($conn, "select id from customnews where uid='$uid' and source='$author' and imgloc='../res/custom/$img'"));
			mysqli_query($conn, "insert into user_logs (uid, action) values ('$uid', 'inserted news item #\"${iid['id']}\" into customnews')");
//TODO use failsafe error logging mechanism
		}
		else{
			echo "Try again some other time 2";
			$err = mysqli_error($conn);
			mysqli_query($conn, "insert into user_logs (uid, action) values ('$uid', 'could not insert news item into customnews, error : $err')");
		}
		mysqli_close($conn);
	}
?>
