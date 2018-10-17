<?php
		$texts = array();
		$statuses = array();
		$pubdates = array();
		$sources = array();

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

?>
