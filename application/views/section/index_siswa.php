<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <?php if ($this->session->flashdata('success')) {
          echo '<div class="alert alert-success alert-dismissible" style="font-weight: bold;">' . $this->session->flashdata('success') . '</div>';
        } ?>
        <div id="response-data"></div>
        <div class="card card-orange card-outline">
          <div class="card-header">
            <h3 class="card-title">Daftar <?= $title ?></h3>
            <div class="card-tools">
              <div class="btn-group" role="group">
                <button type="button" id="btnGroupDrop2" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-plus"></i></button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop2">
                  <a class="dropdown-item" href="<?= site_url('add/siswa') ?>" class="btn btn-tool">Tambah Siswa</a>
                </div>
              </div>
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
                      '<th>NIS</th>',
                      '<th>Nama<span style="color: white;">_</span>Lengkap</th>',
                      '<th>Jenis<span style="color: white;">_</span>Kelamin</th>',
                      '<th>Kelas</th>',
                      '<th>No.<span style="color: white;">_</span>Handphone</th>',
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

<form action="<?= site_url('Siswa/import_excel') ?>" method="post" enctype="multipart/form-data" id="form-import_siswa" style="display: none;">
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
        "url": "<?= site_url('list/siswa') ?>",
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
        $('#form-import_siswa').submit();
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

  function delete_data(id) {
    if (confirm('Apakah anda yakin?')) {
      $.getJSON('<?= site_url('delete/siswa') ?>/' + id, function(response) {
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

  function import_siswa() {
    $('[name="file_excel"]').click();
  }
</script>