<?php
  require_once 'gosugamersscraper.php';
  require_once 'login.php';

  $db_server = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);

  if(!$db_server) {
    die ("Unable to connect to MySQL: " . mysql_error());
  }


  foreach($scrapedData as $team=>$elo) {
      echo $team . $elo;
      $team = mysql_real_escape_string($team);
      $gosuSQL = "INSERT INTO gosugamers (team, elo) VALUES ('$team', '$elo') ON DUPLICATE KEY UPDATE gosugamers SET elo='$elo' WHERE team='$team'";
      if($db_server->query($gosuSQL) === TRUE) {
          echo "Record added succesfully<br>";
      }
  }

  mysqli_close($db_server);
?>
