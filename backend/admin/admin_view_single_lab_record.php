<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];
?>
<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php'); ?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include('assets/inc/nav.php'); ?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("assets/inc/sidebar.php"); ?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <?php
        $lab_id = $_GET['lab_id'];
        $lab_number = $_GET['lab_number'];
        $ret = "SELECT  * FROM laboratory WHERE lab_id = ?";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $lab_id);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        //$cnt=1;
        while ($row = $res->fetch_object()) {
            $mysqlDateTime = $row->lab_date_rec;
        ?>

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Laboratory Records</a></li>
                                            <li class="breadcrumb-item active">View Records</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">#<?php echo $row->lab_number; ?></h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card-box">
                                    <!-- Logo & title -->
                                    <div class="clearfix">
                                        <div class="float-left">
                                            <img src="assets/images/mediverse.png" alt="" height="150">
                                        </div>
                                        <div class="float-right">
                                            <h4 class="m-0 d-print-none"><?php echo $row->lab_pat_name; ?>&apos;s Lab Report</h4>
                                        </div>
                                    </div>

                                    <div class="row justify-content-end">
                                        <div class="col-md-6">
                                            <div class="mt-3">
                                                <p><b></b></p>
                                                <p class="text-muted"></p>
                                            </div>

                                        </div><!-- end col -->
                                        <div class="col-md-6 offset-md-2">
                                            <div class="mt-3 float-right">
                                                <p class="m-b-10"><strong>Patient Number : </strong> <span class="float-right"><?php echo $row->lab_pat_number; ?></span></p>
                                                <p class="m-b-10"><strong>Patient Name : </strong> <span class="float-right"><?php echo $row->lab_pat_name; ?></span></p>
                                                <p class="m-b-10"><strong>Lab Test Date : </strong> <span class="float-right"><?php echo $row->lab_date_rec; ?></span></p>
                                                <p class="m-b-10"><strong>Generated Date : </strong> <span class="float-right"> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo date("d-m-Y - h:m:s", strtotime($mysqlDateTime)); ?> </span></p>
                                                <!-- <p class="m-b-10"><strong>Payroll Status : </strong> <span class="float-right"><span class="badge badge-success"><?php echo $row->pay_status; ?></span></span></p> -->
                                                <!-- <p class="m-b-10"><strong>Prescription Date : </strong> <span class="float-right"><?php echo $row->lab_date; ?></span></p> -->

                                            </div>
                                        </div><!-- end col -->
                                    </div>
                                    <!-- end row -->
                                    <!-- end row -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table mt-4 table-centered table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>S.N</th>
                                                            <th>Ailment</th>
                                                            <th>Lab Tests</th>
                                                            <th>Result</th>
                                                            <!-- <th>Remarks</th> -->
                                                            <!-- <th style="width/: 10%">(PAYE)Tax Rate</th> -->
                                                            <!-- <th style="width: 10%" class="text-right">Total Tax</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><?php
                                                                $cnt = 1;
                                                                echo $cnt; ?></td>
                                                            <td>
                                                                <?php echo $row->lab_pat_ailment; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $row->lab_pat_tests; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $row->lab_pat_results; ?>
                                                            </td>


                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div> <!-- end table-responsive -->
                                        </div> <!-- end col -->
                                    </div>
                                    <!-- end row -->

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="clearfix pt-5 d-flex align-items-center">
                                                <h4 class="font-weight-bold">Reported by:</h4>

                                                <span class="font-17 px-2 font-weight-bold">

                                                    <?php echo $row->reported_by; ?>
                                                </span>

                                            </div>
                                        </div> <!-- end col -->
                                    </div>
                                    <!-- end row -->

                                    <div class="mt-4 mb-1">
                                        <div class="text-right d-print-none">
                                            <a href="javascript:window.print()" class="btn btn-primary waves-effect waves-light"><i class="mdi mdi-printer mr-1"></i> Print</a>
                                        </div>
                                    </div>
                                </div> <!-- end card-box -->
                            </div> <!-- end col -->
                        </div>
                        <!-- end row-->

                    </div> <!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                <?php include('assets/inc/footer.php'); ?>
                <!-- end Footer -->

            </div>
        <?php } ?>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->



    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>