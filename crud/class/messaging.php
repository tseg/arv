<?php

class Messaging
{
    // database connection and table name
    private $conn;
    private $table_name = "tbl_msg";

    // object properties
    public $id;
    public $title;
    public $body;
    public $sender;
	public $receiver;
    public $senderLevel;
    public $readed;
	public $date;
	

    public function __construct($db)
    {
        $this->conn = $db;
    }
	

	function SendMessege($title ,$body ,$sender ,$receiver ,$role, $date)
	{
			
		if( strlen($title) == 0 )
			return 1;
		if( strlen($body) == 0 )
			return 2;
		if( strlen($sender) == 0 )
			return 3;
		//if( strlen($receiver) == 0 )
			//return 4;
		if( strlen($role) == 0)
			return 5;
		/*$result = mysql_query("INSERT INTO ".$this->tblName." (title,body,sender,receiver,senderLevel,readed) VALUES ('$title','$body',$sender,$receiver,$senderLevel,0)");
		if($result)
			return 0;
		else 	
			return 6;
		*/
		  
        $query = "INSERT INTO " . $this->table_name . " SET title = ?, body = ?, sender = ?, receiver = ?, role = ?, date = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $title);
        $stmt->bindParam(2, $body);
        $stmt->bindParam(3, $sender);
		$stmt->bindParam(4, $receiver);
        $stmt->bindParam(5, $role);
		$stmt->bindParam(6, $date);
		

        if ($stmt->execute()) {
            return 0;
        } else {
            return 6;
        }
	}

	function GetTitle($msgId)
	{
		if(strlen($msgId) == 0)
			return 1;
		$result = mysql_query("SELECT title FROM ".$this->tblName." WHERE idMsg=$msgId");
		if(mysql_num_rows($result) == 0)
			return 2;
		$row = mysql_fetch_object($result);
		return $row->title;
	}
	

	function GetBody($msgId)
	{
		if(strlen($msgId) == 0)
			return 1;
		$result = mysql_query("SELECT body FROM ".$this->tblName." WHERE idMsg=$msgId");
		if(mysql_num_rows($result) == 0)
			return 2;
		$row = mysql_fetch_object($result);
		return $row->body;
	}

	function GetSenderID($msgId)
	{
		if(strlen($msgId) == 0)
			return 1;
		$result = mysql_query("SELECT sender FROM ".$this->tblName." WHERE idMsg=$msgId");
		if(mysql_num_rows($result) == 0)
			return 2;
		$row = mysql_fetch_object($result);
		return $row->sender;
	}

	function GetReceiverID($msgId)
	{
		if(strlen($msgId) == 0)
			return 1;
		$result = mysql_query("SELECT receiver FROM ".$this->tblName." WHERE idMsg=$msgId");
		if(mysql_num_rows($result) == 0)
			return 2;
		$row = mysql_fetch_object($result);
		return $row->receiver;
	}
	
	function GetByReceiverID($reciverId)
	{
		if(strlen($reciverId) == 0)
			return 1;
		
		$query = "SELECT * FROM  " . $this->table_name . " WHERE receiver = ? ORDER BY date DESC ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $reciverId);
        $stmt->execute();
		
		return $stmt;
	}
	
	function GetBySenderID($senderId)
	{
		if(strlen($senderId) == 0)
			return 1;
		$role = "Template";
		$query = "SELECT * FROM  " . $this->table_name . " WHERE sender = ? AND role != ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $senderId);
		$stmt->bindParam(2, $role);
        $stmt->execute();
		
		return $stmt;
	}
	
	function GetTemplates($senderId, $role)
	{
		if(strlen($senderId) == 0)
			return 1;
		
		$query = "SELECT * FROM  " . $this->table_name . " WHERE sender = ? AND role = ? ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $senderId);
		$stmt->bindParam(2, $role);
        $stmt->execute();
		
		return $stmt;
	}
	
	function GetMsgByID($Id)
	{
		if(strlen($Id) == 0)
			return 1;
		
		$query = "SELECT * FROM  " . $this->table_name . " WHERE idMsg = ? ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $Id);
        $stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}

	function GetSendDate( $msgId ){
		if(strlen($msgId) == 0)
			return 1;
		$result = mysql_query("SELECT date FROM ".$this->tblName." WHERE idMsg=$msgId");
		if(mysql_num_rows($result) == 0)
			return 2;
		$row = mysql_fetch_object($result);
		return $row->date;
	}

	function GetMessege($msgId)
	{
		$messege = array();
		if(strlen($msgId) == 0)
			return 1;
		$result = mysql_query("SELECT * FROM ".$this->tblName." WHERE idMsg=$msgId");
		if(mysql_num_rows($result) == 0)
			return 2;
		$row = mysql_fetch_object($result);
		$messege['receiver'] = $row->receiver;
		$messege['sender'] = $row->sender;
		$messege['title'] = $row->title;
		$messege['body'] = $row->body;
		$messege['senderLevel'] = $row->senderLevel;
		$messege['readed'] = $row->readed;
		$messege['date'] = $row->date;
		return $messege;
	}
	

	function MarkAsRead($msgId)
	{
		if(strlen($msgId) == 0)
			return 1;
		$result = mysql_query("UPDATE ".$this->tblName." SET readed=1 WHERE idMsg=$msgId");
		if($result)
			return 0;
		else 
			return 2;
	}
	

	function GetAllMesseges($order = 0, $receiver = '', $sender = '')
	{
		switch( $order )
		{
			case 0:
				$order = 'senderLevel ASC';
			case 1:
				$order = 'senderLevel DESC';
			case 2:
				$order = 'readed ASC';
			case 3:
				$order = 'readed DESC';
		}
		$where = '';
		if(strlen($receiver) > 0 && strlen($sender) > 0)
			$where = ' AND ';
		
		$where = ((strlen($receiver) > 0)?'receiver=' . $receiver:'') . $where . ((strlen($sender) > 0)?'sender=' . $sender:'');
		
		$result = @mysql_query("SELECT * FROM ".$this->tblName." WHERE $where ORDER BY $order");
		
		if( !$result )
			return 1;
		echo $num = mysql_num_rows($result);
		$messege = '';
		for($i = 0 ; $i < $num ; $i++ )
		{
			$row = mysql_fetch_object($result);
			$messege[$i]['receiver'] = $row->receiver;
			$messege[$i]['sender'] = $row->sender;
			$messege[$i]['title'] = $row->title;
			$messege[$i]['body'] = $row->body;
			$messege[$i]['senderLevel'] = $row->senderLevel;
			$messege[$i]['readed'] = $row->readed;	
			$messege[$i]['date'] = $row->date;
		}
		if( !is_array($messege) )
			return 2;
		return $messege;
			
		
	}


	function DeleteMessege($msgId)
	{
		if(strlen($msgId) == 0)
			return 1;
		$result = mysql_query("DELETE FROM ".$this->tblName." WHERE idMsg=$msgId");
		if($result)
			return 0;
		else 
			return 2;
	}

	
}
?>