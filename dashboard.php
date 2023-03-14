<?php
$page_title = "Dashboard";
include("includes/header.php");
require("includes/lb_helper.php");

$qry_cat = "SELECT COUNT(*) as num FROM tbl_category";
$total_category = mysqli_fetch_array(mysqli_query($mysqli, $qry_cat));
$total_category = $total_category['num'];

$qry_language = "SELECT COUNT(*) as num FROM tbl_language";
$total_language = mysqli_fetch_array(mysqli_query($mysqli, $qry_language));
$total_language = $total_language['num'];

$qry_signs = "SELECT COUNT(*) as num FROM tbl_signs";
$total_signs = mysqli_fetch_array(mysqli_query($mysqli, $qry_signs));
$total_signs = $total_signs['num'];

$qry_users = "SELECT COUNT(*) as num FROM tbl_users";
$total_users = mysqli_fetch_array(mysqli_query($mysqli, $qry_users));
$total_users = $total_users['num'];

$qry_re = "SELECT COUNT(*) as num FROM tbl_reports";
$total_reports = mysqli_fetch_array(mysqli_query($mysqli, $qry_re));
$total_reports = $total_reports['num'];

$qry_questions = "SELECT COUNT(*) as num FROM tbl_quiz";
$total_questions = mysqli_fetch_array(mysqli_query($mysqli, $qry_questions));
$total_questions = $total_questions['num'];



$sql_reports = "SELECT * FROM tbl_reports ORDER BY tbl_reports.`id` DESC LIMIT 7";
$result_reports = mysqli_query($mysqli, $sql_reports);

$sql_user = "SELECT * FROM tbl_users ORDER BY tbl_users.`id` DESC LIMIT 7";
$result_user = mysqli_query($mysqli, $sql_user);

$countStr = '';
$no_data_status = false;
$count = $monthCount = 0;
for ($mon = 1; $mon <= 12; $mon++) {
    if (date('n') < $mon) {
        break;
    }
    $monthCount++;
    if (isset($_GET['filterByYear'])) {
        $year = $_GET['filterByYear'];
        $month = date('M', mktime(0, 0, 0, $mon, 1, $year));
        $sql_user = "SELECT `id` FROM tbl_users WHERE DATE_FORMAT(FROM_UNIXTIME(`registered_on`), '%c') = '$mon' AND DATE_FORMAT(FROM_UNIXTIME(`registered_on`), '%Y') = '$year'";
    } else {
        $month = date('M', mktime(0, 0, 0, $mon, 1, date('Y')));
        $sql_user = "SELECT `id` FROM tbl_users WHERE DATE_FORMAT(FROM_UNIXTIME(`registered_on`), '%c') = '$mon'";
    }
    $count = mysqli_num_rows(mysqli_query($mysqli, $sql_user));
    $countStr .= "['" . $month . "', " . $count . "], ";
    if ($count != 0) {
        $count++;
    }
}

if ($count != 0) {
    $no_data_status = false;
} else {
    $no_data_status = true;
}
$countStr = rtrim($countStr, ", ");

?>

