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
  // Crem variables de error per gestionar-los
  $error = null;

  // <- Carrega per GET del contacte agafat per EDITAR ->
  // A partir de la URL amb el metode GET recollim el valor pasat amb la variable $id
  $id = $_GET["id"];
  // Primer de tot fem una QUERY a la base de dades per comprobar si exixteix algun contatcte amb la id pasada I escribim LIMIT 1 per tindre un array sense index ja que nomes tenim un contacte
  $statement = $conn->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");
  // I executem la QUERY abre
  $statement->execute([":id" => $id]);
  // A partir de la QUERY feta anteriorment si el resultat de columnes es 0 no esxisteix retornem un 404
  if ($statement->rowCount() == 0) {
    http_response_code(404);
    echo ("HTTP 404 NOT FOUND");
    return;
  }
  // En el cas que el contacte existeix pasem a la variable  $conrtact el resultat de la querry en format Array amb clau valor gracies a FETCH_ASSOC
  $contact = $statement->fetch(PDO::FETCH_ASSOC);

  // Comprobem que el user_id correspont amb el del contacte en cas que no responem amb que no te permis
  if ($contact["user_id"] !== $_SESSION["user"]["id"]) {
    http_response_code(403);
    echo ("Forbbiden");
    return;
  }

  $error = null;

  // <- UPDATE del contacte a la Base de Dades ->
  
  if ($_SERVER["REQUEST_METHOD"]  == "POST") {
    if (empty($_POST["name"]) || empty($_POST["phone_number"])) {
      $error = "Please fill all the fields.";
    } else if (strlen($_POST["phone_number"]) < 6) {
      $error = "Phone number must be at least 6 characters.";
    } else {
      $name = $_POST["name"];
      $phoneNumber = $_POST["phone_number"];

      $statement = $conn->prepare("UPDATE contacts SET name = :name, num_phone = :phone_number WHERE id = :id");
      $statement->execute([
        ":id" => $id,
        ":name" => $_POST["name"],
        ":phone_number" => $_POST["phone_number"],
      ]);
      
      // Crem una variable de sessió per mostra un missatge flash que es mostra una vegada.
      $_SESSION["flash"] = ["message" => "Contact {$_POST["name"]} updated."];

      header("Location: home.php");
      return;
    }
  }


?>

<!-- Header.php -->
<?php require "partials/header.php"?>

    <div class="container pt-5">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">Add New Contact</div>
            <div class="card-body">

              <!-- If per mostra els errors en el cas que la variable tinqgui un error assignat -->
              <?php if ($error != null): ?>
                <p class="text-danger"><?= $error ?></p>
              <?php endif ?>

              <form method="POST" action="edit.php?id=<?= $contact["id"];  ?>">
                <div class="mb-3 row">
                  <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>
    
                  <div class="col-md-6">
                    <input value="<?= $contact["name"]; ?>" id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
                  </div>
                </div>
    
                <div class="mb-3 row">
                  <label for="phone_number" class="col-md-4 col-form-label text-md-end">Phone Number</label>
    
                  <div class="col-md-6">
                    <input value="<?= $contact["num_phone"] ?>" id="phone_number" type="tel" class="form-control" name="phone_number" autocomplete="phone_number" autofocus>
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