<?php
header("Content-Type: application/json");
require "db.php";

$data = json_decode(file_get_contents("php://input"));

if (isset($data->match_id) && isset($data->team1_score) && isset($data->team2_score)) {
    $match_id = $data->match_id;
    $team1_score = $data->team1_score;
    $team2_score = $data->team2_score;

    $sql = "INSERT INTO scores (match_id, team1_score, team2_score) 
            VALUES ('$match_id', '$team1_score', '$team2_score')
            ON DUPLICATE KEY UPDATE team1_score = VALUES(team1_score), team2_score = VALUES(team2_score)";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "Risultato aggiornato con successo!"]);
    } else {
        echo json_encode(["error" => "Errore nell'aggiornamento: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "Dati mancanti"]);
}

$conn->close();
?>
