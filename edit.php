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

$id = "";
$name = "";
$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(!isset($_GET["id"])){
        header("location: /crud_uasd/index.php?role=$role");
        exit;
    }
    $id = $_GET["id"];
    if ($role === 'teacher') {
        $sql = "SELECT * FROM teachers WHERE id=$id";
    } else {
        $sql = "SELECT * FROM students WHERE id=$id";
    }
    $result = $connection->query($sql);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $name = $row["name"];
        if ($role === 'teacher') {
            $subject = $row["subject"];
        }
    } else {
        header("location: /crud_uasd/index.php?role=$role");
        exit;
    }
}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
    do {
        $id = $_POST["id"];
        $name = $_POST["name"];
        if (empty($name)) {
            $errorMessage = "Campos requeridos";
            break;
        }
        if ($role === 'teacher') {
            $subject = $_POST["subject"];
            if (empty($subject)) {
                $errorMessage = "El campo 'Materia' es requerido para profesores";
                break;
            }
            $sql = "UPDATE teachers SET name = '$name', subject = '$subject' WHERE id = $id";
        } else {
            $sql = "UPDATE students SET name = '$name' WHERE id = $id";
        }
        $result = $connection->query($sql);
        if(!$result){
            die("Invalid query: " . $connection->error);
        }
        $successMessage = "Registro actualizado";
        header("location: /crud_uasd/index.php?role=$role");
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
        <h1>Actualizar <?php echo $role === 'teacher' ? 'Profesor' : 'Estudiante'; ?></h1>
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
            <input type="hidden" name="id" value="<?php echo $id;?>"/>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Nombre</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="name" value="<?php echo $name;?>">
                </div>
            </div>
            <?php if ($role === 'teacher'): ?>
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Materia</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="subject" value="<?php echo $subject;?>">
                    </div>
                </div>
            <?php endif; ?>
            <div class="row mb-3">
                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
<!-- Author : Robert Jeysson Nin Urena [Sr. FullStack Dev]-->