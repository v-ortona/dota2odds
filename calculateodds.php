<?php
    require_once('login.php');

    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);

    if(!$conn) {
      die ("Unable to connect to MySQL: " . mysql_error());
    }


    function getTeamElo($team, $conn) {
        switch($team) {
            case 'Vega': $team = 'Vega Squadron'; break;
            case 'Na\'Vi': $team = 'Natus Vincere Dota 2'; break;
            case 'hehe': $team = 'hehe united'; break;
            case 'SFZ': $team = 'SCARYFACEZZZ.Dota 2'; break;
            case 'Empire': $team = 'Team Empire Dota2'; break;
            case 'NiP': $team = 'Ninjas in Pyjamas Dota2'; break;
        }
        $result = $conn->query("SELECT `elo` FROM `gosugamers` WHERE `team` = ' $team'");
        if ($result->num_rows > 0) {
            $elo = mysqli_fetch_assoc($result)['elo'];
        }
        else {
            echo "No elo found";
            $elo = 0;
        }
        return $elo;
    }


    function calculateOdds($team1, $team2, $conn) { //Calculate the odds the team 1 beats team 2
        $team1elo = getTeamElo($team1, $conn);
        $team2elo = getTeamElo($team2, $conn);
        $odds = 1 / (1+pow(10,($team2elo-$team1elo)/400));
        return $odds;
    }

    echo calculateOdds('Na\'Vi', 'NiP', $conn);
    mysqli_close($conn);
?>
