<?php
	if($_POST){
		$sfile = fopen("./data/cnlist.xml", 'r');
		$data = fread($sfile, filesize("./data/cnlist.xml"));
		fclose($sfile);

		echo $data;
	}
?>
