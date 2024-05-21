<?php 
$name = "";
$subject = ""; 
$errorMessage = "";
$successMessage = "";
$servername = "localhost";
$username = "root";
$password = "";
$database = "crud_uasd";

$role = isset($_GET['role']) ? $_GET['role'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST["name"];

    do {
        if (empty($name) || empty($role)) {
            $errorMessage = "Campos requeridos";
            break;
        }

        $connection = new mysqli($servername, $username, $password, $database);
        if($connection->connect_error){
            die("Connection failed: " . $connection->connect_error);
        }

        if ($role === 'student') {
            $sql = "INSERT INTO students (name) VALUES ('$name')";
        } elseif ($role === 'teacher') {
            $subject = $_POST["subject"];
            if (empty($subject)) {
                $errorMessage = "El campo 'Materia' es requerido para profesores";
                break;
            }
            $sql = "INSERT INTO teachers (name, subject) VALUES ('$name', '$subject')";
        }

        $result = $connection->query($sql);
        if(!$result){
            die("Invalid query: " . $connection->error);
        }

        $name = "";
        $subject = "";
        $successMessage = "Registro agregado";

        // Redirect to index.php after successful insertion
        header("location: /crud_uasd/index.php");
        exit;
    } while (false);
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
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Nuevo Registro</h1>
            </div>
            <div class="card-body">
                <?php 
                    if (!empty($errorMessage)) {
                        echo "
                            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                <strong>$errorMessage</strong>
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>
                        ";
                    }
                    if (!empty($successMessage)) {
                        echo "
                            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>$successMessage</strong>
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>
                        ";
                    }
                ?>
                <br>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="name" value="<?php echo $name;?>" required>
                    </div>

                    <?php if ($role === 'teacher'): ?>
                    <div class="mb-3">
                        <label class="form-label">Materia (solo para profesores)</label>
                        <input type="text" class="form-control" name="subject" value="<?php echo $subject;?>">
                    </div>
                    <?php endif; ?>

                    <input type="hidden" name="role" value="<?php echo $role; ?>">

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<!-- Author : Robert Jeysson Nin Urena [Sr. FullStack Dev]-->
