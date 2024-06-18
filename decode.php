<?php
$host_name  = "localhost";
$port       = "3306"; 
$user_name  = "root";
$password   = "";
$database   = "restaurandb_uas_decode";

// Establish a connection to the database
$con = new mysqli($host_name, $user_name, $password, $database, $port);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

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

$api_profile = http_request("https://raw.githubusercontent.com/MahesKanoko999/curl-maheskanoko/main/api/encode_mk.json"); 

$profile = json_decode($api_profile, TRUE);

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
    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("SELECT * FROM tb_detail_pesanan WHERE id = ?");
    $stmt->bind_param("s", $id[$x]);
    $stmt->execute();
    $result = $stmt->get_result();
    $dt = $result->num_rows;
    $stmt->close();

    if ($dt == 0)
    {
        $stmt = $con->prepare("INSERT INTO tb_detail_pesanan (id, pelanggan_id, tanggal, menu_id, jumlah, total_harga, handle_karyawan) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $id[$x], $pelanggan_id[$x], $tanggal[$x], $menu_id[$x], $jumlah[$x], $total_harga[$x], $handle_karyawan[$x]);
        $stmt->execute();
        $stmt->close();
        
        echo $id[$x] . " " . $pelanggan_id[$x] . " " .$tanggal[$x]. " " .$menu_id[$x]. " " .$jumlah[$x]. " " .$total_harga[$x]. " " .$handle_karyawan[$x] . "<br>";
    }
    else
    {
        // Update query using prepared statements
        $stmt = $con->prepare("UPDATE tb_detail_pesanan SET pelanggan_id=?, tanggal=?, menu_id=?, jumlah=?, total_harga=?, handle_karyawan=? WHERE id=?");
        $stmt->bind_param("sssssss", $pelanggan_id[$x], $tanggal[$x], $menu_id[$x], $jumlah[$x], $total_harga[$x], $handle_karyawan[$x], $id[$x]);
        $stmt->execute();
        $stmt->close();
    }
}

$con->close();
?>
