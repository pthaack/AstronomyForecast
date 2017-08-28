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
	private $intMap1Coordinates = [ MAPPARAM => 'northeast', XCLOUDCOVER => 368, YCLOUDCOVER => 451 ];
	private $intMap2Coordinates = [ XNORTHAMERICA => 501, YNORTHAMERICA => 316 ];
	private $fltTimeZoneOffset = -4.0;
	private $fltTimeZoneAdjustment = 0.0; 
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
		'Fred' => [ MAPPARAM => 'northeast', XCLOUDCOVER => 367, YCLOUDCOVER => 450, XNORTHAMERICA => 500, YNORTHAMERICA => 315, LATITUDE => 43.650821, LONGITUDE => -79.570732, ALTITUDE => 110, TZOFFSET => -5, TZLOCAL => 'America/Toronto' ],
		'Victoria' => [ MAPPARAM => 'northwest', XCLOUDCOVER => 258, YCLOUDCOVER => 492, XNORTHAMERICA => 143, YNORTHAMERICA => 322, LATITUDE => 48.433, LONGITUDE => -123.35, ALTITUDE => 18, TZOFFSET => -8, TZLOCAL => 'America/Vancouver' ],
		'Winnipeg' => [ MAPPARAM => 'northwest', XCLOUDCOVER => 596, YCLOUDCOVER => 470, XNORTHAMERICA => 333, YNORTHAMERICA => 309, LATITUDE => 49.68, LONGITUDE => -98.229, ALTITUDE => 232, TZOFFSET => -6, TZLOCAL => 'America/Winnipeg' ],
		'Orono' => [ MAPPARAM => 'northeast', XCLOUDCOVER => 375, YCLOUDCOVER => 438, XNORTHAMERICA => 505, YNORTHAMERICA => 307, LATITUDE => 43.9518, LONGITUDE => -78.61700, ALTITUDE => 164, TZOFFSET => -5, TZLOCAL => 'America/Toronto' ],
		'Torrance Barrens' => [ MAPPARAM => 'northeast', XCLOUDCOVER => 354, YCLOUDCOVER => 428, XNORTHAMERICA => 493, YNORTHAMERICA => 301, LATITUDE => 44.9342, LONGITUDE => -79.50111, ALTITUDE => 247, TZOFFSET => -5, TZLOCAL => 'America/Toronto' ],
		'Churchill' => [ MAPPARAM => 'northwest', XCLOUDCOVER => 597, YCLOUDCOVER => 277, XNORTHAMERICA => 333, YNORTHAMERICA => 199, LATITUDE => 58.767, LONGITUDE => -94.167, ALTITUDE => 6, TZOFFSET => -6, TZLOCAL => 'America/Winnipeg' ],
		'St. John\'s' => [ MAPPARAM => 'northeast', XCLOUDCOVER => 581, YCLOUDCOVER => 128, XNORTHAMERICA => 626, YNORTHAMERICA => 125, LATITUDE => 47.55, LONGITUDE => -52.667, ALTITUDE => 18, TZOFFSET => -3.5, TZLOCAL => 'America/St_Johns' ],
		'Thunder Bay' => [ MAPPARAM => 'northeast', XCLOUDCOVER => 199, YCLOUDCOVER => 430, XNORTHAMERICA => 402, YNORTHAMERICA => 302, LATITUDE => 48.2802777778, LONGITUDE => -89.5372222222, ALTITUDE => 187, TZOFFSET => -6, TZLOCAL => 'America/Thunder_Bay' ],
		'Iqaluit' => [ MAPPARAM => 'northeast', XCLOUDCOVER => 235, YCLOUDCOVER => 64, XNORTHAMERICA => 423, YNORTHAMERICA => 87, LATITUDE => 63.7330, LONGITUDE => -68.50000, ALTITUDE => 11, TZOFFSET => -5, TZLOCAL => 'America/Iqaluit' ],
		'Fairbanks' => [ MAPPARAM => 'northwest', XCLOUDCOVER => 150, YCLOUDCOVER => 91, XNORTHAMERICA => 81, YNORTHAMERICA => 96, LATITUDE => 64.838, LONGITUDE => -147.716, ALTITUDE => 136, TZOFFSET => -9, TZLOCAL => 'America/Juneau' ],
		'Colbeck' => [ MAPPARAM => 'northeast', XCLOUDCOVER => 353, YCLOUDCOVER => 451, XNORTHAMERICA => 492, YNORTHAMERICA => 315, LATITUDE => 43.99, LONGITUDE => -80.3622222222, ALTITUDE => 488, TZOFFSET => -5, TZLOCAL => 'America/Toronto' ] ];

