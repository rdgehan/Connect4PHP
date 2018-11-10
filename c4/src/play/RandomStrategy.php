<?php
class RandomStrategy extends MoveStrategy{
    function makeStrategicMove(){}
    
    public function pickSlot(){
        $valid = false;
        while(!$valid){
            $slot = rand(0,6);
            if($this->board->board[0][$slot] != 0)
                $valid = true;
        }
        return $slot;
    }

}

?>
