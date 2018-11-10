<?php // index.php
require_once '../play/Game.php';
define('STRATEGY', "strategy"); // constant
$strategies = array("Smart" => "SmartStrategy", "Random" => "RandomStrategy"); // supported strategies
if (!array_key_exists(STRATEGY, $_GET)) {
    echo json_encode(array("response" => false, "reason" => "Strategy not given"));
}
else{
    $strategy = $_GET[STRATEGY];
    foreach($strategies as $key => $strat){
        if($strategy == $strat || $strategy == $key){
            $pid = uniqid();
            $file = fopen("../../writable/".$pid.".txt", "w") or die("Unable to open file.");
            $arr = array("new game" => $strategy);
            fwrite($file, $arr);
            echo json_encode(array("response" => true, "pid" => $pid));
        }
    }
    if(!$pid){
        echo json_encode(array("response" => false, "reason" => "Unknown strategy"));
    }
}
?>