<?php
$page_title = (isset($_GET['lan_id'])) ? 'تعديل اللغة' : 'اضافة لغة';
include("includes/header.php");
require("includes/lb_helper.php");
require("language/language.php");

$page_save = (isset($_GET['lan_id'])) ? 'حفظ' : 'إنشاء';

if (isset($_POST['submit']) and isset($_GET['add'])) {

    $data = array(
        'language_name'  =>  cleanInput($_POST['language_name'])
    );

    $qry = Insert('tbl_language', $data);

    $_SESSION['msg'] = "10";
    $_SESSION['class'] = 'success';
    header("Location:manage_language.php");
    exit;
}

if (isset($_GET['lan_id'])) {
    $qry = "SELECT * FROM tbl_language where lid='" . $_GET['lan_id'] . "'";
    $result = mysqli_query($mysqli, $qry);
    $row = mysqli_fetch_assoc($result);
}

if (isset($_POST['submit']) and isset($_POST['lan_id'])) {

    $data = array(
        'language_name'  =>  cleanInput($_POST['language_name'])
    );

    $category_edit = Update('tbl_language', $data, "WHERE lid = '" . $_POST['lan_id'] . "'");

    $_SESSION['msg'] = "11";
    $_SESSION['class'] = 'success';
    header("Location:add_language.php?lan_id=" . $_POST['lan_id']);
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
                        <a href="manage_language.php">
                            <h4 class="pull-left"><i class="fa fa-arrow-left"></i> رجوع</h4>
                        </a>
                    <?php } ?>
                    <?= $page_title ?>
                </span>
            </div>
            <div class="pb-card__body py-4">
                <form action="" name="addeditcategory" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="lan_id" value="<?= (isset($_GET['lan_id'])) ? $_GET['lan_id'] : '' ?>" />
                    <div class="row">
                        <div class="form-group row mb-4">
                            <label class="col-sm-3 col-form-label">اسم اللغة</label>
                            <div class="col-sm-9">
                                <input type="text" name="language_name" class="form-control" value="<?php if (isset($_GET['lan_id'])) {
                                                                                                        echo $row['language_name'];
                                                                                                    } ?>" required>
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