<?php
/* **************************************************
								*********************
								*  Online version   *
								*********************

Report if the next night is good for viewing stars, 
and what to look for, expressed as XML.
TODO: read json file of events
	Event structure
		Event name
		Time of event: start / end
		Requirements of event: darkness, cloud cover, transparency, seeing, wind, humidity, temperature
			all requirments except temperature are scaled from 0 up. Temperature is in Celcius. -1 (including temperature) or absence means does not matter
		Kind of event: meteorlogical/astronomical; 
			astronomical events that take place at lunar distance or beyond can be seen by observers countries apart
			meteorlogical events like aurora and satelites are so close to the observer that they can only be expressed in Alt/Az and may appear different to observers blocks apart
			solar eclipses and occultations of stars by the moon are meteorlogical as observers countries apart may see something different
		Position of event: RA/decl;Alt/Az;whole sky
		
	

Big thanks to Allan Rahill of the Canadian Meteorological Center (weather.gc.ca) and Attila Danko of Clear Sky Chart (cleardarksky.com) for providing the maps and the locations, and inspiration.
Data on sun, moon, and planet locations from Heavens Above 

Location
- observatory name
- latitude
- longitude
- x-map1-coordinate
- y-map1-coordinate
- x-map2-coordinate
- y-map2-coordinate
+ sunrise
+ sunset
+ moonrise
+ moonset

Night
- hours
- moon

Weather Map
- download state
- url address
- local address
- weather map cloud cover
- weather map transparency
- weather map seeing
- weather map wind
- weather map humidity
- weather map temperature
+ get map

Weather Analysis
- colour
- text
- rating
- x
- y

Events

***************************************************** */

//Set timezone for Ottawa, source of government maps
define('TIMEZONE', 'America/Toronto');	// The time zone used by the map generating system
define('TZUTC', 'UTC');	// Zulu time, GMT in Standard Time 
define('TZSTD', 'standard');
define('TZDST', 'daylight');
define('TZLOCAL', 'localtimezone');	// The time zone of the observatory
define('TZOFFSET', 'tz');	// The hours off of GMT standard time, 1 hour less in Daylight Saving Time
define('TZISO', 'isotimezone');	// ISO standardized time zone, America/New_York 
define('TZCOLLOQ', 'coltimezone');	// Time zone as called colloqually, Eastern Standard Time, Pacific Daylight Time (EST, PDT)
define('SUNRISE', 'sunrise');
define('SUNSET', 'sunset');
define('SUNTRANSIT', 'transit');
define('CIVILTWILIGHTBEG', 'civil_twilight_begin');
define('CIVILTWILIGHTEND', 'civil_twilight_end');
define('NAUTTWILIGHTBEG', 'nautical_twilight_begin');
define('NAUTTWILIGHTEND', 'nautical_twilight_end');
define('ASTROTWILIGHTBEG', 'astronomical_twilight_begin');
define('ASTROTWILIGHTEND', 'astronomical_twilight_end');
define('MAPPARAM', 'map');
define('XCLOUDCOVER', 'xcc');
define('YCLOUDCOVER', 'ycc');
define('XNORTHAMERICA', 'xna');
define('YNORTHAMERICA', 'yna');
define('NECLOUDCOVER', 'northeast');
define('NWCLOUDCOVER', 'northwest');
define('SECLOUDCOVER', 'southeast');
define('SWCLOUDCOVER', 'southwest');
define('TRNORTHAMERICA', 'transparency');
define('SENORTHAMERICA', 'seeing');
define('UVNORTHAMERICA', 'wind');
define('HRNORTHAMERICA', 'humidity');
define('TTNORTHAMERICA', 'temperature');
define('LATITUDE', 'lat');
define('LONGITUDE', 'lng');
define('ALTITUDE', 'alt');

define('CRCOLOUR', 'colour');
define('CRTEXT', 'text');
define('CRRATING', 'rating');

define('HRTIME', 'Time');
define('HRATOM', 'Atomic');
define('ATOMICHOUR', 3600);

define('AVGEVENING', 'evening');
define('AVGNIGHT', 'overnight');
define('AVGMORN', 'morning');
define('AVGASTROTWILIGHT', 'astronomical_twilight');
define('AVGAVERAGE', 'average');
define('AVGCOUNT', 'count');
define('TIMEEVENING', '22h00');
define('TIMEMORN', '04h00');

define('DATADIR', 'data');
define('DATELENGTH', 10);

define('CSALTITUDE', 'alt');
define('CSAZIMUTH', 'az');
define('CSRIGHTASCENSION', 'ra');
define('CSDECLINATION', 'dec');
define('CSDISTANCE', 'dist');

error_reporting(E_ALL);
ini_set('display_errors', 1);

//	include 'MoonPhase.php';	// calculates illumination and phase of the moon
//	include 'dxprog.php';	// calculates moon rise and moon set
include 'suncalc.php';	// has functions and classes for sun and moon RA/D calculations, rise/set times, illumination, phase.
						/*
						sunCoords($date)	// calculates dec, ra for the sun
						moonCoords($date)	// calculates dec, ra, dist for the moon 
						
						$test = new SunCalc(new DateTime(), 48.85, 2.35);
						
						$test->getSunPosition()	// calculates altitude, azimuth of the sun for a given date and latitude/longitude
						$test->getSunTimes() 	// calculates sun times for a given date and latitude/longitude
						$test->getMoonPosition($date)	// calculates altitude, azimuth, distance of the moon for a finer date and the same latitude/longitude
						$test->getMoonIllumination()	// calculates fraction, phase, angle of the moon for a given date and latitude/longitude
						$test->getMoonTimes($inUTC=false)	// calculates moonrise, moonset, alwaysUp, alwaysDown of the moon for a given date and latitude/longitude.  Returns times in Zulu if inUTC is true.
						*/ 

class Location {
/*
Location
- observatory name
- latitude
- longitude
- x-map1-coordinate
- y-map1-coordinate
- x-map2-coordinate
- y-map2-coordinate
+ sunrise
+ sunset
+ moonrise
+ moonset
*/
	private $strObservatory = 'Fred';
	private $fltGeoposition = [ LATITUDE => 43.650821, LONGITUDE => -79.570732 ];
	private $intMap1Coordinates = [ MAPPARAM => NECLOUDCOVER, XCLOUDCOVER => 368, YCLOUDCOVER => 451 ];
	private $intMap2Coordinates = [ XNORTHAMERICA => 501, YNORTHAMERICA => 316 ];
	private $fltTimeZoneOffset = -4.0;
	private $fltTimeZoneAdjustment = 14400.0; 
	private $intSunrise;
	private $intSunset;
	
	private $dteSunrise;
	private $dteSunset;
	private $dteTransit;
	private $dteOpposition;
	private $dteCTBegin;
	private $dteCTEnd;
	private $dteNTBegin;
	private $dteNTEnd;
	private $dteATBegin;
	private $dteATEnd;
	private $dteMoonrise;
	private $dteMoonset;

	private $arrObservatory = [ 
		'Fred' => [ MAPPARAM => NECLOUDCOVER, XCLOUDCOVER => 367, YCLOUDCOVER => 450, XNORTHAMERICA => 500, YNORTHAMERICA => 315, LATITUDE => 43.650821, LONGITUDE => -79.570732, ALTITUDE => 110, TZOFFSET => -5, TZLOCAL => 'America/Toronto' ],
		'Mississauga' => [ MAPPARAM => NECLOUDCOVER, XCLOUDCOVER => 367, YCLOUDCOVER => 452, XNORTHAMERICA => 500, YNORTHAMERICA => 315, LATITUDE => 43.5802, LONGITUDE => -79.61658, ALTITUDE => 110, TZOFFSET => -5, TZLOCAL => 'America/Toronto' ],
		'Victoria' => [ MAPPARAM => NWCLOUDCOVER, XCLOUDCOVER => 258, YCLOUDCOVER => 492, XNORTHAMERICA => 143, YNORTHAMERICA => 322, LATITUDE => 48.433, LONGITUDE => -123.35, ALTITUDE => 18, TZOFFSET => -8, TZLOCAL => 'America/Vancouver' ],
		'Winnipeg' => [ MAPPARAM => NWCLOUDCOVER, XCLOUDCOVER => 596, YCLOUDCOVER => 470, XNORTHAMERICA => 333, YNORTHAMERICA => 309, LATITUDE => 49.68, LONGITUDE => -98.229, ALTITUDE => 232, TZOFFSET => -6, TZLOCAL => 'America/Winnipeg' ],
		'Orono' => [ MAPPARAM => NECLOUDCOVER, XCLOUDCOVER => 375, YCLOUDCOVER => 438, XNORTHAMERICA => 505, YNORTHAMERICA => 307, LATITUDE => 43.9518, LONGITUDE => -78.61700, ALTITUDE => 164, TZOFFSET => -5, TZLOCAL => 'America/Toronto' ],
		'Torrance Barrens' => [ MAPPARAM => NECLOUDCOVER, XCLOUDCOVER => 354, YCLOUDCOVER => 428, XNORTHAMERICA => 493, YNORTHAMERICA => 301, LATITUDE => 44.9342, LONGITUDE => -79.50111, ALTITUDE => 247, TZOFFSET => -5, TZLOCAL => 'America/Toronto' ],
		'Churchill' => [ MAPPARAM => NWCLOUDCOVER, XCLOUDCOVER => 597, YCLOUDCOVER => 277, XNORTHAMERICA => 333, YNORTHAMERICA => 199, LATITUDE => 58.767, LONGITUDE => -94.167, ALTITUDE => 6, TZOFFSET => -6, TZLOCAL => 'America/Winnipeg' ],
		'St. John\'s' => [ MAPPARAM => NECLOUDCOVER, XCLOUDCOVER => 581, YCLOUDCOVER => 128, XNORTHAMERICA => 626, YNORTHAMERICA => 125, LATITUDE => 47.55, LONGITUDE => -52.667, ALTITUDE => 18, TZOFFSET => -3.5, TZLOCAL => 'America/St_Johns' ],
		'Thunder Bay' => [ MAPPARAM => NECLOUDCOVER, XCLOUDCOVER => 199, YCLOUDCOVER => 430, XNORTHAMERICA => 402, YNORTHAMERICA => 302, LATITUDE => 48.2802777778, LONGITUDE => -89.5372222222, ALTITUDE => 187, TZOFFSET => -6, TZLOCAL => 'America/Thunder_Bay' ],
		'Iqaluit' => [ MAPPARAM => NECLOUDCOVER, XCLOUDCOVER => 235, YCLOUDCOVER => 64, XNORTHAMERICA => 423, YNORTHAMERICA => 87, LATITUDE => 63.7330, LONGITUDE => -68.50000, ALTITUDE => 11, TZOFFSET => -5, TZLOCAL => 'America/Iqaluit' ],
		'Fairbanks' => [ MAPPARAM => NWCLOUDCOVER, XCLOUDCOVER => 150, YCLOUDCOVER => 91, XNORTHAMERICA => 81, YNORTHAMERICA => 96, LATITUDE => 64.838, LONGITUDE => -147.716, ALTITUDE => 136, TZOFFSET => -9, TZLOCAL => 'America/Juneau' ],
		'Colbeck' => [ MAPPARAM => NECLOUDCOVER, XCLOUDCOVER => 353, YCLOUDCOVER => 451, XNORTHAMERICA => 492, YNORTHAMERICA => 315, LATITUDE => 43.99, LONGITUDE => -80.3622222222, ALTITUDE => 488, TZOFFSET => -5, TZLOCAL => 'America/Toronto' ] ];
/***  Other areas to validate Lat/Lng calculations
southwest
Anaheim: lat-long 33.8350 -117.91400; cloud2: (199, 465); trans2: (163, 508); wind2: (163, 508); hum2: (163, 508); temp2: (163, 508); seeing2: (163, 508)
Butte: lat-long 46.0040 -112.53400; cloud2: (325, 169); trans2: (226, 360); wind2: (226, 361); hum2: (226, 361); temp2: (226, 361); seeing2: (226, 360)
Portland: lat-long 45.5240 -122.67500; cloud2: (155, 161); trans2: (141, 356); wind2: (141, 357); hum2: (141, 357); temp2: (141, 357); seeing2: (141, 356)
Albuquerque: lat-long 35.0840 -106.65100; cloud2: (443, 438); trans2: (285, 495); wind2: (285, 495); hum2: (285, 495); temp2: (285, 495); seeing2: (285, 495)

northwest:
Seattle: lat-long 47.6060 -122.33100; trans2: (149, 333); wind2: (149, 333); hum2: (149, 333); temp2: (149, 333); seeing2: (149, 333); cloud2: (269, 512)

southeast
Mobile: lat-long 30.6954 -88.03990; trans2: (499, 500); wind2: (498, 502); hum2: (498, 502); temp2: (498, 502); seeing2: (499, 500); cloud2: (381, 444)
Austin: lat-long 30.2670 -97.74300; trans2: (393, 542); wind2: (393, 542); hum2: (393, 542); temp2: (393, 543); seeing2: (393, 542); cloud2: (184, 519)
Dallas: lat-long 32.7830 -96.80000; trans2: (395, 507); wind2: (396, 507); hum2: (395, 507); temp2: (395, 507); seeing2: (395, 507); cloud2: (189, 453)
Houston: lat-long 29.7630 -95.36300; trans2: (422, 542); wind2: (422, 542); hum2: (422, 542); temp2: (422, 542); seeing2: (422, 542); cloud2: (238, 518)
Chicago: lat-long 41.8500 -87.65000; trans2: (446, 368); wind2: (446, 368); hum2: (446, 368); temp2: (446, 368); seeing2: (446, 368); cloud2: (284, 195)
Hartford: lat-long 41.7640 -72.68600; trans2: (564, 298); wind2: (564, 298); hum2: (564, 298); temp2: (564, 299); cloud2: (475, 422); seeing2: (564, 298)
Miami: lat-long 25.7740 -80.19400; trans2: (616, 519); wind2: (616, 519); hum2: (616, 519); temp2: (616, 519); seeing2: (616, 519); cloud2: (596, 477)
Tallahassee: lat-long 30.4380 -84.28100; trans2: (540, 486); wind2: (541, 486); hum2: (540, 486); temp2: (540, 486); seeing2: (540, 486); cloud2: (457, 416)
Tampa: lat-long 27.9470 -82.45900; trans2: (576, 506); wind2: (576, 506); hum2: (576, 506); temp2: (576, 507); seeing2: (576, 506); cloud2: (523, 452)
Windsor: lat-long 42.3330 -83.03300; trans2: (482, 344); wind2: (482, 344); hum2: (482, 344); temp2: (482, 344); seeing2: (482, 344); cloud2: (349, 152)


*/
// TODO: create child class based on just lat/lng and tz; requires that position is on both maps; alt is to be looked up; tz might be a look up, too; would allow for reversal of data flow where Aurora Watch, Heavens Above would look here for local forecast instead of this fusking for their data.
	function __construct( $obs = null ) {
		date_default_timezone_set(TIMEZONE);
		if( is_null( $obs ) ) {
			$obs = $this->strObservatory;
			// TODO: echo 'Unknown observatory:'. $obs . chr(13) . chr(10);
		}
		else {
			switch( $obs ){
			case 'Fred':
			case 'Mississauga':
			case 'Victoria':
			case 'Winnipeg':
			case 'Churchill':
			case 'St. John\'s':
			case 'Thunder Bay':
			case 'Orono':
			case 'Torrance Barrens':
			case 'Iqaluit':
			case 'Fairbanks':
			case 'Colbeck':
				// Known observatory
				// TODO: echo 'Known observatory:'. $obs . chr(13) . chr(10);
				
		 		$this->strObservatory = $obs;
				$this->fltGeoposition = [ LATITUDE => $this->arrObservatory[ $obs ][ LATITUDE ], LONGITUDE =>  $this->arrObservatory[ $obs ][ LONGITUDE ] ];
				$this->intMap1Coordinates = [ MAPPARAM => $this->arrObservatory[ $obs ][ MAPPARAM ], XCLOUDCOVER => $this->arrObservatory[ $obs ][ XCLOUDCOVER ], YCLOUDCOVER => $this->arrObservatory[ $obs ][ YCLOUDCOVER ] ];
				$this->intMap2Coordinates = [ XNORTHAMERICA => $this->arrObservatory[ $obs ][ XNORTHAMERICA ], YNORTHAMERICA => $this->arrObservatory[ $obs ][ YNORTHAMERICA ] ];
				break;
			default:
				$obs = $this->strObservatory;
				// Unknown observatory, No change
				break;
			}
		}
		$tzUTC = new DateTimeZone( TZUTC );
		$tzLocal = new DateTimeZone( $this->arrObservatory[$this->strObservatory][ TZLOCAL ] );
		$dteLocal = new DateTime( 'now' );
		$dteLocal->setTimezone( $tzLocal );
		$this->fltTimeZoneAdjustment = $tzLocal->getOffset( $dteLocal ) - $tzUTC->getOffset( $dteLocal ); 
		// TODO: DELETE LINE: echo 'tz:'. $this->fltTimeZoneAdjustment/3600 .chr(13).chr(10);

		$this->fltTimeZoneOffset = $this->fltTimeZoneAdjustment / ATOMICHOUR;
		$this->arrObservatory[ $obs ][TZOFFSET] = $this->fltTimeZoneOffset;
					
		$arrSunInfo = date_sun_info( time(), $this->arrObservatory[ $obs ][ LATITUDE ], $this->arrObservatory[ $obs ][ LONGITUDE ]); 
		$this->dteTransit = $this->set_transit( $arrSunInfo );
		$arrTomorrow = date_sun_info( time()+86400, $this->arrObservatory[ $obs ][ LATITUDE ], $this->arrObservatory[ $obs ][ LONGITUDE ]); 
		$dteLocal->setTimestamp( $arrTomorrow[SUNRISE] );
		$this->intSunrise = $dteLocal->format('H');
		$dteLocal->setTimestamp( $arrSunInfo[SUNSET] );
		$this->intSunset = $dteLocal->format('H');
		$this->dteOpposition = $this->set_opposition( $arrSunInfo, $arrTomorrow );
		$this->dteSunrise = $this->set_sunrise( $arrTomorrow );
		$this->dteSunset = $this->set_sunset( $arrSunInfo );
		$this->dteCTBegin = $this->set_civiltwilightbegins( $arrTomorrow );
		$this->dteCTEnd = $this->set_civiltwilightends( $arrSunInfo );
		$this->dteNTBegin = $this->set_nauticaltwilightbegins( $arrTomorrow );
		$this->dteNTEnd = $this->set_nauticaltwilightends( $arrSunInfo );
		$this->dteATBegin = $this->set_astrotwilightbegins( $arrTomorrow );
		$this->dteATEnd = $this->set_astrotwilightends( $arrSunInfo );

	}

