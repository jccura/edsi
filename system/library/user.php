<?php
final class User {
	private $user_id;
	private $username;
	private $serial_code;
	private $user_group_id;
	private $name;
	private $branch;
	private $branch_id;
	private $user_group;
	private $legacy_branch;
	private $legacy_branch_id;
  	private $permission = array();
  	private $customer = array();
	private $redirect_page;
	private $firstname;
	private $middlename;
	private $lastname;
	private $birthdate;
	private $profession;
	private $gender;
	private $mobile;
	private $home;
	private $email;
	private $upline;
	private $serial;
	private $code;
	private $customer_id;
	private $rank_img;
	private $rank_id;
	private $rank;
	private $group_code;
	private $id_no;
	private $ewallet;
	private $contact;
	private $address;
	private $province_id;
	private $city_municipality_id;
	private $baranggay_id;
	private $landmark;
	private $province_desc;
	private $city_municipality_desc;
	private $barangay_desc;
	private $refer_by_id;
	private $operator_id;
	private $sales_rep_id;
	private $e_seller_id;
	private $admin_id;
	private $auto_spill_setup;
	private $paid_flag = 0;
	private $activation_flag = 0;
	private $area_id;
	private $permission_level;
	private $site = WEBSITE;

  	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
		$this->log = $registry->get('log');
		
