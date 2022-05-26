<?php
// Importem el fitxer database.php i en conseqüencia les seves variables
require "database.php";
session_start();
// En el cas que no exsiteix una sessió enviem al usuari al login.php
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  return;
}
// Crem variables de error per gestionar-los
$error = null;

// <- Carrega per GET del contacte agafat per EDITAR ->
// A partir de la URL amb el metode GET recollim el valor pasat amb la variable $id
$id = $_GET["id"];
// Primer de tot fem una QUERY a la base de dades per comprobar si exixteix algun contatcte amb la id pasada I escribim LIMIT 1 per tindre un array sense index ja que nomes tenim un contacte
$statement = $conn->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
// I executem la QUERY abre
$statement->execute([":id" => $id]);
// A partir de la QUERY feta anteriorment si el resultat de columnes es 0 no esxisteix retornem un 404
if ($statement->rowCount() == 0) {
  http_response_code(404);
  echo ("HTTP 404 NOT FOUND");
  return;
}
// En el cas que el usuari existeix pasem a la variable  $conrtact el resultat de la querry en format Array amb clau valor gracies a FETCH_ASSOC
$user = $statement->fetch(PDO::FETCH_ASSOC);

$error = null;

// <- UPDATE del contacte a la Base de Dades ->

if ($_SERVER["REQUEST_METHOD"]  == "POST") {
  if (empty($_POST["name"]) || empty($_POST["email"]) ||  empty($_POST["password"]) || empty($_POST["role"])) {
    $error = "Please fill all the fields.";
  } else {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    $statement = $conn->prepare("UPDATE users SET name = :name, email = :email, password = :password, role = :role WHERE id = :id");
    $statement->execute([
      ":id" => $id,
      ":name" => $_POST["name"],
      ":email" => $_POST["email"],
      ":password" => password_hash($_POST["password"], PASSWORD_BCRYPT),
      ":role" => $_POST["role"]
    ]);

    // Crem una variable de sessió per mostra un missatge flash que es mostra una vegada.
    $_SESSION["flash"] = ["message" => "User {$_POST["email"]} updated."];

    header("Location: usersAdmin.php");
    return;
  }
}

?>

<!-- Header.php -->
<?php require "partials/header.php" ?>

<div class="container pt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Edit User</div>
        <div class="card-body">

          <!-- If per mostra els errors en el cas que la variable tinqgui un error assignat -->
          <?php if ($error != null) : ?>
            <p class="text-danger"><?= $error ?></p>
          <?php endif ?>

          <form method="POST" action="editUser.php?id=<?= $user["id"]; ?>">
            <div class="mb-3 row">
              <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

              <div class="col-md-6">
                <input value="<?= $user["name"]; ?>" id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>

              <div class="col-md-6">
                <input value="<?= $user["email"] ?>" id="email" type="text" class="form-control" name="email" autocomplete="email" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>

              <div class="col-md-6">
                <input id="password" type="text" class="form-control" name="password" autocomplete="password" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="role" class="col-md-4 col-form-label text-md-end">Role</label>

              <div class="col-md-6">
                <input value="<?= $user["role"] ?>" id="role" type="text" class="form-control" name="role" autocomplete="role" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-success">Update</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer.php  -->
<?php require "partials/footer.php" ?>