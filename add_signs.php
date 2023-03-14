<?php
$page_title = (isset($_GET['signs_id'])) ? 'تعديل اشارة مرورية' : 'اضافة اشارة مرورية';
include("includes/header.php");
require("includes/lb_helper.php");
require("language/language.php");
require_once("thumbnail_images.class.php");

$page_save = (isset($_GET['signs_id'])) ? 'Save' : 'Create';

$cat_qry = "SELECT * FROM tbl_category ORDER BY category_name";
$cat_result = mysqli_query($mysqli, $cat_qry);

$language_qry = "SELECT * FROM tbl_language ORDER BY language_name";
$language_result = mysqli_query($mysqli, $language_qry);

if (isset($_POST['submit']) and isset($_GET['add'])) {

    if ($_FILES['signs_image']['name'] != "") {

        $ext = pathinfo($_FILES['signs_image']['name'], PATHINFO_EXTENSION);
        $signs_image = rand(0, 99999) . "_signs." . $ext;
        $tpath1 = 'images/' . $signs_image;

        if ($ext != 'png') {
            $pic1 = compress_image($_FILES["signs_image"]["tmp_name"], $tpath1, 80);
        } else {
            $tmp = $_FILES['signs_image']['tmp_name'];
            move_uploaded_file($tmp, $tpath1);
        }
    } else {
        $signs_image = '';
    }

    $data = array(
        'signs_name'  =>  cleanInput($_POST['signs_name']),
        'cat_id'  =>  $_POST['cat_id'],
        'lan_id'  =>  $_POST['lan_id'],
        'signs_image'  =>  $signs_image
    );

    $qry = Insert('tbl_signs', $data);

    $_SESSION['msg'] = "10";
    $_SESSION['class'] = 'success';
    header("Location:add_signs.php?add=yes");
    exit;
}

if (isset($_GET['signs_id'])) {
    $qry = "SELECT * FROM tbl_signs where id='" . $_GET['signs_id'] . "'";
    $result = mysqli_query($mysqli, $qry);
    $row = mysqli_fetch_assoc($result);
}

if (isset($_POST['submit']) and isset($_POST['signs_id'])) {

    if ($_FILES['signs_image']['name'] != "") {

        if ($row['signs_image'] != "") {
            unlink('images/' . $row['signs_image']);
        }

        $ext = pathinfo($_FILES['signs_image']['name'], PATHINFO_EXTENSION);
        $signs_image = rand(0, 99999) . "_signs." . $ext;
        $tpath1 = 'images/' . $signs_image;

        if ($ext != 'png') {
            $pic1 = compress_image($_FILES["signs_image"]["tmp_name"], $tpath1, 80);
        } else {
            $tmp = $_FILES['signs_image']['tmp_name'];
            move_uploaded_file($tmp, $tpath1);
        }
    } else {
        $signs_image = $row['signs_image'];
    }

    $data = array(
        'signs_name'  =>  cleanInput($_POST['signs_name']),
        'cat_id'  =>  $_POST['cat_id'],
        'lan_id'  =>  $_POST['lan_id'],
        'signs_image'  =>  $signs_image
    );

    $category_edit = Update('tbl_signs', $data, "WHERE id = '" . $_POST['signs_id'] . "'");

    $_SESSION['msg'] = "11";
    $_SESSION['class'] = 'success';
    header("Location:add_signs.php?signs_id=" . $_POST['signs_id']);
    exit;
}
?>
<!-- Begin:: Theme main content -->
<main id="pb_main">
    <div class="pb-main-container">
        <div class="pb-card">
            <div class="pb-card__head">
                <span class="pb-card__title mb-2 mb-sm-0"><?= $page_title ?></span>
            </div>
            <div class="pb-card__body py-4">
                <form action="" name="addeditcategory" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="signs_id" value="<?= (isset($_GET['signs_id'])) ? $_GET['signs_id'] : '' ?>" />
                    <div class="row">
                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">الفئة</label>
                            <div class="col-sm-9">
                                <select name="cat_id" id="cat_id" class="form-control basic" required>
                                    <option value="">--اختر فئة--</option>
                                    <?php while ($cat_row = mysqli_fetch_array($cat_result)) { ?>
                                        <?php if (isset($_GET['signs_id'])) { ?>
                                            <option value="<?php echo $cat_row['cid']; ?>" <?php if ($cat_row['cid'] == $row['cat_id']) { ?>selected<?php } ?>><?php echo $cat_row['category_name']; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $cat_row['cid']; ?>"><?php echo $cat_row['category_name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">اللغة</label>
                            <div class="col-sm-9">
                                <select name="lan_id" id="lan_id" class="form-control basic2" required>
                                    <option value="">--اختر اللغة--</option>
                                    <?php while ($lan_row = mysqli_fetch_array($language_result)) { ?>
                                        <?php if (isset($_GET['signs_id'])) { ?>
                                            <option value="<?php echo $lan_row['lid']; ?>" <?php if ($lan_row['lid'] == $row['lan_id']) { ?>selected<?php } ?>><?php echo $lan_row['language_name']; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $lan_row['lid']; ?>"><?php echo $lan_row['language_name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">Signs Name</label>
                            <div class="col-sm-9">
                                <input type="text" name="signs_name" class="form-control" value="<?php if (isset($_GET['signs_id'])) {
                                                                                                        echo $row['signs_name'];
                                                                                                    } ?>" required>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">Select Image</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control-file" name="signs_image" accept=".png, .jpg, .JPG .PNG" onchange="fileValidation()" id="fileupload">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">&nbsp;</label>
                            <div class="col-sm-9">
                                <div class="fileupload_img" id="imagePreview">
                                    <?php if (isset($_GET['signs_id'])) { ?>
                                        <img class="col-sm-3 img-thumbnail" type="image" src="images/<?php echo $row['signs_image']; ?>" alt="image" />
                                    <?php } else { ?>
                                        <img class="col-sm-3 img-thumbnail" type="image" src="assets/images/300x300.jpg" alt="image" />
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <button type="submit" name="submit" class="btn btn-primary" style="min-width: 110px;"><?= $page_save ?></button>
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