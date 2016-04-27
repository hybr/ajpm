<?php
if ($_SERVER['request_method'] = "get") {
 if ($_GET['t'] == "assets") {
  $searchBuffer = array();
  $postSearchBuffer = array();
  $q = $_GET['q'];
  $q = strtolower($q);
  $q = str_replace("flats","flat",$q);
  $q = str_replace("apartments","flat",$q);
  $q = str_replace("apartment","flat",$q);
  $q = str_replace("homes","home",$q);
  $q = str_replace("plots","plot",$q);
  $q = str_replace("flats","flat",$q);
  $q = str_replace("shops","shop",$q);
  $q = str_replace("bunglows","bunglow",$q);
  $qs = explode(" ",$q);
  $mongo = new MongoClient;
  $db = $mongo->db1;
  $coll = $db->real_estate_asset;
  foreach ($qs as $i) {
   $foundType = $coll->find(array("Type"=>$i));
    foreach ($qs as $k) {
     $foundLocation = $coll->find(array("Type"=>$i,"Contact"=>array("City"=>$k)));
     if (sizeof($foundLocation) > 0) {
      foreach ($foundLocation as $l) {
        array_push($searchBuffer,$l);
      }
     }
    }
   }
   $areaSearchBuffer = array();
   $postSearchBuffer = array();
   $p = "";
   foreach ($searchBuffer as $m) {
    foreach ($qs as $n) {
     if ($n == $m["Area"]) {
      $p = $m["Area"];
      array_push($areaSearchBuffer,$m);
     }
    }
    if ($m["Area"] != $p) {
     array_push($postSearchBuffer,$m);
    }
   }
   if (sizeof($areaSearchBuffer)>0) {
    $searchBuffer = array();
    foreach($areaSearchBuffer as $i) {
     array_push($searchBuffer,$i);
    }
    foreach ($postSearchBuffer as $i) {
     array_push($searchBuffer,$i);
    }
   }

   $areaUnitSearchBuffer = array();
   $postAreaUnitSearchBuffer = array();
   $o = "";
   foreach ($searchBuffer as $m) {
    foreach ($qs as $n) {
     if ($n == $m["Area Unit"]) {
      $o = $m["Area Unit"];
      array_push($areaUnitSearchBuffer,$m);
     }
    }
    if ($m["Area Unit"] != $o) {
     array_push($postAreaUnitSearchBuffer,$m);
    }
   }
   if (sizeof($areaUnitSearchBuffer)>0) {
    $searchBuffer = array();
    foreach ($areaUnitSearchBuffer as $i) {
     array_push($searchBuffer,$i);
    }
    foreach ($postAreaUnitSearchBuffer as $i) {
     array_push($searchBuffer,$i);
    }
   }
    echo json_encode($searchBuffer,JSON_HEX_TAG);
 } elseif ($GET['t'] == "blogs") {
 }
}
?>
