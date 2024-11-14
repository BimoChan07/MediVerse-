<?php
session_start();
include('assets/inc/config.php');

if (isset($_POST['recommend_treatment'])) {
    $vit_pat_number = $_POST['vit_pat_number'];
    $vit_bodytemp = isset($_POST['vit_bodytemp']) ? floatval($_POST['vit_bodytemp']) : 0.0;
    $vit_heartpulse = isset($_POST['vit_heartpulse']) ? floatval($_POST['vit_heartpulse']) : 0.0;
    $vit_resprate = isset($_POST['vit_resprate']) ? floatval($_POST['vit_resprate']) : 0.0;
    $vit_bloodpress = isset($_POST['vit_bloodpress']) ? floatval($_POST['vit_bloodpress']) : 0.0;

    // Get suggested treatment based on normal ranges
    $recommended_treatment = checkVitalsAndRecommend($vit_bodytemp, $vit_heartpulse, $vit_resprate, $vit_bloodpress);

    // Determine if all vitals are normal
    $is_normal = strpos($recommended_treatment, 'No immediate action required') !== false;

    // Save the recommended treatment
    $sql = "UPDATE vitals SET remarks = ? WHERE vit_pat_number = ? ORDER BY vit_number DESC LIMIT 1";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ss', $recommended_treatment, $vit_pat_number);
    $stmt->execute();

    if ($stmt) {
        echo "<script>
                window.addEventListener('DOMContentLoaded', (event) => {
                    showAlert('$recommended_treatment', " . json_encode($is_normal) . ");
                });
              </script>";
    } else {
        echo "<script>
                window.addEventListener('DOMContentLoaded', (event) => {
                    showAlert('Unable to recommend treatment. Please try again.', false);
                });
              </script>";
    }
}

// Function to check vitals and recommend treatment based on deviation from normal ranges
function checkVitalsAndRecommend($vit_bodytemp, $vit_heartpulse, $vit_resprate, $vit_bloodpress)
{
    // Normal ranges and corresponding conditions
    $normal_ranges = [
        'bodytemp' => ['range' => [36.1, 37.2], 'unit' => '°C'],
        'heartpulse' => ['range' => [60, 100], 'unit' => 'bpm'],
        'resprate' => ['range' => [12, 20], 'unit' => 'bpm'],
        'bloodpress' => ['range' => [90, 120], 'unit' => 'mmHg']
    ];

    // Define the conditions and messages
    $conditions = [
        'bodytemp' => function ($value) use ($normal_ranges) {
            return checkRange($value, $normal_ranges['bodytemp'], 'Body temperature');
        },
        'heartpulse' => function ($value) use ($normal_ranges) {
            return checkRange($value, $normal_ranges['heartpulse'], 'Heart rate');
        },
        'resprate' => function ($value) use ($normal_ranges) {
            return checkRange($value, $normal_ranges['resprate'], 'Respiratory rate');
        },
        'bloodpress' => function ($value) use ($normal_ranges) {
            return checkRange($value, $normal_ranges['bloodpress'], 'Blood pressure');
        }
    ];

    // Manually call the condition functions with the correct values
    $treatment_suggestions = [
        $conditions['bodytemp']($vit_bodytemp),
        $conditions['heartpulse']($vit_heartpulse),
        $conditions['resprate']($vit_resprate),
        $conditions['bloodpress']($vit_bloodpress)
    ];

    // Filter out empty suggestions
    $treatment_suggestions = array_filter($treatment_suggestions);

    // If there are no treatment suggestions, return the "normal" message
    return !empty($treatment_suggestions) ? implode(" ", $treatment_suggestions) : "Vitals are within normal range (36.1°C - 37.2°C, 60 - 100 bpm, 12 - 20 bpm, 90/60 mmHg - 120/80 mmHg). No immediate action required.";
}

