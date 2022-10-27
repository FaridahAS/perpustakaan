<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-6">
        <div class="card card-orange card-outline">
          <div class="card-header">
            <h3 class="card-title"><?= $header ?></h3>
            <div class="card-tools">
              <a href="<?= site_url('siswa') ?>" class="btn btn-tool"><i class="fas fa-times"></i></a>
            </div>
          </div>
          <div class="card-body">
            <form action="" method="post" id="form-data" enctype="multipart/form-data">
              <input type="hidden" name="id_siswa" value="<?= @$row->id_siswa ?>">
              <div class="form-group">
                <label for="nis">NIS</label>
                <input type="text" class="form-control" id="nis" name="nis" value="<?= @$row->nis ?>" placeholder="Nomor Induk Siswa" autocomplete="off">
                <span id="error-nis" class="error invalid-feedback"></span>
              </div>
              <div class="form-group">
                <label for="nama_siswa">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" value="<?= @$row->nama_siswa ?>" placeholder="Nama Lengkap" autocomplete="off">
                <span id="error-nama_siswa" class="error invalid-feedback"></span>
              </div>
              <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control select2" style="width: 100%;">
                  <option value="">-- Jenis Kelamin --</option>
                  <?php foreach (['L' => 'Laki-laki', 'P' => 'Perempuan'] as $key => $value): ?>
                    <option value="<?= $key ?>" <?php if ($key == @$row->jenis_kelamin) echo 'selected' ?>><?= $value ?></option>
                  <?php endforeach ?>
                </select>
                <span id="error-jenis_kelamin" class="error invalid-feedback"></span>
              </div>
              <div class="form-group">
                <label for="kelas_id">Kelas</label>
                <select name="kelas_id" id="kelas_id" class="form-control select2" style="width: 100%;">
                  <option value="">-- Kelas --</option>
                  <?php foreach ($kelas as $k): ?>
                    <option value="<?= $k->id_kelas ?>" <?php if ($k->id_kelas == @$row->kelas_id) echo 'selected' ?>><?= $k->nama_kelas ?></option>
                  <?php endforeach ?>
                </select>
                <span id="error-kelas_id" class="error invalid-feedback"></span>
              </div>
              <div class="form-group">
                <label for="no_handphone">No. Handphone</label>
                <input type="text" class="form-control" id="no_handphone" name="no_handphone" value="<?= @$row->no_handphone ?>" placeholder="No. Handphone" autocomplete="off">
                <span id="error-no_handphone" class="error invalid-feedback"></span>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <a href="<?= site_url('siswa') ?>" class="btn btn-secondary btn-sm" style="font-weight: bold;"><i class="fas fa-angle-double-left"></i> Batal</a>
            <button type="button" id="btn-save" class="btn btn-success btn-sm float-right" style="font-weight: bold;"><i class="fas fa-save"></i> Simpan</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
  $(function() {

    $('#btn-save').click(function() {
      $.ajax({
        url: '<?= base_url('save/siswa') ?>',
        type: 'POST',
        dataType: 'json',
        data: new FormData($('#form-data')[0]),
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function(response) {
          if (response.status) {
            window.location.href = '<?= base_url('siswa') ?>';
          } else {
            $.each(response.errors, function (key, val) {

                $('[name="' + key + '"]').addClass('is-invalid');
                $('#error-'+ key +'').text(val).show();

                if (val === '') {
                    $('[name="' + key + '"]').removeClass('is-invalid');
                    $('#error-'+ key +'').text('').hide();
                }

                $('[name="' + key + '"]').on('change keyup', function(event) {
                  $('[name="' + key + '"]').removeClass('is-invalid');
                  $('#error-'+ key +'').text('').hide();
                });
            });
          }

        }

      });

    });

  });

</script>