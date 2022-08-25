<?php
class ModelAccountTicket extends Model {
	
	public function getTicketType() {
	
		$sql  = "select * from gui_status_tbl where `grouping` = 'Ticket Type'";					
		$query = $this->db->query($sql);
		return $query->rows;
	
	}

	public function getBranches() {
		$sql  = "select user_id, description from gui_branch_tbl where status = 1";
		$query = $this->db->query($sql);
		return $query->rows;
	}	

	public function insertTicket($data){
		$return_array = array();
		
		$recipient = 0;
		$branch_id = 0;
		$order_id = 0;
		
		if($data['encoded_from'] == "ticket") {
		
			switch ($data['ticket_type']) {
				case '113':

					$sql = "select branch_id from gui_branch_tbl where user_id = ".$data['branch_recipient'];
					$query = $this->db->query($sql);
					if(isset($query->row['branch_id'])) {
						$branch_id = $query->row['branch_id'];
					}
					$recipient = $data['branch_recipient']; //branch user_id

					break;
				case '114': //finance1 user_id
					$sql = "select user_id from oc_user where user_group_id = 51 limit 1 ";
					$query = $this->db->query($sql);
					$recipient = $query->row['user_id'];
					break;
				
				case '115':
					$sql = "select user_id from oc_user where user_group_id = 64 limit 1 ";
					$query = $this->db->query($sql);
					$recipient = $query->row['user_id'];
					break;
					
				case '116':
					$sql = "select user_id from oc_user where user_group_id = 63 limit 1 ";
					$query = $this->db->query($sql);
					$recipient = $query->row['user_id'];
					break;
				
				case '117':
					$sql = "select user_id from oc_user where user_group_id = 63 limit 1 ";
					$query = $this->db->query($sql);
					$recipient = $query->row['user_id'];
					break;
				case '127':
					$sql = "select user_id from oc_user where user_group_id = 71 limit 1 ";
					$query = $this->db->query($sql);
					$recipient = $query->row['user_id'];
					break;
			}
			
			$order_id = 0;
			if (isset($data['order_id'])) { 
				if(!empty($data['order_id'])) {
					$order_id = $data['order_id'];
				}
			}
			
			$concern_content = $data['ticket_text'];
			$concern_type = $data['ticket_type'];
		} else if($data['encoded_from'] == "trackorder") {	
			$sql = "select branch_id, order_id, operator_id
						from oc_order
						where ref = '".$data['ref']."' ";
			$query = $this->db->query($sql);
			if(isset($query->row['branch_id'])) {
				$branch_id = $query->row['branch_id'];
				$order_id = $query->row['order_id'];
				$operator_id = $query->row['operator_id'];
			}
			
			if($this->user->getUserGroupId() == 53) {
				$recipient = $operator_id;
			} else {			
				$sql = "select user_id 
							from gui_branch_tbl 
							where branch_id = ".$branch_id ;
				$query = $this->db->query($sql);
						
				if(isset($query->row['user_id'])) {
					$recipient = $query->row['user_id']; //branch user_id
				}
			}
			$concern_content = $data['remark'];
			$concern_type = 132;
		} else if($data['encoded_from'] == "updatestatus") {	
			$sql = "select branch_id, order_id, operator_id
						from oc_order
						where order_id = '".$data['order_id']."' ";
			$query = $this->db->query($sql);
			if(isset($query->row['branch_id'])) {
				$branch_id = $query->row['branch_id'];
				$order_id = $query->row['order_id'];
				$operator_id = $query->row['operator_id'];
			}
			
			if($this->user->getUserGroupId() == 53) {
				$recipient = $operator_id;
			} else {			
				$sql = "select user_id 
							from gui_branch_tbl 
							where branch_id = ".$branch_id ;
				$query = $this->db->query($sql);
						
				if(isset($query->row['user_id'])) {
					$recipient = $query->row['user_id']; //branch user_id
				}
			}
			$concern_content = $data['remarks'];
			$concern_type = 132;
		}
			
		$sql = "insert into oc_ticket_header
				set source = ". $this->user->getId() ."
				, recipient = " . $recipient . "
				, order_id = " . $order_id . "
				, branch_id = " . $branch_id . "
				, latest_user_id = ". $this->user->getId() . "
				, concern_content = '". $this->db->escape($concern_content) ."'
				, concern_type = " . $concern_type . "
				, date_added = '". $this->user->now() ."'
				, date_modified = '".$this->user->now()."'
				, read_flag = 0
				, read_by = ''
				, close_flag = 0";

		$this->db->query($sql);

		$ticket_id = $this->db->getLastId();
		
		if($data['encoded_from'] == "trackorder" or $data['encoded_from'] == "updatestatus") {	
			$sql = "insert into oc_ticket_content(ticket_id,source,concern_content,date_added)
					select ".$ticket_id.", user_id, remark, date_added
					from oc_order_comments 
					where order_id = ".$order_id;
			$this->db->query($sql);
		}

		$sql = "insert into oc_ticket_content 
				set ticket_id = ". $ticket_id ."
				, source = ". $this->user->getId() ."
				, concern_content = '". $this->db->escape($concern_content) ."'
				, date_added = '". $this->user->now() ."'
				, read_flag = 0
				, read_by = ''";

		$this->db->query($sql);
		
		$ticket_content_id = $this->db->getLastId();
		
		//send notifications (2018-11-12)
		//to csr main user_ids
		$sql = " select user_id from oc_user where user_group_id = 61 and status = 1 ";
		$query = $this->db->query($sql);
		foreach($query->rows as $csrm) {
			$this->insertTicketNotification($csrm['user_id'], $ticket_id, $ticket_content_id);
		}
		
		//if ticket type = delivery 
		if($concern_type == 113) {
			//if manila
			if($branch_id == 97) { //Manila Central Office
				//put notification to the manila csr
				$sql = " select user_id from oc_user where user_group_id = 69 and status = 1 ";
				$query = $this->db->query($sql);
				foreach($query->rows as $csrman) {
					$this->insertTicketNotification($csrman['user_id'], $ticket_id, $ticket_content_id);
				}
				//put notification to the branch
				$this->insertTicketNotification($recipient, $ticket_id, $ticket_content_id);
			//else 
			} else {			
				//put notification to the branch
				$this->insertTicketNotification($recipient, $ticket_id, $ticket_content_id);
			}
			
			$sql = "select owned_flag from gui_branch_tbl where branch_id = ".$branch_id;
			$query = $this->db->query($sql);
			$owned_flag = $query->row['owned_flag'];
			//if company owned branch
			if($owned_flag == 1) {
				//to company owned csr
				$sql = " select user_id from oc_user where user_group_id = 67 and status = 1 ";
				$query = $this->db->query($sql);
				foreach($query->rows as $csrco) {
					$this->insertTicketNotification($csrco['user_id'], $ticket_id, $ticket_content_id);
				}
			//else
			} else {
				//to franchise csr
				$sql = " select user_id from oc_user where user_group_id = 68 and status = 1 ";
				$query = $this->db->query($sql);
				foreach($query->rows as $csrf) {
					$this->insertTicketNotification($csrf['user_id'], $ticket_id, $ticket_content_id);
				}
			}
		
		//else if not delivery
		} else {
			$this->insertTicketNotification($recipient, $ticket_id, $ticket_content_id);
		}
		
		if($data['encoded_from'] == "trackorder" or $data['encoded_from'] == "updatestatus") {	
			$sql = "update oc_order 
					   set ticket_id = " .$ticket_id. " 
					 where order_id = ".$order_id;
			$this->db->query($sql);
		}
		
		$return_array['ticket_id'] = $ticket_id;
		$return_array['err_msg'] = "Successful Creation of Ticket Id ".$ticket_id." for your concern.";
		
		return $return_array;

	}	
	
