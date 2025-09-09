<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PendidikanModel;
use App\Models\UnorModel;
use App\Models\PesertaModel;
use App\Models\Pppkt2Model;

class Ajax extends BaseController
{
    public function index()
    {
        //
    }

    public function searchunor()
    {
      $model = new UnorModel;
      $search = $this->request->getVar('search');

      $data = $model->like('unor_lengkap', $search, 'both')->orWhere('id_unor',$search)->findAll();
      return $this->response->setJSON($data);
    }

    function getmonitoring($limit, $offset) {
        $model = new Pppkt2Model;
        $data= $model->orderBy('nopeserta', 'asc')->findAll($limit,$offset);

        foreach($data as $row){
            $this->monitoringusulnip($row->nopeserta,2024,'02','0208',1,0);
        }

        $no = $offset+$limit;

        echo '<a href="'.site_url('ajax/getmonitoring/'.$limit.'/'.$no).'">Next</a>';
    }

    function monitoringusulnip($no_peserta,$tahun,$jenis,$jenis_formasi_id,$limit,$offset) {
      $client = service('curlrequest');
      $cache = service('cache');

      // $token = $cache->get('siasn_token');
    //   $token = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3NTY3MzkwMTcsImlhdCI6MTc1NjY5NjA0OSwiYXV0aF90aW1lIjoxNzU2Njk1ODE3LCJqdGkiOiI0ODllNGExMi0xMGRlLTQxODctOGQ1YS1hMDI2ZGQ2M2YxNTAiLCJpc3MiOiJodHRwczovL3Nzby1zaWFzbi5ia24uZ28uaWQvYXV0aC9yZWFsbXMvcHVibGljLXNpYXNuIiwiYXVkIjoiYWNjb3VudCIsInN1YiI6ImVjNzEwYmU2LWY5MjctNDkyZC04M2JjLWY4YTdmZWI3ZGMzYyIsInR5cCI6IkJlYXJlciIsImF6cCI6InNpYXNuLWluc3RhbnNpIiwibm9uY2UiOiJmNmE3YmE0Ny0zZDA0LTQwZTQtOWFkMi0xYTU0N2QwOGQ5N2EiLCJzZXNzaW9uX3N0YXRlIjoiYzU4NmZhNTItM2Q3Yi00NmI2LThhNjEtZjUxYjdmMGZiN2RkIiwiYWNyIjoiMCIsImFsbG93ZWQtb3JpZ2lucyI6WyJodHRwczovL3NpYXNuLWluc3RhbnNpLmJrbi5nby5pZCIsImh0dHA6Ly9zaWFzbi1pbnN0YW5zaS5ia24uZ28uaWQiLCJodHRwOi8vbG9jYWxob3N0OjMwMDAiXSwicmVhbG1fYWNjZXNzIjp7InJvbGVzIjpbInJvbGU6aW11dC1pbnN0YW5zaTptb25pdG9yaW5nIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjppbnN0YW5zaS1vcGVyYXRvci1zb3RrIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW1iZXJoZW50aWFuOmFwcHJvdmFsX2l6aW5fcHBwayIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46dXN1bC1yaW5jaWFuLWZvcm1hc2kiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTppbXV0LWluc3RhbnNpOm9wZXJhdG9yIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW5nYWRhYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnByb2ZpbGFzbjp2aWV3cHJvZmlsIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW5nYWRhYW46YXBwcm92YWwiXX0sInJlc291cmNlX2FjY2VzcyI6eyJhY2NvdW50Ijp7InJvbGVzIjpbIm1hbmFnZS1hY2NvdW50IiwibWFuYWdlLWFjY291bnQtbGlua3MiLCJ2aWV3LXByb2ZpbGUiXX19LCJzY29wZSI6Im9wZW5pZCBlbWFpbCBwcm9maWxlIiwiZW1haWxfdmVyaWZpZWQiOmZhbHNlLCJuYW1lIjoiTVVIQU1NQUQgQkFTV09STyIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5ODUwMjA1MjAwOTEyMTAwNSIsImdpdmVuX25hbWUiOiJNVUhBTU1BRCIsImZhbWlseV9uYW1lIjoiQkFTV09STyIsImVtYWlsIjoia2Vtb2QxODJAZ21haWwuY29tIn0.KEIhf-Q2ghksyYMUeqh9VqQQAyllLfnRfmH15yblW2spcp_UvzT3_XXYtSQZbgj9IbKlfWD5uMm_PrXAcCQNKKnF0U2MXtoo0Uub1WbZI3pZQDkDC0H1jjSuOMDVHPErrfK4tUi5MYOvb7oDHD8uQ8EOI-DInyE-A1-rKCjGAcCbF_VULbW3p6FkoVC5-5cfILMMXuTn_TQ36DzQ8JXFGjJ6OhQZ8PE8kMwa6UJi2DE-Fc7UPdf3aYLpqmgZjjn-eYqZaBqlyzOyBEEqtKz5GWNGfodVxLJWHBBhJFCUy3qvD6hNOzyKD2h-lIu50CvlwX6xBKPcdaZJBXCkQadsgg';
        $token = $cache->get('zxc');
    //   $response = $client->request('GET', 'https://api-siasn.bkn.go.id/siasn-instansi/pengadaan/usulan/monitoring?jenis_pengadaan_id='.$jenis.'&status_usulan=&periode='.$tahun.'&limit='.$limit.'&offset='.$offset, [
      $response = $client->request('GET', 'https://api-siasn.bkn.go.id/siasn-instansi/pengadaan/usulan/monitoring?no_peserta='.$no_peserta.'&jenis_pengadaan_id='.$jenis.'&jenis_formasi_id='.$jenis_formasi_id.'&periode='.$tahun.'&limit='.$limit.'&offset='.$offset, [
          'headers' => [
              'Authorization'     => 'Bearer '.$token,
          ],
          'verify' => false,
          'debug' => true,
      ]);

      // return $this->response->setJSON( $response->getBody() );
      $data = json_decode($response->getBody());

      foreach ($data->data as $row) {
          $nopeserta = $row->usulan_data->data->no_peserta;
          $id = $row->id;
          $status = siasn_usul_status($row->status_usulan);
          $alasan = $row->alasan_tolak_tambahan;
          $pathpertek = $row->path_ttd_pertek; 
          $nopertek = $row->no_pertek;
          $nip = $row->nip;

          $model = new Pppkt2Model;
          $model->set('usul_id', $id);
          $model->set('usul_status', $status);
          $model->set('usul_alasan_tolak', $alasan);
          $model->set('usul_path_ttd_pertek', $pathpertek);
          $model->set('usul_no_pertek', $nopertek);
          $model->set('usul_nip', $nip);
          $model->where('nopeserta', $nopeserta);
          $model->update();

        //   echo '<pre>'.$no_peserta.'</pre>';
      }
  }