	/*
		set the sunrise and sunset and other solar times
		if the sun never rises, set the sunrise and sunset to the transit time
		if the sun never sets, set the sunrise and sunset to the opposition time
		similarly
		if twilight never begins, set the begin and end times to the transit time
		if twilight never ends, set the begin and end times to the opposition time
		
		an empty value in the element indicates that the sun never rises or twilight never begins
		value of 1 in the element indicates that the sun never sets or twilight never ends
		// TODO: verify that empty is no start and 1 is no end
	*/
	private function set_sunrise( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			if( is_numeric( $arrInfo[SUNRISE] ) ) {
				if( $arrInfo[SUNRISE] == 1 ) {
					return( $arrInfo[SUNTRANSIT] );
				}
				else {
					return( $arrInfo[SUNRISE] );
				}
			}
			else {
				return( $this->dteOpposition );
			}
		}
		return( $this->dteSunrise ); 
	}
	private function set_sunset( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			if( is_numeric( $arrInfo[SUNSET] ) ) {
				if( $arrInfo[SUNSET] == 1 ) {
					return( $arrInfo[SUNTRANSIT] );
				}
				else {
					return( $arrInfo[SUNSET] );
				}
			}
			else {
				return( $this->dteOpposition );
			}			
		}
		return( $this->dteSunset ); 
	}
	private function set_transit( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			return( $arrInfo[SUNTRANSIT] );
		}
		return( $this->dteTransit ); 
	}
	private function set_opposition( $arrInfo = null, $arrTomorrow = null ){ 
		if( is_array( $arrInfo ) && is_array( $arrTomorrow ) ) {
			return( floor( ($arrTomorrow[SUNTRANSIT] + $arrInfo[SUNTRANSIT]) / 2 ) );
		}
		return( $this->dteOpposition ); 
	}
	private function set_civiltwilightbegins( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			if( is_numeric( $arrInfo[CIVILTWILIGHTBEG] ) ) {
				if( $arrInfo[CIVILTWILIGHTBEG] == 1 ) {
					return( $arrInfo[SUNTRANSIT] );
				}
				else {
					return( $arrInfo[CIVILTWILIGHTBEG] );
				}
			}
			else {
				return( $this->dteOpposition );
			}			
		}
		return( $this->dteCTBegin ); 
	}
	private function set_civiltwilightends( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			if( is_numeric( $arrInfo[CIVILTWILIGHTEND] ) ) {
				if( $arrInfo[CIVILTWILIGHTEND] == 1 ) {
					return( $arrInfo[SUNTRANSIT] );
				}
				else {
					return( $arrInfo[CIVILTWILIGHTEND] );
				}
			}
			else {
				return( $this->dteOpposition );
			}			
		}
		return( $this->dteCTEnd ); 
	}
	private function set_nauticaltwilightbegins( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			if( is_numeric( $arrInfo[NAUTTWILIGHTBEG] ) ) {
				if( $arrInfo[NAUTTWILIGHTBEG] == 1 ) {
					return( $arrInfo[SUNTRANSIT] );
				}
				else {
					return( $arrInfo[NAUTTWILIGHTBEG] );
				}
			}
			else {
				return( $this->dteOpposition );
			}			
		}
		return( $this->dteNTBegin ); 
	}
	private function set_nauticaltwilightends( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			if( is_numeric( $arrInfo[NAUTTWILIGHTEND] ) ) {
				if( $arrInfo[NAUTTWILIGHTEND] == 1 ) {
					return( $arrInfo[SUNTRANSIT] );
				}
				else {
					return( $arrInfo[NAUTTWILIGHTEND] );
				}
			}
			else {
				return( $this->dteOpposition );
			}			
		}
		return( $this->dteNTEnd ); 
	}
	private function set_astrotwilightbegins( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			if( is_numeric( $arrInfo[ASTROTWILIGHTBEG] ) ) {
				if( $arrInfo[ASTROTWILIGHTBEG] == 1 ) {
					return( $arrInfo[SUNTRANSIT] );
				}
				else {
					return( $arrInfo[ASTROTWILIGHTBEG] );
				}
			}
			else {
				return( $this->dteOpposition );
			}			
		}
		return( $this->dteATBegin ); 
	}
	private function set_astrotwilightends( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			if( is_numeric( $arrInfo[ASTROTWILIGHTEND] ) ) {
				if( $arrInfo[ASTROTWILIGHTEND] == 1 ) {
					return( $arrInfo[SUNTRANSIT] );
				}
				else {
					return( $arrInfo[ASTROTWILIGHTEND] );
				}
			}
			else {
				return( $this->dteOpposition );
			}			
		}
		return( $this->dteATEnd ); 
	}
	
	public function get_transit(){ return( $this->dteTransit ); }
	public function get_opposition(){ return( $this->dteOpposition ); }
	public function get_sunrise(){ return( $this->dteSunrise ); }
	public function get_sunset(){ return( $this->dteSunset ); }
	public function get_civiltwilightbegins(){ return( $this->dteCTBegin ); }
	public function get_civiltwilightends(){ return( $this->dteCTEnd ); }
	public function get_nauticaltwilightbegins(){ return( $this->dteNTBegin ); }
	public function get_nauticaltwilightends(){ return( $this->dteNTEnd ); }
	public function get_astrotwilightbegins(){ return( $this->dteATBegin ); }
	public function get_astrotwilightends(){ return( $this->dteATEnd ); }
	
	public function observatory() { return( $this->strObservatory ); }
	
	public function geoposition(){ return( $this->fltGeoposition ); }
	
	public function map1coordinates(){ return( $this->intMap1Coordinates ); }
	
	public function map2coordinates(){ return( $this->intMap2Coordinates ); }
	
	// Public function to return sunrise time in Local Time.  Must be between 00 and 13.
	public function sunrise_hour(){
		if( isset( $this->arrObservatory[$this->strObservatory][SUNRISE] ) ) {
			$intSunrise = $this->arrObservatory[$this->strObservatory][SUNRISE];
			// TODO: DELETE LINE: echo 'Sunrise:'. $intSunrise .chr(13).chr(10);
		}
		else {
			// TODO: DELETE LINE: $tzLocal = new DateTimeZone( $this->arrObservatory[$this->strObservatory][ TZLOCAL ] );
			// TODO: DELETE LINE: $dteLocal = new DateTime( 'now', $tzLocal );
			// TODO: DELETE LINE: echo 'Sunrise:'. $this->arrObservatory[$this->strObservatory][TZOFFSET] .chr(13).chr(10);
			// TODO: DELETE LINE: $this->arrObservatory[$this->strObservatory][TZOFFSET] = $tzLocal->getOffset( $dteLocal )/3600 ;
			// TODO: DELETE LINE: echo 'Sunrise:'. $this->arrObservatory[$this->strObservatory][TZOFFSET] .chr(13).chr(10);
			$intSunrise = (integer) date('H',(date_sunrise(time(),SUNFUNCS_RET_TIMESTAMP,$this->arrObservatory[$this->strObservatory][LATITUDE], $this->arrObservatory[$this->strObservatory][LONGITUDE],90,$this->arrObservatory[$this->strObservatory][TZOFFSET]) ) );
			$this->arrObservatory[$this->strObservatory][SUNRISE] = $intSunrise;
		}
		return $intSunrise;
	}

	// Public function to return sunset time in Local Time.  Must be over 12.
	public function sunset_hour(){
		if( isset( $this->arrObservatory[$this->strObservatory][SUNSET] ) ) {
			$intSunset = $this->arrObservatory[$this->strObservatory][SUNSET];
		}
		else {
			// TODO: DELETE LINE: $tzLocal = new DateTimeZone( $this->arrObservatory[$this->strObservatory][ TZLOCAL ] );
			// TODO: DELETE LINE: $dteLocal = new DateTime( 'now', $tzLocal );
			// TODO: DELETE LINE: echo 'Sunset:'. $this->arrObservatory[$this->strObservatory][TZOFFSET] .chr(13).chr(10);
			// TODO: DELETE LINE: $this->arrObservatory[$this->strObservatory][TZOFFSET] = $tzLocal->getOffset( $dteLocal )/3600 ;
			// TODO: DELETE LINE: echo 'Sunset Lat:'. $this->arrObservatory[$this->strObservatory][LATITUDE] .chr(13).chr(10);
			// TODO: DELETE LINE: echo 'Sunset Lng:'. $this->arrObservatory[$this->strObservatory][LONGITUDE] .chr(13).chr(10);
			// TODO: DELETE LINE: echo 'Sunset TZ:'. $this->arrObservatory[$this->strObservatory][TZOFFSET] .chr(13).chr(10);
			$intSunset = (integer) date('H',(date_sunset(time(),SUNFUNCS_RET_TIMESTAMP,$this->arrObservatory[$this->strObservatory][LATITUDE], $this->arrObservatory[$this->strObservatory][LONGITUDE],90,$this->arrObservatory[$this->strObservatory][TZOFFSET]) ) );
			// TODO: DELETE LINE: echo 'Sunset:'. $intSunset .chr(13).chr(10);
			$this->arrObservatory[$this->strObservatory][SUNSET] = $intSunset < 12 ? $intSunset + 24 : $intSunset;
		}
		return $intSunset;
	}

	public function timeoffset() { return( $this->fltTimeZoneOffset ); }
	public function timezone() { return( $this->arrObservatory[$this->strObservatory][ TZLOCAL ] ); }

	public function map() { return( $this->arrObservatory[ $this->strObservatory ][ MAPPARAM ] ); }
	
}

class GeoLocation extends Location {
// TODO: create child class based on just lat/lng; requires that position is on both maps; alt is to be looked up; tz might be a look up, too; would allow for reversal of data flow where Aurora Watch, Heavens Above would look here for local forecast instead of this fusking for their data.
// TODO: reverse the calculation that Attila Danko uses to calculate the lat/lng based on the map position. 
/*
Assertion: the map uses radial coordinates with the centre off of the map
re=;  TODO: Check with Attila to find out what it's for
longitude_offset=;  The polar coordinate is skewed by a few degrees
x_offset=;  The north pole is not on the image 
y_offset=;  The X,Y coordinates need tbe offset
Example: northwest cloud_quadrant
re=1996.44363878;longitude_offset=21.0903036044;x_offset=-80.0592559677;y_offset=-278.039242032;
Example: northeast cloud_quadrant
re=2088.73662456;longitude_offset=20.8820184467;x_offset=429.191923645;y_offset=-282.399544304;
Example: southwest cloud_quadrant
re=2358.48225263;longitude_offset=20.9742281431;x_offset=351.030821817;y_offset=-783.700927506;
Example: southeast cloud_quadrant
re=2172.07869185;longitude_offset=20.8369967695;x_offset=-98.4221876369;y_offset=-696.29098283;
Example: transparency, seeing
re=             	1174.17107051;
longitude_offset=	21.0522177703;
x_offset=       	237.823352676;
y_offset=       	-113.829432426;
Example: wind 
re=1174.16904765;longitude_offset=21.0603019683;x_offset=237.808527382;y_offset=-113.650010673;
Example: humidity
re=1174.20983653;longitude_offset=21.0563495659;x_offset=237.757334067;y_offset=-113.678638164;
Example: temperature
re=1174.15495006;longitude_offset=21.0456753417;x_offset=237.842271542;y_offset=-113.593452387;

calculation to reverse:
	latlon =  {'lat':0,'lon':0};
        x=x-x_offset+0.5; y=y-y_offset+0.5;      
        dlon=Math.atan(y/x)/degrees_to_radians;
        if (x<0.5) dlon = dlon+ sgn(y)*180;                
        dlon=dlon+longitude_offset;
        if (dlon > 180) {dlon=dlon-360;}
        else { if (dlon < -180) dlon=dlon+360; }; 
        r2 = x*x + y*y;
        re2 = re*re; 
        dlat=(re2-r2)/(re2+r2);
        dlat=Math.asin(dlat)/degrees_to_radians;
        latlon.lat = dlat; latlon.lon=-dlon;
        return latlon;

method:
	knowing that the map is radial, 
	use the complement of the latitude to calculate the radius
	use the complement of the longitude to calculate the angle
	fit the offsets and test
	
	re - a radius modifier specific to each map
	lng offset - the map is slightly rotated so that 0º longitude is not flat across
	x offset	y offset - the pole is not in the upper right corner
         	re   	lng offset	x offset	y offset
northeast	1996.44	21.09000	-80.06000	-278.04
southeast	2172.08	20.84000	-98.42000	-696.29
southwest	2358.48	20.97000	351.03000	-783.7
northwest	2088.74	20.88000	429.19000	-282.4

	r2 = re squared
	h2 = hypotenuse squared
	s = slope

	a=(r2 - h2) / (r2 + h2)
	h2 = ( r2 - a * r2 ) / a - 1
	s = y / x
	h2 = x2 + y2

	a = sin( LatRad )
	r2 = re*re
	h2 = ( r2 - a * r2 ) / a - 1
	
	lngNorm = LngDeg - lngOffset
	LngRad = lngNorm*deg2rad
	s = tan( LngRad )
	x = = SQRT( h2 / (1 + 1/s ) )
	y = x * s
	xMap = floor(x+xOffset +.5)
	yMap = floor(y+yOffset +.5)

*/

// GeoLocation
	private $strObservatory = 'Unknown';	// Give the observation point a name
	private $fltGeoposition = [ LATITUDE => 43.650821, LONGITUDE => -79.570732 ];	// Default location is Eatonville (Etobicoke), Ontario
	private $intMap1Coordinates = [ MAPPARAM => NECLOUDCOVER, XCLOUDCOVER => 368, YCLOUDCOVER => 451 ];	// Default cloud cover map and pixels
	private $intMap2Coordinates = [ XNORTHAMERICA => 501, YNORTHAMERICA => 316 ];	// Default pixels on North America map
	private $fltTimeZoneAdjustment = -14400.0; // Time zone offset in seconds
	private $fltTimeZoneOffset = -4.0;	// Default timezone offset in hours
	private $strTimeZone = TIMEZONE;
	private $intSunrise;
	private $intSunset;
// GeoLocation
	
