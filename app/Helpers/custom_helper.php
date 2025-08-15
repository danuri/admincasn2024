<?php

if ( ! function_exists('ago'))
{
  function timeago($time)
  {
    $ptime  = strtotime($time);
    $estimate_time = time() - $ptime;

    if( $estimate_time < 1 )
    {
        return 'less than 1 second ago';
    }

    $condition = array(
                12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
    );

    foreach( $condition as $secs => $str )
    {
        $d = $estimate_time / $secs;

        if( $d >= 1 )
        {
            $r = round( $d );
            return $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
        }
    }
  }
}

if ( ! function_exists('get_option'))
{
	function get_option($name)
	{
		$db = \Config\Database::connect();

    $builder = $db->table('options');
    $builder->select('*');
    $builder->where('option_name',$name);
    $result = $builder->get();
    $result = $result->getRow();

		return $result->option_value;
	}
}

if ( ! function_exists('update_option'))
{
	function update_option($name,$value)
	{
		$CI =& get_instance();
    $CI->cms_model->update('options',array('option_value' => $value), array('option_name' => $name));

		return true;
	}
}

function harisesi($sesi)
{
  if($sesi == 1){
    return 'Selasa';
  }else if($sesi == 2){
    return 'Selasa';
  }else if($sesi == 3){
    return 'Selasa';
  }else if($sesi == 4){
    return 'Selasa';
  }
}

function alasan_tolak($alasan)
{
  if($alasan == 1){
    return 'Meninggal Dunia';
  }else if($alasan == 2){
    return 'Tidak Aktif Bekerja';
  }
}

function hari($day)
{
	// $day = date('N', strtotime($date));

  $hari = array(
						'2' => 'Senin',
						'3' => 'Selasa',
						'4' => 'Rabu',
						'5' => 'Kamis',
						'6' => "Jum'at",
						'7' => 'Sabtu',
						'1' => 'Minggu',
 					);
	return $hari[$day];
}

function dayenid($day)
{
  $hari = array(
						'Monday' => 'Senin',
						'Tuesday' => 'Selasa',
						'Wednesday' => 'Rabu',
						'Thursday' => 'Kamis',
						'Friday' => "Jumat",
						'Saturday' => 'Sabtu',
						'Sunday' => 'Minggu',
 					);
	return $hari[$day];
}

function bulan($date)
{
	// $month = date('n', strtotime($date));

	$bulan = array(
						'1' => 'Januari','2' => 'Februari','3' => 'Maret','4' => 'April',
						'5' => 'Mei','6' => 'Juni','7' => 'Juli','8' => 'Agustus',
						'9' => 'September','10' => 'Oktober','11' => 'November','12' => 'Desember'
 					);
	return $bulan[$date];
}

function bulans()
{
  $bulan = array(
						'1' => 'Januari','2' => 'Februari','3' => 'Maret','4' => 'April',
						'5' => 'Mei','6' => 'Juni','7' => 'Juli','8' => 'Agustus',
						'9' => 'September','10' => 'Oktober','11' => 'November','12' => 'Desember'
 					);
  return $bulan;
}

function bulan1($bln)
{
  if(strlen($bln)==2){
    return $bln;
  }else{
    return substr($bln,-1);
  }
}

function local_date($tgl){
	// $tanggal = substr($tgl,8,2);
	// $bulan = bulan($tgl);
	// $tahun = substr($tgl,0,4);
	// return $tanggal.' '.$bulan.' '.$tahun;

  $tanggal = date('d-m-Y', strtotime($tgl));
  return $tanggal;
}

function timeDiff($firstTime,$lastTime)
{
  $firstTime=strtotime($firstTime);
  $lastTime=strtotime($lastTime);

  $timeDiff=$lastTime-$firstTime;

  return gmdate("H:i:s", $timeDiff);

}

function to_date($date='')
{
  $time = strtotime($date);
  return date('Y-m-d', $time);
}

function to_time($date='')
{
  $time = strtotime($date);
  return date('H:i:s', $time);
}

function std_to_time($std='')
{
  $time1 = substr($std, 0, 2);
  $time2 = substr($std, -2);

  return $time1.':'.$time2.':00';

}

function format_number($dates='')
{
  $date = date('d', strtotime($dates));
  $month = date('M', strtotime($dates));
  $year = date('Y', strtotime($dates));

  return $date.'/'.$month.' '.$year;
}

