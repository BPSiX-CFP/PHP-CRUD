<?php
  // Importem el fitxer database.php i en conseqüencia les seves variables
  require "database.php";

  // Crem variables de error per gestionar-los
  $error = null;
  // Si rebem un per les capçaleres un metode POST entrara dins del POST
  if ($_SERVER["REQUEST_METHOD"] == "POST"){
    // Comprovem que no estiguin buits el nom el email i la contrasenya en cas de estar buits retornem un error
      if (empty($_POST["email"]) || empty($_POST["password"])) {
        $error = "Please fill all the fields.";
      } else {
        // Comprovem que esxisteixi el correu a la base de dades
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $statement->bindParam(":email", $_POST["email"]);
        $statement->execute();
        
        if ($statement->rowCount() == 0) {
          $error = "Invalid credentials.";
        } else {
          // Associem el resultat de la query a la variable user
          $user = $statement->fetch(PDO::FETCH_ASSOC);
          // Comprovem la contrasenya de la base de dades amb la del post
          if (!password_verify($_POST["password"], $user["password"])) {
            $error = "Invalid credentials.";
          } else {
            // Iniciem sessió 
            session_start();  
            // Eliminem el password de la variable $user
            unset($user["password"]);
            // Crem la variable user com a session
            $_SESSION["user"] = $user;
            // Retornem al home.php
            header("Location: home.php");
          } 
        }
      }
  }
?>
<!-- Header.php -->
<?php require "partials/header.php"?>

    <div class="container pt-5">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">Login</div>
            <div class="card-body">
              
            <!-- If per mostra els errors en el cas que la variable tinqgui un error assignat -->
              <?php if ($error != null): ?>
                <p class="text-danger"><?= $error ?></p>
              <?php endif ?>

              <form method="POST" action="login.php">
          
                <div class="mb-3 row">
                  <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
    
                  <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email" autocomplete="email" autofocus>
                  </div>
                </div>
                
                <div class="mb-3 row">
                  <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>
    
                  <div class="col-md-6">
                    <input id="password" type="password" class="form-control" name="password" autocomplete="password" autofocus>
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
