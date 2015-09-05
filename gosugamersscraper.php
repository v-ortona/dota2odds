<?php

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



  $gosupage1 = getPage('http://www.gosugamers.net/dota2/rankings?page=1');
  $gosuXPath1 = returnXPathObject($gosupage1);


  //GET IMPORTANT DATA FROM XPATH OBJECT
  $teamName1 = $gosuXPath1->query('//span[@class="main no-game"]'); //query for team name
  $teamElo1 = $gosuXPath1->query('//td[@class="numbers"]'); //query for team Elo

  $i=0;
  while($teamName1->item($i)->nodeValue){ //while there are still teams on the page

    if($teamName1->item($i)->nodeValue) {
        $scrapedData[$teamName1->item($i)->nodeValue] = str_replace(',', '', $teamElo1->item($i)->nodeValue); //replace commas from elo value eg) 1,000
    }
    $i++;
  }


  //SAME THING BUT SECOND PAGE (TEAMS 51-100)
  $gosupage2 = getPage('http://www.gosugamers.net/dota2/rankings?page=2');
  $gosuXPath2 = returnXPathObject($gosupage2);


  $teamName2 = $gosuXPath2->query('//span[@class="main no-game"]'); //query for team name
  $teamElo2 = $gosuXPath2->query('//td[@class="numbers"]'); //query for team Elo

  $i=0;
  while($teamName2->item($i)->nodeValue){

    if($teamName2->item($i)->nodeValue) {
        $scrapedData[$teamName2->item($i)->nodeValue] = str_replace(',', '', $teamElo2->item($i)->nodeValue);
    }
    $i++;
  }

  
  print_r($scrapedData);



?>
