<?php
$page_title = (isset($_GET['question_cat_id'])) ? 'تعديل فئة' : 'اضافة فئة';
include("includes/header.php");
require("includes/lb_helper.php");
require("language/language.php");
require_once("thumbnail_images.class.php");

$page_save = (isset($_GET['question_cat_id'])) ? 'حفظ' : 'انشاء';

if (isset($_POST['submit']) and isset($_GET['add'])) {



    $data = array(


        'category_name' => $_POST['category_name'],
    );

    $qry = Insert('tbl_questions_categories', $data);

    $_SESSION['msg'] = "10";
    $_SESSION['class'] = 'success';
    header("location:manage_questions_categories.php");
    exit;
}

if (isset($_GET['question_cat_id'])) {
    $q_cat_qry = "SELECT * FROM tbl_questions_categories where id='" . $_GET['question_cat_id'] . "'";
    $q_cat_result = mysqli_query($mysqli, $q_cat_qry);
    $q_cat_row = mysqli_fetch_assoc($q_cat_result);
}

if (isset($_POST['submit']) and isset($_POST['question_cat_id'])) {


    $data = array(
        'category_name' => $_POST['category_name'],

    );


    $q_cat_edit = Update('tbl_questions_categories', $data, "WHERE id = '" . $_POST['question_cat_id'] . "'");

    $_SESSION['msg'] = "11";
    $_SESSION['class'] = 'success';
    header("Location:add_questions_categories.php?question_cat_id=" . $_POST['question_cat_id']);
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
                        <a href="manage_questions_categories.php">
                            <h4 class="pull-left"><i class="fa fa-arrow-left"></i> رجوع</h4>
                        </a>
                    <?php } ?>
                    <?= $page_title ?>
                </span>
            </div>
            <div class="pb-card__body py-4">
                <form action="" name="addeditquestioncategory" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="question_cat_id" value="<?= (isset($_GET['question_cat_id'])) ? $_GET['question_cat_id'] : '' ?>" />
                    <div class="row">
                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">اسم الفئة</label>
                            <div class="col-sm-9">
                                <input type="text" name="category_name" value="<?php if (isset($_GET['question_cat_id'])) {
                                                                                    echo $q_cat_row['category_name'];
                                                                                } ?>" class="form-control" required>
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