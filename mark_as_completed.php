<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $stmt = $db->prepare("UPDATE emails SET status = 'completed' WHERE id = ?");
    $stmt->execute([$id]);
}

?>