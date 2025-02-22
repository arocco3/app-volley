<?php
header("Content-Type: application/json");
require "db.php";

$sql = "SELECT m.id, t1.name AS team1, t2.name AS team2, s.team1_score, s.team2_score, m.match_date
        FROM matches m
        JOIN teams t1 ON m.team1_id = t1.id
        JOIN teams t2 ON m.team2_id = t2.id
        LEFT JOIN scores s ON m.id = s.match_id
        WHERE m.played = 1";
$result = $conn->query($sql);

$matches = [];
while ($row = $result->fetch_assoc()) {
    $matches[] = $row;
}

echo json_encode($matches);
$conn->close();
?>
