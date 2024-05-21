<?php 
$servername = "localhost";
$username = "root";
$password = "";
$database = "crud_uasd";

$role = isset($_GET['role']) ? $_GET['role'] : 'student';

$connection = new mysqli($servername, $username, $password, $database);
if($connection->connect_error){
    die("Connection failed: " . $connection->connect_error);
}

$table = $role === 'teacher' ? 'teachers' : 'students';
$sql = "SELECT * FROM $table";
$result = $connection->query($sql);

if(!$result){
    die("Invalid query: " . $connection->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESTUDIANTES UASD</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container my-5">
        <h1>Lista de <span id="context-title"><?php echo $role === 'teacher' ? 'profesores' : 'estudiantes'; ?></span></h1>
        
        <div class="mb-3">
            <input type="radio" id="student" name="role" value="student" <?php echo $role === 'student' ? 'checked' : ''; ?>>
            <label for="student">Estudiante</label>
            <input type="radio" id="teacher" name="role" value="teacher" <?php echo $role === 'teacher' ? 'checked' : ''; ?>>
            <label for="teacher">Profesor</label>
        </div>

        <a class="btn btn-primary mb-3" id="addButton" href="/crud_uasd/create.php?role=student" role="button">Agregar</a>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <?php if ($role === 'teacher'): ?>
                            <th>Materia</th>
                        <?php endif; ?>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        while($row = $result->fetch_assoc()){
                            echo "
                            <tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>";
                                if ($role === 'teacher') {
                                    echo "<td>{$row['subject']}</td>";
                                }
                                echo "
                                <td>
                                    <a class='btn btn-primary btn-sm me-2' href='/crud_uasd/edit.php?id={$row['id']}&role=$role' role='button'>Editar</a>
                                    <a class='btn btn-danger btn-sm' href='/crud_uasd/delete.php?id={$row['id']}&role=$role' role='button'>Eliminar</a>
                                </td>
                            </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function setContextTitle(role) {
            const contextTitle = document.getElementById('context-title');
            contextTitle.textContent = role === 'teacher' ? 'profesores' : 'estudiantes';
        }

        const urlParams = new URLSearchParams(window.location.search);
        const role = urlParams.get('role') || 'student';

        document.getElementById(role).checked = true;
        setContextTitle(role);

        document.querySelectorAll('input[name="role"]').forEach((radio) => {
            radio.addEventListener('change', function() {
                const selectedRole = document.querySelector('input[name="role"]:checked').value;
                window.location.href = `?role=${selectedRole}`;
            });
        });

        document.getElementById('addButton').addEventListener('click', function(e) {
            e.preventDefault();
            const selectedRole = document.querySelector('input[name="role"]:checked').value;
            window.location.href = `/crud_uasd/create.php?role=${selectedRole}`;
        });
    </script>
</body>
</html>
<!-- Author : Robert Jeysson Nin Urena [Sr. FullStack Dev]-->