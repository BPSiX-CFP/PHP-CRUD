<?php 
// Agafem la sessió que ja existeix del usuari
session_start();
// Destruim aquesta sessió
session_destroy();
// Redirigim al index
header("Location: index.php");
?>