	private $dteSunrise;
	private $dteSunset;
	private $dteTransit;
	private $dteOpposition;
	private $dteCTBegin;
	private $dteCTEnd;
	private $dteNTBegin;
	private $dteNTEnd;
	private $dteATBegin;
	private $dteATEnd;
	private $dteMoonrise;
	private $dteMoonset;

// GeoLocation
	function __construct( $geoPos = [ LATITUDE => 43.650821, LONGITUDE => -79.570732 ], $obs = 'Unknown' ) {
		date_default_timezone_set(TIMEZONE);
		$fltLat = (float)$geoPos[ LATITUDE ];
		$fltLng = (float)$geoPos[ LONGITUDE ];

		$strMap = '';
		// Cloud Cover
		if( $fltLat >= 74 ||  $fltLat <= 22 || $fltLng >= -49 || $fltLng <= -160 ) { 
			$this->strObservatory = $obs;
			$this->fltGeoposition = [ LATITUDE => $fltLat, LONGITUDE => $fltLng ];
			// Unobtainable observation point, No views
			$strMap = NECLOUDCOVER;
			$fltPixelX = 16;	// Set the view point to a border colour
			$fltPixelY = 32;
			$this->intMap1Coordinates = [ MAPPARAM => $strMap, XCLOUDCOVER => $fltPixelX, YCLOUDCOVER => $fltPixelY ];
			$fltPixelX = 2; 	// Set the view point to a border 
			$fltPixelY = 2;
			$this->intMap2Coordinates = [ XNORTHAMERICA => $fltPixelX, YNORTHAMERICA => $fltPixelY ];
		}
		else {
			if( $fltLat < 74 ) { 
				if( $fltLat > 43 ) {
					if( $fltLng < -95 ) {
						$strMap = NWCLOUDCOVER;
						$fltOffsetX = 429.191923645;
						$fltOffsetY = -282.399544304;
						$fltOffsetLng = 20.8820184467;
						$fltRSquared = pow(2088.73662456,2); 
					}
					elseif( $fltLng < -49 ) {
						$strMap = NECLOUDCOVER; 
						$fltOffsetX = -80.0592559677;
						$fltOffsetY = -278.039242032; 
						$fltOffsetLng = 21.0903036044;
						$fltRSquared = pow(1996.44363878,2); 
					}
				}
				elseif( $fltLat > 22 ) {
					if( $fltLng < -100 ) {
						$strMap = SWCLOUDCOVER;
						$fltOffsetX = 351.030821817;
						$fltOffsetY = -783.700927506; 
						$fltOffsetLng = 20.9742281431;
						$fltRSquared = pow(2358.48225263,2); 
					}
					elseif( $fltLng < -49 ) {
						$strMap = SECLOUDCOVER; 
						$fltOffsetX = -98.4221876369;
						$fltOffsetY = -696.29098283;
						$fltOffsetLng = 20.8369967695;
						$fltRSquared = pow(2172.07869185,2); 
					}
				}
			}
			$fltDeg2Rad = M_PI / 180.0;
			$fltAlpha = sin( $fltLat * $fltDeg2Rad );
			$fltSlope = tan( -( $fltLng + $fltOffsetLng) * $fltDeg2Rad );
			$fltHypSquared = ( $fltRSquared - $fltAlpha * $fltRSquared ) / ( $fltAlpha + 1 );
			$fltY = SQRT( $fltHypSquared / ( 1 + 1/$fltSlope/$fltSlope  ) );
			$fltPixelY = $fltY + $fltOffsetY;
			$fltX = $fltY / $fltSlope;
			$fltPixelX = $fltX + $fltOffsetX;
			// TODO: DELETE LINE 			echo 'step 13:' . $fltLng.chr(13).chr(10);
			// TODO: DELETE LINE 			echo 'step 12:' . $fltLat.chr(13).chr(10);
			// TODO: DELETE LINE 			echo 'step 11:' . $fltLat.chr(13).chr(10);
			// TODO: DELETE LINE 			echo 'step 10:' . $fltAlpha.chr(13).chr(10);
			// TODO: DELETE LINE 			echo 'step  9:' . $fltRSquared.chr(13).chr(10);
			// TODO: DELETE LINE 			echo 'step  8:' . $fltHypSquared.chr(13).chr(10);
			// TODO: DELETE LINE 			echo 'step  5:' . -$fltLng.chr(13).chr(10);
			// TODO: DELETE LINE 			echo 'step  4:' . (-$fltLng - $fltOffsetLng).chr(13).chr(10);
			// TODO: DELETE LINE 			echo 'col  BE:' . $fltSlope.chr(13).chr(10);
			// TODO: DELETE LINE 			echo 'step  2:' . $fltY.chr(13).chr(10);
			// TODO: DELETE LINE 			echo 'step  1:' . $fltX.chr(13).chr(10);
			// TODO: DELETE LINE 			echo 'col   R:' . $fltOffsetY.chr(13).chr(10);
			// TODO: DELETE LINE 			echo 'col   Q:' . $fltOffsetX.chr(13).chr(10);
			// TODO: DELETE LINE 			echo 'col  BC:' . $fltPixelY.chr(13).chr(10);
			// TODO: DELETE LINE 			echo 'col  BD:' . $fltPixelX.chr(13).chr(10);
			// TODO: DELETE LINE echo 'fltOffsetX:'. $fltOffsetX . ' fltOffsetY:'. $fltOffsetY . ' fltOffsetLng:'. $fltOffsetLng . ' fltRSquared:'. $fltRSquared .chr(13).chr(10);
			// TODO: DELETE LINE echo 'Alpha:'. $fltAlpha. ' Slope:'. $fltSlope. ' Hyp:'. $fltHypSquared .' Map:'. $strMap. ' X:'. $fltX. ' Y:'. $fltY .chr(13).chr(10);

			// check if pixel is off of the map or on a text box
			$arrPixelConfirm = $this->validate_pixel( $strMap, $fltPixelX, $fltPixelY );
			$this->fltGeoposition = [ LATITUDE => $fltLat, LONGITUDE => $fltLng ];
			$this->intMap1Coordinates = [ MAPPARAM => $strMap, XCLOUDCOVER => $arrPixelConfirm[LATITUDE], YCLOUDCOVER => $arrPixelConfirm[LONGITUDE] ];

			$fltRSquared=pow(1174.16904765,2);
			$fltOffsetLng=21.0603019683;
			$fltOffsetX=237.808527382;
			$fltOffsetY=-113.650010673;
			$fltAlpha = sin( $fltLat * $fltDeg2Rad );
			$fltAlpha = sin( $fltLat * $fltDeg2Rad );
			$fltSlope = tan( -( $fltLng + $fltOffsetLng) * $fltDeg2Rad );
			$fltHypSquared = ( $fltRSquared - $fltAlpha * $fltRSquared ) / ( $fltAlpha + 1 );
			$fltY = SQRT( $fltHypSquared / ( 1 + 1/$fltSlope/$fltSlope  ) );
			$fltPixelY = $fltY + $fltOffsetY;
			$fltX = $fltY / $fltSlope;
			$fltPixelX = $fltX + $fltOffsetX;
			// TODO: DELETE LINE echo 'Map:'. $strMap. ' X:'. $fltX. ' Y:'. $fltY .chr(13).chr(10);
	
			// check if pixel is off of the map or on a text box
			$arrPixelConfirm = $this->validate_pixel( TRNORTHAMERICA, $fltPixelX, $fltPixelY );
			$this->intMap2Coordinates = [ XNORTHAMERICA => $arrPixelConfirm[LATITUDE], YNORTHAMERICA => $arrPixelConfirm[LONGITUDE] ];
		}	   	
		// $strGoogleAPIkey='AIzaSyDO0vSeg58MtXboS4iJuTT778pKPUAsnl8'; // Browser key
		$strGoogleAPIkey='AIzaSyBHVGRox44tkYoHtFFIfzSjxuN6IJ-0MgI'; // Elevation key
		// TODO: https://maps.googleapis.com/maps/api/elevation/json?locations=39.7391536,-104.9847034&key=
		$strLatLng = urlencode( (string)$fltLat .','. (string)$fltLng );
		$objRequest = file_get_contents('https://maps.googleapis.com/maps/api/elevation/json?locations=' . $strLatLng . '&key=' . $strGoogleAPIkey );
		$arrJson = json_decode($objRequest, true);	// Return a JSON array
		// TODO: DELETE LINE  var_dump( $arrJson );
		$intElevation = ( isset($arrJson['results']['elevation']) ? $arrJson['results']['elevation'] :113 );

		$strGoogleAPIkey='AIzaSyA6vk3c-4SnKzfrzK2qLgH9hQ-NEkLZsIU'; // Timezone key
		// TODO: https://maps.googleapis.com/maps/api/timezone/json?location=39.7391536,-104.9847034&timestamp=&key=
		$objRequest = file_get_contents('https://maps.googleapis.com/maps/api/timezone/json?location=' . $strLatLng . '&timestamp=' . time() . '&key=' . $strGoogleAPIkey );
		$arrJson = json_decode($objRequest, true);	// Return a JSON array
		// TODO: DELETE LINE  var_dump( $arrJson );
		$this->strTimeZone = ( isset($arrJson['timeZoneId']) ? $arrJson['timeZoneId'] : TIMEZONE );;
		$this->fltTimeZoneAdjustment = ( isset($arrJson['rawOffset']) && isset($arrJson['dstOffset'])? ($arrJson['rawOffset']+$arrJson['dstOffset']) : -4.0*ATOMICHOUR );
		$this->fltTimeZoneOffset = $this->fltTimeZoneAdjustment / ATOMICHOUR;
		
		$this->strObservatory = $obs;

		$arrSunInfo = date_sun_info( time(), $fltLat, $fltLng ); 
		$this->dteTransit = $this->set_transit( $arrSunInfo );
		$arrTomorrow = date_sun_info( time()+86400, $fltLat, $fltLng ); 
		$this->dteOpposition = $this->set_opposition( $arrSunInfo, $arrTomorrow );
		$this->dteSunrise = $this->set_sunrise( $arrTomorrow );
		$this->dteSunset = $this->set_sunset( $arrSunInfo );
		$this->dteCTBegin = $this->set_civiltwilightbegins( $arrTomorrow );
		$this->dteCTEnd = $this->set_civiltwilightends( $arrSunInfo );
		$this->dteNTBegin = $this->set_nauticaltwilightbegins( $arrTomorrow );
		$this->dteNTEnd = $this->set_nauticaltwilightends( $arrSunInfo );
		$this->dteATBegin = $this->set_astrotwilightbegins( $arrTomorrow );
		$this->dteATEnd = $this->set_astrotwilightends( $arrSunInfo );

		$this->intSunrise = $this->sunrise_hour();
		$this->intSunset = $this->sunset_hour();
		// TODO: DELETE LINE  echo 'TZ:'. $this->strTimeZone . ' Timeoffset:' . $this->fltTimeZoneOffset .chr(13).chr(10);
	}
// GeoLocation
	// Check if pixel actually checks the weather
	private function validate_pixel( $strMap, $fltX, $fltY ) {
		// check if pixel is off of the map or on a text box
		if( $fltX<=1 || $fltY<=1 || $fltX>=717 || $fltY>=598 
				|| $fltX>=4 && $fltY>=4 && $fltX<=380 && $fltY<=45 
				|| $fltX>=660 && $fltY>=196 && $fltX<=715 && $fltY<=596 ) {
			// TODO: DELETE LINE  echo 'Map:'. $strMap. ' X:'. $fltX. ' Y:'. $fltY .chr(13).chr(10);
			switch( $strMap ) {
			case NECLOUDCOVER:
			case NWCLOUDCOVER:
			case SECLOUDCOVER:
			case SWCLOUDCOVER:
				$intPixelX = 16;
				$intPixelY = 32;
				break;
			case TRNORTHAMERICA:
				$intPixelX = 2;
				$intPixelY = 2;
				break;
			}
		}
		else {

			// Check if the pixel winds up on a border
			$imgColourReference = imagecreate( 10, 10 );
			switch( $strMap ) {
			case NECLOUDCOVER:
			case NWCLOUDCOVER:
			case SECLOUDCOVER:
			case SWCLOUDCOVER:
				$arrColourReference = 	
					[
					[ CRCOLOUR => '#FF0000', CRTEXT => 'border', CRRATING => -1 ],
					[ CRCOLOUR => '#FBFBFB', CRTEXT => 'Overcast', CRRATING => 0 ],
					[ CRCOLOUR => '#EAEAEA', CRTEXT => '90% covered', CRRATING => 1 ],
					[ CRCOLOUR => '#C2C2C2', CRTEXT => '80% covered', CRRATING => 2 ],
					[ CRCOLOUR => '#AEEEEE', CRTEXT => '70% covered', CRRATING => 3 ],
					[ CRCOLOUR => '#9ADADA', CRTEXT => '60% covered', CRRATING => 4 ],
					[ CRCOLOUR => '#77B7F7', CRTEXT => '50% covered', CRRATING => 5 ],
					[ CRCOLOUR => '#63A3E3', CRTEXT => '40% covered', CRRATING => 6 ],
					[ CRCOLOUR => '#4F8FCF', CRTEXT => '30% covered', CRRATING => 7 ],
					[ CRCOLOUR => '#2767A7', CRTEXT => '20% covered' , CRRATING => 8],
					[ CRCOLOUR => '#135393', CRTEXT => '10% covered', CRRATING => 9 ],
					[ CRCOLOUR => '#003F7F', CRTEXT => 'Clear', CRRATING => 10 ]
					];
				break;
			case TRNORTHAMERICA:
				$arrColourReference = 	
					[
					[ CRCOLOUR => '#FF0000', CRTEXT => 'border', CRRATING => -1 ],
					[ CRCOLOUR => '#F9F9F9', CRTEXT => 'Too cloudy to forecast', CRRATING => 0 ],
					[ CRCOLOUR => '#C7C7C7', CRTEXT => 'Poor', CRRATING => 1 ],
					[ CRCOLOUR => '#95D5D5', CRTEXT => 'Below Average', CRRATING => 2 ],
					[ CRCOLOUR => '#63A3E3', CRTEXT => 'Average', CRRATING => 3 ],
					[ CRCOLOUR => '#2C6CAC', CRTEXT => 'Above average', CRRATING => 4 ],
					[ CRCOLOUR => '#003F7F', CRTEXT => 'Transparent', CRRATING => 5 ]
					];
				break;
			}
			foreach( $arrColourReference AS $colref ) {
				imagecolorallocate( $imgColourReference, hexdec( substr( $colref[ CRCOLOUR ], 1, 2 ) ), hexdec( substr( $colref[ CRCOLOUR ], 3, 2 )), hexdec( substr( $colref[ CRCOLOUR ], 5, 2 )) ); 
			}
			switch( $strMap ) {
			case NECLOUDCOVER:
				$strLocalAddress = 'data/northeast_I_ASTRO_nt.png';
				break;
			case NWCLOUDCOVER:
				$strLocalAddress = 'data/northwest_I_ASTRO_nt.png';
				break;
			case SECLOUDCOVER:
				$strLocalAddress = 'data/southeast_I_ASTRO_nt.png';
				break;
			case SWCLOUDCOVER:
				$strLocalAddress = 'data/southwest_I_ASTRO_nt.png';
				break;
			case TRNORTHAMERICA:
				$strLocalAddress = 'data/astro_I_ASTRO_transp.png';
				break;
			}
			$imgMap = imagecreatefrompng($strLocalAddress);
			$intPixelX = floor($fltX + .5);
			$intPixelY = floor($fltY + .5);
			$rgb = imagecolorsforindex($imgMap, imagecolorat($imgMap, $intPixelX, $intPixelY ));
			$index = imagecolorclosest( $imgColourReference, $rgb['red'], $rgb['green'], $rgb['blue'] );
			$strText = $arrColourReference[ $index ][ CRTEXT ];
			if( $strText == 'border' ) {
				// the rounded pixel point is on a border.  find a nearby point.
				$intPixelX = floor($fltX);
				$intPixelY = floor($fltY);
				$rgb = imagecolorsforindex($imgMap, imagecolorat($imgMap, $intPixelX, $intPixelY ));
				$index = imagecolorclosest( $imgColourReference, $rgb['red'], $rgb['green'], $rgb['blue'] );
				$strText = $arrColourReference[ $index ][ CRTEXT ];
				if( $strText == 'border' ) {
					// the upper left pixel point is also on a border.  find a nearby point.
					$intPixelX = ceil($fltX);
					$intPixelY = floor($fltY);
					$rgb = imagecolorsforindex($imgMap, imagecolorat($imgMap, $intPixelX, $intPixelY ));
					$index = imagecolorclosest( $imgColourReference, $rgb['red'], $rgb['green'], $rgb['blue'] );
					$strText = $arrColourReference[ $index ][ CRTEXT ];
					if( $strText == 'border' ) {
						// the upper right pixel point is also on a border.  find a nearby point.
						$intPixelX = floor($fltX);
						$intPixelY = ceil($fltY);
						$rgb = imagecolorsforindex($imgMap, imagecolorat($imgMap, $intPixelX, $intPixelY ));
						$index = imagecolorclosest( $imgColourReference, $rgb['red'], $rgb['green'], $rgb['blue'] );
						$strText = $arrColourReference[ $index ][ CRTEXT ];
						if( $strText == 'border' ) {
							// the lower left pixel point is also on a border.  find a nearby point.
							$intPixelX = ceil($fltX);
							$intPixelY = ceil($fltY);
							$rgb = imagecolorsforindex($imgMap, imagecolorat($imgMap, $intPixelX, $intPixelY ));
							$index = imagecolorclosest( $imgColourReference, $rgb['red'], $rgb['green'], $rgb['blue'] );
							$strText = $arrColourReference[ $index ][ CRTEXT ];
							if( $strText == 'border' ) {
								// the lower right pixel point is also on a border.  find a nearby point.
								
							}
						}
					}
				}
			}

			imagedestroy( $imgColourReference );
			imagedestroy( $imgMap );
			// Finished: Check if the pixel winds up on a border
			
		}
		return [ LATITUDE => $intPixelX, LONGITUDE => $intPixelY ];
	}
	
	private function set_sunrise( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			if( is_numeric( $arrInfo[SUNRISE] ) ) {
				if( $arrInfo[SUNRISE] == 1 ) {
					return( $arrInfo[SUNTRANSIT] );
				}
				else {
					return( $arrInfo[SUNRISE] );
				}
			}
			else {
				return( $this->dteOpposition );
			}
		}
		return( $this->dteSunrise ); 
	}
	private function set_sunset( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			if( is_numeric( $arrInfo[SUNSET] ) ) {
				if( $arrInfo[SUNSET] == 1 ) {
					return( $arrInfo[SUNTRANSIT] );
				}
				else {
					return( $arrInfo[SUNSET] );
				}
			}
			else {
				return( $this->dteOpposition );
			}			
		}
		return( $this->dteSunset ); 
	}
	private function set_transit( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			return( $arrInfo[SUNTRANSIT] );
		}
		return( $this->dteTransit ); 
	}
	private function set_opposition( $arrInfo = null, $arrTomorrow = null ){ 
		if( is_array( $arrInfo ) && is_array( $arrTomorrow ) ) {
			return( floor( ($arrTomorrow[SUNTRANSIT] + $arrInfo[SUNTRANSIT]) / 2 ) );
		}
		return( $this->dteOpposition ); 
	}
	private function set_civiltwilightbegins( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			if( is_numeric( $arrInfo[CIVILTWILIGHTBEG] ) ) {
				if( $arrInfo[CIVILTWILIGHTBEG] == 1 ) {
					return( $arrInfo[SUNTRANSIT] );
				}
				else {
					return( $arrInfo[CIVILTWILIGHTBEG] );
				}
			}
			else {
				return( $this->dteOpposition );
			}			
		}
		return( $this->dteCTBegin ); 
	}
	private function set_civiltwilightends( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			if( is_numeric( $arrInfo[CIVILTWILIGHTEND] ) ) {
				if( $arrInfo[CIVILTWILIGHTEND] == 1 ) {
					return( $arrInfo[SUNTRANSIT] );
				}
				else {
					return( $arrInfo[CIVILTWILIGHTEND] );
				}
			}
			else {
				return( $this->dteOpposition );
			}			
		}
		return( $this->dteCTEnd ); 
	}
	private function set_nauticaltwilightbegins( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			if( is_numeric( $arrInfo[NAUTTWILIGHTBEG] ) ) {
				if( $arrInfo[NAUTTWILIGHTBEG] == 1 ) {
					return( $arrInfo[SUNTRANSIT] );
				}
				else {
					return( $arrInfo[NAUTTWILIGHTBEG] );
				}
			}
			else {
				return( $this->dteOpposition );
			}			
		}
		return( $this->dteNTBegin ); 
	}
	private function set_nauticaltwilightends( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			if( is_numeric( $arrInfo[NAUTTWILIGHTEND] ) ) {
				if( $arrInfo[NAUTTWILIGHTEND] == 1 ) {
					return( $arrInfo[SUNTRANSIT] );
				}
				else {
					return( $arrInfo[NAUTTWILIGHTEND] );
				}
			}
			else {
				return( $this->dteOpposition );
			}			
		}
		return( $this->dteNTEnd ); 
	}
	private function set_astrotwilightbegins( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			if( is_numeric( $arrInfo[ASTROTWILIGHTBEG] ) ) {
				if( $arrInfo[ASTROTWILIGHTBEG] == 1 ) {
					return( $arrInfo[SUNTRANSIT] );
				}
				else {
					return( $arrInfo[ASTROTWILIGHTBEG] );
				}
			}
			else {
				return( $this->dteOpposition );
			}			
		}
		return( $this->dteATBegin ); 
	}
	private function set_astrotwilightends( $arrInfo = null ){ 
		if( is_array( $arrInfo ) ) {
			if( is_numeric( $arrInfo[ASTROTWILIGHTEND] ) ) {
				if( $arrInfo[ASTROTWILIGHTEND] == 1 ) {
					return( $arrInfo[SUNTRANSIT] );
				}
				else {
					return( $arrInfo[ASTROTWILIGHTEND] );
				}
			}
			else {
				return( $this->dteOpposition );
			}			
		}
		return( $this->dteATEnd ); 
	}

