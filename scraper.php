<?php

$data = array();
$matches = array();

if(isset($_POST['data']) && !empty($_POST['data'])) {
	$time_pre = microtime(true);
	
	$languages_pre_search = $languages_post_search = $num_of_jobs = $return_data = array();
	$data = json_decode($_POST['data']);
	$city = searchFriendlyString($data->city);
	$languages_pre_search = $data->languages;
	$state = 				$data->state;
	$radius = 				$data->radius;
	$salary = 				$data->salary;
	$api_count =			$data->apiCount;
	$api_type = 			$data->apiType[$api_count];
	
	for ($i = 0; $i < count($languages_pre_search); $i++) {
		$languages_post_search[$i] = searchFriendlyString($languages_pre_search[$i]);
		
	    if ($api_type == "indeed"){
	    	$json_data = json_decode(apiCall($languages_post_search[$i], $city, $state, $radius, $salary));
			$num_of_jobs[$i] = $json_data->totalResults;
	    } else if ($api_type == "career-builder"){
		    $xml_data = apiCallXML($languages_post_search[$i], $city, $state, $radius, $salary);
			$xml_data = (string)$xml_data -> TotalCount;
			$num_of_jobs[$i] = $xml_data;
	    }
	    
	}
	
	//print_r($num_of_jobs);	
	$time_post = microtime(true);
	$exec_time = $time_post - $time_pre;
	
	$return_data = [$languages_pre_search, $num_of_jobs, $exec_time, $api_count, $api_type];
	
	print_r(json_encode($return_data));
	
}

function searchFriendlyString($string) {
	$string = htmlspecialchars(stripslashes(trim($string)));  // Sanitize string
    $string = strtolower($string);
    $string = str_replace('+', '%2B', $string);				  // Convert + signs
    $string = str_replace('#', '%23', $string);				  // Convert # signs
    $string = preg_replace("/[\s_]/", "+", $string); 		  // Convert whitespaces and underscore to dash
    return $string;
}

// INDEED API
function apiCall($language, $city, $state, $radius, $salary ){
	$url   = "http://api.indeed.com/ads/apisearch?publisher=78375103125";
	$url  .= "11309&q=$language&l=$city%2C$state&salary=$salary&radius=";
	$url  .= "$radius&limit=1&format=json&v=2";

	$response = curlProccess($url);
	return $response;
}

// CAREERBUILDER API
function apiCallXML( $language, $city, $state, $radius, $salary ){
	$url  = "http://api.careerbuilder.com/v1/jobsearch?developerkey=";
	$url .= "WDHS22N6HBSM68ZD9NHD&keywords=$language&hostsite=US&pay";
	$url .= "low=$salary&location=$city,$state&radius=$radius";
	
	$response = curlProccess($url);
	$response = new SimpleXMLElement($response);
	return $response;
}

function curlProccess( $url ){
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}


// SCRAPE INDEED.COM
// Not currently using this
/*
function scrapePages($languages, $city, $state){
	if (isset($match)){
		unset($match);
	}
	
	for($i = 0; $i<2; $i++){
		$url = "http://www.indeed.com/q-" . $languages[$i] . "-l-" . $city . ",-" . $state . "-jobs.html";
		$ch = curl_init($url);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$curl_scraped_page = curl_exec($ch);
		curl_close($ch);
		
		$regex = '/(?<=name="description"\scontent=")(.*?)(?=\s)/';
		
		preg_match( $regex, $curl_scraped_page, $match);
				
		$match = preg_replace('/[$,]/', '', $match[0]);
		
		$matchNumeric = str_replace( ',', '', $match );
		if( is_numeric( $matchNumeric ) ) {
		    $matches[$i] = $matchNumeric;
		} else {
			echo 'non-numeric value for Regex search';
			die();
		}
	}
	return $matches;
}
*/


?>