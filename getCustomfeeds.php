<?php
	if($_POST){
		$sfile = fopen("./data/customfeeds.xml", 'r');
		$data = fread($sfile, filesize("./data/customfeeds.xml"));
		fclose($sfile);

		echo $data;
	}
?>