  function setidpeserta() {
    // $model = new Pppkt2Model;
    // $data= $model->where(['idpeserta'=>NULL])->first();
    $data = ['24301230810009646','24301230810022468','24301230810033648','24301230810036455','24301230810036476','24301230810036864','24301230810037074','24301230810037256','24301230810037453','24301230810037687','24301230810037928','24301230810038122','24301230810038237','24301230810038339','24301230810038447','24301230810038653','24301230810038829','24301230810038885','24301230810039012','24301230810039140','24301230810039247','24301230810039385','24301230810039526','24301230810039620','24301230810039748','24301230810039930','24301230810040016','24301230810040097','24301230810040266','24301230810040362','24301230810040488','24301230810040597','24301230810040731','24301230810040825','24301230810040987','24301230810041117','24301230810041187','24301230810041276','24301230810041399','24301230810041492','24301230810041570','24301230810041637','24301230810041775','24301230810041948','24301230810042080','24301230810042162','24301230810042248','24301230810042363','24301230810042506','24301230810042619','24301230810042664','24301230810042800','24301230810042918','24301230810043036','24301230810043201','24301230810043314','24301230810043484','24301230810043674','24301230810043818','24301230810043910','24301230810044045','24301230810044189','24301230810044292','24301230810044449','24301230810044599','24301230810044807','24301230810044942','24301230810045144','24301230810045330','24301230810045552','24301230810045767','24301230810045929','24301230810046109','24301230810046301','24301230810046436','24301230810046530','24301230810046691','24301230810046832','24301230810046889','24301230810046937','24301230810047068','24301230810047309','24301230810047448','24301230810047600','24301230810047698','24301230810047723','24301230810047844','24301230810047967','24301230810048087','24301230810048175','24301230810048378','24301230810048560','24301230810048644','24301230810048734','24301230810048957','24301230810049074','24301230810049306','24301230810049419','24301230810049551','24301230810049721','24301230810049848','24301230810049998','24301230810050160','24301230810050358','24301230810050496','24301230810050705','24301230810050811','24301230810050959','24301230810051078','24301230810051313','24301230810052348','24301230810052455','24301230820001832','24301230820017314','24301230820034854','24301230820036068','24301230820036146','24301230820036337','24301230820036610','24301230820036706','24301230820036787','24301230820036874','24301230820037094','24301230820037146','24301230820037309','24301230820037438','24301230820037526','24301230820037709','24301230820037830','24301230820037971','24301230820038065','24301230820038174','24301230820038275','24301230820038392','24301230820038476','24301230820038587','24301230820038685','24301230820038801','24301230820038910','24301230820039044','24301230820039158','24301230820039257','24301230820039412','24301230820039536','24301230820039686','24301230820039788','24301230820039963','24301230820040085','24301230820040247','24301230820040325','24301230820040418','24301230820040533','24301230820040702','24301230820040790','24301230820040964','24301230820041092','24301230820041226','24301230820041347','24301230820041491','24301230820041594','24301230820041666','24301230820041810','24301230820041924','24301230820042100','24301230820042230','24301230820042398','24301230820042550','24301230820042709','24301230820042848','24301230820042989','24301230820043665'];

    $model = new Pppkt2Model;
    // loop data
    for($i=0; $i < count($data); $i++){
        $res = $this->cekNik($data[$i]);
        if(isset($res->result->id)){
            $model->set('idpeserta', $res->result->id);
            $model->where('nopeserta', $data[$i]);
            $model->update();
            echo '<pre>'.$data[$i].' => '.$res->result->id.'</pre>';
        } else {
            echo '<pre>'.$data[$i].' => NOT FOUND</pre>';
        }
    }

    // foreach($data as $row){
    //     $res = $this->cekNik($row->nopeserta);
    //     $model = new Pppkt2Model;
    //     $model->set('idpeserta', $res->result->id);
    //     $model->where('nopeserta', $row->nopeserta);
    //     $model->update();
    // }
  }

