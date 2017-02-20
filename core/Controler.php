<?php

Class Controler
{
	public $control_status = true;
	
	protected function View($view, $data = false)
	{
		if(is_array($data))
		{
			extract($data, EXTR_PREFIX_SAME, "wddx");
		}
		
		if(file_exists('application/views/'.$view.'.php'))
		{
			include 'application/views/'.$view.'.php';
			
			return true;
		}
		
		return false;
	}
	
	protected function Module($module)
	{
		if(file_exists('application/modules/'.$module.'.php'))
		{
			include 'application/modules/'.$module.'.php';
			
			return true;
		}
		
		return false;
	}
	
    protected function Escape($arr)
	{
		if(is_array($arr))
		{
			foreach($arr as $key=>$value)
			{
				if(is_array($arr[$key]))
				{
					$arr[$key] = $this->Escape($arr[$key]);
				}
				else
				{
					$arr[$key] = htmlspecialchars(mysql_real_escape_string($value));
				}
			}
			return $arr;
		}
		else
		{
			return htmlspecialchars(mysql_real_escape_string($arr));
		}
	}
}

?>