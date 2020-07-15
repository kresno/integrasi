<?php
$connection = mysqli_connect("localhost", "root", "", "perubahan2020");

// Check connection
$sqlProgram ="SELECT id, nama FROM program";
$queryProgram = mysqli_query($connection, $sqlProgram);

$DataProgram = array();
while($dataPrg = mysqli_fetch_array($queryProgram)){
    $x['id_prog'] = $dataPrg['id'];
    $x['nama_prog'] = $dataPrg['nama'];
    $x['Kegiatan'] = array();


    $sqlKegiatan= "SELECT id, nama FROM kegiatan WHERE program_id='".$dataPrg['id']."'";
    $queryKegiatan = mysqli_query($connection, $sqlKegiatan);

    while($dataKegiatan=mysqli_fetch_array($queryKegiatan)){
        $y['id_kegiatan'] =$dataKegiatan['id'];
        $y['nama_kegiatan'] =$dataKegiatan['nama'];

        array_push($x['Kegiatan'], $y);
    }
    array_push($DataProgram, $x);
}
echo json_encode($DataProgram);
?> 