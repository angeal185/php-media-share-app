<?php

Class POST extends Controler
{
	public $Info;
	
	function __construct()
	{
        $this->Module('Info');
		
		$this->Info = new Info;
		
		$this->Info->LoginCheck();
    }
	
	function Like($hash = false)
	{
		if($hash != false && $this->Info->login['status'] && $this->Info->CheckSecurity())
		{
			if($this->Info->VideoExist($hash) !== false)
			{
				$like_check = mysql_query("SELECT id FROM MS_likes WHERE video_id='".$hash."' AND user_id='".$this->Info->login['id']."' ;");
				
				if(mysql_num_rows($like_check) == 0)
				{
					mysql_query("INSERT INTO MS_likes (video_id, user_id, ip, date) VALUES ('".$hash."','".$this->Info->login['id']."','".$_SERVER['REMOTE_ADDR']."','".time()."') ;");
					
					$this->Info->Log('Like video.');
					
					echo '[LIKED]';
					
					return;
				}
			}
		}
		
		echo 'Error';
	}
	
	function UnLike($hash = false)
	{
		if($hash != false && $this->Info->login['status'] && $this->Info->CheckSecurity())
		{
			if($this->Info->VideoExist($hash) !== false)
			{
				$like_check = mysql_query("SELECT id FROM MS_likes WHERE video_id='".$hash."' AND user_id='".$this->Info->login['id']."' ;");
				
				if(mysql_num_rows($like_check) == 1)
				{
					mysql_query("DELETE FROM MS_likes WHERE video_id='".$hash."' AND user_id='".$this->Info->login['id']."';");
					
					$this->Info->Log('Unlike video.');
					
					echo '[UNLIKED]';
					
					return;
				}
			}
		}
		
		echo 'Error';
	}
	
	function Upload()
	{		
	    if(!$this->Info->CheckSecurity())
		{
			die("Error!");
		}
		
	    if($this->Info->login['status'])
		{
		    if(!isset($_FILES["video"]))
		    {
		    	die('Please select video file!');
		    }
		    
		    if(!isset($_FILES["background"]))
		    {
		    	die('Please select image file!');
		    }
		    
		    if(!isset($_POST["title"]))
		    {
		    	die('Please write title!');
		    }
		    
		    if(!isset($_POST["description"]))
		    {
		    	die('Please write description!');
		    }
		    
		    if(!isset($_POST["category"]))
		    {
		    	die('Please write category!');
		    }
			
			if(!isset($_POST["enable_comments"]))
		    {
		    	die('Enable comments function has not found!');
		    }
			
			if ($_FILES['video']['error'] !== UPLOAD_ERR_OK)
			{
                die("Upload failed!");
            }
            
			if ($_FILES['background']['error'] !== UPLOAD_ERR_OK)
			{
                die("Upload failed!");
            }
			
	        $title = $this->Info->Validate(trim($_POST["title"]), array("min_len" => 4, "max_len" => 80));
		    
			if($title === 1)
		    {
		    	die('Title must be between 4 and 80 symbols.');
		    }
			
		    $description = $this->Info->Validate(trim($_POST["description"]), array("min_len" => 10, "max_len" => 5000));
		    
		    if($description === 1)
		    {
		    	die('Description must be between 10 and 5000 symbols.');
		    }
			
		    $category = $this->Escape(trim($_POST["category"]));
		    
		    if(!is_numeric($category))
		    {
		    	die('Invalid Category!');
		    }
		    else
		    {
		    	if(!$this->Info->CategoryExist($category))
		    	{
		    		die('Invalid Category!');
		    	}
		    }
		    
			$enable_comments = $_POST["enable_comments"];
		    
		    if($enable_comments != 0 && $enable_comments != 1)
			{
				die('Enable comments function has invalid arguments!');
			}
            
		    $image_type = $this->Info->is_image($_FILES["background"]["tmp_name"], array('image/gif' => 'gif', 'image/jpeg' => 'jpeg', 'image/png' => 'png', 'image/bmp' => 'bmp'));
		    
		    if(!$image_type)
		    {
		    	die('Invalid Background!');
		    }
		    
		    $video_type = $this->Info->CheckFileType($_FILES["video"]["type"], array('video/mp4' => 'mp4', 'video/avi' => 'avi', 'video/flv' => 'flv', 'application/mp4' => 'mp4'));
		    
		    if(!$video_type)
		    {
		    	die('Invalid Video!');
		    }
	        
		    $fileTmpVideoLoc = $_FILES["video"]["tmp_name"];
		    
		    $fileTmpImageLoc = $_FILES["background"]["tmp_name"];
		    
		    $hash = crc32($_POST["video"] . time() . rand(10000000, 99999999));
		    
		    $fileNameVideo = $hash.".".$video_type;
		    
		    $fileNameImage = $hash.".".$image_type;
		    
            $fileSize = $_FILES["video"]["size"];
		    
            $fileErrorMsg = $_FILES["video"]["error"];
		    
            if (!$fileTmpVideoLoc) 
		    {
                die("ERROR: Please browse for a video before clicking the upload button.");
            }
		    
		    if (!$fileTmpImageLoc) 
		    {
                die("ERROR: Please browse for a image before clicking the upload button.");
            }
			
            if(move_uploaded_file($fileTmpVideoLoc, "videos/".$fileNameVideo) && move_uploaded_file($fileTmpImageLoc, "videos/".$fileNameImage))
		    {
		    	mysql_query("INSERT INTO MS_videos (id,title,video_file,image_file,user_id,category_id,description,enable_comments,date) VALUES('".$hash."','".$title."','".$fileNameVideo."','".$fileNameImage."','".$this->Info->login['id']."','".$category."','".$description."','".$enable_comments."','".time()."') ;");
		    	
				$this->Info->Log('Upload video.');
				
                die("CLICK <a href='".URL."home/video/".$hash."'>HERE</a> TO VIEW VIDEO.");
            } 
		    else
		    {
                die("An error occurred. Please try again later.");
            }
		}
		
		echo 'Please login to account!';
	}
	
	function DeleteMyVideo($id = null)
	{
		if($id != null && $this->Info->CheckSecurity() && $this->Info->login['status'])
		{
			$video = $this->Info->MyVideoExist($id);
			
			if($video !== false)
			{
				$this->Info->DeleteMyVideo($id, $video['video_file'], $video['image_file']);
				
				$this->Info->Log('Delete video.');
				
				die('[OK]');
			}
		}
		
		echo 'Error';
	}
	
	function DeleteComment($comment_id = null)
	{
		if($comment_id != null && $this->Info->CheckSecurity() && $this->Info->login['status'])
		{
			if($this->Info->CommentCheck($comment_id))
			{
				mysql_query("DELETE FROM MS_comments WHERE id = '".$comment_id."' ;");
				
				$this->Info->Log('Delete comment.');
				
				die('[OK]');
			}
		}
		
		echo 'Error';
	}
	
	function PM_Operation($type = 0)
	{
		if($type == 0 || !is_numeric($type))
		{
			die("[INVALID_OPERATION]");
		}
		
		if($this->Info->login['status'] && $this->Info->CheckSecurity())
		{
		    if(isset($_POST['List']))
		    {
				$list = $_POST['List'];
				
		    	if(is_array($list))
				{
					$delRows = null;
					
					for($i = 0; $i < count($list); ++$i)
					{
						if(is_numeric($list[$i]))
						{
							if($delRows != null)
							{
								$delRows .= ",";
							}
							
							$delRows .= "'".$list[$i]."'";
						}
					}
					
					if($delRows != null)
					{
						switch($type)
						{
							case 1:
							mysql_query("DELETE FROM MS_pm WHERE id IN (".$delRows.") AND to_id = '".$this->Info->login['id']."' ;");
							$this->Info->Log('Delete message from PM.');
							break;
							
							case 2:
							mysql_query("UPDATE MS_pm SET view = '1' WHERE id IN (".$delRows.") AND to_id = '".$this->Info->login['id']."' ;");
							break;
							
							default:
							die("[INVALID_OPERATION_1]");
							break;
						}
						
					}
					
					die("[OK]");
				}
		    }
		}
		
		die("[ERROR]");
	}
	
	function AdminVideoInfo($videoId = false)
	{
		if($this->Info->login['isAdmin'] && $videoId !== false && $this->Info->CheckSecurity())
		{
			$videoId = $this->Escape($videoId);
			
			$info = $this->Info->VideoExist($videoId);
			
			if($info !== false)
			{
				echo json_encode($info);
				
				exit;
			}
		}
		
		echo '[ERROR]';
	}
	
	function AdminDeleteCategory($categotyId = false)
	{
		if($this->Info->login['isAdmin'] && $categotyId !== false && $this->Info->CheckSecurity())
		{
			if(is_numeric($categotyId))
			{
				if($this->Info->CategoryExist($categotyId))
				{
					$this->Info->DeleteCategory($categotyId);
					
					echo '[OK]';
					
					exit;
				}
			}
		}
		
		echo '[ERROR]';
	}
	
	function AdminUserDelete($username = false)
	{
		if($this->Info->login['isAdmin'] && $username !== false && $this->Info->CheckSecurity())
		{
			$username = $this->Escape($username);
			
			$info = $this->Info->UserExist($username);
			
			if($info !== false)
			{
				$this->Info->DeleteUser($info);
				
				echo '[OK]';
				
				exit;
			}
		}
		
		echo '[ERROR]';
	}
	
	function Comment($hash)
	{
		if($this->Info->login['status'] && isset($_POST['comment']) && $this->Info->CheckSecurity())
		{
			$check = $this->Info->VideoExist($hash);
			
			if($check !== false)
		    {
				if($check['enable_comments'] == 1)
				{
					$comment = $this->Info->Escape(trim($_POST['comment']));
				
				    if($comment != null)
				    {
						if($this->Info->LastCommentDate() + 60 < time())
				        {
				        	mysql_query("INSERT INTO MS_comments (video_id,user_id,comment,date) VALUES ('".$hash."', '".$this->Info->login['id']."', '".$comment."', '".time()."') ;");
				            
							$this->Info->Log('Comment on video.');
							
				            echo '[OK]';
				        }
				        else
				        {
				        	echo '[TIME]';
				        }
				        
				        exit;
				    }
				}
		    }
		}
	    
		echo '[ERROR]';
	}
	
	function LoadComments($start = 0)
	{
		if(!is_numeric($start))
		{
			$start = 0;
		}
		
		if(isset($_POST['hash']))
		{
			$hash = $this->Escape($_POST['hash']);
			
			echo $this->Info->CommentsToHTML($this->Info->GetComments($hash, $start), true);
			
			exit;
		}
		
		echo 'Error';
	}
	
	function LoadVideos($start = 0)
	{
		if(!is_numeric($start))
		{
			$start = 0;
		}
		
		if(isset($_POST['category']) && isset($_POST['hash']))
		{
			$list = $this->Info->GetRightVideos($_POST['category'], $_POST['hash'], $start);
			
			if($list !== false)
			{
				echo $this->Info->RightVideosToHTML($list, true);
				
				exit;
			}
		}
		
		echo 'Error';
	}
	
	function Follow($followers = 0)
	{
		if(!is_numeric($followers))
		{
			$followers = 0;
		}
		
		if($followers != 0 && $this->Info->login['status'] && $this->Info->login['id'] != $followers && $this->Info->CheckSecurity())
		{
		    if(!$this->Info->CheckFollow($followers))
		    {
		    	mysql_query("INSERT INTO MS_follows (follower_id, followers_id, date) VALUES ('".$this->Info->login['id']."','".$followers."','".time()."') ;");
		    	
				$this->Info->Log('Follow user.');
				
		    	echo '[OK]';
		    	
		    	exit;
		    }
		}
		
		echo 'Error';
	}
	
	function UnFollow($followers = 0)
	{
		if(!is_numeric($followers))
		{
			$followers = 0;
		}
		
		if($followers != 0 && $this->Info->login['status'] && $this->Info->login['id'] != $followers && $this->Info->CheckSecurity())
		{
		    if($this->Info->CheckFollow($followers))
		    {
		    	mysql_query("DELETE FROM MS_follows WHERE follower_id = '".$this->Info->login['id']."' AND followers_id = '".$followers."' ;");
		    	
				$this->Info->Log('Unfollow user.');
				
		    	echo '[OK]';
		    	
		    	exit;
		    }
		}
		
		echo '[ERROR]';
	}
}
?>