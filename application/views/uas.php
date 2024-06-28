<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="<?= base_url() ?>assets/images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favicon.png" type="image/x-icon">
    <title>UAS</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/font-awesome.css">
    <!-- ico-font-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <!-- login page start-->
    <div class="container p-0">
      <form action="<?= base_url('uas/submit') ?>" method="post" id="ac">
        <div class="row">
          <div class="col-xl-4">
            <h2>TUGAS UAS</h2>
            <h6>Nama : Muhamad Alfandi</h6>
            <h6>NPM : 191010019</h6>
          </div>
        </div>
        <div class="row m-0">
          <div class="col-xl-2">
            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label">Nama Mahasiswa</label>
              <input type="text" class="form-control nama" name="nama" id="exampleFormControlInput1" placeholder="">
            </div>
          </div>
          <div class="col-xl-2">
            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label">NPM</label>
              <input type="number" class="form-control npm" name="npm" id="exampleFormControlInput1" placeholder="">
            </div>
          </div>
          <div class="col-xl-2">
            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label">Jenis Kelamin</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" value="Laki-laki" id="gender1">
                <label class="form-check-label" for="gender1">
                  Laki-laki
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" value="Perempuan" id="gender2">
                <label class="form-check-label" for="gender2">
                  Perempuan
                </label>
              </div>
            </div>
          </div>
          <div class="col-xl-3">
            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label">Alamat</label>
              <input type="text" class="form-control alamat" name="alamat" id="exampleFormControlInput1" placeholder="">
            </div>
          </div>
          <div class="col-xl-3">
            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label">Tanggal Lahir</label>
              <input type="date" class="form-control tgl_lahir" name="tgl_lahir" id="exampleFormControlInput1" placeholder="">
            </div>
          </div>
          </div>
          <div class="row">
            <div class="col-xl-2">
                  <input value="Submit" type="submit" class="btn btn-primary action">
                  <a href="<?= base_url('uas')?>" class="btn btn-primary invisible">Refresh</a>

            </div>
          </div>
          <hr>
        </form>
          <div class="row">
              <div class="col">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">NPM</th>
                    <th scope="col">Jenis Kelamin</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">Tanggal Lahir</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  foreach ($row as $x) {  ?>
                  <tr>
                    <th scope="row"><?= $no++ ?></th>
                    <td><?= $x->nama ?></td>
                    <td><?= $x->npm ?></td>
                    <td><?= $x->gender ?></td>
                    <td><?= $x->alamat ?></td>
                    <td><?= $x->tgl_lahir ?></td>
                    <td>
                      <button type="button" id="<?= $x->id ?>" class="btn btn-warning edit">Edit</button>
                      <a href="<?= base_url('uas/delete/'.$x->id) ?>" class="btn btn-danger">Hapus</a>

                  </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
              </div>
          </div>
      <!-- latest jquery-->

      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
      <script>
      $(document).on('click', '.edit', function () {
            // var linkURL = $(this).attr("href").split('#');
            var id = this.id;
            $.ajax({
            url: '<?= base_url('uas/get')?>',//controller
            method: "POST",
            data: {
                id: id
            },
            async: true,
            dataType: 'json',
            success: function(data) {
                var html='';
                var html2='';
                var i;
                $('.nama').val(data.nama)
                $('.npm').val(data.npm)
                $('.alamat').val(data.alamat)
                $('.tgl_lahir').val(data.tgl_lahir)
                if (data.gender == 'Perempuan') {
                  // $('input[name="gender"]').attr('checked',true)
                  $('#gender2').attr('checked',true)
                  $('#gender1').attr('checked',false)

                }else{
                  $('#gender1').attr('checked',true)
                  $('#gender2').attr('checked',false)

                }
                $('.action').val('Update');
                
                $('.action').attr('class','btn btn-warning');
                $(".invisible").removeClass("invisible");
                $('#ac').attr('action','http://localhost/uas/uas/submit/update/'+data.id)


            }
        });
          
        });
</script>
    </div>
  </body>
</html>
