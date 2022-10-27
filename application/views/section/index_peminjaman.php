<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div id="response-data"></div>
        <div class="card card-orange card-outline">
          <div class="card-header">
            <h3 class="card-title">Daftar <?= $title ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" onclick="add_data();"><i class="fas fa-plus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="table" class="table table-bordered" style="width: 100%;">
                <thead>
                  <tr>
                    <?php

                    $thead = array(
                      '<th style="width: 5%; text-align: center;">No</th>',
                      '<th>Nama<span style="color: white;">_</span>Buku</th>',
                      '<th>Nama<span style="color: white;">_</span>Siswa</th>',
                      '<th>Tanggal<span style="color: white;">_</span>Peminjaman</th>',
                      '<th>Tanggal<span style="color: white;">_</span>Pengembalian</th>',
                      '<th style="width: 5%; text-align: center;">Aksi</th>',
                    );

                    $targets = array();
                    for ($i=0; $i < count($thead); $i++) { 
                      if ($i >= 1) {
                        $targets[] = $i;
                      }
                      echo $thead[$i];
                    }

                    ?>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modal-form">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="form-data">
          <input type="text" class="form-control" name="id_peminjaman" value="" style="display: none;">
          <div class="form-group">
            <label for="buku_id">Nama Buku</label>
            <select name="buku_id" id="buku_id" class="form-control select2" style="width: 100%;">
              <option value="">-- Nama Buku --</option>
              <?php foreach ($buku as $b): ?>
                <option value="<?= $b->id_buku ?>"><?= $b->kode_buku .' - '. $b->nama_buku ?></option>
              <?php endforeach ?>
            </select>
            <span id="error-buku_id" class="error invalid-feedback"></span>
          </div>
          <div class="form-group">
            <label for="siswa_id">Nama Siswa</label>
            <select name="siswa_id" id="siswa_id" class="form-control select2" style="width: 100%;">
              <option value="">-- Nama Siswa --</option>
              <?php foreach ($siswa as $s): ?>
                <option value="<?= $s->id_siswa ?>"><?= $s->nis .' - '. $s->nama_siswa ?></option>
              <?php endforeach ?>
            </select>
            <span id="error-siswa_id" class="error invalid-feedback"></span>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="font-weight: bold;"><i class="fas fa-angle-double-left"></i> Batal</button>
        <button type="button" onclick="save_data();" class="btn btn-success btn-sm" style="font-weight: bold;"><i class="fas fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(function() {

    table = $('#table').DataTable({
      "processing": false,
      "serverSide": true,
      "searching": true,
      "info": true,
      "ordering": true,
      "lengthChange": true,
      "autoWidth": false,
      "responsive": false,
      "language": { 
        "infoFiltered": "",
        "sZeroRecords": "",
        "sEmptyTable": "",
        "sSearch": "Cari:"
      },
      "order": [],
      "ajax": {
        "url": "<?= site_url('list/peminjaman') ?>",
        "type": "POST",
        "data": function(data) {

        },
      },
      "columnDefs": [{ 
        "targets": <?= json_encode($targets) ?>,
        "orderable": false,
      }],
    });

  });

  function add_data() {
    $('#form-data .form-control').val('').change();
    $('#form-data .form-control').removeClass('is-invalid');
    $('.modal-title').text('Tambah Peminjaman');
    $('#modal-form').modal('show');
  }

  function edit_data(id) {
    var buku_id   = $('[name="buku_id_'+ id +'"]').val();
    var siswa_id  = $('[name="siswa_id_'+ id +'"]').val();

    $('#form-data .form-control').val('').change();
    $('#form-data .form-control').removeClass('is-invalid');

    $('[name="id_peminjaman"]').val(id).change();
    $('[name="buku_id"]').val(buku_id).change();
    $('[name="siswa_id"]').val(siswa_id).change();
    $('.modal-title').text('Ubah Peminjaman');
    $('#modal-form').modal('show');
  }

  function save_data() {
    $.ajax({
      url: '<?= base_url('save/peminjaman') ?>',
      type: 'POST',
      dataType: 'json',
      data: new FormData($('#form-data')[0]),
      processData: false,
      contentType: false,
      cache: false,
      async: false,
      success: function(response) {
        if (response.status) {
          table.ajax.reload();
          $('#modal-form').modal('hide');
          $('#response-data').html('');
          $(window).scrollTop(0);
          $('<div class="alert alert-'+ response.type +' alert-dismissible" id="alert-data" style="font-weight: bold;">'+ response.message +'</div>').show().appendTo('#response-data');
          $('#alert-data').delay(2750).slideUp('slow', function(){
              $(this).remove();
          });
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
  }

  function delete_data(id) {
    if (confirm('Apakah anda yakin?')) {
      $.getJSON('<?= site_url('delete/peminjaman') ?>/' + id, function(response) {
        if (response.status) {
          table.ajax.reload();
          $('#response-data').html('');
          $(window).scrollTop(0);
          $('<div class="alert alert-success alert-dismissible" id="alert-data" style="font-weight: bold;">'+ response.message +'</div>').show().appendTo('#response-data');
          $('#alert-data').delay(2750).slideUp('slow', function(){
              $(this).remove();
          });
        }
      });
    }
  }

</script>