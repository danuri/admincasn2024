<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Data Usulan</h4>
                    <div class="page-title-right">
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
                        <th>Nama</th>
                        <th>Pendidikan</th>
                        <th>Jabatan</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($paruhwaktu as $row) {?>
                      <tr>
                        <td><?= $row->nik?></td>
                        <td><?= $row->nama?></td>
                        <td><?= $row->pendidikan?></td>
                        <td><?= $row->jabatan?></td>
                        <td><?= $row->lokasi?></td>
                        <td>
                          <a href="<?= site_url('surat/delete/'.encrypt($row->id))?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Input Peserta</h5>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('surat/create')?>" method="POST" enctype="multipart/form-data">
                        <div class="row mb-4">
                            <label for="nik" class="col-sm-3 col-form-label">NIK</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                <input type="number" class="form-control" name="nik" id="nik" required>
                                <button class="btn btn-outline-success" type="button" id="cari">Cari</button>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="xnama" class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-9">
                                <input type="text" name="xnama" class="form-control" id="xnama" readonly>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="xjabatan" class="col-sm-3 col-form-label">Jabatan</label>
                            <div class="col-sm-9">
                                <input type="text" name="xjabatan" class="form-control" id="xjabatan" readonly>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="xpendidikan" class="col-sm-3 col-form-label">Pendidikan</label>
                            <div class="col-sm-9">
                                <input type="text" name="xpendidikan" class="form-control" id="xpendidikan" readonly>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="xpenempatan" class="col-sm-3 col-form-label">Penempatan</label>
                            <div class="col-sm-9">
                                <input type="text" name="xpenempatan" class="form-control" id="xpenempatan" readonly>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="pendidikan" class="col-sm-3 col-form-label">Penyesuaian Pendidikan</label>
                            <div class="col-sm-9">
                                <select name="pendidikan" id="pendidikan" class="form-control">
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="1005001">SD/SEDERAJAT</option>
                                    <option value="2005001">SLTP/SMP SEDERAJAT</option>
                                    <option value="3000006">SLTA/SMA SEDERAJAT</option>
                                    <option value="4480043">D-III SEMUA JURUSAN</option>
                                    <option value="5210074">-1 SEMUA JURUSAN</option>
                                    <option value="7400051">-2 SEMUA JURUSAN</option>
                                    <option value="9500363">-3 SEMUA JURUSAN</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="jabatan" class="col-sm-3 col-form-label">Penyesuaian Jabatan</label>
                            <div class="col-sm-9">
                                <select name="jabatan" id="jabatan" class="form-control">
                                    <option value="">Pilih Jabatan</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="penempatan" class="col-sm-3 col-form-label">Penyesuaian Lokasi</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="searchunor" name="unor"></select>
                                <input type="hidden" name="siasnname" id="siasnname">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {

    $('#cari').on('click', function(event) {
    $nik = $('#nik').val();

    if($nik == ''){
      alert('NIK tidak boleh kosong');
    }else{
      axios.get('<?= site_url()?>paruhwaktu/search/'+$nik)
      .then(function (response) {

        if(response.data.status){
          $('#nama').val(response.data.data.NAMA_LENGKAP);
          $('#jabatan').val(response.data.data.TAMPIL_JABATAN);
          $('#pangkat').val(response.data.data.PANGKAT);
          $('#golongan').val(response.data.data.GOL_RUANG);
          $('#satker').val(response.data.data.SATKER_3);
          $('#kode_satker').val(response.data.data.KODE_SATKER_3);
        }else{
          alert(response.data.message);
        }
      });
    }

  });

  $('#searchunor').select2({
        dropdownParent: $('#mapping'),
        ajax: {
            url: '<?= site_url() ?>ajax/searchlokasi',
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
                    id: item.id,
                    text: item.nama,
                };
                });
                return {
                results: results,
                }
            },
        },
        placeholder: 'Cari dengan nama atau ID Lokasi',
        minimumInputLength: 5,
        });

        $('#searchunor').on('change', function() {
            var data = $('#searchunor').select2('data');
            $('#siasnname').val(data[0].text);
        });
  });

  

</script>
<?= $this->endSection() ?>