// GeoLocation
	public function get_transit(){ return( $this->dteTransit ); }
	public function get_opposition(){ return( $this->dteOpposition ); }
	public function get_sunrise(){ return( $this->dteSunrise ); }
	public function get_sunset(){ return( $this->dteSunset ); }
	public function get_civiltwilightbegins(){ return( $this->dteCTBegin ); }
	public function get_civiltwilightends(){ return( $this->dteCTEnd ); }
	public function get_nauticaltwilightbegins(){ return( $this->dteNTBegin ); }
	public function get_nauticaltwilightends(){ return( $this->dteNTEnd ); }
	public function get_astrotwilightbegins(){ return( $this->dteATBegin ); }
	public function get_astrotwilightends(){ return( $this->dteATEnd ); }
// GeoLocation
	
	public function observatory() { return( $this->strObservatory ); }
	
	public function geoposition(){ return( $this->fltGeoposition ); }
	
	public function map1coordinates(){ return( $this->intMap1Coordinates ); }
	
	public function map2coordinates(){ return( $this->intMap2Coordinates ); }
	
// GeoLocation
	// Public function to return sunrise time in Local Time.  Must be between 00 and 13.
	public function sunrise_hour(){
		if( isset( $this->intSunrise ) ) {
			$intSunrise = $this->intSunrise;
		}
		else {
			// TODO: DELETE LINE $tzLocal = new DateTimeZone( $this->strTimeZone );
			// TODO: DELETE LINE $dteLocal = new DateTime( 'now', $tzLocal );
			// TODO: DELETE LINE $this->fltTimeZoneOffset = $tzLocal->getOffset( $dteLocal )/3600 ;
			$intSunrise = (integer) date('H',(date_sunrise(time(),SUNFUNCS_RET_TIMESTAMP,$this->fltGeoposition[LATITUDE], $this->fltGeoposition[LONGITUDE],90,$this->fltTimeZoneOffset) ) );
			$this->intSunrise = $intSunrise;
		}
		return $intSunrise;
	}
// GeoLocation

	// Public function to return sunset time in Local Time.  Must be over 12.
	public function sunset_hour(){
		if( isset( $this->intSunset ) ) {
			$intSunset = $this->intSunset;
		}
		else {
			// TODO: DELETE LINE $tzLocal = new DateTimeZone( $this->strTimeZone );
			// TODO: DELETE LINE $dteLocal = new DateTime( 'now', $tzLocal );
			// TODO: DELETE LINE $this->fltTimeZoneOffset = $tzLocal->getOffset( $dteLocal )/3600 ;
			$intSunset = (integer) date('H',(date_sunset(time(),SUNFUNCS_RET_TIMESTAMP,$this->fltGeoposition[LATITUDE], $this->fltGeoposition[LONGITUDE],90,$this->fltTimeZoneOffset) ) );
			$this->intSunset = $intSunset < 12 ? $intSunset + 24 : $intSunset;
		}
		return $intSunset;
	}
// GeoLocation
	public function timeoffset() { return( $this->fltTimeZoneOffset ); }
	public function timezone() { return( $this->strTimeZone ); }
	public function map() { return( $this->intMap1Coordinates[ MAPPARAM ] ); }
	
}

class Night {
/*

Night
- hours	- the list of dusk to dawn hours for all possible locations 
	(including Iqaluit, Inuvik, and Fairbanks, so noon to noon)
- moon

Time zones in service area
Newfld	-2.5	-3.5
Atlant	-3	-4
Eastern	-4	-5
Central	-5	-6
Mountn	-6	-7
Pacific	-7	-8
Alaska	-8	-9
Hawaii-Aleutian Islands are off the map
Greenland is off the map
Bermuda, Cuba, Bahamas are mostly off the map.  Check for availability.
Saskatchewan (except Lloydminster) and Arizona (except Navaho) don't use Daylight Saving Time (DST). 
Indiana changed in 2006 to use DST.
*/
	private $arrHours = [];	// list of times
	private $strDate = '';	// the current date in YYYYMMDD## format, 
			// where ## is 00/12 for morning or afternoon Eastern Time
	private $arrMoon = [];	// array for moonrise and moonset. 
			// If Arctic location, 12h to 12h (no rise) or 12h to 36h (no set) local time
	private $strTwelve;	// for determining the correct map, =00 for morning, =12 for afternoon
	
	function __construct( $tz = TIMEZONE ) {
		date_default_timezone_set(TIMEZONE); // Has to be done in Ottawa time zone, because the server updates at noon and midnight Ottawa time
		$this->strTwelve = (date('H')>=12?'12':'00');
		$tzHrTime = new DateTimeZone( $tz );
		$dteHrTime = new DateTime();
		$dteHrTime->setTime( 13,0,0 ); 
		$dteHrTime->setTimezone( $tzHrTime );
		// TODO: DELETE echo 'TZ:'. $tz . ' Twv: ' . $this->strTwelve . chr(13).chr(10);
		for( $intI = 12 -(integer) $this->strTwelve +5; 
				$intI <= 12 -(integer) $this->strTwelve +9 +24;
				$intI++ ) {
			$strHour = substr('000' . (string) $intI, -3);

			// HRTIME is the Human Readable time in HHhMM
			$this->arrHours[ $strHour ][HRTIME] = $dteHrTime->format('H\hi');
			// $this->arrHours[ $strHour ][HRTIME] = substr('0' . (string)( ($intI + floor($tz))%24>12 ? ($intI + floor($tz))%24 - (integer)$this->strTwelve : ($intI + floor($tz))%24 + (integer)$this->strTwelve ), -2) . ($tz-floor($tz)>0.49?'h30':'h00');
			// $strDateTime = (string)((integer)date('Ymd')+floor(($intI + $tz)/24)).substr($this->arrHours[ $strHour ][HRTIME],0,2).substr($this->arrHours[ $strHour ][HRTIME],3,2).'00';
			// $strDateTime = (string)((integer)date('Ymd')+floor(($intI + $tz)/24)).substr($this->arrHours[ $strHour ][HRTIME],0,2).substr($this->arrHours[ $strHour ][HRTIME],3,2).'00';
			// Timestamp has to be interpreted in the local time zone, not Ottawa time.
			/* $intDateTime = mktime((integer)substr($strDateTime,8,2),
									(integer)substr($strDateTime,10,2),
									(integer)substr($strDateTime,12,2),
									(integer)substr($strDateTime,4,2),
									(integer)substr($strDateTime,6,2),
									(integer)substr($strDateTime,0,4));
									*/
			// HRATOM is the HouR in the timestamp format
			$this->arrHours[ $strHour ][HRATOM] = $dteHrTime->getTimestamp();
			// $this->arrHours[ $strHour ][HRATOM] = $intDateTime - $tz*ATOMICHOUR -4*ATOMICHOUR + (  ? 0 : 24*ATOMICHOUR );
			
			$dteHrTime->add( DateInterval::createFromDateString('1 hour') );
		}
		$this->strDate = date('Ymd') . $this->strTwelve;
	}

	// add extra hours, in case of daytime events
	public function add_hour( $intTimeStamp ) {
		// requires that strHour is the ### value (001-048)
		// requires that the timestamp strHour + TZOFFSET (+12)
		$arrHrKeys = array_keys($this->arrHours);
		$arrHrVals = array_values($this->arrHours);
		if( is_numeric( $intTimeStamp ) ) {
			if( $intTimeStamp > time() ) {
				// TODO: DELETE 	echo 'adding '. $intTimeStamp . ' first: '. $arrHrVals[0][HRATOM] . ' last: '. $arrHrVals[ sizeof($arrHrVals)-1 ][HRATOM] .chr(13) . chr(10);
				$dte1stHour = new DateTime();
				$dte1stHour->setTimestamp( $arrHrVals[0][HRATOM] );
				$dteNewHour = new DateTime();
				$dteNewHour->setTimestamp( $intTimeStamp );
				// Calculate UTC equivalent
				$itvDiff = $dte1stHour->diff($dteNewHour);
				$intHour = (integer) $arrHrKeys[0] + $itvDiff->format('%h') + $itvDiff->format('%a') * 24;
				$strHour = substr( '000' . (string)$intHour, -3 );
				// Delete extra minutes and seconds, taking into account timezone.
				$intHrTimestamp = $arrHrVals[ sizeof($arrHrVals)-1 ][HRATOM] + ( $intHour - (integer)$arrHrKeys[ sizeof($arrHrVals)-1 ]) * ATOMICHOUR;
				$dteHrTime = new DateTime();
				$dteHrTime->setTime( (integer)substr( $arrHrVals[ 0 ][HRTIME], 0, 2 ), (integer)substr( $arrHrVals[ 0 ][HRTIME], 3, 2 ) )->add(DateInterval::createFromDateString( (string)( $intHour -(integer)$arrHrKeys[0] ) .' hours') );
				$strHrTime = $dteHrTime->format('Ymd H\hi');
				
				// TODO: DELETE echo 'adding '. $dteHrTime->format( 'Ymd H\hi' ) . ' str: '. $intTimeStamp . ' Hour: '. $intHour .chr(13) . chr(10);
				if( $intHour <= 48 && array_search( $strHour, $arrHrKeys ) === false ) {
					$this->arrHours[ $strHour ] = [ HRTIME => $dteHrTime->format('H\hi'), HRATOM => $intHrTimestamp ];
				}				
			}
		}
		return $this->arrHours;
	}

	// Create hours for noon Iqaluit (05h00Z) to next day noon Fairbanks (09h00Z) 
	public function get_hours() {
		return $this->arrHours;
	}
	
	public function get_date() {
		return $this->strDate;
	}
	
	public function get_twelve() {
		return $this->strTwelve;
	}
	
}

class Weather_Map {
/*
Class for the weather map. Is it available? Is it local, yet?
It takes time to download new images.  Suggest background download.
Canada Weather provides new maps every 12 hours.

The goal is to read the pixel for the observation point, but beyond the scope of this class.

Weather Map
- download state
- url address
- local address
+ get map

*/
	private $strDownload;
	private $strURL;
	private $strLocalAddress;
	private $imgMap;
	private $strObservatory;
	protected $arrFiles;

	function __construct( $obs = null, $date = null, $hour = null, $map = null ) {
		$strPathURL = 'http://weather.gc.ca/data/prog/regional/';
		$strSuffixLocal = '.png';
		$strSuffixURL = '.png?1';
		// TODO: DELETE $arrFiles = [];
		if( is_null( $obs ) || is_null( $date ) || is_null( $hour ) || is_null( $map ) ) {
			// not enough data to find weather map. make it blank.
			$this->imgMap = imagecreate( 10, 10 );
			$this->strURL = 'N/A';
			$this->strLocalAddress = 'N/A';
			$this->strDownload = 'N/A';
			$this->strObservatory = '';
		// TODO: echo 'missing valid Weather Map parameter';
			return;
		}
		else {
		/*
			switch( $obs ) {
			case 'Fred':
			case 'Mississauga':
			case 'Victoria':
			case 'Winnipeg':
			case 'Churchill':
			case 'St. John\'s':
			case 'Thunder Bay':
			case 'Orono':
			case 'Torrance Barrens':
			case 'Iqaluit':
			case 'Fairbanks':
			case 'Colbeck':
				// Known observatory
				$this->strObservatory = $obs;
				break;
			default: 

				// Unknown observatory. No map for you
				$this->imgMap = imagecreate( 10, 10 );
				$this->strURL = 'N/A';
				$this->strLocalAddress = 'N/A';
				$this->strDownload = 'N/A';
				$this->strObservatory = '';
		// TODO: echo 'missing valid Weather Map observatory';
				return;
			}
			*/
			if( is_numeric( $date ) && is_numeric( $hour ) ) {
				switch( $map ) {
				case NECLOUDCOVER:
					$strMap = '_054_R1_north@america@northeast_I_ASTRO_nt_';
					break;
				case NWCLOUDCOVER:
					$strMap = '_054_R1_north@america@northwest_I_ASTRO_nt_';
					break;
				case SECLOUDCOVER:
					$strMap = '_054_R1_north@america@southeast_I_ASTRO_nt_';
					break;
				case SWCLOUDCOVER:
					$strMap = '_054_R1_north@america@southwest_I_ASTRO_nt_';
					break;
				case TRNORTHAMERICA:
					$strMap = '_054_R1_north@america@astro_I_ASTRO_transp_';
					break;
				case SENORTHAMERICA:
					$strMap = '_054_R1_north@america@astro_I_ASTRO_seeing_';
					$hour = substr( '000' . (string) (floor(((integer) $hour +1)/3)*3), -3); 
					break;
				case UVNORTHAMERICA:
					$strMap = '_054_R1_north@america@astro_I_ASTRO_uv_';
					break;
				case HRNORTHAMERICA:
					$strMap = '_054_R1_north@america@astro_I_ASTRO_hr_';
					break;
				case TTNORTHAMERICA:
					$strMap = '_054_R1_north@america@astro_I_ASTRO_tt_';
					break;
				default: 
					// Unknown map request. No map for you
					$this->imgMap = imagecreate( 10, 10 );
					$this->strURL = 'N/A';
					$this->strLocalAddress = 'N/A';
					$this->strDownload = 'N/A';
					$this->strObservatory = '';
		// TODO: echo 'missing valid Weather Map map';
					return;
				}
				// TODO: Find weather maps from other agency sites
				$this->strLocalAddress = DATADIR . '/' . $date . $strMap . $hour . $strSuffixLocal;
				$strLocalAddress = $date . $strMap . $hour . $strSuffixLocal;
				$this->strURL = $strPathURL . $date . '/' . $date . $strMap . $hour . $strSuffixURL;

				// We have the file name. Check if we have the file, or go get it.
				if( sizeof($this->arrFiles) == 0 ) { $this->arrFiles = scandir( DATADIR ); }
				if( sizeof( array_intersect( $this->arrFiles, [ $strLocalAddress ] ) ) > 0 ) {
					$imgMap = imagecreatefrompng($this->strLocalAddress);
				}
				else {
					// TODO: get the file in the background
					$imgMap = imagecreatefrompng($this->strURL);
					if( $imgMap ) {
						imagepng( $imgMap, $this->strLocalAddress );
						$this->arrFiles[] = $strLocalAddress;
					}
				}
				return; // all done

			}
		}
	}

	public function image() {
		return $this->imgMap;
	}

	public function local() {
		return $this->strLocalAddress;
	}

	public function url() {
		return $this->strURL;
	}

	public function files() {
		return $this->arrFiles;
	}
	
	public function flushImage() {
		if( $this->imgMap ) { imagedestroy( $this->imgMap ); }
		return;
	}

}

class Weather_Analysis {
/*
Weather Analysis
- colour
- text
- rating
*/

	private $strColour = '#000000';
	private $strText = '';
	private $intRating = 0;
	private $arrColourReference = [];

