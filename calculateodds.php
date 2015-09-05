<?php
    require_once('login.php');

    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);

    if(!$conn) {
      die ("Unable to connect to MySQL: " . mysql_error());
    }


    function getTeamElo($team, $conn) {
        //CONVERT ALL OF DOTA2LOUNGE NAMES TO GOSUGAMERS NAMES
        switch($team) {
            case 'Vega': $team = 'Vega Squadron'; break;
            case 'Na\'Vi': $team = 'Natus Vincere Dota 2'; break;
            case 'hehe': $team = 'hehe united'; break;
            case 'SFZ': $team = 'SCARYFACEZZZ.Dota 2'; break;
            case 'Empire': $team = 'Team Empire Dota2'; break;
            case 'NiP': $team = 'Ninjas in Pyjamas Dota2'; break;
        }
        $result = $conn->query("SELECT `elo` FROM `gosugamers` WHERE `team` = ' $team'"); //THERE IS A SPACE AFTER $TEAM BECAUSE ITS STORED LIKE THAT IN THE DATABASE
        if ($result->num_rows > 0) {
            $elo = mysqli_fetch_assoc($result)['elo'];
        }
        else {
            $elo = 0;
        }
        return $elo;
    }


    function calculateOdds($team1, $team2, $conn) { //Calculate the odds the team 1 beats team 2
        $team1elo = getTeamElo($team1, $conn);
        $team2elo = getTeamElo($team2, $conn);
        $odds = 1 / (1+pow(10,($team2elo-$team1elo)/400)); //Elo calculation
        if($team1elo = 0 || $team2elo = 0) {
           $odds = 0;
        }
        return $odds;
    }

?>
