<?php
	if($_POST){
		$sfile = fopen("./data/autofeeds.xml", 'r');
		$data = fread($sfile, filesize("./data/autofeeds.xml"));
		fclose($sfile);

		echo $data;
	}
?>
