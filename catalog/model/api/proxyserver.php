<?php
class ModelApiProxyServer extends Model {
	
	private $api_key = 'AIzaSyCOWrJfo_vHtEcavanYueHyju2EGNcudWQ';

	public function getDistances($data){

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['origins'])) {
			if(empty($data['origins'])) {
				$valid = 0;
				$return_msg .= "Origins is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Origins is required\n";
		}

		if(isset($data['destinations'])) {
			if(empty($data['destinations'])) {
				$valid = 0;
				$return_msg .= "Destinations is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Destinations is required\n";
		}

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$link = 'https://maps.googleapis.com/maps/api/distancematrix/json?';

			// $keys_name = array_keys(ksort($data));

  	// 		foreach ($keys_name as $index => $key) {
  	// 			if($key != 'task'){
  	// 				$link .= $index == 1 ? $key.'='.$data[$key] : '&'.$key.'='.$data[$key];
  	// 			}
  	// 		}

			$link .= 'origins='.$data['origins'];
			$link .= '&destinations='.$data['destinations'];
			$link .= '&key='.$this->api_key;

			$response = $this->curlGetRequest($link);

			return Array( 	'status'			=> 200,
      						'valid' 			=> true,
      						'status_message'	=> 'Distance Matrix Proxy success!',
      						'data' 				=> $response );
		}

	}

	public function getCoordinates($data){
		
		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['origin'])) {
			if(empty($data['origin'])) {
				$valid = 0;
				$return_msg .= "Origin is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Origin is required\n";
		}

		if(isset($data['destination'])) {
			if(empty($data['destination'])) {
				$valid = 0;
				$return_msg .= "Destination is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Destination is required\n";
		}

		if(isset($data['waypoints'])) {
			if(empty($data['waypoints'])) {
				$valid = 0;
				$return_msg .= "Waypoints is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Waypoints is required\n";
		}

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$link = 'https://maps.googleapis.com/maps/api/directions/json?';

			// $keys_name = array_keys($data);

  	// 		foreach ($keys_name as $index => $key) {
  	// 			if($key != 'task'){
  	// 				$link .= $index == 1 ? $key.'='.$data[$key] : '&'.$key.'='.$data[$key];
  	// 			}
  	// 		}

			$link .= 'origin='.$data['origin'];
			$link .= '&destination='.$data['destination'];
			$link .= '&waypoints='.$data['waypoints'];
			$link .= '&key='.$this->api_key;

			$response = $this->curlGetRequest($link);

			// return $response;
			return Array( 	'status'			=> 200,
      						'valid' 			=> true,
      						'status_message'	=> 'Directions Proxy success!',
      						'data' 				=> $response );
		}
	}

	public function curlGetRequest($link){

		// Get cURL resource
		$curl = curl_init();
		// Check if initialization had gone wrong*    
	    if ($curl === false) {
	        throw new Exception('failed to initialize');
	    }

		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, [
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $link.'&key='.$this->api_key,
		]);

		// Send the request & save response to $resp
		$response = json_decode(curl_exec($curl));

		// Check the return value of curl_exec(), too
	    if ($response === false) {
	        throw new Exception(curl_error($curl), curl_errno($curl));
	    }

		// Close request to clear up some resources
		curl_close($curl);

		return $response;
	}
}
?>