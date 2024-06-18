<?php
$host_name  = "localhost";
$port       = "3306"; 
$user_name  = "root";
$password   = "";
$database   = "restaurandb_uas";

$con = mysqli_connect($host_name . ":" . $port, $user_name, $password);
$sdb = mysqli_select_db($con, $database);
?>

<br>
<h2><b>Request</b></h2>
<hr>

<a class="btn btn-primary btn-sm" href="index.php?page=up-request" role="button">Request</a>
<br><br>
<?php

function http_request($url){
    // persiapkan curl
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, $url);
    
    // set user agent    
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      

    // mengembalikan hasil curl
    return $output;
}

$api_profile = http_request("https://raw.githubusercontent.com/MahesKanoko999/curl-maheskanoko/main/api/encode_mk.json"); 

$profile= json_decode($api_profile, TRUE);

//print_r($profile);

$id     = array_column($profile, 'id');
$pelanggan_id    = array_column($profile, 'pelanggan_id');
$tanggal   = array_column($profile, 'tanggal');
$menu_id  = array_column($profile, 'menu_id');
$jumlah  = array_column($profile, 'jumlah');
$total_harga  = array_column($profile, 'total_harga');
$handle_karyawan  = array_column($profile, 'handle_karyawan');
$last   = count($id);

for ($x = 0; $x < $last; $x++) 
{
    $list   = mysqli_query($con, "SELECT * FROM tb_detail_pesanan WHERE id = '$id[$x]'");
    $dt     = mysqli_num_rows($list);

    if ($dt==0)
    {
        $result = mysqli_query($con, "INSERT INTO tb_detail_pesanan (id, pelanggan_id, tanggal, menu_id, jumlah, total_harga, handle_karyawan) VALUES ('$id[$x]', '$pelanggan_id[$x]', '$tanggal[$x]', '$menu_id[$x]', '$jumlah[$x]', '$total_harga[$x]',$handle_karyawan[$x]) ");
        echo $id[$x] . " " . $pelanggan_id[$x] . " " .$tanggal[$x]. " " .$menu_id[$x]. " " .$jumlah[$x]. " " .$total_harga[$x]. " " .$handle_karyawan[$x]. "<br>";
    }

    $result = mysqli_query($con, "UPDATE tb_detail_pesanan SET id='$id[$x]', pelanggan_id='$pelanggan_id[$x]', tanggal='$tanggal[$x]', menu_id='$menu_id[$x]', jumlah='$jumlah[$x]', total_harga='$total_harga[$x]', handle_karyawan='$handle_karyawan[$x]' WHERE id='$id[$x]'");
    
}

?>