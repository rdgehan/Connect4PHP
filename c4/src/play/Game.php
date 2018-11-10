<?php
class Game {
    public $board;
    public $strategy;
    public $CPU;
    
    function __construct($strategy){
        $this->strategy = $strategy;
        //$this->board = new Board();
        if($strategy == "Smart" || $strategy == "SmartStrategy")
            $this->CPU = new SmartStrategy($this->board);
        else
            $this->CPU = new RandomStrategy($this->board);
    }
    static function fromJsonString($json) {
        $obj = json_decode($json); // instance of stdClass
        $strategy = $obj->{"strategy"};
        $board = $obj->{"board"};
        $game = new Game();
        $game->board = Board::fromJson($board);
        $name = $strategy->{"name"};
        $game->strategy = $name::fromJson($strategy);
        $game->strategy->board = $game->board;
        return $game;
    }
    function makePlayerMove($move, $player){
        return $this->board->placeDisk($move, $player);
    }
    function makeOpponentMove(){
        $cpuMove = $this->CPU->pickSlot();
        $results = $this->board->placeDisk($cpuMove, "Opponent");
        array_push($results, "cpuMove" -> $cpuMove);
        return $results;
    }
}
?>