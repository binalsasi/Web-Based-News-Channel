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
		$uname = htmlspecialchars($_POST['uname']);
		$action = htmlspecialchars($_POST['action']);
		

		$sfile = fopen($activeFile, 'r');
		$activeXml = fread($sfile, filesize($activeFile));
		fclose($sfile);

		$atitles = array();
		$astatuses = array();
		$apubdates = array();
		$asources = array();
		$adescs = array();
		$aimglinks = array();
		$adomCols = array();
		$atextCols = array();

		if(strcmp($action, "getAllActive") == 0){
			echo $activeXml;
		}
		elseif(strcmp($action, "getAllCustom") == 0){
			$conn = mysqli_connect($server, $user, $pass, $db) or die("Error while connecting " .mysqli_error());

			$ret = mysqli_query($conn, "select * from all_news where deleted = 'n' order by timeofcreation desc");
			if($ret){
				$btitles = array();
				$bsources = array();
				$bstatuses = array();
				$bpubdates = array();
				$bdescs = array();
				$bimglinks = array();
				$bids = array();

				while($row = mysqli_fetch_assoc($ret)){
					array_push($btitles, $row['title']);
					array_push($bsources, $row['source']);
					array_push($bimglinks, $row['imgloc']);
					array_push($bpubdates, $row['pubdate']);
					array_push($bdescs, $row['description']);
					array_push($bstatuses, "active");
					array_push($bids, $row['id']);
				}
				$xml = makeXMLString($btitles, $bstatuses, $bpubdates, $bsources, $bdescs, $bimglinks, false, false, $bids);

				echo "$xml";
			}
			else{
				echo "none";
			// log error
			}
		}
		elseif(strcmp($action, "blockSingle") == 0){
			$index = htmlspecialchars($_POST['index']);
			populateActiveData($activeXml, $atitles, $astatuses, $asources, $apubdates, $adescs, $aimglinks, $adomCols, $atextCols);

			if(strcmp($astatuses[$index], "active") == 0){
				$astatuses[$index] = "blocked";
			}
			elseif(strcmp($astatuses[$index], "blocked") == 0){
				$astatuses[$index] = "active";
			}

			$xml = makeXMLString($atitles, $astatuses, $apubdates, $asources, $adescs, $aimglinks, $adomCols, $atextCols);

			$sfile = fopen($activeFile, 'w');
			fwrite($sfile, $xml);
			fclose($sfile);

			echo "success";
		}
		elseif(strcmp($action, "blockMultiple") == 0){
			$selection = htmlspecialchars($_POST['selection']);
			$selection = explode(",", $selection);
			$count = count($selection);

			populateActiveData($activeXml, $atitles, $astatuses, $asources, $apubdates, $adescs, $aimglinks, $adomCols, $atextCols);

			for($z = 0; $z < $count; ++$z){
				if(strcmp($astatuses[$selection[$z]], "active") == 0){
					$astatuses[$selection[$z]] = "blocked";
				}
				elseif(strcmp($astatuses[$selection[$z]], "blocked") == 0){
					$astatuses[$selection[$z]] = "active";
				}
			}


			$xml = makeXMLString($atitles, $astatuses, $apubdates, $asources, $adescs, $aimglinks, $adomCols, $atextCols);

			$sfile = fopen($activeFile, 'w');
			fwrite($sfile, $xml);
			fclose($sfile);

			echo "Success";

		}
		elseif(strcmp($action, "rearrange") == 0){
			$newOrder = htmlspecialchars($_POST['newOrder']);
			$newOrder = explode(",", $newOrder);
			$count = count($newOrder);

			populateActiveData($activeXml, $atitles, $astatuses, $asources, $apubdates, $adescs, $aimglinks, $adomCols, $atextCols);

			$ttitles = array();
			$tstatuses = array();
			$tpubdates = array();
			$tsources = array();
			$tdescs = array();
			$timglinks = array();
			$tdomCols = array();
			$ttextCols = array();

			for($z = 0; $z < $count; ++ $z){
				$ttitles[$z] = $atitles[$newOrder[$z]];
				$tpubdates[$z] = $apubdates[$newOrder[$z]];
				$tstatuses[$z] = $astatuses[$newOrder[$z]];
				$tsources[$z] = $asources[$newOrder[$z]];
				$tdescs[$z] = $adescs[$newOrder[$z]];
				$timglinks[$z] = $aimglinks[$newOrder[$z]];
				$tdomCols[$z] = $adomCols[$newOrder[$z]];
				$ttextCols[$z] = $atextCols[$newOrder[$z]];
			}

			$xml = makeXMLString($ttitles, $tstatuses, $tpubdates, $tsources, $tdescs, $timglinks, $tdomCols, $ttextCols);

			$sfile = fopen($activeFile, 'w');
			fwrite($sfile, $xml);
			fclose($sfile);

			echo "Success";
		}
		elseif(strcmp($action, "removeSingle_active") == 0){
			$index = htmlspecialchars($_POST['index']);

			populateActiveData($activeXml, $atitles, $astatuses, $asources, $apubdates, $adescs, $aimglinks, $adomCols, $atextCols);

			array_splice($atitles, $index, 1);
			array_splice($astatuses, $index, 1);
			array_splice($asources, $index, 1);
			array_splice($apubdates, $index, 1);
			array_splice($adescs, $index, 1);
			array_splice($aimglinks, $index, 1);
			array_splice($adomCols, $index, 1);
			array_splice($atextCols, $index, 1);

			$xml = makeXMLString($atitles, $astatuses, $apubdates, $asources, $adescs, $aimglinks, $adomCols, $atextCols);

			$sfile = fopen($activeFile, 'w');
			fwrite($sfile, $xml);
			fclose($sfile);

			echo "Success";
			
		}
		elseif(strcmp($action, "removeSingle_allcustom") == 0){
			$id = htmlspecialchars($_POST['index']);

			$conn = mysqli_connect($server, $user, $pass, $db) or die("Error while connecting " .mysqli_error());

			$ret = mysqli_query($conn , "update all_news set deleted='y' where id='$id'");
			if($ret)
				echo "success";
			else
				echo "Something went wrong while performing that operation. error : " . mysqli_error($conn);
			mysqli_close($conn);
		}
		elseif(strcmp($action, "removeMultiple_active") == 0){
			$selection = htmlspecialchars($_POST['selection']);
			$selection = explode(",", $selection);
			$count = count($selection);

			populateActiveData($activeXml, $atitles, $astatuses, $asources, $apubdates, $adescs, $aimglinks, $adomCols, $atextCols);

			for($z = $count -1; $z >= 0; --$z){
				array_splice($atitles, $selection[$z], 1);
				array_splice($astatuses, $selection[$z], 1);
				array_splice($asources, $selection[$z], 1);
				array_splice($apubdates, $selection[$z], 1);
				array_splice($adescs, $selection[$z], 1);
				array_splice($aimglinks, $selection[$z], 1);
				array_splice($adomCols, $selection[$z], 1);
				array_splice($atextCols, $selection[$z], 1);
			}

			$xml = makeXMLString($atitles, $astatuses, $apubdates, $asources, $adescs, $aimglinks, $adomCols, $atextCols);

			$sfile = fopen($activeFile, 'w');
			fwrite($sfile, $xml);
			fclose($sfile);

			echo "Success";
		}
		elseif(strcmp($action, "removeMultiple_allcustom") == 0){
			$selection = htmlspecialchars($_POST['selection']);

			$conn = mysqli_connect($server, $user, $pass, $db) or die("Error while connecting " .mysqli_error());

			$ret = mysqli_query($conn , "update all_news set deleted='y' where id in ($selection)");
			if($ret)
				echo "success";
			else
				echo "Something went wrong while performing that operation. error : " . mysqli_error($conn);

			mysqli_close($conn);
		}
		elseif($action === "approve"){

			$id = htmlspecialchars($_POST['id']);

			$conn = mysqli_connect($server, $user, $pass, $db) or die("Error while connecting " .mysqli_error());

			$ret = mysqli_query($conn , "select * from all_news where id='$id'");
			if($ret){
				$ret = mysqli_fetch_assoc($ret);

				populateActiveData($activeXml, $atitles, $astatuses, $asources, $apubdates, $adescs, $aimglinks, $adomCols, $atextCols);

				$writeitem = "<item><title>title</title><desc>title</desc><source>placeholder</source><img_link>${ret['imgloc']}</img_link></item>";
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

				array_unshift($atitles, $ret['title']);
				array_unshift($astatuses, "active");
				array_unshift($asources, $ret['source']);
				array_unshift($apubdates, $ret['pubdate']);
				array_unshift($adescs, $ret['description']);
				array_unshift($aimglinks, $imglink);
				array_unshift($adomCols, $domCol);
				array_unshift($atextCols, $textCol);

				$xml = makeXMLString($atitles, $astatuses, $apubdates, $asources, $adescs, $aimglinks, $adomCols, $atextCols);

				$sfile = fopen($activeFile, 'w');
				fwrite($sfile, $xml);
				fclose($sfile);
				echo "success";

				}

			}
			else
				echo "Something went wrong while performing that operation. error : " . mysqli_error($conn);

		}
	}
?>
