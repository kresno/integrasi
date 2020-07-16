<?php

//Koneksi dengan database
$connection = mysqli_connect("localhost", "root", "", "rkpd2021");
$DataRenstraAll = array();

$sqlBidangUrusan = "SELECT id as bidang_urusan_id FROM bidang_urusan";
$queryBidangUrusan = mysqli_query($connection, $sqlBidangUrusan);

while($dataBidangUrusan = mysqli_fetch_array($queryBidangUrusan)){
    $base_bidang_urusan_id = $dataBidangUrusan['bidang_urusan_id'];
    
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
            $d['kegiatan'] = array();

            $sqlIndikator = "SELECT b.id as indikator_id, b.nama as indikator_nama, b.target as indikator_target, b.satuan as indikator_satuan FROM indikator_program a JOIN indikator_sasaran b ON a.indikator_sasaran_id=b.id WHERE a.program_id='".$dataProgram['id']."'";
            $queryIndikator = mysqli_query($connection, $sqlIndikator);
            while($dataIndikator = mysqli_fetch_array($queryIndikator)){
                $e['kodeindikator'] = $dataIndikator['indikator_id'];
                $e['uraiindikator'] = $dataIndikator['indikator_nama'];
                $e['satuan'] = $dataIndikator['indikator_satuan'];
                $e['status'] = "positif";
                $e['volume_n1'] = "0";
                $e['volume_n2'] = "0";
                $e['volume_n3'] = "0";
                $e['volume_n4'] = "0";
                $e['volume_n5'] = "0";
                $e['pagu_n1'] = "1000000000";
                $e['pagu_n2'] = "1000000000";
                $e['pagu_n3'] = "1000000000";
                $e['pagu_n4'] = "1000000000";
                $e['pagu_n5'] = "1000000000";

                array_push($d['indikator'], $e);
            }

            $sqlKegiatan = "SELECT a.id as kegiatan_id, a.nama as kegiatan_nama FROM kegiatan a JOIN program b ON a.program_id=b.id WHERE a.program_id='".$dataProgram['id']."'"; 
            $queryKegiatan = mysqli_query($connection, $sqlKegiatan);
            while($dataKegiatan = mysqli_fetch_array($queryKegiatan)){
                $f['kodekegiatan'] = $dataKegiatan['kegiatan_id'];
                $f['uraikegiatan'] = $dataKegiatan['kegiatan_nama'];
                $f['lokasi'] = "Kabupaten Sukabumi";
                $f['indikator'] = array();
                $f['subkegiatan'] = array();

                $sqlSubKegiatan = "SELECT a.id as kegiatan_id, a.nama as kegiatan_nama FROM kegiatan a WHERE a.id='".$dataKegiatan['kegiatan_id']."'"; 
                $querySubKegiatan = mysqli_query($connection, $sqlSubKegiatan);
                while($dataSubKegiatan = mysqli_fetch_array($querySubKegiatan)){
                    $g['kodesubkegiatan'] = $dataSubKegiatan['kegiatan_id'];
                    $g['uraisubkegiatan'] = $dataSubKegiatan['kegiatan_nama'];
                    $g['lokasi'] = "Kabupaten Sukabumi";
                    $g['indikator'] = array();
                    
                    $sqlIndikatorKegiatan = "SELECT a.id as indikator_subkegiatan_id, a.tolak_ukur as indikator_subkegiatan_tolakukur, c.nama as indikator_subkegiatan_satuan FROM indikator_kegiatan a JOIN kegiatan b ON a.kegiatan_id=b.id JOIN satuan c on a.satuan_id=c.id WHERE a.kegiatan_id='".$dataSubKegiatan['kegiatan_id']."' AND a.indikator_hasil_id='2'";
                    $queryIndikatorKegiatan = mysqli_query($connection, $sqlIndikatorKegiatan);
                    while($dataIndikatorKegiatan = mysqli_fetch_array($queryIndikatorKegiatan)){
                        $h['kodeindikator'] = $dataIndikatorKegiatan['indikator_subkegiatan_id'];
                        $h['tolakukur'] = $dataIndikatorKegiatan['indikator_subkegiatan_tolakukur'];
                        $h['satuan'] = $dataIndikatorKegiatan['indikator_subkegiatan_satuan'];
                        $h['volume_n1'] = "0";
                        $h['volume_n2'] = "0";
                        $h['volume_n3'] = "0";
                        $h['volume_n4'] = "0";
                        $h['volume_n5'] = "0";
                        $h['pagu_n1'] = "1000000000";
                        $h['pagu_n2'] = "1000000000";
                        $h['pagu_n3'] = "1000000000";
                        $h['pagu_n4'] = "1000000000";
                        $h['pagu_n5'] = "1000000000";

                        array_push($g['indikator'], $h);
                    }
                    
                    array_push($f['subkegiatan'], $g);
                }

                array_push($d['kegiatan'], $f);
            }

            array_push($a['program'], $d);
        }

        //ini untuk masukin ke array final
        array_push($DataRenstra, $a);
    }
    array_push($DataRenstraAll, $DataRenstra);
}

echo json_encode($DataRenstraAll);
?>