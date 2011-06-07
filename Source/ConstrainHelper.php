<?php
namespace MVC;

class Constrain
{
    public $Type;
    public $RxPattern;
    
    function __construct($type)
    {
        $this->Type = $type;
        $this->RxPattern = ConstrainHelper::GetRxPattern($type);
    }
    
    public function ApplyToValue($value)
    {
        if ($this->Type == "int")
        {
            return (int)$value;
        }
        return $value;
    }    
    
}

class ConstrainHelper 
{
    public static function CreateConstrain($value)
    {
        if (is_bool($value))
            return new Constrain("bool");
        else if (is_int($value))
            return new Constrain("int");
    }   
    
    
    public static function GetRxPattern($type)
    {
        if ($type == "int")
        {
            return "(\d+)";
        }
        else if ($type == "bool")
        {
            return "(true|false|0|1)";
        }
        return "([\w\d]+)";
    }
}

?>
