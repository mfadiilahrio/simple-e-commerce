  <?php
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
        <?php if($message != null) { echo "<p class='text text-danger text-center'>".$message."</p>"; } ?>
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
            <h3 class="card-title"><?= 'Ubah '.$page_name; ?></h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form autocomplete="off" action="<?= base_url("profile/update") ?>" method="POST" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" value="<?= $this->session->userdata('email') ?>" readonly> 
                  </div>
                  <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password" value="" autocomplete="off"> 
                  </div>
                  <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" value="<?= $this->session->userdata('name') ?>" required> 
                  </div>
                  <div class="form-group">
                    <label>No Telp</label>
                    <input type="text" name="phone" class="form-control" data-inputmask='"mask": "9999-9999-9999"' data-mask value="<?=  $this->session->userdata('phone') ?>" required>
                  </div>
                  <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                      <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" name="dob" value="<?=  $this->session->userdata('dob') ?>" required>
                      <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Tipe User</label>
                    <input type="text" class="form-control" value="<?= $this->session->userdata('user_type') ?>" readonly> 
                  </div>
                  <div class="form-group">
                    <label>Alamat</label>
                    <textarea class="form-control" name="address" required><?= $this->session->userdata('address') ?></textarea>
                  </div>
                  <div class="form-group">
                    <label>Kode POS</label>
                    <input type="number" class="form-control" name="postal_code" value="<?= $this->session->userdata('postal_code') ?>" required>
                  </div> 
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <button type="submit" class="btn btn-outline-primary">Ubah</button>
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