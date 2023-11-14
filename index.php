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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center mt-3">Nuevo Email Reminder</h1>
                <form method="post" class="m-4">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" class="form-control" id="email" name="email">
                        <!-- Help separar por comas varios emails -->
                        <small id="emailHelp" class="form-text text-muted">Separar por comas varios emails.</small>
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

                <div id="editForm" style="display: none;">
                    <div class="card">
                        <div class="card-body">
                            <h2>Editar Email Reminder</h2>
                            <form>
                                <input type="hidden" id="editId">
                                <label for="editEmail">Email:</label><br>
                                <input type="text" id="editEmail" class="form-control"><br>
                                <label for="editFrequency">Frequency:</label><br>
                                <input type="text" id="editFrequency" class="form-control"><br>
                                <label for="editTemplate">Template:</label><br>
                                <textarea id="editTemplate" class="form-control" rows="10"></textarea><br>
                                <button type="button" onclick="submitEdit()" class="btn btn-success">Edit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-body">
                        <?php
                        // Consulta para obtener todos los email reminders
                        $stmt = $db->query("SELECT * FROM emails WHERE status = 'pending' ORDER BY next_send_time ASC");

                        // Mostrar los resultados en una tabla
                        if ($stmt->rowCount() > 0) {
                            echo '<div class="card-header"><h2>Pending Email Reminders</h2></div>';
                            echo '<table id="myTable" class="table table-responsive">';
                            echo '<thead><tr><th>Email</th><th>Frequency</th><th>Template</th><th>Status</th><th>Actions</th></tr></thead>';
                            echo '<tbody>';
                            while ($row = $stmt->fetch()) {
                                echo '<tr>';
                                echo '<td>' . $row['email'] . '</td>';
                                echo '<td>' . $row['frequency'] . ' days</td>';
                                echo '<td>' . nl2br($row['template']) . '</td>';
                                echo '<td>' . $row['status'] . '</td>';
                                echo '
                            <td>
                                <div class="btn-group">
                                <button class="btn btn-success btn-sm" onclick="markAsCompleted(' . $row['id'] . ')">Completed</button>
                                <button class="btn btn-primary btn-sm" onclick="editEmail(' . $row['id'] . ')">Edit</button>
                                </div>
                            </td>
                            ';
                                echo '</tr>';
                            }
                            echo '</tbody></table>';
                        } else {
                            echo 'No email reminders found.';
                        }
                        ?>
                    </div>
                </div>


                <div class="card mt-4">
                    <div class="card-body">
                        <?php
                        // Consulta para obtener todos los email reminders completados
                        $stmt = $db->query("SELECT * FROM emails WHERE status = 'completed' ORDER BY next_send_time ASC");

                        // Mostrar los resultados en una tabla
                        if ($stmt->rowCount() > 0) {
                            echo '<div class="card-header"><h4>Completed Email Reminders</h4></div>';
                            echo '<table class="table table-responsive">';
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
                    </div>
                </div>
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

                    function editEmail(id) {
                        // Fetch the data for this id
                        $.get('get_email.php', {
                            id: id
                        }, function(data) {
                            // Parse the JSON data
                            var parsedData = JSON.parse(data);

                            // Fill the form with the fetched data
                            $('#editId').val(parsedData.id);
                            $('#editEmail').val(parsedData.email);
                            $('#editFrequency').val(parsedData.frequency);
                            $('#editTemplate').val(parsedData.template);

                            // Show the form
                            $('#editForm').show();
                        });
                    }

                    function submitEdit() {
                        // Get the data from the form
                        var id = $('#editId').val();
                        var email = $('#editEmail').val();
                        var frequency = $('#editFrequency').val();
                        var template = $('#editTemplate').val();

                        // Send the data to the server
                        $.post('edit_email.php', {
                            id: id,
                            email: email,
                            frequency: frequency,
                            template: template
                        }, function() {
                            // Hide the form
                            $('#editForm').hide();

                            // Reload the page
                            location.reload();
                        });
                    }
                    $(document).ready(function() {
                        $('#myTable').DataTable();
                    });
                </script>
            </div>
        </div>
    </div>

    <!-- Agregar Bootstrap JS y sus dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Agregar DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

</body>

</html>