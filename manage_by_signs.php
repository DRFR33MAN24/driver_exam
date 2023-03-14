<?php
$page_title = "ادارة اللغة";
include("includes/header.php");
require("includes/lb_helper.php");
require("language/language.php");

$tableName = "tbl_language";
$targetpage = "manage_by_signs.php";
$limit = 12;

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

$sql_query = "SELECT * FROM tbl_language ORDER BY tbl_language.`lid` DESC LIMIT $start, $limit";
$result = mysqli_query($mysqli, $sql_query) or die(mysqli_error($mysqli));

function get_total_post($cat_id)
{
    global $mysqli;
    $qry_songs = "SELECT COUNT(*) as num FROM tbl_signs WHERE lan_id='" . $cat_id . "'";
    $total_songs = mysqli_fetch_array(mysqli_query($mysqli, $qry_songs));
    $total_songs = $total_songs['num'];
    return $total_songs;
}

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
                <?php if (mysqli_num_rows($result) > 0) { ?>
                    <div class="row">
                        <?php $i = 0;
                        while ($row = mysqli_fetch_array($result)) { ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="pb-card-post-text">
                                    <h5><?php echo $row['language_name']; ?></h5>
                                    <h6 class="card-title">Number of signs : <?php echo get_total_post($row['lid']); ?></h6>
                                    <a href="manage_signs.php?lan_id=<?php echo $row['lid']; ?>" class="btn btn-success btn_cust bs-tooltip mt-2">
                                        <i class="fa fa-history"> manage signs</i>
                                    </a>
                                </div>
                            </div>
                        <?php $i++;
                        } ?>
                    </div>
                <?php } else { ?>
                    <ul class="p-5">
                        <h1 class="text-center">لا يوجد بيانات</h1>
                    </ul>
                <?php } ?>
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