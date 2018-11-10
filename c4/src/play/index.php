<?php
// File: new/index.php
if(!array_key_exists('pid', $_GET))
    reject("Pid not specified");
if(!array_key_exists('move', $_GET))
    reject("Move not specified");
$pid = $_GET['pid'];
$move = $_GET['move'];
$file = "../../writable/".$pid.".txt";
if(!file_exists($file))
    reject("Unknown pid");
$contents = json_decode($file);
else if(array_key_exists("new game", $contents)){
    $strategy = $contents["new game"];
    unlink($file);
    fopen("../../writable/".$pid.".txt", "w") or die("Unable to open file.");
    $game = new Game($strategy);
}
else{
    $json = file_get_contents($file);
    $game = Game::fromJsonString($json);
}
$playerMove = $game->makePlayerMove($move, "Player");
$isWin = false;
$isDraw = false;
$row = array();
switch($playerMove["gameState"]){
    case "Resume":
        break;
    case "isWin":
        $isWin = true;
        $row = $playerMove["row"];
        break;
    case "isDraw":
        $isDraw = true;
        break;
    case "Invalid":
        reject("Invalid slot".$move);
        break; 
}
if ($isWin || $isDraw) {
    unlink($file);
    $response = array("response" => true, 
                      "ack_move" => array("slot" => $move,
                                          "isWin" => $isWin,
                                          "isDraw" => $isDraw,
                                          "row" => $row));
    echo json_encode($response);
    exit;
}
$opponentMove = $game->makeOpponentMove();
$oppMove = $opponentMove["cpuMove"];
$oppWin = false;
$oppDraw = false;
$oppRow = array();
switch($opponentMove["gamestate"]){
    case "Resume":
        break;
    case "isWin":
        $oppWin = true;
        $oppRow = $opponentMove["row"];
    case "isDraw":
        $oppDraw = true;
}
$response = array("response" => true,
                  "ack_move" => array("slot" => $move,
                                      "isWin" => $isWin,
                                      "isDraw" => $isDraw,
                                      "row" => $row),
                  "move" => array("slot" => $oppMove,
                                  "isWin" => $oppWin,
                                  "isDraw" => $oppDraw,
                                  "row" => $oppRow));

if (storeState($file, $game->toJsonString()))
    echo json_encode($response);
else echo createResponse("Failed to store game data");

function reject($reason){
    echo json_encode(array("response" => false, "reason" => $reason));
    exit;
}
?>