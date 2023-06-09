<?php
$page_title = "ادارة التقارير";
include("includes/header.php");
require("includes/lb_helper.php");
require("language/language.php");

$tableName = "tbl_reports";
$targetpage = "manage_report.php";
$limit = 15;

$query = "SELECT COUNT(*) as num FROM $tableName";
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

$sql_query = "SELECT * FROM tbl_reports ORDER BY tbl_reports.`id` DESC LIMIT $start, $limit";
$result = mysqli_query($mysqli, $sql_query) or die(mysqli_error($mysqli));
?>
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
            </div>
            <div class="pb-card__body py-4">
                <div class="table-responsive">
                    <?php if (mysqli_num_rows($result) > 0) { ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Report</th>
                                    <th class="text-center">Date</th>
                                    <th style="width: 200px;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                while ($row = mysqli_fetch_array($result)) { ?>
                                    <tr>
                                        <td><?php echo user_info($row['user_id'], "user_name"); ?></td>
                                        <td><?php echo $row['report_msg']; ?></td>
                                        <td class="text-center"><?php echo date('d-m-Y', $row['report_on']); ?></td>
                                        <td class="pb-link-icon text-center">
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
                            <h1 class="text-center">لا يوجد بيانات</h1>
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