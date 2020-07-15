<?php
//Bidang Urusan Kesehatan
$base_bidang_urusan_id = 31;

//Koneksi dengan database
$connection = mysqli_connect("localhost", "root", "", "rkpd2021");

$DataRenstra = array();
// $DataRenstra = array("kodepemda"=>"3202", "tahunmulai"=>"2016", "tahunselesai"=>"2021");

$sqlSKPD = "SELECT d.nama as urusan_nama, c.id as bidang_urusan_id, c.kode as bidang_urusan_kode, c.nama as bidang_urusan_nama, b.id as opd_id, b.kode as opd_kode, b.nama as opd_nama FROM bidang_urusan_opd a JOIN opd b on a.opd_id=b.id JOIN bidang_urusan c on c.id=a.bidang_urusan_id JOIN urusan d ON c.urusan_id=d.id WHERE a.bidang_urusan_id='".$base_bidang_urusan_id."'";
$querySKPD = mysqli_query($connection, $sqlSKPD);

while($dataSKPD = mysqli_fetch_array($querySKPD)){
    $a['kodepemda'] = "3202";
    $a['tahunmulai'] = "2016";
    $a['tahunselesai'] = "2021";
    $a['kodebidang'] = $dataSKPD['bidang_urusan_kode'];
    $a['uraibidang'] = $dataSKPD['bidang_urusan_nama'];
    $a['kodeskpd'] = $dataSKPD['opd_kode'];
    $a['uraiskpd'] = $dataSKPD['opd_nama'];
    $a['pejabat'] = array();
    $a['pilihanbidang'] = array();
    $a['uraiurusan'] = $dataSKPD['urusan_nama'];
    $a['program'] = array();

    //ngambil pejabat
    $sqlPejabat = "SELECT nip, nama, jabatan, pangkat FROM pejabat WHERE opd_id='".$dataSKPD['opd_id']."'";
    $queryPejabat = mysqli_query($connection, $sqlPejabat);

    while($dataPejabat = mysqli_fetch_array($queryPejabat)){
        ///untuk fetching data kolom
        $b['kepalanip']= $dataPejabat['nip'];
        $b['kepalanama']= $dataPejabat['nama'];
        $b['kepalajabatan']= $dataPejabat['jabatan'];
        $b['kepalapangkat']= $dataPejabat['pangkat'];

        //ini untuk push ke array pejabat
        array_push($a['pejabat'], $b);
    }


    //ngambil pilihanbidang
    $sqlPilihanBidang = "SELECT b.kode as bidang_urusan_kode FROM bidang_urusan_opd a JOIN bidang_urusan b ON a.bidang_urusan_id=b.id WHERE a.opd_id='".$dataSKPD['opd_id']."'";
    $queryPilihanBidang = mysqli_query($connection, $sqlPilihanBidang);
    
    while($dataPilihanBidang = mysqli_fetch_array($queryPilihanBidang)){
        $c = $dataPilihanBidang['bidang_urusan_kode'];

        array_push($a['pilihanbidang'], $c);
    }

    $sqlProgram = "SELECT id, kode, nama FROM program WHERE bidang_urusan_id='".$dataSKPD['bidang_urusan_id']."'";
    $queryPorgram = mysqli_query($connection, $sqlProgram);

    while($dataProgram = mysqli_fetch_array($queryPorgram)){
        $d['kodebidang'] = $dataSKPD['bidang_urusan_kode'];
        $d['kodeprogram'] = $dataProgram['kode'];
        $d['uraiprogram'] = $dataProgram['nama'];
        $d['indikator'] = array();

        array_push($a['program'], $d);
    }

    //ini untuk masukin ke array final
    array_push($DataRenstra, $a);
}

echo json_encode($DataRenstra);
?>