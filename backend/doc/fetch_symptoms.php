<?php
// Include database configuration
include('assets/inc/config.php');

// Check if the request is POST and contains the 'ailment' parameter
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ailment'])) {
    // Sanitize input
    $ailment = trim($_POST['ailment']);

    // Prepare the query to fetch symptoms based on ailment
    $query = "
        SELECT s.id, s.name 
        FROM symptoms s 
        INNER JOIN ailments a ON s.ailment_id = a.id 
        WHERE a.ailment_name = ?
    ";

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        // Return an error if query preparation fails
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database query preparation failed.']);
        exit;
    }

    // Bind parameters and execute the statement
    $stmt->bind_param('s', $ailment);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch symptoms
    $symptoms = [];
    while ($row = $result->fetch_assoc()) {
        $symptoms[] = $row;
    }

    // Check if symptoms were found
    if (empty($symptoms)) {
        header('Content-Type: application/json');
        echo json_encode(['message' => 'No symptoms found for the selected ailment.']);
        exit;
    }

    // Send symptoms as JSON
    header('Content-Type: application/json');
    echo json_encode($symptoms);
    exit;
} else {
    // Handle invalid requests
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request or missing ailment parameter.']);
    exit;
}
