<?php
// Check if form is submitted
if (isset($_POST['add_patient_vitals'])) {
    // Retrieve submitted vitals
    $bodyTemp = $_POST['vit_bodytemp'];
    $heartPulse = $_POST['vit_heartpulse'];
    $respRate = $_POST['vit_resprate'];
    $bloodPress = $_POST['vit_bloodpress'];

    // Insert vitals into the database
    $insertVitals = "INSERT INTO vitals (pat_number, body_temp, heart_pulse, resp_rate, blood_pressure) VALUES (?, ?, ?, ?, ?)";
    $stmtInsert = $mysqli->prepare($insertVitals);
    $stmtInsert->bind_param('sssss', $pat_number, $bodyTemp, $heartPulse, $respRate, $bloodPress);
    $stmtInsert->execute();

    // Recommend treatment based on vitals
    recommendTreatment($bodyTemp, $heartPulse, $respRate, $bloodPress);
}

function cosineSimilarity($vectorA, $vectorB)
{
    $dotProduct = 0;
    $normA = 0;
    $normB = 0;

    for ($i = 0; $i < count($vectorA); $i++) {
        $dotProduct += $vectorA[$i] * $vectorB[$i];
        $normA += $vectorA[$i] ** 2;
        $normB += $vectorB[$i] ** 2;
    }

    return $dotProduct / (sqrt($normA) * sqrt($normB));
}

function recommendTreatment($bodyTemp, $heartPulse, $respRate, $bloodPress)
{
    global $mysqli;

    // Get vitals of other patients
    $query = "SELECT pat_number, body_temp, heart_pulse, resp_rate, blood_pressure, treatment FROM vitals";
    $result = $mysqli->query($query);

    $inputVitals = [$bodyTemp, $heartPulse, $respRate, $bloodPress];
    $similarities = [];

    // Calculate cosine similarity with each patient
    while ($row = $result->fetch_assoc()) {
        $existingVitals = [
            $row['body_temp'],
            $row['heart_pulse'],
            $row['resp_rate'],
            $row['blood_pressure']
        ];

        $similarity = cosineSimilarity($inputVitals, $existingVitals);
        $similarities[] = ['pat_number' => $row['pat_number'], 'similarity' => $similarity, 'treatment' => $row['treatment']];
    }

    // Sort by similarity in descending order
    usort($similarities, function ($a, $b) {
        return $b['similarity'] <=> $a['similarity'];
    });

    // Recommend treatments from the top similar patients
    echo "<h4>Recommended Treatments:</h4><ul>";
    $recommendations = 0;
    foreach ($similarities as $similar) {
        if ($recommendations >= 3) break; // Limit to top 3 recommendations
        echo "<li>Patient ID: " . htmlspecialchars($similar['pat_number']) . " - Treatment: " . htmlspecialchars($similar['treatment']) . " (Similarity: " . round($similar['similarity'], 2) . ")</li>";
        $recommendations++;
    }
    echo "</ul>";
}
