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
        $pres_number = $_GET['pres_number'];
        $pres_id = $_GET['pres_id'];
        $ret = "SELECT  * FROM prescriptions WHERE pres_number = ? AND pres_id = ?";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('ii', $pres_number, $pres_id);
        //$stmt->bind_param('i',$pres_id);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        //$cnt=1;
        while ($row = $res->fetch_object()) {
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pharmaceuticals</a></li>
                                            <li class="breadcrumb-item active">View Prescriptions</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">#<?php echo $row->pres_number; ?></h4>
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
                                            <h4 class="m-0 d-print-none"><?php echo $row->mdr_pat_name; ?>&apos;s Medical Record</h4>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mt-3">
                                                <p><b></b></p>
                                                <p class="text-muted"></p>
                                            </div>

                                        </div><!-- end col -->
                                        <div class="col-md-4 offset-md-2">
                                            <div class="mt-3 float-right">
                                                <p class="m-b-10"><strong>Generated Date : </strong> <span class="float-right"> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo date("d-m-Y - h:m:s", strtotime($mysqlDateTime)); ?> </span></p>
                                                <p class="m-b-10"><strong>Patient Number : </strong> <span class="float-right"><?php echo $row->pres_pat_number; ?></span></p>
                                                <p class="m-b-10"><strong>Patient's Category : </strong> <span class="float-right"><?php echo $row->pres_pat_type; ?></span></p>
                                                <p class="m-b-10"><strong>Patient's Name : </strong> <span class="float-right"> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $row->pres_pat_name; ?> </span></p>
                                                <p class="m-b-10"><strong>Patient's Age : </strong> <span class="float-right"> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $row->pres_pat_age ?> </span></p>
                                                <!-- <p class="m-b-10"><strong>Payroll Status : </strong> <span class="float-right"><span class="badge badge-success"><?php echo $row->pay_status; ?></span></span></p> -->
                                                <p class="m-b-10"><strong>Patient's Address : </strong> <span class="float-right"><?php echo $row->pres_pat_adr; ?></span></p>

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
                                                            <th>Prescription</th>
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
                                                                <?php echo $row->pres_pat_ailment; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $row->pres_ins; ?>
                                                            </td>
                                                            <td>
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
                                                <h4 class="font-weight-bold">Remarks:</h4>

                                                <span class="font-17 px-2 font-weight-bold">

                                                    <?php echo $row->mdr_remarks; ?>
                                                </span>

                                            </div>
                                        </div> <!-- end col -->
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="clearfix pt-5 d-flex align-items-center">
                                                <h4 class="font-weight-bold">Prescripbed by:</h4>

                                                <span class="font-17 px-2 font-weight-bold">

                                                    <?php echo $row->prescribed_by; ?>
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
                        <div class="row">
                            <div class="col-12">
                                <div class="card-box">
                                    <div class="row">

                                        <div class="col-xl-7">
                                            <div class="pl-xl-3 mt-3 mt-xl-0">
                                                <h2 class="mb-3">Name : <?php echo $row->pres_pat_name; ?></h2>
                                                <hr>
                                                <h3 class="text-danger">Age : <?php echo $row->pres_pat_age; ?> Years</h3>
                                                <hr>
                                                <h3 class="text-danger ">Patient Number : <?php echo $row->pres_pat_number; ?></h3>
                                                <hr>
                                                <h3 class="text-danger ">Patient Category : <?php echo $row->pres_pat_type; ?></h3>
                                                <hr>
                                                <h3 class="text-danger ">Patient Ailment : <?php echo $row->pres_pat_ailment; ?></h3>
                                                <hr>
                                                <h2 class="align-centre">Prescription</h2>
                                                <hr>
                                                <p class="text-muted mb-4">
                                                    <?php echo $row->pres_ins; ?>
                                                </p>
                                                <hr>
                                                <!--
                                                    <form class="form-inline mb-4">
                                                        <label class="my-1 mr-2" for="quantityinput">Quantity</label>
                                                        <select class="custom-select my-1 mr-sm-3" id="quantityinput">
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                            <option value="6">6</option>
                                                            <option value="7">7</option>
                                                        </select>

                                                        <label class="my-1 mr-2" for="sizeinput">Size</label>
                                                        <select class="custom-select my-1 mr-sm-3" id="sizeinput">
                                                            <option selected>Small</option>
                                                            <option value="1">Medium</option>
                                                            <option value="2">Large</option>
                                                            <option value="3">X-large</option>
                                                        </select>
                                                    </form>

                                                    <div>
                                                        <button type="button" class="btn btn-danger mr-2"><i class="mdi mdi-heart-outline"></i></button>
                                                        <button type="button" class="btn btn-success waves-effect waves-light">
                                                            <span class="btn-label"><i class="mdi mdi-cart"></i></span>Add to cart
                                                        </button>
                                                    </div> -->
                                            </div>
                                        </div> <!-- end col -->
                                    </div>
                                    <!-- end row -->

                                    <!--
                                        <div class="table-responsive mt-4">
                                            <table class="table table-bordered table-centered mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Outlets</th>
                                                        <th>Price</th>
                                                        <th>Stock</th>
                                                        <th>Revenue</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>ASOS Ridley Outlet - NYC</td>
                                                        <td>$139.58</td>
                                                        <td>
                                                            <div class="progress-w-percent mb-0">
                                                                <span class="progress-value">478 </span>
                                                                <div class="progress progress-sm">
                                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 56%;" aria-valuenow="56" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>$1,89,547</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Marco Outlet - SRT</td>
                                                        <td>$149.99</td>
                                                        <td>
                                                            <div class="progress-w-percent mb-0">
                                                                <span class="progress-value">73 </span>
                                                                <div class="progress progress-sm">
                                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 16%;" aria-valuenow="16" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>$87,245</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Chairtest Outlet - HY</td>
                                                        <td>$135.87</td>
                                                        <td>
                                                            <div class="progress-w-percent mb-0">
                                                                <span class="progress-value">781 </span>
                                                                <div class="progress progress-sm">
                                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 72%;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>$5,87,478</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nworld Group - India</td>
                                                        <td>$159.89</td>
                                                        <td>
                                                            <div class="progress-w-percent mb-0">
                                                                <span class="progress-value">815 </span>
                                                                <div class="progress progress-sm">
                                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 89%;" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>$55,781</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div> -->

                                </div> <!-- end card-->
                            </div> <!-- end col-->
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