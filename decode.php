<?php
$host_name  = "localhost";
$port       = "3306"; 
$user_name  = "root";
$password   = "";
$database   = "restaurantdb_uas";

$con = mysqli_connect($host_name . ":" . $port, $user_name, $password);
$sdb = mysqli_select_db($con, $database);
?>

<h2><b>Request</b></h2>
<hr>

<a class="btn btn-primary btn-sm" href="decode.php" role="button">Request</a>
<br><br>

<?php

function http_request($url)
{
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $output = curl_exec($ch); 
    curl_close($ch);      
    return $output;
}

$profile = http_request("https://raw.githubusercontent.com/rahayuda/cURL/main/mahasiswa.json"); 

$profile = json_decode($profile, TRUE);

$id     = array_column($profile, 'id');
$nim    = array_column($profile, 'nim');
$nama   = array_column($profile, 'nama');
$email  = array_column($profile, 'alamat');
$last   = count($id);

for ($x = 0; $x < $last; $x++) 
{
    $list   = mysqli_query($con,"SELECT * FROM mahasiswa WHERE nim = '$nim[$x]'");
    $dt     = mysqli_num_rows($list);

    if ($dt == 0)
    {
        $result = mysqli_query($con, "INSERT INTO mahasiswa (nim, nama, alamat) VALUES ('$nim[$x]', '$nama[$x]', '$email[$x]')");
        echo $nim[$x] . " " . $nama[$x] . " " .$email[$x]."<br>";
    }
    else
    {
        $result = mysqli_query($con, "UPDATE mahasiswa SET nama='$nama[$x]', alamat='$email[$x]' WHERE nim='$nim[$x]'");
    }
}

mysqli_close($con);
?>