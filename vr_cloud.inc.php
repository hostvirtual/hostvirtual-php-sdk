<?
class vr_cloud {

	private $key;
	private $api_host;
	
	function __construct($key, $id = FALSE) {
    
		$this->id = $id;
		$this->key = $key;
		$this->api_host = "https://www.vr.org/vapi/";
		
	}
	
	// get the server summary data
	public function summary() {
		
		$result = $this->_call("cloud/serversummary/$this->id");

        return $result;

	}
	
	// get the bandwidth report
	public function bandwidth_report() {
		
		$result = $this->_call("cloud/servermonthlybw/$this->id");

        return $result;

	}
 	
	// get ipv4 + ipv6 ips
	public function networkips() {
		
		$result = $this->_call("cloud/networkips/$this->id");

        return $result;

	}
	
	// get ipv4 ips
	public function IPv4() {
		
		$result = $this->_call("cloud/IPv4/$this->id");

        return $result;

	}
	
	// get IPv6 ips
	public function IPv6() {
		
		$result = $this->_call("cloud/IPv6/$this->id");

        return $result;

	}
	
	
	// list operating systems
	public function os_list() {
		
		$result = $this->_call("cloud/images");

        return $result;
		
	}
	
	// list locations available for package
	public function locations($pkg = FALSE) {
		
		$result = $this->_call("cloud/locations");			

        return $result;

	}
	
	// buy a server
	public function buy($plan) {
		
		$result = $this->_call("cloud/buy/$plan");

        return $result;

	}
	
	// buy build
	public function buy_build($plan, $location, $image, $fqdn, $password) {
		
		$data[plan] = $plan;
		$data[location] = $location;
		$data[image] = $location;
		$data[fqdn] = $fqdn;
		$data[password] = $password;
		
		$result = $this->_call("cloud/buy_build", $data, "post");
        
        return $result;
		
	}
	
	
	// list plans
	public function plans($location = FALSE) {
		
		if ($location) {
			
			$data[location] = $location;
			$result = $this->_call("cloud/sizes", $data);
			
		} else {
			$result = $this->_call("cloud/sizes");

		}

        return $result;		
	}
	
	
	// build a new server
	public function build($location, $image, $fqdn, $password) {
		
		$data[mbpkgid] = $this->id;
		$data[location] = $location;
		$data[image] = $image;
		$data[fqdn] = $fqdn;
		$data[password] = $password;
		
		$result = $this->_call("cloud/server/build/$this->id", $data, "post");
        
        return $result;
		
	}

	// get a servers status only
	public function status() {
		
		$result = $this->_call("cloud/status/$this->id");

        return $result->status;

	}
	
	
	// rescue - note, you need to allow rescue mode over api for this to work
	public function rescue($password) {
		
		$data[mbpkgid] = $this->id;
		$data[rescue_pass] = $password;

		$result = $this->_call("cloud/server/start_rescue/$this->id", $data, "post");
        
        return $result;
		
	}
	
	// leave rescue mode
	public function rescue_stop() {
		
		$data[mbpkgid] = $this->id;

		$result = $this->_call("cloud/server/stop_rescue/$this->id", $data, "post");
        
        return $result;
		
	}
	
	// list running servers & data (may not included cancelled)
	public function servers() {
		
		$result = $this->_call("cloud/servers");
        
        return $result;
		
	}
	
	// get server options/details
	public function server() {
		
		$result = $this->_call("cloud/server/$this->id");
        
        return $result;
		
	}
	
	// list servers/packages (includes cancelled)
	public function packages() {
				
		$result = $this->_call("cloud/packages");
        
        return $result;

	}
	
	// show details about the package
	public function package() {
				
		$result = $this->_call("cloud/package/$this->id");
        
        return $result;

	}
	
	// shutdown
	public function shutdown($force = FALSE) {
				
		$data["mbpkgid"] = $this->id;
		if ($force) {
			$data["force"] = "1";
		}
		$result = $this->_call("cloud/server/shutdown/$this->id", $data, "post");
        
        return $result;
		
		
	}
	
	// reboot
	public function reboot($force = FALSE) {
	
		$data[mbpkgid] = $this->id;
		if ($force) {
			$data[force] = "1";
		}
		$result = $this->_call("cloud/server/reboot/$this->id", $data, "post");
        
        return $result;
	
	}
	
	// start
	public function start() {
		
		$data[mbpkgid] = $this->id;
	
		$result = $this->_call("cloud/server/start/$this->id", $data, "post");
        
        return $result;
		
	}
	
