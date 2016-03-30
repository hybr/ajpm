<?php
if ($_SERVER['request_method'] = "get") {
  $searchBuffer = array();
  $location = $_GET['location'];
  $area = $_GET['area'];
  $areaUnit = $_GET['areaunit'];
  $landType = $_GET['landtype'];
  $accessories = $_GET['accessories'];
  $rooms = $_GET['rooms'];
  $mongo = new MongoClient;
  $db = $mongo->db1;
  $coll = $db->real_estate_asset;
  $found = $coll->find(array("Type"=>$landType,"Area"=>$area));
  foreach ($found as $doc) {
   if ($location == $doc["Contact"]["City"]) {
    array_push($searchBuffer, array(
     "Location" => $doc["Contact"]["City"],
     "Area" => $doc["Area"],
     "Area Unit" => $doc["Area Unit"],
     "Accessories" => $doc["Accessory"],
     "Rooms" => $doc["Rooms"]
    ));
   }
  }
   echo json_encode($searchBuffer,JSON_HEX_TAG);
}
?>
