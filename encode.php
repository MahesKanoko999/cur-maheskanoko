<?php
$host_name  = "localhost";
$port       = "3306"; 
$user_name  = "root";
$password   = "";
$database   = "restaurandb_uas";

$con = mysqli_connect($host_name . ":" . $port, $user_name, $password);
$sdb = mysqli_select_db($con, $database);

$result = mysqli_query($con, "SELECT * FROM tb_detail_pesanan");

$emparray = array();
while ($row = mysqli_fetch_assoc($result)) {
	$emparray[] = $row;
}

$jsonfile = json_encode($emparray, JSON_PRETTY_PRINT);
file_put_contents("api/encode_mk.json", $jsonfile);

mysqli_close($con);
?>