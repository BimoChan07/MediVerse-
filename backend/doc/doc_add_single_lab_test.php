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
            // Assuming you've fetched $pat_symptoms from the database
            $pat_symptoms_json = $row->pat_symptoms; // Assuming $row contains the fetched data

            // Decode the JSON string back into an array
            $pat_symptoms = json_decode($pat_symptoms_json, true);
echo $pat_symptoms_json;
            // Check if symptoms exist and display them


        ?>
        <script>
        // Pass PHP data to JavaScript
        const patientData = <?php echo $data_encode; ?>;

        // Log the data in the browser console
        console.log("Patient Data:", patientData);
        </script>
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
                                            <div class="form-group col-md-4">
                                                <label for="inputEmail4" class="col-form-label">Patient Name</label>
                                                <input type="text" required="required" readonly name="lab_pat_name"
                                                    value="<?php echo $row->pat_fname; ?> <?php echo $row->pat_lname; ?>"
                                                    class="form-control" id="inputEmail4"
                                                    placeholder="Patient's First Name">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="inputEmail4" class="col-form-label">Patient Number</label>
                                                <input type="text" required="required" readonly name="lab_pat_number"
                                                    value="<?php echo $row->pat_number; ?>" class="form-control"
                                                    id="inputEmail4" placeholder="Patient Number">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="inputPassword4" class="col-form-label">Patient
                                                    Ailment</label>
                                                <input required="required" type="text" readonly name="lab_pat_ailment"
                                                    value="<?php echo $row->pat_ailment; ?>" class="form-control"
                                                    id="inputPassword4" placeholder="Patient's Ailment">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="inputPassword4" class="col-form-label">Patient's
                                                    Symptoms</label>
                                                <p class="mb-0" id="pat_symptoms" hidden>
                                                    <?php
                                                        if (!empty($pat_symptoms)) {
                                                            echo implode(', ', $pat_symptoms); // Join symptoms with commas
                                                        } else {
                                                            echo "No symptoms recorded for this patient.";
                                                        }
                                                        ?>
                                                </p>
                                                <p class="mb-0" id="pat">
                                                    <?php
                                                        if (!empty($pat_symptoms)) {
                                                            // Convert the array of patient symptom IDs to a comma-separated list
                                                            $symptom_ids = implode(',', array_map('intval', $pat_symptoms)); // Ensures IDs are integers

                                                            // Query to fetch the symptom names from the symptoms table
                                                            $query = "SELECT id, name FROM symptoms WHERE id IN ($symptom_ids)";
                                                            $result = $mysqli->query($query);

                                                            if ($result) {
                                                                $symptom_names = []; // Array to store symptom names

                                                                // Fetch the symptom names from the result
                                                                while ($row = $result->fetch_assoc()) {
                                                                    $symptom_names[] = $row['name'];
                                                                }

                                                                if (!empty($symptom_names)) {
                                                                    // Render the symptom names, joined by commas
                                                                    echo implode(', ', $symptom_names);
                                                                } else {
                                                                    echo "No matching symptoms found in the database.";
                                                                }
                                                            } else {
                                                                // Handle query error
                                                                echo "Error fetching symptoms: " . $mysqli->error;
                                                            }
                                                        } else {
                                                            echo "No symptoms recorded for this patient.";
                                                        }
?>
                                                </p>
                                            </div>

                                        </div>


                                        <div class="form-row">
                                            <div class="form-group col-md-2" style="display:none">
                                                <?php
                                                    $length = 5;
                                                    $pres_no =  substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);
                                                    ?>
                                                <label for="inputZip" class="col-form-label">Lab Test Number</label>
                                                <input type="text" name="lab_number" value="<?php echo $pres_no; ?>"
                                                    class="form-control" id="inputZip">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputAddress" class="col-form-label">Laboratory Tests</label>
                                            <textarea required="required" type="text" class="form-control"
                                                name="lab_pat_tests" rows="5"></textarea>
                                        </div>

                                        <button type="button" name="recommend_tests" class="btn btn-secondary">
                                            Recommend Tests
                                        </button>
                                        <div id="recommended_test_results"></div>
                                        <div class="form-group">
                                            <label for="inputAddress" class="col-form-label">Reported By</label>
                                            <textarea required="required" type="text" class="form-control"
                                                name="reported_by" id="" rows="1"></textarea>
                                        </div>

                                        <button type="submit" name="add_patient_lab_test"
                                            class="ladda-button btn btn-success" data-style="expand-right">Add
                                            Laboratory Test</button>

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


    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js-->
    <script src="assets/js/app.min.js"></script>

    <!-- Loading buttons js -->
    <script src="assets/libs/ladda/spin.js"></script>
    <script src="assets/libs/ladda/ladda.js"></script>

    <!-- Buttons init js-->
    <script src="assets/js/pages/loading-btn.init.js"></script>
    <script>
    document.querySelector('button[name="recommend_tests"]').addEventListener('click', function(event) {
        event.preventDefault();

        var symptomText = document.getElementById('pat_symptoms').textContent.trim();

        if (symptomText === "No symptoms recorded for this patient.") {
            alert('No symptoms available for this patient.');
            return;
        }

        var symptoms = symptomText.split(',').map(symptom => symptom.trim());
        if (symptoms.length === 0) {
            alert('No symptoms available for this patient.');
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'get_tests.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            console.log('Server response:', xhr.responseText);

            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        const recommendedTestResultDiv = document.getElementById(
                            'recommended_test_results');

                        // Clear any previous content
                        recommendedTestResultDiv.innerHTML = '';

                        // Create a list element to display the treatments
                        const ul = document.createElement('ul');

                        // Add each treatment as a list item
                        response.treatments.forEach(treatment => {
                            const li = document.createElement('li');
                            li.textContent = treatment;
                            ul.appendChild(li);
                        });

                        // Append the list to the div
                        recommendedTestResultDiv.appendChild(ul);
                    } else {
                        alert(response.message || 'No recommendations available.');
                    }
                } catch (e) {
                    // alert('Error: Invalid response from server');
                    console.error('Parsing error:', e);
                }
            } else {
                alert('Failed to fetch recommendations.');
            }
        };

        xhr.onerror = function() {
            alert('Request failed. Check your connection or server.');
        };

        xhr.send('symptoms=' + encodeURIComponent(JSON.stringify(symptoms)));
    });
    </script>
</body>

</html>