<?php

Class Home extends Controler
{	
    public $Info;
	
	function __construct()
	{
        $this->Module('Info');
		
		$this->Info = new Info;
		
		$this->Info->LoginCheck();
    }
	
    function Index()
	{		
	    $this->Videos('most-popular');
	}
    
    function Upload()
	{		
	    $this->LoadHead();
		
		if($this->Info->login['status'])
		{
			$categories = $this->Info->CategoriesSelect();
			
			$this->View('upload', array('categories' => $categories));
		}
		else
		{
			header("Location: ".URL."home/login?next=home/Upload");
		}
		
		$this->LoadFooter();
	}
	
	function PM($start = 0)
	{
		$this->LoadHead(array('page_now' => 'home/PM'));
		
		if(!is_numeric($start))
		{
			$start = 0;
		}
		
		if($this->Info->login['status'])
		{
			$PerPage = 20;
			
			$this->View("pm", array('list' => $this->Info->PM_List($start, $PerPage), 'StartPM' => $start, 'CountPM' => $this->Info->PM_Count(), 'PerPage' => $PerPage));
		}
		else
		{
			header("Location: ".URL."home/login?next=home/PM");
		}
		
		$this->LoadFooter();
	}
	
	function PM_send($to = null)
	{
		$this->LoadHead(array('page_now' => 'home/PM'));
		
		if($this->Info->login['status'])
		{
			$info = $this->Info->PM_send();
			
			$to = $this->Info->Escape($to);
			
			$this->View("pm_send", array("msg" => $info, "to" => $to));
		}
		else
		{
			header("Location: ".URL."home/login?next=home/PM_send");
		}
		
		$this->LoadFooter();
	}
	
	function PM_message($message_id = 0)
	{
		$this->LoadHead(array('page_now' => 'home/PM'));
		
		if($this->Info->login['status'])
		{
			if($message_id == 0 || !is_numeric($message_id))
			{
				$this->View('error404');
			}
			else
			{
			    $info = $this->Info->PM_Info($message_id);
			    
			    if($info !== false)
			    {
					if($info['view'] == 0)
					{
						mysql_query("UPDATE MS_pm SET view = '1' WHERE id = '".$message_id."' ;");
					}
					
			    	$this->View("pm_message", array('info' => $info));
			    }
			    else
			    {
			    	header("Location: ".URL."home/login");
			    }
			}
		}
		else
		{
			header("Location: ".URL."home/login?next=home/PM_message/".$message_id);
		}
		
		$this->LoadFooter();
	}
	
	private function LoadHead($arr = array())
	{
		$this->View('header', $arr);
		
		$this->View('menu', $arr);
	}
	
	private function LoadFooter()
	{
		$this->View('footer');
	}
	
	function LOG($page = 1)
	{
		$this->LoadHead();
		
		$perPage = 20;
		
		$StartAndPage = $this->Info->StartAndPage($page, $perPage);
		
		if($this->Info->login['status'])
		{
			$log = $this->Info->GetLog($StartAndPage['start'], $perPage);
			
			$pagination = $this->Info->Pagination($StartAndPage['page'], ceil($this->Info->CountLog()/$perPage), URL.'home/LOG/{i}');
			
			$this->View("log", array('log' => $log, 'pagination' => $pagination));
		}
		else
		{
			header("Location: ".URL."home/login?next=home/LOG");
		}
		
		$this->LoadFooter();
	}
	
	function Forgot($user = false, $hash = false)
	{
		if(!$this->Info->login['status'])
		{
			if($user !== false && $hash !== false)
			{
				if($this->Info->ValidForgotHash($user, $hash))
				{
					$msg = $this->Info->ForgotChangePost($user);
					
					$this->view('forgot-reset', array('msg' => $msg));
				}
				else
				{
					header("Location: ".URL."home/Forgot/");
				}
			}
			else
			{
				$msg = $this->Info->ForgotPOST();
			
			    $this->view('forgot', array('msg' => $msg));
			}
		}
	}
	
	function MyFeed($page = 1)
	{
		$this->LoadHead(array('page_now' => 'home/MyFeed'));
		
		$perPage = 18;
		
		$StartAndPage = $this->Info->StartAndPage($page, $perPage);
		
		if($this->Info->login['status'])
		{
			$pagination = $this->Info->Pagination($StartAndPage['page'], ceil($this->Info->MyFeedCount()/$perPage), URL.'home/MyFeed/{i}');
			
			$this->View("videos", array('videos_list' => $this->Info->MyFeed($StartAndPage['start'], $perPage), 'title' => 'My Feed', 'SubTitle' => 'My Feed', 'PageIcon' => 'pe-7s-paper-plane', 'PageDescription' => 'Whenever you follow someone, their videos will be shown here.', 'pagination' => $pagination));
		}
		else
		{
			header("Location: ".URL."home/login?next=home/MyFeed");
		}
		
		$this->LoadFooter();
	}
	
	function Video($hash = false)
	{
		$this->LoadHead();
		
		$info = $this->Info->Video($hash);
		
		if($info != false)
		{
			$this->View('video', $info);
		}
		else
		{
			$this->View('error404');
		}
		
		$this->LoadFooter();
	}
	
	function Administrator()
	{
		$this->LoadHead(array('page_now' => 'home/Administrator'));
		
		if($this->Info->login['isAdmin'])
		{
			$msg = $this->Info->MyVideosPost();
			
			if(!$msg['error'] && !$msg['success'])
			{
				$msg = $this->Info->AddCategoryPOST();
			}
			
			$categoriesTable = $this->Info->CategoriesTable();
			
			$categoriesSelect = $this->Info->CategoriesSelect();
			
			$this->View('administrator/index', array('msg' => $msg, 'categoriesTable' => $categoriesTable, 'categoriesSelect' => $categoriesSelect));
		}
		else
		{
			$this->View('error404');
		}
		
		$this->LoadFooter();
	}
	
	function Videos($category = null, $page = 1)
	{
		$this->LoadHead(array('page_now' => 'home/videos'));
		
		$perPage = 18;
		
		$StartAndPage = $this->Info->StartAndPage($page, $perPage);
		
		switch(strtoupper($category))
		{
			case "MOST-POPULAR":
			$pagination = $this->Info->Pagination($StartAndPage['page'], ceil($this->Info->CountVideos()/$perPage), URL.'home/videos/'.$category.'/{i}');
			
			$this->View("videos", array('videos_list' => $this->Info->MostPopularsVideos($StartAndPage['start'], $perPage), 'title' => 'Most Popular Videos', 'SubTitle' => 'Most Popular', 'PageDescription' => 'Browse and watch most popular site videos (based on watches)', 'PageIcon' => 'pe-7s-look', 'pagination' => $pagination));
			break;
			
			case "BEST-RATED":
			$pagination = $this->Info->Pagination($StartAndPage['page'], ceil($this->Info->CountVideos()/$perPage), URL.'home/videos/'.$category.'/{i}');
			
			$this->View("videos", array('videos_list' => $this->Info->BestRatedVideos($StartAndPage['start'], $perPage), 'title' => 'Best Rated Videos', 'SubTitle' => 'Best Rated', 'PageDescription' => 'Browse and watch best rated site videos (based on likes)', 'PageIcon' => 'pe-7s-gleam', 'pagination' => $pagination));
			break;
			
			case "RECOMMENDED":
			if($this->Info->login['status'])
			{
				$pagination = $this->Info->Pagination($StartAndPage['page'], ceil($this->Info->CountRecommendedVideos()/$perPage), URL.'home/videos/'.$category.'/{i}');
				
				$this->View("videos", array('videos_list' => $this->Info->RecommendedVideos($StartAndPage['start'], $perPage), 'title' => 'Recommended Videos', 'SubTitle' => 'Recommended', 'PageDescription' => 'Browse and watch recommended for you videos', 'PageIcon' => 'pe-7s-airplay', 'pagination' => $pagination));
			}
			else
			{
				header("Location: ".URL."home/login?next=home/videos/recommended");
			}
			break;
			
			case "RANDOM":
			$this->View("videos", array('videos_list' => $this->Info->RandomVideos(), 'title' => 'Random Videos', 'SubTitle' => 'Random', 'PageDescription' => 'Browse and watch random selected site videos', 'PageIcon' => 'pe-7s-shuffle', 'pagination' => null));
			break;
			
			default:
			$this->View('error404');
			break;
		}
		
		$this->LoadFooter();
	}
	
	function MyVideos($page = 1)
	{
		$this->LoadHead(array('page_now' => 'home/MyVideos'));
		
		$perPage = 18;
		
		$StartAndPage = $this->Info->StartAndPage($page, $perPage);
		
		if($this->Info->login['status'])
		{
			$msg = $this->Info->MyVideosPost();
			
			$pagination = $this->Info->Pagination($StartAndPage['page'], ceil($this->Info->MyVideosCount()/$perPage), URL.'home/MyVideos/{i}');
			
			$this->View("myvideos", array('videos_list' => $this->Info->MyVideos($StartAndPage['start'], $perPage), 'msg' => $msg, 'pagination' => $pagination));
		}
		else
		{
			header("Location: ".URL."home/login?next=home/MyVideos");
		}
		
		$this->LoadFooter();
	}
	
	function Search($search = null, $method = 1, $page = 1)
	{
		if($method != 1 && $method != 2)
		{
			$method = 1;
		}
		
		$this->LoadHead(array('search' => htmlspecialchars($search), 'method' => $method));
		
		if($search == null)
		{
			$search = "Please write text!";
		}
		
		$perPage = 18;
		
		$StartAndPage = $this->Info->StartAndPage($page, $perPage);
		
		$pagination = $this->Info->Pagination($StartAndPage['page'], ceil($this->Info->SearchCount($search, $method)/$perPage), URL.'home/Search/'.$search.'/'.$method.'/{i}');
		
		$this->View("search", array('results_list' => $this->Info->Search($StartAndPage['start'], $perPage, $search, $method), 'pagination' => $pagination));
		
		$this->LoadFooter();
	}
	
	function Settings()
	{
		$this->LoadHead();
		
		if($this->Info->login['status'])
		{
			$update = $this->Info->SettingsPost();
			
			$this->View('settings', array('msg' => $update));
		}
		else
		{
			header("Location: ".URL."home/login?next=home/Settings");
		}
		
		$this->LoadFooter();
	}
	
	function Profile($username = null, $page = 1)
	{
		$this->LoadHead();
		
		if($username != null && $this->Info->login['status'])
		{
			$perPage = 18;
			
			$StartAndPage = $this->Info->StartAndPage($page, $perPage);
			
			$info = $this->Info->GetProfile($username, $StartAndPage['start'], $perPage);
			
			if($info['id'] !== null)
			{
				$this->View('profile', array("profile" => $info, 'username' => $username, 'perPage' => $perPage, 'page' => $StartAndPage['page'], 'count_videos' => $this->Info->CountVideos($info['id']), 'perPage' => $perPage, 'link' => URL.'home/Profile/'.$username.'/{i}'));
			}
			else
			{
				$this->View("error404");
			}
		}
		else
		{
			header("Location: ".URL."home/login?next=home/Profile/".$username);
		}
		
		$this->LoadFooter();
	}
	
	function LogOut()
	{
		$this->Info->LogOut();
		
		$this->Info->Log('Logout from account.');
		
		header('Location: '.URL.'home/login');
	}
	
	function Login()
	{
		if(!$this->Info->login['status'])
		{
			$check = $this->Info->LoginPost();
			
		    if($check['success'])
		    {
				$next = 'home/videos/most-popular';
				
				if(isset($_GET['next']))
				{
					$next = $_GET['next'];
				}
				
				$this->Info->Log('Login to account.');
				
		    	header("Location: ".URL.$next);
		    }
		    
		    $this->View('login', array('msg' => $check));
		}
		else
		{
			header("Location: ".URL."home/videos/most-popular");
		}
	}
	
	function Register()
	{
		if(!$this->Info->login['status'])
		{
			$check = $this->Info->RegisterPOST();
		    
		    if($check === true)
		    {
		    	header("Location: ".URL."home/login");
		    }
		    
		    $this->View('register', array('msg' => $check));
		}
		else
		{
			header("Location: ".URL."home/videos/most-popular");
		}
	}
	
	function Error404()
	{
		$this->LoadHead();
		
		$this->View('error404');
		
		$this->LoadFooter();
	}
}

?>