<!-- Begin:: Theme main content -->
<main id="pb_main">
    <div class="pb-main-container">
        <div class="row">

            <div class="col-lg-3 col-xs-6">
                <a class="decoration-link" href="manage_category.php">
                    <div class="pb-card pb-card--air pb-card--data pb-card--primary mb-4 overflow-hidden">
                        <div class="pb-card__body d-flex align-items-center">
                            <div class="pb-card__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                    <path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z"></path>
                                </svg>
                            </div>
                            <div class="ms-auto text-end">
                                <span>Categories</span>
                                <h3 class="mb-0"><?php echo thousandsNumberFormat($total_category); ?></h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-xs-6">
                <a class="decoration-link" href="manage_language.php">
                    <div class="pb-card pb-card--air pb-card--data pb-card--danger mb-4 overflow-hidden">
                        <div class="pb-card__body d-flex align-items-center">
                            <div class="pb-card__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                    <path d="M16.99 8.95996H7.01001"></path>
                                    <path d="M12 7.28003V8.96002"></path>
                                    <path d="M14.5 8.93994C14.5 13.2399 11.14 16.7199 7 16.7199"></path>
                                    <path d="M16.9999 16.72C15.1999 16.72 13.6 15.76 12.45 14.25"></path>
                                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"></path>
                                </svg>
                            </div>
                            <div class="ms-auto text-end">
                                <span>اللغة</span>
                                <h3 class="mb-0"><?php echo thousandsNumberFormat($total_language); ?></h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-xs-6">
                <a class="decoration-link" href="manage_by_signs.php">
                    <div class="pb-card pb-card--air pb-card--data pb-card--info mb-4 overflow-hidden">
                        <div class="pb-card__body d-flex align-items-center">
                            <div class="pb-card__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                    <path d="M14.5501 2H9.45007C7.65007 2 7.25008 2.90001 7.02008 4.01001L6.20007 7.92999H17.8001L16.9801 4.01001C16.7501 2.90001 16.3501 2 14.5501 2Z" />
                                    <path d="M19.2401 14.3199C19.3201 15.1699 18.6401 15.9 17.7701 15.9H16.4101C15.6301 15.9 15.5201 15.57 15.3801 15.15L15.23 14.7199C15.03 14.1299 14.9001 13.7299 13.8501 13.7299H10.1401C9.10005 13.7299 8.94005 14.1799 8.76005 14.7199L8.61005 15.15C8.47005 15.56 8.36006 15.9 7.58006 15.9H6.22005C5.35005 15.9 4.67005 15.1699 4.75005 14.3199L5.16006 9.89996C5.26006 8.80996 5.47005 7.91992 7.37005 7.91992H16.62C18.52 7.91992 18.7301 8.80996 18.8301 9.89996L19.2401 14.3199Z" />
                                    <path d="M6.20009 5.75H5.47009" />
                                    <path d="M18.53 5.75H17.8" />
                                    <path d="M7.65002 10.8301H9.82004" />
                                    <path d="M14.1801 10.8301H16.3501" />
                                    <path d="M12 17V18" />
                                    <path d="M12 21V22" />
                                    <path d="M3 18L2 22" />
                                    <path d="M21 18L22 22" />
                                </svg>
                            </div>
                            <div class="ms-auto text-end">
                                <span>الاشارات المرورية</span>
                                <h3 class="mb-0"><?php echo thousandsNumberFormat($total_signs); ?></h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-xs-6">
                <a class="decoration-link" href="manage_by_questions.php">
                    <div class="pb-card pb-card--air pb-card--data pb-card--warning mb-4 overflow-hidden">
                        <div class="pb-card__body d-flex align-items-center">
                            <div class="pb-card__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                    <path d="M20 8.25V18C20 21 18.21 22 16 22H8C5.79 22 4 21 4 18V8.25C4 5 5.79 4.25 8 4.25C8 4.87 8.24997 5.43 8.65997 5.84C9.06997 6.25 9.63 6.5 10.25 6.5H13.75C14.99 6.5 16 5.49 16 4.25C18.21 4.25 20 5 20 8.25Z" />
                                    <path d="M16 4.25C16 5.49 14.99 6.5 13.75 6.5H10.25C9.63 6.5 9.06997 6.25 8.65997 5.84C8.24997 5.43 8 4.87 8 4.25C8 3.01 9.01 2 10.25 2H13.75C14.37 2 14.93 2.25 15.34 2.66C15.75 3.07 16 3.63 16 4.25Z" />
                                    <path d="M8 13H12" />
                                    <path d="M8 17H16" />
                                </svg>
                            </div>
                            <div class="ms-auto text-end">
                                <span>الاسئلة</span>
                                <h3 class="mb-0"><?php echo thousandsNumberFormat($total_questions); ?></h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-xs-6">
                <a class="decoration-link" href="manage_report.php">
                    <div class="pb-card pb-card--air pb-card--data pb-card--gren mb-4 overflow-hidden">
                        <div class="pb-card__body d-flex align-items-center">
                            <div class="pb-card__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                    <path d="M1.97998 13H5.76998C6.52998 13 7.21998 13.43 7.55998 14.11L8.44998 15.9C8.99998 17 9.99998 17 10.24 17H13.77C14.53 17 15.22 16.57 15.56 15.89L16.45 14.1C16.79 13.42 17.48 12.99 18.24 12.99H21.98" />
                                    <path d="M19 8C20.6569 8 22 6.65685 22 5C22 3.34315 20.6569 2 19 2C17.3431 2 16 3.34315 16 5C16 6.65685 17.3431 8 19 8Z" />
                                    <path d="M14 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22H15C20 22 22 20 22 15V10" />
                                </svg>
                            </div>
                            <div class="ms-auto text-end">
                                <span>التقارير</span>
                                <h3 class="mb-0"><?php echo thousandsNumberFormat($total_reports); ?></h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-xs-6">
                <a class="decoration-link" href="manage_users.php">
                    <div class="pb-card pb-card--air pb-card--data pb-card--primary mb-4 overflow-hidden">
                        <div class="pb-card__body d-flex align-items-center">
                            <div class="pb-card__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <div class="ms-auto text-end">
                                <span>المستخدمين</span>
                                <h3 class="mb-0"><?php echo thousandsNumberFormat($total_users); ?></h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="pb-card pb-card--air pb-card--data pb-card--danger mb-4 overflow-hidden">
                    <div class="pb-card__body d-flex align-items-center">
                        <div class="pb-card__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="45" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <div class="ms-auto text-end">
                            <span>الادمن</span>
                            <h3 class="mb-0">1</h3>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="pb-card container-fluid p-4">
                    <div class=" d-sm-flex align-items-sm-center">
                        <div class="col-lg-10">
                            <h3 style="font-weight: 900;">إحصائيات المستخدمين</h3>
                            <p style="font-weight: 200;">تسجيلات جديدة</p>
                        </div>
                        <div class="pb-card__head__option">
                            <form method="get" id="graphFilter">
                                <select class="form-control" name="filterByYear" style="width: 120px;">
                                    <?php
                                    $currentYear = date('Y');
                                    $minYear = 2020;
                                    for ($i = $currentYear; $i >= $minYear; $i--) {
                                    ?>
                                        <option value="<?= $i ?>" <?= (isset($_GET['filterByYear']) && $_GET['filterByYear'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
                                    <?php } ?>
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <?php if ($no_data_status) { ?>
                            <h3 class="text-muted text-center" style="padding-bottom: 2em">لا يوجد بيانات !</h3>
                        <?php } else { ?>
                            <div id="registerChart"></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-xl-6">
                <div class="pb-card mb-4">
                    <div class="pb-card__head">
                        <span class="pb-card__title">تقرير جديد</span>
                    </div>
                    <div class="pb-card__body">
                        <div class="table-responsive">
                            <?php if (mysqli_num_rows($result_reports) > 0) { ?>
                                <table class="table">
                                    <tbody>
                                        <?php $i = 0;
                                        while ($row = mysqli_fetch_array($result_reports)) { ?>
                                            <tr>
                                                <td><?php echo user_info($row['user_id'], "user_name"); ?></td>
                                                <td><?php echo $row['report_msg']; ?></td>
                                            </tr>
                                        <?php $i++;
                                        } ?>
                                    </tbody>
                                </table>
                            <?php } else { ?>
                                <ul class="p-2">
                                    <h3 class="text-center">لا توجد بيانات</h3>
                                </ul>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="pb-card mb-4">
                    <div class="pb-card__head">
                        <span class="pb-card__title">مستخدمين جدد</span>
                    </div>
                    <div class="pb-card__body">
                        <div class="table-responsive">
                            <?php if (mysqli_num_rows($result_user) > 0) { ?>
                                <table class="table">
                                    <tbody>
                                        <?php $i = 0;
                                        while ($row = mysqli_fetch_array($result_user)) { ?>
                                            <tr>
                                                <td><?php echo $row['user_name']; ?></td>
                                                <td><?php echo $row['user_email']; ?></td>
                                            </tr>
                                        <?php $i++;
                                        } ?>
                                    </tbody>
                                </table>
                            <?php } else { ?>
                                <ul class="p-2">
                                    <h3 class="text-center">لا يوجد بيانات !</h3>
                                </ul>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Begin:: Main footer -->
        <?php include("includes/main_footer.php"); ?>
        <!-- End:: Main footer  -->
    </div>
</main>
<!-- End:: Theme main content -->
<?php include("includes/footer.php"); ?>

<?php if (!$no_data_status) { ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
        google.charts.load('current', {
            packages: ['corechart', 'line']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Month');
            data.addColumn('number', 'Users');

            data.addRows([<?= $countStr ?>]);

            var options = {
                curveType: 'function',
                fontSize: 15,
                hAxis: {
                    title: "Months of <?= (isset($_GET['filterByYear'])) ? $_GET['filterByYear'] : date('Y') ?>",
                    titleTextStyle: {
                        color: '#000',
                        bold: 'true',
                        italic: false
                    },
                },
                vAxis: {
                    title: "Nos of Users",
                    titleTextStyle: {
                        color: '#000',
                        bold: 'true',
                        italic: false,
                    },
                    gridlines: {
                        count: 5
                    },
                    format: '#',
                    viewWindowMode: "explicit",
                    viewWindow: {
                        min: 0
                    },
                },
                height: 400,
                chartArea: {
                    left: 100,
                    top: 20,
                    width: '100%',
                    height: 'auto'
                },
                legend: {
                    position: 'none'
                },
                lineWidth: 4,
                animation: {
                    startup: true,
                    duration: 1200,
                    easing: 'out',
                },
                pointSize: 5,
                pointShape: "circle",
                colors: ['#2196f3']

            };
            var chart = new google.visualization.LineChart(document.getElementById('registerChart'));

            chart.draw(data, options);
        }

        $(document).ready(function() {
            $(window).resize(function() {
                drawChart();
            });
        });
    </script>
<?php } ?>

<script type="text/javascript">
    // filter of graph
    $("select[name='filterByYear']").on("change", function(e) {
        $("#graphFilter").submit();
    });
</script>