<?php
  // Importem el fitxer database.php i en conseqüencia les seves variables
  require "database.php";
  // En el cas que no exsiteix una sessió enviem al usuari al login.php
  if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    return;
  }

  // En el cas que no sigui un usuari admin que no tingui acces al panell
  if ($_SESSION["user"]["role"] === "user" ) {
    header("Location: home.php");
    return;
  }
  
  // Preparem una syntaxis SQL per extreure els usuaris de la BD
  $users = $conn->query("SELECT * FROM users");
 ?> 
<!-- Header.php -->
<?php require "partials/header.php"?>

<div class="container pt-5">
      <div class="row justify-content-center">
        <div class="col-md-10">
          <table class="table text-center">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Editar</th>
                <th>Borrar</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
              <tr>
                <th scope="row"><?= $user["id"] ?></td>
                <td><?= $user["name"] ?></td>
                <td><?= $user["email"] ?></td>
                <td><?= $user["role"] ?></td>
                <td><a href="./editUser.php?id=<?= $user["id"];?>" class="btn btn-primary mb-2">Edit User</a></td>
                <td><a href="./deleteUser.php?id=<?= $user["id"];?>" class="btn btn-danger mb-2">Delete User</a></td>
              </tr>
            <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
</div>




<!-- Footer.php -->
<?php require "partials/footer.php" ?>
