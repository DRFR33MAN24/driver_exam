<?php
$page_title = "ادارة فئات الاسئلة";
include("includes/header.php");
require("includes/lb_helper.php");
require("language/language.php");

$tableName = "tbl_questions_categories";
$targetpage = "manage_questions_categories.php";
$limit = 15;
$keyword = '';

if (!isset($_GET['keyword'])) {
    $query = "SELECT COUNT(*) as num FROM $tableName";
} else {

    $keyword = addslashes(trim($_GET['keyword']));
    $query = "SELECT COUNT(*) as num FROM $tableName WHERE (`category_name` LIKE '%$keyword%' )";
    $targetpage = "manage_questions_categories.php?keyword=" . $_GET['keyword'];
}

$total_pages = mysqli_fetch_array(mysqli_query($mysqli, $query));
$total_pages = $total_pages['num'];

$stages = 3;
$page = 0;
if (isset($_GET['page'])) {
    $page = mysqli_real_escape_string($mysqli, $_GET['page']);
}
if ($page) {
    $start = ($page - 1) * $limit;
} else {
    $start = 0;
}

if (!isset($_GET['keyword'])) {
    $sql_query = "SELECT * FROM tbl_questions_categories ORDER BY tbl_questions_categories.`id` DESC LIMIT $start, $limit";
} else {
    $sql_query = "SELECT * FROM tbl_questions_categories WHERE (`category_name` LIKE '%$keyword%' ) ORDER BY tbl_questions_categories.`id` DESC LIMIT $start, $limit";
}
$result = mysqli_query($mysqli, $sql_query) or die(mysqli_error($mysqli));
?>
<style>
    .table td img.social_img {
        position: absolute;
        width: 20px !important;
        height: 20px !important;
        z-index: 1;
        left: 73px;
        margin: -8px;
    }
</style>
<!-- Begin:: Theme main content -->
<main id="pb_main">
    <div class="pb-main-container">
        <div class="pb-card">
            <div class="pb-card__head d-sm-flex align-items-sm-center">
                <span class="pb-card__title mb-2 mb-sm-0">
                    <?php if (isset($_SERVER['HTTP_REFERER'])) {
                        echo '<a href="' . $_SERVER['HTTP_REFERER'] . '"><h4 class="pull-left"><i class="fa fa-arrow-left"></i> رجوع</h4></a>';
                    } ?>
                    <?= $page_title ?>
                </span>
                <div class="pb-card__head__option">
                    <form method="get" action="">
                        <div class="pb-card__head__option__item">
                            <div class="input-group input-group-sm">
                                <input type="text" type="search" name="keyword" class="form-control" placeholder="ابحث هنا" value="<?php if (isset($_GET['keyword'])) {
                                                                                                                                        echo $_GET['keyword'];
                                                                                                                                    } ?>" required="required">
                                <button type="submit" class="btn btn-outline-secondary" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="pb-card__head__option__item">
                        <a href="add_questions_categories.php?add" class="btn btn-sm btn-primary">+ <span class="d-none d-sm-inline-block">اضافة فئة</span></a>
                    </div>
                </div>
            </div>
            <div class="pb-card__body py-4">
                <div class="table-responsive">
                    <?php if (mysqli_num_rows($result) > 0) { ?>
                        <table class="table">
                            <thead>
                                <tr>

                                    <th>الاسم</th>

                                    <th style="width: 200px;" class="text-center">خيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                while ($row = mysqli_fetch_array($result)) { ?>
                                    <tr>

                                        <td><?php echo $row['category_name']; ?></td>


                                        <td class="pb-link-icon text-center">
                                            <a href="add_questions_categories.php?question_cat_id=<?php echo $row['id']; ?>" class="btn btn-primary" data-bs-toggle="tooltip" data-placement="top" title="Edit" style="padding: 10px 10px !important;">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="" class="btn btn-danger delete_data" data-id="<?php echo $row['id']; ?>" data-table="<?= $tableName ?>" data-bs-toggle="tooltip" data-placement="top" title="Delete" style="padding: 10px 10px !important;">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php $i++;
                                } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <ul class="p-5">
                            <h1 class="text-center">لا يوجد فئات مضافة</h1>
                        </ul>
                    <?php } ?>
                </div>
                <!-- Begin:: Pagination -->
                <?php include("pagination.php"); ?>
                <!-- End:: Pagination -->
            </div>
        </div>
        <!-- Begin:: Main footer -->
        <?php include("includes/main_footer.php"); ?>
        <!-- End:: Main footer  -->
    </div>
</main>
<!-- End:: Theme main content -->
<?php include("includes/footer.php"); ?>