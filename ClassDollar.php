<?php 

class Dollar
{
     //This really shouldn't be public, but it's consistent with Beck's example
    public $amount;
    
    function __construct($val) {
        $this->amount = $val;
    }
    
    function times($multiplier) {
        $this->amount *= $multiplier;
    }
    
}
?>