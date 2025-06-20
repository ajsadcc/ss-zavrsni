<?php
$conn = new mysqli("localhost", "root", "", "ss");
if ($conn->connect_error) {
    die("Greška pri povezivanju: " . $conn->connect_error);
}
?>
