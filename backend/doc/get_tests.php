<?php
include('assets/inc/config.php');

// Get the ailment from the GET request
$ailment = isset($_GET['ailment']) ? $_GET['ailment'] : '';

// Prepare the SQL query to fetch the lab tests based on the ailment
$query = "SELECT lab_test FROM ailments WHERE ailment_name = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $ailment);
$stmt->execute();
$stmt->bind_result($lab_tests);

// Fetch the result
if ($stmt->fetch()) {
    // Return the lab tests in a JSON format
    echo json_encode(['success' => true, 'lab_tests' => $lab_tests]);
} else {
    // Return an error message if no tests are found for the ailment
    echo json_encode(['success' => false, 'message' => 'No recommendations found for this ailment']);
}

$stmt->close();
