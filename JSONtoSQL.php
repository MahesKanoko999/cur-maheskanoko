<?php
include "koneksi.php";
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

$profile = http_request("https://websensordata.000webhostapp.com/admin/api/mahasiswa.json"); 
$profile = http_request("https://raw.githubusercontent.com/rahayuda/cURL/main/mahasiswa.json"); 

$profile= json_decode($profile, TRUE);

//print_r($profile);

$id     = array_column($profile, 'id');
$nim    = array_column($profile, 'nim');
$nama   = array_column($profile, 'nama');
$email  = array_column($profile, 'alamat');
$last   = count($id);

for ($x = 0; $x < $last; $x++) 
{
    $list   = mysqli_query($con,"select * from mahasiswa where nim = '$nim[$x]'");
    $dt     = mysqli_num_rows($list);

    if ($dt==0)
    {
        $result = mysqli_query($con, "INSERT INTO mahasiswa (nim,nama,alamat) VALUES ('$nim[$x]','$nama[$x]','$email[$x]')");
        echo $nim[$x] . " " . $nama[$x] . " " .$email[$x]."<br>";
    }

    $result = mysqli_query($con, "UPDATE mahasiswa SET nama='$nama[$x]',alamat='$email[$x]',nim='$nim[$x]' WHERE nim=$nim[$x]");
    
}

?>