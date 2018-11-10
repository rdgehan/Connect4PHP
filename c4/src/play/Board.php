<?php
define('height', 6);
define('width', 7);
class Board{
	public $board;
	
	function __construct(){
	    $board = array();
		$this->file = "data/strategy.txt";
		for($w = 0; $w < width; $w++){
		    array_push($board, array());
		    for($h = 0; $h < height; $h++)
		        array_push($board[$w], 0);//0's represent blank spaces.
		}
	}
	function placeDisk($player, $pos){
	    if ($this->board[0][$pos] != 0 || $pos < 0 || $pos > width) {
	        return array("gameState" => "Invalid");
	    }
	    for($w = width-1; $w >= 0; $w--){
	        if($this->board[$w][$pos] == 0){
	            $this->board[$w][$pos] = $player;
	            $coord = array('x' => $w, 'y' => $pos); // Coordinates to disk placement on board.
	            break;
	        }
	    }
	    $results = isWin($player, $coord);
	    if($results["gameState"] == "Resume")
	        return isDraw();
	    else return $results;
	}
	// This function only looks at the row, col, and two diagonals surrounding the
	// last placed desk to check if it causes the last player to win.
	function isWin($player, $coord){
	    $x = $coord['x']; $y = $coord['y'];
	    $strat = array(1, 0, 0, 1, 1, 1, 1, -1);
	    $winningRow = array(); // Array to keep track of winning row, if there is one.
	    /* This strategy allows the program to more easily checks the row, col, and diagonals.
	     * First pair (1,0) checks vertical column.
	     * Second pair (0,1) checks horizontal row.
	     * Third pair (1,1) checks falling diagonal.
	     * Fourth pair (1,-1) checks rising diagonal.
	     */
	    for($i = 0; $i < 8; $i+=2){
	        $four = 0;
	        // b stands for bound, and it goes from -3 to 3 to check for four in a row.
	        for($b = -3; $b < 4; $b++){
	            $iX = $strat[i]; // Used to help increment x counter using the planned strategy.
	            $iY = $strat[i+1]; // Used to help increment y counter using the planned strategy.
	            if($b*$iX+$x < 0 || $b*$iX+$x > height || $b*$iY+$y < 0 || $b*$iY+$y > width)
	                continue; // If counter is out of bounds, continue.
	            if($this->board[$b*$iX+$x][$b*$iY+$y] == $player){
	                $four++;
	                if($four == 4) return array("gameState" => "isWin", "row" => $winningRow); // Found 4 in a row.
	                array_push($winningRow, $b*$iX+$x, $b*$iY+$y);
	            }
	            else{
	                $four = 0; // Resets counter when row is broken.
	                $winningRow = array();
	            }
	        }
	    }
	    return array("gameState" => "Resume"); // Not a win state.
	}
	// This function looks at the slots in the top row of the board.
	// If any slot checked is empty, the game isn't over yet.
	function isDraw(){
	    for($i = 0; $i < width; $i++){
	        if($this->board[$i][0] != 0)
	            return array("gameState" => "Resume");
	    }
	    return array("gameState" => "isDraw");
	}
}
?>