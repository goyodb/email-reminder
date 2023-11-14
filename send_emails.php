<?php
require 'config.php';

date_default_timezone_set('America/Bogota');

$now = date('Y-m-d H:i:s');
$inTwentyMinutes = date('Y-m-d H:i:s', strtotime('+20 minutes'));

$stmt = $db->prepare("SELECT * FROM emails WHERE status = 'pending' AND next_send_time >= ? AND next_send_time < ?");
$stmt->execute([$now, $inTwentyMinutes]);

// Rest of your code...
while ($row = $stmt->fetch()) {
    echo "Processing email id: {$row['id']}\n";

    $mail->setFrom('legal@franconoriega.com', 'Legal Franco Noriega');
    $mail->isHTML(true);
    $subjectDate = date('D, d M Y');
    $mail->Subject = 'Legal Department from Franco Noriega - ' . $subjectDate;
    $mail->Body    = nl2br($row['template']);

    // Split the email string into an array
    $emails = explode(',', $row['email']);

    // Loop through the array and add each email address
    foreach ($emails as $email) {
        $mail->addAddress(trim($email));
    }

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

    // Clear all addresses for next loop
    $mail->clearAddresses();
}