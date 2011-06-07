<?php

//routes: array("Default", // Route name
//              "{controller}/{action}/{id}", // URL with parameters
//              array { controller = "Home", action = "Index", id = UrlParameter.Optional } // Paramete
namespace MVC;
require_once 'Route.php';
use MVC\Route as Route;

class UrlRouteModule
{
    private $Routes; 
    
    function __construct($routes) 
    {
        $this->Routes = $routes;
    }
    
    public function GetRouteData($url)
    {
        $route = $this->GetRoute($url);
        $routeData = $this->GetRouteDataForRoute($route, $url);
        return $routeData;
    }
    
    private function GetRoute($url)
    {
        $res = explode('/', $url);
        $countUrlParts = count($res);
        foreach($this->Routes as $route)
        {            
            $parts = explode('/',$route->Url);
            $countRouteParts = count($parts);
            
            //if ($countRouteParts == $countUrlParts)
           // {
                $r = (preg_match($route->RxPattern, $url, $array));
                if ($r)
                {
                    return $route;
                }
           //     else
           //     {
           //         throw new \Exception("Route don't found for $url");
           //     }
            //}
            
            
        }
        
        $lastRoute = $this->Routes[count($this->Routes) - 1];
        return $lastRoute;
        
    }   
    
    private function GetRouteDataForRoute(Route $route, $url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        $pathParts = explode('/', $path);
        
        $urlParts = $pathParts;
        $query = parse_url($url, PHP_URL_QUERY);
        if (!empty ($query))
        {
            $queryParts = explode('&', $query);
        
            $urlParts = array_merge($pathParts, $queryParts);
        }
        
        $routedata = array();
        
        foreach ($route->Keys as $key => $obj)
        {
            $value = $obj->Default;
            $j = 0;
            foreach ($urlParts as $part)
            {
                $r = preg_match($obj->RxForGetValue, $part, $array);
                
                if ($r)
                {                    
                    $value = $array[count($array)-1];
                    
                    if ($obj->Constrain != null)
                        $value = $obj->Constrain->ApplyToValue($value);
                    
                    array_splice($urlParts, $j, 1);                    
                    break;
                }
                $j++;
            }
            
            /*if ( (array_key_exists($obj->Index, $urlParts)) && (!empty($urlParts[$obj->Index])) )
            {
                $part = $urlParts[$obj->Index];
                preg_match($obj->RxForGetValue, $part, $array);
                
                $value = $array[count($array)-1];
                
                if ($obj->Constrain != null)
                    $value = $obj->Constrain->ApplyToValue($value);
                
            } */           
            
            $routedata[$key] = $value;
        }
        
        
        return $routedata;
    }   
   
}

?>