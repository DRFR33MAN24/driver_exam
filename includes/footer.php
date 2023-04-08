</div>
<!-- Scripts -->
<script src="assets/js/vendors.bundle.js"></script>
<script src="assets/js/notify.js"></script>
<script src="assets/js/scripts.bundle.js"></script>
<script src="assets/plugins/sweetalerts/sweetalert2.min.js"></script>

<script type="text/javascript">
  $(".enable_disable").on("click", function(e) {
    e.preventDefault();

    var _for = $(this).data("action");
    var _id = $(this).data("id");
    var _column = $(this).data("column");
    var _table = $(this).data("table");
    var _table_id = $(this).data("table_id");

    $.ajax({
      type: 'post',
      url: 'processData.php',
      dataType: 'json',
      data: {
        id: _id,
        for_action: _for,
        column: _column,
        table: _table,
        'action': 'toggle_status',
        tbl_id: _table_id
      },
      success: function(res) {
        console.log(res);
        if (res.status == '1') {
          location.reload();
        }
      }
    });

  });


  $(".refresh_id").on("click", function(e) {

    e.preventDefault();

    var _id = $(this).data("id");
    var _table = $(this).data("table");


    swal({
      title: "هل انت متأكد من انك تريد إعادة تعيين معرف الجهاز",
      type: "warning",
      confirmButtonClass: 'btn btn-primary mb-2',
      cancelButtonClass: 'btn btn-danger mb-2',
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "نعم",
      cancelButtonText: "لا",
      closeOnConfirm: false,
      closeOnCancel: false,
      showLoaderOnConfirm: true
    }).then(function(result) {
      if (result.value) {

        $.ajax({
          type: 'post',
          url: 'processData.php',
          // dataType: 'json',

          data: {
            id: _id,
            for_action: 'refresh',
            table: _table,
            'action': 'multi_action'
          },
          success: function(res) {
            console.log(res);
            location.reload();
          },
          error: function(xhr, ajaxOptions, thrownError) {
            console.log(xhr.responseJSON);

          }
        });
      } else {
        swal.close();
      }
    });
  });

  $(".delete_data").on("click", function(e) {

    e.preventDefault();

    var _id = $(this).data("id");
    var _table = $(this).data("table");


    swal({
      title: "هل انت متأكد من انك تريد الحذف؟",
      type: "warning",
      confirmButtonClass: 'btn btn-primary mb-2',
      cancelButtonClass: 'btn btn-danger mb-2',
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "نعم",
      cancelButtonText: "لا",
      closeOnConfirm: false,
      closeOnCancel: false,
      showLoaderOnConfirm: true
    }).then(function(result) {
      if (result.value) {

        $.ajax({
          type: 'post',
          url: 'processData.php',
          // dataType: 'json',

          data: {
            id: _id,
            for_action: 'delete',
            table: _table,
            'action': 'multi_action'
          },
          success: function(res) {
            console.log(res);
            location.reload();
          },
          error: function(xhr, ajaxOptions, thrownError) {
            console.log(xhr.responseJSON);

          }
        });
      } else {
        swal.close();
      }
    });
  });

  function fileValidation() {
    var fileInput = document.getElementById('fileupload');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.png|.jpg|.jpeg|.PNG|.JPG|.JPEG)$/i;
    if (!allowedExtensions.exec(filePath)) {
      if (filePath != '')
        fileInput.value = '';
      $.notify('Please upload file having extension .png, .jpg, .PNG, .JPG only.', {
        position: "top right",
        className: 'error'
      });
      return false;
    } else {
      if (fileInput.files && fileInput.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $("#imagePreview").find("img").attr("src", e.target.result);
        };
        reader.readAsDataURL(fileInput.files[0]);
      }
    }
  }
</script>

<?php if (isset($_SESSION['msg'])) {
  $_class = ($_SESSION["class"]) ? $_SESSION["class"] : "success";
?>
  <script type="text/javascript">
    var _msg = '<?php echo $client_lang[$_SESSION["msg"]]; ?>';
    _msg = _msg.replace(/(<([^>]+)>)/ig, "");

    $('.notifyjs-corner').empty();
    $.notify(_msg, {
      position: "top right",
      className: '<?= $_class ?>'
    });
  </script>
<?php
  unset($_SESSION['msg']);
  unset($_SESSION['class']);
}
?>
</body>

</html>