<?php
// Importem el fitxer database.php i en conseqüencia les seves variables
require "database.php";
// Crem o afafem una sessio del usuari
session_start();
// En el cas que no exsiteix una sessió enviem al usuari al login.php
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  return;
}
// A partir de la URL amb el metode GET recollim el valor pasat amb la variable $id
$id = $_GET["id"];
// Primer de tot fem una QUERY a la base de dades per comprobar si exixteix algun contatcte amb la id pasada
$statement = $conn->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");
// I executem la QUERY abre
$statement->execute([":id" => $id]);
// A partir de la QUERY feta anteriorment si el resultat de columnes es 0 no esxisteix retornem un 404
if ($statement->rowCount() == 0) {
  http_response_code(404);
  echo ("HTTP 404 NOT FOUND");
  return;
}
// Agafem les dades de la quey i les guardem a la vartiable contact
$contact = $statement->fetch(PDO::FETCH_ASSOC);
// Comprobem que el user_id correspont amb el del contacte en cas que no responem amb que no te permis
if ($contact["user_id"] !== $_SESSION["user"]["id"]) {
  http_response_code(403);
  echo ("Forbbiden");
  return;
}

// En el cas que si existeix el contacte preparem la QUERY i la executarem 
 $conn->prepare("DELETE FROM contacts WHERE id = :id")->execute([":id" => $id]);
// Crem una variable de sessió per mostra un missatge flash que es mostra una vegada.
$_SESSION["flash"] = ["message" => "Contact {$contact["name"]} deleted."];
 // Redirigim l'usuari a home.php
header("Location: home.php");
?>