	function __construct( $strMap = null, $pixelX = null, $pixelY = null, $map = null ) {
		if( is_null( $strMap ) || is_null( $pixelX ) || is_null( $pixelY ) || is_null( $map ) ) {
		// TODO: echo 'missing valid Weather Analysis parameter'."\n".'$imgMap:'.$strMap."\n".'$pixelX:'.$pixelX."\n".'$pixelY:'.$pixelY."\n".'$map:'.$map."\n";
			return;
		}
		switch( $map ){
		case NECLOUDCOVER:
		case NWCLOUDCOVER:
		case SECLOUDCOVER:
		case SWCLOUDCOVER:
			$this->arrColourReference = 	
				[
				[ CRCOLOUR => '#FF0000', CRTEXT => 'border', CRRATING => -1 ],
				[ CRCOLOUR => '#FBFBFB', CRTEXT => 'Overcast', CRRATING => 0 ],
				[ CRCOLOUR => '#EAEAEA', CRTEXT => '90% covered', CRRATING => 1 ],
				[ CRCOLOUR => '#C2C2C2', CRTEXT => '80% covered', CRRATING => 2 ],
				[ CRCOLOUR => '#AEEEEE', CRTEXT => '70% covered', CRRATING => 3 ],
				[ CRCOLOUR => '#9ADADA', CRTEXT => '60% covered', CRRATING => 4 ],
				[ CRCOLOUR => '#77B7F7', CRTEXT => '50% covered', CRRATING => 5 ],
				[ CRCOLOUR => '#63A3E3', CRTEXT => '40% covered', CRRATING => 6 ],
				[ CRCOLOUR => '#4F8FCF', CRTEXT => '30% covered', CRRATING => 7 ],
				[ CRCOLOUR => '#2767A7', CRTEXT => '20% covered' , CRRATING => 8],
				[ CRCOLOUR => '#135393', CRTEXT => '10% covered', CRRATING => 9 ],
				[ CRCOLOUR => '#003F7F', CRTEXT => 'Clear', CRRATING => 10 ]
				];
			break;
		case TRNORTHAMERICA:
			$this->arrColourReference = 	
				[
				[ CRCOLOUR => '#FF0000', CRTEXT => 'border', CRRATING => -1 ],
				[ CRCOLOUR => '#F9F9F9', CRTEXT => 'Too cloudy to forecast', CRRATING => 0 ],
				[ CRCOLOUR => '#C7C7C7', CRTEXT => 'Poor', CRRATING => 1 ],
				[ CRCOLOUR => '#95D5D5', CRTEXT => 'Below Average', CRRATING => 2 ],
				[ CRCOLOUR => '#63A3E3', CRTEXT => 'Average', CRRATING => 3 ],
				[ CRCOLOUR => '#2C6CAC', CRTEXT => 'Above average', CRRATING => 4 ],
				[ CRCOLOUR => '#003F7F', CRTEXT => 'Transparent', CRRATING => 5 ]
				];
			break;
		case SENORTHAMERICA:
			$this->arrColourReference = 	
				[
				[ CRCOLOUR => '#000000', CRTEXT => 'border', CRRATING => -1 ],
				[ CRCOLOUR => '#F9F9F9', CRTEXT => 'Too cloudy to forecast', CRRATING => 0 ],
				[ CRCOLOUR => '#C7C7C7', CRTEXT => 'Bad 1/5', CRRATING => 1 ],
				[ CRCOLOUR => '#95D5D5', CRTEXT => 'Poor 2/5', CRRATING => 2 ],
				[ CRCOLOUR => '#63A3E3', CRTEXT => 'Average 3/5', CRRATING => 3 ],
				[ CRCOLOUR => '#2C6CAC', CRTEXT => 'Good 4/5', CRRATING => 4 ],
				[ CRCOLOUR => '#003F7F', CRTEXT => 'Excellent 5/5', CRRATING => 5 ]
				];
			break;
		case UVNORTHAMERICA:
			$this->arrColourReference = 	
				[
				[ CRCOLOUR => '#000000', CRTEXT => 'border', CRRATING => -1 ],
				[ CRCOLOUR => '#F9F9F9', CRTEXT => '&gt;72 km/hr', CRRATING => 0 ],
				[ CRCOLOUR => '#C7C7C7', CRTEXT => '46 to 72 km/hr', CRRATING => 1 ],
				[ CRCOLOUR => '#95D5D5', CRTEXT => '28 to 45 km/hr', CRRATING => 2 ],
				[ CRCOLOUR => '#63A3E3', CRTEXT => '19 to 27 km/hr', CRRATING => 3 ],
				[ CRCOLOUR => '#2C6CAC', CRTEXT => '9 to 18 km/hr', CRRATING => 4 ],
				[ CRCOLOUR => '#003F7F', CRTEXT => '0 to 8 km/hr', CRRATING => 5 ]
				];
			break;
		case HRNORTHAMERICA:
			$this->arrColourReference = 	
				[
				[ CRCOLOUR => '#000000', CRTEXT => 'border', CRRATING => -1 ],
				[ CRCOLOUR => '#08035D', CRTEXT => '&lt;25%', CRRATING => 15 ],
				[ CRCOLOUR => '#0D4D8D', CRTEXT => '25% to 30%', CRRATING => 14 ],
				[ CRCOLOUR => '#3070B0', CRTEXT => '30% to 35%', CRRATING => 13 ],
				[ CRCOLOUR => '#4E8ECE', CRTEXT => '35% to 40%', CRRATING => 12 ],
				[ CRCOLOUR => '#71B1F1', CRTEXT => '40% to 45%', CRRATING => 11 ],
				[ CRCOLOUR => '#80C0C0', CRTEXT => '45% to 50%', CRRATING => 10 ],
				[ CRCOLOUR => '#09FEED', CRTEXT => '50% to 55%', CRRATING => 9 ],
				[ CRCOLOUR => '#55FAAD', CRTEXT => '55% to 60%', CRRATING => 8 ],
				[ CRCOLOUR => '#94FE6A', CRTEXT => '60% to 65%', CRRATING => 7 ],
				[ CRCOLOUR => '#EAFB16', CRTEXT => '65% to 70%', CRRATING => 6 ],
				[ CRCOLOUR => '#FEC600', CRTEXT => '70% to 75%', CRRATING => 5 ],
				[ CRCOLOUR => '#FC8602', CRTEXT => '75% to 80%', CRRATING => 4 ],
				[ CRCOLOUR => '#FE3401', CRTEXT => '80% to 85%', CRRATING => 3 ],
				[ CRCOLOUR => '#EA0000', CRTEXT => '85% to 90%', CRRATING => 2 ],
				[ CRCOLOUR => '#B70000', CRTEXT => '90% to 95%', CRRATING => 1 ],
				[ CRCOLOUR => '#E10000', CRTEXT => '95% to 100%', CRRATING => 0 ] 
				];
			break;
		case TTNORTHAMERICA:
			$this->arrColourReference = 	
				[
				[ CRCOLOUR => '#000000', CRTEXT => 'border', CRRATING => -1 ],
				[ CRCOLOUR => '#FC00FC', CRTEXT => '&lt; -40C', CRRATING => -40 ],
				[ CRCOLOUR => '#000085', CRTEXT => '-40C to -35C', CRRATING => -35 ],
				[ CRCOLOUR => '#0000B2', CRTEXT => '-35C to -30C', CRRATING => -30 ],
				[ CRCOLOUR => '#0000EC', CRTEXT => '-30C to -25C', CRRATING => -25 ],
				[ CRCOLOUR => '#0034FE', CRTEXT => '-25C to -20C', CRRATING => -20 ],
				[ CRCOLOUR => '#0089FE', CRTEXT => '-20C to -15C', CRRATING => -15 ],
				[ CRCOLOUR => '#00D4FE', CRTEXT => '-15C to -10C', CRRATING => -10 ],
				[ CRCOLOUR => '#1EFEDE', CRTEXT => '-10C to -5C', CRRATING => -5 ],
				[ CRCOLOUR => '#FBFBFB', CRTEXT => '-5C to 0C', CRRATING => 0 ],
				[ CRCOLOUR => '#5EFE9E', CRTEXT => '0C to 5C', CRRATING => 5 ],
				[ CRCOLOUR => '#A2FE5A', CRTEXT => '5C to 10C', CRRATING => 10 ],
				[ CRCOLOUR => '#FEDE00', CRTEXT => '10C to 15C', CRRATING => 15 ],
				[ CRCOLOUR => '#FE9E00', CRTEXT => '15C to 20C', CRRATING => 20 ],
				[ CRCOLOUR => '#FE5A00', CRTEXT => '20C to 25C', CRRATING => 25 ],
				[ CRCOLOUR => '#FE1E00', CRTEXT => '25C to 30C', CRRATING => 30 ],
				[ CRCOLOUR => '#E20000', CRTEXT => '30C to 35C', CRRATING => 35 ],
				[ CRCOLOUR => '#A90000', CRTEXT => '35C to 40C', CRRATING => 40 ],
				[ CRCOLOUR => '#7E0000', CRTEXT => '40C to 45C', CRRATING => 45 ],
				[ CRCOLOUR => '#C6C6C6', CRTEXT => '&gt; 45C', CRRATING => 50 ] 
				];
			break;
		default: 
			// Unknown map request. No map for you
		// TODO: echo 'missing valid Weather Analysis map';
			return;
		}
		$imgColourReference = imagecreate( 10, 10 );
		$imgMap = imagecreatefrompng($strMap);
		foreach( $this->arrColourReference AS $colref ) {
			imagecolorallocate( $imgColourReference, hexdec( substr( $colref[ CRCOLOUR ], 1, 2 ) ), hexdec( substr( $colref[ CRCOLOUR ], 3, 2 )), hexdec( substr( $colref[ CRCOLOUR ], 5, 2 )) ); 
		}
		$rgb = imagecolorsforindex($imgMap, imagecolorat($imgMap, $pixelX, $pixelY));
		$index = imagecolorclosest( $imgColourReference, $rgb['red'], $rgb['green'], $rgb['blue'] );
		$this->strColour = $this->arrColourReference[ $index ][ CRCOLOUR ];
		$this->strText = $this->arrColourReference[ $index ][ CRTEXT ];
		$this->intRating = $this->arrColourReference[ $index ][ CRRATING ];
		imagedestroy( $imgColourReference );
		imagedestroy( $imgMap );
		return ;
	}
	
	public function get_colour(){ return( $this->strColour ); }
	
	public function get_text(){ return( $this->strText ); }
	
	public function get_rating(){ return( $this->intRating ); }
	
}

// TODO: Create class for objects in the sky. Returns right ascension, declination, magnitude. Functions for altitude, azimuth, rise, set.
class CelestialObject {
	private $strObjectName;
	private $fltRightAscension;
	private $fltDeclination;
	private $fltMagnitude;

	function __construct( $strName, $fltRA, $fltDecl, $fltMag ) {
		if( is_string( $strName ) ) $this->strObjectName = $strName;
		if( is_numeric( $fltRA ) ) $this->fltRightAscension = $fltRA;
		if( is_numeric( $fltDecl ) ) $this->fltDeclination = $fltDecl;
		if( is_numeric( $fltMag ) ) $this->fltMagnitude = $fltMag;
	}
	
	public function name() { return $this->strObjectName; }
	public function ra() { return $this->fltRightAscension; }
	public function dec() { return $this->fltDeclination; }
	public function mag() { return $this->fltMagnitude; }
}

// TODO: Create class for objects that move in the sky
class Planet extends CelestialObject {
	function __construct( $strName, $dteTime, $arrEphemeris, $loc ) {
	}
}

class Moon extends Planet {
	private $strObjectName;
	private $fltRightAscension;
	private $fltDeclination;
	private $fltMagnitude;
	private $fltDistance;
	private $dteRise;
	private $dteSet;
	private $arrIllumination;
	
	function __construct( $dteTime, $loc ) {
		$this->strObjectName = 'Moon';
		$dteOpposition = new DateTime();
		$dteOpposition->setTimestamp( $loc->get_opposition() );
		$dteTransit = new DateTime();
		$dteTransit->setTimestamp( $loc->get_transit() );
		$mooncalc = new SunCalc( $dteTime, $loc->geoposition()[ LATITUDE ], $loc->geoposition()[ LONGITUDE ] );
		$this->dteRise = ( isset( $mooncalc->getMoonTimes()['alwaysUp'] ) ? $dteTransit :
					 ( isset( $mooncalc->getMoonTimes()['moonrise'] ) ? $mooncalc->getMoonTimes()['moonrise'] : 
					  $dteOpposition ) );
		$this->dteSet = ( isset( $mooncalc->getMoonTimes()['alwaysUp'] ) ? $dteTransit :
					 ( isset( $mooncalc->getMoonTimes()['moonset'] ) ? $mooncalc->getMoonTimes()['moonset'] : 
					  $dteOpposition ) );
		$this->arrIllumination = $mooncalc->getMoonIllumination();
		$arrNames = array( 'New Moon', 'Waxing Crescent', 'First Quarter', 'Waxing Gibbous', 'Full Moon', 'Waning Gibbous', 'Third Quarter', 'Waning Crescent', 'New Moon' );
		// There are eight phases, evenly split. A "New Moon" occupies the 1/16th phases either side of phase = 0, and the rest follow from that.
		$this->arrIllumination['name'] = $arrNames[ floor( ( $this->arrIllumination['phase'] + 0.0625 ) * 8 ) ];

		$this->fltDistance = moonCoords( toDays($dteTime) )->dist;
		$this->fltRightAscension = ( moonCoords( toDays($dteTime) )->ra < 0 ? 2 * M_PI : 0.0 ) + moonCoords( toDays($dteTime) )->ra;
		$this->fltDeclination = moonCoords( toDays($dteTime) )->dec;
		$this->fltMagnitude = -13.0 ;
	}
	
	public function dist() { return $this->fltDistance; }
	public function rise() { return $this->dteRise; }
	public function set() { return $this->dteSet; }
	public function illumination() { return $this->arrIllumination; }
	public function name() { return $this->strObjectName; }
	public function ra() { return $this->fltRightAscension; }
	public function dec() { return $this->fltDeclination; }
	public function mag() { return $this->fltMagnitude; }
}

// Delete files collected from previous nights. Do me first.
function garbage_collection( $dteDateToKeep, $strMapType ) {

	// TODO: Lookup and delete line. const DATELENGTH = 10;
	$strMap = '';
	
	// Recalculate the date string also used in class Weather_Map 
	$strDateToKeep = $dteDateToKeep->format('Ymd') . ((integer)$dteDateToKeep->format('H')<12?'00':'12');
	
	// check if there is any garbage to collect
	$arrFiles = scandir( DATADIR );
	if( sizeof($arrFiles) == 0 ) { return; }		
	
	if( is_numeric( $strDateToKeep ) && strlen( $strDateToKeep ) == DATELENGTH ) {
		switch( $strMapType ) {
		case NECLOUDCOVER:
			$strMap = '_054_R1_north@america@northeast_I_ASTRO_nt_';
			break;
		case NWCLOUDCOVER:
			$strMap = '_054_R1_north@america@northwest_I_ASTRO_nt_';
			break;
		case SECLOUDCOVER:
			$strMap = '_054_R1_north@america@southeast_I_ASTRO_nt_';
			break;
		case SWCLOUDCOVER:
			$strMap = '_054_R1_north@america@southwest_I_ASTRO_nt_';
			break;
		case TRNORTHAMERICA:
			$strMap = '_054_R1_north@america@astro_I_ASTRO_transp_';
			break;
		case SENORTHAMERICA:
			$strMap = '_054_R1_north@america@astro_I_ASTRO_seeing_';
			break;
		case UVNORTHAMERICA:
			$strMap = '_054_R1_north@america@astro_I_ASTRO_uv_';
			break;
		case HRNORTHAMERICA:
			$strMap = '_054_R1_north@america@astro_I_ASTRO_hr_';
			break;
		case TTNORTHAMERICA:
			$strMap = '_054_R1_north@america@astro_I_ASTRO_tt_';
			break;
		default: 
			// Unknown map request. Don't delete me.
// TODO: echo 'missing valid Weather Map map';
			return;
		}
		
		foreach( $arrFiles AS $strLocalAddress ) {
			if( strpos( $strLocalAddress, $strMap ) > 1 && strpos( $strLocalAddress, $strDateToKeep ) === false ) {
				// A candidate is found. Double check and delete.
				if( is_numeric( substr( $strLocalAddress, 0, DATELENGTH ) ) ) {
					unlink( DATADIR . '/' . $strLocalAddress );
				}
			}
		}
	}
	return;	
}

// TODO: Use XML object to build this XML code.
header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>' . chr(13) . chr(10);   
echo '<?xml-stylesheet type="text/css" href="astroforecastCSS.css"?>' . chr(13) . chr(10);
/*
XML structure
+ astroforecast
|
+-+ observatory
| +-+ observatory
|   + latitude
|   + longitude
|   + x-map1-coordinate
|   + y-map1-coordinate
|   + x-map2-coordinate
|   + y-map2-coordinate
|   + timezone
|   + sunrise
|   + sunset
|   + moonrise
|   + moonset
|   + twilightbegin
|   + twilightend
|
+-+ night
| +-+ date
|   + hour
|   + analysis  
|   +-+ evening
|     + night 
|     + morning
|
+-+ time
| +-- clock
| +-+ map
| | +-+ local
| |   + remote 
| |
| +-+ weather
| | +-+ colour
| |   + text 
| |   + rating
| |
| +-+ viewing
|   +-+ clock
|     + skies
|
+-+ events
| +-+ event 
|   +-+ date
|     + hour
|     + right ascension
|     + declination
|     + azimuth
|     + altitude
|     + event name
|     + event description
*/
echo '<astroforecast>' . chr(13) . chr(10);

/** GET parameters:
 *    obs: the observatory to be used for this location
 *    ts: the timestamp for an event to be checked out
 *    hr: the 24h time for an event to be checked out
 *    verbose: show your work
 **/ 
