<?php
session_start();

include('assets/inc/config.php');

if (isset($_POST['add_patient'])) {
    // Capture other patient data
    $pat_fname = $_POST['pat_fname'];
    $pat_lname = $_POST['pat_lname'];
    $pat_number = $_POST['pat_number'];
    $pat_phone = $_POST['pat_phone'];
    $pat_type = $_POST['pat_type'];
    $pat_addr = $_POST['pat_addr'];
    $pat_age = $_POST['pat_age'];
    $pat_dob = $_POST['pat_dob'];
    $pat_ailment = $_POST['pat_ailment'];

    // Capture the selected symptoms as an array (from multiple select)
    $pat_symptoms = isset($_POST['pat_symptoms']) ? $_POST['pat_symptoms'] : [];

    // Convert the symptoms array into a JSON string
    $pat_symptoms_json = json_encode($pat_symptoms);

    // SQL to insert captured values including the symptoms
    $query = "INSERT INTO patients (pat_fname, pat_lname, pat_ailment, pat_age, pat_dob, pat_number, pat_phone, pat_type, pat_addr, pat_symptoms) VALUES(?,?,?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssssssssss', $pat_fname, $pat_lname, $pat_ailment, $pat_age, $pat_dob, $pat_number, $pat_phone, $pat_type, $pat_addr, $pat_symptoms_json);

    // Execute the statement and check if it's successful
    if ($stmt->execute()) {
        $success = "Patient Details Added Successfully!";
    } else {
        $err = "Error: Please try again later.";
    }
}
?>


<!--End Server Side-->
<!--End Patient Registration-->
<!DOCTYPE html>
<html lang="en">
<style>
.select2-selection__choice {
    background-color: #024a59 !important;
    color: #ffffff !important;
}
</style>
<!--Head-->
<?php include('assets/inc/head.php'); ?>