// Function to check a value against a normal range and provide a treatment suggestion
function checkRange($value, $range, $name)
{
    list($min, $max) = $range['range'];
    $unit = $range['unit'];

    if ($value < $min) {
        return "$name is below normal ($min - $max $unit). Consider warming measures and hydration.";
    } elseif ($value > $max) {
        return "$name is above normal ($min - $max $unit). Monitor for fever and consider antipyretics if fever persists.";
    }
    return null;
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php'); ?>

<body>

    <div id="wrapper">
        <?php include("assets/inc/nav.php"); ?>
        <?php include("assets/inc/sidebar.php"); ?>
        <?php
        $pat_number = $_GET['pat_number'];
        $ret = "SELECT  * FROM patients WHERE pat_number=?";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('s', $pat_number);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($row = $res->fetch_object()) {
        ?>
            <div class="content-page">
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="doc_dashboard.php">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Laboratory</a></li>
                                            <li class="breadcrumb-item active">Capture Vitals</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Capture <?php echo $row->pat_fname; ?> <?php echo $row->pat_lname; ?> Vitals</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row pb-5">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Fill all fields</h4>
                                        <!--Add Patient Form-->
                                        <form method="post">
                                            <div class="form-row">

                                                <div class="form-group col-md-6">
                                                    <label for="inputEmail4" class="col-form-label">Patient Name</label>
                                                    <input type="text" required readonly name="" value="<?php echo $row->pat_fname; ?> <?php echo $row->pat_lname; ?>" class="form-control" id="inputEmail4" placeholder="Patient's First Name">
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="inputPassword4" class="col-form-label">Patient Ailment</label>
                                                    <input required type="text" readonly name="" value="<?php echo $row->pat_ailment; ?>" class="form-control" id="inputPassword4" placeholder="Patient`s Last Name">
                                                </div>

                                            </div>

                                            <div class="form-row">

                                                <div class="form-group col-md-12">
                                                    <label for="inputEmail4" class="col-form-label">Patient Number</label>
                                                    <input type="text" required readonly name="vit_pat_number" value="<?php echo $row->pat_number; ?>" class="form-control" id="inputEmail4" placeholder="DD/MM/YYYY">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-row">
                                                <div class="form-group col-md-2" style="display:none">
                                                    <?php
                                                    $length = 5;
                                                    $vit_no =  substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);
                                                    ?>
                                                    <label for="inputZip" class="col-form-label">Vital Number</label>
                                                    <input type="text" name="vit_number" value="<?php echo $vit_no; ?>" class="form-control" id="inputZip">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-3">
                                                    <label for="inputEmail4" class="col-form-label">Patient Body Temperature °C</label>
                                                    <input type="text" required name="vit_bodytemp" class="form-control" id="inputEmail4" placeholder="°C">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label for="inputPassword4" class="col-form-label">Patient Heart Pulse/Beat BPM</label>
                                                    <input required type="text" name="vit_heartpulse" class="form-control" id="inputPassword4" placeholder="HeartBeats Per Minute ">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label for="inputPassword4" class="col-form-label">Patient Respiratory Rate bpm</label>
                                                    <input required type="text" name="vit_resprate" class="form-control" id="inputPassword4" placeholder="Breathes Per Minute">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label for="inputPassword4" class="col-form-label">Patient Blood Pressure mmHg</label>
                                                    <input required type="text" name="vit_bloodpress" class="form-control" id="inputPassword4" placeholder="mmHg">
                                                </div>

                                            </div>

                                            <button type="submit" name="add_patient_vitals" class="ladda-button btn btn-success" data-style="expand-right">Add Vitals</button>
                                            <button type="submit" name="recommend_treatment" class="ladda-button btn btn-success" data-style="expand-right">Recommend Treatment</button>

                                        </form>
                                        <!--End Patient Form-->
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

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->


    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>
    <script src="//cdn.ckeditor.com/4.6.2/basic/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace('editor')
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function showAlert(message, isNormal) {
            if (isNormal) {
                // Normal vitals case
                Swal.fire({
                    title: 'Vitals Normal',
                    text: message,
                    icon: 'success',
                    confirmButtonColor: '#28a745', // Green button
                    iconColor: '#28a745'
                });
            } else {
                // Abnormal vitals case
                Swal.fire({
                    title: 'Vitals Not Normal',
                    text: message,
                    icon: 'error',
                    confirmButtonColor: '#dc3545', // Red button
                    iconColor: '#dc3545'
                });
            }
        }
    </script>

</body>

</html>