// TODO: create child class based on just lat/lng and tz; requires that position is on both maps; alt is to be looked up; tz might be a look up, too; would allow for reversal of data flow where Aurora Watch, Heavens Above would look here for local forecast instead of this fusking for their data.
	function __construct( $obs = null ) {
		if( is_null( $obs ) ) {
			$obs = $this->strObservatory;
		}
		else {
			switch( $obs ){
			case 'Fred':
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
				date_default_timezone_set(TIMEZONE);
				
		 		$this->strObservatory = $obs;
				$this->fltGeoposition = [ LATITUDE => $this->arrObservatory[ $obs ][ LATITUDE ], LONGITUDE =>  $this->arrObservatory[ $obs ][ LONGITUDE ] ];
				$this->intMap1Coordinates = [ MAPPARAM => $this->arrObservatory[ $obs ][ MAPPARAM ], XCLOUDCOVER => $this->arrObservatory[ $obs ][ XCLOUDCOVER ], YCLOUDCOVER => $this->arrObservatory[ $obs ][ YCLOUDCOVER ] ];
				$this->intMap2Coordinates = [ XNORTHAMERICA => $this->arrObservatory[ $obs ][ XNORTHAMERICA ], YNORTHAMERICA => $this->arrObservatory[ $obs ][ YNORTHAMERICA ] ];
				$this->intSunrise = $this->sunrise_hour();
				$this->intSunset = $this->sunset_hour();
				break;
			default:
				$obs = $this->strObservatory;
				// Unknown observatory, No change
				break;
			}
		}
		$tzSource = new DateTimeZone( TIMEZONE );
		$dteSource = new DateTime( 'now' );
		$tzLocal = new DateTimeZone( $this->arrObservatory[$this->strObservatory][ TZLOCAL ] );
		$dteLocal = new DateTime( 'now' );
		$dteLocal->setTimezone( $tzLocal );
		$this->fltTimeZoneAdjustment = $tzLocal->getOffset( $dteLocal ) - $tzSource->getOffset( $dteSource ); // TODO: echo 'tz:'. $this->fltTimeZoneAdjustment/3600 .chr(13).chr(10);

		$arrSunInfo = date_sun_info( time(), $this->arrObservatory[ $obs ][ LATITUDE ], $this->arrObservatory[ $obs ][ LONGITUDE ]); 
		$this->dteTransit = $this->set_transit( $arrSunInfo );
		$arrTomorrow = date_sun_info( time()+86400, $this->arrObservatory[ $obs ][ LATITUDE ], $this->arrObservatory[ $obs ][ LONGITUDE ]); 
		$this->dteOpposition = $this->set_opposition( $arrSunInfo, $arrTomorrow );
		$this->dteSunrise = $this->set_sunrise( $arrTomorrow );
		$this->dteSunset = $this->set_sunset( $arrSunInfo );
		$this->dteCTBegin = $this->set_civiltwilightbegins( $arrTomorrow );
		$this->dteCTEnd = $this->set_civiltwilightends( $arrSunInfo );
		$this->dteNTBegin = $this->set_nauticaltwilightbegins( $arrTomorrow );
		$this->dteNTEnd = $this->set_nauticaltwilightends( $arrSunInfo );
		$this->dteATBegin = $this->set_astrotwilightbegins( $arrTomorrow );
		$this->dteATEnd = $this->set_astrotwilightends( $arrSunInfo );

		$this->fltTimeZoneOffset = (float)$this->arrObservatory[ $obs ][ TZOFFSET ];
					
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
		}
		else {
			$tzLocal = new DateTimeZone( $this->arrObservatory[$this->strObservatory][ TZLOCAL ] );
			$dteLocal = new DateTime( 'now', $tzLocal );
			$this->arrObservatory[$this->strObservatory][TZOFFSET] = $tzLocal->getOffset( $dteLocal )/3600 ;
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
			$tzLocal = new DateTimeZone( $this->arrObservatory[$this->strObservatory][ TZLOCAL ] );
			$dteLocal = new DateTime( 'now', $tzLocal );
			$this->arrObservatory[$this->strObservatory][TZOFFSET] = $tzLocal->getOffset( $dteLocal )/3600 ;
			$intSunset = (integer) date('H',(date_sunset(time(),SUNFUNCS_RET_TIMESTAMP,$this->arrObservatory[$this->strObservatory][LATITUDE], $this->arrObservatory[$this->strObservatory][LONGITUDE],90,$this->arrObservatory[$this->strObservatory][TZOFFSET]) ) );
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
re=;  TODO: Define
longitude_offset=;  TODO: Define
x_offset=;  TODO: Define
y_offset=;  TODO: Define
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
*/

	function __construct( $geoPos = [ LATITUDE => 43.650821, LONGITUDE => -79.570732 ] ) {
	}
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
	
	function __construct( $tz = -4 ) {
		date_default_timezone_set(TIMEZONE); // Has to be done in Ottawa time zone, because the server updates at noon and midnight Ottawa time
		$this->strTwelve = (date('H')>=12?'12':'00');
		// TODO: DELETE echo 'TZ:'. $tz . ' Twv: ' . $this->strTwelve . chr(13).chr(10);
		for( $intI = 12 -(integer) $this->strTwelve +5; 
				$intI <= 12 -(integer) $this->strTwelve +9 +24;
				$intI++ ) {
			$strHour = substr('000' . (string) $intI, -3);

			// HRTIME is the Human Readable time in HHhMM
			$this->arrHours[ $strHour ][HRTIME] = substr('0' . (string)( ($intI + floor($tz))%24>12 ? ($intI + floor($tz))%24 - (integer)$this->strTwelve : ($intI + floor($tz))%24 + (integer)$this->strTwelve ), -2) . ($tz-floor($tz)>0.49?'h30':'h00');
			$strDateTime = (string)((integer)date('Ymd')+floor(($intI + $tz)/24)).substr($this->arrHours[ $strHour ][HRTIME],0,2).substr($this->arrHours[ $strHour ][HRTIME],3,2).'00';
			$strDateTime = (string)((integer)date('Ymd')+floor(($intI + $tz)/24)).substr($this->arrHours[ $strHour ][HRTIME],0,2).substr($this->arrHours[ $strHour ][HRTIME],3,2).'00';
			// Timestamp has to be interpreted in the local time zone, not Ottawa time.
			$intDateTime = mktime((integer)substr($strDateTime,8,2),
									(integer)substr($strDateTime,10,2),
									(integer)substr($strDateTime,12,2),
									(integer)substr($strDateTime,4,2),
									(integer)substr($strDateTime,6,2),
									(integer)substr($strDateTime,0,4));
			// HRATOM is the HouR in the timestamp format
			$this->arrHours[ $strHour ][HRATOM] = $intDateTime - $tz*ATOMICHOUR -4*ATOMICHOUR ;
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
			if( $intTimeStamp > time() && $intTimeStamp < $arrHrVals[0][HRATOM] || $intTimeStamp > $arrHrVals[ sizeof($arrHrVals)-1 ][HRATOM] + ATOMICHOUR ) {
				// TODO: DELETE echo 'adding '. $intTimeStamp . ' first: '. $arrHrVals[0][HRATOM] . ' last: '. $arrHrVals[ sizeof($arrHrVals)-1 ][HRATOM] .chr(13) . chr(10);
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
				
				// TODO: DELETE echo 'adding '. date('Ymd H\hi',$intTimeStamp) . ' str: '. $intTimeStamp . ' Hour: '. $strHrTime .chr(13) . chr(10);
				if( $intHour <= 48 ) {
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
	protected $blnGarbageCollected = false;

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
			switch( $obs ) {
			case 'Fred':
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
			if( is_numeric( $date ) && is_numeric( $hour ) ) {
				switch( $map ) {
				case 'northeast':
					$strMap = '_054_R1_north@america@northeast_I_ASTRO_nt_';
					break;
				case 'northwest':
					$strMap = '_054_R1_north@america@northwest_I_ASTRO_nt_';
					break;
				case 'southeast':
					$strMap = '_054_R1_north@america@southeast_I_ASTRO_nt_';
					break;
				case 'southwest':
					$strMap = '_054_R1_north@america@southwest_I_ASTRO_nt_';
					break;
				case 'transparency':
					$strMap = '_054_R1_north@america@astro_I_ASTRO_transp_';
					break;
				case 'seeing':
					$strMap = '_054_R1_north@america@astro_I_ASTRO_seeing_';
					$hour = substr( '000' . (string) (floor(((integer) $hour +1)/3)*3), -3); 
					break;
				case 'wind':
					$strMap = '_054_R1_north@america@astro_I_ASTRO_uv_';
					break;
				case 'humidity':
					$strMap = '_054_R1_north@america@astro_I_ASTRO_hr_';
					break;
				case 'temperature':
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

	// Delete files collected from previous nights
	private function garbage_collection( $strDateToKeep, $strMapType ) {
	
		// TODO: Lookup and delete line. const DATELENGTH = 10;
		$strMap = '';
		
	 	// check if there is any garbage to collect
		if( sizeof($this->arrFiles) == 0 ) { return; }		
		
		if( is_numeric( $strDateToKeep ) && strlen( $strDateToKeep ) == DATELENGTH ) {
			switch( $strMapType ) {
			case 'northeast':
				$strMap = '_054_R1_north@america@northeast_I_ASTRO_nt_';
				break;
			case 'northwest':
				$strMap = '_054_R1_north@america@northwest_I_ASTRO_nt_';
				break;
			case 'southeast':
				$strMap = '_054_R1_north@america@southeast_I_ASTRO_nt_';
				break;
			case 'southwest':
				$strMap = '_054_R1_north@america@southwest_I_ASTRO_nt_';
				break;
			case 'transparency':
				$strMap = '_054_R1_north@america@astro_I_ASTRO_transp_';
				break;
			case 'seeing':
				$strMap = '_054_R1_north@america@astro_I_ASTRO_seeing_';
				$hour = substr( '000' . (string) (floor(((integer) $hour +1)/3)*3), -3); 
				break;
			case 'wind':
				$strMap = '_054_R1_north@america@astro_I_ASTRO_uv_';
				break;
			case 'humidity':
				$strMap = '_054_R1_north@america@astro_I_ASTRO_hr_';
				break;
			case 'temperature':
				$strMap = '_054_R1_north@america@astro_I_ASTRO_tt_';
				break;
			default: 
				// Unknown map request. No map for you
	// TODO: echo 'missing valid Weather Map map';
				return;
			}
			
			foreach( $this->arrFiles AS $strLocalAddress ) {
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
		case 'northeast':
		case 'northwest':
		case 'southeast':
		case 'southwest':
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
		case 'transparency':
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
		case 'seeing':
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
		case 'wind':
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
		case 'humidity':
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
		case 'temperature':
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
					 ( isset( $mooncalc->getMoonTimes()['alwaysDown'] ) ? $dteOpposition : 
					  $mooncalc->getMoonTimes()['moonrise'] ) );
		$this->dteSet = ( isset( $mooncalc->getMoonTimes()['alwaysUp'] ) ? $dteTransit :
					 ( isset( $mooncalc->getMoonTimes()['alwaysDown'] ) ? $dteOpposition : 
					  $mooncalc->getMoonTimes()['moonset'] ) );
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

// TODO: Use XML object to build this XML code.
echo '<?xml version="1.0" encoding="UTF-8"?>' . chr(13) . chr(10);
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
if( isset( $_GET['obs'] ) ) {
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
							$dteEvent->add(new DateInterval('P1D'));
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

if( $blnVerbose ) {
echo ' <observatory>' . chr(13) . chr(10);
echo '  <observatory>'. $location->observatory() .'</observatory>' . chr(13) . chr(10);
echo '  <latitude>';
echo $location->geoposition()[ LATITUDE ];
echo '</latitude>' . chr(13) . chr(10);
echo '  <longitude>';
echo $location->geoposition()[ LONGITUDE ];
echo '</longitude>' . chr(13) . chr(10);
	
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

// convert timestamps to DateTime object to display in observatory timezone, for to be sure
$dteDisplayTime = new DateTime();
$dteDisplayTime->setTimezone( $tzEvent );

echo '  <suntimes>' . chr(13) . chr(10);
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
$objMoon = new Moon( $dteTransit, $location );
$objMoonRise = new Moon( $objMoon->rise(), $location );
$objMoonSet = new Moon( $objMoon->set(), $location );
if( $blnVerbose ) {
echo '  <moontimes>' . chr(13) . chr(10);
echo '   <moonrise atomic="'.$objMoon->rise()->getTimestamp().'">'. $dteDisplayTime->setTimestamp( $objMoon->rise()->getTimestamp() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</moonrise>' . chr(13) . chr(10);
echo '   <ra radian="'. $objMoonRise->ra() .'">'. ( $objMoonRise->ra() * 12.0 / M_PI) .'</ra>' . chr(13) . chr(10);
echo '   <dec radian="'. $objMoonRise->dec() .'">'. ( $objMoonRise->dec() * 180.0 / M_PI) .'</dec>' . chr(13) . chr(10);
echo '   <phase>'. $objMoon->illumination()['phase'] .'</phase>' . chr(13) . chr(10);
echo '   <illum>'. $objMoon->illumination()['fraction'] .'</illum>' . chr(13) . chr(10);
echo '   <phasename>'. $objMoon->illumination()['name'] .'</phasename>' . chr(13) . chr(10);
echo '   <ra radian="'. $objMoon->ra() .'">'. ( $objMoon->ra() * 12.0 / M_PI) .'</ra>' . chr(13) . chr(10);
echo '   <dec radian="'. $objMoon->dec() .'">'. ( $objMoon->dec() * 180.0 / M_PI) .'</dec>' . chr(13) . chr(10);
echo '   <moonset atomic="'.$objMoon->set()->getTimestamp().'">'. $dteDisplayTime->setTimestamp( $objMoon->set()->getTimestamp() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</moonset>' . chr(13) . chr(10);
echo '   <ra radian="'. $objMoonSet->ra() .'">'. ( $objMoonSet->ra() * 12.0 / M_PI) .'</ra>' . chr(13) . chr(10);
echo '   <dec radian="'. $objMoonSet->dec() .'">'. ( $objMoonSet->dec() * 180.0 / M_PI) .'</dec>' . chr(13) . chr(10);
echo '  </moontimes>' . chr(13) . chr(10);


echo ' </observatory>' . chr(13) . chr(10);
}

$night = new Night($location->timeoffset());
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

$maps = [ 'northeast', 'northwest', 'southeast', 'southwest', 'transparency', 'seeing', 'wind', 'humidity', 'temperature' ];
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
	  case 'transparency':
	  case 'seeing':
	  case 'wind':
	  case 'humidity':
	  case 'temperature':
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
		case 'northeast':
		case 'northwest':
		case 'southeast':
		case 'southwest':
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
		case 'northeast':
		case 'northwest':
		case 'southeast':
		case 'southwest':
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
		case 'transparency':
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
		case 'seeing':
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
		case 'wind':
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
		case 'humidity':
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
		case 'temperature':
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
echo '   <viewing>' . ($intCloudCoverAverage[AVGEVENING][AVGAVERAGE]>=9?'Clear':($intCloudCoverAverage[AVGEVENING][AVGAVERAGE]>=7?'Mostly clear':($intCloudCoverAverage[AVGEVENING][AVGAVERAGE]>=5?'Mostly cloudy':($intCloudCoverAverage[AVGEVENING][AVGAVERAGE]>=3?'Too cloudy':($intCloudCoverAverage[AVGEVENING][AVGAVERAGE]>=0?'Overcast':'Unknown'))))) . '</viewing>' . chr(13) . chr(10);
echo '  </evening>' . chr(13) . chr(10);
echo '  <darkestnight>' . chr(13) . chr(10) . '   <clockbegin atomic="'.$location->get_astrotwilightends().'">'. $dteDisplayTime->setTimestamp( $location->get_astrotwilightends() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockbegin>' . chr(13) . chr(10);
echo '   <clockend atomic="'.$location->get_astrotwilightbegins().'">'. $dteDisplayTime->setTimestamp( $location->get_astrotwilightbegins() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockend>' . chr(13) . chr(10);
echo '   <viewing>' . ($intCloudCoverAverage[AVGASTROTWILIGHT][AVGAVERAGE]>=9?'Clear':($intCloudCoverAverage[AVGASTROTWILIGHT][AVGAVERAGE]>=7?'Mostly clear':($intCloudCoverAverage[AVGASTROTWILIGHT][AVGAVERAGE]>=5?'Mostly cloudy':($intCloudCoverAverage[AVGASTROTWILIGHT][AVGAVERAGE]>=3?'Too cloudy':($intCloudCoverAverage[AVGASTROTWILIGHT][AVGAVERAGE]>=0?'Overcast':'Unknown'))))) . '</viewing>' . chr(13) . chr(10);
echo '  </darkestnight>' . chr(13) . chr(10);
echo '  <overnight>' . chr(13) . chr(10) . '   <clockbegin atomic="'.$hrEven[HRATOM].'">'. $dteDisplayTime->setTimestamp( $hrEven[HRATOM] )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockbegin>' . chr(13) . chr(10);
echo '   <clockend atomic="'.$hrMorn[HRATOM].'">'. $dteDisplayTime->setTimestamp( $hrMorn[HRATOM] )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockend>' . chr(13) . chr(10);
echo '   <viewing>' . ($intCloudCoverAverage[AVGNIGHT][AVGAVERAGE]>=9?'Clear':($intCloudCoverAverage[AVGNIGHT][AVGAVERAGE]>=7?'Mostly clear':($intCloudCoverAverage[AVGNIGHT][AVGAVERAGE]>=5?'Mostly cloudy':($intCloudCoverAverage[AVGNIGHT][AVGAVERAGE]>=3?'Too cloudy':($intCloudCoverAverage[AVGNIGHT][AVGAVERAGE]>=0?'Overcast':'Unknown'))))) . '</viewing>' . chr(13) . chr(10);
echo '  </overnight>' . chr(13) . chr(10);
echo '  <morning>' . chr(13) . chr(10) . '   <clockbegin atomic="'.$hrMorn[HRATOM].'">'. $dteDisplayTime->setTimestamp( $hrMorn[HRATOM] )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockbegin>' . chr(13) . chr(10);
echo '   <clockend atomic="'.$location->get_sunrise().'">'. $dteDisplayTime->setTimestamp( $location->get_sunrise() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockend>' . chr(13) . chr(10);
echo '   <viewing>' . ($intCloudCoverAverage[AVGMORN][AVGAVERAGE]>=9?'Clear':($intCloudCoverAverage[AVGMORN][AVGAVERAGE]>=7?'Mostly clear':($intCloudCoverAverage[AVGMORN][AVGAVERAGE]>=5?'Mostly cloudy':($intCloudCoverAverage[AVGMORN][AVGAVERAGE]>=3?'Too cloudy':($intCloudCoverAverage[AVGMORN][AVGAVERAGE]>=0?'Overcast':'Unknown'))))) . '</viewing>' . chr(13) . chr(10);
echo '  </morning>' . chr(13) . chr(10);
foreach( $hrEvent AS $evtKey => $evtVal ) {
	echo '  <event>' . chr(13) . chr(10) . '   <clockbegin atomic="'.$evtVal[HRATOM].'">'. $dteDisplayTime->setTimestamp( $evtVal[HRATOM] )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockbegin>' . chr(13) . chr(10);
	echo '   <clockend atomic="'.$evtVal[HRATOM].'">'. $dteDisplayTime->setTimestamp( $evtVal[HRATOM] )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi' ) .'</clockend>' . chr(13) . chr(10);
	echo '   <viewing>' . ($intCloudCoverAverage[ $evtKey ][AVGAVERAGE]>=9?'Clear':($intCloudCoverAverage[ $evtKey ][AVGAVERAGE]>=7?'Mostly clear':($intCloudCoverAverage[ $evtKey ][AVGAVERAGE]>=5?'Mostly cloudy':($intCloudCoverAverage[ $evtKey ][AVGAVERAGE]>=3?'Too cloudy':($intCloudCoverAverage[ $evtKey ][AVGAVERAGE]>=0?'Overcast':'Unknown'))))) . '</viewing>' . chr(13) . chr(10);
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