// function rupiah($uang){
//
// 	$rupiah  = "";
// 	$panjang = strlen($uang);
//
// 	while ($panjang > 3){
// 		$rupiah		= ".".substr($uang, -3).$rupiah;
// 		$lebar		= strlen($uang) - 3;
// 		$uang		= substr($uang,0,$lebar);
// 		$panjang	= strlen($uang);
// 	}
//
// 	$rupiah = 'Rp. '.$uang.$rupiah.',-';
// 	return $rupiah;
// }

function rupiah($angka){

	$hasil_rupiah = number_format($angka,0,',','.');
	return $hasil_rupiah;

}

function tingkat($jumlah)
{
  $tingkat = '';
  for($x=1;$x<=$jumlah;$x++) {
    $tingkat .= 'X';
  }

  return $tingkat;
}

function kodesatker($id)
{
  if(strlen($id) == 1){
    return '00'.$id;
  }else if(strlen($id) == 2){
    return '0'.$id;
  }else{
    return $id;
  }
}

function pangkat($id)
{
  if(strlen($id) == 1){
    return '0'.$id;
  }else{
    return $id;
  }
}

function shortdec($number='')
{
  return number_format((float)$number, 2, '.', '');
}

function is_loggedin()
{
  $CI =& get_instance();
  if($CI->session->userdata('nip'))
  {
    return true;
  }

  return false;
}

function targetik($year)
{
  $param = array('2020'=>'target_1','2021'=>'target_2','2022'=>'target_3','2023'=>'target_4','2024'=>'target_5');

  return $param[$year];
}

function persen($angka)
{
    if($angka == '.0000'){
        return '-';
    }else if($angka == '.5000'){
        return '0.5';
    }else{
        return str_replace('000','',$angka);
    }
}

function removezero($angka)
{
    if($angka == '0'){
        return '-';
    }else if($angka == '.0000'){
        return '-';
    }else{
        return str_replace('0000','',$angka);
    }
}

function jam($jam=false)
{
  if($jam){
    return date('H:i', strtotime($jam));
  }else{
    return '-';
  }
}

function tanggal($date=false)
{
  if($date){
    return date('d-m-Y', strtotime($date));
  }else{
    return '-';
  }
}

function kodekepala($kode)
{
  if($kode == 0){
    $kodebuntut = 14;
  }else if(substr($kode,-12) == '000000000000'){
    $kodebuntut = 12;
  }else if(substr($kode,-10) == '0000000000'){
    $kodebuntut = 10;
  }else if(substr($kode,-8) == '00000000'){
    $kodebuntut = 8;
  }else if(substr($kode,-6) == '000000'){
    $kodebuntut = 6;
  }else if(substr($kode,-4) == '0000'){
    $kodebuntut = 4;
  }else if(substr($kode,-2) == '00'){
    $kodebuntut = 2;
  }else if(substr($kode,-2) != '00'){
    $kodebuntut = 2;
  }

  $kodekepala = substr($kode, 0, (strlen($kode) - $kodebuntut));

  return $kodekepala;
}

function kodekelola($kode)
{
  if($kode == 0){
    $kodebuntut = 14;
  }else if(substr($kode,-12) == '000000000000'){
    $kodebuntut = 12;
  }else if(substr($kode,-10) == '0000000000'){
    $kodebuntut = 10;
  }else if(substr($kode,-8) == '00000000'){
    $kodebuntut = 8;
  }else if(substr($kode,-6) == '000000'){
    $kodebuntut = 6;
  }else if(substr($kode,-4) == '0000'){
    $kodebuntut = 4;
  }else if(substr($kode,-2) == '00'){
    $kodebuntut = 2;
  }else if(substr($kode,-2) != '00'){
    $kodebuntut = 2;
  }
  return $kodebuntut;
}

function niplama($nipbaru)
{
  $db = \Config\Database::connect('simpeg');
  $query = $db->query("SELECT NIP FROM TEMP_PEGAWAI WHERE NIP_BARU='$nipbaru'");
  $result = (object) $query->getRow();
  return $result->NIP;
}

