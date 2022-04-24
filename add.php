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
  // Si rebem un per les capçaleres un metode POST entrara dins del POST
  if ($_SERVER["REQUEST_METHOD"] == "POST"){
      // Si el parametres name i num enviats per post estan buits retornem el misstage de error
      if (empty($_POST["name"]) || empty($_POST["num"])) {
        $error = "Sisplau entri un nom i telèfon al contacte!";
      } else if (strlen($_POST["num"]) < 6) {
        $error = "Entra un número més gran o igual que 6!"; 
      }else {
        //Variables que rebem per POST
        $nom = $_POST["name"];
        $num_phone = $_POST["num"];
        // Preparem una syntaxis SQL amb valors incognita per prevenir un SQL Inyection!
        $statement = $conn->prepare("INSERT INTO contacts (user_id,name, num_phone) VALUES ({$_SESSION["user"]["id"]},:name, :num)");
        // Comprobem que els valors pasats per POST no son un atac a la base de dades i canviem les variables :name i :num per les que ens pasen per el POST el usuari
        $statement->bindParam(":name", $_POST["name"]);
        $statement->bindParam(":num", $_POST["num"]);
        // Executem la syntaxis anteriorment preparada
        $statement->execute();  
        // Crem una variable de sessió per mostra un missatge flash que es mostra una vegada.
        $_SESSION["flash"] = ["message" => "Contact {$_POST["name"]} added."];
        // Enviem al home.php
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
              <form method="POST" action="add.php">
                <div class="mb-3 row">
                  <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>
    
                  <div class="col-md-6">
                    <input id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
                  </div>
                </div>
    
                <div class="mb-3 row">
                  <label for="phone_number" class="col-md-4 col-form-label text-md-end">Phone Number</label>
    
                  <div class="col-md-6">
                    <input id="phone_number" type="tel" class="form-control" name="num" autocomplete="phone_number" autofocus>
                  </div>
                </div>
    
                <div class="mb-3 row">
                  <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">Submit</button>
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
