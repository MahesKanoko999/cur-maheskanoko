<?php
$user_name 	= "root";
$password	= "";
$database	= "restaurandb_uas";
$host_name	= "localhost";

$con 	= mysqli_connect($host_name,$user_name,$password);
$kampus = mysqli_select_db($con, $database);

	$result 	= mysqli_query($con,"SELECT * FROM tb_detail_fasilitas");

    //create an array
    $emparray 	= array();
    while($row 	= mysqli_fetch_assoc($result))
    {
        $emparray[] = $row;
    }
	
	$jsonfile = json_encode($emparray, JSON_PRETTY_PRINT);

	file_put_contents("mahasiswa.json", $jsonfile);
?>