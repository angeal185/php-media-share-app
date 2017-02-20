<?php
Class Info
{	
	public $login = array('status' => false, 'id' => false, 'FirstName' => false, 'LastName' => false, 'username' => false, 'profile' => 'default.png', 'password' => false, 'cover' => false, 'email' => false, 'isAdmin' => false);
	
	public $error = null;
	
	public $security = false;
	
	function LoginCheck()
	{		
	    session_start();
		
		if(isset($_SESSION['id']))
		{
			$this->login['id'] = $_SESSION['id'];
			
			$this->security = $_SESSION['security'];
			
			$this->login['status'] = true;
			
			if($this->AccountInformation($this->login['id']) === false)
			{
				header("Location: ".URL."home/login");
			}
			
			return true;
		}

		return false;
	}
	
	function CheckSecurity($value = 'security')
	{
		if(isset($_POST[$value]))
		{
			if($_POST[$value] == $this->security)
			{
				return true;
			}
		}
		
		return false;
	}
	
	function AccountInformation($id)
	{
		$info = mysql_query("SELECT MS_users.*,MS_administrators.id as isAdmin FROM MS_users LEFT JOIN MS_administrators ON MS_administrators.user_id = MS_users.id WHERE MS_users.id='".$id."' ;");
		
		if(mysql_num_rows($info) == 1)
		{
			$info = mysql_fetch_array($info);
			
			$this->login['status'] = true;
			
			$this->login['id'] = $info['id'];
			
			$this->login['FirstName'] = $info['FirstName'];
			
			$this->login['LastName'] = $info['LastName'];
			
			$this->login['username'] = $info['username'];
			
			$this->login['profile'] = $info['profile'];
			
			$this->login['cover'] = $info['cover'];
			
			$this->login['password'] = $info['password'];
			
			$this->login['email'] = $info['email'];
			
			if($info['isAdmin'] != NULL)
			{
				$this->login['isAdmin'] = true;
			}
		}
		else
		{
			$this->LogOut();
			
			return false;
		}
	}
	
	function CategoriesSelect()
	{
		$data = null;
		
		$list = mysql_query("SELECT * FROM MS_categories;");
		
		while($info = mysql_fetch_array($list))
		{
			$data .= '<option value="'.$info['id'].'">'.$info['category'].'</option>';
		}
		
		return $data;
	}
	
	function CategoriesTable()
	{
		$data = null;
		
		$list = mysql_query("SELECT MS_categories.*, COUNT(MS_videos.id) as videosCount FROM MS_categories LEFT JOIN MS_videos ON MS_videos.category_id = MS_categories.id GROUP BY MS_categories.id ORDER BY MS_categories.id;");
		
		while($info = mysql_fetch_array($list))
		{
			$data .= '<tr><td>'.$info['id'].'</td><td>'.$info['category'].'</td><td>'.$info['videosCount'].'</td><td><button type="button" onclick="AdminDeleteCategory(this, '.$info['id'].')" class="btn w-xs btn-primary btn-sm"><i class="fa fa-trash"></i> DELETE THIS CATEGORY</button></td></tr>';
		}
		
		return $data;
	}
	
	function CategoryExist($id, $name = false)
	{
		if($name)
		{
			$check = mysql_query("SELECT id FROM MS_categories WHERE category = '".$id."' ;");
		}
		else
		{
			$check = mysql_query("SELECT id FROM MS_categories WHERE id = '".$id."' ;");
		}
		
		if(mysql_num_rows($check) == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function GenerateMenu($menu, $page_now)
	{
		foreach($menu as $key => $value)
		{
			if(isset($value['SUBMENU']))
			{
				if(strtoupper($page_now) == strtoupper($value['href']))
				{
					echo '<li class="active"><a href="'.URL.$value['href'].'"><span class="nav-label">'.$key.'</span><span class="fa arrow"></span> </a>';
				}
				else
				{
					echo '<li><a href="'.URL.$value['href'].'"><span class="nav-label">'.$key.'</span><span class="fa arrow"></span> </a>';
				}
				
				echo '<ul class="nav nav-second-level">';
				
				$this->GenerateMenu($value['SUBMENU'], $page_now);
				
				echo '</ul>';
			}
			else
			{
				$show = true;
				
				if(isset($value['OnLogin']) && !$this->login['status'])
				{
					$show = false;
				}
				
				if(isset($value['OnLogOut']) && $this->login['status'])
				{
					$show = false;
				}
				
				if(isset($value['OnAdmin']) && !$this->login['isAdmin'])
				{
					$show = false;
				}
				
				if($show)
				{
					if(strtoupper($page_now) == strtoupper($value['href']))
					{
						echo '<li class="active"><a href="'.URL.$value['href'].'"> <span class="nav-label">'.$key.'</span></a></li>';
					}
					else
					{
						echo '<li><a href="'.URL.$value['href'].'"> <span class="nav-label">'.$key.'</span></a></li>';
					}
				}
			}
		}
	}
	
	function LoginPost()
	{
		$msg = array('error' => false, 'success' => false);
		
		if(isset($_POST['user']) && isset($_POST['pass']))
		{
			$user = $this->Escape($_POST['user']);
			
			$pass = $this->HashPass($_POST['pass']);
			
			$check = mysql_query("SELECT id FROM MS_users WHERE username='".$user."' AND password = '".$pass."' ;");
			
			if(mysql_num_rows($check) == 1)
			{
				$info = mysql_fetch_array($check);
				
				$_SESSION['id'] = $info['id'];
				
				$this->login['id'] = $_SESSION['id'];
				
				$_SESSION['security'] = $this->HashPass(time().rand(1000000000, 9999999999));
				
				$msg['success'] = true;
			}
			else
			{
				$msg['error'] = 'Wrong username or password!';
			}
		}
		
		return $msg;
	}
	
	function AutoLinkText($text)
    {
       return preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1" target="new_blank">$1</a>', $text);
    }

	function RegisterPOST()
	{
		$msg['error'] = false;
		$msg['success'] = false;
		
		if(isset($_POST['FirstName']) && isset($_POST['LastName']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_re']) && isset($_POST['email']) && isset($_POST['email_re']))
		{
			$FirstName = $this->Validate($_POST['FirstName'], array('min_len' => 2, 'max_len' => 20, 'OnlyNumAlpha' => true));
			
			if($FirstName === 1 || $FirstName === 2)
			{
				$msg['error'] = "Invalid first name.";
				return $msg;
			}
			
			$LastName = $this->Validate($_POST['LastName'], array('min_len' => 2, 'max_len' => 20, 'OnlyNumAlpha' => true));
			
			if($LastName === 1 || $LastName === 2)
			{
				$msg['error'] = "Invalid last name.";
				return $msg;
			}
			
			$user = $this->Validate($_POST['username'], array('min_len' => 4, 'max_len' => 18, 'OnlyNumAlpha' => true));
			
			switch($user)
			{
				case 1:
				$msg['error'] = "Username must be between 4 and 40 symbols.";
				return $msg;
				break;
				
				case 2:
				$msg['error'] = "Username must contain only letters and numbers.";
				return $msg;
				break;
			}
			
			$pass = $this->Validate($_POST['password'], array('min_len' => 6, 'max_len' => 18));
			
			$password_re = $this->Escape($_POST['password_re']);	
			
			if($pass === 1)
			{
				$msg['error'] = "Password must be between 6 and 18 symbols.";
				return $msg;
			}

			if($pass != $password_re)
			{
				$msg['error'] = "Password does not match the confirm password";
				return $msg;
			}
			
			$email = $this->Validate($_POST['email'], array('IsEmail' => true));
			
			if($email === 3)
		    {
				$msg['error'] = "Invalid Email!";
				return $msg;
			}
			
			$email_re = $_POST['email_re'];
			
			if($email != $email_re)
			{
				$msg['error'] = "Emails do not match!";
				return $msg;
			}
			
			if($this->UserExist($user) !== false)
			{
				$msg['error'] = "Username already exist.";
				return $msg;
			}
			
			mysql_query("INSERT INTO MS_users (username, password, profile, cover, email, FirstName, LastName, date) VALUES ('".$user."', '".$this->HashPass($pass)."', 'default.png', 'default.png', '".$email."', '".$FirstName."', '".$LastName."', '".time()."') ;");
			
			$msg['success'] = "Registration Complete.";
		}
		
		return $msg;
	}
	
	function UserExist($user)
	{
		$check = mysql_query("SELECT id,cover,profile FROM MS_users WHERE username = '".$user."' ;");
		
		if(mysql_num_rows($check) == 1)
		{
			$check = mysql_fetch_array($check) ;
			
			return $check;
		}
		
		return false;
	}
	
	function DeleteCategory($categoryId)
	{
		$videos = mysql_query("SELECT id,image_file,video_file FROM MS_videos WHERE category_id = '".$categoryId."' ;");
		
		$delete_videos = null;
		
		while($info = mysql_fetch_array($videos))
		{
			if(file_exists('videos/'.$info['video_file']))
			{
				unlink('videos/'.$info['video_file']);
			}
			
			if(file_exists('videos/'.$info['image_file']))
			{
				unlink('videos/'.$info['image_file']);
			}
			
			if($delete_videos != null)
			{
				$delete_videos .= ",";
			}
			
			$delete_videos .= "'".$info['id']."'";
		}
		
		mysql_query("DELETE FROM MS_comments WHERE video_id IN (".$delete_videos.") ;");
		mysql_query("DELETE FROM MS_likes WHERE video_id IN (".$delete_videos.") ;");
		mysql_query("DELETE FROM MS_videos WHERE id IN (".$delete_videos.") ;");
		mysql_query("DELETE FROM MS_views WHERE video_id IN (".$delete_videos.") ;");
		mysql_query("DELETE FROM MS_categories WHERE id = '".$categoryId."' ;");
	}
	
	function AddCategoryPOST()
	{
		$msg = array('error' => false, 'success' => false);
		
		if(isset($_POST['add_category']) && $this->CheckSecurity())
		{
			$category_name = $this->Escape($_POST['add_category']);
			
			if(trim($category_name) != null)
			{
				if(!$this->CategoryExist($category_name, true))
			    {
			    	mysql_query("INSERT INTO MS_categories (category) VALUES ('".$category_name."') ;");
			    	
			    	$msg['success'] = 'Category added successful.';
			    }
			    else
			    {
			    	$msg['error'] = 'Category already exist.';
			    }
			}
			else
			{
				$msg['error'] = 'Please write valid category.';
			}
		}
		
		return $msg;
	}
	
	function DeleteUser($user)
	{
		$videos = mysql_query("SELECT image_file,video_file FROM MS_videos WHERE user_id = '".$user['id']."' ;");
		
		while($info = mysql_fetch_array($videos))
		{
			if(file_exists('videos/'.$info['video_file']))
			{
				unlink('videos/'.$info['video_file']);
			}
			
			if(file_exists('videos/'.$info['image_file']))
			{
				unlink('videos/'.$info['image_file']);
			}
		}
		
		if(file_exists('profiles/cover/'.$user['cover']) && $user['cover'] != "default.png")
		{
			unlink('profiles/cover/'.$user['cover']);
		}
		
		if(file_exists('profiles/avatar/'.$user['profile']) && $user['profile'] != "default.png")
		{
			unlink('profiles/avatar/'.$user['profile']);
		}
		
		mysql_query("DELETE FROM MS_comments WHERE user_id = '".$user['id']."' ;");
		mysql_query("DELETE FROM MS_likes WHERE user_id = '".$user['id']."' ;");
		mysql_query("DELETE FROM MS_follows WHERE follower_id = '".$user['id']."' OR followers_id = '".$user['id']."' ;");
		mysql_query("DELETE FROM MS_log WHERE user_id = '".$user['id']."' ;");
		mysql_query("DELETE FROM MS_videos WHERE user_id = '".$user['id']."' ;");
		mysql_query("DELETE FROM MS_pm WHERE from_id = '".$user['id']."' OR to_id = '".$user['id']."' ;");
		mysql_query("DELETE FROM MS_users WHERE id = '".$user['id']."' ;");
	}
	
	function GetProfile($username, $start, $perPage)
	{
		$username = $this->Escape($username);
		
		$check = mysql_query("SELECT MS_users.id,MS_users.FirstName,MS_users.LastName,MS_users.profile,MS_users.cover,COUNT(DISTINCT MS_follows.id) AS follows_count,COUNT(DISTINCT MS_videos.id) AS videos_count,COUNT(DISTINCT IF(MS_follows.follower_id = '".$this->login['id']."', true, NULL)) AS following FROM MS_users LEFT JOIN MS_follows ON MS_follows.followers_id = MS_users.id LEFT JOIN MS_videos ON MS_videos.user_id = MS_users.id WHERE MS_users.username = '".$username."'");
		
		if(mysql_num_rows($check) > 0)
		{
			$check = mysql_fetch_array($check) ;
			
			$check['playlist'] = mysql_query("SELECT MS_videos.id,MS_videos.title,MS_videos.image_file,MS_videos.date,COUNT(DISTINCT MS_views.id) AS views FROM MS_videos LEFT JOIN MS_views ON MS_views.video_id = MS_videos.id WHERE MS_videos.user_id = '".$check['id']."' GROUP BY MS_videos.id LIMIT ".$start.",".$perPage." ;");
			
			$check['comment_list'] = mysql_query("SELECT MS_comments.comment,MS_comments.date,MS_videos.title,MS_videos.id FROM MS_comments LEFT JOIN MS_videos ON MS_videos.id = MS_comments.video_id WHERE MS_comments.user_id = '".$check['id']."' ORDER BY MS_comments.date desc LIMIT 10;");
			
			return $check;
		}
		
		return false;
	}
	
	function HashPass($pass)
	{
		for($i = 0; $i <= 6; ++$i)
		{
			$pass = base64_encode($pass);
		}
		
		for($i = 0; $i <= 3; ++$i)
		{
			$pass = md5($pass);
		}
		
		return $pass;
	}
	
	function LogOut()
	{
		session_destroy();
		
		$this->login['status'] = false;
	}
	
	function Video($hash)
	{
		if($hash !== false)
		{
			$info = $this->GetVideo($hash);
			
            if($info !== false)
            {
				if($info['view_check'] == 0)
				{
					mysql_query("INSERT INTO MS_views (video_id,ip,date) VALUES ('".$hash."','".$_SERVER['REMOTE_ADDR']."','".time()."') ;");
					
					$info['views']++;
				}
				
            	return $info;
		    }
			else
			{
				$hash = false;
			}
		}

		return $hash;
	}
	
	function MyFeed($start, $perPage)
	{
		$list = mysql_query("SELECT MS_videos.id,MS_videos.date,MS_videos.title,MS_videos.image_file,MS_users.username,COUNT(DISTINCT MS_views.id) as views FROM MS_follows INNER JOIN MS_videos ON MS_videos.user_id = MS_follows.followers_id INNER JOIN MS_users ON MS_users.id = MS_videos.user_id LEFT JOIN MS_views ON MS_views.video_id = MS_videos.id WHERE MS_follows.follower_id = '".$this->login['id']."' GROUP BY MS_videos.id ORDER BY MS_videos.date desc LIMIT ".$start.",".$perPage." ;");
	    
		return $this->ConvertVideosToHTML($list);
	}
	
	function MyFeedCount()
	{
		$info = mysql_query("SELECT count(MS_follows.id) FROM MS_follows INNER JOIN MS_videos ON MS_videos.user_id = MS_follows.followers_id WHERE MS_follows.follower_id = '".$this->login['id']."' ;");
	    
		$info = mysql_fetch_array($info);
		
		return $info[0];
	}
	
	function MyVideos($start, $perPage)
	{
		$list = mysql_query("SELECT MS_videos.id,MS_videos.date,MS_videos.title,MS_videos.description,MS_videos.image_file,MS_videos.enable_comments,MS_users.username,COUNT(MS_views.id) as views FROM MS_videos INNER JOIN MS_users ON MS_users.id = MS_videos.user_id LEFT JOIN MS_views ON MS_views.video_id = MS_videos.id WHERE MS_videos.user_id = '".$this->login['id']."' GROUP BY MS_videos.id ORDER BY MS_videos.date desc LIMIT ".$start.",".$perPage." ;");
		
		return $list;
	}
	
	function StartAndPage($page, $perPage)
	{
		if(!is_numeric($page))
		{
			$page = 1;
		}
		
		$start = 0;
		
		if($page > 1)
		{
			$start = $page*$perPage - $perPage;
		}
		else if($page == 0)
		{
			$page = 1;
		}
		
		return array('page' => $page, 'start' => $start);
	}
	
	function MyVideosCount()
	{
		$info = mysql_query("SELECT count(id) FROM MS_videos WHERE user_id = '".$this->login['id']."' ;");
	    
		$info = mysql_fetch_array($info);
		
		return $info[0];
	}
	
	function MyVideosPost()
	{
		$msg = array('error' => false, 'success' => false);
		
		if(isset($_POST['edit_video_id']) && isset($_POST['edit_title']) && isset($_POST['edit_description']) && isset($_POST['enable_comments']))
		{
			if($this->CheckSecurity())
		    {
		    	$video_id = $_POST['edit_video_id'];
			    $title = $_POST['edit_title'];
			    $description = $_POST['edit_description'];
				$enable_comments = $_POST['enable_comments'];
				
				if($this->login['isAdmin'] && isset($_POST['video_category']))
				{
					$video_category = $_POST['video_category'];
				    
					if(!is_numeric($video_category))
					{
						$msg['error'] = "Invalid category.";
						
						return $msg;
					}
					
					if(!$this->CategoryExist($video_category))
					{
						$msg['error'] = "Invalid category.";
						
						return $msg;
					}
				}
				
				if($this->MyVideoExist($video_id))
				{
					$title = $this->Validate(trim($title), array("min_len" => 4, "max_len" => 80));
		    
			        if($title === 1)
		            {
						$msg['error'] = "Title must be between 4 and 80 symbols.";
						
						return $msg;
		            }
			        
		            $description = $this->Validate(trim($description), array("min_len" => 10, "max_len" => 5000));
		            
		            if($description === 1)
		            {
						$msg['error'] = "Description must be between 10 and 5000 symbols.";
						
						return $msg;
		            }
					
					if($enable_comments != 0 && $enable_comments != 1)
			        {
						$msg['error'] = "Enable comments function has invalid arguments!";
						
						return $msg;
			        }
					
					$update_category = null;
					
					if($this->login['isAdmin'] && isset($_POST['video_category']))
				    {
						$update_category = ", category_id = '".$video_category."'";
					}
					
					mysql_query("UPDATE MS_videos SET title = '".$title."', description = '".$description."', enable_comments = '".$enable_comments."'".$update_category." WHERE id = '".$video_id."' ;");
					
					$msg['success'] = "Successful update video.";
				}
				else
				{
					$msg['error'] = "Video not exist.";
				}
		    }
			else
			{
				$msg['error'] = "An error has occurred, please try again later.";
			}
		}
		
		return $msg;
	}
	
	function Search($start, $perPage, $search, $method)
	{
		$i = 0;
		$data = null;
		
		if($method == 1)
		{
			$list = mysql_query("SELECT MS_videos.id,MS_videos.date,MS_videos.title,MS_videos.image_file,MS_users.username,COUNT(MS_views.id) as views FROM MS_videos INNER JOIN MS_users ON MS_users.id = MS_videos.user_id LEFT JOIN MS_views ON MS_views.video_id = MS_videos.id WHERE MS_videos.title LIKE '%".$search."%' GROUP BY MS_videos.id ORDER BY views desc LIMIT ".$start.",".$perPage.";");

	        $data = $this->ConvertVideosToHTML($list);
		}
		else
		{
			$list = mysql_query("SELECT MS_users.id,MS_users.username,MS_users.FirstName,MS_users.LastName,MS_users.profile,COUNT(DISTINCT MS_follows.id) AS follows_count,COUNT(DISTINCT MS_videos.id) AS videos_count FROM MS_users LEFT JOIN MS_follows ON MS_follows.followers_id = MS_users.id LEFT JOIN MS_videos ON MS_videos.user_id = MS_users.id WHERE MS_users.username LIKE '%".$search."%' GROUP BY MS_users.id ORDER BY follows_count desc LIMIT ".$start.",".$perPage.";");

	        while($info = mysql_fetch_array($list))
		    {
		    	$data .= '
				    <div class="col-lg-4">	
                        <div class="hpanel hgreen contact-panel no-pad">
                            <div class="panel-body">
                                <center><a href="'.URL.'home/Profile/'.$info['username'].'"><img id="profileavatar" alt="avatar" class="img-circle m-b m-t-md" src="'.URL.'profiles/avatar/'.$info["profile"].'" width="78px">
                                <div class="text-muted font-bold m-b-xs">'.$info['username'].'</div></a> </center>		
                            </div>
                            <div class="panel-footer contact-footer">
                                <div class="row">
                                    <div class="col-md-6 border-right animated-panel zoomIn" style="animation-delay: 0.2s; -webkit-animation-delay: 0.2s;">
                                        <div class="contact-stat"><span>UPLOADED VIDEOS </span> <strong>'.$info['videos_count'].'</strong></div>
                                    </div>
                                    <div class="col-md-6 border-right animated-panel zoomIn" style="animation-delay: 0.2s; -webkit-animation-delay: 0.2s;">
                                        <div class="contact-stat"><span>FOLLOWERS </span> <strong>'.$info['follows_count'].'</strong></div>
                                    </div>
                                </div>
                            </div>
                        
                        </div>
                    </div>';
		    	$i++;
		    }
			
			if($i == 0)
		    {
		    	$data = '<div class="alert alert-info"><i class="fa fa-info"></i> &nbsp;DO NOT HAVE INFORMATION IN SYSTEM</div>';
		    }
		}
		
		return $data;
	}
	
	function ConvertVideosToHTML($list)
	{
		$data = null;
		$i = 0;
		
		while($info = mysql_fetch_array($list))
		{
			$data .= '
			    <div class="col-lg-4">
                    <div class="hpanel">
		                <div class="panel-heading hbuilt" id="auto-text">
                             <a href="'.URL.'home/video/'.$info['id'].'" title="'.$info['title'].'">'.$info['title'].'</a>
                        </div>
                        <div class="panel-body text-center video_background">
			       	        <div class="thumb thumb-play">
                                <a href="'.URL.'home/video/'.$info['id'].'">
                                    <span class="play">&#9658;</span>
                                    <div class="overlay"></div>
                                </a>
                                <img src="'.URL.'videos/'.$info['image_file'].'" />
                            </div>
                        </div>
                        <div class="panel-footer">
                            <span><i class="pe-7s-look"></i> '.$info['views'].' | <i class="pe-7s-date"></i> '.date('Y.m.d', $info['date']).' | <i class="pe-7s-user"></i> <a href="'.URL.'home/Profile/'.$info['username'].'">'.$info['username'].'</a></span>
                        </div>
                    </div>
                </div>';
			$i++;
		}
		
		if($i == 0)
		{
			$data = '<div class="alert alert-info"><i class="fa fa-info"></i> &nbsp;DO NOT HAVE INFORMATION IN SYSTEM</div>';
		}
		
		return $data;
	}
	
	function SearchCount($search, $method)
	{
		if($method == 1)
		{
			$count = mysql_query("SELECT COUNT(id) FROM MS_videos WHERE title LIKE '%".$search."%' ;");
		}
		else
		{
			$count = mysql_query("SELECT COUNT(id) FROM MS_users WHERE username LIKE '%".$search."%' ;");
		}
		
		$count = mysql_fetch_array($count);
		
		return $count[0];
	}
	
	function CommentCheck($comment_id)
	{
		if($this->login['isAdmin'])
		{
			$check = mysql_query("SELECT MS_comments.id FROM MS_comments INNER JOIN MS_videos ON MS_videos.id = MS_comments.video_id WHERE MS_comments.id = '".$this->Escape($comment_id)."' ;");
		}
		else
		{
			$check = mysql_query("SELECT MS_comments.id FROM MS_comments INNER JOIN MS_videos ON MS_videos.id = MS_comments.video_id WHERE MS_comments.id = '".$this->Escape($comment_id)."' AND MS_videos.user_id = '".$this->login['id']."' ;");
		}
		
		$info = mysql_fetch_array($check);
		
		if(mysql_num_rows($check) == 1)
		{
			return true;
		}
		
		return false;
	}
	
	function LastCommentDate()
	{
		$get = mysql_query("SELECT date FROM MS_comments WHERE user_id = '".$this->login['id']."' ORDER BY date desc LIMIT 1;");
		
		$get = mysql_fetch_array($get);
		
		return $get['date'];
	}
	
	function Log($log)
	{
		mysql_query("INSERT INTO MS_log (user_id, log, ip, date) VALUES ('".$this->login['id']."', '".$this->Escape($log)."', '".$_SERVER['REMOTE_ADDR']."', '".time()."') ;");
	}
	
	function GetLog($start, $perPage)
	{
		$list = mysql_query("SELECT * FROM MS_log WHERE user_id = '".$this->login['id']."' ORDER BY date desc LIMIT ".$start.",".$perPage." ;");
		
		$data = null;
		
		while($info = mysql_fetch_array($list))
		{
			$data .= '<tr><td><span class="font-bold">'.$info['log'].'</span></td><td>'.date("d.m.Y h:i:s", $info['date']).'</td><td>'.$info['ip'].'</td></tr>';
		}
		
		return $data;
	}
	
	function CountLog()
	{
		$count = mysql_query("SELECT count(id) FROM MS_log WHERE user_id = '".$this->login['id']."' ;");
		
		$count = mysql_fetch_array($count);
		
		return $count[0];
	}
	
	function GetVideo($hash)
	{
		$check = mysql_query("SELECT MS_videos.id,MS_videos.title,MS_categories.category,MS_videos.category_id,MS_videos.image_file,MS_videos.video_file,MS_videos.description,MS_videos.enable_comments,MS_videos.date,MS_users.id as uploader_user_id,MS_users.username,COUNT(DISTINCT MS_likes.id) as likes, COUNT(DISTINCT IF(MS_likes.user_id = '".$this->login['id']."', true, NULL)) as liked,COUNT(DISTINCT MS_views.id) as views,COUNT(DISTINCT IF(MS_views.ip = '".$_SERVER['REMOTE_ADDR']."', true, NULL)) as view_check,MS_follows.id as following FROM MS_videos INNER JOIN MS_users ON MS_users.id = MS_videos.user_id LEFT JOIN MS_likes ON MS_likes.video_id = MS_videos.id LEFT JOIN MS_categories ON MS_categories.id = MS_videos.category_id LEFT JOIN MS_views ON MS_views.video_id = MS_videos.id LEFT JOIN MS_follows ON MS_follows.followers_id = MS_users.id AND MS_follows.follower_id = '".$this->login['id']."' WHERE MS_videos.id='".$hash."' GROUP BY MS_videos.id ;");
        
		$info = mysql_fetch_array($check);
		
        if($info["id"] != null)
        {
			$info['rightVideos'] = $this->RightVideosToHTML($this->GetRightVideos($info['category_id'], $hash));
			
			if($info['enable_comments'])
			{
				$info['videoComments'] = $this->CommentsToHTML($this->GetComments($hash), false, $info["uploader_user_id"]);
			}
			else
			{
				$info['videoComments'] = '<center>Comments has been disabled for this video.</center>';
			}
			
			return $info;
		}
		
		return false;
	}
	
	function ValidForgotHash($user, $hash)
	{
		$hash = $this->Escape($hash);
		
		$user = $this->Escape($user);
		
		$check = mysql_query("SELECT id FROM MS_users WHERE username = '".$user."' AND hash = '".$hash."' ;");
		
		if(mysql_num_rows($check) == 1)
		{
			return true;
		}
		
		return false;
	}
	
	function ForgotChangePost($user)
	{
		$msg = array('error' => false, 'success' => false);
		
		if(isset($_POST['new_pass']) && isset($_POST['new_pass_re']))
		{
			$new_pass = $this->Escape($_POST['new_pass']);
			
			$new_pass_re = $this->Escape($_POST['new_pass_re']);
			
			if($new_pass == $new_pass_re)
			{
				$new_pass = $this->Validate($new_pass, array('min_len' => 6, 'max_len' => 18));
			    
			    if($new_pass === 1)
			    {
			    	$msg['error'] = "Password must be between 6 and 18 symbols.";
					
			    	return $msg;
			    }
				
				mysql_query("UPDATE MS_users SET password = '".$this->HashPass($new_pass)."' WHERE username = '".$user."' ;");
				
				$msg['success'] = "Password changed successful.";
			}
			else
			{
				$msg['error'] = "Passwords do not match.";
			}
		}
		
		return $msg;
	}
	
	function ForgotPOST()
	{
		$msg = array('error' => false, 'success' => false);
		
		if(isset($_POST['forgot_user']) && isset($_POST['forgot_email']))
		{
			$user = $this->Escape($_POST['forgot_user']);
			
			$email = $this->Escape($_POST['forgot_email']);
			
			$check = mysql_query("SELECT id FROM MS_users WHERE username = '".$user."' AND email = '".$email."' ;");
			
			if(mysql_num_rows($check) == 1)
			{
				$hash = $this->HashPass(time().rand(1000000000, 9999999999).$user.$email);
				
				$to      = $email;
                $subject = 'mediashare forgot password';
                $message = "Forgot link: ".URL."home/Forgot/".$user."/".$hash."/";
                $headers = 'From: mediashare@example.com' . "\r\n" .
                    'Reply-To: mediashare@example.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                
                mail($to, $subject, $message, $headers);

				mysql_query("UPDATE MS_users SET hash = '".$hash."' WHERE username = '".$user."' ;");
				
				$msg['success'] = 'Please view your email.';
			}
			else
			{
				$msg['error'] = 'Invalid email or username.';
			}
		}
		
		return $msg;
	}
	
	function RightVideosToHTML($list, $post = false)
	{
		$i = 0;
		$data = null;
		
		if(!$post)
		{
			$data = '<div class="video-box">';
		}
		
		while($info = mysql_fetch_array($list))
		{
			$data .= '
			    <div class="row">
                    <div class="col-md-6"><a href="'.$info['id'].'"><img src="'.URL.'videos/'.$info['image_file'].'" width="200px" height="90px"></a></div>
                    <div class="box-info"><b><a href="'.$info['id'].'" title="'.$info['title'].'">'.$this->RemoveText($info['title'], 55).'</a></b><br /><i class="pe-7s-look"></i> '.$info['views'].'<br /><a href="'.URL.'home/Profile/'.$info['username'].'"><i class="pe-7s-user"></i> '.$info['username'].'</a></div>
		    	</div> 
				<hr />';
				
			$i++;
		}
		
		if(!$post)
		{
			$data .= '</div>';
		}
		
		if(!$post)
		{
			if($i == 10)
		    {
		    	$data .= '<button type="button" class="btn btn-block btn-outline btn-default" onclick="LoadVideos(this)">SHOW MORE VIDEOS</button>';
		    }
			else if($i == 0)
			{
				$data = '<center>NO VIDEOS HAVE BEEN ADDED YET</center>';
			}
		}
		else
		{
		    if($i == 0)
		    {
		    	$data = '[NULL]';
		    }
		}
		
		return $data;
	}
	
	function CommentsToHTML($list, $post = false, $uploader_user_id = false)
	{
        $i = 0;
		
		if(!$post)
		{
			$data = '<div class="comment-box">';
		}
		
		while($info = mysql_fetch_array($list))
		{
			$data .= '
			    <div class="social-talk">
                    <div class="media social-profile clearfix">
                        <a class="pull-left" href="'.URL.'home/Profile/'.$info['username'].'">
                            <img src="'.URL.'profiles/avatar/'.$info['profile'].'" alt="'.$info['username'].'">
                        </a>';
			if($this->login['id'] === $uploader_user_id || $this->login['isAdmin'])
			{
				$data .= '<div class="panel-tools"><a onclick="DeleteComment('.$info['id'].', this)"><i class="fa fa-times"></i></a></div>';
			}
			
            $data .= '
			        <div class="media-body">
                        <span class="font-bold"><a href="'.URL.'home/Profile/'.$info['username'].'">'.$info['username'].'</a></span>
                        <small class="text-muted">'.date('h:i / d.m.Y', $info['date']).'</small>
                            
                        <div class="social-content">
                            '.$info['comment'].'
                        </div>
                    </div>
                </div>
            <hr /></div>';

			$i++;
		}
		
		if(!$post)
		{
			$data .= '</div><center>';
		
		    if($i < 10)
		    {
		    	if($i == 0)
		    	{
		    		$data .= 'NO COMMENTS HAVE BEEN ADDED YET';
		    	}
		    	else
		    	{
		    		$data .= 'NO MORE COMMENTS';
		    	}
		    }
		    else
		    {
		    	$data .= '<button type="button" class="btn btn-block btn-outline btn-default" onclick="LoadComments(this)">SHOW MORE COMMENTS</button>';
		    }
		    
		    $data .= '</center>';
		}
		else
		{
			if($i == 0)
			{
				$data = '[NULL]';
			}
		}
		
		return $data;
	}
	
	function VideoExist($hash)
	{
		$check = mysql_query("SELECT MS_videos.id,MS_videos.enable_comments,MS_videos.category_id,MS_videos.title,MS_videos.description,MS_categories.category FROM MS_videos LEFT JOIN MS_categories ON MS_categories.id = MS_videos.category_id WHERE MS_videos.id='".$this->Escape($hash)."' ;");
		
		if(mysql_num_rows($check) == 1)
		{
			return mysql_fetch_array($check);
		}
		
		return false;
	}
	
	function MyVideoExist($hash)
	{
		if($this->login['isAdmin'])
		{
			$check = mysql_query("SELECT * FROM MS_videos WHERE id='".$this->Escape($hash)."' ;");
		}
		else
		{
			$check = mysql_query("SELECT * FROM MS_videos WHERE id='".$this->Escape($hash)."' AND user_id = '".$this->login['id']."' ;");
		}
		
		if(mysql_num_rows($check) == 1)
		{
			return mysql_fetch_array($check);
		}
		
		return false;
	}
	
	function Pagination($page, $max, $url, $adjacents = 4) 
	{
        $out = '<center><ul class="pagination">';
		
        // previous
        if ($page == 1) 
		{
            $out.= '<li class="disabled"><a aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
        } 
		else 
		{
			$out.='<li><a aria-label="Previous" href="'.str_replace('{i}', ($page - 1), $url).'"><span aria-hidden="true">&laquo;</span></a></li>';
        }
		
        $pmin=($page>$adjacents)?($page - $adjacents):1;
        $pmax=($page<($max - $adjacents))?($page + $adjacents):$max;
		
		if($page > $pmax)
		{
			return false;
		}
		
        for ($i = $pmin; $i <= $pmax; $i++) 
		{
            if ($i == $page) 
			{
				$out.= '<li class="active-green"><a>'.$i.'</li>';
            } 
			else 
			{
				$out.='<li><a href="'.str_replace('{i}', $i, $url).'">'.$i.'</li>';
            }
			
        }
        
        if ($page<($max - $adjacents)) 
		{
			$out.= '<li><a>...</li>';
			$out.= '<li><a href="'.str_replace('{i}', $max, $url).'">'.$max.'</li>';
        }
		
        // next
        if ($page < $max) 
		{
			$out.= '<li><a aria-label="Next" href="'.str_replace('{i}', ($page + 1), $url).'"><span aria-hidden="true">&raquo;</span></a></li>';
        } 
		else 
		{
			$out.= '<li class="disabled"><a aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
        }
		
        $out.= '</ul></center>';
		
        return $out;
    }

	function DeleteMyVideo($id, $video_file, $image_file)
	{
		@unlink("videos/".$video_file);
		@unlink("videos/".$image_file);
		$id = $this->Escape($id);
		mysql_query("DELETE FROM MS_videos WHERE id='".$id."' ;");
		mysql_query("DELETE FROM MS_comments WHERE video_id='".$id."' ;");
		mysql_query("DELETE FROM MS_likes WHERE video_id='".$id."' ;");
		mysql_query("DELETE FROM MS_views WHERE video_id='".$id."' ;");
	}
	
	function CountVideos($user_id = false)
	{
		if($user_id !== false)
		{
			$count = mysql_query("SELECT count(id) FROM MS_videos WHERE user_id = '".$user_id."' ;");
		}
		else
		{
			$count = mysql_query("SELECT count(id) FROM MS_videos ;");
		}
		
		$count = mysql_fetch_array($count);
		
		return $count[0];
	}
	
	function CountRecommendedVideos()
	{
		$count = mysql_query("SELECT count(id) FROM MS_likes WHERE user_id = '".$this->login['id']."' ;");
		
		$count = mysql_fetch_array($count);
		
		return $count[0];
	}
	
	function MostPopularsVideos($start, $perPage)
	{
		$list = mysql_query("SELECT MS_videos.id,MS_videos.date,MS_videos.title,MS_videos.image_file,MS_users.username,COUNT(MS_views.id) as views FROM MS_videos INNER JOIN MS_users ON MS_users.id = MS_videos.user_id LEFT JOIN MS_views ON MS_views.video_id = MS_videos.id GROUP BY MS_videos.id ORDER BY views desc LIMIT ".$start.",".$perPage." ;");
		
		return $this->ConvertVideosToHTML($list);
	}
	
	function BestRatedVideos($start, $perPage)
	{
		$list = mysql_query("SELECT MS_videos.id,MS_videos.date,MS_videos.title,MS_videos.image_file,MS_users.username,COUNT(DISTINCT MS_views.id) as views,COUNT(DISTINCT MS_likes.id) as likes FROM MS_videos INNER JOIN MS_users ON MS_users.id = MS_videos.user_id LEFT JOIN MS_views ON MS_views.video_id = MS_videos.id LEFT JOIN MS_likes ON MS_likes.video_id = MS_videos.id GROUP BY MS_videos.id ORDER BY likes desc LIMIT ".$start.",".$perPage." ;");
		
		return $this->ConvertVideosToHTML($list);
	}
	
	function RecommendedVideos($start, $perPage)
	{
		$list = mysql_query("SELECT MS_videos.id,MS_videos.date,MS_videos.title,MS_videos.image_file,MS_users.username,COUNT(MS_views.id) as views FROM MS_likes LEFT JOIN MS_videos ON MS_videos.id = MS_likes.video_id INNER JOIN MS_users ON MS_users.id = MS_videos.user_id LEFT JOIN MS_views ON MS_views.video_id = MS_videos.id WHERE MS_likes.user_id = '".$this->login['id']."' GROUP BY MS_likes.id ORDER BY views desc LIMIT ".$start.",".$perPage." ;");
		
		return $this->ConvertVideosToHTML($list);
	}
	
	function RandomVideos()
	{
		$list = mysql_query("SELECT MS_videos.id,MS_videos.date,MS_videos.title,MS_videos.image_file,MS_users.username,COUNT(MS_views.id) as views FROM MS_videos INNER JOIN MS_users ON MS_users.id = MS_videos.user_id LEFT JOIN MS_views ON MS_views.video_id = MS_videos.id GROUP BY MS_videos.id ORDER BY RAND() LIMIT 18 ;");
		
		return $this->ConvertVideosToHTML($list);
	}
	
	function GetRightVideos($category_id, $hash, $start = 0)
	{
		if(!is_numeric($start))
		{
			$start = 0;
		}
		
		$hash = $this->Escape($hash);
		
		if(is_numeric($category_id))
		{
			$list = mysql_query("SELECT MS_videos.id,MS_videos.title,MS_videos.image_file,MS_users.username,COUNT(MS_views.id) as views FROM MS_videos INNER JOIN MS_users ON MS_users.id = MS_videos.user_id LEFT JOIN MS_views ON MS_views.video_id = MS_videos.id WHERE MS_videos.category_id = '".$category_id."' AND MS_videos.id != '".$hash."' GROUP BY MS_videos.id ORDER BY RAND() LIMIT ".$start.",10 ;");
		    
			if(mysql_num_rows($list) == 0)
			{
				$list = mysql_query("SELECT MS_videos.id,MS_videos.title,MS_videos.image_file,MS_users.username,COUNT(MS_views.id) as views FROM MS_videos INNER JOIN MS_users ON MS_users.id = MS_videos.user_id LEFT JOIN MS_views ON MS_views.video_id = MS_videos.id WHERE MS_videos.id != '".$hash."' GROUP BY MS_videos.id ORDER BY RAND() LIMIT ".$start.",10 ;");
			}
			
			return $list;
		}
		else
		{
			return false;
		}
	}
	
	function GetComments($hash, $start = 0)
	{
		$list = mysql_query("SELECT MS_users.username,MS_users.profile,MS_comments.id,MS_comments.comment,MS_comments.date FROM MS_comments INNER JOIN MS_users ON MS_users.id = MS_comments.user_id WHERE MS_comments.video_id='".$hash."' ORDER BY MS_comments.date desc LIMIT ".$start.",10;");
		
		return $list;
	}
	
    function Escape($arr)
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
	
	function Validate($text, $config)
	{
		$text = $this->Escape($text);
		
		foreach($config as $key => $value)
		{
			switch(strtoupper($key))
			{
				case "MIN_LEN":
				if(strlen($text) < $value)
				{
					return 1;
				}
				break;
				
				case "MAX_LEN":
				if(strlen($text) > $value)
				{
					return 1;
				}
				break;
				
				case "ONLYNUMALPHA":
				if($value){
				    if (preg_match('/[^A-Za-z0-9]/', $text))
                    {
                        return 2;
                    }
				}
				break;
				
				case "ISEMAIL":
				if($value)
				{
					if(!preg_match('/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD', $text))
					{
						return 3;
					}
				}
				break;
			}
		}
		
		return $text;
	}
	
	function is_image($path, $allow)
    {
        $a = @getimagesize($path);
        
        $image_type = $a['mime'];
    	
        if(isset($allow[$image_type]))
        {
            return $allow[$image_type];
        }
    	
        return false;
    }
    
	function CheckFileType($ext, $allow)
	{
		$type = false;
		
		if(isset($allow[$ext]))
		{
			$type = $allow[$ext];
		}
		
		return $type;
	}
	
	function SettingsPost()
	{
		$msg = array('error' => false, 'success' => false);
		
		if($this->CheckSecurity())
		{
		    if(isset($_POST['FirstName']) && isset($_POST['LastName']))
		    {			
		    	$FirstName = $this->Validate($_POST['FirstName'], array('min_len' => 2, 'max_len' => 20, 'OnlyNumAlpha' => true));
		    	
		    	if($FirstName === 1 || $FirstName === 2)
		    	{
		    		$msg['error'] = "Invalid first name.";
		    		
		    		return $msg;
		    	}
		    	
		    	$LastName = $this->Validate($_POST['LastName'], array('min_len' => 2, 'max_len' => 20, 'OnlyNumAlpha' => true));
		    	
		    	if($LastName === 1 || $LastName === 2)
		    	{
		    		$msg['error'] = "Invalid last name.";
		    		
		    		return $msg;
		    	}
				
		    	if(isset($_FILES['avatar']['name']))
		    	{
		    		if(!empty($_FILES['avatar']['name']))
		    		{
		    		    if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK)
		    	        {
		    		    	$msg['error'] = "Upload avatar failed!";
		    		    
		    		        return $msg;
                        }
		    	        
		    		    $image_type = $this->is_image($_FILES["avatar"]["tmp_name"], array('image/gif' => 'gif', 'image/jpeg' => 'jpeg', 'image/png' => 'png', 'image/bmp' => 'bmp'));
		                
		                if(!$image_type)
		                {
		                	$msg['error'] = "Avatar is not image.";
		    		    
		    		        return $msg;
		                }
		    		    else
		    		    {
		    		    	if($this->login['profile'] == "default.png")
		    		    	{
		    		    		$this->login['profile'] = crc32($this->login['id']).".png";
		    		    	}
		    		    	
		    		    	if(!move_uploaded_file($_FILES["avatar"]["tmp_name"], "profiles/avatar/".$this->login['profile']))
		    		    	{
		    		    		$msg['error'] = "Avatar move filed!";
		    		    
		    		            return $msg;
		    		    	}
		    		    }
		    		}
		    	}
		    	
		    	if(isset($_FILES['cover']['name']))
		    	{
		    		if(!empty($_FILES['cover']['name']))
		    		{
		    		    if ($_FILES['cover']['error'] !== UPLOAD_ERR_OK)
		    	        {
		    		    	$msg['error'] = "Upload cover failed!";
		    		    
		    		        return $msg;
                        }
		    	        
		    		    $image_type = $this->is_image($_FILES["cover"]["tmp_name"], array('image/gif' => 'gif', 'image/jpeg' => 'jpeg', 'image/png' => 'png', 'image/bmp' => 'bmp'));
		                
		                if(!$image_type)
		                {
		                	$msg['error'] = "cover is not image.";
		    		    
		    		        return $msg;
		                }
		    		    else
		    		    {
		    		    	if($this->login['cover'] == "default.png")
		    		    	{
		    		    		$this->login['cover'] = crc32($this->login['id']).".png";
		    		    	}
		    		    	
		    		    	if(!move_uploaded_file($_FILES["cover"]["tmp_name"], "profiles/cover/".$this->login['cover']))
		    		    	{
		    		    		$msg['error'] = "Cover upload filed!";
		    		    
		    		            return $msg;
		    		    	}
		    		    }
		    		}
		    	}
		    	
		    	$msg['success'] = 'Updated successful.Please lait...<meta http-equiv="refresh" content="3; url=" />';
		    	
		    	mysql_query("UPDATE MS_users SET FirstName = '".$FirstName."', LastName = '".$LastName."', profile = '".$this->login['profile']."', cover = '".$this->login['cover']."' WHERE id = '".$this->login['id']."' ;");
				
		    	$this->login['FirstName'] = $FirstName;
		    	
		    	$this->login['LastName'] = $LastName;
		    	
		    	return $msg;
		    }
		    
		    if(isset($_POST['old_pass']) && isset($_POST['new_pass']) && isset($_POST['new_pass_re']))
		    {
		    	$old_pass = $this->HashPass($_POST['old_pass']);
		    	
		    	if($old_pass != $this->login['password'])
		    	{
		    		$msg['error'] = "Old password is not true!";
		    		    
		    	    return $msg;
		    	}
		    	
		    	if($_POST['new_pass'] != $_POST['new_pass_re'])
		    	{
		    		$msg['error'] = "New passwords do not match!";
		    		    
		    	    return $msg;
		    	}
		    	
		    	$new_pass = $this->Validate($_POST['new_pass'], array('min_len' => 6, 'max_len' => 18));
		    	
		    	if($new_pass === 1)
		    	{
		    		$msg['error'] = "Password must be between 6 and 18 symbols.";
		    		    
		    	    return $msg;
		    	}
		    	
		    	$new_pass = $this->HashPass($new_pass);
		    	
		    	$msg['success'] = "Password changed successful.";
		    	
		    	mysql_query("UPDATE MS_users SET password = '".$new_pass."' WHERE id = '".$this->login['id']."' ;");
		    	
		    	return $msg;
		    }
		    
		    if(isset($_POST['old_email']) && isset($_POST['new_email']) && isset($_POST['new_email_re']))
		    {
		    	if($_POST['old_email'] != $this->login['email'])
		    	{
		    		$msg['error'] = "Old email is not true!";
		    		    
		    	    return $msg;
		    	}
		    	
		    	if($_POST['new_email'] != $_POST['new_email_re'])
		    	{
		    		$msg['error'] = "New email do not match!";
		    		    
		    	    return $msg;
		    	}
		    	
		    	$new_email = $this->Validate($_POST['new_email'], array('IsEmail' => true));
		    	
		    	if($new_email === 3)
		    	{
		    		$msg['error'] = "Invalid Email.";
		    		    
		    	    return $msg;
		    	}
		    	
		    	$msg['success'] = "Email changed successful.";
		    	
		    	mysql_query("UPDATE MS_users SET email = '".$new_email."' WHERE id = '".$this->login['id']."' ;");
		    	
		    	return $msg;
		    }
	    }
	
		return $msg;
	}
	
	function Auto($arr, $value, $else)
	{
		if(isset($arr[$value]))
		{
			return $arr[$value];
		}
		
		return $else;
	}
	
	function CheckFollow($followers)
	{
		if(!is_numeric($followers))
		{
			return null;
		}
		
		$check = mysql_query("SELECT id FROM MS_follows WHERE follower_id = '".$this->login['id']."' AND followers_id = '".$followers."' ;");
			
		if(mysql_num_rows($check) == 1)
		{
			return true;
		}
		
		return false;
	}
	
	function RemoveText($text, $len)
	{
		if(strlen($text) > $len)
		{
			return substr($text, 0, $len)."...";
		}
		
		return $text;
	}
	
	function PM_List($start, $PerPage)
	{
		$list = mysql_query("SELECT MS_pm.*,MS_users.username FROM MS_pm INNER JOIN MS_users ON MS_users.id = MS_pm.from_id WHERE MS_pm.to_id = '".$this->login['id']."' OR MS_pm.type = 1 ORDER BY MS_pm.date desc LIMIT ".$start.", ".$PerPage."; ");
		
		return $list;
	}
	
	function PM_Info($id)
	{
		$info = mysql_query("SELECT MS_pm.*,MS_users.profile,MS_users.username,MS_users.FirstName,MS_users.LastName FROM MS_pm INNER JOIN MS_users ON MS_users.id = MS_pm.from_id WHERE MS_pm.id = '".$id."' AND (MS_pm.to_id = '".$this->login['id']."' OR MS_pm.from_id = '".$this->login['id']."') ;");
		
		if(mysql_num_rows($info) == 1)
		{
			return mysql_fetch_array($info);
		}
		else
		{
			return false;
		}
	}
	
	function PM_Count()
	{
		$list = mysql_query("SELECT COUNT(id) FROM MS_pm WHERE to_id = '".$this->login['id']."' OR type = 1; ");
		
		$list = mysql_fetch_array($list);
		
		return $list[0];
	}
	
	function PM_send()
	{
		$msg = array('error' => false, 'success' => false);
		
		if(isset($_POST['to']) && isset($_POST['title']) && isset($_POST['message']))
		{
			$to = trim($_POST['to']);
			$title = trim($_POST['title']);
			$message = trim($_POST['message']);
			
			if($to != null && $title != null && $message != null)
			{
				$title = $this->Validate($to, array("min_len" => 2, "max_len" => 60));
		    
			    if($title === 1)
		        {
		        	$msg['error'] = "Title must be between 2 and 60 symbols.";
					
					return $msg;
		        }
				
				$message = $this->Validate($message, array("min_len" => 2, "max_len" => 2000));
		    
			    if($message === 1)
		        {
		        	$msg['error'] = "Message must be between 2 and 2000 symbols.";
					
					return $msg;
		        }
			    
				if($to == $this->login['username'])
				{
					$msg['error'] = "Can't send messages to yours account.";
					
					return $msg;
				}
				
				$to_user = $this->UserExist($to);
				
				if($to_user !== false)
				{
					mysql_query("INSERT INTO MS_pm (from_id,to_id,title,message,date) VALUES ('".$this->login['id']."','".$to_user['id']."','".$title."','".$message."','".time()."') ;");
					
					$_POST['title'] = null;
					$_POST['message'] = null;
					
					$msg['success'] = "Successful sent message to ".$to.".";
				}
				else
				{
					$msg['error'] = "Username don't exist!";
				}
			}
			else
			{
				$msg['error'] = "Please write all information!";
			}
		}
		
		return $msg;
	}
}
?>