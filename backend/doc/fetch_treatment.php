<?php
include('assets/inc/config.php');

if (isset($_GET['ailment'])) {
    $ailment = $_GET['ailment'];

    // Query to fetch treatments based on ailment
    $query = "
        SELECT DISTINCT t.treatment_name 
        FROM treatment t
        INNER JOIN symptom_treatment st ON t.id = st.treatment_id
        INNER JOIN symptoms s ON st.symptom_id = s.id
        INNER JOIN ailments a ON s.ailment_id = a.id
        WHERE a.ailment_name = ?
    ";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $ailment);
    $stmt->execute();
    $result = $stmt->get_result();

    $treatments = [];
    while ($row = $result->fetch_object()) {
        $treatments[] = $row->treatment_name;
    }

    // Return treatments as JSON
    if (!empty($treatments)) {
        echo json_encode(['success' => true, 'treatments' => $treatments]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No treatments found for the given ailment.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Ailment not specified.']);
}
?>
