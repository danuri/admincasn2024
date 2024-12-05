<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7  align-self-center  my-4">
                <div class="sub-header align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100"><h4 class="mb-0">Penguji</h4></p>
                        <?php if(strtotime('2025-12-10 22:00:00') > strtotime(date('Y-m-d H:i:s'))){ ?>
                        <!-- <button type="button" class="btn btn-success float-right" onclick="addpenguji()"><i class="zmdi zmdi-plus"></i>Tambah Penguji</button> -->
                        <a href="javascript:;" class="btn btn-primary" onclick="addpenguji()">Tambah Penguji <i class="fas fa-plus"></i></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4 mt-3">
                <div class="card">
                    <div class="card-content border-bottom border-primary border-w-5">
                        <h2 class="text-center"><?php echo rupiah(count($penguji));?></h2>
                        <h6 class="text-center">Jumlah</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('message'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($validation) && $validation->getErrors()): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <?php foreach ($validation->getErrors() as $error): ?>
                      <p><?= esc($error) ?></p>
                  <?php endforeach; ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>
            <div class="card-body">
            <ul>
                <li>Download Template Pakta Integritas: <a href="<?php echo base_url();?>downloads/template-pakta-integritas.docx" class="text-danger">Download</a> (Bermaterai)</li>
                <li>Untuk Penguji yang tidak Memiliki NIP, Gunakan NIK.</li>
                <li>Untuk 1 Penguji yang menguji praktik kerja sekaligus wawancara diinput 2 kali.</li>
                <li>Akses aplikasi Penguji <a href="http://skbcpns.kemenag.go.id/" target="_blank">http://skbcpns.kemenag.go.id/</a></li>
                <li>Username lihat di tabel. Untuk password menggunakan NIP/NIK</li>
            </ul>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover datacpns">
                <thead class="text-center">
                    <tr>
                    <th>NIP/NIK</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Agama</th>
                    <th>Jabatan</th>
                    <th>Ujian</th>
                    <th>Pakta Integritas</th>
                    <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($penguji as $row) {
                    ?>
                    <tr>
                        <td><?php echo $row->nip;?></td>
                        <td><?php echo $row->nama;?></td>
                        <td class="text-danger"><?php echo ($row->type=='Wawancara')?'interview_'.$row->nip:'praker_'.$row->nip;?></td>
                        <td><?php echo $row->agama;?></td>
                        <td><?php echo $row->jabatan;?></td>
                        <td><b><?php echo $row->type;?></b></td>
                        <td>
                        <?php if(file_exists("./downloads/$row->kode_satker-$row->nip.pdf")){ ?>
                            <a href="<?php echo base_url('downloads/'.$row->kode_satker.'-'.$row->nip.'.pdf');?>" target="_blank">Lihat</a>
                        <?php }else{ ?>
                            Belum Upload
                        <?php } ?>
                        <form class="" action="<?php echo site_url('skb/penguji/addfile');?>" method="post" enctype="multipart/form-data" id="form<?php echo $row->id;?>">
                            <input type="hidden" name="nip" value="<?php echo $row->nip;?>">
                            <input type="file" name="lampiran" id="lampiran<?php echo $row->id;?>" class="form-control" onchange="$('#form<?php echo $row->id;?>').submit()" />
                        </form>
                        </td>
                        <?php if(strtotime('2025-01-05 00:00:00') > strtotime(date('Y-m-d H:i:s'))){ ?>
                        <td><a href="javascript:;" onclick="detail(<?php echo $row->id;?>)">Edit</a> | <a href="<?php echo site_url('skb/penguji/delete/'.$row->id); ?>">Delete</a></td>
                    <?php }else{ ?>
                        <td></td>
                    <?php } ?>
                    </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addpenguji" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="title" id="defaultModalLabel">Tambah Penguji</h4>
          <div id="progress"></div>
        </div>
        <div class="modal-body" id="">
          <form class="" action="<?php echo site_url('skb/penguji/add');?>" method="post" id="tambahpenguji" enctype="multipart/form-data">
            <label for="">NIP/NIK</label>
            <div class="input-group input-group-sm">
              <input type="text" class="form-control" name="nip" id="nip">
              <span class="input-group-btn">
                <button type="button" class="btn btn-info btn-flat" onclick="getpegawai();">Ambil Data</button>
              </span>
            </div>
            <div class="form-group">
			  <img src="https://ropeg.kemenag.go.id/webview/avatar/image/20101" id="foto">
			  Jika tidak ada foto, harap update di simpeg
            </div>
            <div class="form-group">
              <label for="">Nama</label>
              <input type="text" class="form-control" name="nama" id="nama">
            </div>
			<div class="form-group">
              <label for="">Tempat Lahir</label>
              <input type="text" class="form-control" name="tpt_lahir" id="tpt_lahir">
            </div>
			<div class="form-group">
              <label for="">Tanggal Lahir</label>
              <input type="text" class="form-control" name="tgl_lahir" id="tgl_lahir">
            </div>
            <div class="form-group">
              <label for="">Jenis Kelamin</label>
              <input type="text" class="form-control" name="jk" id="jk">
            </div>
            <div class="form-group">
              <label for="">Agama</label>
              <input type="text" class="form-control" name="agama" id="agama">
            </div>
            <div class="form-group">
              <label for="">Satuan Kerja</label>
              <input type="text" class="form-control" name="satker" id="satker">
            </div>
            <div class="form-group">
              <label for="">Pendidikan</label>
              <input type="text" class="form-control" name="pendidikan" id="pendidikan">
            </div>
            <div class="form-group">
              <label for="">Jabatan</label>
              <input type="text" class="form-control" name="jabatan" id="jabatan">
            </div>
            <div class="form-group">
              <label for="">Jenis Ujian</label>
              <select class="form-control" name="type" id="type">
                <option value="Praktik Kerja">Praktik Kerja</option>
                <option value="Wawancara">Wawancara</option>
              </select>
            </div>
            <div class="form-group">
              <label for="">Pakta Integritas</label>
              <input type="file" class="form-control" name="lampiran" id="lampiran">
			  <input type="hidden" name="nip_lama" id="nip_lama">
            </div>
          </form>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary waves-effect" onclick="$('#tambahpenguji').submit()">SIMPAN</button>
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">BATAL</button>
      </div>
    </div>
  </div>
  </div>

   <div class="modal fade" id="editpenguji" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="title" id="defaultModalLabel">Edit Penguji</h4>
          <div id="progress"></div>
        </div>
        <div class="modal-body" id="bodydetail">
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary waves-effect" onclick="$('#updatepenguji').submit()">SIMPAN</button>
        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">BATAL</button>
      </div>
    </div>
  </div>
  </div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url();?>assets/vendors/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url();?>assets/vendors/datatable/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    // $('#datatable').DataTable();
    $('.datacpns').DataTable({
      dom: 'Bfrtip',
      lengthMenu: [
              [ 10, 25, 50, -1 ],
              [ '10 rows', '25 rows', '50 rows', 'Show all' ]
          ],
      buttons: [
        'pageLength','copy',
        {
              extend: 'excel',
              exportOptions: {
                  orthogonal: 'sort'
              },
              customizeData: function ( data ) {
                  for (var i=0; i<data.body.length; i++){
                      for (var j=0; j<data.body[i].length; j++ ){
                          data.body[i][j] = '\u200C' + data.body[i][j];
                      }
                  }
              }
              }
      ]
  	});

    //$('.select2').select2();
  });

  function addpenguji() {
    $('#tambahpenguji').trigger('reset');
    $('#addpenguji').modal('show');
  }

  function getpegawai()
  {
  	$('#infoget').html('Loading...');
  	var nip = $('#nip').val();
  	$.get('<?php echo site_url('skb/penguji/getpegawai');?>/'+nip, function(result) {

      if(!result.NAMA_LENGKAP){
        $('#infoget').html('Data tidak ditemukan...');
        return false;
      }
      $('#infoget').html('');
      // var obj = jQuery.parseJSON( result );

      $('#nama').val(result.NAMA_LENGKAP);
      $('#nip').val(result.NIP_BARU);
      $('#nip_lama').val(result.NIP);
      $('#pendidikan').val(result.PENDIDIKAN);
      $('#jabatan').val(result.KETERANGAN);
      $('#agama').val(result.AGAMA);
      $('#alamat').val(result.ALAMAT_1+' '+result.ALAMAT_2);
      $('#satker').val(result.SATKER_1+' '+result.SATKER_2+' '+result.SATKER_3+' '+result.SATKER_4);
      $('#jk').val(setjk(result.JENIS_KELAMIN));
      $('#tgl_lahir').val(result.TANGGAL_LAHIR);
      $('#tpt_lahir').val(result.TEMPAT_LAHIR);
      $('#foto').attr('src','https://ropeg.kemenag.go.id/webview/avatar/image/'+result.NIP);

    });
  }

  function setjk(jk)
  {
  	if(jk == '1'){
  		return 'Laki-Laki';
  	}else{
  		return 'Perempuan';
  	}
  }

  function detail(id) {
	  $('#bodydetail').load('<?php echo site_url('skb/penguji/get_detail');?>/'+id.toString());
	  $('#editpenguji').modal('show');
	}
</script>
<?= $this->endSection() ?>