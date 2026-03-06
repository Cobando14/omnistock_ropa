<?php
session_start();
include("../config/conexion.php");

$correo = $_POST['correo'];
$password = $_POST['password'];

$sql = "SELECT * FROM usuarios WHERE correo_usuario='$correo'";
$result = $conn->query($sql);

if($result->num_rows > 0){

    $usuario = $result->fetch_assoc();

    if(password_verify($password,$usuario['password_usuario'])){

        $_SESSION['usuario']=$usuario['nom_usuario'];
        $_SESSION['id_usuario']=$usuario['id_usuario'];
        $_SESSION['rol']=$usuario['rol'];

        header("Location: ../dashboard/index.php");

    }else{

        echo "Contraseña incorrecta";

    }

}else{

    echo "Usuario no encontrado";

}
?>