<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>

<body>
  <div class="container mt-5">
    <div class="row">
      <div class="col-12 col-md-5">

        <div class="card">
          <div class="card-body">
            <h3 class="card-title">Importar archivo de excel del mes actual.</h3>

            <form method="POST" action="importar.php" enctype="multipart/form-data">
              <label>Archivo de excel:</label>
              <input type="file" name="uploadedFile" required>
              <br>
              <input type="submit" name="enviar" value="Procesar" class="btn btn-primary mt-3">
            </form>

          </div>
        </div>

      </div>
      <div class="col-12 col-md-7">

        <div class="card">
          <div class="card-body">
            <h3 class="card-title">Planes de las líneas.</h3>

            <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">Línea</th>
                  <th scope="col">Plan</th>
                </tr>
              </thead>
              <tbody>
                <?php

                require 'conexion.php';

                $sql = "SELECT * FROM celulares";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {

                ?>

                  <tr>
                    <td><?php echo $row["celular"] ?></td>
                    <td>$<?php echo number_format($row["total_plan"]) ?></td>
                  </tr>

                <?php } ?>
              </tbody>
            </table>

          </div>
        </div>

      </div>
    </div>



</body>

</html>