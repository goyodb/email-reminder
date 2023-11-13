<?php
require 'config.php';

date_default_timezone_set('America/Bogota');

$now = date('Y-m-d H:i:s');
$inOneHour = date('Y-m-d H:i:s', strtotime('+1 hour'));

$stmt = $db->prepare("SELECT * FROM emails WHERE status = 'pending' AND next_send_time >= ? AND next_send_time < ?");
$stmt->execute([$now, $inOneHour]);

while ($row = $stmt->fetch()) {
    echo "Processing email id: {$row['id']}\n";

    $mail->setFrom('legal@franconoriega.com', 'Legal Franco Noriega');
    $mail->addAddress($row['email']);
    $mail->isHTML(true);
    $mail->Subject = 'Legal Department from Franco Noriega';
    $mail->Body    = nl2br($row['template']);

    if ($mail->send()) {
        echo "Email sent to: {$row['email']}\n";

        // Calculate the next send time
        $next_send_time = date('Y-m-d H:i:s', strtotime("{$row['next_send_time']} +{$row['frequency']} days"));
        echo "Next send time: $next_send_time\n";

        // Update the next_send_time in the database
        $update_stmt = $db->prepare("UPDATE emails SET next_send_time = ? WHERE id = ?");
        $update_stmt->execute([$next_send_time, $row['id']]);
    } else {
        echo "Failed to send email to: {$row['email']}\n";
    }
}