<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include("assets/inc/nav.php"); ?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("assets/inc/sidebar.php"); ?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

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
                                        <li class="breadcrumb-item"><a href="doc_dashboard.php">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                        <li class="breadcrumb-item active">Add Patient</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Add Patient Details</h4>
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
                                    <!--Add Patient Form-->
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputEmail4" class="col-form-label">First Name</label>
                                                <input type="text" required="required" name="pat_fname"
                                                    class="form-control" id="inputEmail4"
                                                    placeholder="Patient's First Name">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputPassword4" class="col-form-label">Last Name</label>
                                                <input required="required" type="text" name="pat_lname"
                                                    class="form-control" id="inputPassword4"
                                                    placeholder="Patient`s Last Name">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="dob" class="col-form-label">Date Of Birth</label>
                                                <input type="date" required="required" name="pat_dob"
                                                    class="form-control" id="dob" placeholder="DD/MM/YYYY"
                                                    onchange="calculateAge()">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="age" class="col-form-label">Age</label>
                                                <input type="text" required="required" name="pat_age"
                                                    class="form-control" id="age" placeholder="Patient`s Age" readonly>
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <label for="inputAddress" class="col-form-label">Address</label>
                                            <input required="required" type="text" class="form-control" name="pat_addr"
                                                id="inputAddress" placeholder="Patient's Addresss">
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="mobileNumber" class="col-form-label">Mobile Number</label>
                                                <input required="required" type="text" name="pat_phone"
                                                    class="form-control" id="mobileNumber">
                                            </div>
                                            <!-- <div class="form-group col-md-4">
                                                <label for="inputCity" class="col-form-label">Patient Ailment</label>
                                                <input required="required" type="text" name="pat_ailment" class="form-control" id="inputCity">
                                            </div> -->
                                            <div class="form-group col-md-4">
                                                <label for="patientAilment" class="col-form-label">Patient
                                                    Ailment</label>
                                                <select required="required" name="pat_ailment" class="form-control"
                                                    id="patientAilment">
                                                    <option value="">Select Ailment</option>
                                                    <?php
                                                    // Fetch ailments from the database
                                                    include('assets/inc/config.php');

                                                    // Query to get all ailment names
                                                    $query = "SELECT ailment_name FROM ailments";
                                                    $result = $mysqli->query($query);

                                                    // Loop through the results and create an option for each ailment
                                                    while ($row = $result->fetch_object()) {
                                                        echo "<option value='" . $row->ailment_name . "'>" . $row->ailment_name . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="inputSymptoms" class="col-form-label">Symptoms</label>
                                                <select name="pat_symptoms[]" class="form-control" id="inputSymptoms"
                                                    multiple>
                                                    <!-- Options will be dynamically loaded -->
                                                </select>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="inputState" class="col-form-label">Patient's Type</label>
                                                <select id="inputState" required="required" name="pat_type"
                                                    class="form-control">
                                                    <option>Choose</option>
                                                    <option>InPatient</option>
                                                    <option>OutPatient</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2" style="display:none">
                                                <?php
                                                $length = 5;
                                                $patient_number =  substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);
                                                ?>
                                                <label for="inputZip" class="col-form-label">Patient Number</label>
                                                <input type="text" name="pat_number"
                                                    value="<?php echo $patient_number; ?>" class="form-control"
                                                    id="inputZip">
                                            </div>
                                        </div>

                                        <button type="submit" name="add_patient" class="ladda-button btn btn-primary"
                                            data-style="expand-right">Add Patient</button>

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

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->


    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>


    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js-->
    <script src="assets/js/app.min.js"></script>

    <!-- Loading buttons js -->
    <script src="assets/libs/ladda/spin.js"></script>
    <script src="assets/libs/ladda/ladda.js"></script>

    <!-- Buttons init js-->
    <script src="assets/js/pages/loading-btn.init.js"></script>

    <!-- Select2 CSS and JS -->
    <!-- Place this just before the closing </body> tag -->
    <!-- jQuery (required for Select2) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Select2 CSS and JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
    function calculateAge() {
        const dobInput = document.getElementById('dob');
        const ageInput = document.getElementById('age');

        // Parse the input date
        const dob = new Date(dobInput.value);
        const today = new Date();

        // Validation: Ensure a valid date is entered and it is not in the future
        if (!dobInput.value || dob > today) {
            ageInput.value = '';
            alert("Please enter a valid date of birth.");
            return;
        }

        // Calculate age
        let age = today.getFullYear() - dob.getFullYear();
        const monthDiff = today.getMonth() - dob.getMonth();
        const dayDiff = today.getDate() - dob.getDate();

        // Adjust the age if the birthday has not been reached this year
        if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
            age--;
        }

        // Set the calculated age in the input field
        ageInput.value = age;
    }
    </script>
    </script>
    <script>
    $(document).ready(function() {
        // Confirm that Select2 is loaded by checking if it exists
        if (typeof $.fn.select2 !== 'undefined') {
            console.log("Select2 loaded successfully.");

            // Initialize Select2 for the symptoms dropdown
            $('#inputSymptoms').select2({
                placeholder: "Select Symptoms"
            });
        } else {
            console.error("Select2 is not loaded. Check your script URLs.");
        }

        // Fetch symptoms based on selected ailment
        $('#patientAilment').change(function() {
            var ailment = $(this).val();

            if (ailment) {
                $.ajax({
                    url: 'fetch_symptoms.php',
                    method: 'POST',
                    data: {
                        ailment: ailment
                    },
                    dataType: 'json',
                    success: function(data) {
                        // Clear existing options
                        $('#inputSymptoms').empty();

                        // Populate symptoms dropdown with new options
                        $.each(data, function(key, value) {
                            $('#inputSymptoms').append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching symptoms:', error);
                    }
                });
            } else {
                // Clear symptoms if no ailment is selected
                $('#inputSymptoms').empty();
            }
        });
    });
    </script>
</body>


</body>

</html>