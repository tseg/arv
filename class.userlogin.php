<?php

class userlogin
{
    private $db;
	public $userrole;
    function __construct($DB_con){
        $this->db = $DB_con;
    }
    
    public function register($uid,$uname,$password,$utpe){
        try {
            $new_password = password_hash($password, PASSWORD_DEFAULT);
            $stm = $this->db->prepare("INSERT INTO tbl_user_account (User_Id, User_Name, Password, User_Type) VALUES (:uid, :uname, :password, :utype)");
            $stm->bindparam(":uid", $uid);
            $ttm->bindparam(":uname", $uname);
            $stm->bindparam(":password", $new_password);
            $stm->bindparam(":utype", $utpe);
            $stm->execute();
            
            return $stm;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    public function login($uname, $password){
        try {
            $stm = $this->db->prepare("SELECT * FROM tbl_users WHERE user_name = :uname LIMIT 1");
            $stm->execute(array(':uname' => $uname));
            $userRow = $stm->fetch(PDO::FETCH_ASSOC);
            
        if($stm->rowCount() > 0){
            if(password_verify($password, $userRow['password'])){
				
                $_SESSION['user_session'] = $userRow['id'];
				$_SESSION['user_name'] = $userRow['user_name'];
				$_SESSION['role'] = $userRow['user_role'];
				$_SESSION['fullname'] = $userRow['first_name']." ".$userRow['last_name'];
				$_SESSION['email'] = $userRow['email_id'];
				$rl=$userRow['user_role'];
				if($rl=="Viewer")
				{
					header("Location:viewer_task_report_page.php");
				}
			    else if($rl=="Judge")
				{
					header("Location:judge_task_page.php");
				}
				else if($rl=="Admin")
				{
					
						header("Location:tasks.php");
					
				}
                return true;
            }
            else
            {
                return false;
            }
        }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    public function is_loggedin(){
        if(isset($_SESSION['user_session'])){
            return true;
        }
    }
    
    public function redirect($url){
        header("Location:$url");
        //die("<script>location.href = '$url'</script>");
    }
    
    public function logout(){
        session_destroy();
        unset($_SESSION['user_session']);
        return true;
    }
}
