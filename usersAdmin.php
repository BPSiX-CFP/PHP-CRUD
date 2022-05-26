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

// En el cas que no sigui un usuari admin que no tingui acces al panell
if ($_SESSION["user"]["role"] === "user") {
  header("Location: home.php");
  return;
}

// Preparem una syntaxis SQL per extreure els usuaris de la BD
$users = $conn->query("SELECT * FROM users");
?>
<!-- Header.php -->
<?php require "partials/header.php" ?>

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
          <?php $index = 0;
          foreach ($users as $user) : $index++ ?>
            <tr>
              <th scope="row"><?= $user["id"] ?></td>
              <td><?= $user["name"] ?></td>
              <td><?= $user["email"] ?></td>
              <td><?= $user["role"] ?></td>
              <td><a href="./editUser.php?id=<?= $user["id"]; ?>" class="btn btn-primary mb-2">Edit User</a></td>

              <!-- Button trigger modal -->
              <td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo ($index) ?>">
                  Eliminar
                </button></td>

              <!-- Modal -->
              <div class="modal fade" id="exampleModal<?php echo ($index) ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title" id="exampleModalLabel">Confirmacio</h4>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <h5>Segur que vols eliminar l'usuari: <?php echo ($user['name']) ?></h5>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <a href="./deleteUser.php?id=<?= $user["id"]; ?>" class="btn btn-danger mb-2">Eliminar Usuari</a>
                    </div>
                  </div>
                </div>
              </div>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
</div>




<!-- Footer.php -->
<?php require "partials/footer.php" ?>