	// delete - needs to be allowed from api options page
	public function delete() {
		
		$data[mbpkgid] = $this->id;

		$result = $this->_call("cloud/server/delete/$this->id", $data, "post");
        
        return $result;

	}
	
	// cancel - needs to be allowed from api options page
	public function cancel() {
		
		$data[mbpkgid] = $this->id;

		$result = $this->_call("cloud/cancel", $data, "post");
        
        return $result;

	}
	
	// cancel - needs to be allowed from api options page
	public function unlink() {
		
		$result = $this->_call("cloud/unlink/$this->id");
        
        return $result;

	}
	
	// change root password - needs to be allowed from api options page
	public function root_password($password) {
		
		$data[mbpkgid] = $this->id;
		$data[rootpass] = $password;

		// this call is not yet enabled for api key only use, however you can auth with the account password to verify & submit
		// $data[email] = $location;
		// $data[password] = $fqdn;
		
		$result = $this->_call("cloud/server/password/$this->id", $data, "post");
        
        return $result;
		
	}
	
	// suspend
	public function suspend() {
		
		// not yet - you can implement this yourself by changing auto boot = 0, then issue a force shutdown
		// (assuming there is no direct access to the web ui)
		$server = $this->server();

		$options["kernel_id"] = $server->kernel_id;
		$options["disk_type"] = $server->disk_type;
		$options["boot"] = $server->boot;
		$options["fqdn"] = $server->fqdn;
		$options["vcpus"] = $server->vcpus;
		
		// set auto rescue to 0 so the server won't boot automatically
		$options["autorescue"] = "0";
		
		// set the options
		$this->options($options);
		
		// force shutdown the server
		return $this->shutdown("1");
				
	}
	
	// unsuspend
	public function unsuspend() {
		
		// not yet - you can implement this yourself by changing auto boot = 0, then issue a force shutdown
		// (assuming there is no direct access to the web ui)
		$server = $this->server();

		$options["kernel_id"] = $server->kernel_id;
		$options["disk_type"] = $server->disk_type;
		$options["boot"] = $server->boot;
		$options["fqdn"] = $server->fqdn;
		$options["vcpus"] = $server->vcpus;
		
		// set auto rescue to 0 so the server won't boot automatically
		$options["autorescue"] = "1";
		
		// set the options
		$this->options($options);
		
		// force shutdown the server
		return $this->start();
				
	}
	
	// update ipv4 rdns
	public function ipv4_rdns($id, $reverse) {
		
		$data["id"] = $id;
		$data["reverse"] = $reverse;
		
		$result = $this->_call("cloud/ipv4/$id", $data, "put");

        return $result;
		
	}
	
	// update ipv6 rdns
	public function ipv6_rdns($id, $reverse) {
		
		$data["id"] = $id;
		$data["reverse"] = $reverse;
		
		$result = $this->_call("cloud/ipv6/$id", $data, "put");

        return $result;
		
	}	
	
	// update server options
	public function options($data) {
		
		$data["mbpkgid"] = $this->id;
		
		$result = $this->_call("cloud/server/$this->id", $data, "put");

        return $result;
		
	}
	
	// get id
	private function _get_id() {
		return $this->id;
	}
	
	// set id
	private function _set_id($id) {
		$this->id = $id;
	}
	
	// get key
	private function _get_key() {
		return $this->key;
	}
	
	// set key
	private function _set_key($key) {
		$this->key = $key;
	}
	
	// handle the api call
	private function _call($call, $data = FALSE, $type = FALSE) {

		switch ($type) {

			case "post" :

				$curl = new Curl;
				$url = $this->api_host . $call . "?key=$this->key";
				$response = $curl->post($url, $data);
				$response = json_decode($response->body);	
				return $response;

			break;
			
			case "put" :
			
				$curl = new Curl;
				$url = $this->api_host . $call . "?key=$this->key";
				$response = $curl->put($url, $data);
				$response = json_decode($response->body);	
				return $response;
			
			break;
			
			case "delete":
				
				$curl = new Curl;
				$url = $this->api_host . $call . "?key=$this->key";
				$response = $curl->request('DELETE', $url, $data);
				$response = json_decode($response->body);	
				return $response;
				
			break;

			default :

				$data["key"] = $this->key;
				
				$curl = new Curl;
				//$url = $this->api_host . $call . "?key=$this->key";
				$url = $this->api_host . $call;
				$response = $curl->get($url, $data);
				$response = json_decode($response->body);	
				return $response;
				
			break;

		}

	}
	
	
}


?>