<?php
header("Content-Type: application/json");
require "db.php";

$data = json_decode(file_get_contents("php://input"));

if (isset($data->team1_id) && isset($data->team2_id) && isset($data->match_date)) {
    $sql = "INSERT INTO matches (team1_id, team2_id, match_date) VALUES ('$data->team1_id', '$data->team2_id', '$data->match_date')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "Partita aggiunta con successo!"]);
    } else {
        echo json_encode(["error" => "Errore: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "Dati mancanti"]);
}

$conn->close();
?>