	public function insertTicketNotification($user_id, $ticket_id, $ticket_content_id) {
		if($this->user->isLogged()) {
			if($user_id != $this->user->getUserGroupId()) {
				$sql = "insert into oc_notification 
						   set category_id = 125
							  ,user_id = ".$user_id."
							  ,ticket_id = ".$ticket_id."
							  ,ticket_content_id = ".$ticket_content_id."
							  ,created_by = ".$this->user->getId()."
							  , date_added = '".$this->user->now()."' ";
				$this->db->query($sql);
			}
		} else {
			$sql = "select user_id from oc_user where username = 'GUEST' limit 1 ";
			$query = $this->db->query($sql);
			$creator = $query->row['user_id'];
			$sql = "insert into oc_notification 
						   set category_id = 125
							  ,user_id = ".$user_id."
							  ,ticket_id = ".$ticket_id."
							  ,ticket_content_id = ".$ticket_content_id."
							  ,created_by = ".$creator."
							  , date_added = '".$this->user->now()."' ";
			$this->db->query($sql);
		}
		
	}

	public function updateTicket($data){
		if($data['encoded_from'] == "ticket") {
			$ticket_id = $data['ticket_id'];
			$concern_content = $data['ticket_text'];			
			$picture_flag = $data['picture_flag'];
		} else if($data['encoded_from'] == "trackorder") {
			$ticket_id = $data['ticket_id'];
			$concern_content = $data['remark'];			
			$picture_flag = 0;
		} else if($data['encoded_from'] == "updatestatus") {
			$ticket_id = $data['ticket_id'];
			$concern_content = $data['remarks'];			
			$picture_flag = 0;
		}
		
		if($this->user->isLogged()) {
			$user_id = $this->user->getId();
		} else {
			$sql = "select user_id from oc_user where username = 'GUEST' limit 1 ";
			$query = $this->db->query($sql);
			$user_id = $query->row['user_id'];
		}
		
		$sql = "select order_id from oc_ticket_header where ticket_id = ".$ticket_id;
		$query = $this->db->query($sql);
		$order_id = $query->row['order_id'];
		if($order_id > 0) {
			$sql = "update oc_order 
					   set remarks = '".$this->db->escape($concern_content)."' 
					 where order_id = ".$order_id;
			$query = $this->db->query($sql);
			
			$sql = "INSERT into oc_order_comments 
					   SET user_id = " . $user_id . "
					     , order_id = ". $order_id ."
						 , remark = '". $this->db->escape($concern_content) ."'
						 , date_added = '". $this->user->now() ."'";
			$query = $this->db->query($sql);
		}
				
		$sql = "update oc_ticket_header 
					set concern_content = '". $this->db->escape($concern_content) ."'
					, read_flag = 0
					, latest_user_id = ". $user_id ."
					, date_modified = '".$this->user->now()."'
					, read_by = ''
					, close_flag = 0 
					where ticket_id = " . $ticket_id;
		//echo $sql."<br>";
		$this->db->query($sql);
		
		$sql = "insert into oc_ticket_content 
				   set ticket_id = ". $ticket_id ."
					 , source = ". $user_id ."
					 , concern_content = '". $this->db->escape($concern_content) ."'
					 , date_added = '". $this->user->now() ."'
					 , picture_flag = ".$picture_flag."
					 , read_flag = 0
					 , read_by = ''";

		$this->db->query($sql);
		$ticket_content_id = $this->db->getLastId();
		
		$branch_id = 0;
		//get branch_id, ticket_id, ticket_content_id
		$sql = "select branch_id, source, recipient, concern_type from oc_ticket_header where ticket_id = ".$ticket_id;
		$query = $this->db->query($sql);
		$branch_id = $query->row['branch_id'];
		$concern_type = $query->row['concern_type'];
		$source = $query->row['source'];
		$recipient = $query->row['recipient'];
		
		//call the ticket notification even if the 
		$this->insertTicketNotification($source, $ticket_id, $ticket_content_id);

		//call the ticket notification even if the 
		$this->insertTicketNotification($recipient, $ticket_id, $ticket_content_id);			
		
		//to csr main user_ids
		$sql = " select user_id from oc_user where user_group_id = 61 and status = 1 ";
		$query = $this->db->query($sql);
		foreach($query->rows as $csrm) {
			$this->insertTicketNotification($csrm['user_id'], $ticket_id, $ticket_content_id);
		}
		
		//if ticket type = delivery 
		if($concern_type == 113) {
			//if manila
			if($branch_id == 97) { //Manila Central Office
				//put notification to the manila csr
				$sql = " select user_id from oc_user where user_group_id = 69 and status = 1 ";
				$query = $this->db->query($sql);
				foreach($query->rows as $csrman) {
					$this->insertTicketNotification($csrman['user_id'], $ticket_id, $ticket_content_id);
				}
			//else 
			} 
			
			$sql = "select owned_flag from gui_branch_tbl where branch_id = ".$branch_id;
			$query = $this->db->query($sql);
			$owned_flag = $query->row['owned_flag'];
			//if company owned branch
			if($owned_flag == 1) {
				//to company owned csr
				$sql = " select user_id from oc_user where user_group_id = 67 and status = 1 ";
				$query = $this->db->query($sql);
				foreach($query->rows as $csrco) {
					$this->insertTicketNotification($csrco['user_id'], $ticket_id, $ticket_content_id);
				}
			//else
			} else {
				//to franchise csr
				$sql = " select user_id from oc_user where user_group_id = 68 and status = 1 ";
				$query = $this->db->query($sql);
				foreach($query->rows as $csrf) {
					$this->insertTicketNotification($csrf['user_id'], $ticket_id, $ticket_content_id);
				}
			}
		} 
		
		return $this->db->getLastId();
	}
	
