<?php
session_start();
include('assets/inc/config.php');

// Function to calculate cosine similarity
function calculate_cosine_similarity($symptom_vector, $treatment_vector)
{
    $dot_product = 0;
    $symptom_norm = 0;
    $treatment_norm = 0;

    foreach ($symptom_vector as $key => $value) {
        $dot_product += $value * $treatment_vector[$key];
        $symptom_norm += $value * $value;
        $treatment_norm += $treatment_vector[$key] * $treatment_vector[$key];
    }

    if ($symptom_norm == 0 || $treatment_norm == 0) {
        return 0; // Avoid division by zero
    }

    return $dot_product / (sqrt($symptom_norm) * sqrt($treatment_norm));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_symptoms = $_POST['symptoms']; // JSON-encoded array of symptom IDs
    $symptom_ids = json_decode($patient_symptoms, true);

    if (!is_array($symptom_ids) || empty($symptom_ids)) {
        echo json_encode(['success' => false, 'message' => 'Invalid or empty symptoms provided.']);
        exit();
    }

    // Fetch treatments and symptoms via join
    $query = "
       SELECT 
    t.id AS treatment_id,
    t.treatment_name,
    s.id AS symptom_id,
    s.name AS symptom_name
FROM 
    treatment t
INNER JOIN 
    symptom_treatment st ON t.id = st.treatment_id
INNER JOIN 
    symptoms s ON st.symptom_id = s.id
WHERE 
    s.id IN ('" . implode("','", $symptom_ids) . "');

    ";

    $result = $mysqli->query($query);

    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $mysqli->error]);
        exit();
    }

    $treatments = [];
    $treatment_symptom_map = [];

    while ($row = $result->fetch_assoc()) {
        $treatments[$row['treatment_id']] = $row['treatment_name'];
        $treatment_symptom_map[$row['treatment_id']][] = $row['symptom_id'];
    }

    // Build vectors for cosine similarity
$scores = [];
foreach ($treatment_symptom_map as $treatment_id => $mapped_symptoms) {
    $symptom_vector = [];
    $treatment_vector = [];

    foreach ($symptom_ids as $symptom_id) {
        $symptom_vector[] = 1; // Patient's symptom vector
        $treatment_vector[] = in_array($symptom_id, $mapped_symptoms) ? 1 : 0;
    }

    if (array_sum($treatment_vector) > 0) { // Ensure there's a match
        $scores[$treatment_id] = calculate_cosine_similarity($symptom_vector, $treatment_vector);
    } else {
        $scores[$treatment_id] = 0; // No similarity
    }
}

// Find the treatment with the highest score
$max_score_treatment_id = array_keys($scores, max($scores))[0]; // Treatment ID with the highest score

    // Sort treatments by score in descending order
    arsort($scores);
    // Fetch top recommendations
    $recommended_treatments = [];
    foreach ($scores as $treatment_id => $score) {
        if ($score > 0) {
            $recommended_treatments[] = $treatments[$treatment_id];
        }
    }
foreach ($scores as $treatment_id => &$score) {
    $score = ceil($score * 100); // Scale and round up
}
$recommended_treatments = [];
$threshold = 0.5; // Only consider treatments with similarity above 0.5

foreach ($scores as $treatment_id => $score) {
    if ($score > $threshold) {
        $recommended_treatments[] = $treatment_id;
    }
}

    // Respond with recommended treatments or a message if none are found
   if (!empty($recommended_treatments)) {
    // Convert IDs to a comma-separated string for the SQL query
    $ids_string = implode(',', $recommended_treatments);

    // Fetch treatment names for the recommended treatment IDs
    $query = "SELECT treatment_name FROM treatment WHERE id IN ($ids_string)";
    $result = $mysqli->query($query);

    if ($result && $result->num_rows > 0) {
        $treatment_names = [];
        while ($row = $result->fetch_assoc()) {
            $treatment_names[] = $row['treatment_name'];
        }

        // Respond with treatment names
        echo json_encode(['success' => true, 'treatments' => $treatment_names]);
    } else {
        // No treatments found in the database
        echo json_encode(['success' => false, 'message' => 'No treatments found for the given IDs.']);
    }
} else {
    // No treatments met the threshold
    echo json_encode(['success' => false, 'message' => 'No suitable treatments found.']);
}


} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>