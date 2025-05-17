<?php
session_start();

include 'config.php';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['userid'];
$password = $_POST['password'];

$sql = $conn->prepare("SELECT username, password, modul, id_pegawai FROM authorization WHERE username = ?");
$sql->bind_param("s", $username);
$sql->execute();
$result = $sql->get_result();
$record = $result->fetch_assoc();

if (!$record) {
    header("location:index.php?status=Maaf, username dan password tidak valid");
    exit();
}

// Verify password (assuming passwords are hashed in DB)
if (!password_verify($password, $record['password'])) {
    header("location:index.php?status=Maaf, username dan password tidak valid");
    exit();
}

$_SESSION['username'] = $username;
$_SESSION['idpegawai'] = $record['id_pegawai'];

switch ($record['modul']) {
    case "Finance":
        header("location:Finance/");
        break;
    case "Sales":
        header("location:Sales/");
        break;
    case "Warehouse":
        header("location:warehouse/");
        break;
    case "Adminwarehouse":
        header("location:adminwarehouse/dashboard.php");
        break;
    case "Purchase":
        header("location:adminpurchase/");
        break;
    case "HR":
        header("location:hr/");
        break;
    case "superadmin":
        header("location:superadmin/");
        break;
    default:
        header("location:index.php?status=Modul tidak dikenal");
        break;
}

exit();
?>
