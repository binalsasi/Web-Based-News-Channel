<?php
	if($_POST){
		$sfile = fopen("./data/scrolllist.xml", 'r');
		$data = fread($sfile, filesize("./data/scrolllist.xml"));
		fclose($sfile);

		echo $data;
	}
?>
