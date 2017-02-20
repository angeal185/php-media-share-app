<?php
Class Core
{
	private $config = null;
	
    function IndexPath($else = false, $config = array())
    {
    	$arr = array('htmlspecialchars' => true, 'escape' => true);
    	
    	foreach($arr as $key => $value)
    	{
    		if(!isset($config[$key]))
    		{
    			$config[$key] = $value;
    		}
    	}
    	
    	if(isset($_GET['PATH_INFO']))
    	{
    		if($config['htmlspecialchars'])
    		{
    			$_GET['PATH_INFO'] = htmlspecialchars($_GET['PATH_INFO']);
    		}
    		
    		if($config['escape'])
    		{
    			$_GET['PATH_INFO'] = addslashes($_GET['PATH_INFO']);
    		}
    		
    		$info = explode('/', $_GET['PATH_INFO']);
    		
    		$c = count($info);
    		
    		if($c > 0)
    		{			
    			$class = $else;
    			$method = $else;
    			$parms = array();
    			
    			for($i = 0; $i < $c; ++$i)
    			{
    				$info[$i] = trim($info[$i]);
    				
    				if($info[$i] != null)
    				{
    				    switch($i)
    				    {
    				    	case 0:
    				    	$class = $info[0];
    				    	break;
    				    	
    				    	case 1:
    				    	$method = $info[1];
    				    	break;
    				    	
    				    	default:
    				    	$parms[] = $info[$i];
    				    	break;
    				    }
    				}
    			}
    			
    			if(count($parms) == 0)
    			{
    				$parms = $else;
    			}
    			
    			return array('class' => $this->AlphaNumbers($class), 'method' => $method, 'parms' => $parms);
    		}
    		else
    		{
    			return array('class' => $else, 'method' => $else, 'parms' => $else);
    		}
    	}
    	else
    	{
    		return array('class' => $else, 'method' => $else, 'parms' => $else);
    	}
    }
	
	function OpenControler($class, $method)
	{
		if(!file_exists("application/controllers/".$class.".php"))
		{
			if(!file_exists("application/controllers/".$this->config['ERROR_404'].".php"))
			{
				die("Error! '".$this->config['ERROR_404']."' class not exist!");
			}
		}
		else
		{
			if (!class_exists($class, false)) 
			{
			    require "application/controllers/".$class.".php";
            }
			
		    if (class_exists($class, false)) 
    	    {
		        if(property_exists($class, 'control_status'))
		        {
					if(is_callable(array($class, $method)))
    	    	    {
		    		    $object = new $class();
		    		
		    		    if($object->control_status)
		    		    {				
		    				return $object;
		    			}
		    		}
		    	}
			}
		}
		
		return false;
	}
	
    function OpenMethod($class, $method, $parms)
    {	
	    if(!$method)
		{
			$method = "Index";
		}
		
		if(!$parms)
		{
			$parms = array();
		}
		
		$object = $this->OpenControler($class, $method);
		
		if($object !== false)
		{
			call_user_func_array(array($object, $method), $parms);
		}
		else
		{			
			$object = $this->OpenControler($this->config['ERROR_404'], "Error404");
			
		    call_user_func_array(array($object, "Error404"), array());
		}
    }
	
	function AlphaNumbers($string)
	{
		if(is_bool($string))
		{
			return $string;
		}
		
		return preg_replace("/[^a-z][^0-9]+/", "", $string);
	}
	
	function LoadLibs()
	{
		require 'core/Controler.php';
	}
	
	function base_url()
	{
        if (isset($_SERVER['HTTP_HOST']))
		{
			$base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
			
			$base_url .= '://'. $_SERVER['HTTP_HOST'];
			
			$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
		}
		else
		{
			$base_url = 'http://localhost/';
		}
		
		return $base_url;
    }
	
	function ConnectToDb($host, $user, $pass, $db)
	{
		@mysql_connect($host, $user, $pass) or die('Cant connect to database!');
		
		mysql_select_db($db) or die('Cant select database!');
	}

	function LoadSystem()
	{
		define('URL', $this->base_url());
		
		require "application/config.php";
		
		$this->config = $config;
		
		if($config['mysql'])
		{
			$this->ConnectToDb($config['host'], $config['user'], $config['pass'], $config['db']);
		}
		
		$this->LoadLibs();
		
		$info = $this->IndexPath(false);
		
		if(!$info['class'])
		{
			$this->OpenMethod($config['HOME_CLASS'], $info['method'], $info['parms']);
		}
		else
		{
			$this->OpenMethod($info['class'], $info['method'], $info['parms']);
		}
	}
}
?>