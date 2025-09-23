<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\SuratparuhwaktuModel;

class Automate extends BaseController
{
    public function index()
    {
        //
    }

    function gas() {
        $model = new SuratparuhwaktuModel;
        $data = $model->where(['is_sscasn'=>NULL])->findAll(1,0);

        foreach($data as $row) {
            $this->saveparuhwaktu($row);

            $update = $model->update($row->id, ['is_sscasn' => '1']);
            echo $row->nik;
        }
    }

    function saveparuhwaktu($row) {

        $nik = $row->nik;
        $cek = $this->checkNik($nik);

        $dtpendaftaran = $cek->result->id;
        $pdbaru = $row->pendidikan;
        $jbbaru = $row->jabatan;
        $lokasibaru = $row->lokasi_id;
        $jblama = $cek->result->kode_jabatan;
        $pdlama = $cek->result->pendidikan;
        $lokasilama = $row->lokasi;
        $pendaftarid = $cek->result->pendaftarId;
        $keterangan = $row->keterangan;
        $surat = '8a0283409975c89c019975f0d295006c';

        $token = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3NTg2NjIyMzAsImlhdCI6MTc1ODYxOTAzMCwiYXV0aF90aW1lIjoxNzU4NjE5MDMwLCJqdGkiOiI4OTA1YTMyZS03NzZhLTRiOTctYmQ4My1iYTdiZTVkMjliMzAiLCJpc3MiOiJodHRwczovL3Nzby1zaWFzbi5ia24uZ28uaWQvYXV0aC9yZWFsbXMvcHVibGljLXNpYXNuIiwiYXVkIjoiYWNjb3VudCIsInN1YiI6IjdjYmQyYTJkLTlhNDAtNGQ2Zi05NWMwLWYxZWMyYmRkODc3MCIsInR5cCI6IkJlYXJlciIsImF6cCI6ImFkbWluLXNzY2FzbiIsInNlc3Npb25fc3RhdGUiOiJiZTljNmUwZC0yMWVhLTRkN2UtOGM4Mi00MjhmZDMxY2E3ODIiLCJhY3IiOiIxIiwicmVhbG1fYWNjZXNzIjp7InJvbGVzIjpbIm9mZmxpbmVfYWNjZXNzIiwidW1hX2F1dGhvcml6YXRpb24iLCJyb2xlOnNpYXNuLWluc3RhbnNpOnByb2ZpbGFzbjp2aWV3cHJvZmlsIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJvcGVuaWQgZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IkFITUFEIFpBS1kiLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiIxOTg3MDMxNTIwMjQyMTEwMTAiLCJnaXZlbl9uYW1lIjoiQUhNQUQiLCJmYW1pbHlfbmFtZSI6IlpBS1kiLCJlbWFpbCI6ImFobWFkN2FreUBnbWFpbC5jb20ifQ.Dj_9gS47tpRg_S2XNSsuueAHc9bUpS3QcyK1v9KZioEnuIvDXW0h9Od49Qkfql5HjnfYzo1A479K3jyZyuxoWgM10jquBulwvOB7yl99w7Nqc25JUKDFfDZ6i3uSRVkktbsgB-Y5U9JaDR2QxEnyRA97NUt2LTcPRHhsHA4uJUqrqonepsL26dR7fifqbeHQLw-JxBNmPT5Yt-9X5RK_Fg0OYSqjs5aHM1AzEuLMaQRkzHo4AP2MO9phKVlgRZXXWt7VQSsCSsBp5A-OnwPq68u4qRboN7lCD6u65vuKx5mTPlDVcRaMDjC-p3_R4FN4vUampmEBzewFidbkfxrhqw';

        $param = [
            'nik' => $nik,
            'dtPendaftaran' => $dtpendaftaran,
            'pendidikanBaru' => $pdbaru,
            'jabatanBaru' => $jbbaru,
            'lokasiBaru' => $lokasibaru,
            'jabatanLama' => $jblama,
            'pendidikanLama' => $pdlama,
            'lokasiLama' => $lokasilama,
            'pendaftarId' => $pendaftarid,
            'keterangan' => $keterangan,
            'surat' => $surat
        ];
        $data = http_build_query($param);
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://admin-sscasn.bkn.go.id/permasalahan/orang/save',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
    'Accept: application/json, text/javascript, */*; q=0.01',
    'Accept-Language: en-GB,en;q=0.9,en-US;q=0.8,id;q=0.7,ms;q=0.6,es;q=0.5,pt;q=0.4,vi;q=0.3',
    'Connection: keep-alive',
    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
    'Origin: https://admin-sscasn.bkn.go.id',
    'Sec-Fetch-Dest: empty',
    'Sec-Fetch-Mode: cors',
    'Sec-Fetch-Site: same-origin',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36',
    'X-Requested-With: XMLHttpRequest',
    'sec-ch-ua: "Chromium";v="140", "Not=A?Brand";v="24", "Google Chrome";v="140"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Windows"',
    'Cookie: _ga_NVRKS9CPBG=GS1.1.1737046729.23.1.1737046734.0.0.0; _ga_6L51PKND1M=GS1.1.1738813705.5.0.1738813705.0.0.0; _ga=GA1.1.1409040940.1726985318; _ga_DRZFS4E65W=GS2.1.s1746360884$o4$g1$t1746363331$j0$l0$h0; _ga_0814XD8D5J=GS2.1.s1746363331$o5$g0$t1746363331$j0$l0$h0; _ga_LM1J7YFLJ2=GS2.1.s1746363331$o3$g0$t1746363331$j0$l0$h0; _ga_35R96W5J1R=GS2.1.s1747740545$o1$g0$t1747740545$j0$l0$h0; _ga_SXQ0KJ7TRT=GS2.1.s1753243952$o5$g0$t1753243952$j60$l0$h0; _ga_5GQ07M1DL1=GS2.1.s1753674916$o13$g1$t1753674982$j58$l0$h0; _ga_XSYPSH7VH8=GS2.1.s1755522993$o8$g1$t1755523125$j8$l0$h0; _ga_P60EQWGT4B=GS2.1.s1756290538$o13$g0$t1756290538$j60$l0$h0; _ga_3WZHF169ZR=GS2.1.s1756299252$o4$g0$t1756299252$j60$l0$h0; _ga_794MMPGMHD=GS2.1.s1756345795$o2$g0$t1756345795$j60$l0$h0; _ga_NWFY5EYRQR=GS2.1.s1756793950$o8$g1$t1756793967$j43$l0$h0; _ga_0WNXKJR9W3=GS2.1.s1756881181$o11$g1$t1756881369$j42$l0$h0; _ga_2CYP8KRT04=GS2.1.s1757919298$o8$g0$t1757919298$j60$l0$h0; _ga_REM1P5T4RV=GS2.1.s1758114617$o1$g0$t1758114622$j55$l0$h0; _ga_SN3ET00JF7=GS2.1.s1758530168$o37$g1$t1758530660$j60$l0$h0; _ga_D3VMVHCY1Y=GS2.1.s1758534806$o69$g0$t1758534806$j60$l0$h0; BIGipServerpool_prod_sscasn2024_kube=3137823754.47873.0000; 9760101acce488cfa3a2041bbb8bb77f=b25538b59e6a6cd692269ca1e92676da; JSESSIONID=FA94A2FC26817F0A1EF33109FD965DBA; OAuth_Token_Request_State=bc29c1cf-d64f-4577-81c0-d7235f5a3256; _ga_THXT2YWNHR=GS2.1.s1758619020$o114$g0$t1758619022$j58$l0$h0; access_token=eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3NTg2NjIyMzAsImlhdCI6MTc1ODYxOTAzMCwiYXV0aF90aW1lIjoxNzU4NjE5MDMwLCJqdGkiOiI4OTA1YTMyZS03NzZhLTRiOTctYmQ4My1iYTdiZTVkMjliMzAiLCJpc3MiOiJodHRwczovL3Nzby1zaWFzbi5ia24uZ28uaWQvYXV0aC9yZWFsbXMvcHVibGljLXNpYXNuIiwiYXVkIjoiYWNjb3VudCIsInN1YiI6IjdjYmQyYTJkLTlhNDAtNGQ2Zi05NWMwLWYxZWMyYmRkODc3MCIsInR5cCI6IkJlYXJlciIsImF6cCI6ImFkbWluLXNzY2FzbiIsInNlc3Npb25fc3RhdGUiOiJiZTljNmUwZC0yMWVhLTRkN2UtOGM4Mi00MjhmZDMxY2E3ODIiLCJhY3IiOiIxIiwicmVhbG1fYWNjZXNzIjp7InJvbGVzIjpbIm9mZmxpbmVfYWNjZXNzIiwidW1hX2F1dGhvcml6YXRpb24iLCJyb2xlOnNpYXNuLWluc3RhbnNpOnByb2ZpbGFzbjp2aWV3cHJvZmlsIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJvcGVuaWQgZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IkFITUFEIFpBS1kiLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiIxOTg3MDMxNTIwMjQyMTEwMTAiLCJnaXZlbl9uYW1lIjoiQUhNQUQiLCJmYW1pbHlfbmFtZSI6IlpBS1kiLCJlbWFpbCI6ImFobWFkN2FreUBnbWFpbC5jb20ifQ.Dj_9gS47tpRg_S2XNSsuueAHc9bUpS3QcyK1v9KZioEnuIvDXW0h9Od49Qkfql5HjnfYzo1A479K3jyZyuxoWgM10jquBulwvOB7yl99w7Nqc25JUKDFfDZ6i3uSRVkktbsgB-Y5U9JaDR2QxEnyRA97NUt2LTcPRHhsHA4uJUqrqonepsL26dR7fifqbeHQLw-JxBNmPT5Yt-9X5RK_Fg0OYSqjs5aHM1AzEuLMaQRkzHo4AP2MO9phKVlgRZXXWt7VQSsCSsBp5A-OnwPq68u4qRboN7lCD6u65vuKx5mTPlDVcRaMDjC-p3_R4FN4vUampmEBzewFidbkfxrhqw'
  ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
        echo $param;

    }

