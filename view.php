<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Do something with the selected data</title>
  </head>
  <body>
    <div class="container">
    <h1>Selected client demo</h1>

    <?php
    if (isset($_GET['ID']))
    {
      $id = $_GET['ID']; // you should use real_escape_string here to prevend SQL injections, since were not connecting to database, Im not doing /**

      echo "<div class='alert alert-success' role='alert'>
        ID was received
        <br>
        You requested client # $id
      </div>";

    }
    else
    {
      echo '<div class="alert alert-danger" role="alert">
        ID not received!
      </div>';
    }

    ?>

</div>
  </body>
</html>
