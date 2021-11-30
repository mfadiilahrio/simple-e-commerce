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
            <h3 class="card-title">Filter</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>Kategori</label>
                  <select name="category_id" id="category_id" class="form-control select2bs4" style="width: 100%;" required>
                    <option>--Pilih Kategori--</option>
                    <?php 
                    foreach ($categories as $data) {
                      echo '<option value="'.$data->id.'">'.$data->name.'</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>
              <!-- /.col -->
              <div class="col-md-6">
              </div>
              <!-- /.col -->
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <div class="row" id="products">
          <?php foreach ($records as $record) {
            if (strlen($record->image_url) > 0) {
              $image_url = base_url($record->image_url);
            } else {
              $image_url = base_url("assets/images/image_placeholder.png");
            }
          ?>
          <div class="col-md-2"> 
            <div class="card"> 
              <a class="p-3"> 
                <img src="<?= $image_url ?>" class="card-img-top" style="height: 200px;object-fit: contain;" alt="<?= $image_url ?>"> 
              </a> 
              <div class="card-body"> 
                <div class="row"> 
                  <div class="col-md-12"> 
                    <h5 class="card-title text-bold">$record->name</h5> 
                  </div> 
                </div> 
              </div> 
              <div class="card-footer"> 
                <p class="text text-secondary">Rp.<?= $record->price ?></p> 
                <div onclick="addToCartItems(<?= $record->id ?>)" class="btn btn-sm btn-outline-info btn-block">Tambah ke keranjang</div> 
              </div> 
            </div> 
            <br> 
          </div>
          <?php } ?>
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script type="text/javascript">
    $(document).ready(function() {
      $('#category_id').change(function(e) {

        $('#products').empty();

        var data = {
          'category_id':$('#category_id').val()
        }

        $.ajax({
          type: 'GET',
          url: "<?php echo base_url("shopping/getitemsbycategoryid")?>",
          data: data,
          dataType: "json",
          success: function(resultData) { 
            var toAppend = '';
            $.each(resultData,function(i,o){
              var image_url = "";
              if (o.image_url.length > 0) {
                image_url = "<?php echo base_url('"+o.image_url+"')?>";
              } else {
                image_url = "<?php echo base_url("assets/images/image_placeholder.png")?>";
              }
              toAppend += '<div class="col-md-2">' +
              '<div class="card">' +
                '<a class="p-3">' +
                  '<img src="'+image_url+'" class="card-img-top" style="height: 200px;object-fit: contain;" alt="'+image_url+'">' +
                '</a>' +
                '<div class="card-body">' +
                  '<div class="row">' +
                    '<div class="col-md-12">' +
                      '<h5 class="card-title text-bold">'+o.name+'</h5>' +
                    '</div>' +
                  '</div>' +
                '</div>' +
                '<div class="card-footer">' +
                  '<p class="text text-secondary">Rp.'+o.price+'</p>' +
                  '<div onclick="addToCartItems('+o.id+')" class="btn btn-sm btn-outline-info btn-block">Tambah ke keranjang</div>' +
                '</div>' +
              '</div>' +
              '<br>' +
            '</div>';
           });
            $('#products').append(toAppend);
          }
        });
      });       
    });

    function addToCartItems(id) {
      var data = {
        'user_id' : <?= $this->session->userdata('user_id') ?>,
        'type':'shopping',
        'item_id':id
      }

      $.ajax({
        type: 'POST',
        url: "<?php echo base_url("cart/addtocart")?>",
        data: data,
        dataType: "json",
        success: function(resultData) { 
          if (resultData.error != null) {
            window.alert(resultData.error);
          } else {
            $('#cart_total').text(resultData.result.qty);

            Swal.fire({
              toast: true,
              icon: 'success',
              showCloseButton: true,
              position: 'top-start',
              showConfirmButton: false,
              title: '&nbsp;&nbsp;&nbsp;Berhasil!',
              text: resultData.message,
              timer: 3000
            })
          }
        }
      });
    }
  </script>