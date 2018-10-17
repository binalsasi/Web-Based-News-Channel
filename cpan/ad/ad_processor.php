<?php
	$activeFile = "../../data/customfeeds.xml";

	$server = "localhost";
	$user = "";
	$pass = "";
	$db = "soenews";


	function makeXMLString($titles, $statuses, $pubdates, $sources, $descs, $imglinks, $domCols, $textCols, $ids){
			$count = count($titles);
			$retString = "<root>";
			if(!isset($ids) || $ids === false || count($ids) === 0){
				$ids = false;
			}
			if(!isset($domCols) || $domCols === false){
				$domCols = array_fill(0, $count, "0,0,0");
			}
			if(!isset($textCols) || $textCols === false){
				$textCols = array_fill(0, $count, "black");
			}
			for($z = 0; $z < $count; ++$z){
				$retString .= "<item status='${statuses[$z]}' id='$z'>";
				if($ids)
					$retString .= "<id>${ids[$z]}</id>";
				$retString .= "<title>${titles[$z]}</title>";
				$retString .= "<pubdate>${pubdates[$z]}</pubdate>";
				$retString .= "<source>${sources[$z]}</source>";
				$retString .= "<desc>${descs[$z]}</desc>";
				$retString .= "<img_link dominantColor='${domCols[$z]}' textColor='${textCols[$z]}'>${imglinks[$z]}</img_link>";
				$retString .= "</item>";
			}
			$retString .= "</root>";
			return $retString;
	}

	function populateActiveData($activeXml, &$atitles, &$astatuses, &$asources, &$apubdates, &$adescs, &$aimglinks, &$adomCols, &$atextCols){
		$spos = 0;
		$epos = 0;
		while($spos = strpos($activeXml, "<title>", $spos+1)){
			$epos = strpos($activeXml, "</title>", $spos + 1);
			$len = $epos - $spos - 7;
			array_push($atitles,substr($activeXml, $spos+7, $len));
		}
		$spos = 0;
		$epos = 0;
		while($spos = strpos($activeXml, "<pubdate>", $spos+1)){
			$epos = strpos($activeXml, "</pubdate>", $spos + 1);
			$len = $epos - $spos - 9;
			array_push($apubdates,substr($activeXml, $spos+9, $len));
		}
		$spos = 0;
		$epos = 0;
		while($spos = strpos($activeXml, "<source>", $spos+1)){
			$epos = strpos($activeXml, "</source>", $spos + 1);
			$len = $epos - $spos - 8;
			array_push($asources,substr($activeXml, $spos+8, $len));
		}
		$spos = 0;
		$epos = 0;
		while($spos = strpos($activeXml, "<desc>", $spos+1)){
			$epos = strpos($activeXml, "</desc>", $spos + 1);
			$len = $epos - $spos - 6;
			array_push($adescs,substr($activeXml, $spos+6, $len));
		}
		$spos = 0;
		$epos = 0;
		while($spos = strpos($activeXml, "dominantColor='", $spos+1)){
			$epos = strpos($activeXml, "' textColor='", $spos + 1);
			$len = $epos - $spos - 15;
			array_push($adomCols,substr($activeXml, $spos+15, $len));
		}
		$spos = 0;
		$epos = 0;
		$eepos = 0;
		while($spos = strpos($activeXml, "textColor='", $spos+1)){
			$epos = strpos($activeXml, "'>", $spos + 1);
			$len = $epos - $spos - 11;
			array_push($atextCols,substr($activeXml, $spos+11, $len));
			$eepos = strpos($activeXml, "</img_link>", $epos+1);
			$len = $eepos - $epos - 2;
			array_push($aimglinks, substr($activeXml, $epos + 2, $len));
		}

		$spos = 0;
		$epos = 0;
		while($spos = strpos($activeXml, "status='", $spos+1)){
			$epos = strpos($activeXml, "' id=", $spos + 1);
			$len = $epos - $spos - 8;
			array_push($astatuses,substr($activeXml, $spos+8, $len));
		}
	}



	if($_POST){

		$action = htmlspecialchars($_POST['action']);

		if($action === "add"){

			$title = htmlspecialchars($_POST['title']);
			$desc = htmlspecialchars($_POST['desc']);
			$author = htmlspecialchars($_POST['author']);
			$imgName = htmlspecialchars($_POST['imgName']);
			$uname = htmlspecialchars($_POST['uname']);
			$img = htmlspecialchars($_POST['img']);
			$pubdate = htmlspecialchars($_POST['pubdate']);
			if($pubdate === "default" || $pubdate === ""){
				$pubdate = date("d-m-Y");
			}

			if(($img == 'y' && $imgName == 'none') || $img == 'n'){
				$imgLoc = "none";
				$img = 'n';
			}
			else{
				$imgLoc = "../res/custom/".$imgName;
			}

			$conn = mysqli_connect('localhost', 'soenewsadmin', '12345678', 'soenews') or die ("try again some other time 1");
			$uid = mysqli_query($conn, "select uid from user where uname='$uname'");
			if($uid){
				$uid = mysqli_fetch_assoc($uid);
				$uid = $uid['uid'];

				$ret = mysqli_query($conn, "insert into all_news (uid, title, description, source, pubdate, imgloc, img) values ('$uid', '$title', '$desc', '$author', '$pubdate', '$imgLoc', '$img')");
				if($ret){
					echo "Success";
/*
				$iid = mysqli_fetch_assoc(mysqli_query($conn, "select id from customnews where uid='$uid' and source='$author' and imgloc='../res/custom/$img'"));
				mysqli_query($conn, "insert into user_logs (uid, action) values ('$uid', 'inserted news item #\"${iid['id']}\" into customnews')");
*/
				}
				else{
					echo "Try again some other time 2";
					$err = mysqli_error($conn);
					echo $err;
//					mysqli_query($conn, "insert into user_logs (uid, action) values ('$uid', 'could not insert news item into customnews, error : $err')");
				}
			}
	//TODO use failsafe error logging mechanism
			mysqli_close($conn);
		}
		else if($action === "edit"){
			$uname = htmlspecialchars($_POST['uname']);
			$title = htmlspecialchars($_POST['title']);
			$desc  = htmlspecialchars($_POST['desc']);
			$source = htmlspecialchars($_POST['author']);
			$pubdate = htmlspecialchars($_POST['pubdate']);
			$imgname = htmlspecialchars($_POST['imgName']);
			$img = htmlspecialchars($_POST['img']);
			$id = htmlspecialchars($_POST['id']);
			$changed = htmlspecialchars($_POST['change']);
			$tab = htmlspecialchars($_POST['tab']);
			if($changed === "y")
				$imgname = "../res/custom/".$imgname;

			if($tab === "active"){			

				$writeitem = "<item><title>title</title><desc>desc</desc><source>source</source><img_link>$imgname</img_link></item>";


				$sfile = fopen("./temp.xml", 'w');
				fwrite($sfile, $writeitem);
				fclose($sfile);

				$op = shell_exec("python ./size_filter.py ./temp.xml 1024 720");
				if(strpos($op, "error") == -1){
					echo $op;
				}
				else{
					$sfile = fopen("./temp.xml_processed.xml", 'r');
					$newitem = fread($sfile, filesize("./temp.xml_processed.xml"));
					fclose($sfile);

					$spos = strpos($newitem, "dominantColor=\"", 1);
					$epos = strpos($newitem, "\" textColor=\"", $spos + 1);
					$len = $epos - $spos - 15;
					$domCol = substr($newitem, $spos+15, $len);

					$eepos = strpos($newitem, "\">", $epos + 1);
					$len = $eepos - $epos - 13;
					$textCol = substr($newitem, $epos+13, $len);
					$epos = strpos($newitem, "</img_link>", $eepos+1);
					$len = $epos - $eepos - 2;
					$imglink = substr($newitem, $eepos + 2, $len);


					echo "ASD : " . $domCol . " DSA " . $textCol . " AA " . $imglink."\n";


				$atitles = array();
				$astatuses = array();
				$apubdates = array();
				$asources = array();
				$adescs = array();
				$aimglinks = array();
				$adomCols = array();
				$atextCols = array();


				$sfile = fopen($activeFile, 'r');
				$activeXml = fread($sfile, filesize($activeFile));
				fclose($sfile);

				populateActiveData($activeXml, $atitles, $astatuses, $asources, $apubdates, $adescs, $aimglinks, $adomCols, $atextCols);

				$atitles[$id] = $title;
				$adescs[$id] = $desc;
				$apubdates[$id] = $pubdate;
				$asources[$id] = $source;
				$aimglinks[$id] = $imgname;
				$adomCols[$id] = "0,0,0";//$domCol;
				$atextCols[$id] = "black";//$textCol;

				$xml = makeXMLString($atitles, $astatuses, $apubdates, $asources, $adescs, $aimglinks, $adomCols, $atextCols);

				$sfile = fopen($activeFile, 'w');
				fwrite($sfile, $xml);
				fclose($sfile);

//				echo $xml;

				}

			}
			elseif($tab === "allcustom"){
				$conn = mysqli_connect($server, $user, $pass, $db) or die("Error while connecting " .mysqli_error());
				$ret = mysqli_query($conn, "update all_news set title='$title', description='$desc', source='$source', imgloc='$imgname', img='$img', pubdate='$pubdate' where id=$id");
				if($ret){
					echo "Success";
				}
				else{
					echo "here".mysqli_error($conn);
				}
				mysqli_close($conn);
			}

		}
		else if($action === "getFeed"){
			$tab = htmlspecialchars($_POST['tab']);
			$id = htmlspecialchars($_POST['id']);

			if($tab === "active"){

				$atitles = array();
				$astatuses = array();
				$apubdates = array();
				$asources = array();
				$adescs = array();
				$aimglinks = array();
				$adomCols = array();
				$atextCols = array();


				$sfile = fopen($activeFile, 'r');
				$activeXml = fread($sfile, filesize($activeFile));
				fclose($sfile);

				populateActiveData($activeXml, $atitles, $astatuses, $asources, $apubdates, $adescs, $aimglinks, $adomCols, $atextCols);

				$data = "<item status='${astatuses[$id]}'><title>${atitles[$id]}</title><source>${asources[$id]}</source><pubdate>${apubdates[$id]}</pubdate><desc>${adescs[$id]}</desc><img_link dominantColor='${adomCols[$id]}' textColor='${atextCols[$id]}'>${aimglinks[$id]}</img_link></item>";
				echo $data;

			}
			elseif($tab === "allcustom"){
				$conn = mysqli_connect($server, $user, $pass, $db) or die("Error while connecting " .mysqli_error());

				$ret = mysqli_query($conn, "select * from all_news where id = '$id'");
				if($ret){
					$ret = mysqli_fetch_assoc($ret);
					$xml = "<item status='indb'><title>${ret['title']}</title><source>${ret['source']}</source><pubdate>${ret['pubdate']}</pubdate><desc>${ret['description']}</desc><img_link dominantColor='0,0,0' textColor='black'>${ret[imgloc]}</img_link></item>";

					echo "$xml";
				}
				else{
					echo "none";
				// log error
				}
				mysqli_close($conn);
			}

		}
	}
?>
