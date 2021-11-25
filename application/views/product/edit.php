  <?php
  $action = ($record != null) ? "Ubah" : "Tambah";
  $id = (isset($record->id)) ? $record->id : '';
  $brand_type_id = (isset($record->brand_type_id)) ? $record->brand_type_id : '';
  $brand_id = (isset($record->brand_id)) ? $record->brand_id : '';
  $name = (isset($record->name)) ? $record->name : '';
  $price = (isset($record->price)) ? $record->price : '';
  $qty = (isset($record->qty)) ? $record->qty : '';
  $image_url = (isset($record->image_url)) ? $record->image_url : '';
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
            <form action="<?= base_url("product/update") ?>" method="POST" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Merek</label>
                    <select name="brand_id" id="brand_id" class="form-control select2bs4" style="width: 100%;" <?= ($record != null) ? 'disabled' : '' ?>>
                      <option>--Pilih Merek--</option>
                      <?php 
                      foreach ($brands as $data) {
                        $selected = ($data->id == $brand_id) ? 'selected' : '';
                        echo '<option value="'.$data->id.'" '.$selected.'>'.$data->name.'</option>';
                      }
                      ?>
                    </select>
                    <input type="hidden" name="id" class="form-control" value="<?= $id ?>">
                  </div>
                  <div class="form-group">
                    <label>Tipe</label>
                    <select name="brand_type_id" id="brand_type_id" class="form-control select2bs4" style="width: 100%;" <?= ($record != null) ? 'disabled' : '' ?>>
                      <?php if($record != null) : ?>
                      <option>--Pilih Tipe--</option>
                      <?php 
                      foreach ($brand_types as $data) {
                        $selected = ($data->id == $brand_type_id) ? 'selected' : '';
                        echo '<option value="'.$data->id.'" '.$selected.'>'.$data->name.'</option>';
                      }
                      ?>
                      <?php endif ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control" name="name" placeholder="Nama" value="<?= $name; ?>" required>
                    <input type="hidden" name="id" value="<?= $id; ?>">
                  </div>
                  <div class="form-group">
                    <label>Harga</label>
                    <input type="number" class="form-control" name="price" placeholder="Harga" value="<?= $price; ?>" required>
                  </div>
                  <div class="form-group">
                    <label>Qty</label>
                    <input type="number" class="form-control" name="qty" placeholder="Kuantitas produk" value="<?= $qty; ?>" required>
                  </div>
                </div>
                <!-- /.col -->
                <div class="col-md-6">  
                  <div class="form-group">
                    <div class="img-square-wrapper">
                      <img src="<?= ($image_url != null) ? base_url("$image_url") : base_url("assets/images/image_placeholder.png") ?>" class="card-img-top" style="width: 120px;object-fit: contain;" alt="<?= base_url("$image_url") ?>">
                      <input type="file" name="image" class="form-control" value="" <?= ($record == null) ? 'required' : ''?>>
                      <input type="hidden" name="image_url" class="form-control" value="<?= $image_url ?>" required>
                    </div>
                  </div>
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
        url: "<?php echo base_url("product/delete")?>",
        data: data,
        dataType: "json",
        success: function(resultData) {
          if (resultData.error != null) {
            window.alert(resultData.error);
          } else {
            window.location.href = "<?= base_url("product") ?>";
          }  
        }
      });
    }

    $(document).ready(function() {
      $('#brand_id').change(function(e) {

        $('#brand_type_id').empty();

        var data = {
          'brand_id':$('#brand_id').val()
        }

        $.LoadingOverlay("show");

        $.ajax({
          type: 'GET',
          url: "<?php echo base_url("bookingservice/getbrandtypebybrandid")?>",
          data: data,
          dataType: "json",
          success: function(resultData) { 
            $.LoadingOverlay("hide");

            var toAppend = '';
            toAppend += '<option>--Pilih Tipe</option>';
            $.each(resultData,function(i,o){
              toAppend += '<option value="'+o.id+'">'+o.name+'</option>';
            });
            $('#brand_type_id').append(toAppend);
          }
        });
      });      
    });
  </script>