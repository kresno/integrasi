<?php
$connection = mysqli_connect("localhost", "root", "", "perubahan2020");

$DataRenstra = array();
$DataRenstra = array("kodepemda"=>"3202", "tahunmulai"=>"2016", "tahunselesai"=>"2021");

$sql;


echo json_encode($DataRenstra);
?>