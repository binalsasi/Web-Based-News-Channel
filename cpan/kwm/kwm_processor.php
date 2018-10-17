<?php
/*
	seeHistory	uname action
done	removeSingle	uname action index
done	blockSingle	uname action index
done	addSingle	uname action data
done	editSingle	uname action index data
done	blockMultiple	uname action selection
done	removeMultiple	uname action selection
*/
	$sourceFile = "../../conf/keywords.xml";

	if($_POST){
		$uname = htmlspecialchars($_POST['uname']);
		$action = htmlspecialchars($_POST['action']);

		$sfile = fopen($sourceFile, 'r');
		$inputString = fread($sfile, filesize($sourceFile));
		fclose($sfile);

		$texts = array();
		$statuses = array();

		$spos = 0;
		$epos = 0;
		while($spos = strpos($inputString, "<text>", $spos+1)){
			$epos = strpos($inputString, "</text>", $spos + 1);
			$len = $epos - $spos - 6;
			array_push($texts,substr($inputString, $spos+6, $len));
		}
		$spos = 0;
		$epos = 0;
		while($spos = strpos($inputString, "status='", $spos+1)){
			$epos = strpos($inputString, "' id=", $spos + 1);
			$len = $epos - $spos - 8;
			array_push($statuses,substr($inputString, $spos+8, $len));
		}

		function makeXMLString($texts, $statuses){
			$count = count($texts);
			$retString = "<root>";
			for($z = 0; $z < $count; ++$z){
				$retString .= "<item status='${statuses[$z]}' id='$z'>";
				$retString .= "<text>${texts[$z]}</text>";
				$retString .= "</item>";
			}
			$retString .= "</root>";
			return $retString;
		}






		if(strcmp($action, "removeSingle") == 0){
			$index = htmlspecialchars($_POST['index']);

			array_splice($texts, $index, 1);
			array_splice($statuses, $index, 1);

			$outputString = makeXMLString($texts, $statuses);

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

			$outputString = makeXMLString($texts, $statuses);

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

			$outputString = makeXMLString($texts, $statuses);

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
			
			$outputString = makeXMLString($texts, $statuses);

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


			$outputString = makeXMLString($texts, $statuses);

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
				array_splice($statuses, $selection[$z], 1);
			}

			
			$outputString = makeXMLString($texts, $statuses);

			$sfile = fopen($sourceFile, 'w');
			fwrite($sfile, $outputString);
			fclose($sfile);

			echo "Success";
			echo "$outputString";
		}
		elseif(strcmp($action, "seeHistory") == 0){
			echo "to be done";
		}
	}
?>
