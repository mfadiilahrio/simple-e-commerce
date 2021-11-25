  <?php
  $action = ($record != null) ? "Ubah" : "Tambah";
  $id = (isset($record->id)) ? $record->id : '';
  $email = (isset($record->email)) ? $record->email : '';
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
        <?php if($message != null or $message != '') { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= $message; ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <?php } ?>
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
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form action="<?= base_url("mechanic/update"); ?>" method="post">
              <div class="input-group mb-3">
                <input type="email" name="email" value="<?= $email ?>" class="form-control" placeholder="Email" <?= ($record != null) ? 'readonly' : '' ?>>
                <input type="hidden" name="id" value="<?= $id ?>" class="form-control">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                  </div>
                </div>
              </div>
              <div class="input-group mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" <?= ($record == null) ? 'required' : '' ?>>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div>
              <div class="input-group mb-3">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ketik ulang password" <?= ($record == null) ? 'required' : '' ?>>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <!-- /.col -->
                <div class="col-12">
                  <button type="submit" class="btn btn-outline-primary">Submit</button>
                  <?php if($record != null): ?>
                  <div class="btn btn-outline-danger float-right" onclick="deleteItem(<?= $record->id ?>)">Hapus</div>
                  <?php endif ?>
                </div>
                <!-- /.col -->
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
        url: "<?php echo base_url("mechanic/delete")?>",
        data: data,
        dataType: "json",
        success: function(resultData) {
          if (resultData.error != null) {
            window.alert(resultData.error);
          } else {
            window.location.href = "<?= base_url("mechanic") ?>";
          }  
        }
      });
    }
  </script>