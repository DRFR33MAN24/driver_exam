<?php
$page_title = "ادارة الفيديوهات";
include("includes/header.php");
require("includes/lb_helper.php");
require("language/language.php");

$tableName = "tbl_videos";
$targetpage = "manage_videos.php";
$limit = 15;
$keyword = '';

if (!isset($_GET['keyword'])) {
    $query = "SELECT COUNT(*) as num FROM $tableName";
} else {

    $keyword = addslashes(trim($_GET['keyword']));
    $query = "SELECT COUNT(*) as num FROM $tableName WHERE (`video_title` LIKE '%$keyword%' )";
    $targetpage = "manage_videos.php?keyword=" . $_GET['keyword'];
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
    $sql_query = "SELECT * FROM tbl_videos ORDER BY tbl_videos.`id` DESC LIMIT $start, $limit";
} else {
    $sql_query = "SELECT * FROM tbl_videos WHERE (`video_title` LIKE '%$keyword%' ) ORDER BY tbl_videos.`id` DESC LIMIT $start, $limit";
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
                        <a href="add_videos.php?add" class="btn btn-sm btn-primary">+ <span class="d-none d-sm-inline-block">اضافة فيديو</span></a>
                    </div>
                </div>
            </div>
            <div class="pb-card__body py-4">
                <div class="table-responsive">
                    <?php if (mysqli_num_rows($result) > 0) { ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">صورة مصغرة</th>
                                    <th>العنوان</th>
                                    <th>الرابط</th>
                                    <th class="text-center">النشر</th>
                                    <th style="width: 200px;" class="text-center">خيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                while ($row = mysqli_fetch_array($result)) { ?>
                                    <tr>
                                        <td>
                                            <div class="text-center">

                                                <?php if ($row['thumbnail'] != "" and file_exists("images/" . $row['thumbnail'])) { ?>
                                                    <img src="images/<?php echo $row['thumbnail'] ?>" class="pb-avatar__image_video" alt="">
                                                <?php } else { ?>
                                                    <img src="assets/images/user_photo.png" class="pb-avatar__image_video" alt="">
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td><?php echo $row['video_title']; ?></td>
                                        <td><?php echo $row['video_url']; ?></td>
                                        <td class="text-center">
                                            <?php if ($row['status'] != "0") { ?>
                                                <a class="enable_disable" href="javascript:void(0)" data-id="<?= $row['id'] ?>" data-table_id="id" data-table="<?= $tableName ?>" data-action="deactive" data-column="status" data-bs-toggle="tooltip" data-placement="top" title="تغيير الحالة"><span class="badge badge-success badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>نشر</span></span></a>
                                            <?php } else { ?>
                                                <a class="enable_disable" href="javascript:void(0)" data-id="<?= $row['id'] ?>" data-table_id="id" data-table="<?= $tableName ?>" data-action="active" data-column="status" data-bs-toggle="tooltip" data-placement="top" title="تغيير الحالة"><span class="badge badge-danger badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>الغاء النشر</span></span></a>
                                            <?php } ?>
                                        </td>
                                        <td class="pb-link-icon text-center">
                                            <a href="add_videos.php?video_id=<?php echo $row['id']; ?>" class="btn btn-primary" data-bs-toggle="tooltip" data-placement="top" title="Edit" style="padding: 10px 10px !important;">
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
                            <h1 class="text-center">لا يوجد فيديوهات مضافة</h1>
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