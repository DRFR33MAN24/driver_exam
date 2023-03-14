<?php
$page_title = (isset($_GET['video_id'])) ? 'تعديل الفيديو' : 'اضافة فيديو';
include("includes/header.php");
require("includes/lb_helper.php");
require("language/language.php");
require_once("thumbnail_images.class.php");

$page_save = (isset($_GET['video_id'])) ? 'حفظ' : 'انشاء';

if (isset($_POST['submit']) and isset($_GET['add'])) {

    if ($_FILES['thumbnail']['name'] != "") {

        $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
        $thumbnail = rand(0, 99999) . "_video." . $ext;
        $tpath1 = 'images/' . $thumbnail;

        if ($ext != 'png') {
            $pic1 = compress_image($_FILES["thumbnail"]["tmp_name"], $tpath1, 80);
        } else {
            $tmp = $_FILES['thumbnail']['tmp_name'];
            move_uploaded_file($tmp, $tpath1);
        }
    } else {
        $thumbnail = "";
    }

    $data = array(
        'category' => 'Normal',
        'video_title'  => addslashes(trim($_POST['video_title'])),
        'video_url' => $_POST['video_title'],

        'thumbnail'  => $thumbnail,
        'status'  =>  '1'
    );

    $qry = Insert('tbl_videos', $data);

    $_SESSION['msg'] = "10";
    $_SESSION['class'] = 'success';
    header("location:manage_videos.php");
    exit;
}

if (isset($_GET['video_id'])) {
    $video_qry = "SELECT * FROM tbl_videos where id='" . $_GET['video_id'] . "'";
    $video_result = mysqli_query($mysqli, $video_qry);
    $video_row = mysqli_fetch_assoc($video_result);
}

if (isset($_POST['submit']) and isset($_POST['video_id'])) {
    if ($_FILES['thumbnail']['name'] != "") {

        $img_res = mysqli_query($mysqli, 'SELECT * FROM tbl_videos WHERE id=' . $_GET['video_id'] . '');
        $img_res_row = mysqli_fetch_assoc($img_res);

        if ($img_res_row['thumbnail'] != "") {
            unlink('images/' . $img_res_row['thumbnail']);
        }

        $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
        $thumbnail = rand(0, 99999) . "_video." . $ext;
        $tpath1 = 'images/' . $thumbnail;

        if ($ext != 'png') {
            $pic1 = compress_image($_FILES["thumbnail"]["tmp_name"], $tpath1, 80);
        } else {
            $tmp = $_FILES['thumbnail']['tmp_name'];
            move_uploaded_file($tmp, $tpath1);
        }

        $data = array(
            'video_title'  => addslashes(trim($_POST['video_title'])),
            'video_url'  =>  addslashes(trim($_POST['video_url'])),

            'status'  =>  $_POST['status'],
            'thumbnail'  => $thumbnail,
        );
    } else {
        error_log(json_encode($_POST['status']), 0);
        $data = array(
            'video_title'  => addslashes(trim($_POST['video_title'])),
            'video_url'  =>  addslashes(trim($_POST['video_url'])),

            'status'  =>  $_POST['status'],
            'thumbnail'  => $thumbnail,

        );
    }

    $video_edit = Update('tbl_videos', $data, "WHERE id = '" . $_POST['video_id'] . "'");

    $_SESSION['msg'] = "11";
    $_SESSION['class'] = 'success';
    header("Location:add_videos.php?video_id=" . $_POST['video_id']);
    exit;
}

?>
<!-- Begin:: Theme main content -->
<main id="pb_main">
    <div class="pb-main-container">
        <div class="pb-card">
            <div class="pb-card__head">
                <span class="pb-card__title mb-2 mb-sm-0">
                    <?php if (isset($_GET['redirect'])) { ?>
                        <a href="<?= $_GET['redirect'] ?>">
                            <h4 class="pull-left"><i class="fa fa-arrow-left"></i> رجوع</h4>
                        </a>
                    <?php } else { ?>
                        <a href="manage_videos.php">
                            <h4 class="pull-left"><i class="fa fa-arrow-left"></i> رجوع</h4>
                        </a>
                    <?php } ?>
                    <?= $page_title ?>
                </span>
            </div>
            <div class="pb-card__body py-4">
                <form action="" name="addeditvideo" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="video_id" value="<?= (isset($_GET['video_id'])) ? $_GET['video_id'] : '' ?>" />
                    <div class="row">
                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">عنوان الفيديو</label>
                            <div class="col-sm-9">
                                <input type="text" name="video_title" value="<?php if (isset($_GET['video_id'])) {
                                                                                    echo $video_row['video_title'];
                                                                                } ?>" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">رابط الفيديو</label>
                            <div class="col-sm-9">
                                <input type="text" name="video_url" value="<?php if (isset($_GET['video_id'])) {
                                                                                echo $video_row['video_url'];
                                                                            } ?>" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">حالة النشر</label>
                            <div class="col-sm-9">
                                <select name="status" id="status" class="form-control" required>
                                    <option value="">--اختر حالة--</option>
                                    <?php if (isset($_GET['video_id'])) { ?>
                                        <option value=1 <?php if ($video_row['status'] == "1") { ?>selected<?php } ?>>منشور</option>
                                        <option value=0 <?php if ($video_row['status'] == '0') { ?>selected<?php } ?>>مسودة</option>
                                    <?php } else { ?>
                                        <option value=1>منشور</option>
                                        <option value=0>مسودة</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">اختر صورة مصغرة</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control-file" name="thumbnail" accept=".png, .jpg, .JPG .PNG" onchange="fileValidation()" id="fileupload">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">&nbsp;</label>
                            <div class="col-sm-9">
                                <div class="fileupload_img" id="imagePreview">
                                    <?php if ($row['thumbnail'] != "" and file_exists("images/" . $row['thumbnail'])) { ?>
                                        <img class="col-sm-3 img-thumbnail" type="image" src="images/'.$row['thumbnail']" alt="image" />
                                    <?php } else { ?>
                                        <img class="col-sm-3 img-thumbnail" type="image" src="assets/images/300x300.jpg" alt="image" />
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <button type="submit" name="submit" class="btn btn-primary" style="min-width: 110px;">حفظ</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Begin:: Main footer -->
        <?php include("includes/main_footer.php"); ?>
        <!-- End:: Main footer  -->
    </div>
</main>
<!-- End:: Theme main content -->
<?php include("includes/footer.php"); ?>