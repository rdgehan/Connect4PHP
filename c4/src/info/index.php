<?php
$strategies = array("Smart" => "SmartStrategy", "Random" => "RandomStrategy");
define('WIDTH', 7);
define('HEIGHT', 6);
// create a structure: a class or an array (of key-value pairs)
$info = new GameInfo(WIDTH, HEIGHT, array_keys($strategies));
echo json_encode($info);

class GameInfo {
    public $width;
    public $height;
    public $strategies;
    function __construct($width, $height, $strategies) {
        $this->width= $width;
        $this->height = $height;
        $this->strategies = $strategies;
    }
}
?>