    function checkNik($nik) {
        $token = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3NTg2NjIyMzAsImlhdCI6MTc1ODYxOTAzMCwiYXV0aF90aW1lIjoxNzU4NjE5MDMwLCJqdGkiOiI4OTA1YTMyZS03NzZhLTRiOTctYmQ4My1iYTdiZTVkMjliMzAiLCJpc3MiOiJodHRwczovL3Nzby1zaWFzbi5ia24uZ28uaWQvYXV0aC9yZWFsbXMvcHVibGljLXNpYXNuIiwiYXVkIjoiYWNjb3VudCIsInN1YiI6IjdjYmQyYTJkLTlhNDAtNGQ2Zi05NWMwLWYxZWMyYmRkODc3MCIsInR5cCI6IkJlYXJlciIsImF6cCI6ImFkbWluLXNzY2FzbiIsInNlc3Npb25fc3RhdGUiOiJiZTljNmUwZC0yMWVhLTRkN2UtOGM4Mi00MjhmZDMxY2E3ODIiLCJhY3IiOiIxIiwicmVhbG1fYWNjZXNzIjp7InJvbGVzIjpbIm9mZmxpbmVfYWNjZXNzIiwidW1hX2F1dGhvcml6YXRpb24iLCJyb2xlOnNpYXNuLWluc3RhbnNpOnByb2ZpbGFzbjp2aWV3cHJvZmlsIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJvcGVuaWQgZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IkFITUFEIFpBS1kiLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiIxOTg3MDMxNTIwMjQyMTEwMTAiLCJnaXZlbl9uYW1lIjoiQUhNQUQiLCJmYW1pbHlfbmFtZSI6IlpBS1kiLCJlbWFpbCI6ImFobWFkN2FreUBnbWFpbC5jb20ifQ.Dj_9gS47tpRg_S2XNSsuueAHc9bUpS3QcyK1v9KZioEnuIvDXW0h9Od49Qkfql5HjnfYzo1A479K3jyZyuxoWgM10jquBulwvOB7yl99w7Nqc25JUKDFfDZ6i3uSRVkktbsgB-Y5U9JaDR2QxEnyRA97NUt2LTcPRHhsHA4uJUqrqonepsL26dR7fifqbeHQLw-JxBNmPT5Yt-9X5RK_Fg0OYSqjs5aHM1AzEuLMaQRkzHo4AP2MO9phKVlgRZXXWt7VQSsCSsBp5A-OnwPq68u4qRboN7lCD6u65vuKx5mTPlDVcRaMDjC-p3_R4FN4vUampmEBzewFidbkfxrhqw';

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://admin-sscasn.bkn.go.id/permasalahan/cekNik?nik='.$nik.'&surat=8a0283409975c89c019975f0d295006c&parameter=a0227860824811f0bcca005056b4779b',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
                'Accept: application/json, text/javascript, */*; q=0.01',
                'Accept-Language: en-GB,en;q=0.9,en-US;q=0.8,id;q=0.7,ms;q=0.6,es;q=0.5,pt;q=0.4,vi;q=0.3',
                'Connection: keep-alive',
                'Sec-Fetch-Dest: empty',
                'Sec-Fetch-Mode: cors',
                'Sec-Fetch-Site: same-origin',
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36',
                'X-Requested-With: XMLHttpRequest',
                'sec-ch-ua: "Chromium";v="140", "Not=A?Brand";v="24", "Google Chrome";v="140"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Windows"',
                'Cookie: _ga_NVRKS9CPBG=GS1.1.1737046729.23.1.1737046734.0.0.0; _ga_6L51PKND1M=GS1.1.1738813705.5.0.1738813705.0.0.0; _ga=GA1.1.1409040940.1726985318; _ga_DRZFS4E65W=GS2.1.s1746360884$o4$g1$t1746363331$j0$l0$h0; _ga_0814XD8D5J=GS2.1.s1746363331$o5$g0$t1746363331$j0$l0$h0; _ga_LM1J7YFLJ2=GS2.1.s1746363331$o3$g0$t1746363331$j0$l0$h0; _ga_35R96W5J1R=GS2.1.s1747740545$o1$g0$t1747740545$j0$l0$h0; _ga_SXQ0KJ7TRT=GS2.1.s1753243952$o5$g0$t1753243952$j60$l0$h0; _ga_5GQ07M1DL1=GS2.1.s1753674916$o13$g1$t1753674982$j58$l0$h0; _ga_XSYPSH7VH8=GS2.1.s1755522993$o8$g1$t1755523125$j8$l0$h0; _ga_P60EQWGT4B=GS2.1.s1756290538$o13$g0$t1756290538$j60$l0$h0; _ga_3WZHF169ZR=GS2.1.s1756299252$o4$g0$t1756299252$j60$l0$h0; _ga_794MMPGMHD=GS2.1.s1756345795$o2$g0$t1756345795$j60$l0$h0; _ga_NWFY5EYRQR=GS2.1.s1756793950$o8$g1$t1756793967$j43$l0$h0; _ga_0WNXKJR9W3=GS2.1.s1756881181$o11$g1$t1756881369$j42$l0$h0; _ga_2CYP8KRT04=GS2.1.s1757919298$o8$g0$t1757919298$j60$l0$h0; _ga_REM1P5T4RV=GS2.1.s1758114617$o1$g0$t1758114622$j55$l0$h0; _ga_SN3ET00JF7=GS2.1.s1758530168$o37$g1$t1758530660$j60$l0$h0; _ga_D3VMVHCY1Y=GS2.1.s1758534806$o69$g0$t1758534806$j60$l0$h0; BIGipServerpool_prod_sscasn2024_kube=3137823754.47873.0000; 9760101acce488cfa3a2041bbb8bb77f=b25538b59e6a6cd692269ca1e92676da; JSESSIONID=FA94A2FC26817F0A1EF33109FD965DBA; OAuth_Token_Request_State=bc29c1cf-d64f-4577-81c0-d7235f5a3256; _ga_THXT2YWNHR=GS2.1.s1758619020$o114$g0$t1758619022$j58$l0$h0; access_token=eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3NTg2NjIyMzAsImlhdCI6MTc1ODYxOTAzMCwiYXV0aF90aW1lIjoxNzU4NjE5MDMwLCJqdGkiOiI4OTA1YTMyZS03NzZhLTRiOTctYmQ4My1iYTdiZTVkMjliMzAiLCJpc3MiOiJodHRwczovL3Nzby1zaWFzbi5ia24uZ28uaWQvYXV0aC9yZWFsbXMvcHVibGljLXNpYXNuIiwiYXVkIjoiYWNjb3VudCIsInN1YiI6IjdjYmQyYTJkLTlhNDAtNGQ2Zi05NWMwLWYxZWMyYmRkODc3MCIsInR5cCI6IkJlYXJlciIsImF6cCI6ImFkbWluLXNzY2FzbiIsInNlc3Npb25fc3RhdGUiOiJiZTljNmUwZC0yMWVhLTRkN2UtOGM4Mi00MjhmZDMxY2E3ODIiLCJhY3IiOiIxIiwicmVhbG1fYWNjZXNzIjp7InJvbGVzIjpbIm9mZmxpbmVfYWNjZXNzIiwidW1hX2F1dGhvcml6YXRpb24iLCJyb2xlOnNpYXNuLWluc3RhbnNpOnByb2ZpbGFzbjp2aWV3cHJvZmlsIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJvcGVuaWQgZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IkFITUFEIFpBS1kiLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiIxOTg3MDMxNTIwMjQyMTEwMTAiLCJnaXZlbl9uYW1lIjoiQUhNQUQiLCJmYW1pbHlfbmFtZSI6IlpBS1kiLCJlbWFpbCI6ImFobWFkN2FreUBnbWFpbC5jb20ifQ.Dj_9gS47tpRg_S2XNSsuueAHc9bUpS3QcyK1v9KZioEnuIvDXW0h9Od49Qkfql5HjnfYzo1A479K3jyZyuxoWgM10jquBulwvOB7yl99w7Nqc25JUKDFfDZ6i3uSRVkktbsgB-Y5U9JaDR2QxEnyRA97NUt2LTcPRHhsHA4uJUqrqonepsL26dR7fifqbeHQLw-JxBNmPT5Yt-9X5RK_Fg0OYSqjs5aHM1AzEuLMaQRkzHo4AP2MO9phKVlgRZXXWt7VQSsCSsBp5A-OnwPq68u4qRboN7lCD6u65vuKx5mTPlDVcRaMDjC-p3_R4FN4vUampmEBzewFidbkfxrhqw'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        return $response;
        // response json
        // return $this->response->setJSON($response);

    }
}
