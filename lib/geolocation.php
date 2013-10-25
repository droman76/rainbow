<?php





function geolocate() {
	if (isset( $_SESSION['geolocated']) ) return true;
	$ip = '';
	if ( isset( $_SERVER["REMOTE_ADDR"] ) ) {
		$ip = $_SERVER["REMOTE_ADDR"];
	} else if ( isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else if ( isset( $_SERVER["HTTP_CLIENT_IP"] ) ) {
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}
	if ( !empty( $ip ) && function_exists( 'geoip_record_by_name' ) ) {
		$info = geoip_record_by_name( $ip );
		$country = $info['country_name'];
		$country_code = $info['country_code'];

		$city = $info['city'];
		$region = $info['region'];
		$lat = $info['latitude'];
		$long = $info['longitude'];
		$continent_code = $info['continent_code'];

		if ($country_code == 'BZ' || $country_code == 'MX' || $country_code == 'SV`' || $country_code == 'PA' || $country_code == 'CR' || $country_code == 'NI' ){
			$continent_code = 'CA';	
		}
		if ($country_code == "US" || $country_code == "CA") {
			$continent_code = 'NA';	
			
		}
		switch ($country_code) {
			case 'AD': 
			case 'AL': 
			case 'AT': 
			case 'BA': 
			case 'BE': 
			case 'BG': 
			case 'BY': 
			case 'CH': 
			case 'CY': 
			case 'CZ': 
			case 'DE': 
			case 'DK': 
			case 'EE': 
			case 'ES': 
			case 'FI': 
			case 'FO': 
			case 'FR': 
			case 'GG': 
			case 'GI': 
			case 'GR': 
			case 'HR': 
			case 'HU': 
			case 'IE': 
			case 'IM': 
			case 'IS': 
			case 'IT': 
			case 'JE': 
			case 'LI': 
			case 'LT': 
			case 'LU': 
			case 'LV': 
			case 'MC': 
			case 'MD':
			case 'MK': 
			case 'MT': 
			case 'NL': 
			case 'NO': 
			case 'PL': 
			case 'PT': 
			case 'RO': 
			case 'RU': 
			case 'SE': 
			case 'SI': 
			case 'SJ': 
			case 'SK': 
			case 'SM': 
			case 'TR': 
			case 'UA':
			case 'UK':
			case 'VA': 
			case 'YU': 		
			$continent_code = 'EU';
			break;
				

			default:
				
				break;
		}

		// retrieve stored location to see if taht one is used instead
		//$me = get_logged_in_user_id();
		/*
		$q = "select * from users where id = $me";
		ilog($q);
		$r = get_query($q);

		if ($u = $r->fetch_object()) {
			$xcountrycode = $u->country_code;
			$xregion = $u->region;
			$xcity = $u->city;

			ilog("xcountrycode: $xcountrycode countrycode $country_code -- xcity: $xcity -- city: $city");
			if ($xcountrycode == $country_code && $city != $xcity) {
				ilog("Setting user defined location: $xcity region: $xregion");
				// city was updated but country remains.. use that city instead
				$_SESSION['city'] = utf8_encode($xcity);
				$_SESSION['region'] = utf8_encode($xregion);
		
			}
			else {
				// use the geolocated city instead, country has moved
				$_SESSION['city'] = utf8_encode($city);
				$_SESSION['region'] = utf8_encode($region);
		
			}

		}
		else {
			ilog("NODATA FOUND on query!!");
			$_SESSION['city'] = utf8_encode($city);
			$_SESSION['region'] = utf8_encode($region);
		}
		*/
		$_SESSION['city'] = utf8_encode($city);
		$_SESSION['region'] = utf8_encode($region);
		$_SESSION['ip'] = $ip;
		$_SESSION['country'] = utf8_encode($country);
		$_SESSION['country_code'] = $country_code;
		$_SESSION['continent_code'] = $continent_code;
		$_SESSION['longitude'] = $long;
		$_SESSION['latitude'] = $lat;
		$_SESSION['geolocated'] = 'yes';




		return true;


	}
	else {
		$_SESSION['ip'] = $ip;
		$_SESSION['city'] = 'Rainbow Beach';
		$_SESSION['region'] = 'BC';
		$_SESSION['country'] = "Canada";
		$_SESSION['country_code'] = "CA";
		$_SESSION['longitude'] = 'na';
		$_SESSION['latitude'] = 'na';
		$_SESSION['geolocated'] = 'yes';
		$_SESSION['continent_code'] = 'NA';

	}
}
?>
