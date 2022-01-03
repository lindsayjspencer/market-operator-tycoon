<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class three_controller extends CI_Controller {

	var $is_ajax = false;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');

		$this->data = [];

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			//ajax request
			$this->is_ajax = true;
		}


	}

	public function two() {

		$data = [];
		if($this->is_ajax) {
			$data["locs"] = $this->db->query("SELECT mapLocId, mapLocX, mapLocY, mapLocName, LocRotate, hasAwning FROM `cerb_map_loc` WHERE `mapLocMapId` = 47")->result_array();
			$bookings = $this->db->query("SELECT ClientAttendanceScore as _cas, ClientAvgSqmScore as _cass, ClientScoreTotal as _cst, ClientRainDivergence as _crd, MarketBookingId, MarketClientName, ContactFullName, ContactPrimaryContactNumber, MarketCategoryName, MarketCategoryId as cid FROM cerb_market_booking b JOIN cerb_market_client c ON c.MarketClientId=b.MarketBookingClientId JOIN cerb_market_contact con ON con.ContactId=c.MarketClientPrimaryContactId JOIN cerb_market_category cat ON cat.MarketCategoryId=c.MarketClientPrimaryCategoryId WHERE c.MarketClientPrimaryCategoryId!=6 AND c.ClientAttendanceScore!=0 GROUP BY MarketClientId LIMIT 500")->result_array();
			$products = $this->db->query("SELECT p.productId as pid, p.productName as pname, p.productCategoryId as cid, productRelationArray FROM `cerb_client_product` pr JOIN cerb_market_product p ON p.productId=pr.productId GROUP BY p.productId")->result_array();
			$cats = [];
			foreach($products as $p) {
				$cats[$p["cid"]][] = $p;
			}
			foreach($data["locs"] as $i=>$l) {
				$r = rand(0,count($bookings)-1);
				$booking = $bookings[$r];
				$booking["products"] = [];
				for($f=0;$f<5;$f++) {
					$r = rand(0,count($cats[$booking["cid"]])-1);
					$prod = $cats[$booking["cid"]][$r];
					$prod["price"] = rand(5, 20);
					$booking["products"][] = $prod;
				}
				$data["locs"][$i]["booking"] = $booking;
			}
			echo json_encode($data);

		} else {
			$this->load->view('admin/dragonfly/two', $data);
		}

	}

	public function dragonfly() {

		$data = [];
		if($this->is_ajax) {
			$data["locs"] = $this->db->query("SELECT mapLocId, mapLocX, mapLocY, mapLocName, LocRotate, hasAwning FROM `cerb_map_loc` WHERE `mapLocMapId` = 47")->result_array();
			$bookings = $this->db->query("SELECT ClientAttendanceScore as _cas, ClientAvgSqmScore as _cass, ClientScoreTotal as _cst, ClientRainDivergence as _crd, MarketBookingId, MarketClientName, ContactFullName, ContactPrimaryContactNumber, MarketCategoryName, MarketCategoryId as cid FROM cerb_market_booking b JOIN cerb_market_client c ON c.MarketClientId=b.MarketBookingClientId JOIN cerb_market_contact con ON con.ContactId=c.MarketClientPrimaryContactId JOIN cerb_market_category cat ON cat.MarketCategoryId=c.MarketClientPrimaryCategoryId WHERE c.MarketClientPrimaryCategoryId!=6 AND c.ClientAttendanceScore!=0 GROUP BY MarketClientId LIMIT 500")->result_array();
			$products = $this->db->query("SELECT p.productId as pid, p.productName as pname, p.productCategoryId as cid, productRelationArray FROM `cerb_client_product` pr JOIN cerb_market_product p ON p.productId=pr.productId GROUP BY p.productId")->result_array();
			$cats = [];
			foreach($products as $p) {
				$cats[$p["cid"]][] = $p;
			}
			foreach($data["locs"] as $i=>$l) {
				$r = rand(0,count($bookings)-1);
				$booking = $bookings[$r];
				$booking["products"] = [];
				for($f=0;$f<5;$f++) {
					$r = rand(0,count($cats[$booking["cid"]])-1);
					$prod = $cats[$booking["cid"]][$r];
					$prod["price"] = rand(5, 20);
					$booking["products"][] = $prod;
				}
				$data["locs"][$i]["booking"] = $booking;
			}
			echo json_encode($data);

		} else {
			$this->load->view('admin/dragonfly/three', $data);
		}

	}

	public function new_game() {
		$this->load->model("proton_mot_model");
		if($this->is_ajax) {
			$newgame = [];
			$newgame["motLastAccess"] = time();
			$newgame["motTimeCreated"] = time();
			$newgame["motIp"] = $_SERVER["REMOTE_ADDR"];
			$food_stalls = $this->db->query("
				SELECT ClientAvgSqmScore as _cass, MarketClientId as _clid, MarketCategoryId as _cid
				FROM cerb_market_booking b
				JOIN cerb_market_client c ON c.MarketClientId=b.MarketBookingClientId
				JOIN cerb_market_contact con ON con.ContactId=c.MarketClientPrimaryContactId
				JOIN cerb_market_category cat ON cat.MarketCategoryId=c.MarketClientPrimaryCategoryId
				WHERE c.MarketClientPrimaryCategoryId=1
				AND c.ClientAttendanceScore!=0
				GROUP BY MarketClientId
				ORDER BY ClientAvgSqmScore
				DESC LIMIT 20
			")->result_array();
			$fruit_stalls = $this->db->query("
				SELECT ClientAvgSqmScore as _cass, MarketClientId as _clid, MarketCategoryId as _cid
				FROM cerb_market_booking b
				JOIN cerb_market_client c ON c.MarketClientId=b.MarketBookingClientId
				JOIN cerb_market_contact con ON con.ContactId=c.MarketClientPrimaryContactId
				JOIN cerb_market_category cat ON cat.MarketCategoryId=c.MarketClientPrimaryCategoryId
				WHERE c.MarketClientPrimaryCategoryId=2
				AND c.ClientAttendanceScore!=0
				GROUP BY MarketClientId
				ORDER BY ClientAvgSqmScore
				DESC LIMIT 20
			")->result_array();
			$art_stalls = $this->db->query("
				SELECT ClientAvgSqmScore as _cass, MarketClientId as _clid, MarketCategoryId as _cid
				FROM cerb_market_booking b
				JOIN cerb_market_client c ON c.MarketClientId=b.MarketBookingClientId
				JOIN cerb_market_contact con ON con.ContactId=c.MarketClientPrimaryContactId
				JOIN cerb_market_category cat ON cat.MarketCategoryId=c.MarketClientPrimaryCategoryId
				WHERE c.MarketClientPrimaryCategoryId=3
				AND c.ClientAttendanceScore!=0
				GROUP BY MarketClientId
				ORDER BY ClientAvgSqmScore
				DESC LIMIT 20
			")->result_array();
			$provision_stalls = $this->db->query("
				SELECT ClientAvgSqmScore as _cass, MarketClientId as _clid, MarketCategoryId as _cid
				FROM cerb_market_booking b
				JOIN cerb_market_client c ON c.MarketClientId=b.MarketBookingClientId
				JOIN cerb_market_contact con ON con.ContactId=c.MarketClientPrimaryContactId
				JOIN cerb_market_category cat ON cat.MarketCategoryId=c.MarketClientPrimaryCategoryId
				WHERE c.MarketClientPrimaryCategoryId=15
				AND c.ClientAttendanceScore!=0
				GROUP BY MarketClientId
				ORDER BY ClientAvgSqmScore
				DESC LIMIT 20
			")->result_array();
			$products = $this->db->query("
				SELECT p.productId as pid,
				p.productName as pname,
				p.productCategoryId as cid
				FROM `cerb_client_product` pr
				JOIN cerb_market_product p
				ON p.productId=pr.productId
				GROUP BY p.productId
			")->result_array();
			$cats = [];
			$data["clients"] = [];
			foreach($products as $p) {
				$cats[$p["cid"]][] = $p;
			}
			$clients = array_merge($food_stalls, $fruit_stalls, $art_stalls, $provision_stalls);
			foreach(array_reverse($clients) as $i=>$client) {
				$client["_p"] = [];
				for($f=0;$f<3;$f++) {
					$r = rand(0,count($cats[$client["_cid"]])-1);
					$prod = $cats[$client["_cid"]][$r];
					$cl_prod = [];
					$cl_prod["_pid"] = $prod["pid"];
					$cl_prod["_pr"] = rand(5, 20)*$client["_cass"];
					$client["_p"][] = $cl_prod;
				}
				$client["sc"] = [];
				$client["sc"]["_cs"] = 20+rand(-10,10)+(70*$client["_cass"]);
				$client["sc"]["_pm"] = 30+rand(-10,10)+(60*$client["_cass"]);
				$client["sc"]["_rc"] = 10+rand(-5,5)+(80*$client["_cass"]);
				$client["sc"]["_ca"] = rand(-10,10)+(90*$client["_cass"]);
				$data["clients"][] = $client;
			}

			$newgame["motId"] = $this->proton_mot_model->set($newgame);
			$newgame["motData"] = json_decode($this->input->post("save"), true);
			$newgame["motData"]["gameId"] = $newgame["motId"];
			$newgame["motData"] = json_encode($newgame["motData"]);
			$newgame["motClientData"] = json_encode($data["clients"]);

			$this->proton_mot_model->update($newgame);

			$game = $this->proton_mot_model->get($newgame["motId"]);
			$game["motData"] = json_decode($game["motData"]);
			$game["motClientData"] = json_decode($game["motClientData"]);
			$game["motStallData"] = json_decode($game["motStallData"]);

			$json["game"] = $game;

			echo json_encode($json);

		}
	}

	public function save_game() {
		$this->load->model("proton_mot_model");
		$data = json_decode($this->input->post("save"), true);
		$json = [];
		if($this->is_ajax AND isset($data["gameId"])) {
			$game = $this->proton_mot_model->get($data["gameId"]);
			if($game) {
				$game["motLastAccess"] = time();
				$motData = [];
				$motData["gameId"] = $data["gameId"];
				$motData["settings"] = $data["settings"];
				$motData["stats"] = $data["stats"];
				$game["motData"] = json_encode($motData);
				$game["motStallData"] = json_encode($data["savedStalls"]);
				$game["motClientData"] = json_encode($data["clients"]);
				$this->proton_mot_model->update($game);
				$json["status"] = 1;
			} else {
				$json["status"] = 0;
			}
		} else {
			$json["status"] = 0;
		}
		echo json_encode($json);
	}

	public function load_game() {
		$this->load->model("proton_mot_model");
		$id = $this->input->post('id');
		if($this->is_ajax) {
			$data["game"] = $this->proton_mot_model->get($id);
			if($data["game"]) {
				$data["game"]["motLastAccess"] = time();
				$this->proton_mot_model->update($data["game"]);
				if($data["game"]["motData"]!=="") {
					$data["game"]["motData"] = json_decode($data["game"]["motData"]);
					$data["game"]["motClientData"] = json_decode($data["game"]["motClientData"]);
					$data["game"]["motStallData"] = json_decode($data["game"]["motStallData"]);
					echo json_encode($data["game"]);
				} else {
					echo json_encode(["error"=>true]);
				}
			} else {
				echo json_encode(["error"=> true]);
			}
		}
	}

	public function load_game_data() {
		$data = [];
		$this->load->model("proton_mot_model");
		if($this->is_ajax) {

			$data["locs"] = $this->db->query("SELECT mapLocId, mapLocX, mapLocY, mapLocName, LocRotate, hasAwning FROM `cerb_map_loc` WHERE `mapLocMapId` = 47")->result_array();
			$data["products"] = $this->db->query("SELECT p.productId as pid, p.productName as pname, p.productCategoryId as cid FROM `cerb_client_product` pr JOIN cerb_market_product p ON p.productId=pr.productId GROUP BY p.productId")->result_array();
			$game_id = $this->input->post('id');
			if(!$game_id) { return; }
			$game = $this->proton_mot_model->get($game_id);
			$clients = json_decode($game["motClientData"]);
			$data["clients"] = [];
			foreach($clients as $c) {
				$data["clients"][] = $this->db->query("SELECT MarketClientId, MarketClientName, ContactFullName, MarketCategoryName, MarketCategoryId FROM cerb_market_client c JOIN cerb_market_contact con ON con.ContactId=c.MarketClientPrimaryContactId JOIN cerb_market_category cat ON cat.MarketCategoryId=c.MarketClientPrimaryCategoryId WHERE c.MarketClientId=" . $c->_clid)->row_array();
			}

			echo json_encode($data);

		}
	}

	public function globe() {

		$data = [];
		$this->load->view('admin/threejs/globe', $data);

	}

	public function helicopter() {

		$data = [];
		$this->load->view('admin/threejs/heli', $data);

	}

	public function threejs($file) {

		$data = [];
		$this->load->view('admin/dragonfly/' . $file, $data);

	}

	public function ammo() {

		$this->threejs("ammo");

	}

}
