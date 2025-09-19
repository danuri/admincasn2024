<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Peserta Prioritas Paruh Waktu</h4>
                    <div class="page-title-right">
                      <a href="<?= base_url('paruhwaktu/export') ?>" class="btn btn-primary">Download</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
          <div class="col-xl-12">

            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table align-middle table-striped-columns mb-0 datatable">
                    <thead>
                      <tr>
                        <th>NIK</th>
                        <th>No Peserta</th>
                        <th>Nama</th>
                        <th>Penempatan</th>
                        <th>Jabatan</th>
                        <th>Status DRH</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($peserta as $row) {?>
                      <tr>
                        <td><?= $row->nik?></td>
                        <td><?= $row->no_peserta?></td>
                        <td><?= $row->nama_peserta?></td>
                        <td><?= $row->instansi_paruh_waktu?></td>
                        <td><?= $row->jabatan_melamar?></td>
                        <td><?= $row->status_drh?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="card">
              <div class="card-body">
                <form action="<?= site_url('paruhwaktu/uploaddok')?>" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="websiteUrl" class="form-label">SPTJM</label>
                        </div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="file" class="form-control" name="dokumen" id="dokumensptjm" aria-describedby="dokumensptjmAdd" aria-label="Upload">
                                <?php
                                if($user->paruhwaktu_sptjm){
                                  echo '<a href="https://ropeg.kemenag.go.id:9000/pengadaan/pppk/'.$user->paruhwaktu_sptjm.'" class="btn btn-primary" id="dokumensptjmAdd" target="_blank">Lihat Surat</a>';
                                }?>
                                <button class="btn btn-outline-success" type="submit" id="dokumensptjmAdd">Upload</button>
                            </div>
                        </div>
                    </div>
                </form>    
              </div>
              <div class="card-footer">
                  <p>Unggah surat usul dan SPTJM dalam 1 file. <a href="<?= base_url('assets/template_sptjm_paruhwaktu.pdf')?>" target="_blank">Download Template</a></p>
              </div>
            </div>
          </div>
        </div>

    </div>
</div>

<div class="modal fade" id="mapping" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="title" id="defaultModalLabel">Rincian Paruh Waktu</h4>
          <div id="progress"></div>
        </div>
        <div class="modal-body" id="">
          <form class="" action="<?= site_url('paruhwaktu/setusul');?>" method="post" id="mapform" enctype="multipart/form-data">
            <div class="form-group">
              <label for="">Status Usulan</label>
              <div class="form-check form-switch form-switch-md">
                  <label for="status-usulan-form" class="form-label fieldstatus">Diusulkan</label>
                  <input class="form-check-input" type="checkbox" id="status-usulan-form" checked>
                  <input type="hidden" name="status_usulan" id="status_usulan" value="1">
                  <input type="hidden" name="nik" id="nik" value="">
              </div>
            </div>
            <hr>
            <div class="ditolak d-none">
              <div class="form-group">
                <label for="">Alasan Tidak Diusulkan</label>
                <select class="form-control" name="alasan_tidak_diusulkan" id="alasan_tidak_diusulkan" required>
                  <option value="1">Meninggal Dunia</option>
                  <option value="2">Tidak Aktif Bekerja</option>
                  <option value="3">Tidak Ada Kebutuhan Organisasi</option>
                  <option value="4">Tidak Tersedia Anggaran</option>
                </select>
              </div>
            </div>
            <div class="diusulkan">
              <div class="form-group">
                <label for="">Pendidikan</label>
                <select class="form-control" name="pendidikan_id" id="pendidikan_id" required>
                  <option value="d5ba481b59fd483d95d42fc0d311390b">SD/SEDERAJAT</option>
                  <option value="4da8e613c6584db19a0774f8df4a3490">SLTP/SMP SEDERAJAT</option>
                  <option value="8ae4828947fbda2a01481ad629e5545f">SLTA/SMA SEDERAJAT</option>
                  <option value="0E7FA326159D8672E060640AF1083075">D-III SEMUA JURUSAN</option>
                  <option value="0E7FA32614D38672E060640AF1083075">S-1/D-IV Semua Jurusan</option>
                  <option value="0E7FA32616438672E060640AF1083075">S-2 SEMUA JURUSAN (Khusus Dosen)</option>
                  <option value="39095cf0e4c147a4924da21ad7c0bdf6">S-3 SEMUA JURUSAN (Khusus Dosen)</option>
                </select>
              </div>
              <div class="form-group">
                <label for="">Unit Penempatan</label>
                <select class="form-select" id="searchunor" name="unor"></select>
                <input type="hidden" name="siasnname" id="siasnname">
                <p><a href="https://docs.google.com/spreadsheets/d/1ymCsw7IzhMncnxhpn950gLc8ouDKYswn/edit?usp=sharing&ouid=113552501988446619415&rtpof=true&sd=true" target="_blank">Lihat Referensi</a></p>
              </div>
            </div>
          </form>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary waves-effect" onclick="$('#mapform').submit()">SIMPAN</button>
        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">TUTUP</button>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    // switch checkbox status-usulan-form
    $('#status-usulan-form').change(function() {
      if ($(this).is(':checked')) {
        $('.ditolak').addClass('d-none');
        $('.diusulkan').removeClass('d-none');
        $('.fieldstatus').text('Diusulkan');
        $('#status_usulan').val(1);
      } else {
        $('.ditolak').removeClass('d-none');
        $('.diusulkan').addClass('d-none');
        $('.fieldstatus').text('Tidak Diusulkan');
        $('#status_usulan').val(0);
      }
    });

  $('#searchunor').select2({
        dropdownParent: $('#mapping'),
        ajax: {
            url: '<?= site_url() ?>ajax/searchunor',
            data: function (params) {
            var query = {
                search: params.term,
                type: 'public'
            }

            return query;
            },
            processResults: function (data) {
            return {
                results: data
            };
            },
            processResults: (data, params) => {
                const results = data.map(item => {
                return {
                    id: item.id_unor,
                    text: item.unor_lengkap,
                };
                });
                return {
                results: results,
                }
            },
        },
        placeholder: 'Cari dengan nama atau ID Unor',
        minimumInputLength: 5,
        });

        $('#searchunor').on('change', function() {
            var data = $('#searchunor').select2('data');
            $('#siasnname').val(data[0].text);
        });
  });

  function usul(check) {
        if (check.checked) {
            $.get('<?= site_url('paruhwaktu/setusul'); ?>/' + check.id + '/1', function(e) {
                console.log(e);
                alert('Peserta diusulkan');
            });
        } else {
            $.get('<?= site_url('paruhwaktu/setusul'); ?>/' + check.id + '/0', function() {
                alert('Peserta tidak diusulkan');
            });
        }
        // cekVerifikasi();
    }

  function setusul(nik) {
    $('#nik').val(nik);
    $('#mapform').trigger('reset');
    $('#mapping').modal('show');
  }
</script>
<?= $this->endSection() ?>