if( isset( $_GET['latlng'] ) ) {
	if( is_string( $_GET['latlng'] ) ) {
		if( strpos( $_GET['latlng'], ',' ) > 0 ) {
			$fltLat = (float) substr( $_GET['latlng'], 0, strpos( $_GET['latlng'], ',' ) );
			$fltLng = (float) substr( $_GET['latlng'], strpos( $_GET['latlng'], ',' ) +1 );
			if( isset( $_GET['obs'] ) ) {
				$location = new GeoLocation([LATITUDE=>$fltLat,LONGITUDE=>$fltLng],$_GET['obs']);
			}
			else {
				$location = new GeoLocation([LATITUDE=>$fltLat,LONGITUDE=>$fltLng]);
			}
		}
 	}
	else {
		$location = new Location();
	}
}	
elseif( isset( $_GET['obs'] ) ) {
	$location = new Location($_GET['obs']);
}
else {
	$location = new Location();
}
$blnVerbose = false;
if( isset( $_GET['verbose'] ) ) $blnVerbose = true;
$hrEvent = [];
$tzEvent = new DateTimeZone( $location->timezone() ); 
if( isset( $_GET['ts'] ) ) {
	// Qualify that the ts is a timestamp
	if( is_numeric( $_GET['ts'] ) ) {
		if( (integer) $_GET['ts'] > time() ) {
			$dteEvent = new DateTime; 
			$dteEvent->setTimestamp( $_GET['ts'] ); 
			$dteEvent->setTimezone( $tzEvent );
			$hrEvent[] = [ HRATOM => $dteEvent->getTimestamp(), HRTIME => $dteEvent->format('H\hi') ];
		}
	}
	elseif( is_array( $_GET['ts'] ) ) {
		foreach( $_GET['ts'] AS $tsVal ) {
			if( is_numeric( $tsVal ) ) {
				if( (integer)$tsVal > time() ) {
					$dteEvent = new DateTime; 
					$dteEvent->setTimestamp( $tsVal ); 
					$dteEvent->setTimezone( $tzEvent );
					$hrEvent[] = [ HRATOM => $dteEvent->getTimestamp(), HRTIME => $dteEvent->format('H\hi') ];
				}
			}
		}
	}
}
if( isset( $_GET['hr'] ) ) {
	// Qualify that the hr is a valid 24h time string
	// Allow ##h, ##h##, ##h##m, ##h##m##, ##h##m##s
	$dteEvent = new DateTime; 
	$dteEvent->setTimezone( $tzEvent );
	if( is_string( $_GET['hr'] ) ) {
		$intHour = 0;
		$intMinute = 0;
		$intSecond = 0;
		switch( strlen( $_GET['hr'] ) ) {
		case 9:
		case 8:
			$intSecond = (integer) substr( $_GET['hr'],6,2 );
		case 6:
		case 5:
			$intMinute = (integer) substr( $_GET['hr'],3,2 );
		case 3:
			$intHour = (integer) substr( $_GET['hr'],0,2 );
			if( $intHour >= 0 && $intHour <= 23 && $intMinute >= 0 && $intMinute <= 59 && $intSecond >= 0 && $intSecond <= 59 ) {
				$dteEvent->setTime( $intHour, $intMinute, $intSecond );
				if( $dteEvent->getTimestamp() < time() ) {
					// if the event is in the past, add a day.
					$dteEvent->add(new DateInterval('P1D'));
				}
				$hrEvent[] = [ HRATOM => $dteEvent->getTimestamp(), HRTIME => $dteEvent->format('H\hi') ];
			}
			break;
		default:
			break;
		}
	}
	elseif( is_array( $_GET['hr'] ) ) {
		foreach( $_GET['hr'] AS $hrVal ) {
			if( is_string( $hrVal ) ) {
				$intHour = 0;
				$intMinute = 0;
				$intSecond = 0;
				switch( strlen( $hrVal ) ) {
				case 9:
				case 8:
					$intSecond = (integer) substr( $hrVal,6,2 );
				case 6:
				case 5:
					$intMinute = (integer) substr( $hrVal,3,2 );
				case 3:
					$intHour = (integer) substr( $hrVal,0,2 );
					if( $intHour >= 0 && $intHour <= 23 && $intMinute >= 0 && $intMinute <= 59 && $intSecond >= 0 && $intSecond <= 59 ) {
						$dteEvent->setTime( $intHour, $intMinute, $intSecond );
						if( $dteEvent->getTimestamp() < time() ) {
							// if the event is in the past, add a day.
							$dteEvent->add( DateInterval::createFromDateString('1 day') );
						}
						$hrEvent[] = [ HRATOM => $dteEvent->getTimestamp(), HRTIME => $dteEvent->format('H\hi') ];
					}
					break;
				default:
					break;
				}
			}
		}
	}
}

// convert timestamps to DateTime object to display in observatory timezone, for to be sure
$dteDisplayTime = new DateTime();
$dteDisplayTime->setTimezone( $tzEvent );

echo ' <observatory>' . chr(13) . chr(10);
echo '  <observatory>'. $location->observatory() .'</observatory>' . chr(13) . chr(10);
echo '  <latitude>';
echo $location->geoposition()[ LATITUDE ];
echo '</latitude>' . chr(13) . chr(10);
echo '  <longitude>';
echo $location->geoposition()[ LONGITUDE ];
echo '</longitude>' . chr(13) . chr(10);
	
if( $blnVerbose ) {
echo '  <x-map1-coordinate>';
echo $location->map1coordinates()[ XCLOUDCOVER ];
echo '</x-map1-coordinate>' . chr(13) . chr(10);
echo '  <y-map1-coordinate>';
echo $location->map1coordinates()[ YCLOUDCOVER ];
echo '</y-map1-coordinate>' . chr(13) . chr(10);
	
echo '  <x-map2-coordinate>';
echo $location->map2coordinates()[ XNORTHAMERICA ];
echo '</x-map2-coordinate>' . chr(13) . chr(10);
echo '  <y-map2-coordinate>';
echo $location->map2coordinates()[ YNORTHAMERICA ];
echo '</y-map2-coordinate>' . chr(13) . chr(10);
	
echo '  <timezone>'. $location->timeoffset() .'</timezone>' . chr(13) . chr(10);
echo '  <sunrise>'. $location->sunrise_hour() .'</sunrise>' . chr(13) . chr(10);	
echo '  <sunset>'. $location->sunset_hour() .'</sunset>' . chr(13) . chr(10);
}	

