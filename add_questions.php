<?php
$page_title = (isset($_GET['questions_id'])) ? 'تعديل سؤال' : 'اضف سؤال';
include("includes/header.php");
require("includes/lb_helper.php");
require("language/language.php");
require_once("thumbnail_images.class.php");

$page_save = (isset($_GET['questions_id'])) ? 'حفظ' : 'إنشاء';

$language_qry = "SELECT * FROM tbl_language ORDER BY language_name";
$language_result = mysqli_query($mysqli, $language_qry);

if (isset($_POST['submit']) and isset($_GET['add'])) {


    if ($_FILES['image']['name'] != "") {

        if ($row['image'] != "") {
            unlink('images/' . $row['image']);
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = rand(0, 99999) . "_quiz." . $ext;
        $tpath1 = 'images/' . $image;

        if ($ext != 'png') {
            $pic1 = compress_image($_FILES["image"]["tmp_name"], $tpath1, 80);
        } else {
            $tmp = $_FILES['image']['tmp_name'];
            move_uploaded_file($tmp, $tpath1);
        }
    } else {
        $image = '';
    }

    $data = array(
        'lan_id'  =>  $_POST['lan_id'],
        'answer'  =>  addslashes($_POST['answer']),
        'answer_a'  =>  addslashes($_POST['answer_a']),
        'answer_b'  =>  addslashes($_POST['answer_b']),
        'answer_c'  =>  addslashes($_POST['answer_c']),
        'answer_d'  =>  addslashes($_POST['answer_d']),
        'correctAnswer'  =>  $_POST['correctAnswer'],
        'image_type' =>  $_POST['image_type'],
        'image'  =>  $image
    );

    $qry = Insert('tbl_quiz', $data);

    $_SESSION['msg'] = "10";
    $_SESSION['class'] = 'success';
    header("Location:add_questions.php?add=yes");
    exit;
}

if (isset($_GET['questions_id'])) {
    $qry = "SELECT * FROM tbl_quiz where id='" . $_GET['questions_id'] . "'";
    $result = mysqli_query($mysqli, $qry);
    $row = mysqli_fetch_assoc($result);
}

if (isset($_POST['submit']) and isset($_POST['questions_id'])) {

    if ($_FILES['image']['name'] != "") {

        if ($row['image'] != "") {
            unlink('images/' . $row['image']);
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = rand(0, 99999) . "_quiz." . $ext;
        $tpath1 = 'images/' . $image;

        if ($ext != 'png') {
            $pic1 = compress_image($_FILES["image"]["tmp_name"], $tpath1, 80);
        } else {
            $tmp = $_FILES['image']['tmp_name'];
            move_uploaded_file($tmp, $tpath1);
        }
    } else {
        $image = $row['image'];
    }

    $data = array(
        'lan_id'  =>  $_POST['lan_id'],
        'answer'  =>  addslashes($_POST['answer']),
        'answer_a'  =>  addslashes($_POST['answer_a']),
        'answer_b'  =>  addslashes($_POST['answer_b']),
        'answer_c'  =>  addslashes($_POST['answer_c']),
        'answer_d'  =>  addslashes($_POST['answer_d']),
        'correctAnswer'  =>  $_POST['correctAnswer'],
        'image_type' =>  $_POST['image_type'],
        'image'  =>  $image
    );

    $category_edit = Update('tbl_quiz', $data, "WHERE id = '" . $_POST['questions_id'] . "'");

    $_SESSION['msg'] = "11";
    $_SESSION['class'] = 'success';
    header("Location:add_questions.php?questions_id=" . $_POST['questions_id']);
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
                        <a href="<?php $_GET['redirect'] ?>">
                            <h4 class="pull-left"><i class="fa fa-arrow-left"></i> رجوع</h4>
                        </a>
                    <?php } else { ?>
                        <a href="manage_by_questions.php">
                            <h4 class="pull-left"><i class="fa fa-arrow-left"></i> رجوع</h4>
                        </a>
                    <?php } ?>
                    <?= $page_title ?>
                </span>
            </div>
            <div class="pb-card__body py-4">
                <form action="" name="addeditcategory" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="questions_id" value="<?= (isset($_GET['questions_id'])) ? $_GET['questions_id'] : '' ?>" />
                    <div class="row">

                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">اللغة</label>
                            <div class="col-sm-9">
                                <select name="lan_id" id="lan_id" class="form-control basic2" required>
                                    <option value="">--اختر اللغة--</option>
                                    <?php while ($lan_row = mysqli_fetch_array($language_result)) { ?>
                                        <?php if (isset($_GET['questions_id'])) { ?>
                                            <option value="<?php echo $lan_row['lid']; ?>" <?php if ($lan_row['lid'] == $row['lan_id']) { ?>selected<?php } ?>><?php echo $lan_row['language_name']; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $lan_row['lid']; ?>"><?php echo $lan_row['language_name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">السؤال</label>
                            <div class="col-sm-9">
                                <textarea name="answer" id="answer" class="form-control"><?php if (isset($_GET['questions_id'])) {
                                                                                                echo stripslashes($row['answer']);
                                                                                            } ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">الجواب A</label>
                            <div class="col-sm-9">
                                <textarea name="answer_a" id="answer_a" class="form-control"><?php if (isset($_GET['questions_id'])) {
                                                                                                    echo stripslashes($row['answer_a']);
                                                                                                } ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">الجواب B</label>
                            <div class="col-sm-9">
                                <textarea name="answer_b" id="answer_b" class="form-control"><?php if (isset($_GET['questions_id'])) {
                                                                                                    echo stripslashes($row['answer_b']);
                                                                                                } ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">الجواب C</label>
                            <div class="col-sm-9">
                                <textarea name="answer_c" id="answer_c" class="form-control"><?php if (isset($_GET['questions_id'])) {
                                                                                                    echo stripslashes($row['answer_c']);
                                                                                                } ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">الجواب D </label>
                            <div class="col-sm-9">
                                <textarea name="answer_d" id="answer_d" class="form-control"><?php if (isset($_GET['questions_id'])) {
                                                                                                    echo stripslashes($row['answer_d']);
                                                                                                } ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">الصحيح الجواب</label>
                            <div class="col-sm-9">
                                <select name="correctAnswer" id="correctAnswer" class="form-control" required>
                                    <?php if (isset($_GET['questions_id'])) { ?>
                                        <option value="A" <?php if ($row['correctAnswer'] == 'A') { ?>selected<?php } ?>>الجواب A</option>
                                        <option value="B" <?php if ($row['correctAnswer'] == 'B') { ?>selected<?php } ?>>الجواب B</option>
                                        <option value="C" <?php if ($row['correctAnswer'] == 'C') { ?>selected<?php } ?>>الجواب C</option>
                                        <option value="D" <?php if ($row['correctAnswer'] == 'D') { ?>selected<?php } ?>>الجواب D</option>
                                    <?php } else { ?>
                                        <option value="A">الجواب A</option>
                                        <option value="B">الجواب B</option>
                                        <option value="C">الجواب C</option>
                                        <option value="D">الجواب D</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">نوع الصورة</label>
                            <div class="col-sm-9">
                                <select name="image_type" id="image_type" class="form-control" required>
                                    <?php if (isset($_GET['questions_id'])) { ?>
                                        <option value="thumbnail_none" <?php if ($row['image_type'] == 'thumbnail_none') { ?>selected<?php } ?>>Image Hide</option>
                                        <option value="thumbnail_block" <?php if ($row['image_type'] == 'thumbnail_block') { ?>selected<?php } ?>>Image Show</option>
                                    <?php } else { ?>
                                        <option value="thumbnail_none">مخفية</option>
                                        <option value="thumbnail_block">ظاهرة</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div id="thumbnail" class="form-group row mb-4" <?php if (isset($_GET['questions_id']) and $row['image_type'] == 'thumbnail_block') { ?> style="display:show;" <?php } else { ?> style="display:none;" <?php } ?>>
                            <label class="col-sm-3 col-form-label">اختر صورة</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control-file" name="image" accept=".png, .jpg, .JPG .PNG" onchange="fileValidation()" id="fileupload">
                            </div>
                        </div>
                        <div id="thumbnail2" class="form-group row mb-4" <?php if (isset($_GET['questions_id']) and $row['image_type'] == 'thumbnail_block') { ?> style="display:show;" <?php } else { ?> style="display:none;" <?php } ?>>
                            <label class="col-sm-3 col-form-label">&nbsp;</label>
                            <div class="col-sm-9">
                                <div class="fileupload_img" id="imagePreview">
                                    <?php if (isset($_GET['questions_id'])) { ?>
                                        <img class="col-sm-3 img-thumbnail" type="image" src="images/<?php echo $row['image']; ?>" alt="image" />
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
<script>
    $("#image_type").change(function() {
        var type = $("#image_type").val();
        //alert(type);
        if (type == "thumbnail_none") {
            $("#thumbnail").hide();
            $("#thumbnail2").hide();
        } else {
            $("#thumbnail").show();
            $("#thumbnail2").show();

        }

    });
</script>