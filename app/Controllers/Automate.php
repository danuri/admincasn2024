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
        $data = $model->where(['is_sscasn'=>'1'])->findAll(100,0);

        foreach($data as $row) {
            $set = $this->saveparuhwaktu($row);

            $update = $model->update($row->id, ['is_sscasn' => '2']);
            // if($set->status && $set->status == 'success') {
            // } else {
            //     $update = $model->update($row->id, ['is_sscasn' => '2']);
            // }
        }
    }

    function updatedatalama() {
        $model = new SuratparuhwaktuModel;
        $data = $model->where(['is_sscasn'=>'0'])->findAll(100,0);

        foreach($data as $row) {
            $cek = $this->checkNik($row->nik);

            $update = $model->update($row->id, ['pendidikan_lama'=>$cek->result->pendidikan, 'jabatan_lama'=>$cek->result->kode_jabatan,'lokasi_lama'=>$cek->result->unor_id, 'is_sscasn' => '1']);
        
            // return $this->response->setJSON($cek);
        }
    }

    function saveparuhwaktu($row) {

        $nik = $row->nik;
        $cek = $this->checkNik($nik);

        if($cek == 'null'){
            echo 'NIK '.$nik.' tidak ditemukan';
            return false;
        }

        $dtpendaftaran = $cek->result->id;
        $pdbaru = $row->pendidikan;
        $jbbaru = $row->jabatan;
        $lokasibaru = $row->lokasi_id;
        $jblama = $cek->result->kode_jabatan;
        $pdlama = $cek->result->pendidikan;
        $lokasilama = $cek->result->unor_id;
        $pendaftarid = $cek->result->pendaftarId;
        $keterangan = $row->keterangan;
        $surat = '8a0085469979dff301997b16676f032b';

        $token = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3NTg3NDk1OTMsImlhdCI6MTc1ODcwNjM5MywiYXV0aF90aW1lIjoxNzU4NzA2MzkzLCJqdGkiOiI1YWM1NTM3OC1lNzc0LTQ4OTgtYmNjOC01ZjkxYmJmZTNiNDkiLCJpc3MiOiJodHRwczovL3Nzby1zaWFzbi5ia24uZ28uaWQvYXV0aC9yZWFsbXMvcHVibGljLXNpYXNuIiwiYXVkIjoiYWNjb3VudCIsInN1YiI6IjdjYmQyYTJkLTlhNDAtNGQ2Zi05NWMwLWYxZWMyYmRkODc3MCIsInR5cCI6IkJlYXJlciIsImF6cCI6ImFkbWluLXNzY2FzbiIsInNlc3Npb25fc3RhdGUiOiJlNzU3NzUyMy01MjRjLTQ4OTItYThmMC02YTMyNWJkNDRmMzIiLCJhY3IiOiIxIiwicmVhbG1fYWNjZXNzIjp7InJvbGVzIjpbIm9mZmxpbmVfYWNjZXNzIiwidW1hX2F1dGhvcml6YXRpb24iLCJyb2xlOnNpYXNuLWluc3RhbnNpOnByb2ZpbGFzbjp2aWV3cHJvZmlsIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJvcGVuaWQgZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IkFITUFEIFpBS1kiLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiIxOTg3MDMxNTIwMjQyMTEwMTAiLCJnaXZlbl9uYW1lIjoiQUhNQUQiLCJmYW1pbHlfbmFtZSI6IlpBS1kiLCJlbWFpbCI6ImFobWFkN2FreUBnbWFpbC5jb20ifQ.N6oIHwdp8kia7Oy1VjblE908tnbRyq5ypxua8lKJiUhrW-JhrwOHiRMK7M2VyX6gZdT5NJHE8NrMHWkh_TYz65jx_bm6JwY65EWOhsvthGMmGoJRh45Rs4N5Pu4HWNOnt5ja8olr36R8Ssl_8Jhajg82ctxpyavxy1hD0oh-j7two7tyQ1EPWvc9_SkZB-Oyt9BKTKYeF_iyj3S_FrLxV9nAhhxFEZEYqL1aaLhgh2I5hlemDXu5jI5D_xQnHjapx88TJGJnA9JZm5p-rjKV9QdpBiUlgT8U5UpDB7nocKHgvPrrcZMZoxTf_2cziAvzMLP3StAcjx8vtk7X79KrIg';

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
            'keterangan' => ($keterangan)?$keterangan:'-',
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
                'Cookie: _ga_NVRKS9CPBG=GS1.1.1737046729.23.1.1737046734.0.0.0; _ga_6L51PKND1M=GS1.1.1738813705.5.0.1738813705.0.0.0; _ga=GA1.1.1409040940.1726985318; _ga_DRZFS4E65W=GS2.1.s1746360884$o4$g1$t1746363331$j0$l0$h0; _ga_0814XD8D5J=GS2.1.s1746363331$o5$g0$t1746363331$j0$l0$h0; _ga_LM1J7YFLJ2=GS2.1.s1746363331$o3$g0$t1746363331$j0$l0$h0; _ga_35R96W5J1R=GS2.1.s1747740545$o1$g0$t1747740545$j0$l0$h0; _ga_SXQ0KJ7TRT=GS2.1.s1753243952$o5$g0$t1753243952$j60$l0$h0; _ga_5GQ07M1DL1=GS2.1.s1753674916$o13$g1$t1753674982$j58$l0$h0; _ga_XSYPSH7VH8=GS2.1.s1755522993$o8$g1$t1755523125$j8$l0$h0; _ga_P60EQWGT4B=GS2.1.s1756290538$o13$g0$t1756290538$j60$l0$h0; _ga_3WZHF169ZR=GS2.1.s1756299252$o4$g0$t1756299252$j60$l0$h0; _ga_794MMPGMHD=GS2.1.s1756345795$o2$g0$t1756345795$j60$l0$h0; _ga_NWFY5EYRQR=GS2.1.s1756793950$o8$g1$t1756793967$j43$l0$h0; _ga_0WNXKJR9W3=GS2.1.s1756881181$o11$g1$t1756881369$j42$l0$h0; _ga_2CYP8KRT04=GS2.1.s1757919298$o8$g0$t1757919298$j60$l0$h0; _ga_REM1P5T4RV=GS2.1.s1758114617$o1$g0$t1758114622$j55$l0$h0; _ga_SN3ET00JF7=GS2.1.s1758630817$o38$g1$t1758630828$j49$l0$h0; 9760101acce488cfa3a2041bbb8bb77f=fcb7afe4a0963867aeac955eb17ee95f; BIGipServerpool_prod_sscasn2024_kube=3121046538.47873.0000; _ga_D3VMVHCY1Y=GS2.1.s1758706348$o72$g0$t1758706348$j60$l0$h0; JSESSIONID=0E4CAACA6BDE74BD91EF6DAAA659DDFA; OAuth_Token_Request_State=53cf3f53-7b9f-4b0f-b74c-54b85729e3da; _ga_THXT2YWNHR=GS2.1.s1758706349$o116$g0$t1758706350$j59$l0$h0; access_token=eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3NTg3NDk1OTMsImlhdCI6MTc1ODcwNjM5MywiYXV0aF90aW1lIjoxNzU4NzA2MzkzLCJqdGkiOiI1YWM1NTM3OC1lNzc0LTQ4OTgtYmNjOC01ZjkxYmJmZTNiNDkiLCJpc3MiOiJodHRwczovL3Nzby1zaWFzbi5ia24uZ28uaWQvYXV0aC9yZWFsbXMvcHVibGljLXNpYXNuIiwiYXVkIjoiYWNjb3VudCIsInN1YiI6IjdjYmQyYTJkLTlhNDAtNGQ2Zi05NWMwLWYxZWMyYmRkODc3MCIsInR5cCI6IkJlYXJlciIsImF6cCI6ImFkbWluLXNzY2FzbiIsInNlc3Npb25fc3RhdGUiOiJlNzU3NzUyMy01MjRjLTQ4OTItYThmMC02YTMyNWJkNDRmMzIiLCJhY3IiOiIxIiwicmVhbG1fYWNjZXNzIjp7InJvbGVzIjpbIm9mZmxpbmVfYWNjZXNzIiwidW1hX2F1dGhvcml6YXRpb24iLCJyb2xlOnNpYXNuLWluc3RhbnNpOnByb2ZpbGFzbjp2aWV3cHJvZmlsIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJvcGVuaWQgZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IkFITUFEIFpBS1kiLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiIxOTg3MDMxNTIwMjQyMTEwMTAiLCJnaXZlbl9uYW1lIjoiQUhNQUQiLCJmYW1pbHlfbmFtZSI6IlpBS1kiLCJlbWFpbCI6ImFobWFkN2FreUBnbWFpbC5jb20ifQ.N6oIHwdp8kia7Oy1VjblE908tnbRyq5ypxua8lKJiUhrW-JhrwOHiRMK7M2VyX6gZdT5NJHE8NrMHWkh_TYz65jx_bm6JwY65EWOhsvthGMmGoJRh45Rs4N5Pu4HWNOnt5ja8olr36R8Ssl_8Jhajg82ctxpyavxy1hD0oh-j7two7tyQ1EPWvc9_SkZB-Oyt9BKTKYeF_iyj3S_FrLxV9nAhhxFEZEYqL1aaLhgh2I5hlemDXu5jI5D_xQnHjapx88TJGJnA9JZm5p-rjKV9QdpBiUlgT8U5UpDB7nocKHgvPrrcZMZoxTf_2cziAvzMLP3StAcjx8vtk7X79KrIg'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
        print_r($param);
        // $response = json_decode($response);
        // return $response;

    }

    function checkNik($nik) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://admin-sscasn.bkn.go.id/permasalahan/cekNik?nik='.$nik.'&surat=8a0085469979dff301997b16676f032b&parameter=a0227860824811f0bcca005056b4779b',
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
                'Cookie: _ga_NVRKS9CPBG=GS1.1.1737046729.23.1.1737046734.0.0.0; _ga_6L51PKND1M=GS1.1.1738813705.5.0.1738813705.0.0.0; _ga=GA1.1.1409040940.1726985318; _ga_DRZFS4E65W=GS2.1.s1746360884$o4$g1$t1746363331$j0$l0$h0; _ga_0814XD8D5J=GS2.1.s1746363331$o5$g0$t1746363331$j0$l0$h0; _ga_LM1J7YFLJ2=GS2.1.s1746363331$o3$g0$t1746363331$j0$l0$h0; _ga_35R96W5J1R=GS2.1.s1747740545$o1$g0$t1747740545$j0$l0$h0; _ga_SXQ0KJ7TRT=GS2.1.s1753243952$o5$g0$t1753243952$j60$l0$h0; _ga_5GQ07M1DL1=GS2.1.s1753674916$o13$g1$t1753674982$j58$l0$h0; _ga_XSYPSH7VH8=GS2.1.s1755522993$o8$g1$t1755523125$j8$l0$h0; _ga_P60EQWGT4B=GS2.1.s1756290538$o13$g0$t1756290538$j60$l0$h0; _ga_3WZHF169ZR=GS2.1.s1756299252$o4$g0$t1756299252$j60$l0$h0; _ga_794MMPGMHD=GS2.1.s1756345795$o2$g0$t1756345795$j60$l0$h0; _ga_NWFY5EYRQR=GS2.1.s1756793950$o8$g1$t1756793967$j43$l0$h0; _ga_0WNXKJR9W3=GS2.1.s1756881181$o11$g1$t1756881369$j42$l0$h0; _ga_2CYP8KRT04=GS2.1.s1757919298$o8$g0$t1757919298$j60$l0$h0; _ga_REM1P5T4RV=GS2.1.s1758114617$o1$g0$t1758114622$j55$l0$h0; _ga_SN3ET00JF7=GS2.1.s1758630817$o38$g1$t1758630828$j49$l0$h0; _ga_D3VMVHCY1Y=GS2.1.s1758706348$o72$g0$t1758706348$j60$l0$h0; 9760101acce488cfa3a2041bbb8bb77f=8e17c26a3e9590758ba9e1fbe53759e8; BIGipServerpool_prod_sscasn2024_kube=3406324746.47873.0000; JSESSIONID=B8C8EF8264183304CCB7423354EEDFB1; OAuth_Token_Request_State=3f6258f0-6704-4bfa-83f1-c88c74e04722; _ga_THXT2YWNHR=GS2.1.s1758777944$o117$g0$t1758777946$j58$l0$h0; access_token=eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3NTg4MjExNTksImlhdCI6MTc1ODc3Nzk1OSwiYXV0aF90aW1lIjoxNzU4Nzc3OTU5LCJqdGkiOiJkOTMyZDFhOC1lNTMyLTQyMjYtYWRjZS01OTBjZDQ3Yjk4NjQiLCJpc3MiOiJodHRwczovL3Nzby1zaWFzbi5ia24uZ28uaWQvYXV0aC9yZWFsbXMvcHVibGljLXNpYXNuIiwiYXVkIjoiYWNjb3VudCIsInN1YiI6IjdjYmQyYTJkLTlhNDAtNGQ2Zi05NWMwLWYxZWMyYmRkODc3MCIsInR5cCI6IkJlYXJlciIsImF6cCI6ImFkbWluLXNzY2FzbiIsInNlc3Npb25fc3RhdGUiOiI5YmY2YWM1My0xYWQ2LTQ3MDQtYTIzZi04MDgzNDMzZDU5YWEiLCJhY3IiOiIxIiwicmVhbG1fYWNjZXNzIjp7InJvbGVzIjpbIm9mZmxpbmVfYWNjZXNzIiwidW1hX2F1dGhvcml6YXRpb24iLCJyb2xlOnNpYXNuLWluc3RhbnNpOnByb2ZpbGFzbjp2aWV3cHJvZmlsIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJvcGVuaWQgZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IkFITUFEIFpBS1kiLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiIxOTg3MDMxNTIwMjQyMTEwMTAiLCJnaXZlbl9uYW1lIjoiQUhNQUQiLCJmYW1pbHlfbmFtZSI6IlpBS1kiLCJlbWFpbCI6ImFobWFkN2FreUBnbWFpbC5jb20ifQ.RczGRB4INQAL8nl3tagOKHn5S_JQi6bwoRoVDf6Gw_CAAEGNe9swCjWLEWsJhH5fvlmRzSRVV4AL3INPOZ5eRAs2B8nm5yBszhn4Y8e-KHZLHBbzDIFlwoBPATB1n0_mEoujYOY5ysVfmhI_mInKjPrkKjxdgJNCNNDu3RZeG08oa34SyctKLLqWGpJHD0zSiK6IRG72y-88lPSJ4kGm8SXYGRqzEZMtOV1teITsoAvpH-DxXfX-1v-kMXRzZLYNhBvryBV6fOEfM-vMhSjnYlaFXL7cUKrI36dkn3HOW_3prVAVk2ZqHreuM17qI53mo1dx95fUJpA9ihjVTo5jwQ'
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
