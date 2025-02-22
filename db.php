<?php
$host = "localhost";
$user = "root"; // Cambia se hai una password per MySQL
$password = "";
$dbname = "volleyball_tournament";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connessione fallita: " . $conn->connect_error]));
}
?>
