<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $frequency = $_POST['frequency'];
    $template = $_POST['template'];
    $next_send_time = $_POST['next_send_time'];

    $stmt = $db->prepare("INSERT INTO emails (email, frequency, template, next_send_time, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->execute([$email, $frequency, $template, $next_send_time]);

    // Redirect to index.php
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta name="robots" content="noindex, nofollow">
    <!-- Agregar Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center mt-3">Formulario</h1>
                <form method="post" class="m-4">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="frequency">Frecuencia (cada cuantos dias):</label>
                        <input type="number" class="form-control" id="frequency" name="frequency">
                    </div>
                    <div class="form-group">
                        <label for="next_send_time">Dia del Proximo Envio:</label>
                        <input type="datetime-local" class="form-control" id="next_send_time" name="next_send_time">
                    </div>
                    <div class="form-group">
                        <label for="template">Contenido del Email:</label>
                        <textarea class="form-control" id="template" name="template" rows="10"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-11">
                <?php
                // Consulta para obtener todos los email reminders
                $stmt = $db->query("SELECT * FROM emails WHERE status = 'pending' ORDER BY next_send_time ASC");

                // Mostrar los resultados en una tabla
                if ($stmt->rowCount() > 0) {
                    echo '<h2>Pending Email Reminders</h2>';
                    echo '<table class="table">';
                    echo '<thead><tr><th>Email</th><th>Frequency</th><th>Template</th><th>Status</th><th>Actions</th></tr></thead>';
                    echo '<tbody>';
                    while ($row = $stmt->fetch()) {
                        echo '<tr>';
                        echo '<td>' . $row['email'] . '</td>';
                        echo '<td>' . $row['frequency'] . ' days</td>';
                        echo '<td>' . nl2br($row['template']) . '</td>';
                        echo '<td>' . $row['status'] . '</td>';
                        echo '<td><button class="btn btn-success" onclick="markAsCompleted(' . $row['id'] . ')">Mark as Completed</button></td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo 'No email reminders found.';
                }
                ?>



                <?php
                // Consulta para obtener todos los email reminders completados
                $stmt = $db->query("SELECT * FROM emails WHERE status = 'completed' ORDER BY next_send_time ASC");

                // Mostrar los resultados en una tabla
                if ($stmt->rowCount() > 0) {
                    echo '<h4>Completed Email Reminders</h4>';
                    echo '<table class="table">';
                    echo '<thead><tr><th>Email</th><th>Frequency</th><th>Template</th><th>Status</th></tr></thead>';
                    echo '<tbody>';
                    while ($row = $stmt->fetch()) {
                        echo '<tr>';
                        echo '<td>' . $row['email'] . '</td>';
                        echo '<td>' . $row['frequency'] . ' days</td>';
                        echo '<td>' . nl2br($row['template']) . '</td>';
                        echo '<td>' . $row['status'] . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<h2>No completed email reminders found.</h2>';
                }
                ?>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    function markAsCompleted(id) {
                        $.ajax({
                            url: 'mark_as_completed.php',
                            type: 'POST',
                            data: {
                                id: id
                            },
                            success: function(response) {
                                alert('Email reminder marked as completed.');
                                location.reload();
                            },
                            error: function() {
                                alert('Error marking email reminder as completed.');
                            }
                        });
                    }
                </script>
            </div>
        </div>
    </div>

    <!-- Agregar Bootstrap JS y sus dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>