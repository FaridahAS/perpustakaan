<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <?php if ($this->session->flashdata('success')) {
          echo '<div class="alert alert-success alert-dismissible" style="font-weight: bold;">'. $this->session->flashdata('success') .'</div>';
        } ?>
        <div id="response-data"></div>
        <div class="card card-orange card-outline">
          <div class="card-header">
            <h3 class="card-title">Profile Petugas</h3>
            <div class="card-tools">
              <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                  <i class="fas fa-cogs"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                  <a href="javascript:void(0)" onclick="ubah_profile();" class="dropdown-item">Ubah Profile</a>
                  <div class="dropdown-divider"></div>
                  <a href="javascript:void(0)" onclick="ubah_foto();" class="dropdown-item">Ubah Foto</a>
                  <?php if ($petugas->foto_profile): ?>
                    <div class="dropdown-divider"></div>
                    <a href="<?= base_url('delete_foto/petugas/'. base64_encode($petugas->id_petugas)) ?>" onclick="return confirm('Apakah anda yakin?');" class="dropdown-item">Hapus Foto</a>
                  <?php endif ?>
                  <div class="dropdown-divider"></div>
                  <a href="javascript:void(0)" onclick="ubah_password();" class="dropdown-item">Ubah Password</a>
                  <div class="dropdown-divider"></div>
                  <a href="javascript:void(0)" onclick="tambah_petugas();" class="dropdown-item">Tambah Petugas</a>
                  <div class="dropdown-divider"></div>
                  <a href="javascript:void(0)" onclick="tambah_kelas();" class="dropdown-item">Tambah Kelas</a>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-3"></div>
              <div class="col-md-6">
                <div class="card-body box-profile">
                  <div class="text-center">
                    <?php $foto_profile = $petugas->foto_profile ? 'assets/img/'. $petugas->foto_profile : 'assets/dist/img/default-150x150.png'; ?>
                    <img class="img-fluid" src="<?= site_url($foto_profile) ?>" alt="" style="width: 150px; height: 150px;">
                  </div>
                  <ul class="list-group list-group-unbordered mt-3">
                    <?php 

                    $profile_petugas = array(
                      'Nama<span style="color: white;">_</span>Lengkap' => $petugas->nama_petugas,
                      'Email' => $petugas->email ? $petugas->email : '-',
                    );

                    foreach ($profile_petugas as $key => $value) {
                      $list = '<li class="list-group-item">';
                      $list .= '<b>'. $key .'</b><span class="float-right">'. $value .'</span>';
                      $list .= '</li>';
                      echo $list;
                    }

                    ?>
                  </ul>
                </div>
              </div>
              <div class="col-md-3"></div>
            </div>
          </div>
        </div>
        <div class="card card-orange card-outline">
          <div class="card-header">
            <h3 class="card-title">Daftar Petugas</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="table-petugas" class="table table-bordered" style="width: 100%;">
                <thead>
                  <tr>
                    <?php

                    $thead1 = array(
                      '<th style="width: 5%; text-align: center;">No</th>',
                      '<th>Nama<span style="color: white;">_</span>Petugas</th>',
                      '<th>Email</th>',
                      '<th style="text-align: center;">Foto</th>',
                      '<th style="width: 5%; text-align: center;">Aksi</th>',
                    );

                    $targets1 = array();
                    for ($i=0; $i < count($thead1); $i++) { 
                      if ($i >= 1) {
                        $targets1[] = $i;
                      }
                      echo $thead1[$i];
                    }

                    ?>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
        <div class="card card-orange card-outline">
          <div class="card-header">
            <h3 class="card-title">Daftar Kelas</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="table-kelas" class="table table-bordered" style="width: 100%;">
                <thead>
                  <tr>
                    <?php

                    $thead2 = array(
                      '<th style="width: 5%; text-align: center;">No</th>',
                      '<th>Nama<span style="color: white;">_</span>Kelas</th>',
                      '<th style="width: 5%; text-align: center;">Aksi</th>',
                    );

                    $targets2 = array();
                    for ($i=0; $i < count($thead2); $i++) { 
                      if ($i >= 1) {
                        $targets2[] = $i;
                      }
                      echo $thead2[$i];
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

<form action="<?= base_url('update_foto/petugas/'. base64_encode($petugas->id_petugas)) ?>" method="post" id="form-foto_profile" enctype="multipart/form-data" style="display: none;">
  <input type="file" name="foto_profile" accept=".jpg, .png, .gif, .jpeg">
</form>

<div class="modal fade" id="modal-petugas">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="form-petugas">
          <input type="text" name="id_petugas" class="form-control" value="" style="display: none;">
          <div class="form-group">
            <label for="nama_petugas">Nama Petugas</label>
            <input type="text" name="nama_petugas" id="nama_petugas" class="form-control" value="" placeholder="Nama Petugas" autocomplete="off">
            <span id="error-nama_petugas" class="error invalid-feedback"></span>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" class="form-control" value="" placeholder="Email" autocomplete="off">
            <span id="error-email" class="error invalid-feedback"></span>
          </div>
          <div class="form-group" id="input-password">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" value="" placeholder="Password" autocomplete="off">
            <span id="error-password" class="error invalid-feedback"></span>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="font-weight: bold;"><i class="fas fa-angle-double-left"></i> Batal</button>
        <button type="button" onclick="save_petugas();" class="btn btn-success btn-sm" style="font-weight: bold;"><i class="fas fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-kelas">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="form-kelas">
          <input type="text" class="form-control" name="id_kelas" value="" style="display: none;">
          <div class="form-group">
            <label for="nama_kelas">Nama Kelas</label>
            <input type="text" name="nama_kelas" id="nama_kelas" class="form-control" value="" placeholder="Nama Petugas" autocomplete="off">
            <span id="error-nama_kelas" class="error invalid-feedback"></span>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="font-weight: bold;"><i class="fas fa-angle-double-left"></i> Batal</button>
        <button type="button" onclick="save_kelas();" class="btn btn-success btn-sm" style="font-weight: bold;"><i class="fas fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-password">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="form-password">
          <div class="form-group">
            <label for="password1">Password Sekarang</label>
            <input type="password" name="password1" id="password1" class="form-control" value="" placeholder="Password Sekarang" autocomplete="off">
            <span id="error-password1" class="error invalid-feedback"></span>
          </div>
          <div class="form-group" id="input-password">
            <label for="password2">Password Baru</label>
            <input type="password" name="password2" id="password2" class="form-control" value="" placeholder="Password Baru" autocomplete="off">
            <span id="error-password2" class="error invalid-feedback"></span>
          </div>
          <div class="form-group" id="input-password">
            <label for="password3">Konfirmasi Password</label>
            <input type="password" name="password3" id="password3" class="form-control" value="" placeholder="Konfirmasi Password" autocomplete="off">
            <span id="error-password3" class="error invalid-feedback"></span>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="font-weight: bold;"><i class="fas fa-angle-double-left"></i> Batal</button>
        <button type="button" onclick="save_password();" class="btn btn-success btn-sm" style="font-weight: bold;"><i class="fas fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(function() {

    tb_petugas = $('#table-petugas').DataTable({
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
        "url": "<?= site_url('list/petugas') ?>",
        "type": "POST",
        "data": function(data) {

        },
      },
      "columnDefs": [{ 
        "targets": <?= json_encode($targets1) ?>,
        "orderable": false,
      }],
    });

    tb_kelas = $('#table-kelas').DataTable({
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
        "url": "<?= site_url('list/kelas') ?>",
        "type": "POST",
        "data": function(data) {

        },
      },
      "columnDefs": [{ 
        "targets": <?= json_encode($targets2) ?>,
        "orderable": false,
      }],
    });

    $('[name="foto_profile"]').change(function() {
      if ($(this).val()) {
        if ($('[name="id_petugas"]').val()) {
          save_foto($('[name="id_petugas"]').val());
        } else {
          $('#form-foto_profile').submit();
        }

      }
    });

  });

  function ubah_profile() {
    $('#input-password').hide();
    $('#form-petugas .form-control').val('').change();
    $('#form-petugas .form-control').removeClass('is-invalid');
    $('[name="id_petugas"]').val('<?= $petugas->id_petugas ?>').change();
    $('[name="nama_petugas"]').val('<?= $petugas->nama_petugas ?>').change();
    $('[name="email"]').val('<?= $petugas->email ?>').change();
    $('.modal-title').text('Ubah Petugas');
    $('#modal-petugas').modal('show');
  }

  function ubah_foto(id_petugas = null) {
    if (id_petugas != null) {
      $('[name="id_petugas"]').val(id_petugas).change();
    } else {
      $('[name="id_petugas"]').val('').change();
    }
    $('[name="foto_profile"]').click();
  }

  function save_foto(id_petugas) {
    $.ajax({
      url: '<?= base_url('update_foto/petugas') ?>/' + id_petugas,
      type: 'POST',
      dataType: 'json',
      data: new FormData($('#form-foto_profile')[0]),
      processData: false,
      contentType: false,
      cache: false,
      async: false,
      success: function(response) {
        if (response.status) {
          tb_petugas.ajax.reload();
          $('#response-data').html('');
          $(window).scrollTop(0);
          $('<div class="alert alert-success alert-dismissible" id="alert-data" style="font-weight: bold;">'+ response.message +'</div>').show().appendTo('#response-data');
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

  function ubah_password() {
    $('#form-password .form-control').val('').change();
    $('#form-password .form-control').removeClass('is-invalid');
    $('.modal-title').text('Ubah Password');
    $('#modal-password').modal('show');
  }

  function save_password() {
    $.ajax({
      url: '<?= base_url('password/petugas/'. base64_encode($petugas->id_petugas)) ?>',
      type: 'POST',
      dataType: 'json',
      data: new FormData($('#form-password')[0]),
      processData: false,
      contentType: false,
      cache: false,
      async: false,
      success: function(response) {
        if (response.status) {
          $('#modal-password').modal('hide');
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

  function tambah_petugas() {
    $('#input-password').show();
    $('#form-petugas .form-control').val('').change();
    $('#form-petugas .form-control').removeClass('is-invalid');
    $('.modal-title').text('Tambah Petugas');
    $('#modal-petugas').modal('show');
  }

  function edit_petugas(id) {
    var nama_petugas   = $('[name="nama_petugas_'+ id +'"]').val();
    var email = $('[name="email_'+ id +'"]').val();

    $('#input-password').show();
    $('#form-petugas .form-control').val('').change();
    $('#form-petugas .form-control').removeClass('is-invalid');

    $('[name="id_petugas"]').val(id).change();
    $('[name="nama_petugas"]').val(nama_petugas).change();
    $('[name="email"]').val(email).change();
    $('.modal-title').text('Ubah Petugas');
    $('#modal-petugas').modal('show');
  }

  function save_petugas() {
    $.ajax({
      url: '<?= base_url('save/petugas') ?>',
      type: 'POST',
      dataType: 'json',
      data: new FormData($('#form-petugas')[0]),
      processData: false,
      contentType: false,
      cache: false,
      async: false,
      success: function(response) {
        if (response.status) {
          $('#modal-petugas').modal('hide');
          if (response.id_petugas) {
            setTimeout(function() {
              window.location.reload();
            }, 375);
          } else {
            tb_petugas.ajax.reload();
            $('#response-data').html('');
            $(window).scrollTop(0);
            $('<div class="alert alert-success alert-dismissible" id="alert-data" style="font-weight: bold;">'+ response.message +'</div>').show().appendTo('#response-data');
            $('#alert-data').delay(2750).slideUp('slow', function(){
                $(this).remove();
            });
          }
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

  function delete_petugas(id) {
    if (confirm('Apakah anda yakin?')) {
      $.getJSON('<?= site_url('delete/petugas') ?>/' + id, function(response) {
        if (response.status) {
          tb_petugas.ajax.reload();
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

  function tambah_kelas() {
    $('#form-kelas .form-control').val('').change();
    $('#form-kelas .form-control').removeClass('is-invalid');
    $('.modal-title').text('Tambah Kelas');
    $('#modal-kelas').modal('show');
  }

  function edit_kelas(id) {
    var nama_kelas   = $('[name="nama_kelas_'+ id +'"]').val();

    $('#form-petugas .form-control').val('').change();
    $('#form-petugas .form-control').removeClass('is-invalid');

    $('[name="id_kelas"]').val(id).change();
    $('[name="nama_kelas"]').val(nama_kelas).change();
    $('.modal-title').text('Ubah Kelas');
    $('#modal-kelas').modal('show');
  }

  function save_kelas() {
    $.ajax({
      url: '<?= base_url('save/kelas') ?>',
      type: 'POST',
      dataType: 'json',
      data: new FormData($('#form-kelas')[0]),
      processData: false,
      contentType: false,
      cache: false,
      async: false,
      success: function(response) {
        if (response.status) {
          tb_kelas.ajax.reload();
          $('#modal-kelas').modal('hide');
          $('#response-data').html('');
          $(window).scrollTop(0);
          $('<div class="alert alert-success alert-dismissible" id="alert-data" style="font-weight: bold;">'+ response.message +'</div>').show().appendTo('#response-data');
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

  function delete_kelas(id) {
    if (confirm('Apakah anda yakin?')) {
      $.getJSON('<?= site_url('delete/kelas') ?>/' + id, function(response) {
        if (response.status) {
          tb_kelas.ajax.reload();
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