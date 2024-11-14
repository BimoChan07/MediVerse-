<?php
session_start();
include('assets/inc/config.php');

if (isset($_POST['add_patient_lab_test'])) {
    $lab_pat_name = $_POST['lab_pat_name'];
    $lab_pat_ailment = $_POST['lab_pat_ailment'];
    $lab_pat_number = $_POST['lab_pat_number'];
    $lab_pat_tests = $_POST['lab_pat_tests'];
    $lab_number = $_POST['lab_number'];
    $reported_by = $_POST['reported_by'];

    // SQL to insert captured values
    $query = "INSERT INTO laboratory (lab_pat_name, lab_pat_ailment, lab_pat_number, lab_pat_tests, lab_number, reported_by) VALUES(?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssssss', $lab_pat_name, $lab_pat_ailment, $lab_pat_number, $lab_pat_tests, $lab_number, $reported_by);
    $stmt->execute();

    if ($stmt) {
        $success = "Patient Laboratory Tests Added";
    } else {
        $err = "Please Try Again Or Try Later";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<!-- Head -->
<?php include('assets/inc/head.php'); ?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include("assets/inc/nav.php"); ?>
        <!-- end Topbar -->

        <!-- Left Sidebar Start -->
        <?php include("assets/inc/sidebar.php"); ?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <?php
        $pat_number = $_GET['pat_number'];
        $ret = "SELECT * FROM patients WHERE pat_number=?";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('s', $pat_number);
        $stmt->execute();
        $res = $stmt->get_result();
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Laboratory</a></li>
                                            <li class="breadcrumb-item active">Add Lab Test</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Add Lab Test</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <!-- Form row -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Fill all fields</h4>
                                        <!-- Add Patient Form -->
                                        <form method="post">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputEmail4" class="col-form-label">Patient Name</label>
                                                    <input type="text" required="required" readonly name="lab_pat_name" value="<?php echo $row->pat_fname; ?> <?php echo $row->pat_lname; ?>" class="form-control" id="inputEmail4" placeholder="Patient's First Name">
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="inputPassword4" class="col-form-label">Patient Ailment</label>
                                                    <input required="required" type="text" readonly name="lab_pat_ailment" value="<?php echo $row->pat_ailment; ?>" class="form-control" id="inputPassword4" placeholder="Patient's Ailment">
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <label for="inputEmail4" class="col-form-label">Patient Number</label>
                                                    <input type="text" required="required" readonly name="lab_pat_number" value="<?php echo $row->pat_number; ?>" class="form-control" id="inputEmail4" placeholder="Patient Number">
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-2" style="display:none">
                                                    <?php
                                                    $length = 5;
                                                    $pres_no =  substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);
                                                    ?>
                                                    <label for="inputZip" class="col-form-label">Lab Test Number</label>
                                                    <input type="text" name="lab_number" value="<?php echo $pres_no; ?>" class="form-control" id="inputZip">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputAddress" class="col-form-label">Laboratory Tests</label>
                                                <textarea required="required" type="text" class="form-control" name="lab_pat_tests" rows="5"></textarea>
                                            </div>

                                            <button type="button" name="recommend_tests" class="btn btn-secondary">
                                                Recommend Tests
                                            </button>

                                            <div class="form-group">
                                                <label for="inputAddress" class="col-form-label">Reported By</label>
                                                <textarea required="required" type="text" class="form-control" name="reported_by" id="" rows="1"></textarea>
                                            </div>

                                            <button type="submit" name="add_patient_lab_test" class="ladda-button btn btn-success" data-style="expand-right">Add Laboratory Test</button>

                                        </form>
                                        <!-- End Patient Form -->
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                    </div> <!-- container -->
                </div> <!-- content -->

                <!-- Footer Start -->
                <?php include('assets/inc/footer.php'); ?>
                <!-- end Footer -->

            </div>
        <?php } ?>



    </div>
    <!-- END wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>
    <script src="//cdn.ckeditor.com/4.6.2/basic/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace('editor');
    </script>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js-->
    <script src="assets/js/app.min.js"></script>

    <!-- Loading buttons js -->
    <script src="assets/libs/ladda/spin.js"></script>
    <script src="assets/libs/ladda/ladda.js"></script>

    <!-- Buttons init js-->
    <script src="assets/js/pages/loading-btn.init.js"></script>


    <script type="text/javascript">
        // Add your JavaScript for the "Recommend Tests" button functionality
        document.querySelector('button[name="recommend_tests"]').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent form submission

            var ailment = document.querySelector('input[name="lab_pat_ailment"]').value;

            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_tests.php?ailment=' + encodeURIComponent(ailment), true);
            xhr.onload = function() {
                if (xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);

                    if (response.success) {
                        document.querySelector('textarea[name="lab_pat_tests"]').value = response.lab_tests;
                    } else {
                        alert('No recommendations available for this ailment');
                    }
                } else {
                    alert('Error fetching test recommendations');
                }
            };
            xhr.send();
        });
    </script>



</body>

</html>