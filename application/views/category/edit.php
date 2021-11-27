  <?php
  $action = ($record != null) ? "Ubah" : "Tambah";
  $id = (isset($record->id)) ? $record->id : '';
  $name = (isset($record->name)) ? $record->name : '';
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?= $page_name; ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"><?= $this->uri->segment(1); ?></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <?php if($error != null or $error != '') { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= $error; ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <?php } ?>
        <!-- card -->
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title"><?= $action.' '.$page_name; ?></h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form action="<?= base_url("category/update") ?>" method="POST" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control" name="name" placeholder="Nama" value="<?= $name; ?>" required>
                    <input type="hidden" name="id" value="<?= $id; ?>">
                  </div>
                </div>
                <!-- /.col -->
                <div class="col-md-6">
                </div>
                <!-- /.col -->
              </div>
              <div class="row">
                <div class="col-md-12">
                  <button type="submit" class="btn btn-outline-primary"><?= $action; ?></button>
                  <?php if($record != null): ?>
                  <div class="btn btn-outline-danger float-right" onclick="deleteItem(<?= $record->id ?>)">Hapus</div>
                  <?php endif ?>
                </div>
              </div>
            </form>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script type="text/javascript">
    function deleteItem(id) {
      var data = {
        'id': id
      }

      $.ajax({
        type: 'POST',
        url: "<?php echo base_url("category/delete")?>",
        data: data,
        dataType: "json",
        success: function(resultData) {
          if (resultData.error != null) {
            window.alert(resultData.error);
          } else {
            window.location.href = "<?= base_url("category") ?>";
          }  
        }
      });
    }
  </script>