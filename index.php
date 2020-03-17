<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

  <title>Krono's data grid</title>
</head>
<body>
<div class="container">

  <h1>Krono's simple data grid</h1>

  <p>
    Welcome, this is a data grid test, made in PHP.
  </p>

  <p>
  You should have run the install scripts first, to create a suitable database.
  <br>
  <a href='./install'>install scripts here, run  ONLY ONCE</a>
  </p>

<?php
// do a demo table

require_once 'dbConfig.php';
require_once 'lib/kronoDataGrid.php';

// Turn off all error reporting
//error_reporting(0);

// setup data grid
$dataG = new kronoDataGrid($db_host, $db_db_name, $db_username, $db_password);
$dataG->table_css_class = 'table table-striped table-hover';

$dataG->PK = 'ID'; // set up the primary key, is IMPORTANT! CASE SENSITIVE!

// add actions, you can add as many as you need
$dataG->actions[] = new tableAction('view.php?', 'img/edit_icon.png', 'View');

$cmd = 'SELECT Name, Surname, Phone, Birthday, ID FROM clients'; // command to show

$totalR = $dataG->queryShow($cmd); // do the table show

if ($totalR > 0)
  echo '<P>SUCCESS!</P>';

?>

</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <!-- not needed for now
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    -->
</body>
</html>
