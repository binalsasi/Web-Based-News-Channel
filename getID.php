<?php
//TODO fix concurrency problem
$sfile = fopen("nextID.txt", 'r');
$id = fread($sfile, filesize("nextID.txt"));
fclose($sfile);
$id = (int)$id;
$conn = mysqli_connect("localhost", "pidisplayadmin", "12345678", "pidisplay");
$ret = mysqli_query($conn, "insert into clients (id) values ('$id')");
if($ret){
	echo $id;
	$id++;
	$silfe = fopen("nextID.txt", 'w');
	fwrite($sfile, $id);
	fclose($sfile);
}
else{
	echo "";
}
mysqli_close($conn);
?>
