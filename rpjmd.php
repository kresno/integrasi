<?php
$connection = mysqli_connect("localhost", "root", "", "perubahan2020");

// Check connection
$sqlVisi ="SELECT id, nama, kodepemda, mulai, selesai, periode_rpjmd FROM visi";
$queryVisi = mysqli_query($connection, $sqlVisi);

$DataVisi = array();
while($dataVs = mysqli_fetch_array($queryVisi)){
    $a['kodepemda'] = $dataVs['kodepemda'];
    $a['tahunmulai'] = $dataVs['mulai'];
    $a['tahunselesai'] = $dataVs['selesai'];
    $a['periode_rpjmd'] = $dataVs['periode_rpjmd'];
    $a['uraivisi'] = $dataVs['nama'];
    $a['misi'] = array();

    $sqlMisi= "SELECT id, nama FROM misi WHERE visi_id='".$dataVs['id']."'";
    $queryMisi = mysqli_query($connection, $sqlMisi);

    while($dataMisi=mysqli_fetch_array($queryMisi)){
        $b['kodemisi'] =$dataMisi['id'];
        $b['uraimisi'] =$dataMisi['nama'];
        $b['tujuan'] = array();

        $sqlTujuan = "SELECT id,kode, nama FROM tujuan WHERE misi_id='".$dataMisi['id']."'";
        $queryTujuan = mysqli_query($connection, $sqlTujuan);

        while($dataTujuan = mysqli_fetch_array($queryTujuan)){
            $c['kodetujuan'] = $dataTujuan['kode'];
            $c['uraitujuan'] = $dataTujuan['nama'];
            $c['indikator'] = array();
            $c['sasaran'] = array();

            $sqlSasaran = "SELECT id, kode, nama FROM sasaran where tujuan_id='".$dataTujuan['id']."'";
            $querySasaran = mysqli_query($connection, $sqlSasaran);
            
            while($dataSasaran = mysqli_fetch_array($querySasaran)){
                $d['kodesasaran'] = $dataSasaran['kode'];
                $d['uraiansasaran'] = $dataSasaran['nama'];
                $d['indikator'] = array();
                $d['program'] = array();

                array_push($c['sasaran'], $d);
            }
            array_push($b['tujuan'], $c);
        }
        array_push($a['misi'], $b);
    }
    array_push($DataVisi, $a);
}
echo json_encode($DataVisi);
?> 