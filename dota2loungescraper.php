<?php
  require_once('calculateOdds.php');

  function getPage($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_URL, $url);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
  }

  //CONVERT TO XPATH OBJECT
  $scrapedData = array();

  function returnXPathObject($item) {
    $xmlPageDom = new DOMDocument(); //instantiate
    @$xmlPageDom->loadHTML($item);  //load
    $xmlPageXPath = new DOMXPath($xmlPageDom); //instantiate xpath object

    return $xmlPageXPath;
  }

  $loungepage = getPage('http://www.dota2lounge.com/');
  $loungeXPath = returnXPathObject($loungepage);


  echo 'UPCOMING GAMES:<br>';
  $match = $loungeXPath->query('//div[@class="match"]');

  $i=0;
  $j=0;
  while($match->item($i)->nodeValue) {
      $teams = $loungeXPath->query('//div[@class="teamtext"]');

      $team1 = substr($teams->item($j)->nodeValue, 0, -3);
        $odds1 = substr($teams->item($j)->nodeValue, -3, -1);
      $team2 = substr($teams->item($j+1)->nodeValue, 0, -3);
        $odds2 = substr($teams->item($j+1)->nodeValue, -3, -1);

      echo $team1 . ' ('.$odds1.'%) ' . $team2 . ' ('.$odds2.'%)<br>';


      echo 'ACTUAL ODDS: ';
      $actualOdds = calculateOdds($team1,$team2,$conn);
      if($actualOdds < 0.01) {
        $actualOdds = 'N/A';
        echo 'Elo of one of these teams cannot be found<br>';
      }
      else {
      $actualOdds = round($actualOdds * 100);
      echo $team1. ' ('.$actualOdds.'%) ' . $team2 . ' ('.(100-$actualOdds).'%)<br>';
      }

      $i++;
      $j=$j+2;
  }


  echo 'FINISHED GAMES:<br>';
  $match = $loungeXPath->query('//div[@class="match notavailable"]');

  $i=0;
  $j=0;
  while($match->item($i)->nodeValue) {
      $teams = $loungeXPath->query('//div[@class="match notavailable"]//div[@class="teamtext"]');

      $team1 = substr($teams->item($j)->nodeValue, 0, -3);
        $odds1 = substr($teams->item($j)->nodeValue, -3, -1);
      $team2 = substr($teams->item($j+1)->nodeValue, 0, -3);
        $odds2 = substr($teams->item($j+1)->nodeValue, -3, -1);

      echo $team1 . ' ('.$odds1.'%) ' . $team2 . ' ('.$odds2.'%)<br>';


      echo 'ACTUAL ODDS: ';
      $actualOdds = calculateOdds($team1,$team2,$conn);
      if($actualOdds < 0.01 || $actualOdds > 0.99) {
        $actualOdds = 'N/A';
        echo 'Elo of one of these teams cannot be found<br>';
      }
      else {
      $actualOdds = round($actualOdds * 100);
      echo $team1. ' ('.$actualOdds.'%) ' . $team2 . ' ('.(100-$actualOdds).'%)<br>';
      }

      $i++;
      $j=$j+2;
  }


  mysqli_close($conn);
?>
