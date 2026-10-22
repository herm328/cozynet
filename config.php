
<?php

$conn = new mysqli("localhost", "root", "", "ice_cream_delights");

if ($conn->connect_error) {
    die( $conn->connect_error);
}
?>
