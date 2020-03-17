<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

  <title>Krono Data Grid INSTALL</title>
</head>
<body>

  <div class="container">

    <h1>Krono's simple data grid</h1>

    <h2>INSTALL SCRIPT</h2>

    <p>
      We will create a table inside the database, and populate it with random data.
    </p>

    <?php
    require_once '../lib/connectMySQL.php';
    require_once '../dbConfig.php';

    // load names and surnames for random generator
    $surnameS = file('surnames.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // lo carga en array
    $nameS = file('names.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // random names
    function nameGen()
    {
      global $nameS;
      $a0 = intval(rand(0, count($nameS)-1));
      $a1 = intval(rand(0, count($nameS)-1));
      $ret = $nameS[$a0] . ' ' . $nameS[$a1];
      return ucwords(strtolower($ret));
    }

    // random surnames
    function surnameGen()
    {
      global $surnameS;
      $a2 = intval(rand(0, count($surnameS)-1));
      $a3 = intval(rand(0, count($surnameS)-1));
      $ret = $surnameS[$a2] . ' ' . $surnameS[$a3];
      return ucwords(strtolower($ret));
    }


    $db = connectMySQL($db_host, $db_username, $db_password, $db_db_name);

    $cmd = 'CREATE TABLE IF NOT EXISTS clients (
      Name varchar(256) ,
      Surname varchar(256) ,
      Birthday date ,
      Phone varchar(80),
      ID int(11) NOT NULL AUTO_INCREMENT,
      PRIMARY KEY (ID)) ;';

      $db->query($cmd);

      echo '<div class="alert alert-success" role="alert">
      Table created inside the database! GOOD!
      </div>';


      // insert random data

      echo count($surnameS) . ' surnames loaded.<br>';
      echo count($nameS) . ' names loaded.<br>';

      $c = 100; // how many random clients?

      for ($i = 0;$i < $c; $i++)
      {
        $name = nameGen();
        $surname = surnameGen();


        // tricky random Birthday

        $min = strtotime("47 years ago");
        $max = strtotime("18 years ago");
        $rand_time = mt_rand($min, $max);

        $bd = date("Y-m-d H:i:s", $rand_time);


        $phone = rand(111, 2314) . "-" . rand(5000, 10000);

        $cmd = "INSERT INTO clients (Name, Surname, Birthday, Phone) VALUES ('$name', '$surname', '$bd', '$phone')";

        $db->query($cmd);
      }

      echo '<div class="alert alert-success" role="alert">
      Data inserted into database! Cool!
      <br>
      All done. You can now test the data grid with random data loaded.
      </div>';

      ?>



    </div>
  </body>
  </html>
