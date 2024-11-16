<?php
include('assets/inc/config.php');

// Convert symptoms into a binary vector (1 if symptom exists, 0 otherwise)
$symptom_vector = [];
$stmt = $mysqli->prepare("SELECT id FROM symptoms");  // Get all symptoms from the database
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $symptom_vector[$row['id']] = in_array($row['id'], $symptoms) ? 1 : 0;
}

// Get all patients and their symptoms
$patient_vectors = [];
$stmt = $mysqli->prepare("SELECT pat_id, pat_symptoms FROM patients");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $patient_symptoms = json_decode($row['pat_symptoms'], true);
    $patient_vector = [];
    foreach ($symptom_vector as $id => $value) {
        $patient_vector[$id] = in_array($id, $patient_symptoms) ? 1 : 0;
    }
    $patient_vectors[$row['pat_id']] = $patient_vector;
}

// Helper function to calculate cosine similarity between two vectors
function cosine_similarity($vec1, $vec2) {
    $dotProduct = 0;
    $magnitude1 = 0;
    $magnitude2 = 0;

    foreach ($vec1 as $key => $value) {
        if (isset($vec2[$key])) {
            $dotProduct += $vec1[$key] * $vec2[$key];
        }
    }

    foreach ($vec1 as $value) {
        $magnitude1 += $value * $value;
    }

    foreach ($vec2 as $value) {
        $magnitude2 += $value * $value;
    }

    $magnitude1 = sqrt($magnitude1);
    $magnitude2 = sqrt($magnitude2);

    if ($magnitude1 * $magnitude2 == 0) {
        return 0; // If either magnitude is zero, similarity is zero
    }

    return $dotProduct / ($magnitude1 * $magnitude2);
}

// Find most similar patients
$similarities = [];
foreach ($patient_vectors as $pat_id => $vector) {
    $similarity = cosine_similarity($symptom_vector, $vector);
    if ($similarity > 0) {
        $similarities[$pat_id] = $similarity;
    }
}

// Sort patients by similarity (high to low)
arsort($similarities);

// Get the treatments of the most similar patients
$recommended_treatments = [];
$count = 0;
foreach ($similarities as $pat_id => $similarity) {
    if ($count >= 5) break;  // Limit to top 5 similar patients

    $stmt = $mysqli->prepare("SELECT treatment_id FROM symptom_treatment WHERE pat_id=?");
    $stmt->bind_param('s', $pat_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $recommended_treatments[] = $row['treatment_id'];
    }

    $count++;
}

// Return recommended treatments as a response
if (count($recommended_treatments) > 0) {
    echo json_encode(['success' => true, 'lab_tests' => implode(', ', array_unique($recommended_treatments))]);
} else {
    echo json_encode(['success' => false, 'message' => 'No treatments found for these symptoms']);
}
?>