function hp($nohp) {
  $nohp = str_replace(" ","",$nohp);
  $nohp = str_replace("(","",$nohp);
  $nohp = str_replace(")","",$nohp);
  $nohp = str_replace(".","",$nohp);

  $hp = null;
  if(!preg_match('/[^+0-9]/',trim($nohp))){
     if(substr(trim($nohp), 0, 3)=='+62'){
         $hp = trim($nohp);
     }
     elseif(substr(trim($nohp), 0, 1)=='0'){
         $hp = '62'.substr(trim($nohp), 1);
     }
  }
  return $hp;
 }

 function setencrypt($string) {
  $key = '1a8c7879f4a1f61ca80511b138ca404b';
  $result = '';
  for($i=0; $i<strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)+ord($keychar));
    $result.=$char;
  }

  return base64_encode($result);
}

function setdecrypt($string) {
  $key = '1a8c7879f4a1f61ca80511b138ca404b';
  $result = '';
  $string = base64_decode($string);

  for($i=0; $i<strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)-ord($keychar));
    $result.=$char;
  }

  return $result;
}

function gets3url($file,$folder)
   {
     // $file = str_replace('https://docu.kemenag.go.id:9000/presensi/','',$file);
     $s3Client = new Aws\S3\S3Client([
       'region'  => 'us-east-1',
         'endpoint' => 'https://docu.kemenag.go.id:9001/',
         'use_path_style_endpoint' => true,
         'version' => 'latest',
         'credentials' => [
           'key'    => "118ZEXFCFS0ICPCOLIEJ",
           'secret' => "9xR+TBkYyzw13guLqN7TLvxhfuOHSW++g7NCEdgP",
         ],
         'http'    => [
             'verify' => false
         ]
     ]);

     $cmd = $s3Client->getCommand('GetObject', [
       'Bucket' => 'presensi',
       'Key'    => $folder.'/'.$file,
       'ContentDisposition'    => 'inline',
       'ContentType'    => 'application/pdf',
     ]);

     $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');

     $presignedUrl = (string)$request->getUri();

     return $presignedUrl;
   }

   function siasn_usul_status($status)
 {
  
   if($status == 1){
     $result = 'Input Berkas';
   }else if($status == 2){
     $result = 'Berkas Disimpan (Terverifikasi)';
   }else if($status == 3){
     $result = 'Surat Usulan';
   }else if($status == 4){
     $result = 'Approval Surat Usulan';
   }else if($status == 5){
     $result = 'Perbaikan Dokumen';
   }else if($status == 6){
     $result = 'Tidak Memenuhi Syarat';
   }else if($status == 7){
     $result = 'Menunggu Cetak SK - Menyetujui';
   }else if($status == 8){
     $result = 'Menunggu Cetak SK - Perbaikan Pertek';
   }else if($status == 9){
     $result = 'Menunggu Cetak SK - Pembatalan Pertek';
   }else if($status == 10){
     $result = 'Cetak SK';
   }else if($status == 11){
     $result = 'Profil PNS telah diperbaharui';
   }else if($status == 12){
     $result = 'Terima Usulan';
   }else if($status == 13){
     $result = 'Validasi Usulan - Tidak Memenuhi Syarat';
   }else if($status == 14){
     $result = 'Validasi Usulan - Perbaikan Dokumen';
   }else if($status == 15){
     $result = 'Validasi Usulan - Disetujui';
   }else if($status == 15){
     $result = 'Validasi Usulan - Disetujui';
    }else if($status == 16){
      $result = 'Berkas Disetujui';
    }else if($status == 17){
      $result = 'Menunggu Paraf - Paraf Pertek';
    }else if($status == 18){
      $result = 'Menunggu Paraf - Gagal Paraf Pertek';
    }else if($status == 19){
      $result = 'Sudah di paraf - Pertek';
    }else if($status == 20){
      $result = 'Menunggu Tanda tangan- TTD Pertek';
    }else if($status == 21){
      $result = 'Berkas Ditolak - TTD Pertek';
    }else if($status == 22){
      $result = 'Sudah TTD - Pertek';
    }else if($status == 23){
      $result = 'Surat Keluar';
    }else if($status == 24){
      $result = 'Perbaikan Pertek (Menunggu Approval Instansi)';
    }else if($status == 25){
      $result = 'Terima Usulan Penetapan - Pembatalan';
    }else if($status == 26){
      $result = 'Pembatalan Pertek (Menunggu Approval Instansi)';
    }else if($status == 27){
      $result = 'Menunggu SK - Paraf / TTE';
    }else if($status == 28){
      $result = 'Setuju Paraf SK';
    }else if($status == 29){
      $result = 'Tolak TTD SK';
    }else if($status == 30){
      $result = 'Setuju TTD SK';
    }else if($status == 31){
      $result = 'Telah Update di Profile PNS';
    }else if($status == 32){
      $result = 'Pembuatan SK Berhasil';
    }else if($status == 33){
      $result = 'Menunggu Layanan';
    }else if($status == 34){
      $result = 'Perbaikan Dokumen - Menunggu Approval';
    }else if($status == 35){
      $result = 'Tolak Paraf SK';
    }else if($status == 36){
      $result = 'Menunggu TTD - SK';
    }else if($status == 37){
      $result = 'Approval Perbaikan Pertek';
    }else if($status == 38){
      $result = 'Approval Pembatalan Pertek';
    }else if($status == 39){
      $result = 'Perbaikan SK';
    }else if($status == 40){
      $result = 'Berkas Disimpan (Terverifikasi) - Perbaikan SK';
    }else if($status == 41){
      $result = 'Validasi Usulan - Perbaikan SK';
    }else if($status == 42){
      $result = 'Validasi Usulan - Perbaikan SK (Disetujui)';
    }else if($status == 43){
      $result = 'Menunggu Paraf - Perbaikan SK';
    }else if($status == 44){
      $result = 'Menunggu TTD - Perbaikan SK';
    }else if($status == 45){
      $result = 'Sudah TTD - Perbaikan SK';
    }else if($status == 46){
      $result = 'Menunggu TTD SK - Instansi';
    }else if($status == 47){
      $result = 'Tolak TTD SK - Instansi';
    }else if($status == 48){
      $result = 'Setuju TTD SK - Instansi';
    }else if($status == 49){
      $result = 'Sudah TTD - SK';
    }else if($status == 50){
     $result = 'Perbaikan Dokumen - MYSAPK';
   }else if($status == 51){
     $result = 'Input Berkas - Perbaikan MySAPK';
   }else if($status == 52){
     $result = 'Perbaikan Dokumen - Approval';
   }else if($status == 53){
     $result = 'Setuju TTD Pertek';
   }else if($status == 55){
     $result = 'Approval Tingkat Provinsi';
   }else if($status == 56){
     $result = 'Perbaikan Approval';
   }else if($status == 57){
     $result = 'Perbaikan Pertek';
   }else if($status == 58){
     $result = 'Validasi Usulan - Perbaikan Pertek';
   }else if($status == 59){
     $result = 'Menunggu Buat Sk';
   }else if($status == 60){
     $result = 'Proses Persidangan';
   }else if($status == 61){
     $result = 'Input Berkas - SK PNS';
   }else if($status == 62){
     $result = 'Menunggu TTD SK PNS - Instansi';
   }else if($status == 63){
     $result = 'Setuju TTD Digital SK PNS';
   }else if($status == 64){
     $result = 'Pembuatan SK Basah PNS Berhasil';
   }else if($status == 65){
     $result = 'Pembatalan NIP/Pertek';
   }else if($status == 66){
     $result = 'Perbaikan SK Provinsi';
   }else if($status == 67){
     $result = 'Perbaikan Dokumen - BTS';
   }else if($status == 68){
     $result = 'Perbaikan SK - Validasi Usulan';
   }else if($status == 69){
     $result = 'Tolak Perbaikan SK - BTS';
   }else if($status == 70){
     $result = 'Tolak Perbaikan SK - TMS';
   }else if($status == 71){
     $result = 'Perbaikan Dokumen SK - BTS';
   }else if($status == 99){
     $result = 'Usulan Dihapus';
   }else{
     $result = '';
   }

   return $result;
 }

define('ENCRYPTION_KEY', '4736d52f85bdb63e46bf7d6d41bbd551af36e1bfb7c68164bf81e2400d291319');
function encrypt($string, $salt = null)
{
	if($salt === null) { $salt = hash('sha256', uniqid(mt_rand(), true)); }  // this is an unique salt per entry and directly stored within a password
	return base64_encode(openssl_encrypt($string, 'AES-256-CBC', ENCRYPTION_KEY, 0, str_pad(substr($salt, 0, 16), 16, '0', STR_PAD_LEFT))).':'.$salt;
}
function decrypt($string)
{
  	// if( count(explode(':', $string)) !== 2 ) { return $string; }
	$salt = explode(":",$string)[1]; $string = explode(":",$string)[0]; // read salt from entry
	return openssl_decrypt(base64_decode($string), 'AES-256-CBC', ENCRYPTION_KEY, 0, str_pad(substr($salt, 0, 16), 16, '0', STR_PAD_LEFT));
}
