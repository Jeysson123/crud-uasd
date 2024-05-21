<?php 
if(isset($_GET["id"])) {
    $id = $_GET["id"];
    $role = isset($_GET["role"]) ? $_GET["role"] : "";

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "crud_uasd";

    $connection = new mysqli($servername, $username, $password, $database);

    if($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $table = ($role === "teacher") ? "teachers" : "students";

    $stmt = $connection->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->bind_param("i", $id);

    if($stmt->execute()) {
        header("Location: /crud_uasd/index.php?role=$role");
        exit; 
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();
}
?>
<!-- Author : Robert Jeysson Nin Urena [Sr. FullStack Dev]-->