<?php
namespace MVC;

//new MVC\Route(
//              "{controller}/{action}/{id}", 
//               array("{controller}" => "Home","{action}" => "Index", "{id}" => 5),
//               array("{id}" => "int")
//             );

require_once('ConstrainHelper.php');
use MVC\ConstrainHelper as ConstrainHelper;
use MVC\Constrain as Constrain;

class KeyRoute
{
    public $Name;
    public $Index = - 1;
    public $RxForGetValue;
    public $Default;
    public $Constrain;
}

class Route
{    
    
    public $Defaults = array();
    public $Constrains = array();
    public $Url;
    
    public $Keys = array();   
    public $RxPattern = "";
    
    private function SetupDefaultValues($defaults)
    {
        foreach ($defaults as $key => $val)
        {
            $constrain = ConstrainHelper::CreateConstrain($val);
            if (array_key_exists($key, $this->Keys))
            {
                $keyRoute = $this->Keys[$key];            
            }
            else
            {
                $keyRoute = new KeyRoute;
                $keyRoute->Name = $key;                

                $this->Keys[$key] = $keyRoute;
            }            
            $keyRoute->Default = $val;
            $keyRoute->Constrain = $constrain;            
        }
    }
    
    private function SetupConstrains($constrains)
    {
        foreach ($constrains as $key => $val)
        {
            $constrain = new Constrain($val);
            if (array_key_exists($key, $this->Keys))
            {
                $keyRoute = $this->Keys[$key];                
            }
            else
            {
                $keyRoute = new KeyRoute;
                $keyRoute->Name = $key;    
                $this->Keys[$key] = $keyRoute;
            }
            
            $keyRoute->Constrain = $constrain;
        }
    }
    
    function __construct($urlpattern, $defaults = null, $constrains = null) 
    {
        
        if (is_array($defaults))
        {   
            $this->SetupDefaultValues($defaults);
        }
        
        if (is_array($constrains))
        {   
            $this->SetupConstrains($constrains);
        }
        
        $rxKeyPattern = "/\{[\w\d]+\}/";       
        
        $this->Url = $urlpattern;
        //set keys from Url Path
        
        $path = parse_url($urlpattern,PHP_URL_PATH);
        $pathParts = explode('/', $path);                      
        $endIndexPathParts = count($pathParts) - 1;
        $queryString = parse_url($urlpattern,PHP_URL_QUERY);
        $parts = &$pathParts;
        if (!empty($queryString))
        {
            $queryParts = explode('&', $queryString);        
            $parts = array_merge($pathParts, $queryParts);
        }        
        
        $pathRxPattern = "";
        $queryRxPattern = "";
        $rxPattern = "";
        $i = 0;
        foreach ($parts as $part)
        {            
            $rxPatternForGetValue = $part;
            if (preg_match($rxKeyPattern, $part, $array))
            {                
                $key = $array[0];
                
                if (strpos($part,'.')) //url valid chars
                    $part = preg_replace("/\./", "\.", $part);
                
                $rxParamPattern = "([\w\d]+)";
                if ((array_key_exists($key, $this->Keys)) && ($this->Keys[$key]->Constrain != null))
                {
                    $rxParamPattern = $this->Keys[$key]->Constrain->RxPattern;
                }
                $rxPatternForGetValue = preg_replace($rxKeyPattern, $rxParamPattern, $part);                                
                
                if (array_key_exists($key, $this->Keys))
                {
                    $keyRoute = $this->Keys[$key];
                }
                else 
                {
                    $keyRoute = new KeyRoute;
                    $keyRoute->Name = $key;
                    
                    $this->Keys[$key] = $keyRoute;
                }                
                
                $keyRoute->Index = $i;
                $keyRoute->RxForGetValue = "/".$rxPatternForGetValue."/";                
                
            }            
            
            if ((array_key_exists($key, $this->Keys)) && ($this->Keys[$key]->Default != null))
            {   
                    $rxPatternForGetValue = "(".$rxPatternForGetValue.")"."*";                    
            }
                        
            if ($i <= $endIndexPathParts) // PATH: for path use "/" bettewen params
            {                
                $currentRxPattern = &$pathRxPattern; 
                $delimeter = "\/";                
            }
            else // QUERYSTRING: for queryString use "&" bettewen params
            {
                $currentRxPattern = &$queryRxPattern;
                $delimeter = "\&";                    
            }                            
           
            if (!empty($currentRxPattern))
            {                   
                $lastChar = $currentRxPattern[strlen($currentRxPattern) - 1];
                if ($lastChar == '*')
                    $delimeter .= "?";                                                        
            } 
            else
                $delimeter = "";                
            
            $rxPattern = $delimeter.$rxPatternForGetValue;            
            if ($i <= $endIndexPathParts) // PATH: for path use "/" bettewen params
            {                
                $pathRxPattern .= $rxPattern;                    
            }
            else // QUERYSTRING: for queryString use "&" bettewen params
            {
                $queryRxPattern .= $rxPattern;
            }                            
            
            $i++;
        }              
                        
        $rxPattern = $pathRxPattern;
        if (!empty($queryRxPattern))
        {            
            $rxPattern .= "\?".$queryRxPattern;            
        }
        
        $this->RxPattern = "/".$rxPattern."/";
        
    }   
    
    
    public function GetDefaultValue($key)
    {
        if (array_key_exists($key, $this->Defaults))
            return $this->Defaults[$key];        
    }
    
    public function GetTypeConstrains($key)
    {
        if (array_key_exists($key, $this->Constrains))
            return $this->Constrains[$key];        
    }
    
}

?>