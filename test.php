<?php
if (isset($_GET['q'])) {
    $data = file_get_contents("http://localhost/foodbox2/api/test.php?q=" . $_GET['q']);
    $xml = simplexml_load_string($data);
    $dizi = json_decode(json_encode($xml), true);
    //print_r($dizi);
    foreach ($dizi as $key => $value) {
        echo $key . " : " . $value . "<br>";
    }
}
?>



<form action="" method="get">
    <input type="number" name="q" required>
    <input type="submit">
</form>