  function cekNik() {

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://admin-sscasn.bkn.go.id/permasalahan/cekNik?nik=24301230810047309&surat=8a03838c992742f5019928df0f720001&parameter=f147f3903f5111efa54d0050568fed0f',
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
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36',
        'X-Requested-With: XMLHttpRequest',
        'sec-ch-ua: "Not;A=Brand";v="99", "Google Chrome";v="139", "Chromium";v="139"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Windows"',
        'Cookie: _ga_NVRKS9CPBG=GS1.1.1737046729.23.1.1737046734.0.0.0; _ga_6L51PKND1M=GS1.1.1738813705.5.0.1738813705.0.0.0; _ga=GA1.1.1409040940.1726985318; _ga_DRZFS4E65W=GS2.1.s1746360884$o4$g1$t1746363331$j0$l0$h0; _ga_0814XD8D5J=GS2.1.s1746363331$o5$g0$t1746363331$j0$l0$h0; _ga_LM1J7YFLJ2=GS2.1.s1746363331$o3$g0$t1746363331$j0$l0$h0; _ga_35R96W5J1R=GS2.1.s1747740545$o1$g0$t1747740545$j0$l0$h0; _ga_SXQ0KJ7TRT=GS2.1.s1753243952$o5$g0$t1753243952$j60$l0$h0; _ga_5GQ07M1DL1=GS2.1.s1753674916$o13$g1$t1753674982$j58$l0$h0; _ga_XSYPSH7VH8=GS2.1.s1755522993$o8$g1$t1755523125$j8$l0$h0; _ga_2CYP8KRT04=GS2.1.s1756173377$o3$g0$t1756173377$j60$l0$h0; _ga_P60EQWGT4B=GS2.1.s1756290538$o13$g0$t1756290538$j60$l0$h0; _ga_3WZHF169ZR=GS2.1.s1756299252$o4$g0$t1756299252$j60$l0$h0; _ga_794MMPGMHD=GS2.1.s1756345795$o2$g0$t1756345795$j60$l0$h0; _ga_NWFY5EYRQR=GS2.1.s1756793950$o8$g1$t1756793967$j43$l0$h0; _ga_0WNXKJR9W3=GS2.1.s1756881181$o11$g1$t1756881369$j42$l0$h0; 9760101acce488cfa3a2041bbb8bb77f=7fd80907be8d9553c810f0421f97e5c9; BIGipServerpool_prod_sscasn2024_kube=3406324746.47873.0000; _ga_D3VMVHCY1Y=GS2.1.s1757396551$o47$g1$t1757396575$j36$l0$h0; _ga_SN3ET00JF7=GS2.1.s1757404173$o18$g0$t1757404173$j60$l0$h0; JSESSIONID=317956EDF5E23911FB83E3B748D84337; OAuth_Token_Request_State=0e379f17-46e9-4c4e-b02a-d63612cd8120; _ga_THXT2YWNHR=GS2.1.s1757421028$o98$g0$t1757421030$j58$l0$h0; access_token=eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3NTc0NjQyNDUsImlhdCI6MTc1NzQyMTA0NSwiYXV0aF90aW1lIjoxNzU3NDIxMDQ1LCJqdGkiOiJmZDZkZDdhZi0yNTI0LTQ5YjktODc1NS02YjRmOGIyZDYyNjkiLCJpc3MiOiJodHRwczovL3Nzby1zaWFzbi5ia24uZ28uaWQvYXV0aC9yZWFsbXMvcHVibGljLXNpYXNuIiwiYXVkIjoiYWNjb3VudCIsInN1YiI6IjdjYmQyYTJkLTlhNDAtNGQ2Zi05NWMwLWYxZWMyYmRkODc3MCIsInR5cCI6IkJlYXJlciIsImF6cCI6ImFkbWluLXNzY2FzbiIsInNlc3Npb25fc3RhdGUiOiI5ZDRkNTMzNi04OWVmLTRhOTMtOWM4OS0zMWQ5ZWRmNzhjZDciLCJhY3IiOiIxIiwicmVhbG1fYWNjZXNzIjp7InJvbGVzIjpbIm9mZmxpbmVfYWNjZXNzIiwidW1hX2F1dGhvcml6YXRpb24iLCJyb2xlOnNpYXNuLWluc3RhbnNpOnByb2ZpbGFzbjp2aWV3cHJvZmlsIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJvcGVuaWQgZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IkFITUFEIFpBS1kiLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiIxOTg3MDMxNTIwMjQyMTEwMTAiLCJnaXZlbl9uYW1lIjoiQUhNQUQiLCJmYW1pbHlfbmFtZSI6IlpBS1kiLCJlbWFpbCI6ImFobWFkN2FreUBnbWFpbC5jb20ifQ.B2jrFjK7I9TrNjAAKAJbJanLi3s7ryFgE4vXKXH_LpEyqmlX0JXOCytLDgp0MhnnOLWAEHFUW9hNPW2mt_PAFbDRwKFyUuTQikhaPj7Rg9use7A70X0JC1FSI4QkzCpDVDiEXQ_kn1-wO-joUR7KbMPzD2e8riLP2uWqucpXpxF1oZGm9h8x9diXY87Sio8KxfzhUUQcog70LnHrsnkoXMPUMOPAqNXkLS9oaBTQKMk7c_3V8LhbGRHBG30Xw4gGH2UzmTA_ZQ3yDAatMQ78TN270AZmSDfTY-ozcRB_tDD5SKbqDXH6vyJk-RAkscWTRlXLaSUFAmI8MSBEu4Ko8A'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return json_decode($response);

  }
}
