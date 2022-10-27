<section class="content">
  <div class="container-fluid">
    <div class="row">
      <?php foreach ($list_info as $key1 => $value1): ?>
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box">
            <?php foreach ($value1 as $key2 => $value2): ?>
              <span class="info-box-icon bg-orange elevation-1" style="color: white;"><?= $key2 ?></span>
              <div class="info-box-content">
                <span class="info-box-text"><?= $key1 ?></span>
                <span class="info-box-number"><?= $value2 ?></span>
              </div>
            <?php endforeach ?>
          </div>
        </div>
      <?php endforeach ?>
    </div>
    <div class="row">
      <div class="col-12">
        <?php if ($this->session->flashdata('success')) {
          echo '<div class="alert alert-success alert-dismissible" style="font-weight: bold;">'. $this->session->flashdata('success') .'</div>';
        } ?>
        <div class="card card-orange card-outline">
          <div class="card-header">
            <h3 class="card-title">Daftar Pengembalian</h3>
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
                      '<th>Tanggal<span style="color: white;">_</span>Pinjam</th>',
                      '<th>Pengembalian</th>',
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
          data.pengembalian = true;
        },
      },
      "columnDefs": [{ 
        "targets": <?= json_encode($targets) ?>,
        "orderable": false,
      }],
    });

  });

  function kembalikan_buku(id_peminjaman) {
    if (confirm('Apakah anda yakin?')) {
      $.getJSON('<?= site_url('update/pengembalian') ?>/' + id_peminjaman, function(response) {
        window.location.reload();
      });
    }
  }

</script>