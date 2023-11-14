<?php
require 'config.php';

$id = $_GET['id'];

$stmt = $db->prepare("SELECT * FROM emails WHERE id = ?");
$stmt->execute([$id]);

$row = $stmt->fetch();

echo json_encode($row);