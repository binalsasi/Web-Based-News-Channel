<?php
/*
	seeHistory	uname action
done	removeSingle	uname action index
done	blockSingle	uname action index
done	addSingle	uname action data
done	editSingle	uname action index data
done	blockMultiple	uname action selection
done	removeMultiple	uname action selection
done	rearrange	uname action newOrder
done	changeSeperator	uname action data
*/
	$sourceFile = "../../data/scrolllist.xml";

	if($_POST){
		$uname = htmlspecialchars($_POST['uname']);
		$action = htmlspecialchars($_POST['action']);

		$sfile = fopen($sourceFile, 'r');
		$inputString = fread($sfile, filesize($sourceFile));
		fclose($sfile);

		$texts = array();
		$statuses = array();
		$pubdates = array();
		$sources = array();
		$seperator = "&amp;diams;";

		$spos = 0;
		$epos = 0;
		while($spos = strpos($inputString, "<text>", $spos+1)){
			$epos = strpos($inputString, "</text>", $spos + 1);
			$len = $epos - $spos - 6;
			array_push($texts,substr($inputString, $spos+6, $len));
		}
		$spos = 0;
		$epos = 0;
		while($spos = strpos($inputString, "<pubdate>", $spos+1)){
			$epos = strpos($inputString, "</pubdate>", $spos + 1);
			$len = $epos - $spos - 9;
			array_push($pubdates,substr($inputString, $spos+9, $len));
		}
		$spos = 0;
		$epos = 0;
		while($spos = strpos($inputString, "<source>", $spos+1)){
			$epos = strpos($inputString, "</source>", $spos + 1);
			$len = $epos - $spos - 8;
			array_push($sources,substr($inputString, $spos+8, $len));
		}
		$spos = 0;
		$epos = 0;
		while($spos = strpos($inputString, "status='", $spos+1)){
			$epos = strpos($inputString, "' id=", $spos + 1);
			$len = $epos - $spos - 8;
			array_push($statuses,substr($inputString, $spos+8, $len));
		}
		$spos = 0;
		$spos = strpos($inputString, "seperator='", $spos + 1);
		$epos = strpos($inputString, "'>", $spos+1);

		$x = substr($inputString, $spos + 11, $epos - $spos - 11);
		if(strlen($x) != 0)
			$seperator = $x;

		function makeXMLString($texts, $statuses, $pubdates, $sources, $seperator){
			$count = count($texts);
			$retString = "<root seperator='$seperator'>";
			for($z = 0; $z < $count; ++$z){
				$retString .= "<item status='${statuses[$z]}' id='$z'>";
				$retString .= "<text>${texts[$z]}</text>";
				$retString .= "<pubdate>${pubdates[$z]}</pubdate>";
				$retString .= "<source>${sources[$z]}</source>";
				$retString .= "</item>";
			}
			$retString .= "</root>";
			return $retString;
		}






		if(strcmp($action, "removeSingle") == 0){
			$index = htmlspecialchars($_POST['index']);

			array_splice($texts, $index, 1);
			array_splice($pubdates, $index, 1);
			array_splice($statuses, $index, 1);
			array_splice($sources, $index, 1);

			$outputString = makeXMLString($texts, $statuses, $pubdates, $sources, $seperator);

			$sfile = fopen($sourceFile, 'w');
			fwrite($sfile, $outputString);
			fclose($sfile);

			echo "Success";
			echo "$outputString";

		}
		elseif(strcmp($action, "blockSingle") == 0){
			$index = htmlspecialchars($_POST['index']);

			if(strcmp($statuses[$index], "active") == 0){
				$statuses[$index] = "blocked";
			}
			elseif(strcmp($statuses[$index], "blocked") == 0){
				$statuses[$index] = "active";
			}

			$outputString = makeXMLString($texts, $statuses, $pubdates, $sources, $seperator);

			$sfile = fopen($sourceFile, 'w');
			fwrite($sfile, $outputString);
			fclose($sfile);

			echo "Success";
			echo "$outputString";
		}
		elseif(strcmp($action, "addSingle") == 0){
			$data = htmlspecialchars($_POST['data']);

			array_unshift($texts, $data);
			array_unshift($statuses, "active");
			array_unshift($sources, $uname);
			array_unshift($pubdates, date('d-m-Y', time()));

			$outputString = makeXMLString($texts, $statuses, $pubdates, $sources, $seperator);

			$sfile = fopen($sourceFile, 'w');
			fwrite($sfile, $outputString);
			fclose($sfile);

			echo "Success";
			echo "$outputString";
		}
		elseif(strcmp($action, "editSingle") == 0){
			$index = htmlspecialchars($_POST['index']);
			$data = htmlspecialchars($_POST['data']);

			$texts[$index] = $data;
			
			$outputString = makeXMLString($texts, $statuses, $pubdates, $sources, $seperator);

			$sfile = fopen($sourceFile, 'w');
			fwrite($sfile, $outputString);
			fclose($sfile);

			echo "Success";
			echo "$outputString";

		}
		elseif(strcmp($action, "blockMultiple") == 0){
			$selection = htmlspecialchars($_POST['selection']);
			$selection = explode(",", $selection);
			$count = count($selection);
			for($z = 0; $z < $count; ++$z){
				if(strcmp($statuses[$selection[$z]], "active") == 0){
					$statuses[$selection[$z]] = "blocked";
				}
				elseif(strcmp($statuses[$selection[$z]], "blocked") == 0){
					$statuses[$selection[$z]] = "active";
				}
			}


			$outputString = makeXMLString($texts, $statuses, $pubdates, $sources, $seperator);

			$sfile = fopen($sourceFile, 'w');
			fwrite($sfile, $outputString);
			fclose($sfile);

			echo "Success";
			echo "$outputString";
		}
		elseif(strcmp($action, "removeMultiple") == 0){
			$selection = htmlspecialchars($_POST['selection']);
			$selection = explode(",", $selection);
			$count = count($selection);

			for($z = $count -1; $z >= 0; --$z){
				array_splice($texts, $selection[$z], 1);
				array_splice($pubdates, $selection[$z], 1);
				array_splice($statuses, $selection[$z], 1);
				array_splice($sources, $selection[$z], 1);
			}

			
			$outputString = makeXMLString($texts, $statuses, $pubdates, $sources, $seperator);

			$sfile = fopen($sourceFile, 'w');
			fwrite($sfile, $outputString);
			fclose($sfile);

			echo "Success";
			echo "$outputString";
		}
		elseif(strcmp($action, "rearrange") == 0){
			$newOrder = htmlspecialchars($_POST['newOrder']);
			$newOrder = explode(",", $newOrder);
			$count = count($newOrder);

			$ttexts = array();
			$tstatuses = array();
			$tpubdates = array();
			$tsources = array();

			for($z = 0; $z < $count; ++ $z){
				$ttexts[$z] = $texts[$newOrder[$z]];
				$tpubdates[$z] = $pubdates[$newOrder[$z]];
				$tstatuses[$z] = $statuses[$newOrder[$z]];
				$tsources[$z] = $sources[$newOrder[$z]];
			}

			$outputString = makeXMLString($ttexts, $tstatuses, $tpubdates, $tsources, $seperator);

			$sfile = fopen($sourceFile, 'w');
			fwrite($sfile, $outputString);
			fclose($sfile);

			echo "Success";
			echo "$outputString";
		}
		elseif(strcmp($action, "seeHistory") == 0){
			echo "to be done";
		}
		elseif(strcmp($action, "changeSeperator") == 0){
			$newSeperator = htmlspecialchars($_POST['data']);

			
			$outputString = makeXMLString($texts, $statuses, $pubdates, $sources, $newSeperator);

			$sfile = fopen($sourceFile, 'w');
			fwrite($sfile, $outputString);
			fclose($sfile);

			echo "Success";
			echo "$outputString";
		}
	}
?>