if( $blnVerbose || isset($_GET['sun']) ) {
echo '  <suntimes>' . chr(13) . chr(10);
echo '   <comment>Sun times</comment>' . chr(13) . chr(10);
echo '   <transit atomic="'.$location->get_transit().'">'. $dteDisplayTime->setTimestamp( $location->get_transit() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</transit>' . chr(13) . chr(10);
echo '   <sunset atomic="'.$location->get_sunset().'">'. $dteDisplayTime->setTimestamp( $location->get_sunset() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' )  .'</sunset>' . chr(13) . chr(10);
echo '   <civiltwilightends atomic="'.$location->get_civiltwilightends().'">'. $dteDisplayTime->setTimestamp( $location->get_civiltwilightends() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' )  .'</civiltwilightends>' . chr(13) . chr(10);
echo '   <nauticaltwilightends atomic="'.$location->get_nauticaltwilightends().'">'. $dteDisplayTime->setTimestamp( $location->get_nauticaltwilightends() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' )  .'</nauticaltwilightends>' . chr(13) . chr(10);
echo '   <astrotwilightends atomic="'.$location->get_astrotwilightends().'">'. $dteDisplayTime->setTimestamp( $location->get_astrotwilightends() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' )  .'</astrotwilightends>' . chr(13) . chr(10);
echo '   <opposition atomic="'.$location->get_opposition().'">'. $dteDisplayTime->setTimestamp( $location->get_opposition() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' )  .'</opposition>' . chr(13) . chr(10);
echo '   <astrotwilightbegins atomic="'.$location->get_astrotwilightbegins().'">'. $dteDisplayTime->setTimestamp( $location->get_astrotwilightbegins() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' )  .'</astrotwilightbegins>' . chr(13) . chr(10);
echo '   <nauticaltwilightbegins atomic="'.$location->get_nauticaltwilightbegins().'">'. $dteDisplayTime->setTimestamp( $location->get_nauticaltwilightbegins() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' )  .'</nauticaltwilightbegins>' . chr(13) . chr(10);
echo '   <civiltwilightbegins atomic="'.$location->get_civiltwilightbegins().'">'. $dteDisplayTime->setTimestamp( $location->get_civiltwilightbegins() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' )  .'</civiltwilightbegins>' . chr(13) . chr(10);
echo '   <sunrise atomic="'.$location->get_sunrise().'">'. $dteDisplayTime->setTimestamp( $location->get_sunrise() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' )  .'</sunrise>' . chr(13) . chr(10);
echo '  </suntimes>' . chr(13) . chr(10);
}	

//	+ moonrise
//	+ moonset
// TODO: DELETE LINE $moonphase = new Solaris\MoonPhase( $location->get_opposition() );
// TODO: DELETE LINE $moonriset = new MoonRiSet();
// TODO: DELETE LINE $dteOpposition = new DateTime();
// TODO: DELETE LINE $dteOpposition->setTimestamp( $location->get_opposition() );
// TODO: DELETE LINE $dteTransit = new DateTime();
// TODO: DELETE LINE $dteTransit->setTimestamp( $location->get_transit() );
// TODO: DELETE LINE $mooncalc = new SunCalc( $dteTransit, $location->geoposition()[ LATITUDE ], $location->geoposition()[ LONGITUDE ] );
// TODO: DELETE LINE $moonrise = $moonriset->calculateMoonTimes(date('m', $moonphase->phase()>.25?$location->get_sunset():$location->get_sunrise() ), date('d', $moonphase->phase()>.25?$location->get_sunset():$location->get_sunrise() ), date('Y', $moonphase->phase()>.25?$location->get_sunset():$location->get_sunrise() ), $location->geoposition()[ LATITUDE ], $location->geoposition()[ LONGITUDE ])->moonrise;
// TODO: DELETE LINE $moonrise = ( isset( $mooncalc->getMoonTimes()['alwaysUp'] ) ? $dteTransit :
// TODO: DELETE LINE 			 ( isset( $mooncalc->getMoonTimes()['alwaysDown'] ) ? $dteOpposition : 
// TODO: DELETE LINE 			  $mooncalc->getMoonTimes()['moonrise'] ) );
// TODO: DELETE LINE $moonset = $moonriset->calculateMoonTimes(date('m', $moonphase->phase()<=.25?$location->get_sunset():$location->get_sunrise() ), date('d', $moonphase->phase()<=.25?$location->get_sunset():$location->get_sunrise() ), date('Y', $moonphase->phase()<=.25?$location->get_sunset():$location->get_sunrise() ), $location->geoposition()[ LATITUDE ], $location->geoposition()[ LONGITUDE ])->moonset;
// TODO: DELETE LINE $moonset = ( isset( $mooncalc->getMoonTimes()['alwaysUp'] ) ? $dteTransit :
// TODO: DELETE LINE 			 ( isset( $mooncalc->getMoonTimes()['alwaysDown'] ) ? $dteOpposition : 
// TODO: DELETE LINE 			  $mooncalc->getMoonTimes()['moonset'] ) );
$dteTransit = new DateTime();
$dteTransit->setTimestamp( $location->get_transit() );
$dteOpposition = new DateTime();
$dteOpposition->setTimestamp( $location->get_opposition() );
$objMoon = new Moon( $dteTransit, $location );
if( $objMoon->rise() < $objMoon->set() ) {
	$objMoonRise = new Moon( $objMoon->rise(), $location );
	$objMoonSet = new Moon( $objMoon->set(), $location );
}
else {
	$objMoonRise = new Moon( $dteTransit, $location );
	$objMoonSet = new Moon( $dteOpposition, $location );
}
// TODO: DELETE echo 'Sun transit:'. $dteTransit->format('H\hi') .' moon rise:'.  $objMoon->rise()->format('H\hi')  .' moon set:'.  $objMoon->set()->format('H\hi') ;
if( $blnVerbose || isset($_GET['moon']) ) {
echo '  <moontimes>' . chr(13) . chr(10);
echo '   <comment>Moon times</comment>' . chr(13) . chr(10);
echo '   <moonrise atomic="'.$objMoon->rise()->getTimestamp().'">'. $objMoon->rise()->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</moonrise>' . chr(13) . chr(10);
if( $blnVerbose ) {
echo '   <ra radian="'. $objMoonRise->ra() .'">'. ( $objMoonRise->ra() * 12.0 / M_PI) .'</ra>' . chr(13) . chr(10);
echo '   <dec radian="'. $objMoonRise->dec() .'">'. ( $objMoonRise->dec() * 180.0 / M_PI) .'</dec>' . chr(13) . chr(10);
echo '   <phase>'. $objMoon->illumination()['phase'] .'</phase>' . chr(13) . chr(10);
echo '   <illum>'. $objMoon->illumination()['fraction'] .'</illum>' . chr(13) . chr(10);
}
echo '   <phasename>'. $objMoon->illumination()['name'] .'</phasename>' . chr(13) . chr(10);
if( $blnVerbose ) {
echo '   <ra radian="'. $objMoon->ra() .'">'. ( $objMoon->ra() * 12.0 / M_PI) .'</ra>' . chr(13) . chr(10);
echo '   <dec radian="'. $objMoon->dec() .'">'. ( $objMoon->dec() * 180.0 / M_PI) .'</dec>' . chr(13) . chr(10);
}
echo '   <moonset atomic="'.$objMoonSet->set()->getTimestamp().'">'. $objMoonSet->set()->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</moonset>' . chr(13) . chr(10);
if( $blnVerbose ) {
echo '   <ra radian="'. $objMoonSet->ra() .'">'. ( $objMoonSet->ra() * 12.0 / M_PI) .'</ra>' . chr(13) . chr(10);
echo '   <dec radian="'. $objMoonSet->dec() .'">'. ( $objMoonSet->dec() * 180.0 / M_PI) .'</dec>' . chr(13) . chr(10);
}
echo '  </moontimes>' . chr(13) . chr(10);
}


echo ' </observatory>' . chr(13) . chr(10);

$tzServer = new DateTimeZone( TIMEZONE );
$dteTrashMe = new DateTime();
$dteTrashMe->setTimezone( $tzServer );
foreach( [ NECLOUDCOVER, NWCLOUDCOVER, SECLOUDCOVER, SWCLOUDCOVER, TRNORTHAMERICA, SENORTHAMERICA, UVNORTHAMERICA, HRNORTHAMERICA, TTNORTHAMERICA ] AS $strMapType ) {
	garbage_collection( $dteTrashMe, $strMapType );
}
// Night Class grabs the third party images. Run garbage collection BEFORE this point
$night = new Night($location->timezone());
$strFolder = $night->get_date();
$arrHours = $night->get_hours();
foreach( $hrEvent AS $evtKey => $evtVal ) { 
	$arrHours = $night->add_hour( $evtVal[HRATOM] ); 
}
$hrEven = [ HRATOM => $location->get_opposition(), HRTIME => TIMEEVENING ];
$hrMorn = [ HRATOM => $location->get_opposition(), HRTIME => TIMEMORN ];
if( $blnVerbose ) {
echo ' <night>' . chr(13) . chr(10);
echo '  <date>' . $strFolder . '</date>' . chr(13) . chr(10);
foreach( $arrHours AS $key => $val ) {
	echo '  <hour img="'. $key .'">'. $val[HRTIME] .'</hour><atom>'. $val[HRATOM] .'</atom>' . chr(13) . chr(10);
	if( $val[HRTIME] == TIMEEVENING ) { $hrEven[HRATOM] = $val[HRATOM]; }
	if( $val[HRTIME] == TIMEMORN ) { $hrMorn[HRATOM] = $val[HRATOM]; }
}
echo ' </night>' . chr(13) . chr(10);
}
else
{
foreach( $arrHours AS $key => $val ) {
	if( $val[HRTIME] == TIMEEVENING ) { $hrEven[HRATOM] = $val[HRATOM]; }
	if( $val[HRTIME] == TIMEMORN ) { $hrMorn[HRATOM] = $val[HRATOM]; }
}
}

$maps = [ NECLOUDCOVER, NWCLOUDCOVER, SECLOUDCOVER, SWCLOUDCOVER, TRNORTHAMERICA, SENORTHAMERICA, UVNORTHAMERICA, HRNORTHAMERICA, TTNORTHAMERICA ];
// TODO: dusk to dawn
$strDusk = substr( '000' . (string)($location->sunset_hour()  +5  -(integer)$night->get_twelve()), -3);
$strDawn = substr( '000' . (string)($location->sunrise_hour() +29 -(integer)$night->get_twelve()), -3);
// TODO: $strDusk = substr( '000' . (string)($location->sunset_hour() - $location->timeoffset() +00 -(integer)$night->get_twelve()), -3);
// TODO: $strDawn = substr( '000' . (string)($location->sunrise_hour() - $location->timeoffset() +24 -(integer)$night->get_twelve()), -3);

// TODO:  Can I see anything?
$intCloudCoverAverage = [ AVGEVENING => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGNIGHT => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGASTROTWILIGHT => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGMORN => [ AVGAVERAGE => -1, AVGCOUNT => 0] ];
$intTransparencyAverage = [ AVGEVENING => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGNIGHT => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGASTROTWILIGHT => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGMORN => [ AVGAVERAGE => -1, AVGCOUNT => 0] ];
$intSeeingAverage = [ AVGEVENING => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGNIGHT => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGASTROTWILIGHT => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGMORN => [ AVGAVERAGE => -1, AVGCOUNT => 0] ];
$intWindAverage = [ AVGEVENING => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGNIGHT => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGASTROTWILIGHT => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGMORN => [ AVGAVERAGE => -1, AVGCOUNT => 0] ];
$intHumidityAverage = [ AVGEVENING => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGNIGHT => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGASTROTWILIGHT => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGMORN => [ AVGAVERAGE => -1, AVGCOUNT => 0] ];
$intTemperatureAverage = [ AVGEVENING => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGNIGHT => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGASTROTWILIGHT => [ AVGAVERAGE => -1, AVGCOUNT => 0], AVGMORN => [ AVGAVERAGE => -1, AVGCOUNT => 0] ];
foreach( $hrEvent AS $evtKey => $evtVal ) {
	$intCloudCoverAverage[ $evtKey ] = [ AVGAVERAGE => -1, AVGCOUNT => 0];
	$intTransparencyAverage[ $evtKey ] = [ AVGAVERAGE => -1, AVGCOUNT => 0];
	$intSeeingAverage[ $evtKey ] = [ AVGAVERAGE => -1, AVGCOUNT => 0];
	$intWindAverage[ $evtKey ] = [ AVGAVERAGE => -1, AVGCOUNT => 0];
	$intHumidityAverage[ $evtKey ] = [ AVGAVERAGE => -1, AVGCOUNT => 0];
	$intTemperatureAverage[ $evtKey ] = [ AVGAVERAGE => -1, AVGCOUNT => 0];
}

// TODO: daytime events
foreach( $arrHours AS $hourkey => $hourval ) {
  $blnShowMe = $hourkey >= $strDusk && $hourkey <= $strDawn;
  foreach( $hrEvent AS $evtKey => $evtVal ) {
	if( $hourval[HRATOM] <= $evtVal[HRATOM] && $hourval[HRATOM]+ATOMICHOUR > $evtVal[HRATOM] ) $blnShowMe = true;
  }
  if( $blnShowMe ) {
if( $blnVerbose ) {
	echo ' <hour time="'. $hourval[HRTIME] .'">' . chr(13) . chr(10);
}
	$intCloudCoverRating = -1;
	$intTransparencyRating = -1;
	$intSeeingRating = -1;
	$intWindRating = -1;
	$intHumidityRating = -1;
	$intTemperatureRating = -1;
	foreach( $maps AS $mapkey => $mapval ) {
	  switch( $mapval ) {
	  case $location->map():
	  case TRNORTHAMERICA:
	  case SENORTHAMERICA:
	  case UVNORTHAMERICA:
	  case HRNORTHAMERICA:
	  case TTNORTHAMERICA:
		$map = new Weather_Map( $location->observatory(), $night->get_date(), $hourkey, $mapval );
		// TODO: MIME an image?
if( $blnVerbose ) {
		echo '  <map mapval="'. $mapval .'">' . chr(13) . chr(10);
		echo '   <local>' . $map->local() . '</local>' . chr(13) . chr(10);
		echo '   <remote>' . $map->url() . '</remote>' . chr(13) . chr(10);
		// TODO: echo '  <files>' . print_r( $map->files() ) . '</files>' . chr(13) . chr(10);
		echo '  </map>' . chr(13) . chr(10);
}
		
		switch( $mapval ){
		case NECLOUDCOVER:
		case NWCLOUDCOVER:
		case SECLOUDCOVER:
		case SWCLOUDCOVER:
			$weather = new Weather_Analysis( $map->local(), $location->map1coordinates()[XCLOUDCOVER], $location->map1coordinates()[YCLOUDCOVER], $mapval );
			break;
		default:
			$weather = new Weather_Analysis( $map->local(), $location->map2coordinates()[XNORTHAMERICA], $location->map2coordinates()[YNORTHAMERICA], $mapval );
			break;
		}
if( $blnVerbose ) {
		echo '  <weather mapval="'. $mapval .'">' . chr(13) . chr(10);
		echo '   <colour>' . $weather->get_colour() . '</colour>' . chr(13) . chr(10);
		echo '   <text>' . $weather->get_text() . '</text>' . chr(13) . chr(10);
		echo '   <rating>' . $weather->get_rating() . '</rating>' . chr(13) . chr(10);
		echo '  </weather>' . chr(13) . chr(10);
}

// TODO:  Can I see anything?
		switch( $mapval ) {
		case NECLOUDCOVER:
		case NWCLOUDCOVER:
		case SECLOUDCOVER:
		case SWCLOUDCOVER:
			$intCloudCoverRating = $weather->get_rating();
			if( $hourval[HRATOM] >= $location->get_astrotwilightends() && $hourval[HRATOM] <= $location->get_astrotwilightbegins() ) {
				// Astronomical twilight has the darkest skies. The period between is the darkest. Stars up to Magnitude 6 are visible. 
				$intCloudCoverAverage[ AVGASTROTWILIGHT ][ AVGAVERAGE ] = ( $intCloudCoverAverage[ AVGASTROTWILIGHT ][ AVGAVERAGE ] * $intCloudCoverAverage[ AVGASTROTWILIGHT ][ AVGCOUNT ]++ + $intCloudCoverRating ) / $intCloudCoverAverage[ AVGASTROTWILIGHT ][ AVGCOUNT ];
				// TODO: echo $hourval[HRTIME] . '  Average:' . $intCloudCoverAverage[ AVGASTROTWILIGHT ][ AVGAVERAGE ] . '   Count:'. $intCloudCoverAverage[ AVGASTROTWILIGHT ][ AVGCOUNT ] . chr(13) . chr(10); 
			}
			else {
				// TODO: echo $hourval[HRTIME] . '  Average:' . $intCloudCoverAverage[ AVGASTROTWILIGHT ][ AVGAVERAGE ] . '   Count:'. $intCloudCoverAverage[ AVGASTROTWILIGHT ][ AVGCOUNT ] . chr(13) . chr(10); 
			}
			if( $hourval[HRTIME] <= TIMEEVENING && $hourval[HRTIME] >= '12h00' ) {
				$intCloudCoverAverage[ AVGEVENING ][ AVGAVERAGE ] = ( $intCloudCoverAverage[ AVGEVENING ][ AVGAVERAGE ] * $intCloudCoverAverage[ AVGEVENING ][ AVGCOUNT ]++ + $intCloudCoverRating ) / $intCloudCoverAverage[ AVGEVENING ][ AVGCOUNT ];
				// TODO: echo $hourval[HRTIME] . '  Average:' . $intCloudCoverAverage[ AVGEVENING ][ AVGAVERAGE ] . '   Count:'. $intCloudCoverAverage[ AVGEVENING ][ AVGCOUNT ] . chr(13) . chr(10);
				// TODO: echo $hourval['Time'] . '  Average:' . $intCloudCoverAverage[ AVGNIGHT ][ AVGAVERAGE ] . '   Count:'. $intCloudCoverAverage[ AVGNIGHT ][ AVGCOUNT ] . chr(13) . chr(10);
				// TODO: echo $hourval['Time'] . '  Average:' . $intCloudCoverAverage[ AVGMORN ][ AVGAVERAGE ] . '   Count:'. $intCloudCoverAverage[ AVGMORN ][ AVGCOUNT ] . chr(13) . chr(10);
			}
			elseif( $hourval[HRTIME] >= TIMEMORN && $hourval[HRTIME] < '12h00' ) {
				$intCloudCoverAverage[ AVGMORN ][ AVGAVERAGE ] = ( $intCloudCoverAverage[ AVGMORN ][ AVGAVERAGE ] * $intCloudCoverAverage[ AVGMORN ][ AVGCOUNT ]++ + $intCloudCoverRating ) / $intCloudCoverAverage[ AVGMORN ][ AVGCOUNT ];
				// TODO: echo $hourval['Time'] . '  Average:' . $intCloudCoverAverage[ AVGEVENING ][ AVGAVERAGE ] . '   Count:'. $intCloudCoverAverage[ AVGEVENING ][ AVGCOUNT ] . chr(13) . chr(10);
				// TODO: echo $hourval['Time'] . '  Average:' . $intCloudCoverAverage[ AVGNIGHT ][ AVGAVERAGE ] . '   Count:'. $intCloudCoverAverage[ AVGNIGHT ][ AVGCOUNT ] . chr(13) . chr(10);
				// TODO: echo $hourval['Time'] . '  Average:' . $intCloudCoverAverage[ AVGMORN ][ AVGAVERAGE ] . '   Count:'. $intCloudCoverAverage[ AVGMORN ][ AVGCOUNT ] . chr(13) . chr(10);
			}
			else {
				$intCloudCoverAverage[ AVGNIGHT ][ AVGAVERAGE ] = ( $intCloudCoverAverage[ AVGNIGHT ][ AVGAVERAGE ] * $intCloudCoverAverage[ AVGNIGHT ][ AVGCOUNT ]++ + $intCloudCoverRating ) / $intCloudCoverAverage[ AVGNIGHT ][ AVGCOUNT ];
				// TODO: echo $hourval['Time'] . '  Average:' . $intCloudCoverAverage[ AVGEVENING ][ AVGAVERAGE ] . '   Count:'. $intCloudCoverAverage[ AVGEVENING ][ AVGCOUNT ] . chr(13) . chr(10);
				// TODO: echo $hourval['Time'] . '  Average:' . $intCloudCoverAverage[ AVGNIGHT ][ AVGAVERAGE ] . '   Count:'. $intCloudCoverAverage[ AVGNIGHT ][ AVGCOUNT ] . chr(13) . chr(10);
				// TODO: echo $hourval['Time'] . '  Average:' . $intCloudCoverAverage[ AVGMORN ][ AVGAVERAGE ] . '   Count:'. $intCloudCoverAverage[ AVGMORN ][ AVGCOUNT ] . chr(13) . chr(10);
			}
			// The same thing, again, for each event.
			foreach( $hrEvent AS $evtKey => $evtVal ) {
				if( $hourval[HRATOM] <= $evtVal[HRATOM] && $hourval[HRATOM]+ATOMICHOUR > $evtVal[HRATOM] ) {
					$intCloudCoverAverage[ $evtKey ][ AVGAVERAGE ] = ( $intCloudCoverAverage[ $evtKey ][ AVGAVERAGE ] * $intCloudCoverAverage[ $evtKey ][ AVGCOUNT ]++ + $intCloudCoverRating ) / $intCloudCoverAverage[ $evtKey ][ AVGCOUNT ];
					// TODO: DELETE echo $hourval[HRATOM] . '  Average:' . $intCloudCoverAverage[ $evtKey ][ AVGAVERAGE ] . '   Count:'. $intCloudCoverAverage[ $evtKey ][ AVGCOUNT ] . chr(13) . chr(10);
				}
			}
			break;
		case TRNORTHAMERICA:
			$intTemperatureRating = $weather->get_rating();
			if( $hourval[HRATOM] >= ASTROTWILIGHTEND && $hourval[HRATOM] <= ASTROTWILIGHTBEG ) {
				// Astronomical twilight has the darkest skies. The period between is the darkest. Stars up to Magnitude 6 are visible. 
				$intTransparencyAverage[ AVGASTROTWILIGHT ][ AVGAVERAGE ] = ( $intTransparencyAverage[ AVGASTROTWILIGHT ][ AVGAVERAGE ] * $intTransparencyAverage[ AVGASTROTWILIGHT ][ AVGCOUNT ]++ + $intTransparencyRating ) / $intTransparencyAverage[ AVGASTROTWILIGHT ][ AVGCOUNT ];
			}
			if( $hourval[HRTIME] <= TIMEEVENING ) {
				$intTransparencyAverage[ AVGEVENING ][ AVGAVERAGE ] = ( $intTransparencyAverage[ AVGEVENING ][ AVGAVERAGE ] * $intTransparencyAverage[ AVGEVENING ][ AVGCOUNT ]++ + $intTransparencyRating ) / $intTransparencyAverage[ AVGEVENING ][ AVGCOUNT ];
			}
			elseif( $hourval[HRTIME] >= TIMEMORN ) {
				$intTransparencyAverage[ AVGMORN ][ AVGAVERAGE ] = ( $intTransparencyAverage[ AVGMORN ][ AVGAVERAGE ] * $intTransparencyAverage[ AVGMORN ][ AVGCOUNT ]++ + $intTransparencyRating ) / $intTransparencyAverage[ AVGMORN ][ AVGCOUNT ];
			}
			else {
				$intTransparencyAverage[ AVGNIGHT ][ AVGAVERAGE ] = ( $intTransparencyAverage[ AVGNIGHT ][ AVGAVERAGE ] * $intTransparencyAverage[ AVGNIGHT ][ AVGCOUNT ]++ + $intTransparencyRating ) / $intTransparencyAverage[ AVGNIGHT ][ AVGCOUNT ];
			}
			// The same thing, again, for each event.
			foreach( $hrEvent AS $evtKey => $evtVal ) {
				if( $hourval[HRATOM] <= $evtVal[HRATOM] && $hourval[HRATOM]+ATOMICHOUR > $evtVal[HRATOM] ) {
					$intTransparencyAverage[ $evtKey ][ AVGAVERAGE ] = ( $intTransparencyAverage[ $evtKey ][ AVGAVERAGE ] * $intTransparencyAverage[ $evtKey ][ AVGCOUNT ]++ + $intTransparencyRating ) / $intTransparencyAverage[ $evtKey ][ AVGCOUNT ];
				// TODO: DELETE echo $hourval[HRATOM] . '  Average:' . $intTransparencyAverage[ $evtKey ][ AVGAVERAGE ] . '   Count:'. $intTransparencyAverage[ $evtKey ][ AVGCOUNT ] . chr(13) . chr(10);
				}
			}
			break;
		case SENORTHAMERICA:
			$intSeeingRating = $weather->get_rating();
			if( $hourval[HRATOM] >= ASTROTWILIGHTEND && $hourval[HRATOM] <= ASTROTWILIGHTBEG ) {
				// Astronomical twilight has the darkest skies. The period between is the darkest. Stars up to Magnitude 6 are visible. 
				$intSeeingAverage[ AVGASTROTWILIGHT ][ AVGAVERAGE ] = ( $intSeeingAverage[ AVGASTROTWILIGHT ][ AVGAVERAGE ] * $intSeeingAverage[ AVGASTROTWILIGHT ][ AVGCOUNT ]++ + $intSeeingRating ) / $intSeeingAverage[ AVGASTROTWILIGHT ][ AVGCOUNT ];
			}
			if( $hourval[HRTIME] <= TIMEEVENING ) {
				$intSeeingAverage[ AVGEVENING ][ AVGAVERAGE ] = ( $intSeeingAverage[ AVGEVENING ][ AVGAVERAGE ] * $intSeeingAverage[ AVGEVENING ][ AVGCOUNT ]++ + $intSeeingRating ) / $intSeeingAverage[ AVGEVENING ][ AVGCOUNT ];
			}
			elseif( $hourval[HRTIME] >= TIMEMORN ) {
				$intSeeingAverage[ AVGMORN ][ AVGAVERAGE ] = ( $intSeeingAverage[ AVGMORN ][ AVGAVERAGE ] * $intSeeingAverage[ AVGMORN ][ AVGCOUNT ]++ + $intSeeingRating ) / $intSeeingAverage[ AVGMORN ][ AVGCOUNT ];
			}
			else {
				$intSeeingAverage[ AVGNIGHT ][ AVGAVERAGE ] = ( $intSeeingAverage[ AVGNIGHT ][ AVGAVERAGE ] * $intSeeingAverage[ AVGNIGHT ][ AVGCOUNT ]++ + $intSeeingRating ) / $intSeeingAverage[ AVGNIGHT ][ AVGCOUNT ];
			}
			// The same thing, again, for each event.
			foreach( $hrEvent AS $evtKey => $evtVal ) {
				if( $hourval[HRATOM] <= $evtVal[HRATOM] && $hourval[HRATOM]+ATOMICHOUR > $evtVal[HRATOM] ) {
					$intSeeingAverage[ $evtKey ][ AVGAVERAGE ] = ( $intSeeingAverage[ $evtKey ][ AVGAVERAGE ] * $intSeeingAverage[ $evtKey ][ AVGCOUNT ]++ + $intSeeingRating ) / $intSeeingAverage[ $evtKey ][ AVGCOUNT ];
				// TODO: DELETE echo $hourval[HRATOM] . '  Average:' . $intSeeingAverage[ $evtKey ][ AVGAVERAGE ] . '   Count:'. $intSeeingAverage[ $evtKey ][ AVGCOUNT ] . chr(13) . chr(10);
				}
			}
			break;
		case UVNORTHAMERICA:
			$intWindRating = $weather->get_rating();
			if( $hourval[HRATOM] >= ASTROTWILIGHTEND && $hourval[HRATOM] <= ASTROTWILIGHTBEG ) {
				// Astronomical twilight has the darkest skies. The period between is the darkest. Stars up to Magnitude 6 are visible. 
				$intWindAverage[ AVGASTROTWILIGHT ][ AVGAVERAGE ] = ( $intWindAverage[ AVGASTROTWILIGHT ][ AVGAVERAGE ] * $intWindAverage[ AVGASTROTWILIGHT ][ AVGCOUNT ]++ + $intWindRating ) / $intWindAverage[ AVGASTROTWILIGHT ][ AVGCOUNT ];
			}
			if( $hourval[HRTIME] <= TIMEEVENING ) {
				$intWindAverage[ AVGEVENING ][ AVGAVERAGE ] = ( $intWindAverage[ AVGEVENING ][ AVGAVERAGE ] * $intWindAverage[ AVGEVENING ][ AVGCOUNT ]++ + $intWindRating ) / $intWindAverage[ AVGEVENING ][ AVGCOUNT ];
			}
			elseif( $hourval[HRTIME] >= TIMEMORN ) {
				$intWindAverage[ AVGMORN ][ AVGAVERAGE ] = ( $intWindAverage[ AVGMORN ][ AVGAVERAGE ] * $intWindAverage[ AVGMORN ][ AVGCOUNT ]++ + $intWindRating ) / $intWindAverage[ AVGMORN ][ AVGCOUNT ];
			}
			else {
				$intWindAverage[ AVGNIGHT ][ AVGAVERAGE ] = ( $intWindAverage[ AVGNIGHT ][ AVGAVERAGE ] * $intWindAverage[ AVGNIGHT ][ AVGCOUNT ]++ + $intWindRating ) / $intWindAverage[ AVGNIGHT ][ AVGCOUNT ];
			}
			// The same thing, again, for each event.
			foreach( $hrEvent AS $evtKey => $evtVal ) {
				if( $hourval[HRATOM] <= $evtVal[HRATOM] && $hourval[HRATOM]+ATOMICHOUR > $evtVal[HRATOM] ) {
					$intWindAverage[ $evtKey ][ AVGAVERAGE ] = ( $intWindAverage[ $evtKey ][ AVGAVERAGE ] * $intWindAverage[ $evtKey ][ AVGCOUNT ]++ + $intWindRating ) / $intWindAverage[ $evtKey ][ AVGCOUNT ];
				// TODO: DELETE echo $hourval[HRATOM] . '  Average:' . $intWindAverage[ $evtKey ][ AVGAVERAGE ] . '   Count:'. $intWindAverage[ $evtKey ][ AVGCOUNT ] . chr(13) . chr(10);
				}
			}
			break;
		case HRNORTHAMERICA:
			$intHumidityRating = $weather->get_rating();
			if( $hourval[HRATOM] >= ASTROTWILIGHTEND && $hourval[HRATOM] <= ASTROTWILIGHTBEG ) {
				// Astronomical twilight has the darkest skies. The period between is the darkest. Stars up to Magnitude 6 are visible. 
				$intHumidityAverage[ AVGASTROTWILIGHT ][ AVGAVERAGE ] = ( $intHumidityAverage[ AVGASTROTWILIGHT ][ AVGAVERAGE ] * $intHumidityAverage[ AVGASTROTWILIGHT ][ AVGCOUNT ]++ + $intHumidityRating ) / $intHumidityAverage[ AVGASTROTWILIGHT ][ AVGCOUNT ];
			}
			if( $hourval[HRTIME] <= TIMEEVENING ) {
				$intHumidityAverage[ AVGEVENING ][ AVGAVERAGE ] = ( $intHumidityAverage[ AVGEVENING ][ AVGAVERAGE ] * $intHumidityAverage[ AVGEVENING ][ AVGCOUNT ]++ + $intHumidityRating ) / $intHumidityAverage[ AVGEVENING ][ AVGCOUNT ];
			}
			elseif( $hourval[HRTIME] >= TIMEMORN ) {
				$intHumidityAverage[ AVGMORN ][ AVGAVERAGE ] = ( $intHumidityAverage[ AVGMORN ][ AVGAVERAGE ] * $intHumidityAverage[ AVGMORN ][ AVGCOUNT ]++ + $intHumidityRating ) / $intHumidityAverage[ AVGMORN ][ AVGCOUNT ];
			}
			else {
				$intHumidityAverage[ AVGNIGHT ][ AVGAVERAGE ] = ( $intHumidityAverage[ AVGNIGHT ][ AVGAVERAGE ] * $intHumidityAverage[ AVGNIGHT ][ AVGCOUNT ]++ + $intHumidityRating ) / $intHumidityAverage[ AVGNIGHT ][ AVGCOUNT ];
			}
			// The same thing, again, for each event.
			foreach( $hrEvent AS $evtKey => $evtVal ) {
				if( $hourval[HRATOM] <= $evtVal[HRATOM] && $hourval[HRATOM]+ATOMICHOUR > $evtVal[HRATOM] ) {
					$intHumidityAverage[ $evtKey ][ AVGAVERAGE ] = ( $intHumidityAverage[ $evtKey ][ AVGAVERAGE ] * $intHumidityAverage[ $evtKey ][ AVGCOUNT ]++ + $intHumidityRating ) / $intHumidityAverage[ $evtKey ][ AVGCOUNT ];
				// TODO: DELETE echo $hourval[HRATOM] . '  Average:' . $intHumidityAverage[ $evtKey ][ AVGAVERAGE ] . '   Count:'. $intHumidityAverage[ $evtKey ][ AVGCOUNT ] . chr(13) . chr(10);
				}
			}
			break;
		case TTNORTHAMERICA:
			$intTemperatureRating = $weather->get_rating();
			if( $hourval[HRATOM] >= ASTROTWILIGHTEND && $hourval[HRATOM] <= ASTROTWILIGHTBEG ) {
				// Astronomical twilight has the darkest skies. The period between is the darkest. Stars up to Magnitude 6 are visible. 
				$intTemperatureAverage[ AVGASTROTWILIGHT ][ AVGAVERAGE ] = ( $intTemperatureAverage[ AVGASTROTWILIGHT ][ AVGAVERAGE ] * $intTemperatureAverage[ AVGASTROTWILIGHT ][ AVGCOUNT ]++ + $intTemperatureRating ) / $intTemperatureAverage[ AVGASTROTWILIGHT ][ AVGCOUNT ];
			}
			if( $hourval[HRTIME] <= TIMEEVENING ) {
				$intTemperatureAverage[ AVGEVENING ][ AVGAVERAGE ] = ( $intTemperatureAverage[ AVGEVENING ][ AVGAVERAGE ] * $intTemperatureAverage[ AVGEVENING ][ AVGCOUNT ]++ + $intTemperatureRating ) / $intTemperatureAverage[ AVGEVENING ][ AVGCOUNT ];
			}
			elseif( $hourval[HRTIME] >= TIMEMORN ) {
				$intTemperatureAverage[ AVGMORN ][ AVGAVERAGE ] = ( $intTemperatureAverage[ AVGMORN ][ AVGAVERAGE ] * $intTemperatureAverage[ AVGMORN ][ AVGCOUNT ]++ + $intTemperatureRating ) / $intTemperatureAverage[ AVGMORN ][ AVGCOUNT ];
			}
			else {
				$intTemperatureAverage[ AVGNIGHT ][ AVGAVERAGE ] = ( $intTemperatureAverage[ AVGNIGHT ][ AVGAVERAGE ] * $intTemperatureAverage[ AVGNIGHT ][ AVGCOUNT ]++ + $intTemperatureRating ) / $intTemperatureAverage[ AVGNIGHT ][ AVGCOUNT ];
			}
			// The same thing, again, for each event.
			foreach( $hrEvent AS $evtKey => $evtVal ) {
				if( $hourval[HRATOM] <= $evtVal[HRATOM] && $hourval[HRATOM]+ATOMICHOUR > $evtVal[HRATOM] ) {
					$intTemperatureAverage[ $evtKey ][ AVGAVERAGE ] = ( $intTemperatureAverage[ $evtKey ][ AVGAVERAGE ] * $intTemperatureAverage[ $evtKey ][ AVGCOUNT ]++ + $intTemperatureRating ) / $intTemperatureAverage[ $evtKey ][ AVGCOUNT ];
				// TODO: DELETE echo $hourval[HRATOM] . '  Average:' . $intTemperatureAverage[ $evtKey ][ AVGAVERAGE ] . '   Count:'. $intTemperatureAverage[ $evtKey ][ AVGCOUNT ] . chr(13) . chr(10);
				}
			}
			break;
		}

		$map->flushImage();
	  } 
	}
if( $blnVerbose ) {
	echo '  <viewing>' . chr(13) . chr(10) . '   <clock>'. $hourval[HRTIME] .'</clock>' . chr(13) . chr(10);
	switch( $intCloudCoverRating ) {
	case -1:
		echo '   <skies>Unknown</skies>' . chr(13) . chr(10);
		break;
	case 0:
		echo '   <skies>Overcast</skies>' . chr(13) . chr(10);
		break;
	case 1:
	case 2:
	case 3:
	case 4:
	case 5:
		echo '   <skies>Cloudy</skies>' . chr(13) . chr(10);
		break;
	case 6:
	case 7:
	case 8:
		switch( $intTransparencyRating ) {
		case -1:
			echo '   <skies>Cloudy, with unknown transparency.</skies>' . chr(13) . chr(10);
			break;
		case 0:
		case 1:
		case 2:
			echo '   <skies>Cloudy, with intermittent haze.</skies>' . chr(13) . chr(10);
			break;
		case 3:
			echo '   <skies>Cloudy, with intermittent stars.</skies>' . chr(13) . chr(10);
			break;
		case 4:
			echo '   <skies>Cloudy, with intermittent Milky Way.</skies>' . chr(13) . chr(10);
			break;
		case 5:
			echo '   <skies>Cloudy, with intermittent nebula.</skies>' . chr(13) . chr(10);
			break;
		}
		break;
	case 9:
	case 10:
		switch( $intTransparencyRating ) {
		case -1:
			echo '   <skies>Clear, with unknown transparency.</skies>' . chr(13) . chr(10);
			break;
		case 0:
		case 1:
		case 2:
			echo '   <skies>Clear, with haze.</skies>' . chr(13) . chr(10);
			break;
		case 3:
		case 4:
		case 5:
			switch( $intSeeingRating ) {
			case -1:
				echo '   <skies>Clear, with unknown seeing.</skies>' . chr(13) . chr(10);
			case 0:
			case 1:
			case 2:
				echo '   <skies>Clear, with lots of twinkle.</skies>' . chr(13) . chr(10);
				break;
			case 3:
				echo '   <skies>Clear, with some twinkle.</skies>' . chr(13) . chr(10);
				break;
			case 4:
				echo '   <skies>Clear, with little twinkle.</skies>' . chr(13) . chr(10);
				break;
			case 5:
				echo '   <skies>Clear, with no twinkle.</skies>' . chr(13) . chr(10);
				break;
			}
			break;
		}
	}
	echo '  </viewing>' . chr(13) . chr(10);
	echo ' </hour>' . chr(13) . chr(10);
}
  }	
}

// TODO: Check that the rise/set times match reality for St. John's and Fairbanks.
echo ' <viewing>' . chr(13) . chr(10);
echo '  <evening>' . chr(13) . chr(10) . '   <clockbegin atomic="'.$location->get_sunset().'">'. $dteDisplayTime->setTimestamp( $location->get_sunset() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockbegin>' . chr(13) . chr(10);
echo '   <clockend atomic="'.$hrEven[HRATOM].'">'. $dteDisplayTime->setTimestamp( $hrEven[HRATOM] )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockend>' . chr(13) . chr(10);
echo '   <view>' . ($intCloudCoverAverage[AVGEVENING][AVGAVERAGE]>=9?
					($intTransparencyAverage[AVGEVENING][AVGAVERAGE]>=5?
						($intSeeingAverage[AVGEVENING][AVGAVERAGE]>=5?'Clear, with little twinkle':'Clear, with some twinkle'):
						($intTransparencyAverage[AVGEVENING][AVGAVERAGE]>=4?
							($intSeeingAverage[AVGEVENING][AVGAVERAGE]>=5?'Clear, with a little haze but little twinkle':'Clear, with a little haze and some twinkle'):'Clear, with a lot of haze'
						)
					):
					($intCloudCoverAverage[AVGEVENING][AVGAVERAGE]>=7?'Mostly clear':($intCloudCoverAverage[AVGEVENING][AVGAVERAGE]>=5?'Mostly cloudy':($intCloudCoverAverage[AVGEVENING][AVGAVERAGE]>=3?'Too cloudy':($intCloudCoverAverage[AVGEVENING][AVGAVERAGE]>=0?'Overcast':'Unknown'))))
				) . '</view>' . chr(13) . chr(10);
echo '  </evening>' . chr(13) . chr(10);
echo '  <darkestnight>' . chr(13) . chr(10) . '   <clockbegin atomic="'.$location->get_astrotwilightends().'">'. $dteDisplayTime->setTimestamp( $location->get_astrotwilightends() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockbegin>' . chr(13) . chr(10);
echo '   <clockend atomic="'.$location->get_astrotwilightbegins().'">'. $dteDisplayTime->setTimestamp( $location->get_astrotwilightbegins() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockend>' . chr(13) . chr(10);
echo '   <view>' . ($intCloudCoverAverage[AVGASTROTWILIGHT][AVGAVERAGE]>=9?($intTransparencyAverage[AVGASTROTWILIGHT][AVGAVERAGE]>=5?($intSeeingAverage[AVGASTROTWILIGHT][AVGAVERAGE]>=5?'Clear, with little twinkle':'Clear, with some twinkle'):($intTransparencyAverage[AVGASTROTWILIGHT][AVGAVERAGE]>=4?($intSeeingAverage[AVGASTROTWILIGHT][AVGAVERAGE]>=5?'Clear, with a little haze but little twinkle':'Clear, with a little haze and some twinkle'):'Clear, with a lot of haze')):($intCloudCoverAverage[AVGASTROTWILIGHT][AVGAVERAGE]>=7?'Mostly clear':($intCloudCoverAverage[AVGASTROTWILIGHT][AVGAVERAGE]>=5?'Mostly cloudy':($intCloudCoverAverage[AVGASTROTWILIGHT][AVGAVERAGE]>=3?'Too cloudy':($intCloudCoverAverage[AVGASTROTWILIGHT][AVGAVERAGE]>=0?'Overcast':'Unknown'))))) . '</view>' . chr(13) . chr(10);
echo '  </darkestnight>' . chr(13) . chr(10);
echo '  <overnight>' . chr(13) . chr(10) . '   <clockbegin atomic="'.$hrEven[HRATOM].'">'. $dteDisplayTime->setTimestamp( $hrEven[HRATOM] )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockbegin>' . chr(13) . chr(10);
echo '   <clockend atomic="'.$hrMorn[HRATOM].'">'. $dteDisplayTime->setTimestamp( $hrMorn[HRATOM] )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockend>' . chr(13) . chr(10);
echo '   <view>' . ($intCloudCoverAverage[AVGNIGHT][AVGAVERAGE]>=9?($intTransparencyAverage[AVGNIGHT][AVGAVERAGE]>=5?($intSeeingAverage[AVGNIGHT][AVGAVERAGE]>=5?'Clear, with little twinkle':'Clear, with some twinkle'):($intTransparencyAverage[AVGNIGHT][AVGAVERAGE]>=4?($intSeeingAverage[AVGNIGHT][AVGAVERAGE]>=5?'Clear, with a little haze but little twinkle':'Clear, with a little haze and some twinkle'):'Clear, with a lot of haze')):($intCloudCoverAverage[AVGNIGHT][AVGAVERAGE]>=7?'Mostly clear':($intCloudCoverAverage[AVGNIGHT][AVGAVERAGE]>=5?'Mostly cloudy':($intCloudCoverAverage[AVGNIGHT][AVGAVERAGE]>=3?'Too cloudy':($intCloudCoverAverage[AVGNIGHT][AVGAVERAGE]>=0?'Overcast':'Unknown'))))) . '</view>' . chr(13) . chr(10);
echo '  </overnight>' . chr(13) . chr(10);
echo '  <morning>' . chr(13) . chr(10) . '   <clockbegin atomic="'.$hrMorn[HRATOM].'">'. $dteDisplayTime->setTimestamp( $hrMorn[HRATOM] )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockbegin>' . chr(13) . chr(10);
echo '   <clockend atomic="'.$location->get_sunrise().'">'. $dteDisplayTime->setTimestamp( $location->get_sunrise() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockend>' . chr(13) . chr(10);
echo '   <view>' . ($intCloudCoverAverage[AVGMORN][AVGAVERAGE]>=9?($intTransparencyAverage[AVGMORN][AVGAVERAGE]>=5?($intSeeingAverage[AVGMORN][AVGAVERAGE]>=5?'Clear, with little twinkle':'Clear, with some twinkle'):($intTransparencyAverage[AVGMORN][AVGAVERAGE]>=4?($intSeeingAverage[AVGMORN][AVGAVERAGE]>=5?'Clear, with a little haze but little twinkle':'Clear, with a little haze and some twinkle'):'Clear, with a lot of haze')):($intCloudCoverAverage[AVGMORN][AVGAVERAGE]>=7?'Mostly clear':($intCloudCoverAverage[AVGMORN][AVGAVERAGE]>=5?'Mostly cloudy':($intCloudCoverAverage[AVGMORN][AVGAVERAGE]>=3?'Too cloudy':($intCloudCoverAverage[AVGMORN][AVGAVERAGE]>=0?'Overcast':'Unknown'))))) . '</view>' . chr(13) . chr(10);
echo '  </morning>' . chr(13) . chr(10);
foreach( $hrEvent AS $evtKey => $evtVal ) {
	echo '  <event>' . chr(13) . chr(10) . '   <clockbegin atomic="'.$evtVal[HRATOM].'">'. $dteDisplayTime->setTimestamp( $evtVal[HRATOM] )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockbegin>' . chr(13) . chr(10);
	echo '   <clockend atomic="'.$evtVal[HRATOM].'">'. $dteDisplayTime->setTimestamp( $evtVal[HRATOM] )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockend>' . chr(13) . chr(10);
	echo '   <view>' . ($intCloudCoverAverage[ $evtKey ][AVGAVERAGE]>=9?($intTransparencyAverage[ $evtKey ][AVGAVERAGE]>=5?($intSeeingAverage[ $evtKey ][AVGAVERAGE]>=5?'Clear, with little twinkle':'Clear, with some twinkle'):($intTransparencyAverage[ $evtKey ][AVGAVERAGE]>=4?($intSeeingAverage[ $evtKey ][AVGAVERAGE]>=5?'Clear, with a little haze but little twinkle':'Clear, with a little haze and some twinkle'):'Clear, with a lot of haze')):($intCloudCoverAverage[ $evtKey ][AVGAVERAGE]>=7?'Mostly clear':($intCloudCoverAverage[ $evtKey ][AVGAVERAGE]>=5?'Mostly cloudy':($intCloudCoverAverage[ $evtKey ][AVGAVERAGE]>=3?'Too cloudy':($intCloudCoverAverage[ $evtKey ][AVGAVERAGE]>=0?'Overcast':'Unknown'))))) . '</view>' . chr(13) . chr(10);
	echo '  </event>' . chr(13) . chr(10);
}
echo ' </viewing>' . chr(13) . chr(10);

if( $blnVerbose ) {
echo ' <credits>' . chr(13) . chr(10);
echo 'Big thanks to Allan Rahill of the Canadian Meteorological Center (weather.gc.ca) for providing the maps.' . chr(13) . chr(10);
echo ' </credits>' . chr(13) . chr(10);
echo ' <credits>' . chr(13) . chr(10);
echo 'Big thanks to Attila Danko of Clear Sky Chart (cleardarksky.com) for providing the locations (There are charts for 5518 locations.), and inspiration.' . chr(13) . chr(10);
echo ' </credits>' . chr(13) . chr(10);
}

echo '</astroforecast>' . chr(13) . chr(10);
?>
