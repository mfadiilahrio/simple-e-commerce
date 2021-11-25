  <?php
  $action = ($record != null) ? "Ubah" : "Tambah";
  $id = (isset($record->id)) ? $record->id : '';
  $employee_id = (isset($record->employee_id)) ? $record->employee_id : '';
  $name = (isset($record->name)) ? $record->name : '';
  $username = (isset($record->username)) ? $record->username : '';
  $user_type = (isset($record->user_type)) ? $record->user_type : '';
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
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form action="<?= base_url("user/update") ?>" method="POST" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Nama</label>
                    <?php if ($id != null || $id != '') { ?>
                    <input type="text" class="form-control" value="<?= $name; ?>" disabled> 
                    <input type="hidden" name="employee_id" class="form-control" value="<?= $employee_id; ?>"> 
                    <?php } else { ?>
                    <select name="employee_id" class="form-control select2bs4" style="width: 100%;" required>
                      <?php 
                      foreach ($employees as $employee) {

                        if ($employee_id == $employee->id) {
                          $selected = 'selected';
                        } else {
                          $selected = '';
                        }

                        echo '<option value="'.$employee->id.'" '.$selected.'>'.$employee->name.'</option>';

                      }
                      ?>
                    </select>
                    <?php } ?>
                    <input type="hidden" name="id" value="<?= $id; ?>">
                  </div>
                </div>
                <!-- /.col -->
                <div class="col-md-6">
                  <?php if ($id != null || $id != '') { ?>
                  <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" value="<?= $username; ?>" disabled> 
                  </div>
                  <?php } ?>
                  <div class="form-group">
                    <label>Tipe User</label>
                    <select name="user_type" class="form-control select2bs4" style="width: 100%;" required>
                      <?php 

                      $user_types = array('sales'=>'Sales', 'admin'=>'Admin', 'super admin'=>'Super Admin');

                      foreach ($user_types as $key => $value) {
                        if ($key == $user_type) {
                          $selected = 'selected';
                        } else {
                          $selected = '';
                        }

                        echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                      }
                      ?>
                    </select>
                  </div>    
                </div>
                <!-- /.col -->
              </div>
              <div class="row">
                <div class="col-md-12">
                  <button type="submit" class="btn btn-outline-primary"><?= $action; ?></button>
                </div>
              </div>
            </form>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <ul>
              <i class="text text-italic text-sm"><strong>Note</strong></i>
              <li><i class="text text-italic text-sm">Username digenerate otomatis. </i></li>
              <li><i class="text text-italic text-sm">Password digenerate dari tanggal lahir. </i></li>
            </ul>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->