<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('supervise/index/(:num)', 'Supervise::index/$1');
$routes->get('supervise/supervise/(:any)', 'Supervise::app/$1');
$routes->get('supervise/setparuhwaktu', 'Supervise::setparuhwaktu');
$routes->get('supervise/getid', 'Supervise::getid');


$routes->get('automate', 'Automate::gas');
$routes->get('automate/cek/(:any)', 'Automate::checkNik/$1');
$routes->get('automate/updatedatalama', 'Automate::updatedatalama');

$routes->get('auth', 'Auth::index');
$routes->get('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('auth/callback', 'Auth::callback');

$routes->get('pub/document/(:any)', 'Publish::document/$1');
$routes->get('pub/sinkron', 'Publish::sinkron');
$routes->get('pub/paruhwaktu', 'Publish::paruhwaktu');
$routes->get('pub/monitoring', 'Publish::monitoring');
$routes->get('pub/optimalisasi', 'Publish::optimalisasi');
$routes->get('pub/sinkronpw', 'Publish::sinkronpw');

$routes->get('/', 'Home::index',['filter' => 'auth']);
$routes->get('generateformasi', 'Home::generateformasi',['filter' => 'auth']);
$routes->get('formasi', 'Formasi::index',['filter' => 'auth']);
$routes->get('regulasi', 'Regulasi::index',['filter' => 'auth']);
$routes->get('pelamar', 'Pelamar::index',['filter' => 'auth']);
$routes->get('pelamar/pppkteknis', 'Pelamar::pppkteknis',['filter' => 'auth']);
$routes->get('formasi', 'Formasi::index',['filter' => 'auth']);

$routes->get('pengaturan', 'Pengaturan::index',['filter' => 'auth']);
$routes->post('pengaturan/save', 'Pengaturan::save',['filter' => 'auth']);

$routes->group("api", function ($routes) {
    $routes->get('sanggah/(:any)/(:any)/(:num)', 'Api::sanggah/$1/$2/$3');
    $routes->get('whatsapp', 'Api::whatsapp');
});

$routes->group("siasn", function ($routes) {
    $routes->get('role', 'Siasn::role');
});

$routes->group("skb", ["filter" => "auth"], function ($routes) {
    $routes->get('lokasi', 'Skb\Lokasi::index');
    $routes->get('lokasi/index/(:any)', 'Skb\Lokasi::index/$1');
    $routes->get('lokasi/setjadwal/(:any)', 'Skb\Lokasi::setjadwal/$1');
    $routes->post('lokasi/updatejadwal/(:any)', 'Skb\Lokasi::updatejadwal/$1');
    $routes->get('lokasi/setlokasi/(:any)', 'Skb\Lokasi::setlokasi/$1');
    $routes->post('lokasi/add', 'Skb\Lokasi::add');
    $routes->get('lokasi/delete/(:num)', 'Skb\Lokasi::delete/$1');
    $routes->get('lokasi/get_detail/(:num)', 'Skb\Lokasi::get_detail/$1');
    $routes->get('lokasi/export', 'Skb\Lokasi::export');
    $routes->post('lokasi/update', 'Skb\Lokasi::update');
    $routes->get('peserta', 'Skb\Peserta::index');
    $routes->get('peserta/getdata', 'Skb\Peserta::getdata');
    $routes->get('peserta/export', 'Skb\Peserta::export');
    $routes->get('peserta/detail/(:any)', 'Skb\Peserta::detail/$1');
    $routes->get('peserta/detail/(:any)', 'Skb\Peserta::detail/$1');
    $routes->get('penguji', 'Skb\Penguji::index');
    $routes->post('penguji/add', 'Skb\Penguji::add');
    $routes->get('penguji/getpegawai/(:any)', 'Skb\Penguji::getpegawai/$1');
    $routes->get('penguji/get_detail/(:num)', 'Skb\Penguji::get_detail/$1');
    $routes->post('penguji/addfile', 'Skb\Penguji::addfile');
    $routes->post('penguji/update', 'Skb\Penguji::update');
    $routes->get('penguji/delete/(:num)', 'Skb\Penguji::delete/$1');
    $routes->get('jadwal', 'Skb\Jadwal::index');
    $routes->post('jadwal/importjadwal', 'Skb\Jadwal::importjadwal');
    $routes->get('jadwal/delete/(:any)/(:any)', 'Skb\Jadwal::delete/$1/$2');
    $routes->get('jadwal/export', 'Skb\Jadwal::export');
    $routes->get('info', 'Skb\Info::index');
    $routes->post('info/update', 'Skb\Info::update');
    $routes->get('dokumen', 'Skb\Dokumen::index');
    $routes->get('dokumen/unggahan/(:num)', 'Skb\Dokumen::unggahan/$1');
    $routes->post('dokumen/saveunggahan', 'Skb\Dokumen::saveunggahan');
    $routes->get('dokumen/deleteunggahan/(:num)', 'Skb\Dokumen::deleteunggahan/$1');
    $routes->post('dokumen/save', 'Skb\Dokumen::save');
    $routes->get('dokumen/delete/(:num)', 'Skb\Dokumen::delete/$1');
});

$routes->group("downloads", ["filter" => "auth"], function ($routes) {
    $routes->get('', 'Download::index');
    $routes->get('pelamar', 'Download::pelamar');
    $routes->get('sanggah', 'Download::sanggah');
    $routes->get('jadwalskd', 'Download::jadwalskd');
    $routes->get('jadwalsk', 'Download::jadwalsk');
    $routes->get('jadwalsksatker', 'Download::jadwalsksatker');
    $routes->get('skttpeserta', 'Download::skttpeserta');
    $routes->get('skttpesertaprov', 'Download::skttpesertaprov');
});

$routes->group("upload", ["filter" => "auth"], function ($routes) {
    $routes->get('', 'Upload::index');
    $routes->post('save', 'Upload::save');
});

$routes->group("surat", ["filter" => "auth"], function ($routes) {
    $routes->get('', 'Surat::index');
    $routes->post('save', 'Surat::save');
    $routes->post('saveinput', 'Surat::saveinput');
    $routes->post('savedefault', 'Surat::savedefault');
    $routes->get('input/(:any)', 'Surat::input/$1');
    $routes->get('inputdelete/(:any)', 'Surat::inputdelete/$1');
    $routes->get('delete/(:any)', 'Surat::delete/$1');
    $routes->get('submit/(:any)', 'Surat::submit/$1');
});

$routes->group("pppk", ["filter" => "auth"], function ($routes) {
    $routes->get('peserta', 'Pppk::peserta');
    $routes->get('pesertal2in', 'Pppk::pesertal2in');
    $routes->get('pesertal2out', 'Pppk::pesertal2out');
    $routes->get('usuloptimalisasi', 'Pppk::usuloptimalisasi');
    $routes->post('uploadsk', 'Pppk::uploadsk');
    $routes->post('uploaddok/(:any)', 'Pppk::uploaddok/$1');
    $routes->get('submit', 'Pppk::submit');
    $routes->get('sinkron', 'Pppk::sinkron');
    $routes->get('peserta/cekdosen/(:any)/(:any)', 'Pppk::cekdosen/$1/$2');
});

$routes->group("paruhwaktu", ["filter" => "auth"], function ($routes) {
    $routes->get('', 'Paruhwaktu::index');
    $routes->post('setusul', 'Paruhwaktu::setusul');
    $routes->post('uploaddok', 'Paruhwaktu::uploaddok');
    $routes->get('export', 'Paruhwaktu::export');
    $routes->get('search/(:any)', 'Paruhwaktu::search/$1');
    $routes->post('sprp', 'Paruhwaktu::cetak_sprp');
    $routes->post('getpeserta', 'Paruhwaktu::getpeserta');
    $routes->post('kontrak', 'Paruhwaktu::draftkontrak');
});

$routes->group("admin", ["filter" => "auth"], function ($routes) {
    $routes->get('pppk/optimalisasi', 'Admin\Pppk::optimalisasi');
    $routes->get('pppk/optimalisasidetail/(:any)', 'Admin\Pppk::optimalisasidetail/$1');
});

$routes->group("pengaturan", ["filter" => "auth"], function ($routes) {
    $routes->get('formasi', 'Pengaturan\Formasi::index');
    $routes->post('formasi/saveporsi', 'Pengaturan\Formasi::saveporsi');
    $routes->post('formasi/final', 'Pengaturan\Formasi::final');
    $routes->get('penempatan', 'Pengaturan\Penempatan::index');
    $routes->get('penempatan/search', 'Pengaturan\Penempatan::search');
    $routes->post('penempatan/save', 'Pengaturan\Penempatan::save');
});

$routes->group("verifikasi", ["filter" => "auth"], function ($routes) {
    $routes->get('thk2', 'Verifikasi::thk2');
    $routes->post('thk2', 'Verifikasi::searchthk2');
    $routes->get('nonasn', 'Verifikasi::nonasn');
    $routes->post('nonasn', 'Verifikasi::searchnonasn');
});

// $routes->group("sktt", ["filter" => "auth"], function ($routes) {
//     $routes->get('lokasi', 'Sktt::lokasi');
//     $routes->post('lokasi', 'Sktt::inserttilok');
//     $routes->get('edit/(:any)', 'Sktt::edit/$1');
//     $routes->post('edittilok', 'Sktt::edittilok');
//     $routes->get('delete/(:any)', 'Sktt::deletetilok/$1');
// });

$routes->group("sktt", ["filter" => "auth"], function ($routes) {
    // $routes->get('', 'Sktt::lokasi');
    // $routes->post('', 'Sktt::inserttilok');
    $routes->get('lokasi', 'Sktt::lokasi');
    $routes->post('lokasi', 'Sktt::inserttilok');
    $routes->get('akses', 'Sktt::akses');
    $routes->post('lokasi', 'Sktt::inserttilok');
    $routes->post('pindahtilok', 'Sktt::pindahtilok');
    $routes->get('gettilok/(:any)', 'Sktt::gettilok/$1');
    $routes->post('generatejadwal', 'Sktt::generatejadwal');
    $routes->get('delete/(:any)', 'Sktt::deletetilok/$1');
    $routes->get('peserta/(:any)', 'Sktt::peserta/$1');
    $routes->get('edit/(:num)', 'Sktt::edit/$1');
    $routes->post('edittilok', 'Sktt::edittilok');
});

$routes->group("skb", ["filter" => "auth"], function ($routes) {
    $routes->get('aksespenguji', 'Skb::aksespenguji');
});

$routes->group("penetapan", ["filter" => "auth"], function ($routes) {
    $routes->get('spmt', 'Penetapan\Spmt::index');
    $routes->post('spmt/upload', 'Penetapan\Spmt::upload');
    $routes->post('spmt/baupload', 'Penetapan\Spmt::baupload');
    $routes->get('sprp', 'Penetapan\Sprp::index');
    $routes->get('formasi', 'Penetapan\Formasi::index');
    $routes->post('formasi', 'Penetapan\Formasi::updateunor');
    $routes->get('formasi/rekapitulasi', 'Penetapan\Formasi::rekapitulasi');
    $routes->get('formasi/getdata', 'Penetapan\Formasi::getdata');
    $routes->get('formasi/export', 'Penetapan\Formasi::export');
    $routes->get('peserta', 'Penetapan\Peserta::index');
    $routes->get('peserta/getdataskb', 'Penetapan\Peserta::getdataskb');
    $routes->get('peserta/getdataadmin', 'Penetapan\Peserta::getdataadmin');
    $routes->get('peserta/export', 'Penetapan\Peserta::export');
    $routes->get('peserta/get_detail/(:any)', 'Penetapan\Peserta::get_detail/$1');
    $routes->post('peserta/save', 'Penetapan\Peserta::save');
    $routes->get('peserta/reset/(:any)', 'Penetapan\Peserta::reset/$1');
    $routes->get('peserta/sprp/(:any)', 'Penetapan\Peserta::cetak_sprp/$1');
    $routes->get('peserta/export', 'Penetapan\Peserta::export');
    $routes->get('monitoring', 'Penetapan\Monitoring::index');
});

$routes->group("sanggah", ["filter" => "auth"], function ($routes) {
    $routes->get('nilai', 'Sanggah::index');
});

$routes->group("sktt", ["filter" => "auth"], function ($routes) {
    $routes->get('tilok', 'Sktt::tilok');
});

$routes->group("ajax", ["filter" => "auth"], function ($routes) {
    $routes->get('searchunor', 'Ajax::searchunor');
    $routes->get('searchlokasi', 'Ajax::searchlokasi');
    $routes->get('jabatan/(:any)', 'Ajax::jabatan/$1');
});
$routes->get('ajax/monitoringusulnip/(:num)/(:num)/(:num)/(:num)/(:num)/(:num)', 'Ajax::monitoringusulnip/$1/$2/$3/$4/$5/$6');
$routes->get('ajax/getmonitoring/(:num)/(:num)', 'Ajax::getmonitoring/$1/$2');
$routes->get('ajax/setidpeserta', 'Ajax::setidpeserta');
$routes->get('ajax/postit', 'Ajax::postIt');

// $routes->group("admin", ["filter" => "admin"], function ($routes) {

//   $routes->group("users", function ($routes) {
//       $routes->get('', 'Admin\Users::index');
//   });

//   $routes->group("dokumen", function ($routes) {
//       $routes->get('', 'Admin\Dokumen::index');
//       $routes->get('unggahan/(:num)', 'Admin\Dokumen::unggahan/$1');
//       $routes->get('deleteunggahan/(:num)', 'Admin\Dokumen::deleteunggahan/$1');
//   });

//   $routes->group("download", function ($routes) {
//       $routes->get('', 'Admin\Download::index');
//   });

//   $routes->group("penempatan", function ($routes) {
//       $routes->get('', 'Admin\Penempatan::index');
//   });

//   $routes->group("faq", function ($routes) {
//       $routes->get('', 'Admin\Faq::index');
//       $routes->get('add', 'Admin\Faq::add');
//       $routes->post('add', 'Admin\Faq::save');
//       $routes->get('edit/(:num)', 'Admin\Faq::edit/$1');
//       $routes->post('edit/(:num)', 'Admin\Faq::saveedit/$1');
//   });

//   $routes->get('pelamar/(:any)', 'Admin\Pelamar::index/$1');

// });
