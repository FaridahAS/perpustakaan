<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div id="response-data"></div>
        <div class="card card-orange card-outline">
          <div class="card-header">
            <h3 class="card-title">Daftar <?= $title ?></h3>
            <div class="card-tools">
              <button class="dropdown-item" onclick="add_data()"><i class="fas fa-plus"></i></button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="table" class="table table-bordered" style="width: 100%;">
                  <thead>
                    <tr>
                      <?php

                      $thead = array(
                        '<th style="width: 5%; text-align: center;">No</th>',
                        '<th>Kode<span style="color: white;">_</span>Buku</th>',
                        '<th>Nama<span style="color: white;">_</span>Buku</th>',
                        '<th>Jumlah<span style="color: white;">_</span>Buku</th>',
                        '<th>Buku<span style="color: white;">_</span>Tersedia</th>',
                        '<th style="width: 5%; text-align: center;">Aksi</th>',
                      );

                      $targets = array();
                      for ($i = 0; $i < count($thead); $i++) {
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
          <input type="text" class="form-control" name="id_buku" value="" style="display: none;">
          <div class="form-group">
            <label for="kode_buku">Kode Buku</label>
            <input type="text" name="kode_buku" id="kode_buku" class="form-control" value="" placeholder="Kode Buku" autocomplete="off">
            <span id="error-kode_buku" class="error invalid-feedback"></span>
          </div>
          <div class="form-group">
            <label for="nama_buku">Nama Buku</label>
            <input type="text" name="nama_buku" id="nama_buku" class="form-control" value="" placeholder="Nama Buku" autocomplete="off">
            <span id="error-nama_buku" class="error invalid-feedback"></span>
          </div>
          <div class="form-group">
            <label for="jumlah_buku">Jumlah Buku</label>
            <input type="text" name="jumlah_buku" id="jumlah_buku" class="form-control" value="" placeholder="Jumlah Buku" autocomplete="off">
            <span id="error-jumlah_buku" class="error invalid-feedback"></span>
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

<form action="<?= site_url('Buku/import_excel') ?>" method="post" enctype="multipart/form-data" id="form-import_buku" style="display: none;">
  <input type="file" name="file_excel" accept=".xls, .xlsx">
</form>
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
        "url": "<?= site_url('list/buku') ?>",
        "type": "POST",
        "data": function(data) {

        },
      },
      "columnDefs": [{
        "targets": <?= json_encode($targets) ?>,
        "orderable": false,
      }],
    });

    $('[name="file_excel"]').change(function() {
      if ($(this).val()) {
        $('#form-import_buku').submit();
      }
    });

  })

  const reloadtable = () => {
    table.ajax.reload()
  }

  const cekall = () => {
    if ($('#cek').is(':checked')) {
      $(".data-check").prop('checked', true);
    } else {
      $(".data-check").prop('checked', false);
    }
  }

  function add_data() {
    $('#form-data .form-control').val('').change();
    $('#form-data .form-control').removeClass('is-invalid');
    $('.modal-title').text('Tambah Buku');
    $('#modal-form').modal('show');
  }

  function edit_data(id) {
    var kode_buku = $('[name="kode_buku_' + id + '"]').val();
    var nama_buku = $('[name="nama_buku_' + id + '"]').val();
    var jumlah_buku = $('[name="jumlah_buku_' + id + '"]').val();

    $('#form-data .form-control').val('').change();
    $('#form-data .form-control').removeClass('is-invalid');

    $('[name="id_buku"]').val(id).change();
    $('[name="kode_buku"]').val(kode_buku).change();
    $('[name="nama_buku"]').val(nama_buku).change();
    $('[name="jumlah_buku"]').val(jumlah_buku).change();
    $('.modal-title').text('Ubah Buku');
    $('#modal-form').modal('show');
  }

  function save_data() {
    $.ajax({
      url: '<?= base_url('save/buku') ?>',
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
          $('<div class="alert alert-success alert-dismissible" id="alert-data" style="font-weight: bold;">' + response.message + '</div>').show().appendTo('#response-data');
          $('#alert-data').delay(2750).slideUp('slow', function() {
            $(this).remove();
          });
        } else {
          $.each(response.errors, function(key, val) {

            $('[name="' + key + '"]').addClass('is-invalid');
            $('#error-' + key + '').text(val).show();

            if (val === '') {
              $('[name="' + key + '"]').removeClass('is-invalid');
              $('#error-' + key + '').text('').hide();
            }

            $('[name="' + key + '"]').on('change keyup', function(event) {
              $('[name="' + key + '"]').removeClass('is-invalid');
              $('#error-' + key + '').text('').hide();
            });
          });
        }

      }

    });
  }

  function delete_data(id) {
    if (confirm('Apakah anda yakin?')) {
      $.getJSON('<?= site_url('delete/buku') ?>/' + id, function(response) {
        if (response.status) {
          table.ajax.reload();
          $('#response-data').html('');
          $(window).scrollTop(0);
          $('<div class="alert alert-success alert-dismissible" id="alert-data" style="font-weight: bold;">' + response.message + '</div>').show().appendTo('#response-data');
          $('#alert-data').delay(2750).slideUp('slow', function() {
            $(this).remove();
          });
        }
      });
    }

  }

  function import_buku() {
    $('[name="file_excel"]').click();
  }
</script>