    	if (isset($this->session->data['user_id'])) {
			
			$sql = "select a.user_id, concat(a.firstname, ' ', a.lastname) name 
						, a.code group_code, b.name user_group, a.firstname, a.middlename, a.lastname  
						, b.redirect_page, a.gender, a.id_no, a.ewallet, b.user_group_id, a.username 
						, a.paid_flag, a.contact, a.activation_flag, a.email, a.refer_by_id
						, a.address, a.province_id, a.city_municipality_id, a.barangay_id, a.landmark
						, c.description province_desc, d.description city_municipality_desc
						, e.description barangay_desc, a.branch_id, a.operator_id, a.sales_rep_id, a.e_seller_id
						,a.admin_id, a.permission_level, a.area_id, a.rank_id, f.description `rank`,a.profile_pic_ext
					from oc_user a  
					join oc_user_group b on (b.user_group_id = a.user_group_id) 
					left join oc_provinces c on (c.province_id = a.province_id) 
					left join oc_city_municipality d on (d.city_municipality_id = a.city_municipality_id) 
					left join oc_barangays e on (e.barangay_id = a.barangay_id) 
					left join oc_ranks f on (f.rank_id = a.rank_id) 
					where a.user_id = " .(int)$this->session->data['user_id']."
						and a.status = 1 
						and a.site = '".$this->site."'";		

			$user_query = $this->db->query($sql);
			
			if ($user_query->num_rows) {				
				$this->user_id = $user_query->row['user_id'];
				$this->user_id = $user_query->row['user_id'];
				$this->id_no = $user_query->row['id_no'];
				$this->username = $user_query->row['username'];
				$this->user_group_id = $user_query->row['user_group_id'];			
				$this->name = $user_query->row['name'];
				$this->user_group = $user_query->row['user_group'];
				$this->redirect_page = $user_query->row['redirect_page'];
				$this->group_code = $user_query->row['group_code'];
				$this->firstname = $user_query->row['firstname'];
				$this->middlename = $user_query->row['middlename'];
				$this->lastname = $user_query->row['lastname'];
				$this->ewallet = $user_query->row['ewallet'];				
				$this->paid_flag = $user_query->row['paid_flag'];				
				$this->contact = $user_query->row['contact'];				
				$this->address = $user_query->row['address'];				
				$this->province_id = $user_query->row['province_id'];				
				$this->province_desc = $user_query->row['province_desc'];				
				$this->city_municipality_id = $user_query->row['city_municipality_id'];				
				$this->city_municipality_desc = $user_query->row['city_municipality_desc'];				
				$this->barangay_id = $user_query->row['barangay_id'];				
				$this->landmark = $user_query->row['landmark'];				
				$this->refer_by_id = $user_query->row['refer_by_id'];				
				$this->barangay_desc = $user_query->row['barangay_desc'];				
				$this->activation_flag = $user_query->row['activation_flag'];				
				$this->email = $user_query->row['email'];				
				$this->branch_id = $user_query->row['branch_id'];				
				$this->operator_id = $user_query->row['operator_id'];
				$this->sales_rep_id = $user_query->row['sales_rep_id'];
				$this->e_seller_id = $user_query->row['e_seller_id'];
				$this->admin_id = $user_query->row['admin_id'];
				$this->permission_level = $user_query->row['permission_level'];		
				$this->area_id = $user_query->row['area_id'];				
				$this->rank_id = $user_query->row['rank_id'];				
				$this->rank = $user_query->row['rank'];				
				$this->profile_pic_ext = $user_query->row['profile_pic_ext'];				
			} else {
				$this->logout();
			}
    	}
  	}
		
  	public function login($username, $password) {
		$sql = "select a.user_id, concat(a.firstname, ', ', a.lastname) name 
						, a.code group_code, b.name user_group, a.firstname, a.middlename, a.lastname  
						, b.redirect_page, a.gender, a.id_no, a.ewallet, b.user_group_id, a.username, a.permission_level,a.profile_pic_ext
					from oc_user a  
					join oc_user_group b on (b.user_group_id = a.user_group_id) 
				   where lower(a.username) = lower('" .$this->db->escape($username)."') 	
					 and a.password = '" . $this->db->escape(md5($password)) . "' 
					 and a.status = '1' 
					 and site = '".$this->site."'";			
		
		$this->log->write($sql);
		
		$user_query = $this->db->query($sql);

    	if ($user_query->num_rows) {
			$this->session->data['user_id'] = $user_query->row['user_id'];
			$this->session->data['user_group_id'] = $user_query->row['user_group_id'];
			$this->user_id = $user_query->row['user_id'];
			$this->id_no = $user_query->row['id_no'];
			$this->username = $user_query->row['username'];
			$this->user_group_id = $user_query->row['user_group_id'];			
			$this->name = $user_query->row['name'];
			$this->user_group = $user_query->row['user_group'];
			$this->redirect_page = $user_query->row['redirect_page'];
			$this->group_code = $user_query->row['group_code'];
			$this->firstname = $user_query->row['firstname'];
			$this->middlename = $user_query->row['middlename'];
			$this->lastname = $user_query->row['lastname'];
			$this->ewallet = $user_query->row['ewallet'];
			$this->permission_level = $user_query->row['permission_level'];	
			$this->profile_pic_ext = $user_query->row['profile_pic_ext'];	
      		return true;
    	} else {
      		return false;
    	}
  	}	

	public function checkScreenAuth($user_group_id, $module) {
		$sql  = "select count(1) as total 
				   from gui_scr_permission_tbl 
				  where user_group_id = ".$user_group_id. " and module = '".$module."'";
		$query = $this->db->query($sql);
		if ($query->row['total'] > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getSecondLevelMenu($permission_id) {
		$sql  = "select permission_id, module, description, image from gui_scr_permission_tbl ";
		$sql .= " where parent_menu = ".$permission_id." and viewable = 1 and menu_level > 0 and permission_level <= ".$this->getPermissionLevel();
		$sql .= " order by menu_level ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
  	public function logout() {
		unset($this->session->data['user_id']);
	
		$this->user_id = '';
		$this->username = '';
		$this->user_group_id = '';
		
		if(!(session_id() == '')) {
			// session isn't started
			session_destroy();
		}
		
  	}

  	public function hasPermission($key, $value) {
    	if (isset($this->permission[$key])) {
	  		return in_array($value, $this->permission[$key]);
		} else {
	  		return false;
		}
  	}
  
  	public function isLogged() {
    	return $this->user_id;
  	}
  
  	public function getId() {
    	return $this->user_id;
  	}
	
  	public function getUserName() {
    	return $this->username;
  	}

  	public function getSerialCode() {
    	return $this->serial_code;
  	}	
	
   	public function getUserGroupId() {
		return $this->user_group_id;
  	}
	
   	public function getName() {
		return $this->name;
  	}
	
   	public function getBranch() {
		return $this->branch;
  	}
	
	public function getUserGroup() {
		return $this->user_group;
	}

	public function getLegacyBranch() {
		return $this->legacy_branch;
	}

	public function getLegacyBranchId() {
		return $this->legacy_branch_id;
	}	
	
	public function getRedirectPage() {
		return $this->redirect_page;
	}	

	public function getFirstname() {
		return $this->firstname;
	}	

	public function getMiddlename() {
		return $this->middlename;
	}	

	public function getLastname() {
		return $this->lastname;
	}	

	public function getBirthdate() {
		return $this->birthdate;
	}	

	public function getAddress() {
		return $this->address;
	}
	
	public function getLandmark() {
		return $this->landmark;
	}

	public function getProvinceId() {
		return $this->province_id;
	}
	
	public function getProvinceDesc() {
		return $this->province_desc;
	}

	public function getCityMunicipalityId() {
		return $this->city_municipality_id;
	}
	
	public function getCityMunicipalityDesc() {
		return $this->city_municipality_desc;
	}

	public function getBarangayId() {
		return $this->barangay_id;
	}

	public function getBarangayDesc() {
		return $this->barangay_desc;
	}
	
	public function getReferror() {
		return $this->refer_by_id;
	}

	public function getOperator() {
		return $this->operator_id;
	}
	
	public function getSalesRep() {
		return $this->sales_rep_id;
	}
	
	public function getReseller() {
		return $this->e_seller_id;
	}
	
	public function getAdmin() {
		return $this->admin_id;
	}

	public function getProfession() {
		return $this->profession;
	}	

	public function getGender() {
		return $this->gender;
	}

	public function getMobile() {
		return $this->mobile;
	}	

	public function getHome() {
		return $this->home;
	}

	public function getEmail() {
		return $this->email;
	}	

	public function getUpline() {
		return $this->upline;
	}	
	
	public function getCustomerData() {
		return $this->customer;
	}	

	public function getCustomerId() {
		return $this->customer_id;
	}
	
	public function getIdNo() {
		return $this->id_no;
	}

	public function getRankImg() {
		return $this->rank_img;
	}	

	public function getRankId() {
		return $this->rank;
	}

	public function getRank() {
		return $this->rank;
	}	

	public function getGroupCode() {
		return $this->group_code;
	}
	
	public function getPaidFlag() {
		return $this->paid_flag;
	}
	
	public function getContact() {
		return $this->contact;
	}
	
	public function getBranchId() {
		return $this->branch_id;
	}
	
	public function getExtension() {
		return $this->profile_pic_ext;
	}
	
	public function getActivationFlag() {
		return $this->activation_flag;
	}
	
	public function getAreaId() {
		return $this->area_id;
	}
	
	public function setActivationFlag($activation_flag) {
		$this->activation_flag = $activation_flag;
	}
	

	public function setContact($contact) {
		$this->contact = $contact;
	}
	
	public function setEmail($email) {
		$this->email = $email;
	}	
	
	public function setLandmark($landmark) {
		$this->landmark = $landmark;
	}
	
	public function setAddress($address) {
		$this->address = $address;
	}
	
	public function setProvinceDesc($province_desc) {
		$this->province_desc = $province_desc;
	}
	
	public function setCityMunicipalityDesc($city_municipality_desc) {
		$this->city_municipality_desc = $city_municipality_desc;
	}
	
	public function setCityMunicipalityId($city_municipality_id) {
		$this->city_municipality_id = $city_municipality_id;
	}
	
	public function setBarangayDesc($barangay_desc) {
		$this->barangay_desc = $barangay_desc;
	}
	
	public function setBarangayId($barangay_id) {
		$this->barangay_id = $barangay_id;
	}
	public function getPermissionLevel() {
		return $this->permission_level;
	}

	public function nowDate($date = null) {
		if(isset($date)) {
			
		} else {
			date_default_timezone_set('Asia/Manila');
			
			$now = new DateTime();		
		}
		
		return $now->format('Y-m-d');
	}	
	
	public function now($date = null) {
		if(isset($date)) {
			
		} else {
			date_default_timezone_set('Asia/Manila');
			
			$now = new DateTime();		
		}
		
		return $now->format('Y-m-d H:i:s');
	}
	
	public function nowCurrentDate($date = null) {
		if(isset($date)) {
			
		} else {
			date_default_timezone_set('Asia/Manila');
			
			$now = new DateTime();		
		}
		
		return $now->format('d');
	}

	public function nowGetFirstDateTimeOfMonth($date = null) {
		if(isset($date)) {
			
		} else {
			date_default_timezone_set('Asia/Manila');
			
			$now = new DateTime();		
		}
		
		return $now->format('Y-m')."-01 00:00:00";
	}
	
	public function nowGetLastDateTimeOfMonth($date = null) {
		if(isset($date)) {
			
		} else {
			date_default_timezone_set('Asia/Manila');
			
			$now = new DateTime();		
		}
		
		return $now->format('Y-m-t')." 23:59:59";
	}	

	public function nowGetFirstDayOfMonth($date = null) {
		if(isset($date)) {
			
		} else {
			date_default_timezone_set('Asia/Manila');
			
			$now = new DateTime();		
		}
		
		return $now->format('Y-m-t');
	}
	
	public function getLastMonday() {
		date_default_timezone_set('Asia/Manila');
		$day = date("N");
		if($day == 1) {
			return date('Y-m-d');
		} else {
			return date('Y-m-d',strtotime('last monday'));
		}
	}
	
	public function nowGetLastDayOfMonth($date = null) {
		if(isset($date)) {
			
		} else {
			date_default_timezone_set('Asia/Manila');
			
			$now = new DateTime();		
		}
		
		return $now->format('Y-m-t');
	}	

	public function nowGet15thDayOfNextMonth($date = null) {
		if(isset($date)) {
			
		} else {
			date_default_timezone_set('Asia/Manila');
			
			$now = new DateTime();
			$interval = new DateInterval('P1M');
			$now->add($interval);
		}
		
		return $now->format('Y-m-15');
	}	
	
	public function getDayOfWeekNow($date = null) {
		if(isset($date)) {
			
		} else {
			date_default_timezone_set('Asia/Manila');
			
			$now = new DateTime();		
		}
		
		return $now->format('N');
	}

	public function getNextDateFromNow($next) {

		date_default_timezone_set('Asia/Manila');
		
		$now = new DateTime();		
		$now->modify($next);
		
		return $now->format('Y-m-d');
	}

	public function getNextWeekFromMaturity($next) {

		date_default_timezone_set('Asia/Manila');
		
		$now = new DateTime();		
		$now->modify($next);
		$now->modify($next);
		
		return $now->format('Y-m-d');
	}
	
	public function nowym($date = null) {
		if(isset($date)) {
			
		} else {
			date_default_timezone_set('Asia/Manila');
			
			$now = new DateTime();		
		}
		
		return $now->format('Ym');
	}
	
	public function nowy($date = null) {
		if(isset($date)) {
			
		} else {
			date_default_timezone_set('Asia/Manila');
			
			$now = new DateTime();		
		}
		
		return $now->format('Y');
	}
	
	public function getEwallet() {
		return $this->ewallet;
	}

	public function Random($table = "", $column = "", $length = 8) {
		//To Pull 7 Unique Random Values Out Of AlphaNumeric

		//removed number 0, capital o, number 1 and small L
		//Total: keys = 32, elements = 33
		$random_chars = "";
		$characters = array(
		"A","B","C","D","E","F","G","H","J","K","L","M",
		"N","P","Q","R","S","T","U","V","W","X","Y","Z",
		"1","2","3","4","5","6","7","8","9");

		//make an "empty container" or array for our keys
		$keys = array();
		
		$unique = 0;
		
		while($unique == 0) {			
			//first count of $keys is empty so "1", remaining count is 1-6 = total 7 times
			while(count($keys) < $length) {
				//"0" because we use this to FIND ARRAY KEYS which has a 0 value
				//"-1" because were only concerned of number of keys which is 32 not 33
				//count($characters) = 33
				$x = mt_rand(0, count($characters)-1);
				if(!in_array($x, $keys)) {
				   $keys[] = $x;
				}
			}

			foreach($keys as $key){
			   $random_chars .= $characters[$key];
			}
			
			if($table == "" and $column == "") {
				$unique = 1;
			} else {
				$sql = "select count(1) total from ".$table." where ".$column." = '".$random_chars."'";
				$query = $this->db->query($sql);
				if($query->row['total'] == 0) {
					$unique = 1;
				}
			} 
		}

		return $random_chars;
	}	
	
	public function exportToCSV($fp, $list, $toCsv) {
		fputcsv($fp, $list);
		foreach ($toCsv as $fields) {
			fputcsv($fp, $fields);
		}	
	}	

	public function exportToCSVWithTotal($fp, $list, $toCsv, $total) {		
		fputcsv($fp, $list);
		foreach ($toCsv as $fields) {
			fputcsv($fp, $fields);
		}	
		fputcsv($fp, $total);
	}
	
	public function exportToCSVWithTotalOnTop($fp, $list, $toCsv, $total) {		
		fputcsv($fp, $list);
		fputcsv($fp, $total);
		foreach ($toCsv as $fields) {
			fputcsv($fp, $fields);
		}	
	}
	
}
?>
