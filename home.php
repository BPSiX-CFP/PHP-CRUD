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
  // Donem els valors de la query a la variable $contactes
  $contactes = $conn->query("SELECT * FROM contacts WHERE user_id = {$_SESSION["user"]["id"]}");
?>

<!-- Header.php -->
<?php require "partials/header.php"?>

    <div class="container pt-4 p-3">
      <div class="row">
      <!-- Utilitzem un if per saber si existeixent contactes en el cas que no mostratem una nota per que vagi a crear un al arxiu add.php  -->
      <?php if ($contactes->rowCount() == 0): ?>
        <div class="col-md-4 mx-auto">
          <div class="card card-body text-center">
            <p>No contacts saved yet</p>
            <a href="add.php">Add One!</a>
          </div>
        </div>
      <?php endif ?>
      <!-- Utilitzem al final del foreach: i per tancar endforeach per bucles molt llargs -->
        <?php foreach ($contactes as $contact): ?>
          <div class="col-md-4 mb-3">
            <div class="card text-center">
              <div class="card-body">
                <h3 class="card-title text-capitalize"><?= $contact["name"];?></h3>
                <p class="m-2"><?= $contact["num_phone"];?></p>
                <a href="./edit.php?id=<?= $contact["id"];?>" class="btn btn-secondary mb-2">Edit Contact</a>
                <a href="./delete.php?id=<?= $contact["id"];?>" class="btn btn-danger mb-2">Delete Contact</a>
              </div>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    </div>
    
<!-- Footer.php -->
<?php require "partials/footer.php" ?>
