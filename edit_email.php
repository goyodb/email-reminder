<?php
require 'config.php';

$id = $_POST['id'];
$email = $_POST['email'];
$frequency = $_POST['frequency'];
$template = $_POST['template'];

$stmt = $db->prepare("UPDATE emails SET email = ?, frequency = ?, template = ? WHERE id = ?");
$stmt->execute([$email, $frequency, $template, $id]);

echo 'Success';