	public function getTicketHeaderList($data, $query_type){

		if($this->user->getUserGroupId() == 61){
			$sql = "select a.ticket_id, concat(b.firstname , ' ', b.lastname) source_name, b.username, a.branch_id, d.description branch_name,
						   a.concern_content, c.description concern_type, a.date_added, a.order_id, a.recipient, 
						   case when a.close_flag = 1 then 'Completed' else 'Ongoing' end as close_flag, a.date_modified,
						   concat(e.firstname , ' ', e.lastname) latest_name, e.username latest_username
					  from oc_ticket_header a 
					  join oc_user b on (a.source = b.user_id) 
					  join gui_status_tbl c on (a.concern_type = c.status_id) 
					  left join gui_branch_tbl d on (a.branch_id = d.branch_id)
					  left join oc_user e on (a.latest_user_id = e.user_id) 
					 where a.close_flag = 0 ";					 
		} else if($this->user->getUserGroupId() == 67){
			$sql = "select a.ticket_id, concat(b.firstname , ' ', b.lastname) source_name, b.username, a.branch_id, d.description branch_name,
						   a.concern_content, c.description concern_type, a.date_added, a.order_id, a.recipient, 
						   case when a.close_flag = 1 then 'Completed' else 'Ongoing' end as close_flag, a.date_modified,
						   concat(e.firstname , ' ', e.lastname) latest_name, e.username latest_username
					  from oc_ticket_header a 
					  join oc_user b on (a.source = b.user_id) 
					  join gui_status_tbl c on (a.concern_type = c.status_id) 
					  left join gui_branch_tbl d on (a.branch_id = d.branch_id)
					  left join oc_user e on (a.latest_user_id = e.user_id) 
					 where a.close_flag = 0 and d.owned_flag = 1 ";
		} else if($this->user->getUserGroupId() == 68){
			$sql = "select a.ticket_id, concat(b.firstname , ' ', b.lastname) source_name, b.username, a.branch_id, d.description branch_name,
						   a.concern_content, c.description concern_type, a.date_added, a.order_id, a.recipient, 
						   case when a.close_flag = 1 then 'Completed' else 'Ongoing' end as close_flag, a.date_modified,
						   concat(e.firstname , ' ', e.lastname) latest_name, e.username latest_username
					  from oc_ticket_header a 
					  join oc_user b on (a.source = b.user_id) 
					  join gui_status_tbl c on (a.concern_type = c.status_id) 
					  left join gui_branch_tbl d on (a.branch_id = d.branch_id)
					  left join oc_user e on (a.latest_user_id = e.user_id) 
					 where a.close_flag = 0 and d.owned_flag = 0 ";					 
		} else if($this->user->getUserGroupId() == 69){
			$sql = "select a.ticket_id, concat(b.firstname , ' ', b.lastname) source_name, b.username, a.branch_id, d.description branch_name,
						   a.concern_content, c.description concern_type, a.date_added, a.order_id, a.recipient, 
						   case when a.close_flag = 1 then 'Completed' else 'Ongoing' end as close_flag, a.date_modified,
						   concat(e.firstname , ' ', e.lastname) latest_name, e.username latest_username
					  from oc_ticket_header a 
					  join oc_user b on (a.source = b.user_id) 
					  join gui_status_tbl c on (a.concern_type = c.status_id) 
					  left join gui_branch_tbl d on (a.branch_id = d.branch_id)
					  left join oc_user e on (a.latest_user_id = e.user_id) 
					 where a.close_flag = 0 and a.branch_id = 97 ";	
		} else if($this->user->getUserGroupId() == 72){
			$sql = "select created_by from oc_user where user_id = ".$this->user->getId();
			$query = $this->db->query($sql);
			$creator = $query->row['created_by'];
			
			$sql = "select a.ticket_id, concat(b.firstname , ' ', b.lastname) source_name, b.username, a.branch_id, d.description branch_name,
						   a.concern_content, c.description concern_type, a.date_added, a.order_id, a.recipient, 
						   case when a.close_flag = 1 then 'Completed' else 'Ongoing' end as close_flag, a.date_modified,
						   concat(e.firstname , ' ', e.lastname) latest_name, e.username latest_username
					  from oc_ticket_header a 
					  join oc_user b on (a.source = b.user_id) 
					  join gui_status_tbl c on (a.concern_type = c.status_id) 
					  left join gui_branch_tbl d on (a.branch_id = d.branch_id)
					  left join oc_user e on (a.latest_user_id = e.user_id)
					 where (a.recipient = " . $creator . " or a.source = " . $creator . ") 
					   and a.close_flag = 0 ";
					 
		} else {
			
			//var_dump ($data);
			$sql = "select a.ticket_id, concat(b.firstname , ' ', b.lastname) source_name, b.username, a.branch_id, d.description branch_name,
						   a.concern_content, c.description concern_type, a.date_added, a.order_id, a.recipient, 
						   case when a.close_flag = 1 then 'Completed' else 'Ongoing' end as close_flag, a.date_modified,
						   concat(e.firstname , ' ', e.lastname) latest_name, e.username latest_username
					  from oc_ticket_header a 
					  join oc_user b on (a.source = b.user_id) 
					  join gui_status_tbl c on (a.concern_type = c.status_id) 
					  left join gui_branch_tbl d on (a.branch_id = d.branch_id)
					  left join oc_user e on (a.latest_user_id = e.user_id)
					 where (a.recipient = " . $this->user->getId() . " or a.source = " . $this->user->getId() . ") 
					   and a.close_flag = 0 ";
		}
		
	
		if(isset($data['ticket_id_search'])){
			if(!empty($data['ticket_id_search'])){
				$sql .= " and a.ticket_id = " . $data['ticket_id_search'];
			}
		}
		
		if(isset($data['sender_name'])){
			if(!empty($data['sender_name'])){
				$sql .= " and lower(concat(b.firstname, ' ', b.lastname)) like '%" . $data['sender_name']."%'";
			}
		}
	
		if(isset($data['ticket_type_search'])){
			if(!empty($data['ticket_type_search'])){
				$sql .= " and a.concern_type = " . $data['ticket_type_search'];
			}
		}
		
		if(isset($data['close_flag'])){
			if(!empty($data['close_flag'])){
				$sql .= " and a.close_flag = " . $data['close_flag'];
			}
		}
		if(isset($data['picture_flag'])){
			if(!empty($data['picture_flag'])){
				$sql .= " and a.picture_flag = " . $data['picture_flag'];
			}
		}
		
		if(isset($data['datefrom'])){
			if(!empty($data['datefrom'])){
				$sql .= " and a.date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}
		}			
		
		if(isset($data['dateto'])){
			if(!empty($data['dateto'])){
				$sql .= " and a.date_added <= '" . $data['dateto'] . " 23:59:59'";
			}
		}

		if($query_type == "data") {
			
			$sql .= " order by a.date_modified desc ";
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 6;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			//echo $sql;
			$query = $this->db->query($sql);
			$newRows = array();
			$counter=0;
			foreach($query->rows as $datum){
				$sql = "select count(1) unread_comments 
						  from oc_notification 
						 where ticket_id = ".$datum['ticket_id']." 
						   and user_id = ".$this->user->getId()." 
						   and read_flag = 0 ";
				$query = $this->db->query($sql);
				$unread_comments = $query->row['unread_comments'];	
				
				$finalDatum = $datum;
				$finalDatum['unread_comments'] = $unread_comments;
				$newRows[$counter] = $finalDatum;
				$counter++;
			}

			return $newRows;
		} else {
			$sqlt = "select count(1) total from (".$sql.") t";
			$result = $this->db->query($sqlt);
			return $result->row['total'];			
		}

	}
	
	public function TicketToCsv($data) {
		
		 if($this->user->getUserGroupId() == 61){
			$sql = "select a.ticket_id, concat(b.firstname , ' ', b.lastname) source_name, b.username, concat(e.firstname , ' ', e.lastname) latest_name, e.username latest_username , a.branch_id, d.description branch_name,
						   c.description concern_type, a.concern_content, a.date_added, a.order_id, a.recipient,
						   case when a.close_flag = 1 then 'Completed' else 'Ongoing' end as close_flag
					  from oc_ticket_header a 
					  join oc_user b on (a.source = b.user_id) 
					  join gui_status_tbl c on (a.concern_type = c.status_id) 
					  left join gui_branch_tbl d on (a.branch_id = d.branch_id)
					  left join oc_user e on (a.latest_user_id = e.user_id) 
					 where a.close_flag = 0 ";
					 
					 	
		if(isset($data['ticket_id_search'])){
			if(!empty($data['ticket_id_search'])){
				$sql .= " and a.ticket_id = " . $data['ticket_id_search'];
			}
		}
		
		if(isset($data['sender_name'])){
			if(!empty($data['sender_name'])){
				$sql .= " and lower(concat(b.firstname, ' ', b.lastname)) like '%" . $data['sender_name']."%'";
			}
		}
	
		if(isset($data['ticket_type_search'])){
			if(!empty($data['ticket_type_search'])){
				$sql .= " and a.concern_type = " . $data['ticket_type_search'];
			}
		}
		
		if(isset($data['close_flag'])){
			if(!empty($data['close_flag'])){
				$sql .= " and a.close_flag = " . $data['close_flag'];
			}
		}
		if(isset($data['picture_flag'])){
			if(!empty($data['picture_flag'])){
				$sql .= " and a.picture_flag = " . $data['picture_flag'];
			}
		}
		
		if(isset($data['datefrom'])){
			if(!empty($data['datefrom'])){
				$sql .= " and a.date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}
		}			
		
		if(isset($data['dateto'])){
			if(!empty($data['dateto'])){
				$sql .= " and a.date_added <= '" . $data['dateto'] . " 23:59:59'";
			}
		}
					 		 		
		$result = $this->db->query($sql);
		return $result->rows;
		} else {
			$sql = "select a.ticket_id, concat(b.firstname , ' ', b.lastname) source_name, b.username, concat(e.firstname , ' ', e.lastname) latest_name, e.username latest_username , a.branch_id, d.description branch_name,
						   c.description concern_type, a.concern_content, a.date_added, a.order_id, a.recipient,
						   case when a.close_flag = 1 then 'Completed' else 'Ongoing' end as close_flag
					  from oc_ticket_header a 
					  join oc_user b on (a.source = b.user_id) 
					  join gui_status_tbl c on (a.concern_type = c.status_id) 
					  left join gui_branch_tbl d on (a.branch_id = d.branch_id)
					  left join oc_user e on (a.latest_user_id = e.user_id)
					 where (a.recipient = " . $this->user->getId() . " or a.source = " . $this->user->getId() . ") 
					   and a.close_flag = 0 ";
					   
			
		if(isset($data['ticket_id_search'])){
			if(!empty($data['ticket_id_search'])){
				$sql .= " and a.ticket_id = " . $data['ticket_id_search'];
			}
		}
		
		if(isset($data['sender_name'])){
			if(!empty($data['sender_name'])){
				$sql .= " and lower(concat(b.firstname, ' ', b.lastname)) like '%" . $data['sender_name']."%'";
			}
		}
	
		if(isset($data['ticket_type_search'])){
			if(!empty($data['ticket_type_search'])){
				$sql .= " and a.concern_type = " . $data['ticket_type_search'];
			}
		}
		
		if(isset($data['close_flag'])){
			if(!empty($data['close_flag'])){
				$sql .= " and a.close_flag = " . $data['close_flag'];
			}
		}
		if(isset($data['picture_flag'])){
			if(!empty($data['picture_flag'])){
				$sql .= " and a.picture_flag = " . $data['picture_flag'];
			}
		}
		
		if(isset($data['datefrom'])){
			if(!empty($data['datefrom'])){
				$sql .= " and a.date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}
		}			
		
		if(isset($data['dateto'])){
			if(!empty($data['dateto'])){
				$sql .= " and a.date_added <= '" . $data['dateto'] . " 23:59:59'";
			}
		}
					   
		$result = $this->db->query($sql);
		return $result->rows;
		}
				
	}
	
	public function getcloseTicketHeaderList($data, $query_type){
		
		//var_dump($data);
		//echo $data['ticket_id_search'];

		if($this->user->getUserGroupId() == 61){
			$sql = "select a.ticket_id, concat(b.firstname , ' ', b.lastname) source_name, b.username, a.branch_id, d.description branch_name,
						   a.concern_content, c.description concern_type, a.date_added, a.order_id, a.recipient, a.date_closed, 
						   concat(f.firstname , ' ', f.lastname) close_by,
						   case when a.close_flag = 1 then 'Completed' else 'Ongoing' end as close_flag,
						   concat(e.firstname , ' ', e.lastname) latest_name, e.username latest_username
					  from oc_ticket_header a 
					  join oc_user b on (a.source = b.user_id) 
					  join gui_status_tbl c on (a.concern_type = c.status_id) 
					  left join gui_branch_tbl d on (a.branch_id = d.branch_id)
					  left join oc_user e on (a.latest_user_id = e.user_id)
					  left join oc_user f on (a.close_by = f.user_id)
					 where a.close_flag = 1 ";
		} else if($this->user->getUserGroupId() == 72){
			$sql = "select created_by from oc_user where user_id = ".$this->user->getId();
			$query = $this->db->query($sql);
			$creator = $query->row['created_by'];
			
			$sql = "select a.ticket_id, concat(b.firstname , ' ', b.lastname) source_name, b.username, a.branch_id, d.description branch_name,
						   a.concern_content, c.description concern_type, a.date_added, a.order_id, a.recipient, a.date_closed,
						   concat(f.firstname , ' ', f.lastname) close_by,
						   case when a.close_flag = 1 then 'Completed' else 'Ongoing' end as close_flag,
						   concat(e.firstname , ' ', e.lastname) latest_name, e.username latest_username
					  from oc_ticket_header a 
					  join oc_user b on (a.source = b.user_id) 
					  join gui_status_tbl c on (a.concern_type = c.status_id) 
					  left join gui_branch_tbl d on (a.branch_id = d.branch_id)
					  left join oc_user e on (a.latest_user_id = e.user_id)
					  left join oc_user f on (a.close_by = f.user_id)
					 where (a.recipient = " . $creator . " or a.source = " . $creator . ") 
					   and a.close_flag = 1 ";
		} else {
			
			$sql = "select a.ticket_id, concat(b.firstname , ' ', b.lastname) source_name, b.username, a.branch_id, d.description branch_name,
						   a.concern_content, c.description concern_type, a.date_added, a.order_id, a.recipient, a.date_closed,
						   concat(f.firstname , ' ', f.lastname) close_by,
						   case when a.close_flag = 1 then 'Completed' else 'Ongoing' end as close_flag,
						   concat(e.firstname , ' ', e.lastname) latest_name, e.username latest_username
					  from oc_ticket_header a 
					  join oc_user b on (a.source = b.user_id) 
					  join gui_status_tbl c on (a.concern_type = c.status_id) 
					  left join gui_branch_tbl d on (a.branch_id = d.branch_id)
					  left join oc_user e on (a.latest_user_id = e.user_id)
					  left join oc_user f on (a.close_by = f.user_id)
					 where (a.recipient = " . $this->user->getId() . " or a.source = " . $this->user->getId() . ") 
					   and a.close_flag = 1 ";
		}
	
		
		if(isset($data['ticket_id_search'])){
			if(!empty($data['ticket_id_search'])){
				$sql .= " and a.ticket_id = " . $data['ticket_id_search'];
			}
		}
		
		if(isset($data['sender_name'])){
			if(!empty($data['sender_name'])){
				$sql .= " and lower(concat(b.firstname, ' ', b.lastname)) like '%" . $data['sender_name']."%'";
			}
		}
	
		if(isset($data['ticket_type_search'])){
			if(!empty($data['ticket_type_search'])){
				$sql .= " and a.concern_type = " . $data['ticket_type_search'];
			}
		}
		
		if(isset($data['picture_flag'])){
			if(!empty($data['picture_flag'])){
				$sql .= " and a.picture_flag = " . $data['picture_flag'];
			}
		}
		
		if(isset($data['datefrom'])){
			if(!empty($data['datefrom'])){
				$sql .= " and a.date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}
		}			
		
		if(isset($data['dateto'])){
			if(!empty($data['dateto'])){
				$sql .= " and a.date_added <= '" . $data['dateto'] . " 23:59:59'";
			}
		}

		if($query_type == "data") {
			
			$sql .= " order by a.ticket_id desc";
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 6;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$result = $this->db->query($sql);
			return $result->rows;
		} else {
			$sqlt = "select count(1) total from (".$sql.") t";
			$result = $this->db->query($sqlt);
			return $result->row['total'];			
		}

	}
	
	public function CloseTicketToCsv($data) {
		
		 if($this->user->getUserGroupId() == 61){
			$sql = "select a.ticket_id, concat(b.firstname , ' ', b.lastname) source_name, b.username, concat(e.firstname , ' ', e.lastname) latest_name, e.username latest_username , a.branch_id, d.description branch_name,
						   c.description concern_type, a.concern_content, a.date_added, a.order_id, a.recipient,
						   case when a.close_flag = 1 then 'Completed' else 'Ongoing' end as close_flag, a.date_closed
					  from oc_ticket_header a 
					  join oc_user b on (a.source = b.user_id) 
					  join gui_status_tbl c on (a.concern_type = c.status_id) 
					  left join gui_branch_tbl d on (a.branch_id = d.branch_id)
					  left join oc_user e on (a.latest_user_id = e.user_id) 
					 where a.close_flag = 1 ";
					 
					 	
		if(isset($data['ticket_id_search'])){
			if(!empty($data['ticket_id_search'])){
				$sql .= " and a.ticket_id = " . $data['ticket_id_search'];
			}
		}
		
		if(isset($data['sender_name'])){
			if(!empty($data['sender_name'])){
				$sql .= " and lower(concat(b.firstname, ' ', b.lastname)) like '%" . $data['sender_name']."%'";
			}
		}
	
		if(isset($data['ticket_type_search'])){
			if(!empty($data['ticket_type_search'])){
				$sql .= " and a.concern_type = " . $data['ticket_type_search'];
			}
		}
		
		if(isset($data['close_flag'])){
			if(!empty($data['close_flag'])){
				$sql .= " and a.close_flag = " . $data['close_flag'];
			}
		}
		if(isset($data['picture_flag'])){
			if(!empty($data['picture_flag'])){
				$sql .= " and a.picture_flag = " . $data['picture_flag'];
			}
		}
		
		if(isset($data['datefrom'])){
			if(!empty($data['datefrom'])){
				$sql .= " and a.date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}
		}			
		
		if(isset($data['dateto'])){
			if(!empty($data['dateto'])){
				$sql .= " and a.date_added <= '" . $data['dateto'] . " 23:59:59'";
			}
		}
					 		 		
		$result = $this->db->query($sql);
		return $result->rows;
		} else {
			$sql = "select a.ticket_id, concat(b.firstname , ' ', b.lastname) source_name, b.username, concat(e.firstname , ' ', e.lastname) latest_name, e.username latest_username , a.branch_id, d.description branch_name,
						   c.description concern_type, a.concern_content, a.date_added, a.order_id, a.recipient,
						   case when a.close_flag = 1 then 'Completed' else 'Ongoing' end as close_flag, a.date_closed
					  from oc_ticket_header a 
					  join oc_user b on (a.source = b.user_id) 
					  join gui_status_tbl c on (a.concern_type = c.status_id) 
					  left join gui_branch_tbl d on (a.branch_id = d.branch_id)
					  left join oc_user e on (a.latest_user_id = e.user_id)
					 where (a.recipient = " . $this->user->getId() . " or a.source = " . $this->user->getId() . ") 
					   and a.close_flag = 1 ";
					   
			
		if(isset($data['ticket_id_search'])){
			if(!empty($data['ticket_id_search'])){
				$sql .= " and a.ticket_id = " . $data['ticket_id_search'];
			}
		}
		
		if(isset($data['sender_name'])){
			if(!empty($data['sender_name'])){
				$sql .= " and lower(concat(b.firstname, ' ', b.lastname)) like '%" . $data['sender_name']."%'";
			}
		}
	
		if(isset($data['ticket_type_search'])){
			if(!empty($data['ticket_type_search'])){
				$sql .= " and a.concern_type = " . $data['ticket_type_search'];
			}
		}
		
		if(isset($data['close_flag'])){
			if(!empty($data['close_flag'])){
				$sql .= " and a.close_flag = " . $data['close_flag'];
			}
		}
		if(isset($data['picture_flag'])){
			if(!empty($data['picture_flag'])){
				$sql .= " and a.picture_flag = " . $data['picture_flag'];
			}
		}
		
		if(isset($data['datefrom'])){
			if(!empty($data['datefrom'])){
				$sql .= " and a.date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}
		}			
		
		if(isset($data['dateto'])){
			if(!empty($data['dateto'])){
				$sql .= " and a.date_added <= '" . $data['dateto'] . " 23:59:59'";
			}
		}
					   
		$result = $this->db->query($sql);
		return $result->rows;
		}
				
	}

	public function getTicketDetails($ticket_id){
		
		$sql = "update oc_notification set read_flag = 1 where ticket_id = ".$ticket_id." and user_id = ".$this->user->getId();
		$this->db->query($sql);

		$sql = "select a.ticket_id, a.ticket_content_id, concat(b.firstname, ' ', b.lastname) source_name, b.username,
					   a.concern_content, a.date_added, a.picture_flag
				from oc_ticket_content a 
				join oc_user b on(a.source = b.user_id)
				where a.ticket_id = " . $ticket_id . " order by a.ticket_content_id desc ";

		$query = $this->db->query($sql);

		return $query->rows;

	}
	
	

	public function getTicketHeaderDetails($id){

		$sql = "select a.ticket_id, c.description concern_type_desc, a.concern_type, b.username source, a.order_id, 
					   d.username assigned_to, d.contact assigned_contact, a.concern_content, a.close_flag, b.contact sender_contact,
					   case when a.close_flag = 1 then 'Closed' else 'Ongoing' end close_flag_desc
				  from oc_ticket_header a
				  join oc_user b on(a.source = b.user_id)
				  join gui_status_tbl c on(a.concern_type = c.status_id)
				  left join oc_user d on(a.recipient = d.user_id)
				 where a.ticket_id = " . $id;

		$query = $this->db->query($sql);

		return $query->row;

	}

	public function tagClosed($data){
		$sql = "update oc_ticket_header
			set close_flag = 1 
			, date_closed = '". $this->user->now() ."'
			where ticket_id = " . $data['ticket_id'];
		$query = $this->db->query($sql);
		
		$sql = "update oc_notification 
				   set read_flag = 1 
				 where ticket_id = " . $data['ticket_id'];
		$query = $this->db->query($sql);

	}

	public function getOpenTickets(){

		$sql = "select count(1) total from oc_ticket_header where close_flag = 0 and (recipient = " . $this->user->getId() . " or source = " . $this->user->getId() . ")";

		$query = $this->db->query($sql);

		return $query->row['total'];

	}
	
	public function getOpenTicketNotification() {
		if($this->user->getUserGroupId() == 72){
			$sql = "select created_by from oc_user where user_id = ".$this->user->getId();
			$query = $this->db->query($sql);
			$user_id = $query->row['created_by'];
			$sql = "select count(1) total from oc_notification where user_id = ".$user_id." and read_flag = 0 ";
			$query = $this->db->query($sql);
		} else {	
			$sql = "select count(1) total from oc_notification where user_id = ".$this->user->getId()." and read_flag = 0 ";
			$query = $this->db->query($sql);
		}
		//echo $sql;
		return $query->row['total'];
	}

	public function getOrderHistoryForTicketDetails($ticket_id){

		$sql = "select b.remarks, b.date_added
				  from oc_ticket_header a 
				  join oc_order_hist_tbl b on (a.order_id = b.order_id)
				 where a.ticket_id = " . $ticket_id . " order by b.date_added desc " ;

		$query = $this->db->query($sql);
		
		return $query->rows;
		
	}
}
?>