<?php
/***
 *  Events lookup page
 * Parameters: 
 * 			obs    - pick an known observatory or name one
 * 			latlng - set the geo location
 * 			latlngalt - set the extended geo location
 * 			aurora - set the aurora level 1-9
 * 			dist   - set the angular distance between conjuncting planets, in degrees
 * 			datetime - set the specific observation time ( YYYYMMDDhhmmss )
 * 			verbose - display extra information for error tracking
 *  Planetary alignment
 *  Aurora forecast
 *  Satelite lookup
 *  Variable stars
 *  Meteor shower
 *  Comet
 *  Eclipse
 *
 *  Do garbage collection 
 *
 ***/

define('TZWEATHER', 'America/Toronto');	//Set timezone for Ottawa, source of government maps
define('TZAURORA', 'America/Juneau');	// Timezone for University of Alaska Fairbanks
define('TZUTC', 'UTC');	// Zulu time, GMT in Standard Time 
define('TZTIMEZONE', 'timezone');	// Timezone parameter 
define('TZNST', 'America/St_Johns');	// Set timezone for Newfoundland
define('TZAST', 'America/Halifax');	// Set timezone for Atlantic
define('TZEST', 'America/Toronto');	// Set timezone for Eastern Time
define('TZCST', 'America/Winnipeg');	// Set timezone for Central
define('TZMST', 'America/Edmonton');	// Set timezone for Mountain
define('TZPST', 'America/Vancouver');	// Set timezone for Pacific
define('TZNAST', 'America/Juneau');	// Set timezone for Alaska 
define('TZEAST', 'Pacific/Tongatapu');	// Set timezone for the farthest East
define('TZWEST', 'Pacific/Midway'); 	// Set timezone for the farthest West

// define('J1970', 2440588);  Defined in SunCalc.php
// define('J2000', 2451545);  Defined in SunCalc.php
define('DATADIR', 'data');
define('EVENTTYPE', 'type');
define('AURORALEVEL', 'int');
define('AURORANAME', 'str');
define('AURORAEVENT', 'aurora');
define('STARNAME', 'starname');
define('STARRA', 'ra');
define('STARDEC', 'dec');
define('STARR', 'r');
define('STARMAGNITUDE', 'mag');
define('STARMAXMAGNITUDE', 'max');
define('STARMINMAGNITUDE', 'min');
define('STAREPOCH', 'epoch');
define('STAREPOCHMIN', 'epochmin');
define('STAREPOCHMAX', 'epochmax');
define('STARPERIOD', 'period');
define('STARDATE', 'date');
define('STARDATEMINIMUM', 'datemin');
define('STARDATEMAXIMUM', 'datemax');
define('STARASCPERIOD', 'periodasc');
define('STARDESCPERIOD', 'perioddesc');
define('STARCONJ', 'conjunction');
define('STARDIST', 'angdist');
define('SUNRISE', 'sunrise');
define('SUNSET', 'sunset');
define('SUNTRANSIT', 'transit');
define('CIVILTWILIGHTBEG', 'civil_twilight_begin');
define('CIVILTWILIGHTEND', 'civil_twilight_end');
define('NAUTTWILIGHTBEG', 'nautical_twilight_begin');
define('NAUTTWILIGHTEND', 'nautical_twilight_end');
define('ASTROTWILIGHTBEG', 'astronomical_twilight_begin');
define('ASTROTWILIGHTEND', 'astronomical_twilight_end');
define('STARRISE', 'rise');
define('STARSET', 'set');
define('STARSTART', 'illumination');
define('STARSTOP', 'extinction');
define('LATITUDE', 'lat');
define('LONGITUDE', 'lng');
define('ALTITUDE', 'alt');
define('DEG2RAD', M_PI / 180 );
define('RAD2DEG', 180 / M_PI );
define('HR2RAD', M_PI / 12 );
define('HR2DEG', 180 / 12 );
define('RAD2HR', 12 / M_PI );

// Orbital elements
define('OESUN', 'Sun');
define('OEMER', 'Mercury');
define('OEVEN', 'Venus');
define('OEMAR', 'Mars');
define('OEJUP', 'Jupiter');
define('OESAT', 'Saturn');
define('OEURA', 'Uranus');
define('OENEP', 'Neptune');
define('OENAME','name'); 	// Name of the sun, moon, planet, or asteroid
define('OEMLNG','L'); 	// L	M + w1  = mean longitude
define('OEMDST','a'); 	// a	semi-major axis, or mean distance from Sun
define('OEECTY','e'); 	// e	eccentricity (0=circle, 0-1=ellipse, 1=parabola)
define('OEINCL','i'); 	// i	inclination to the ecliptic (plane of the Earth's orbit)
define('OENODE','N'); 	// N	longitude of the ascending node
define('OEPERD','P'); 	// P	a ^ 1.5 = orbital period (years if a is in AU, astronomical units)
define('OEPERI','w'); 	// w	argument of perihelion
define('OEMANM','M'); 	// M 	mean anomaly (0 at perihelion; increases uniformly with time)
define('OERASC', 'ra');
define('OEDECL', 'decl');

// Satellite viewing elements
define('SATMAG','mag');	// The brightness of the pass (beginning or peak)
define('SATRISE','illumination');	// Rise object, captures the altitude, azimuth, and timestamp at the point that satellite illuminates
define('SATPEAK','peak');	// Peak object, captures the altitude, azimuth, and timestamp at the point that satellite reaches highest altitude
define('SATSET','extinction');	// Set object, captures the altitude, azimuth, and timestamp at the point that satellite extinguishes
define('SATALT','alt');
define('SATAZ','az');
define('SATTIME','time');
define('SATURL','url');	// Link object, captures the link back to the website (for more details) and the pass id
define('SATID','id');
define('SATDATE','date');
define('SATTIMEFORMAT','H:i:s');	// Time format used for the regular satellite passes

// ISS International Space Station viewing elements
define('ISSDATEFORMAT','j M');	// Date format used for ISS passes

// Iridium Flare viewing elements
define('FLRDATETIMEFORMAT','M j, H:i:s');	// Time format used for Iridium Flare satellite passes
define('FLRDISTANCE','flaredist');	// Distance to flare centre
define('FLRMAGCTR','magatctr');	// Brightness at flare centre	
define('FLRSUNALT','sunaltitude');	// Sun altitude

// Meteor shower viewing elements
define('MTRDATEFORMAT','M j');	// Date format used for Meteor events
define('MTRTIMEFORMAT','H\hi');	// Time format used for Meteor events
define('MTRNAME', 'showername');
define('MTRRA', 'ra');
define('MTRDEC', 'dec');
define('MTRTIME', 'time');
define('MTRBEGIN', 'begin');
define('MTRPEAK', 'peak');
define('MTREND', 'end');
define('MTRMAG', 'mag');
define('MTRRATE', 'rate');

// Eclipse viewing elements
define('ECLDATEFORMAT','Y M j');	// Date format used for Eclipse events
define('ECLTIMEFORMAT','H:i:s');	// Time format used for the Eclipse events
define('ECLSDFORMAT','i\ms');	// Time format used for Solar Eclipse duration
define('ECLLDFORMAT','H\hi');	// Time format used for Lunar Eclipse duration
define('ECLDATE', 'date');
define('ECLBEGIN', 'begin');
define('ECLPEAK', 'peak');
define('ECLEND', 'end');
define('ECLTYPE', 'type');
define('ECLDURATION', 'duration');

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'suncalc.php';  // Calculate the times and positions for the Sun and the Moon
include 'radec.class.php';  // Calculate the altitude and azimuth for all celestial objects

header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>' . chr(13) . chr(10);   
echo '<?xml-stylesheet type="text/css" href="astroforecastCSS.css"?>' . chr(13) . chr(10);
echo '<astroforecast>' . chr(13) . chr(10);

$intAuroraLevel = 6;
if( isset( $_GET['aurora'] ) ) { $intAuroraLevel = (integer) $_GET['aurora']; }

$fltDistCheck = 20.0;
if( isset( $_GET['dist'] ) ) {
	if( is_numeric( $_GET['dist'] ) ) { $fltDistCheck = (float)$_GET['dist']; }
}

$fltLatLngAlt = [ LATITUDE => 43.6515952, LONGITUDE => -79.5692296, ALTITUDE => 113 ];
if( isset( $_GET['latlng'] ) ) {
	if( is_string( $_GET['latlng'] ) ) {
		if( strpos( $_GET['latlng'], ',' ) > 0 ) {
			$fltLatLngAlt[LATITUDE] = (float) substr( $_GET['latlng'], 0, strpos( $_GET['latlng'], ',' ) );
			$fltLatLngAlt[LONGITUDE] = (float) substr( $_GET['latlng'], strpos( $_GET['latlng'], ',' ) +1 );

			$strGoogleAPIkey='AIzaSyBHVGRox44tkYoHtFFIfzSjxuN6IJ-0MgI'; // Elevation key
			// TODO: https://maps.googleapis.com/maps/api/elevation/json?locations=39.7391536,-104.9847034&key=
			$strLatLng = urlencode( (string)$fltLatLngAlt[LATITUDE] .','. (string)$fltLatLngAlt[LONGITUDE] );
			$objRequest = file_get_contents('https://maps.googleapis.com/maps/api/elevation/json?locations=' . $strLatLng . '&key=' . $strGoogleAPIkey );
			$arrJson = json_decode($objRequest, true);	// Return a JSON array
			// TODO: DELETE LINE
			if( !isset($arrJson['results'][0]['elevation'])  )  
			{
			  echo ' <comment><![CDATA['; var_dump( $arrJson ); echo ' ]]>' . chr(13) . chr(10) . ' </comment>' . chr(13) . chr(10);
			}  

			$fltLatLngAlt[ALTITUDE] = ( isset($arrJson['results'][0]['elevation']) ? $arrJson['results'][0]['elevation'] :113 );
		}
	}
}
elseif( isset( $_GET['latlngalt'] ) ) {
	if( is_string( $_GET['latlngalt'] ) ) {
		if( strpos( $_GET['latlngalt'], ',' ) > 0 ) {
			$intPosComma1 = strpos( $_GET['latlngalt'], ',' );
			$fltLatLngAlt[LATITUDE] = (float) substr( $_GET['latlngalt'], 0, $intPosComma1 );
			$intPosComma2 = strpos( $_GET['latlngalt'], ',', $intPosComma1 +1 );
			$fltLatLngAlt[LONGITUDE] = (float) substr( $_GET['latlngalt'], $intPosComma1 +1, $intPosComma2 - $intPosComma1 -1 );
			$fltLatLngAlt[ALTITUDE] = (float) substr( $_GET['latlngalt'], $intPosComma2 +1 );
		}
	}
}
else {
	$strGoogleAPIkey='AIzaSyBHVGRox44tkYoHtFFIfzSjxuN6IJ-0MgI'; // Elevation key
	// TODO: https://maps.googleapis.com/maps/api/elevation/json?locations=39.7391536,-104.9847034&key=
	$strLatLng = urlencode( (string)$fltLatLngAlt[LATITUDE] .','. (string)$fltLatLngAlt[LONGITUDE] );
	$objRequest = file_get_contents('https://maps.googleapis.com/maps/api/elevation/json?locations=' . $strLatLng . '&key=' . $strGoogleAPIkey );
	$arrJson = json_decode($objRequest, true);	// Return a JSON array
	// TODO: DELETE LINE  var_dump( $arrJson );
	if( !isset($arrJson['results'][0]['elevation']) )  
			{
			  echo ' <comment><![CDATA['; var_dump( $arrJson ); echo ' ]]>' . chr(13) . chr(10) . ' </comment>' . chr(13) . chr(10);
			}  

	$fltLatLngAlt[ALTITUDE] = ( isset($arrJson['results'][0]['elevation']) ? $arrJson['results'][0]['elevation'] :113 );
}
$strGoogleAPIkey='AIzaSyA6vk3c-4SnKzfrzK2qLgH9hQ-NEkLZsIU'; // Timezone key
// TODO: https://maps.googleapis.com/maps/api/timezone/json?location=39.7391536,-104.9847034&timestamp=&key=
$strLatLng = urlencode( (string)$fltLatLngAlt[LATITUDE] .','. (string)$fltLatLngAlt[LONGITUDE] );
$objRequest = file_get_contents('https://maps.googleapis.com/maps/api/timezone/json?location=' . $strLatLng . '&timestamp=' . time() . '&key=' . $strGoogleAPIkey );
$arrJson = json_decode($objRequest, true);	// Return a JSON array
// TODO: DELETE LINE
if( !isset($arrJson['timeZoneId']) )  
			{
			  echo ' <comment><![CDATA['; var_dump( $arrJson ); echo ' ]]>' . chr(13) . chr(10) . ' </comment>' . chr(13) . chr(10);
			}  

$blnVerbose = isset( $_GET['verbose'] );
$tzDisplay = new DateTimeZone( isset($arrJson['timeZoneId']) ? $arrJson['timeZoneId'] : TZUTC ); 
$dteDisplayTime = new DateTime();
$dteDisplayTime->setTimezone( $tzDisplay );
if( isset( $_GET['datetime'] ) ) {
	$strDateTime = $_GET['datetime'];
	if (preg_match("/^[0-9]{4}(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])([0-1][0-9]|2[0-3])[0-5][1-9][0-5][1-9]$/",$strDateTime) && $blnVerbose) {
		echo ' <comment><![CDATA['; print_r( $strDateTime ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
    	$dteDisplayTime = DateTime::createFromFormat('YmdHis',$strDateTime);
	} elseif (preg_match("/^(\d{4})-(\d{1,2})-(\d{1,2})$/", $strDateTime, $m)
        ? checkdate(intval($m[2]), intval($m[3]), intval($m[1])) && $blnVerbose
        : false) {
		echo ' <comment><![CDATA['; print_r( $strDateTime ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$dteDisplayTime = DateTime::createFromFormat ('Y-m-d',$strDateTime);
	}
	$dteDisplayTime->setTimezone( $tzDisplay );
	if( $blnVerbose ) {
		echo ' <comment><![CDATA['; print_r( $dteDisplayTime ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
	}
}

if( $blnVerbose ) {
	echo ' <comment>'. 'Lat:' . $fltLatLngAlt[LATITUDE] . ' Lng:' . $fltLatLngAlt[LONGITUDE] . ' Alt:' . $fltLatLngAlt[ALTITUDE] .'</comment>' . chr(13) . chr(10);
}

/***
 *
 *  Planetary alignment
 *
 *  Calculate the positions of the planets and the moon, and rise and set times
 *  Create event if the planets are within 5deg of each other or bright stars (use $_GET parameter &dist=)
 *
 *  Classes and methods
 *    Celestial object - RA, Dec, Alt(), Az(), Rise(), Set(), Distance( RA, Dec )
 *    Planet -  RA(), Dec(), Alt(), Az(), Rise(), Set(), Distance( RA, Dec )
 *    Moon -  RA(), Dec(), Alt(), Az(), Rise(), Set(), Distance( RA, Dec )
 *    Sun -  RA(), Dec(), Alt(), Az(), Rise(), Set(), Distance( RA, Dec )
 *
 ***/

abstract class CelestialObject {
	protected $strObjectName;
	protected $arrObjectElements;
	protected $fltRightAscension;
	protected $fltDeclination;
	protected $fltMagnitude;
	protected $tsRise;
	protected $tsSet;
	protected $tsPeak;
	protected $tsBttm;

	abstract public function build ();

	function __construct( $strName, $arrElements ) {
		if( is_string( $strName ) ) $this->strObjectName = $strName;
		if( is_array( $arrElements ) ) $this->arrObjectElements = $arrElements;
		$this->build ();
	}
	
	public function name() { return $this->strObjectName; }
	public function ra() { return $this->fltRightAscension; }
	public function dec() { return $this->fltDeclination; }
	public function mag() { return $this->fltMagnitude; }
	public function rise() { return $this->tsRise; }
	public function set() { return $this->tsSet; }
	public function peak() { return $this->tsPeak; }
	
	public function distance( $fltRa, $fltDec ) {
		// Find the distance between this object and another object to see if it is nearby, as in a few degrees.
		// Objects near the ecliptic are practically cartesian for this purpose.
		$fltDistRa = ( $fltRa - $this->fltRightAscension ) * cos( DEG2RAD * ( $fltDec + $this->fltDeclination ) / 2 );
		$fltDistDec = $fltDec - $this->fltDeclination;
		// echo ' <comment>'. ' Ra:' . $this->fltRightAscension. ' Dec:' . $this->fltDeclination . ' Ra:' . $fltRa. ' Dec:' . $fltDec . '</comment>' . chr(13) . chr(10);
		return( sqrt( pow( $fltDistRa * HR2DEG, 2 ) + pow( $fltDistDec, 2 ) ) );
	}
	
	public function setRiseSet( $fltLat, $fltLng ) {
		//  use radec.class object to find approximate rise or set time.
		// if the object never rises, set times to highest point
		// if the object never sets, set times to lowest point
		$objRaDec = new radec( $fltLat, $fltLng );
		$dteLocalTime = new DateTime;
		
		// The Time has to be set before doing anything with the radec object
		$objRaDec->settimefromtimestamp($dteLocalTime->getTimestamp());
		$objRaDec->setradec( $this->fltRightAscension, $this->fltDeclination );
		
		// Look around the clock an hour at a time until the object crosses the horizon, peaks, and bottoms
		$dteClock = clone $dteLocalTime;
		// Start the clock at sunrise for consistency.
		$dteClock->setTimestamp( date_sun_info( $dteClock->getTimestamp(), $fltLat, $fltLng )[SUNRISE] );
		$this->tsRise = $dteClock->getTimestamp();
		$this->tsSet = $dteClock->getTimestamp();
		$this->tsPeak = $dteClock->getTimestamp();
		$this->tsBttm = $dteClock->getTimestamp();
		$objRaDec->settimefromtimestamp($dteClock->getTimestamp());
		$fltPrevAlt = $objRaDec->getALT($dteLocalTime->getTimestamp());
		$fltMaxAlt = $fltPrevAlt;
		$fltMinAlt = $fltPrevAlt;
		// ToDone:  Trace the clock calculations.
		for( $intClock = 0; $intClock < 24; $intClock++ ) {
			$fltCurrAlt = $objRaDec->getALT($dteClock->getTimestamp());
		// ToDone:  echo ' <comment>'. ' Alt:' . $fltCurrAlt . ' Az:' . $objRaDec->getAZ($dteClock->getTimestamp()) . ' HA:' . $objRaDec->getHA($dteClock->getTimestamp()) . ' Name:' . $this->strObjectName . '</comment>' . chr(13) . chr(10);
			if( $fltCurrAlt > $fltPrevAlt ) { // ascending
				// check if the horizon is crossed
				if( $fltCurrAlt > 0.0 && $fltPrevAlt <= 0.0 ) { 
					$this->tsRise = $dteClock->getTimestamp();
				}
				// check if the altitude has peaked
				if( $fltCurrAlt > $fltMaxAlt ) { 
					$this->tsPeak = $dteClock->getTimestamp();
					$fltMaxAlt = $fltCurrAlt;
				}
			}
			else { // descending
				// check if the horizon is crossed
				if( $fltCurrAlt <= 0.0 && $fltPrevAlt > 0.0 ) { 
					$this->tsSet = $dteClock->getTimestamp();
				}
				// check if the altitude has bottomed
				if( $fltCurrAlt < $fltMinAlt ) { 
					$this->tsBttm = $dteClock->getTimestamp();
					$fltMinAlt = $fltCurrAlt;
				}
			}
				
			$dteClock->add( DateInterval::createFromDateString('1 hour') );
			$objRaDec->settimefromtimestamp($dteClock->getTimestamp());
			$fltPrevAlt = $fltCurrAlt;
		}
		// ToDo:  Refine the calculations from the hour to the second
		// if the horizon was never crossed, nevermind refining
		
		if( $fltMaxAlt <= 0.0 ) {
			// the object never rose 
			$this->tsRise = $this->tsPeak; 
			$this->tsSet = $this->tsPeak;
		}
		if( $fltMinAlt > 0.0 ) {
			// the object never set 
			$this->tsRise = $this->tsBttm; 
			$this->tsSet = $this->tsBttm;
		}

	}

}

/* 
interface radec
 = new radec( lat, lng)
setradec( ra, dec );
settimefromtimestamp($timestamp)
getALT($timestamp)
getAZ($timestamp)
*/
 
class FixedStar extends CelestialObject {
	public function build() {
		if( isset( $this->arrObjectElements[ STARRA ] ) ) $this->fltRightAscension = $this->arrObjectElements[ STARRA ];
		if( isset( $this->arrObjectElements[ STARDEC ] ) ) $this->fltDeclination = $this->arrObjectElements[ STARDEC ];
		if( isset( $this->arrObjectElements[ STARMAGNITUDE ] ) ) $this->fltMagnitude = $this->arrObjectElements[ STARMAGNITUDE ];
		// echo ' <comment>'. ' Ra:' . $this->fltRightAscension. ' Dec:' . $this->fltDeclination . ' Mag:' . $this->fltMagnitude. ' Name:' . $this->strObjectName . '</comment>' . chr(13) . chr(10);
		// echo ' <comment><![CDATA['; print_r( $this->arrObjectElements ); echo ' ]]>' . chr(13) . chr(10) . ' </comment>' . chr(13) . chr(10);
	}
}

class Planet extends CelestialObject {

	// Orbital elements by Peter Hayes http://www.aphayes.pwp.blueyonder.co.uk/
	private $orbitalElements = array (
		OESUN => [ OENAME => OESUN, STARMAGNITUDE => -26.3,	// The Sun
		OEMLNG => [100.466449,  36000.7698231,    0.00030368,    0.000000021],
		OEMDST => [  1.000001018,   0.0,          0.0,           0.0],
		OEECTY => [  0.01670862,   -0.000042037, -0.0000001236,  0.00000000004],
		OEINCL => [  0.0,           0.0,          0.0,           0.0],
		OENODE => [  0.0,           0.0,          0.0,           0.0],
		OEPERD => [102.937348,      1.7195269,    0.00045962,    0.000000499]],

		OEMER => [ OENAME => OEMER, STARMAGNITUDE =>  0.5,	// Mercury
		OEMLNG => [252.250906, 149474.0722491,    0.00030397,    0.000000018],
		OEMDST => [  0.387098310,   0.0,          0.0,           0.0],
		OEECTY => [  0.20563175,    0.000020406, -0.0000000284, -0.00000000017],
		OEINCL => [  7.004986,      0.0018215,   -0.00001809,    0.000000053],
		OENODE => [ 48.330893,      1.1861890,    0.00017587,    0.000000211],
		OEPERD => [ 77.456119,      1.5564775,    0.00029589,    0.000000056]],

		OEVEN => [ OENAME => OEVEN, STARMAGNITUDE => -3.8,	// Venus
		OEMLNG => [181.979801,  58519.2130302,    0.00031060,    0.000000015],
		OEMDST => [  0.723329820,   0.0,          0.0,           0.0],
		OEECTY => [  0.00677188,   -0.000047766,  0.0000000975,  0.00000000044],
		OEINCL => [  3.394662,      0.0010037,   -0.00000088,   -0.000000007],
		OENODE => [ 76.679920,      0.9011190,    0.00040665,   -0.000000080],
		OEPERD => [131.563707,      1.4022188,   -0.00107337,   -0.000005315]],

		OEMAR => [ OENAME => OEMAR, STARMAGNITUDE => 1.8,	// Mars
		OEMLNG => [355.433275,  19141.6964746,    0.00031097,    0.000000015],
		OEMDST => [  1.523679342,   0.0,          0.0,           0.0],
		OEECTY => [  0.09340062,    0.000090483, -0.0000000806, -0.00000000035],
		OEINCL => [  1.849726,     -0.0006010,    0.00001276,   -0.000000006],
		OENODE => [ 49.558093,      0.7720923,    0.00001605,    0.000002325],
		OEPERD => [336.060234,      1.8410331,    0.00013515,    0.000000318]],

		OEJUP => [ OENAME => OEJUP, STARMAGNITUDE => -1.6,	// Jupiter
		OEMLNG => [ 34.351484,   3036.3027889,    0.00022374,    0.000000025],
		OEMDST => [  5.202603191,   0.0000001913, 0.0,           0.0],
		OEECTY => [  0.04849485,    0.000163244, -0.0000004719, -0.00000000197],
		OEINCL => [  1.303270,     -0.0054966,    0.00000465,   -0.000000004],
		OENODE => [100.464441,      1.0209550,    0.00040117,    0.000000569],
		OEPERD => [ 14.331309,      1.6126668,    0.00103127,   -0.000004569]],

		OESAT => [ OENAME => OESAT, STARMAGNITUDE => 0.4,	// Saturn
		OEMLNG => [ 50.077471,   1223.5110141,    0.00051952,   -0.000000003],
		OEMDST => [  9.554909596,  -0.0000021389, 0.0,           0.0],
		OEECTY => [  0.05550862,   -0.000346818, -0.0000006456,  0.00000000338],
		OEINCL => [  2.488878,     -0.0037363,   -0.00001516,    0.000000089],
		OENODE => [113.665524,      0.8770979,   -0.00012067,   -0.000002380],
		OEPERD => [ 93.056787,      1.9637694,    0.00083757,    0.000004899]],

		OEURA => [ OENAME => OEURA, STARMAGNITUDE => 5.7,	// Uranus
		OEMLNG => [314.055005,    429.8640561,    0.00030434,    0.000000026],
		OEMDST => [ 19.218446062,  -0.0000000372, 0.00000000098, 0.0],
		OEECTY => [  0.04629590,   -0.000027337,  0.0000000790,  0.00000000025],
		OEINCL => [  0.773196,      0.0007744,    0.00003749,   -0.000000092],
		OENODE => [ 74.005947,      0.5211258,    0.00133982,    0.000018516],
		OEPERD => [173.005159,      1.4863784,    0.00021450,    0.000000433]],

		OENEP => [ OENAME => OENEP, STARMAGNITUDE => 7.8,	// Neptune
		OEMLNG => [304.348665,    219.8833092,    0.00030926,    0.000000018],
		OEMDST => [ 30.110386869,  -0.0000001663, 0.00000000069, 0.0],
		OEECTY => [  0.00898809,    0.000006408, -0.0000000008, -0.00000000005],
		OEINCL => [  1.769952,     -0.0093082,   -0.00000708,    0.000000028],
		OENODE => [131.784057,      1.1022057,    0.00026006,   -0.000000636],
		OEPERD => [ 48.123691,      1.4262677,    0.00037918,   -0.000000003]]
   		);
	private function rev($deg){return $deg-floor($deg/360.0)*360.0;}
	
	private function helios($p,$obs) {
		// heliocentric xyz for planet p and julian date jd
		$jd=$obs; // (int)($obs+0.5);
		// echo '<comment>' . chr(13) . chr(10);
		$T=($jd-2451545.0)/36525;
		$T2=$T*$T;
		$T3=$T2*$T;
		// longitude of ascending node (in degrees)
		$N=$this->rev($p[OENODE][0]+$p[OENODE][1]*$T+$p[OENODE][2]*$T2+$p[OENODE][3]*$T3);
		// inclination (in degrees)
		$i=$p[OEINCL][0]+$p[OEINCL][1]*$T+$p[OEINCL][2]*$T2+$p[OEINCL][3]*$T3;
		// Mean longitude (in degrees)
		$L=$this->rev($p[OEMLNG][0]+$p[OEMLNG][1]*$T+$p[OEMLNG][2]*$T2+$p[OEMLNG][3]*$T3);
		// semimajor axis (in A.U.)
		$a=$p[OEMDST][0]+$p[OEMDST][1]*$T+$p[OEMDST][2]*$T2+$p[OEMDST][3]*$T3;
		// eccentricity
		$e=$p[OEECTY][0]+$p[OEECTY][1]*$T+$p[OEECTY][2]*$T2+$p[OEECTY][3]*$T3;
		// longitude of perihelion (in degrees)
		$P=$this->rev($p[OEPERD][0]+$p[OEPERD][1]*$T+$p[OEPERD][2]*$T2+$p[OEPERD][3]*$T3);
		$M=$this->rev($L-$P);
		$w=$this->rev($L-$N-$M);
		// echo $p[OENAME] .' $jd:'. $jd .' $a:'. $a .' $e:'. $e .' $P:'. $P .' $M:'. $M .' $w:'. $w. chr(13) . chr(10);
		// if( ( $p->name=='Jupiter' || $p->name=='Earth' ) && $jd==2451542 ){alert($p->name +' a:'+ a +' e:'+ e +' P:'+ P +' M:'+ M +' w:'+ w);}
		// Eccentric anomaly (in degrees)
		$E0=$M+(RAD2DEG)*$e*sin($M*DEG2RAD)*(1+$e*cos($M*DEG2RAD));
		$E=$E0-($E0-(RAD2DEG)*$e*sin($E0*DEG2RAD)-$M)/(1-$e*cos($E0*DEG2RAD));
		// echo $p[OENAME] .' $E:'. $E .' $E0:'. $E0. chr(13) . chr(10);
		$cntStop = 100;
		while (abs($E0-$E) > 0.0005) {
			$E0=$E;
			$E=$E0-($E0-(RAD2DEG)*$e*sin($E0*DEG2RAD)-$M)/(1-$e*cos($E0*DEG2RAD));
			// if( ( p.name=='Jupiter' || p.name=='Earth' ) && jd==2458147 ){alert(p.name +' E:'+ E +' E0:'+ E0 );}
			if( --$cntStop <= 0 ) break;
		};
		$x=$a*(cos($E*DEG2RAD)-$e);
		$y=$a*sqrt(1-$e*$e)*sin($E*DEG2RAD);
		$r=sqrt($x*$x+$y*$y);
		$v=$this->rev(atan2($y,$x)*RAD2DEG);
		// echo $p[OENAME] .' $E:'. $E .' $x:'. $x .' $y:'. $y .' $r:'. $r .' $v:'. $v. chr(13) . chr(10);
		// if( ( $p->name=='Jupiter' || $p->name=='Earth' ) && $jd==2451542 ){alert($p->name +' E:'+ E +' x:'+ x +' y:'+ y +' r:'+ r +' v:'+ v);}
		// Heliocentric Ecliptic Rectangular Coordinates
		$xeclip=$r*(cos($N*DEG2RAD)*cos(($v+$w)*DEG2RAD)-sin($N*DEG2RAD)*sin(($v+$w)*DEG2RAD)*cos($i*DEG2RAD));
		$yeclip=$r*(sin($N*DEG2RAD)*cos(($v+$w)*DEG2RAD)+cos($N*DEG2RAD)*sin(($v+$w)*DEG2RAD)*cos($i*DEG2RAD));
		$zeclip=$r*sin(($v+$w)*DEG2RAD)*sin($i*DEG2RAD);
		// echo $p[OENAME] .' $xeclip:'. $xeclip .' $yeclip:'. $yeclip .' $zeclip:'. $zeclip. chr(13) . chr(10);
		// if( ( $p->name=='Jupiter' || $p->name=='Earth' ) && $jd==2451542 ){alert($p->name +' xeclip:'+ xeclip +' yeclip:'+ yeclip +' zeclip:'+ zeclip);}
		// echo '</comment>' . chr(13) . chr(10);
		return([$xeclip,$yeclip,$zeclip]);
	}

	private function radecr($obj,$base,$jd) {
		// radecr returns ra, dec and earth distance
		// obj and base are Heliocentric Ecliptic Rectangular Coordinates
		// for the object and earth and jd is the julian date
		// Equatorial co-ordinates
		$x=$obj[0];
		$y=$obj[1];
		$z=$obj[2];
		// julian date
		$jdobs=$jd;
		// Obliquity of Ecliptic
		$obl=23.4393-3.563E-7*($jdobs-2451543.5);
		//if( jd==2451542 ){alert(' base0:'+ base[0] +' base1:'+ base[1] +' base2:'+ base[2]);}
		// Convert to Geocentric co-ordinates
		$x1=$x-$base[0];
		$y1=($y-$base[1])*cos($obl*DEG2RAD)-($z-$base[2])*sin($obl*DEG2RAD);
		$z1=($y-$base[1])*sin($obl*DEG2RAD)+($z-$base[2])*cos($obl*DEG2RAD);
		// if( jd==2451542 ){alert(' obl:'+ obl +' x1:'+ x1 +' y1:'+ y1 +' z1:'+ z1);}
		// RA and dec
		$ra=$this->rev(atan2($y1,$x1)*RAD2DEG)/15.0;
		$dec=atan2($z1,sqrt($x1*$x1+$y1*$y1))*RAD2DEG;
		// Earth distance
		$r=sqrt($x1*$x1+$y1*$y1+$z1*$z1);
		return([STARRA => $ra, STARDEC => $dec, STARR => $r]);
	}


	public function build() {
		if( isset( $this->arrObjectElements[ STARDATE ] ) ) { 
			$dteLocalTime = $this->arrObjectElements[ STARDATE ]; 
		} 
		else { 		
			$dteLocalTime = new DateTime;
		}
		if( isset( $this->arrObjectElements[ LATITUDE ] ) ) $fltLat = $this->arrObjectElements[ LATITUDE ];
		if( isset( $this->arrObjectElements[ LONGITUDE ] ) ) $fltLng = $this->arrObjectElements[ LONGITUDE ];
		
		// Calculate Julian date and sidereal time (in degrees) from current date and longitude
		$jdobs = toJulian($dteLocalTime);  
		if( isset( $this->orbitalElements[$this->strObjectName] ) ) {
		/*
		  echo ' <comment><![CDATA[Sun: '; 
		  print_r( $this->orbitalElements[OESUN] );
		  echo ' '. $this->name() .': '; 
		  print_r( $this->orbitalElements[$this->strObjectName] );
		  echo ' ]]>' . chr(13) . chr(10) . ' </comment>' . chr(13) . chr(10);  */
	//	$lst = local_sidereal(dateofinterestY,(dateofinterestM+1),(dateofinterestD),-utcHours+12,longitude); //Noon
	//	var d=dayno(year,month,day,hours);
	//	var lst=(98.9818+0.985647352*d+hours*15+lon);
	//	return rev(lst)/15;
			$earth_xyz=$this->helios( $this->orbitalElements[OESUN], $jdobs );
			$planet_xyz=$this->helios( $this->orbitalElements[$this->strObjectName], $jdobs );
			// if( $this->orbitalElements[$this->strObjectName].name=='Jupiter' && jdobs==2451542 ){alert( planets[pnum].name+' x:'+ planet_xyz[0] +' y:'+ planet_xyz[1] +' z:'+ planet_xyz[2] +' jdobs:'+ jdobs);}
			$radec=$this->radecr( $planet_xyz, $earth_xyz, $jdobs );
			$this->fltRightAscension = $radec[ STARRA ];
			$this->fltDeclination = $radec[ STARDEC ];
			$this->fltMagnitude = $this->orbitalElements[$this->strObjectName][ STARMAGNITUDE ];
		}
		else {
			if( isset( $this->arrObjectElements[ STARRA ] ) ) $this->fltRightAscension = $this->arrObjectElements[ STARRA ];
			if( isset( $this->arrObjectElements[ STARDEC ] ) ) $this->fltDeclination = $this->arrObjectElements[ STARDEC ];
			if( isset( $this->arrObjectElements[ STARMAGNITUDE ] ) ) $this->fltMagnitude = $this->arrObjectElements[ STARMAGNITUDE ];
			if( $blnVerbose ) {
		  echo ' <comment>'. ' Ra:' . $this->fltRightAscension. ' Dec:' . $this->fltDeclination . ' Mag:' . $this->fltMagnitude. ' Name:' . $this->strObjectName . '</comment>' . chr(13) . chr(10);
		  echo ' <comment><![CDATA['; print_r( $this->arrObjectElements ); echo ' ]]>' . chr(13) . chr(10) . ' </comment>' . chr(13) . chr(10);
		  echo ' <comment><![CDATA['; print_r( $this->orbitalElements ); echo ' ]]>' . chr(13) . chr(10) . ' </comment>' . chr(13) . chr(10); 
		  }
		}
	}
}

class Moon extends CelestialObject {
	public function build() {
		if( isset( $this->arrObjectElements[ STARDATE ] ) ) { 
			$dteLocalTime = $this->arrObjectElements[ STARDATE ]; 
		} 
		else { 		
			$dteLocalTime = new DateTime;
		}
		if( isset( $this->arrObjectElements[ LATITUDE ] ) ) $fltLat = $this->arrObjectElements[ LATITUDE ];
		if( isset( $this->arrObjectElements[ LONGITUDE ] ) ) $fltLng = $this->arrObjectElements[ LONGITUDE ];
		if( isset( $this->arrObjectElements[ LATITUDE ] ) && isset( $this->arrObjectElements[ LONGITUDE ] ) ) { 
			$this->setRiseSet( $this->arrObjectElements[ LATITUDE ], $this->arrObjectElements[ LONGITUDE ] ); 
		}  
		// echo ' <comment>'. ' Moon Ra:' . $this->fltRightAscension. ' Dec:' . $this->fltDeclination . ' Mag:' . $this->fltMagnitude. ' Name:' . $this->strObjectName . '</comment>' . chr(13) . chr(10);
		// echo ' <comment><![CDATA[ arrObjectElements '; print_r( $this->arrObjectElements ); echo ' ]]>' . chr(13) . chr(10) . ' </comment>' . chr(13) . chr(10);
	}
	
	public function setRiseSet( $fltLat, $fltLng ) {
		$dteTime = new DateTime;
		$arrSunInfo = date_sun_info( time(), $fltLat, $fltLng ); 
		$dteTransit = new DateTime;
		$dteTransit->setTimestamp( $arrSunInfo[SUNTRANSIT] );
		$arrSunInfo = date_sun_info( time()+86400, $fltLat, $fltLng ); 
		$dteOpposition = new DateTime;
		$dteOpposition->setTimestamp( floor( ($dteTransit->getTimestamp() + $arrSunInfo[SUNTRANSIT]) / 2 ) );
		$mooncalc = new SunCalc( $dteTransit, $fltLat, $fltLng );
		//echo '<comment><![CDATA[ SunCalc '; print_r( $mooncalc ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		//echo '<comment><![CDATA[ MoonCalc '; print_r( $mooncalc->getMoonTimes() ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		//echo '<comment><![CDATA[ MoonPos '; print_r( $mooncalc->getMoonPosition($dteOpposition) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		$arrMoonTimes = $mooncalc->getMoonTimes();
		$dteRise = ( isset( $arrMoonTimes['alwaysUp'] ) ? $dteTransit :
					 ( isset( $arrMoonTimes['moonrise'] ) ? $arrMoonTimes['moonrise'] : 
					  $dteOpposition ) );
		$dteSet = ( isset( $arrMoonTimes['alwaysUp'] ) ? $dteTransit :
					 ( isset( $arrMoonTimes['moonset'] ) ? $arrMoonTimes['moonset'] : 
					  $dteOpposition ) );
		$this->tsRise = $dteRise->getTimestamp();
		$this->tsSet = $dteSet->getTimestamp();
		$this->tsPeak = $dteRise->getTimestamp();
		$this->tsBttm = $dteSet->getTimestamp();
		$this->tsPeak = $arrMoonTimes['moonpeak']->getTimestamp();
		$this->tsBttm = $arrMoonTimes['moonbottom']->getTimestamp();
		// echo '<comment><![CDATA[ setRiseSet '; print_r( $arrMoonTimes ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$this->fltRightAscension = $mooncalc->getMoonPosition($dteOpposition)->ra * RAD2HR;
		$this->fltRightAscension = $this->fltRightAscension - floor( $this->fltRightAscension / 24 ) * 24;
		$this->fltDeclination = $mooncalc->getMoonPosition($dteOpposition)->dec * RAD2DEG;
		
		$arrIllumination = $mooncalc->getMoonIllumination();
		$this->fltMagnitude = $arrIllumination['fraction'] * -12.74;
		$arrNames = array( 'New Moon', 'Waxing Crescent', 'First Quarter', 'Waxing Gibbous', 'Full Moon', 'Waning Gibbous', 'Third Quarter', 'Waning Crescent', 'New Moon' );
	}
}

class Sun extends Planet {
}

/***
 *
 *  Aurora forecast
 *
 *  Grab the aurora forecast from the University of Alaska website. Keep a copy for three hours.
 *  There is a night forecast and a one hour forecast
 *
 *  Classes and methods
 *    Aurora - Date, nightForecast, hourForecast
 *
 ***/

class Aurora {
	private $intNightForecast = 0;
	private $strNightForecast = '';
	private $intHourForecast = 0;
	private $strHourForecast = '';

	function __construct() {
		$tzAurora = new DateTimeZone( TZAURORA );
		$dteAurora = new DateTime( 'now', $tzAurora );
		// Every three hours
		/* switch( floor(( (integer) $dteAurora->format('H') +1)/3)*3 - (integer) $dteAurora->format('H') ) {
		case -1:
			$dteAurora->sub( DateInterval::createFromDateString('1 hour') );
			break;
		case 1:
			$dteAurora->add( DateInterval::createFromDateString('1 hour') );
			break;
		} */
		// Afternoons only (4 hours ahead of Eastern Time)
		switch( (integer) $dteAurora->format('H') ) {
		case 0:
			$dteAurora->sub( DateInterval::createFromDateString('1 hour') );
			break;
		case 1:
			$dteAurora->sub( DateInterval::createFromDateString('2 hour') );
			break;
		case 2:
			$dteAurora->add( DateInterval::createFromDateString('3 hour') );
			break;
		case 3:
			$dteAurora->sub( DateInterval::createFromDateString('4 hour') );
			break;
		case 4:
			$dteAurora->sub( DateInterval::createFromDateString('5 hour') );
			break;
		case 5:
			$dteAurora->add( DateInterval::createFromDateString('6 hour') );
			break;
		case 6:
			$dteAurora->sub( DateInterval::createFromDateString('7 hour') );
			break;
		case 7:
			$dteAurora->sub( DateInterval::createFromDateString('8 hour') );
			break;
		case 8:
			$dteAurora->sub( DateInterval::createFromDateString('9 hour') );
			break;
		case 9:
			$dteAurora->sub( DateInterval::createFromDateString('10 hour') );
			break;
		case 10:
			$dteAurora->add( DateInterval::createFromDateString('10 hour') );
			break;
		case 11:
			$dteAurora->add( DateInterval::createFromDateString('10 hour') );
			break;
		case 12:
		case 13:
		case 14:
		case 15:
		case 16:
		case 17:
			break;
		case 18:
			$dteAurora->sub( DateInterval::createFromDateString('1 hour') );
			break;
		case 19:
			$dteAurora->sub( DateInterval::createFromDateString('2 hour') );
			break;
		case 20:
			$dteAurora->sub( DateInterval::createFromDateString('3 hour') );
			break;
		case 21:
			$dteAurora->sub( DateInterval::createFromDateString('4 hour') );
			break;
		case 22:
			$dteAurora->sub( DateInterval::createFromDateString('5 hour') );
			break;
		case 23:
			break;
		}
	
		$strDateAurora = $dteAurora->format('Ymd');
		$strPathURL = 'http://www.gi.alaska.edu/AuroraForecast/NorthAmerica';
		$strSuffixLocal = '.html';
		$strHour = substr( '00' . $dteAurora->format('H'), -2); 
		$strForecast = '_AuroraForecast';
		$strURLSrc = $strPathURL . '/' . $strDateAurora;
		$strLocalSrc = DATADIR . '/' . $strDateAurora . $strHour . $strForecast . $strSuffixLocal;
		$strLocalAddress = $strDateAurora . $strHour . $strForecast . $strSuffixLocal;
		$arrFiles = scandir( DATADIR );
		if( array_search( $strLocalAddress, $arrFiles ) === false ) { 
			$strAurora = file_get_contents( $strURLSrc );
			$fpAurora = fopen( $strLocalSrc, 'w' );	// Create a new file and post the contents of the Aurora forecast file.
			fwrite( $fpAurora, $strAurora );
			fclose( $fpAurora );
		}
		else {
			$strAurora = file_get_contents( $strLocalSrc );
		}
		$strPathURL = 'http://www.spaceweather.com/index.php';
		$strForecast = '_SpaceWeatherForecast';
		$strLocalSrc = DATADIR . '/' . $strDateAurora . $strHour . $strForecast . $strSuffixLocal;
		$strLocalAddress = $strDateAurora . $strHour . $strForecast . $strSuffixLocal;
		if( array_search( $strLocalAddress, $arrFiles ) === false ) { 
			$strAurora = file_get_contents( $strPathURL );
			$fpAurora = fopen( $strLocalSrc, 'w' );	// Create a new file and post the contents of the Aurora forecast file.
			fwrite( $fpAurora, $strAurora );
			fclose( $fpAurora );
		}
		else {
			$strAurora = file_get_contents( $strLocalSrc );
		}
		// echo '<comment>'. htmlspecialchars( $strAurora, ENT_XML1 | ENT_COMPAT, 'UTF-8') .'</comment>' . chr(13) . chr(10);
		$this->intNightForecast = $this->parse_results_page( $strAurora, '<div class="levels">', '</div>', '<span class="level-', 'l">' );
		if( strlen($this->intNightForecast ) == 1 ) {		
			$this->strNightForecast = $this->parse_results_page( $strAurora, '<div class="levels">', '</div>', '<span class="level-'. $this->intNightForecast .'l">', ':</span>' );
		} else {
			$this->strNightForecast = '0';
			$this->intNightForecast = 0;
		}
		$this->intHourForecast = $this->parse_results_page( $strAurora, 'Short-Term (1-hour) Forecast', '</div>', '<span class="level-', 'l">' );
		if( strlen($this->intHourForecast) == 1 ) {
			$this->strHourForecast = $this->parse_results_page( $strAurora, 'Short-Term (1-hour) Forecast', '</div>', '<span class="level-'. $this->intHourForecast .'l">', ':</span>' );
		} else {
			$this->strHourForecast = '0';
			$this->intHourForecast = 0;
		}
		
		// Here is an alternate as the University of Alaska site seems to be lagging and inaccurate
		$strPathURL = 'http://www.spaceweather.com/index.php';
		$strForecast = '_SpaceWeatherForecast';
		$strLocalSrc = DATADIR . '/' . $strDateAurora . $strHour . $strForecast . $strSuffixLocal;
		$strLocalAddress = $strDateAurora . $strHour . $strForecast . $strSuffixLocal;
		if( array_search( $strLocalAddress, $arrFiles ) === false ) { 
			$strAurora = file_get_contents( $strPathURL );
			$fpAurora = fopen( $strLocalSrc, 'w' );	// Create a new file and post the contents of the Aurora forecast file.
			fwrite( $fpAurora, $strAurora );
			fclose( $fpAurora );
		}
		else {
			$strAurora = file_get_contents( $strLocalSrc );
		}
		$this->intNightForecast = trim( $this->parse_results_page( $strAurora, '<span class="solarWindText"><b>Planetary K-index</b>', '</span>', '24-hr max: <strong> Kp=', '</strong>' ) );
		if( strlen($this->intNightForecast) == 1 ) {		
			$this->strNightForecast = $this->parse_results_page( $strAurora, '24-hr max: <strong> Kp=', '<br>', '\'>', '</font>' );
		} else {
			$this->strNightForecast = '0';
			$this->intNightForecast = 0;
		}
		$this->intHourForecast =trim( $this->parse_results_page( $strAurora, '<span class="solarWindText"><b>Planetary K-index</b>', '</span>', 'Now: <strong>Kp=<font face="Arial, Helvetica, sans-serif">', '</font>')  );
		if( strlen( $this->intHourForecast) == 1 ) {
			$this->strHourForecast = $this->parse_results_page( $strAurora, 'Now: <strong>Kp=', '<br>', '\'>', '</font>' );
		} else {
			$this->strHourForecast = '0';
			$this->intHourForecast = 0;
		}
		
	}
	
	private function parse_results_page( $strPageCapture, $strPageStart, $strPageEnd, $strParseStart, $strParseEnd ) {
		$intParseL = strpos( $strPageCapture, $strPageStart ) + strlen( $strPageStart );
		$intParseR = strpos( $strPageCapture, $strPageEnd, $intParseL );
		$strNibble = ( $intParseR > $intParseL ? substr( $strPageCapture, $intParseL , $intParseR - $intParseL ) : substr( $strPageCapture, $intParseL ) );
		$intParseL = strpos( $strNibble, $strParseStart ) + strlen( $strParseStart );
			/* echo '<comment>' . chr(13) . chr(10); 
			echo '$strPageCapture: ' . substr($strPageCapture, 0, 200) . chr(13) . chr(10);
			echo '$strPageStart: ' . $strPageStart . chr(13) . chr(10);
			echo '$strPageEnd: ' . $strPageEnd . chr(13) . chr(10);
			echo '$strParseStart: ' . $strParseStart . chr(13) . chr(10);
			echo '$strParseEnd: ' . $strParseEnd . chr(13) . chr(10);
			echo '$intParseL: ' . $intParseL . chr(13) . chr(10);
			echo '$strNibble: ' . $strNibble . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
			*/
		if( $intParseL < strlen( $strNibble ) ) {
			$intParseR = strpos( $strNibble, $strParseEnd, $intParseL );
			$strParse = ( $intParseR > $intParseL ? substr( $strNibble, $intParseL , $intParseR - $intParseL ) : substr( $strNibble, $intParseL ) );
		} else {
			$strParse = '';
		}
		return( $strParse );
	}

	public function nightForecast() { return( [ AURORALEVEL => $this->intNightForecast, AURORANAME => $this->strNightForecast ] ); }
	public function hourForecast() { return( [ AURORALEVEL => $this->intHourForecast, AURORANAME => $this->strHourForecast ] ); }
	
}

/***
 *
 *  Satelite lookup
 *
 *  Grab the times for Iridium satelites or ISS
 *  Requires the exact lat/lng/alt or the timing will be off
 *
 *  Classes and methods
 *    ISS(lat,lng,alt) - DateTime, rise[alt,az,time], peak[alt,az,time], set[alt,az,time]
 *    Iridium(lat,lng,alt) - DateTime, rise[alt,az,time], flash[alt,az,time], set[alt,az,time]
 *
 ***/

class Satellite_Pass {
/***
 * Inputs:
 *   strVehicle: The name of the vehicle (does not have to be unique)
 *   arrElements: The rest of the parameters (for foreward compatibility with ISS and Iridium Flare objects)
 *     fltMagnitude: The floating point value of the brightness of the vehicle
 *     objRise
 *     objPeak
 *     objSet
 *
 ***/
	protected $strObjectName;	// The name of the satellite vehicle 
	protected $dtePass;	// DateTime object of the pass (beginning or peak)
	protected $fltMagnitude;	// The brightness of the pass (beginning or peak)
	protected $objRise;	// Rise object, captures the altitude, azimuth, and timestamp at the point that satellite illuminates
	protected $objPeak;	// Peak object, captures the altitude, azimuth, and timestamp at the point that satellite reaches highest altitude
	protected $objSet;	// Set object, captures the altitude, azimuth, and timestamp at the point that satellite extinguishes 
	protected $objLink;	// Link object, captures the link back to the website (for more details) and the pass id
	
	protected $arrObjectElements;	// The rest of the input parameters
	
	
	function build() {
		$this->dtePass = new DateTime;
		if( isset( $this->arrObjectElements[TZTIMEZONE] ) ) {
			switch( $this->arrObjectElements[TZTIMEZONE] ) {
			case 'NST':
				$tzPass = new DateTimeZone( TZNST );
				break;
			case 'AST':
				$tzPass = new DateTimeZone( TZAST );
				break;
			case 'EST':
				$tzPass = new DateTimeZone( TZEST );
				break;
			case 'CST':
				$tzPass = new DateTimeZone( TZCST );
				break;
			case 'MST':
				$tzPass = new DateTimeZone( TZMST );
				break;
			case 'PST':
				$tzPass = new DateTimeZone( TZPST );
				break;
			case 'NAST':
				$tzPass = new DateTimeZone( TZNAST );
				break;
			default:
				$tzPass = new DateTimeZone( TZUTC );
				break;
			}
			$this->dtePass->setTimezone( $tzPass );
		}		
		
		if( isset( $this->arrObjectElements[SATMAG] ) ) $this->fltMagnitude = $this->arrObjectElements[SATMAG];
		if( isset( $this->arrObjectElements[SATRISE] ) ) {
			if( isset( $this->arrObjectElements[SATRISE][SATALT] ) ) $this->objRise[SATALT] = (integer)$this->arrObjectElements[SATRISE][SATALT];
			if( isset( $this->arrObjectElements[SATRISE][SATAZ] ) ) $this->objRise[SATAZ] = $this->arrObjectElements[SATRISE][SATAZ];
			if( isset( $this->arrObjectElements[SATRISE][SATTIME] ) ) {
				$this->objRise[SATTIME] = clone $this->dtePass;
				$this->objRise[SATTIME]->setTime( (integer)substr( $this->arrObjectElements[SATRISE][SATTIME],0,2), (integer)substr($this->arrObjectElements[SATRISE][SATTIME],3,2), (integer)substr($this->arrObjectElements[SATRISE][SATTIME],6,2) );
			}
		}
		if( isset( $this->arrObjectElements[SATPEAK] ) ) {
			if( isset( $this->arrObjectElements[SATPEAK][SATALT] ) ) $this->objPeak[SATALT] = (integer)$this->arrObjectElements[SATPEAK][SATALT];
			if( isset( $this->arrObjectElements[SATPEAK][SATAZ] ) ) $this->objPeak[SATAZ] = $this->arrObjectElements[SATPEAK][SATAZ];
			if( isset( $this->arrObjectElements[SATPEAK][SATTIME] ) ) {
				$this->dtePass->setTime( (integer)substr($this->arrObjectElements[SATPEAK][SATTIME],0,2), (integer)substr($this->arrObjectElements[SATPEAK][SATTIME],3,2), (integer)substr($this->arrObjectElements[SATPEAK][SATTIME],6,2) );
				$this->objPeak[SATTIME] = clone $this->dtePass;
			} 
		}
		if( isset( $this->arrObjectElements[SATSET] ) ) {
			if( isset( $this->arrObjectElements[SATSET][SATALT] ) ) $this->objSet[SATALT] = (integer)$this->arrObjectElements[SATSET][SATALT];
			if( isset( $this->arrObjectElements[SATSET][SATAZ] ) ) $this->objSet[SATAZ] = $this->arrObjectElements[SATSET][SATAZ];
			if( isset( $this->arrObjectElements[SATSET][SATTIME] ) ) {
				$this->objSet[SATTIME] = clone $this->dtePass;
				$this->objSet[SATTIME]->setTime( (integer)substr($this->arrObjectElements[SATSET][SATTIME],0,2), (integer)substr($this->arrObjectElements[SATSET][SATTIME],3,2), (integer)substr($this->arrObjectElements[SATSET][SATTIME],6,2) );
			} 
		}
		if( isset( $this->arrObjectElements[SATURL] ) ) $this->objLink[SATURL] = 'http://heavens-above.com/'. $this->arrObjectElements[SATURL];
		if( isset( $this->arrObjectElements[SATID] ) ) $this->objLink[SATID] = $this->arrObjectElements[SATID];
	}
	
	function __construct( $strVehicle, $arrElements ) {
		if( is_string( $strVehicle ) ) $this->strObjectName = $strVehicle;
		if( is_array( $arrElements ) ) $this->arrObjectElements = $arrElements;
		if( isset( $this->strObjectName ) && isset( $this->arrObjectElements ) ) $this->build ();
	}

	public function getMagnitude() { return( $this->fltMagnitude ); }	// The brightness of the pass (beginning or peak)
	public function getRise() { return( $this->objRise ); }	// Rise object, captures the altitude, azimuth, and timestamp at the point that satellite illuminates
	public function getPeak() { return( $this->objPeak ); }	// Peak object, captures the altitude, azimuth, and timestamp at the point that satellite reaches highest altitude
	public function getSet() { return( $this->objSet ); }	// Set object, captures the altitude, azimuth, and timestamp at the point that satellite extinguishes 
	public function rise() { return( $this->objRise[SATTIME] ); }	// Rise DateTime object only
	public function peak() { return( $this->objPeak[SATTIME] ); }	// Peak DateTime object only
	public function set() { return( $this->objSet[SATTIME] ); }	// Set DateTime object only
	public function getLink() { return( $this->objLink ); }	// Link object, captures the link back to the website (for more details) and the pass id
	public function getVehicle() { return( $this->strObjectName ); }	// The name of the satellite vehicle 
} 

class ISS_Pass extends Satellite_Pass {
	function build() {
		$this->dtePass = new DateTime;
		if( isset( $this->arrObjectElements[TZTIMEZONE] ) ) {
			switch( $this->arrObjectElements[TZTIMEZONE] ) {
			case 'NST':
				$tzPass = new DateTimeZone( TZNST );
				break;
			case 'AST':
				$tzPass = new DateTimeZone( TZAST );
				break;
			case 'EST':
				$tzPass = new DateTimeZone( TZEST );
				break;
			case 'CST':
				$tzPass = new DateTimeZone( TZCST );
				break;
			case 'MST':
				$tzPass = new DateTimeZone( TZMST );
				break;
			case 'PST':
				$tzPass = new DateTimeZone( TZPST );
				break;
			case 'NAST':
				$tzPass = new DateTimeZone( TZNAST );
				break;
			default:
				$tzPass = new DateTimeZone( TZUTC );
				break;
			}
			$this->dtePass->setTimezone( $tzPass );
		}		
		
		if( isset( $this->arrObjectElements[SATMAG] ) ) $this->fltMagnitude = $this->arrObjectElements[SATMAG];
		if( isset( $this->arrObjectElements[SATDATE] ) ) $this->dtePass = DateTime::createFromFormat( ISSDATEFORMAT, $this->arrObjectElements[SATDATE], $tzPass);
		if( isset( $this->arrObjectElements[SATRISE] ) ) {
			if( isset( $this->arrObjectElements[SATRISE][SATALT] ) ) $this->objRise[SATALT] = (integer)$this->arrObjectElements[SATRISE][SATALT];
			if( isset( $this->arrObjectElements[SATRISE][SATAZ] ) ) $this->objRise[SATAZ] = $this->arrObjectElements[SATRISE][SATAZ];
			if( isset( $this->arrObjectElements[SATRISE][SATTIME] ) ) {
				$this->objRise[SATTIME] = clone $this->dtePass;
				$this->objRise[SATTIME]->setTime( (integer)substr( $this->arrObjectElements[SATRISE][SATTIME],0,2), (integer)substr($this->arrObjectElements[SATRISE][SATTIME],3,2), (integer)substr($this->arrObjectElements[SATRISE][SATTIME],6,2) );
			}
		}
		if( isset( $this->arrObjectElements[SATPEAK] ) ) {
			if( isset( $this->arrObjectElements[SATPEAK][SATALT] ) ) $this->objPeak[SATALT] = (integer)$this->arrObjectElements[SATPEAK][SATALT];
			if( isset( $this->arrObjectElements[SATPEAK][SATAZ] ) ) $this->objPeak[SATAZ] = $this->arrObjectElements[SATPEAK][SATAZ];
			if( isset( $this->arrObjectElements[SATPEAK][SATTIME] ) ) {
				$this->dtePass->setTime( (integer)substr($this->arrObjectElements[SATPEAK][SATTIME],0,2), (integer)substr($this->arrObjectElements[SATPEAK][SATTIME],3,2), (integer)substr($this->arrObjectElements[SATPEAK][SATTIME],6,2) );
				$this->objPeak[SATTIME] = clone $this->dtePass;
			} 
		}
		if( isset( $this->arrObjectElements[SATSET] ) ) {
			if( isset( $this->arrObjectElements[SATSET][SATALT] ) ) $this->objSet[SATALT] = (integer)$this->arrObjectElements[SATSET][SATALT];
			if( isset( $this->arrObjectElements[SATSET][SATAZ] ) ) $this->objSet[SATAZ] = $this->arrObjectElements[SATSET][SATAZ];
			if( isset( $this->arrObjectElements[SATSET][SATTIME] ) ) {
				$this->objSet[SATTIME] = clone $this->dtePass;
				$this->objSet[SATTIME]->setTime( (integer)substr($this->arrObjectElements[SATSET][SATTIME],0,2), (integer)substr($this->arrObjectElements[SATSET][SATTIME],3,2), (integer)substr($this->arrObjectElements[SATSET][SATTIME],6,2) );
			} 
		}
		if( isset( $this->arrObjectElements[SATURL] ) ) $this->objLink[SATURL] = 'http://heavens-above.com/'. $this->arrObjectElements[SATURL];
		if( isset( $this->arrObjectElements[SATID] ) ) $this->objLink[SATID] = $this->arrObjectElements[SATID];
	}
} 

class Iridium_Pass extends Satellite_Pass {
	private $intDistanceToFlare;
	private $fltMagnitudeAtCentre;
	private $intSunAltitude;
	
	function build() {
		$this->dtePass = new DateTime;
		// echo ' <comment><![CDATA['; print_r( $this->arrObjectElements ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		if( isset( $this->arrObjectElements[TZTIMEZONE] ) ) {
			switch( $this->arrObjectElements[TZTIMEZONE] ) {
			case 'NST':
				$tzPass = new DateTimeZone( TZNST );
				break;
			case 'AST':
				$tzPass = new DateTimeZone( TZAST );
				break;
			case 'EST':
				$tzPass = new DateTimeZone( TZEST );
				break;
			case 'CST':
				$tzPass = new DateTimeZone( TZCST );
				break;
			case 'MST':
				$tzPass = new DateTimeZone( TZMST );
				break;
			case 'PST':
				$tzPass = new DateTimeZone( TZPST );
				break;
			case 'NAST':
				$tzPass = new DateTimeZone( TZNAST );
				break;
			default:
				$tzPass = new DateTimeZone( TZUTC );
				break;
			}
			$this->dtePass->setTimezone( $tzPass );
		}
		else { $tzPass = new DateTimeZone( TZUTC ); }		
		// echo ' <comment><![CDATA['. $this->dtePass->format(FLRDATETIMEFORMAT) .']]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		if( isset( $this->arrObjectElements[SATMAG] ) ) $this->fltMagnitude = $this->arrObjectElements[SATMAG];
		if( isset( $this->arrObjectElements[SATRISE] ) ) {
			if( isset( $this->arrObjectElements[SATRISE][SATALT] ) ) $this->objRise[SATALT] = (integer)$this->arrObjectElements[SATRISE][SATALT];
			if( isset( $this->arrObjectElements[SATRISE][SATAZ] ) ) $this->objRise[SATAZ] = $this->arrObjectElements[SATRISE][SATAZ];
			if( isset( $this->arrObjectElements[SATRISE][SATTIME] ) ) {
				$this->objRise[SATTIME] = DateTime::createFromFormat( FLRDATETIMEFORMAT, $this->arrObjectElements[SATRISE][SATTIME], $tzPass);
				// $this->objRise[SATTIME] = DateTime::createFromFormat( 'M j, Y', substr( $this->arrObjectElements[SATRISE][SATTIME], 0, strlen($this->arrObjectElements[SATRISE][SATTIME])-8 ). '2017' );
				// echo ' <comment><![CDATA[' . chr(13) . chr(10) . $this->arrObjectElements[SATRISE][SATTIME] . chr(13) . chr(10). $this->objRise[SATTIME]->format(FLRDATETIMEFORMAT) . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
				// echo ' <comment><![CDATA['; print_r( $this->objRise[SATTIME] ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
			}
		}
		if( isset( $this->arrObjectElements[SATPEAK] ) ) {
			if( isset( $this->arrObjectElements[SATPEAK][SATALT] ) ) $this->objPeak[SATALT] = (integer)$this->arrObjectElements[SATPEAK][SATALT];
			if( isset( $this->arrObjectElements[SATPEAK][SATAZ] ) ) $this->objPeak[SATAZ] = $this->arrObjectElements[SATPEAK][SATAZ];
			if( isset( $this->arrObjectElements[SATPEAK][SATTIME] ) ) {
				$this->dtePass = DateTime::createFromFormat( FLRDATETIMEFORMAT, $this->arrObjectElements[SATPEAK][SATTIME], $tzPass );
				$this->objPeak[SATTIME] = clone $this->dtePass;
			} 
		}
		if( isset( $this->arrObjectElements[SATSET] ) ) {
			if( isset( $this->arrObjectElements[SATSET][SATALT] ) ) $this->objSet[SATALT] = (integer)$this->arrObjectElements[SATSET][SATALT];
			if( isset( $this->arrObjectElements[SATSET][SATAZ] ) ) $this->objSet[SATAZ] = $this->arrObjectElements[SATSET][SATAZ];
			if( isset( $this->arrObjectElements[SATSET][SATTIME] ) ) {
				$this->objSet[SATTIME] = DateTime::createFromFormat( FLRDATETIMEFORMAT, $this->arrObjectElements[SATSET][SATTIME], $tzPass );
			} 
		}
		if( isset( $this->arrObjectElements[SATURL] ) ) $this->objLink[SATURL] = 'http://heavens-above.com/'. $this->arrObjectElements[SATURL];
		if( isset( $this->arrObjectElements[SATID] ) ) $this->objLink[SATID] = $this->arrObjectElements[SATID];
		
		if( isset( $this->arrObjectElements[FLRDISTANCE] ) ) $this->intDistanceToFlare = $this->arrObjectElements[FLRDISTANCE];
		if( isset( $this->arrObjectElements[FLRMAGCTR] ) ) $this->fltMagnitudeAtCentre = $this->arrObjectElements[FLRMAGCTR];
		if( isset( $this->arrObjectElements[FLRSUNALT] ) ) $this->intSunAltitude = $this->arrObjectElements[FLRSUNALT];
	}

	public function getDistance() { return( $this->intDistanceToFlare ); }	// The distance on the ground to the centre of the flare 
	public function getCntrMag() { return( $this->fltMagnitudeAtCentre ); }	// The brightness of the flare at that location
	public function getSunAlt() { return( $this->intSunAltitude ); }	// The altitude of the sun at the time of the flare
} 

class Satellite_Page {

	private $arrPasses;
	
	// Takes the page and returns an array of satellite passes (Satellite_Page)
	function parse_results_page( $strHTML ){ 
		$strBOF = 'Minimum brightness:'; // Find the text that always precedes useful data (Beginning of File)
		$strEOF = 'Developed and maintained by ';	// Find the text that always follows useful data (End of File)
		$strBOT = '<table class="standardTable">';	// Find the correct table to parse
		$strEOT = '</table>';	// Find the end of the table
		$strBOD = '<tbody>';	// Find the start of the data
		$strEOD = '</tbody>';	// Find the end of the data
		$strBOL = '<tr class="clickableRow"';	// Find the beginning of the line
		$strEOL = '</tr>';	// Find the end of the line 
		
		// Trim all of the fat (Satellite_Page)
		$intParseL = strpos( $strHTML, $strBOF ) + strlen( $strBOF );
		$intParseR = strpos( $strHTML, $strEOF, $intParseL );
		$strData = substr( $strHTML, $intParseL, $intParseR - $intParseL );
		
		$intParseL = strpos( $strData, $strBOT ) + strlen( $strBOT );
		$intParseR = strpos( $strData, $strEOT, $intParseL );
		$strData = substr( $strData, $intParseL, $intParseR - $intParseL );
		
		$intParseL = strpos( $strData, $strBOD ) + strlen( $strBOD );
		$intParseR = strpos( $strData, $strEOD, $intParseL );
		$strData = substr( $strData, $intParseL, $intParseR - $intParseL );
		
		$arrParse = [];
		$intParseL = strpos( $strData, $strBOL );
		do {
			$intParseR = strpos( $strData, $strEOL, $intParseL );
			if( $intParseL < $intParseR ) $arrParse[] = substr( $strData, $intParseL, $intParseR - $intParseL ); 
			$intParseL = strpos( $strData, $strBOL, $intParseR );
		} while( $intParseL !== false );
		
		return( $arrParse ); }
	// Takes the satellite pass and parses out the data within (Satellite_Page)
	function parse_results_line( $strLine ){ 
		// echo ' <comment><![CDATA[' . chr(13) . chr(10) . $strLine . chr(13) . chr(10). ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$arrParse = [];
		
		$strBOD = htmlspecialchars( '<td>' );	// Find the start of the satellite name
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the satellite name
		$intParseL = strpos( $strLine, $strBOD ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATID] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $arrParse[SATID] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		//echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the brightness data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the brightness data
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		//echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATMAG] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		//echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $arrParse[SATMAG] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		//echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the rise time
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the rise time
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATRISE] = [];
		$arrParse[SATRISE][SATTIME] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		//echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $arrParse[SATRISE][SATTIME] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		//echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data '�</td>'
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL ) -2;
		$arrParse[SATRISE][SATALT] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		//echo ' <comment><![CDATA['; print_r( $arrParse[SATRISE][SATALT] ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		//echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATRISE][SATAZ] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATAZ. $arrParse[SATRISE][SATAZ] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center"><a href="' );	// Find the start of the data
		$strEOD = htmlspecialchars( '">' );	// Find the end of the data
		$intParseL = strpos( $strLine, $strBOD, $intParseR-1 ) + strlen( $strBOD );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATURL] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $arrParse[SATURL] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '">' );	// Find the start of the data
		$strEOD = htmlspecialchars( '</a></td>' );	// Find the end of the data
		$intParseL = strpos( $strLine, $strBOD, $intParseR-1 ) + strlen( $strBOD );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATPEAK] = [];
		$arrParse[SATPEAK][SATTIME] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATTIME. $arrParse[SATPEAK][SATTIME] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data '�</td>'
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$intParseR = strpos( $strLine, $strEOD, $intParseL ) -2;
		$arrParse[SATPEAK][SATALT] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATALT .':'. $arrParse[SATPEAK][SATALT] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATPEAK][SATAZ] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATAZ .':'. $arrParse[SATPEAK][SATAZ] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td>' );	// Find the start of the data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATSET] = [];
		$arrParse[SATSET][SATTIME] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		//echo ' <comment><![CDATA['; print_r( $arrParse[SATSET][SATTIME] ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATTIME. $arrParse[SATSET][SATTIME] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data '�</td>' 
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL ) -2;
		$arrParse[SATSET][SATALT] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		//echo ' <comment><![CDATA['; print_r( $arrParse[SATSET][SATALT] ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATALT. $arrParse[SATSET][SATALT] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATSET][SATAZ] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		//echo ' <comment><![CDATA['; print_r( $arrParse[SATSET][SATAZ] ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATAZ. $arrParse[SATSET][SATAZ] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		return( $arrParse ); }
	
	function build($fltLat,$fltLng,$fltAlt,$strTimeZone,$strTwilightTime,$tzViewing) {
		
		// prepare the satellite information (Satellite_Page)
		$dteViewTime = new DateTime( 'now', $tzViewing );
		$dteNoonTime = new DateTime( 'noon', $tzViewing );
		if( $strTwilightTime=='morning' ) {
			$strAMPM = 'AM';
			$intDayOfMonth = (integer)$dteViewTime->format('d') + 1;
			$intMonthCode = (integer)$dteViewTime->format('Y')*12 + (integer)$dteViewTime->format('m') -1;
			switch( (integer)$dteViewTime->format('m') ) {
			case 2:
				if( (integer)$dteViewTime->format('d')>29 ) { 
					$intMonthCode++;
				} 
				elseif( (integer)$dteViewTime->format('d')>28 && (integer)$dteViewTime->format('Y')%4 > 0 ) { 
					$intMonthCode++;
				}
				break;
			case 4:
			case 6:
			case 9:
			case 11:
				if( (integer)$dteViewTime->format('d')>30 ) { 
					$intMonthCode++;
				} 
				break;
			case 1:
			case 3:
			case 5:
			case 7:
			case 8:
			case 10:
			case 12:
			otherwise:
				if( (integer)$dteViewTime->format('d')>31 ) { 
					$intMonthCode++;
				} 
				break;
			}
			// echo ' <comment><![CDATA['.$dteViewTime->format('Y') .':'. $dteViewTime->format('m'). ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		}
		else {
			$strAMPM = 'PM';
			$intDayOfMonth = (integer)$dteViewTime->format('d');
			$intMonthCode = (integer)$dteViewTime->format('Y')*12 + (integer)$dteViewTime->format('m') -1;
			// echo ' <comment><![CDATA['.$dteViewTime->format('Y') .':'. $dteViewTime->format('m'). ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		}
		$strMagnitude = '3.0';
		// get the satellite information (Satellite_Page)
		if( !isset($strLocation) ) $strLocation='Astronomy+Forecast';
		$strURLSrc = 'http://heavens-above.com/AllSats.aspx?lat='. (string)$fltLat .'&lng='. (string)$fltLng .'&loc='. $strLocation .'&alt='. (string)$fltAlt .'&tz='. $strTimeZone;
		/*
		Heavens Above uses form protection which makes altering the page difficult and the settings moot.
		For morning satellite predictions, check back in the morning before sunrise.
		The page returns evening views until midnight or morning views until noon.
		// $strURLSrc .= '&ctl00$cph1$TimeSelectionControl1$comboMonth='. (string)$intMonthCode;
		// $strURLSrc .= '&ctl00$cph1$TimeSelectionControl1$comboDay='. (string)$intDayOfMonth;
		// $strURLSrc .= '&ctl00$cph1$TimeSelectionControl1$radioAMPM='. $strAMPM;
		// $strURLSrc .= '&ctl00$cph1$radioButtonsMag='. $strMagnitude;
		$strURLSrc .= '&ctl00%24cph1%24TimeSelectionControl1%24comboMonth='. (string)$intMonthCode;
		$strURLSrc .= '&ctl00%24cph1%24TimeSelectionControl1%24comboDay='. (string)$intDayOfMonth;
		$strURLSrc .= '&ctl00%24cph1%24TimeSelectionControl1%24radioAMPM='. $strAMPM;
		$strURLSrc .= '&ctl00%24cph1%24radioButtonsMag='. $strMagnitude;
		*/
		// echo ' <comment><![CDATA['; print_r( $strURLSrc ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strPageCapture = file_post_contents( $strURLSrc );
		// echo ' <comment><![CDATA['; print_r( $strPageCapture ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		$arrParseLine = [];
		foreach( $this->parse_results_page( $strPageCapture ) AS $strLineCapture ) {
			$arrParseLine[] = $this->parse_results_line( htmlspecialchars( $strLineCapture ) );
		}
		// echo ' <comment><![CDATA['; print_r( $arrParseLine ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		foreach( $arrParseLine AS $arrSatellite ) {
			if( !isset( $arrSatellite[TZTIMEZONE] ) ) $arrSatellite[TZTIMEZONE] = $strTimeZone;
			if( (float)$arrSatellite[SATMAG] <= 2.0 ) $this->arrPasses[] = new Satellite_Pass( $arrSatellite[SATID], $arrSatellite );
		}
	}
	
	function __construct($fltLatLngAltTz, $strTwilightTime = 'evening') {
		// Before you get the data, you need to have the elevation and timezone (Satellite_Page)
		// AND the timezone has to be one of a few
		if( !isset( $fltLatLngAltTz[ALTITUDE] ) ) {
			$strGoogleAPIkey='AIzaSyBHVGRox44tkYoHtFFIfzSjxuN6IJ-0MgI'; // Elevation key
			$strLatLng = urlencode( (string)$fltLatLngAltTz[LATITUDE] .','. (string)$fltLatLngAltTz[LONGITUDE] );
			$objRequest = file_get_contents('https://maps.googleapis.com/maps/api/elevation/json?locations=' . $strLatLng . '&key=' . $strGoogleAPIkey );
			$arrJson = json_decode($objRequest, true);	// Return a JSON array
			// TODO: DELETE LINE
			/*if( !isset($arrJson['results'][0]['elevation']) )
			{
			  echo ' <comment><![CDATA['; var_dump( $arrJson ); echo ' ]]>' . chr(13) . chr(10) . ' </comment>' . chr(13) . chr(10);
			}  */
			$fltLatLngAltTz[ALTITUDE] = ( isset($arrJson['results'][0]['elevation']) ? $arrJson['results'][0]['elevation'] :113 );
		}
		if( !isset( $fltLatLngAltTz[TZTIMEZONE] ) ) {
			$strGoogleAPIkey='AIzaSyA6vk3c-4SnKzfrzK2qLgH9hQ-NEkLZsIU'; // Timezone key
			$strLatLng = urlencode( (string)$fltLatLngAltTz[LATITUDE] .','. (string)$fltLatLngAltTz[LONGITUDE] );
			$objRequest = file_get_contents('https://maps.googleapis.com/maps/api/timezone/json?location=' . $strLatLng . '&timestamp=' . time() . '&key=' . $strGoogleAPIkey );
			$arrJson = json_decode($objRequest, true);	// Return a JSON array
			// TODO: DELETE LINE
			/*if( !isset($arrJson['timeZoneName']) )
			{
			  echo ' <comment><![CDATA['; var_dump( $arrJson ); echo ' ]]>' . chr(13) . chr(10) . ' </comment>' . chr(13) . chr(10);
			}  */

			switch( isset($arrJson['timeZoneName']) ? $arrJson['timeZoneName'] : 'UCT' )  {
			case 'Newfoundland Standard Time':
			case 'Newfoundland Daylight Time':
				$fltLatLngAltTz[TZTIMEZONE] = 'NST'; 	// sic;  the third party data site uses standard time code year round
				$tzLocal = new DateTimeZone( $arrJson['timeZoneId'] );
				break;
			case 'Atlantic Standard Time':
			case 'Atlantic Daylight Time':
				$fltLatLngAltTz[TZTIMEZONE] = 'AST'; 	// sic;  the third party data site uses standard time code year round
				$tzLocal = new DateTimeZone( $arrJson['timeZoneId'] );
				break;
			case 'Eastern Standard Time':
			case 'Eastern Daylight Time':
				$fltLatLngAltTz[TZTIMEZONE] = 'EST'; 	// sic;  the third party data site uses standard time code year round
				$tzLocal = new DateTimeZone( $arrJson['timeZoneId'] );
				break;
			case 'Central Standard Time':
				$tzTemp = new DateTimeZone( TZCST );
				$dteTemp = new DateTime( 'now', $tzTemp );
				if( $dteTemp->format('T') == 'CDT' ) { 
					$fltLatLngAltTz[TZTIMEZONE] = 'MST';
				$tzLocal = new DateTimeZone( $arrJson['timeZoneId'] );
					break;
				} 
			case 'Central Daylight Time':
				$fltLatLngAltTz[TZTIMEZONE] = 'CST'; 	// sic;  the third party data site uses standard time code year round
				$tzLocal = new DateTimeZone( $arrJson['timeZoneId'] );
				break;
			case 'Mountain Standard Time':
				$tzTemp = new DateTimeZone( TZMST );
				$dteTemp = new DateTime( 'now', $tzTemp );
				if( $dteTemp->format('T') == 'MDT' ) { 
					$fltLatLngAltTz[TZTIMEZONE] = 'PST';
				$tzLocal = new DateTimeZone( $arrJson['timeZoneId'] );
					break;
				} 
			case 'Mountain Daylight Time':
				$fltLatLngAltTz[TZTIMEZONE] = 'MST'; 	// sic;  the third party data site uses standard time code year round
				$tzLocal = new DateTimeZone( $arrJson['timeZoneId'] );
				break;
			case 'Pacific Standard Time':
			case 'Pacific Daylight Time':
				$fltLatLngAltTz[TZTIMEZONE] = 'PST'; 	// sic;  the third party data site uses standard time code year round
				$tzLocal = new DateTimeZone( $arrJson['timeZoneId'] );
				break;
			case 'Alaska Standard Time':
			case 'Alaska Daylight Time':
				$fltLatLngAltTz[TZTIMEZONE] = 'NAST'; 	// sic;  the third party data site uses standard time code year round
				$tzLocal = new DateTimeZone( $arrJson['timeZoneId'] );
				break;
			default:
				$fltLatLngAltTz[TZTIMEZONE] = 'UCT'; 	// sic;  the third party data site uses UCT instead of UTC
				$tzLocal = new DateTimeZone( isset( $arrJson['timeZoneId'] ) ? $arrJson['timeZoneId'] : 'UTC' );
			}
		}
		
		$this->build($fltLatLngAltTz[LATITUDE],$fltLatLngAltTz[LONGITUDE],$fltLatLngAltTz[ALTITUDE],$fltLatLngAltTz[TZTIMEZONE], $strTwilightTime, $tzLocal);
	}
	
	public function getPasses(){ return( $this->arrPasses ); }
}

class ISS_Page extends Satellite_Page{

	private $arrPasses;
	
	// Takes the page and returns an array of satellite passes (ISS_Page)
	function parse_results_page( $strHTML ){ 
		$strNOP = 'No visible passes found within the search period';	// Trigger that indicates no useful data
		$strBOF = 'Click on the date to get a star chart and other pass details'; // Find the text that always precedes useful data (Beginning of File)
		$strEOF = 'Developed and maintained by ';	// Find the text that always follows useful data (End of File)
		$strBOT = '<table class="standardTable">';	// Find the correct table to parse
		$strEOT = '</table>';	// Find the end of the table
		$strBOD = '<tbody>';	// Find the start of the data
		$strEOD = '</tbody>';	// Find the end of the data
		$strBOL = '<tr class="clickableRow"';	// Find the beginning of the line
		$strEOL = '</tr>';	// Find the end of the line 
		$arrParse = [];
		
		// Trim all of the fat (ISS_Page)
		if( strpos( $strHTML, $strNOP ) !== false ) return( $arrParse );
		if( strpos( $strHTML, $strEOD ) === false ) {
			$strBOD = '</thead>';
			$strEOD = $strEOT;
		};
		$intParseL = strpos( $strHTML, $strBOF ) + strlen( $strBOF );
		$intParseR = strpos( $strHTML, $strEOF, $intParseL ) + strlen( $strEOF );
		$strData = substr( $strHTML, $intParseL, $intParseR - $intParseL );
		//echo ' <comment><![CDATA[' . chr(13) . chr(10) . $strData . chr(13) . chr(10). ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		$intParseL = strpos( $strData, $strBOT ) + strlen( $strBOT );
		$intParseR = strpos( $strData, $strEOT, $intParseL ) + strlen( $strEOT );
		$strData = substr( $strData, $intParseL, $intParseR - $intParseL );
		//echo ' <comment><![CDATA[' . chr(13) . chr(10) . $strData . chr(13) . chr(10). ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		$intParseL = strpos( $strData, $strBOD ) + strlen( $strBOD );
		$intParseR = strpos( $strData, $strEOD, $intParseL ) + strlen( $strEOD );
		$strData = substr( $strData, $intParseL, $intParseR - $intParseL );
		//echo ' <comment><![CDATA[' . chr(13) . chr(10) . $strData . chr(13) . chr(10). ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		$intParseL = strpos( $strData, $strBOL );
		do {
			$intParseR = strpos( $strData, $strEOL, $intParseL );
			if( $intParseL < $intParseR ) $arrParse[] = substr( $strData, $intParseL, $intParseR - $intParseL ); 
			$intParseL = strpos( $strData, $strBOL, $intParseR );
		// echo ' <comment>' . $intParseL . '</comment>' . chr(13) . chr(10);
		} while( $intParseL !== false );
		
		return( $arrParse ); }
	// Takes the satellite pass and parses out the data within (ISS_Page)
	function parse_results_line( $strLine ){ 
		// echo ' <comment><![CDATA[' . chr(13) . chr(10) . $strLine . chr(13) . chr(10). ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$arrParse = [];
		$arrParse[SATID] = 'ISS';
		
		// echo ' <comment><![CDATA['; print_r( $strLine ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td><a href="' );	// Find the start of the link reference
		$strEOD = htmlspecialchars( '" title=' );	// Find the end of the link reference
		$intParseL = strpos( $strLine, $strBOD ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATURL] = substr( $strLine, $intParseL, $intParseR - $intParseL );

		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '">' );	// Find the start of the pass date
		$strEOD = htmlspecialchars( '</a></td>' );	// Find the end of the pass date
		$intParseL = strpos( $strLine, $strBOD, $intParseR-1 ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATDATE] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $arrParse[SATID] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the brightness data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the brightness data
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		//echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATMAG] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		//echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $arrParse[SATMAG] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td>' );	// Find the start of the rise time
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the rise time
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATRISE] = [];
		$arrParse[SATRISE][SATTIME] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $arrParse[SATRISE][SATTIME] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		//echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data '�</td>'
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL ) -2;
		$arrParse[SATRISE][SATALT] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		//echo ' <comment><![CDATA['; print_r( $arrParse[SATRISE][SATALT] ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		//echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td>' );	// Find the start of the data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATRISE][SATAZ] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATAZ. $arrParse[SATRISE][SATAZ] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td>' );	// Find the start of the data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data
		$intParseL = strpos( $strLine, $strBOD, $intParseR-1 ) + strlen( $strBOD );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATPEAK] = [];
		$arrParse[SATPEAK][SATTIME] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATTIME. $arrParse[SATPEAK][SATTIME] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data '�</td>'
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$intParseR = strpos( $strLine, $strEOD, $intParseL ) -2;
		$arrParse[SATPEAK][SATALT] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATALT .':'. $arrParse[SATPEAK][SATALT] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td>' );	// Find the start of the data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATPEAK][SATAZ] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATAZ .':'. $arrParse[SATPEAK][SATAZ] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td>' );	// Find the start of the data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATSET] = [];
		$arrParse[SATSET][SATTIME] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		//echo ' <comment><![CDATA['; print_r( $arrParse[SATSET][SATTIME] ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATTIME. $arrParse[SATSET][SATTIME] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data '�</td>' 
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL ) -2;
		$arrParse[SATSET][SATALT] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		//echo ' <comment><![CDATA['; print_r( $arrParse[SATSET][SATALT] ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATALT. $arrParse[SATSET][SATALT] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td>' );	// Find the start of the data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATSET][SATAZ] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		//echo ' <comment><![CDATA['; print_r( $arrParse[SATSET][SATAZ] ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATAZ. $arrParse[SATSET][SATAZ] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		return( $arrParse ); }
	
	function build($fltLat,$fltLng,$fltAlt,$strTimeZone,$strTwilightTime,$tzViewing) {
		
		// prepare the satellite information (ISS_Page)
		$dteViewTime = new DateTime( 'now', $tzViewing );
		$dteNoonTime = new DateTime( 'noon', $tzViewing );
		if( $strTwilightTime=='morning' ) {
			$strAMPM = 'AM';
			$intDayOfMonth = (integer)$dteViewTime->format('d') + 1;
			$intMonthCode = (integer)$dteViewTime->format('Y')*12 + (integer)$dteViewTime->format('m') -1;
			switch( (integer)$dteViewTime->format('m') ) {
			case 2:
				if( (integer)$dteViewTime->format('d')>29 ) { 
					$intMonthCode++;
				} 
				elseif( (integer)$dteViewTime->format('d')>28 && (integer)$dteViewTime->format('Y')%4 > 0 ) { 
					$intMonthCode++;
				}
				break;
			case 4:
			case 6:
			case 9:
			case 11:
				if( (integer)$dteViewTime->format('d')>30 ) { 
					$intMonthCode++;
				} 
				break;
			case 1:
			case 3:
			case 5:
			case 7:
			case 8:
			case 10:
			case 12:
			otherwise:
				if( (integer)$dteViewTime->format('d')>31 ) { 
					$intMonthCode++;
				} 
				break;
			}
			// echo ' <comment><![CDATA['.$dteViewTime->format('Y') .':'. $dteViewTime->format('m'). ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		}
		else {
			$strAMPM = 'PM';
			$intDayOfMonth = (integer)$dteViewTime->format('d');
			$intMonthCode = (integer)$dteViewTime->format('Y')*12 + (integer)$dteViewTime->format('m') -1;
			// echo ' <comment><![CDATA['.$dteViewTime->format('Y') .':'. $dteViewTime->format('m'). ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		}
		$strMagnitude = '3.0';
		// get the satellite information (ISS_Page)
		if( !isset($strLocation) ) $strLocation='Astronomy+Forecast';
		$strURLSrc = 'http://heavens-above.com/PassSummary.aspx?satid=25544&lat='. (string)$fltLat .'&lng='. (string)$fltLng .'&loc='. $strLocation .'&alt='. (string)$fltAlt .'&tz='. $strTimeZone;
		/*
		Heavens Above uses form protection which makes altering the page difficult and the settings moot.
		For morning satellite predictions, check back in the morning before sunrise.
		The page returns evening views until midnight or morning views until noon.
		// $strURLSrc .= '&ctl00$cph1$TimeSelectionControl1$comboMonth='. (string)$intMonthCode;
		// $strURLSrc .= '&ctl00$cph1$TimeSelectionControl1$comboDay='. (string)$intDayOfMonth;
		// $strURLSrc .= '&ctl00$cph1$TimeSelectionControl1$radioAMPM='. $strAMPM;
		// $strURLSrc .= '&ctl00$cph1$radioButtonsMag='. $strMagnitude;
		$strURLSrc .= '&ctl00%24cph1%24TimeSelectionControl1%24comboMonth='. (string)$intMonthCode;
		$strURLSrc .= '&ctl00%24cph1%24TimeSelectionControl1%24comboDay='. (string)$intDayOfMonth;
		$strURLSrc .= '&ctl00%24cph1%24TimeSelectionControl1%24radioAMPM='. $strAMPM;
		$strURLSrc .= '&ctl00%24cph1%24radioButtonsMag='. $strMagnitude;
		*/
		// echo ' <comment><![CDATA['; print_r( $strURLSrc ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strPageCapture = file_post_contents( $strURLSrc );
		// echo ' <comment><![CDATA['; print_r( $strPageCapture ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		$arrParseLine = [];
		foreach( $this->parse_results_page( $strPageCapture ) AS $strLineCapture ) {
			$arrParseLine[] = $this->parse_results_line( htmlspecialchars( $strLineCapture ) );
		}
		// echo ' <comment><![CDATA['; print_r( $arrParseLine ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		foreach( $arrParseLine AS $arrSatellite ) {
			if( !isset( $arrSatellite[TZTIMEZONE] ) ) $arrSatellite[TZTIMEZONE] = $strTimeZone;
			if( (float)$arrSatellite[SATMAG] <= 2.0 ) $this->arrPasses[] = new ISS_Pass( $arrSatellite[SATID], $arrSatellite );
		}
	}
	
	public function getPasses(){ return( $this->arrPasses ); }
	
}

class Iridium_Page {
	private $arrPasses;
	// Note that Heavens Above is insanely jealous of its Iridium data. Links will only work if source page is linked first. $strURLSrc
	
	// Takes the page and returns an array of satellite passes (Iridium_Page)
	function parse_results_page( $strHTML ){ 
		$strBOF = 'Clicking on the time of the flare'; // Find the text that always precedes useful data (Beginning of File)
		$strEOF = 'Developed and maintained by ';	// Find the text that always follows useful data (End of File)
		$strBOT = '<table class="standardTable">';	// Find the correct table to parse
		$strEOT = '</table>';	// Find the end of the table
		$strBOD = '<tbody>';	// Find the start of the data
		$strEOD = '</tbody>';	// Find the end of the data
		$strBOL = '<tr class="clickableRow"';	// Find the beginning of the line
		$strEOL = '</tr>';	// Find the end of the line 
		
		// Trim all of the fat (Iridium_Page)
		$intParseL = strpos( $strHTML, $strBOF ) + strlen( $strBOF );
		$intParseR = strpos( $strHTML, $strEOF, $intParseL );
		$strData = substr( $strHTML, $intParseL, $intParseR - $intParseL );
		
		$intParseL = strpos( $strData, $strBOT ) + strlen( $strBOT );
		$intParseR = strpos( $strData, $strEOT, $intParseL );
		$strData = substr( $strData, $intParseL, $intParseR - $intParseL );
		
		$intParseL = strpos( $strData, $strBOD ) + strlen( $strBOD );
		$intParseR = strpos( $strData, $strEOD, $intParseL );
		$strData = substr( $strData, $intParseL, $intParseR - $intParseL );
		
		$arrParse = [];
		$intParseL = strpos( $strData, $strBOL );
		do {
			$intParseR = strpos( $strData, $strEOL, $intParseL );
			if( $intParseL < $intParseR ) $arrParse[] = substr( $strData, $intParseL, $intParseR - $intParseL ); 
			$intParseL = strpos( $strData, $strBOL, $intParseR );
		} while( $intParseL !== false );
		
		return( $arrParse ); }
	// Takes the satellite pass and parses out the data within (Iridium_Page)
	function parse_results_line( $strLine ){ 
		// echo ' <comment><![CDATA[' . chr(13) . chr(10) . $strLine . chr(13) . chr(10). ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$arrParse = [];
		
		//echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td><a href="' );	// Find the start of the link reference
		$strEOD = htmlspecialchars( '">' );	// Find the end of the link reference
		$intParseL = strpos( $strLine, $strBOD ) + strlen( $strBOD );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATURL] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $arrParse[SATURL] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '">' );	// Find the start of the flare time
		$strEOD = htmlspecialchars( '</a></td>' );	// Find the end of the flare time
		$intParseL = strpos( $strLine, $strBOD, $intParseR-1 ) + strlen( $strBOD );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATRISE] = [];
		$arrParse[SATRISE][SATTIME] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		$arrParse[SATPEAK] = [];
		$arrParse[SATPEAK][SATTIME] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		$arrParse[SATSET] = [];
		$arrParse[SATSET][SATTIME] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATTIME .':'. $arrParse[SATPEAK][SATTIME] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the brightness data
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the brightness data
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATMAG] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $arrParse[SATMAG] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		//echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the altitude
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the altitude '�</td>'
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL ) -2;
		$arrParse[SATRISE][SATALT] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		$arrParse[SATPEAK][SATALT] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		$arrParse[SATSET][SATALT] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $arrParse[SATPEAK][SATALT] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		//echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the data
		$strEOD = htmlspecialchars( ' (' );	// Find the end of the azimuth '� (S)</td>' OR '� (SW)</td>' OR '� (NNE)</td>'
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL ) -2;
		$arrParse[SATRISE][SATAZ] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		$arrParse[SATPEAK][SATAZ] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		$arrParse[SATSET][SATAZ] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATAZ. $arrParse[SATRISE][SATAZ] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $arrParse[SATPEAK][SATAZ] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td>' );	// Find the start of the satellite name
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the satellite name
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[SATID] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $arrParse[SATID] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		//echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the Distance to flare centre	
		$strEOD = htmlspecialchars( ' (' );	// Find the end of the Distance to flare centre	
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL );
		$arrParse[FLRDISTANCE] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $arrParse[FLRDISTANCE] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the Brightness at flare centre	
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the Brightness at flare centre	
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$intParseR = strpos( $strLine, $strEOD, $intParseL ) -2;
		$arrParse[FLRMAGCTR] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATALT .':'. $arrParse[FLRMAGCTR] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		// echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="right">' );	// Find the start of the Sun altitude
		$strEOD = htmlspecialchars( ' <img ' );	// Find the end of the Sun altitude '� <img '
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$intParseR = strpos( $strLine, $strEOD, $intParseL ) -2;
		$arrParse[FLRSUNALT] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATAZ .':'. $arrParse[FLRSUNALT] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
				
		return( $arrParse ); }
	
	function build($fltLat,$fltLng,$fltAlt,$strTimeZone) {
		
		// get the satellite information (Iridium_Page)
		if( !isset($strLocation) ) $strLocation='Astronomy+Forecast';
		$strURLSrc = 'http://heavens-above.com/IridiumFlares.aspx?lat='. $fltLat .'&lng='. $fltLng .'&loc='. $strLocation .'&alt='. $fltAlt .'&tz='. $strTimeZone;
		$strPageCapture = file_get_contents( $strURLSrc );
		// echo ' <comment><![CDATA['; print_r( $strPageCapture ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		$arrParseLine = [];
		foreach( $this->parse_results_page( $strPageCapture ) AS $strLineCapture ) {
			$arrParseLine[] = $this->parse_results_line( htmlspecialchars( $strLineCapture ) );
		}
		// echo ' <comment><![CDATA['; print_r( $arrParseLine ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		foreach( $arrParseLine AS $arrSatellite ) {
			if( !isset( $arrSatellite[TZTIMEZONE] ) ) $arrSatellite[TZTIMEZONE] = $strTimeZone;
			if( (float)$arrSatellite[SATMAG] <= 2.0 ) $this->arrPasses[] = new Iridium_Pass( $arrSatellite[SATID], $arrSatellite );
		}
	}
	
	function __construct($fltLatLngAltTz) {
		// Before you get the data, you need to have the elevation and timezone (Iridium_Page)
		// AND the timezone has to be one of a few
		if( !isset( $fltLatLngAltTz[ALTITUDE] ) ) {
			$strGoogleAPIkey='AIzaSyBHVGRox44tkYoHtFFIfzSjxuN6IJ-0MgI'; // Elevation key
			$strLatLng = urlencode( (string)$fltLatLngAltTz[LATITUDE] .','. (string)$fltLatLngAltTz[LONGITUDE] );
			$objRequest = file_get_contents('https://maps.googleapis.com/maps/api/elevation/json?locations=' . $strLatLng . '&key=' . $strGoogleAPIkey );
			$arrJson = json_decode($objRequest, true);	// Return a JSON array
			// TODO: DELETE LINE
			if( !isset($arrJson['results'][0]['elevation']) && $blnVerbose )  
			{
			  echo ' <comment><![CDATA['; var_dump( $arrJson ); echo ' ]]>' . chr(13) . chr(10) . ' </comment>' . chr(13) . chr(10);
			}  

			$fltLatLngAltTz[ALTITUDE] = ( isset($arrJson['results'][0]['elevation']) ? $arrJson['results'][0]['elevation'] :113 );
		}
		if( !isset( $fltLatLngAltTz[TZTIMEZONE] ) ) {
			$strGoogleAPIkey='AIzaSyA6vk3c-4SnKzfrzK2qLgH9hQ-NEkLZsIU'; // Timezone key
			$strLatLng = urlencode( (string)$fltLatLngAltTz[LATITUDE] .','. (string)$fltLatLngAltTz[LONGITUDE] );
			$objRequest = file_get_contents('https://maps.googleapis.com/maps/api/timezone/json?location=' . $strLatLng . '&timestamp=' . time() . '&key=' . $strGoogleAPIkey );
			$arrJson = json_decode($objRequest, true);	// Return a JSON array
			// TODO: DELETE LINE
			if( !isset($arrJson['timeZoneName']) && $blnVerbose )  
			{
			  echo ' <comment><![CDATA['; var_dump( $arrJson ); echo ' ]]>' . chr(13) . chr(10) . ' </comment>' . chr(13) . chr(10);
			}  

			switch( isset($arrJson['timeZoneName']) ? $arrJson['timeZoneName'] : 'UCT' )  {
			case 'Newfoundland Standard Time':
			case 'Newfoundland Daylight Time':
				$fltLatLngAltTz[TZTIMEZONE] = 'NST'; 	// sic;  the third party data site uses standard time code year round
				break;
			case 'Atlantic Standard Time':
			case 'Atlantic Daylight Time':
				$fltLatLngAltTz[TZTIMEZONE] = 'AST'; 	// sic;  the third party data site uses standard time code year round
				break;
			case 'Eastern Standard Time':
			case 'Eastern Daylight Time':
				$fltLatLngAltTz[TZTIMEZONE] = 'EST'; 	// sic;  the third party data site uses standard time code year round
				break;
			case 'Central Standard Time':
				$tzTemp = new DateTimeZone( TZCST );
				$dteTemp = new DateTime( 'now', $tzTemp );
				if( $dteTemp->format('T') == 'CDT' ) { 
					$fltLatLngAltTz[TZTIMEZONE] = 'MST';
					break;
				} 
			case 'Central Daylight Time':
				$fltLatLngAltTz[TZTIMEZONE] = 'CST'; 	// sic;  the third party data site uses standard time code year round
				break;
			case 'Mountain Standard Time':
				$tzTemp = new DateTimeZone( TZMST );
				$dteTemp = new DateTime( 'now', $tzTemp );
				if( $dteTemp->format('T') == 'MDT' ) { 
					$fltLatLngAltTz[TZTIMEZONE] = 'PST';
					break;
				} 
			case 'Mountain Daylight Time':
				$fltLatLngAltTz[TZTIMEZONE] = 'MST'; 	// sic;  the third party data site uses standard time code year round
				break;
			case 'Pacific Standard Time':
			case 'Pacific Daylight Time':
				$fltLatLngAltTz[TZTIMEZONE] = 'PST'; 	// sic;  the third party data site uses standard time code year round
				break;
			case 'Alaska Standard Time':
			case 'Alaska Daylight Time':
				$fltLatLngAltTz[TZTIMEZONE] = 'NAST'; 	// sic;  the third party data site uses standard time code year round
				break;
			default:
				$fltLatLngAltTz[TZTIMEZONE] = 'UCT'; 	// sic;  the third party data site uses UCT instead of UTC
			}
		}
		
		$this->build($fltLatLngAltTz[LATITUDE],$fltLatLngAltTz[LONGITUDE],$fltLatLngAltTz[ALTITUDE],$fltLatLngAltTz[TZTIMEZONE]);
	}
	
	public function getPasses(){ return( $this->arrPasses ); }
}

/***
 *
 *  Variable stars
 *
 *  Calculate the minima time for Bet Per, an Algol type variable star
 *  Look up the data for the minimax for Khi Cyg and Omi Cet, Mira type variable stars
 *
 *  Classes and methods
 *    Algol - RA, Dec, Alt(), Az(), Rise(), Set(), Minimum(), Begin(), Finish()
 *    Mira - RA, Dec, Alt(), Az(), Rise(), Set(), Minimum(), Maximum(), Visible()
 *
 ***/

class VariableStar extends CelestialObject {
	protected $fltMaxMagnitude;
	protected $fltMinMagnitude;
	public function build() {
		if( isset( $this->arrObjectElements[ STARRA ] ) ) $this->fltRightAscension = $this->arrObjectElements[ STARRA ];
		if( isset( $this->arrObjectElements[ STARDEC ] ) ) $this->fltDeclination = $this->arrObjectElements[ STARDEC ];
		if( isset( $this->arrObjectElements[ STARMAGNITUDE ] ) ) $this->fltMagnitude = $this->arrObjectElements[ STARMAGNITUDE ];
		if( isset( $this->arrObjectElements[ STARMAXMAGNITUDE ] ) ) $this->fltMaxMagnitude = $this->arrObjectElements[ STARMAXMAGNITUDE ];
		if( isset( $this->arrObjectElements[ STARMINMAGNITUDE ] ) ) $this->fltMinMagnitude = $this->arrObjectElements[ STARMINMAGNITUDE ];
	}

	public function magmax() { return $this->fltMaxMagnitude; }
	public function magmin() { return $this->fltMinMagnitude; }
}

class AlgolStar extends VariableStar {

	protected $fltPeriod;
	protected $dteMinimum;

	// Calculations by Larry McNish
	private $fltJDoffset = 2440587.5;
	private $fltJD2TS = 86400.0;
	private $tsEpoch;
	private $_varibilityElements = array (
		 'BetPer' => [ STARNAME => 'BetPer', STARMAXMAGNITUDE => 2.12,	STARMINMAGNITUDE => 3.39,	STARRA => 3.133369861,	STARDEC => 40.95564722, 	STAREPOCH => 2445641.554, 	STARPERIOD => 2.867324 ],
		 'Algol'  => [ STARNAME => 'Algol', STARMAXMAGNITUDE => 2.12,	STARMINMAGNITUDE => 3.39,	STARRA => 3.133369861,	STARDEC => 40.95564722, 	STAREPOCH => 2445641.554, 	STARPERIOD => 2.867324 ],
		 'DelLib'  => [ STARNAME => 'DelLib', STARMAXMAGNITUDE => 4.91,	STARMINMAGNITUDE => 5.9,	STARRA => 15.01620833,	STARDEC => 8.518938889, 	STAREPOCH => 2445641.554, 	STARPERIOD => 2.0 ],
		 'uHer'  => [ STARNAME => 'uHer', STARMAXMAGNITUDE => 4.69,	STARMINMAGNITUDE => 5.37,	STARRA => 17.2887778,	STARDEC => 33.1, 	STAREPOCH => 2445641.554, 	STARPERIOD => 2.0 ],
		 'R CMa'  => [ STARNAME => 'R CMa', STARMAXMAGNITUDE => 5.7,	STARMINMAGNITUDE => 6.34,	STARRA => 7.3244944,	STARDEC => -16.39525, 	STAREPOCH => 2445641.554, 	STARPERIOD => 1.0 ]
	);
	public function build() {
		$tzEarth = new DateTimeZone( TZUTC );
		$this->dteMinimum = new DateTime( 'noon', $tzEarth );
		if( isset( $this->_varibilityElements[ $this->strObjectName ] ) ) {
			$this->fltRightAscension = $this->_varibilityElements[ $this->strObjectName ][ STARRA ];
			$this->fltDeclination = $this->_varibilityElements[ $this->strObjectName ][ STARDEC ];
			$this->fltMagnitude = $this->_varibilityElements[ $this->strObjectName ][ STARMAXMAGNITUDE ];
			if( isset( $this->arrObjectElements[ STARMAGNITUDE ] ) ) $this->fltMagnitude = $this->arrObjectElements[ STARMAGNITUDE ];	// If magnitude is specified, override
			$this->fltMaxMagnitude = $this->_varibilityElements[ $this->strObjectName ][ STARMAXMAGNITUDE ];
			$this->fltMinMagnitude = $this->_varibilityElements[ $this->strObjectName ][ STARMINMAGNITUDE ];
			$this->tsEpoch = ( $this->_varibilityElements[ $this->strObjectName ][ STAREPOCH ] - $this->fltJDoffset ) * $this->fltJD2TS;
			$this->fltPeriod = $this->_varibilityElements[ $this->strObjectName ][ STARPERIOD ] * $this->fltJD2TS;
			if( isset( $this->arrObjectElements[ STARDATEMINIMUM ] ) ) $this->dteMinimum->setTimestamp( $this->arrObjectElements[ STARDATEMINIMUM ] );
			$intCycles = floor( ( $this->dteMinimum->getTimestamp() - $this->tsEpoch ) / $this->fltPeriod ) + 1;
			$this->dteMinimum->setTimestamp( (integer) ( $this->tsEpoch + $this->fltPeriod * $intCycles ) );
			// That was relative to Algol itself - negligible adjustment for light travel time to the Earth in it's orbital position
			/*if( $blnVerbose ) {
			}*/
			/*echo ' <comment><![CDATA['; echo 'Preloaded Algol variable' . chr(13) . chr(10); print_r( $this ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);*/
		}
		else {
			if( isset( $this->arrObjectElements[ STARRA ] ) ) $this->fltRightAscension = $this->arrObjectElements[ STARRA ];
			if( isset( $this->arrObjectElements[ STARDEC ] ) ) $this->fltDeclination = $this->arrObjectElements[ STARDEC ];
			if( isset( $this->arrObjectElements[ STARMAXMAGNITUDE ] ) ) $this->fltMagnitude = $this->arrObjectElements[ STARMAXMAGNITUDE ];
			if( isset( $this->arrObjectElements[ STARMAXMAGNITUDE ] ) ) $this->fltMaxMagnitude = $this->arrObjectElements[ STARMAXMAGNITUDE ];
			if( isset( $this->arrObjectElements[ STARMINMAGNITUDE ] ) ) $this->fltMinMagnitude = $this->arrObjectElements[ STARMINMAGNITUDE ];
			if( isset( $this->arrObjectElements[ STAREPOCH ] ) ) $this->tsEpoch = ( $this->arrObjectElements[ STAREPOCH ] - $this->fltJDoffset ) * $this->fltJD2TS;
			if( isset( $this->arrObjectElements[ STARPERIOD ] ) ) $this->fltPeriod = $this->arrObjectElements[ STARPERIOD ] * $this->fltJD2TS;
			if( isset( $this->arrObjectElements[ STARDATEMINIMUM ] ) ) $this->dteMinimum->setTimestamp( $this->arrObjectElements[ STARDATEMINIMUM ] );
			if( isset( $this->arrObjectElements[ STARDATE ] ) ) $this->dteMinimum->setTimestamp( $this->arrObjectElements[ STARDATE ] );
			if( isset($this->arrObjectElements[ STAREPOCH ]) && isset($this->arrObjectElements[ STARPERIOD ]) ) {
				$intCycles = floor( ( $this->dteMinimum->getTimestamp() - $this->tsEpoch ) / $this->fltPeriod ) + 1;
				$this->dteMinimum->setTimestamp( (integer) ($this->tsEpoch + $this->fltPeriod * $intCycles ) );
			// That was relative to Algol itself - negligible adj for light travel time to the Earth in it's orbital position, i.e. +/- 8 minutes
			/*if( $blnVerbose ) {
				}*/
 				/*echo ' <comment><![CDATA['; echo 'Generated Algol variable' . chr(13) . chr(10); print_r( $this ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
			} else {
				echo ' <comment><![CDATA['; echo 'No Epoch or No Period' . chr(13) . chr(10); print_r( $this ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);*/
			}
		}
	}
	public function getMaxMagnitude() { return $this->fltMaxMagnitude; }
	public function getMinMagnitude() { return $this->fltMinMagnitude; }
	public function datemin() { return $this->dteMinimum; }
}

class MiraStar extends VariableStar {
	protected $fltPeriod;
	protected $fltDescPeriod;
	protected $fltAscPeriod;
	protected $dteMinimum;
	protected $dteMaximum;
	private $fltJDoffset = 2440587.5;	// 2440587.5 = Jan 1, 1970 00h00 UTC	2457809.0 = Feb 24. 2017 12h00 UTC	https://www.aavso.org/lcg
	private $fltJD2TS = 86400.0;
	private $tsEpoch;

	public function build() {
		$tzEarth = new DateTimeZone( TZUTC );
		$dteEarth = new DateTime( 'noon', $tzEarth );
		$this->dteMaximum = clone $dteEarth;
		$this->dteMinimum = clone $dteEarth;
		if( isset( $this->arrObjectElements[ STARRA ] ) ) $this->fltRightAscension = $this->arrObjectElements[ STARRA ];
		if( isset( $this->arrObjectElements[ STARDEC ] ) ) $this->fltDeclination = $this->arrObjectElements[ STARDEC ];
		if( isset( $this->arrObjectElements[ STARMAGNITUDE ] ) ) $this->fltMagnitude = $this->arrObjectElements[ STARMAGNITUDE ];
		if( isset( $this->arrObjectElements[ STARMAXMAGNITUDE ] ) ) $this->fltMaxMagnitude = $this->arrObjectElements[ STARMAXMAGNITUDE ];
		if( isset( $this->arrObjectElements[ STARMINMAGNITUDE ] ) ) $this->fltMinMagnitude = $this->arrObjectElements[ STARMINMAGNITUDE ];
		if( isset( $this->arrObjectElements[ STAREPOCHMIN ] ) ) $this->tsEpoch = ( $this->arrObjectElements[ STAREPOCHMIN ] - $this->fltJDoffset ) * $this->fltJD2TS;
		if( isset( $this->arrObjectElements[ STAREPOCHMAX ] ) ) $this->tsEpoch = ( $this->arrObjectElements[ STAREPOCHMAX ] - $this->fltJDoffset ) * $this->fltJD2TS;
		if( isset( $this->arrObjectElements[ STAREPOCH ] ) ) $this->tsEpoch = ( $this->arrObjectElements[ STAREPOCH ] - $this->fltJDoffset ) * $this->fltJD2TS;
		if( isset( $this->arrObjectElements[ STARPERIOD ] ) ) $this->fltPeriod = $this->arrObjectElements[ STARPERIOD ] * $this->fltJD2TS;
		if( isset( $this->arrObjectElements[ STARDATEMINIMUM ] ) ) $this->dteMinimum = $this->arrObjectElements[ STARDATEMINIMUM ];
		if( isset( $this->arrObjectElements[ STARDATEMAXIMUM ] ) ) $this->dteMaximum = $this->arrObjectElements[ STARDATEMAXIMUM ];
		if( isset( $this->arrObjectElements[ STARASCPERIOD ] ) ) $this->fltAscPeriod = $this->arrObjectElements[ STARASCPERIOD ] * $this->fltJD2TS;
		if( isset( $this->arrObjectElements[ STARDESCPERIOD ] ) ) $this->fltDescPeriod = $this->arrObjectElements[ STARDESCPERIOD ] * $this->fltJD2TS;
				// echo ' <comment><![CDATA['; print_r( $intCycles ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		if( isset($this->arrObjectElements[ STAREPOCH ]) && isset($this->arrObjectElements[ STARPERIOD ]) ) {
			$intCycles = floor( ( $dteEarth->getTimestamp() - $this->tsEpoch ) / $this->fltPeriod ) + 1;
			$this->dteMaximum->setTimestamp( (integer) ($this->tsEpoch + $this->fltPeriod * $intCycles ) );
			$this->dteMinimum->setTimestamp( (integer) ($this->tsEpoch + $this->fltPeriod * ($intCycles-1/3) ) );
		}
		if( isset($this->arrObjectElements[ STAREPOCHMAX ]) && isset($this->arrObjectElements[ STARPERIOD ]) ) {
			$intCycles = floor( ( $dteEarth->getTimestamp() - $this->tsEpoch ) / $this->fltPeriod ) + 1;
			$this->dteMaximum->setTimestamp( (integer) ($this->tsEpoch + $this->fltPeriod * $intCycles ) );
			$this->dteMinimum->setTimestamp( (integer) ($this->tsEpoch + $this->fltPeriod * ($intCycles-1/3) ) );
		}
		if( isset($this->arrObjectElements[ STAREPOCHMIN ]) && isset($this->arrObjectElements[ STARPERIOD ]) ) {
			$intCycles = floor( ( $dteEarth->getTimestamp() - $this->tsEpoch ) / $this->fltPeriod ) + 1;
			$this->dteMinimum->setTimestamp( (integer) ($this->tsEpoch + $this->fltPeriod * $intCycles ) );
			$this->dteMaximum->setTimestamp( (integer) ($this->tsEpoch + $this->fltPeriod * ($intCycles+1/3) ) );
		}
		if( isset($this->arrObjectElements[ STAREPOCHMAX ]) && isset($this->arrObjectElements[ STAREPOCHMIN ]) && isset($this->arrObjectElements[ STARPERIOD ]) ) {
			$this->tsEpoch = ( $this->arrObjectElements[ STAREPOCHMAX ] - $this->fltJDoffset ) * $this->fltJD2TS;
			$intCycles = floor( ( $dteEarth->getTimestamp() - $this->tsEpoch ) / $this->fltPeriod );
			$tsEarlyMaximum = (integer) ($this->tsEpoch + $this->fltPeriod * $intCycles );
			$tsLateMaximum = (integer) ($this->tsEpoch + $this->fltPeriod * ($intCycles + 1) );
			$this->tsEpoch = ( $this->arrObjectElements[ STAREPOCHMIN ] - $this->fltJDoffset ) * $this->fltJD2TS;
			$intCycles = floor( ( $dteEarth->getTimestamp() - $this->tsEpoch ) / $this->fltPeriod );
			$tsEarlyMinimum = (integer) ($this->tsEpoch + $this->fltPeriod * $intCycles );
			$tsLateMinimum = (integer) ($this->tsEpoch + $this->fltPeriod * ($intCycles + 1) );
		// echo ' <comment><![CDATA['; print_r( [$this->name(), $dteEarth->getTimestamp(), $tsEarlyMinimum=>$tsEarlyMinimum, $tsEarlyMaximum=>$tsEarlyMaximum, $tsLateMinimum=>$tsLateMinimum, $tsLateMaximum=>$tsLateMaximum] ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
			if( $tsEarlyMinimum  > $tsEarlyMaximum ) {
				$this->dteMaximum->setTimestamp( $tsLateMaximum );
				$this->dteMinimum->setTimestamp( $tsEarlyMinimum );
			} else {
				$this->dteMaximum->setTimestamp( $tsEarlyMaximum );
				$this->dteMinimum->setTimestamp( $tsLateMinimum );
			}
			if( isset( $this->arrObjectElements[ STARASCPERIOD ] ) ) {
				$this->fltAscPeriod = $this->arrObjectElements[ STARASCPERIOD ] * $this->fltJD2TS;
			} else {
				$this->fltAscPeriod = $tsEarlyMaximum - $tsEarlyMaximum;
			}
			if( isset( $this->arrObjectElements[ STARDESCPERIOD ] ) ) {
				$this->fltDescPeriod = $this->arrObjectElements[ STARDESCPERIOD ] * $this->fltJD2TS;
			} else {
				$this->fltDescPeriod = $tsLateMinimum - $tsEarlyMaximum;
			}
		}
		// echo ' <comment><![CDATA['; print_r( [$this->name(), $this->arrObjectElements, $this->tsEpoch, $this->dteMaximum, $this->dteMinimum, $this->dteMaximum->getTimestamp(), $this->dteMinimum->getTimestamp(), $this->fltPeriod, $intCycles, $this->fltMinMagnitude, $this->fltMaxMagnitude, $this->dteMaximum->getTimestamp(), $this->dteMinimum->getTimestamp(), $dteEarth->getTimestamp(), $this->dteMinimum->getTimestamp() ] ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		if( $this->dteMinimum->getTimestamp() < $dteEarth->getTimestamp() && $dteEarth->getTimestamp() < $this->dteMaximum->getTimestamp() ) {
			$this->fltMagnitude = $this->fltMinMagnitude 
					+ ( $this->fltMaxMagnitude - $this->fltMinMagnitude )
					/( $this->dteMaximum->getTimestamp() - $this->dteMinimum->getTimestamp() )
					*( $dteEarth->getTimestamp() - $this->dteMinimum->getTimestamp() );
		}
		elseif( $this->dteMaximum->getTimestamp() < $dteEarth->getTimestamp() && $dteEarth->getTimestamp() < $this->dteMinimum->getTimestamp() ) {
			$this->fltMagnitude = $this->fltMaxMagnitude 
					+ ( $this->fltMinMagnitude - $this->fltMaxMagnitude )
					/( $this->dteMinimum->getTimestamp() - $this->dteMaximum->getTimestamp() )
					*( $dteEarth->getTimestamp() - $this->dteMaximum->getTimestamp() );
		}
	}
	public function getMaxMagnitude() { return $this->fltMaxMagnitude; }
	public function getMinMagnitude() { return $this->fltMinMagnitude; }
	public function datemin() { return $this->dteMinimum; }
	public function datemax() { return $this->dteMaximum; }
}

/***
 *
 *  Meteor shower
 *
 *  Look up the data for meteor showers
 *
 *  Classes and methods
 *    MeteorShower - Name, Date, Time, Constellation 
 *
 ***/

class MeteorShower {
	private $_showerElements = array (
		 'Perseids' => [ MTRNAME => 'Perseids', MTRBEGIN => 'Jul 17', MTRPEAK => 'Aug 11', MTREND => 'Aug 24', MTRTIME => '00h00', MTRRA => 3.4, 	MTRDEC => 45.4, 	MTRMAG => 2.0, MTRRATE => 20 ],
		 'Draconids' => 	[ MTRNAME => 'Draconids', MTRBEGIN => 'Oct 7', MTRPEAK => 'Oct 7', MTREND => 'Oct 8', MTRTIME => '22h00', MTRRA => 6.4, 	MTRDEC => 14.9, 	MTRMAG => 2.5, MTRRATE => 20 ],
		 'Orionids' => 	[ MTRNAME => 'Orionids', MTRBEGIN => 'Oct 2', MTRPEAK => 'Oct 22', MTREND => 'Nov 7', MTRTIME => '02h00', MTRRA => 6.4, 	MTRDEC => 14.9, 	MTRMAG => 2.5, MTRRATE => 20 ],
		 'Leonids'  => [ MTRNAME => 'Leonids', MTRBEGIN => 'Nov 6', MTRPEAK => 'Nov 18', MTREND => 'Nov 30', MTRTIME => '02h00', MTRRA => 10.5, 	MTRDEC => 16.8, 	MTRMAG => 2.5, MTRRATE => 15 ],
		 'Geminids'  => [ MTRNAME => 'Geminids', MTRBEGIN => 'Dec 7', MTRPEAK => 'Dec 14', MTREND => 'Dec 17', MTRTIME => '22h00', MTRRA => 7.1, 	MTRDEC => 24.3, 	MTRMAG => 2.6, MTRRATE => 120 ],
		 'Quadrantids' => 	[ MTRNAME => 'Quadrantids', MTRBEGIN => 'Dec 28', MTRPEAK => 'Jan 4', MTREND => 'Jan 12', MTRTIME => '04h00', MTRRA => 15.5, 	MTRDEC => 50.0, 	MTRMAG => 2.1, MTRRATE => 120 ],
		 'Lyrids' => 	[ MTRNAME => 'Lyrids', MTRBEGIN => 'Apr 16', MTRPEAK => 'Apr 24', MTREND => 'Apr 25', MTRTIME => '04h00', MTRRA => 18.8, 	MTRDEC => 35.9, 	MTRMAG => 2.1, MTRRATE => 18 ],
		 'eta-Aquariids' => 	[ MTRNAME => 'eta-Aquariids', MTRBEGIN => 'Apr 19', MTRPEAK => 'May 7', MTREND => 'May 28', MTRTIME => '02h00', MTRRA => 22.5, 	MTRDEC => -10.8, 	MTRMAG => 2.4, MTRRATE => 55 ],
		 'Arietids' => 	[ MTRNAME => 'Arietids', MTRBEGIN => 'May 22', MTRPEAK => 'Jun 9', MTREND => 'Jul 2', MTRTIME => '02h00', MTRRA => 2.4, 	MTRDEC => 22.8, 	MTRMAG => 2.0, MTRRATE => 54 ] 
	);
	private $strName; 
	private $dteBegin;
	private $dtePeak;
	private $dteEnd;
	private $dteTime;
	private $fltRA;
	private $fltDec;
	private $fltMagnitude;
	private $fltRate;
	function __construct( $dteCurrent ){ 
		// Return the next meteor shower based on the start and end date
		$tzLocal = $dteCurrent->getTimezone();
		$dteNext = clone $dteCurrent;
		$dteNext->add( DateInterval::createFromDateString('1 year') );
		foreach( $this->_showerElements AS $arrShower ) {
			$dteShowerStart = DateTime::createFromFormat( MTRDATEFORMAT, $arrShower[MTRBEGIN], $tzLocal );
			if( $dteShowerStart->getTimestamp() < $dteCurrent->getTimestamp() ) $dteShowerStart->add( DateInterval::createFromDateString('1 year') );
			$dteShowerStop = DateTime::createFromFormat( MTRDATEFORMAT, $arrShower[MTREND], $tzLocal );
			if( $dteShowerStop->getTimestamp() < $dteCurrent->getTimestamp() ) $dteShowerStop->add( DateInterval::createFromDateString('1 year') );
			/* echo '<comment><![CDATA['. $arrShower[MTRNAME] . chr(13) . chr(10) . 'start date'. chr(13) . chr(10); 
			print_r( $dteShowerStart ); 
			echo 'stop date'. chr(13) . chr(10); 
			print_r( $dteShowerStop ); 
			echo 'next date'. chr(13) . chr(10); 
			print_r( $dteNext ); 
			echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10); */
			if( $dteShowerStart->getTimestamp() < $dteCurrent->getTimestamp() && $dteCurrent->getTimestamp() < $dteShowerStop->getTimestamp() ) {
				// this is the current meteor shower
				$this->strName = $arrShower[MTRNAME]; 
				$this->dteBegin = DateTime::createFromFormat( MTRDATEFORMAT, $arrShower[MTRBEGIN], $tzLocal );
				$this->dtePeak = DateTime::createFromFormat( MTRDATEFORMAT, $arrShower[MTRPEAK], $tzLocal );
				$this->dteEnd = DateTime::createFromFormat( MTRDATEFORMAT, $arrShower[MTREND], $tzLocal );
				$this->dteTime = DateTime::createFromFormat( MTRTIMEFORMAT, $arrShower[MTRTIME] ); 
				$this->dteBegin->setTime($this->dteTime->format('H'),$this->dteTime->format('i'));
				$this->dtePeak->setTime($this->dteTime->format('H'),$this->dteTime->format('i'));
				$this->dteEnd->setTime($this->dteTime->format('H'),$this->dteTime->format('i'));
				$this->fltRA = $arrShower[MTRRA];
				$this->fltDec = $arrShower[MTRDEC];
				$this->fltMagnitude = $arrShower[MTRMAG];
				$this->fltRate = $arrShower[MTRRATE];
				break;
			} elseif( $dteShowerStart->getTimestamp() < $dteNext->getTimestamp() && $dteCurrent->getTimestamp() < $dteShowerStop->getTimestamp() ) {
				// this is the next meteor shower
				$dteNext = clone $dteShowerStart;
				$this->strName = $arrShower[MTRNAME]; 
				$this->dteBegin = DateTime::createFromFormat( MTRDATEFORMAT, $arrShower[MTRBEGIN], $tzLocal );
				$this->dtePeak = DateTime::createFromFormat( MTRDATEFORMAT, $arrShower[MTRPEAK], $tzLocal );
				$this->dteEnd = DateTime::createFromFormat( MTRDATEFORMAT, $arrShower[MTREND], $tzLocal );
				$this->dteTime = DateTime::createFromFormat( MTRTIMEFORMAT, $arrShower[MTRTIME] ); 
				$this->dteBegin->setTime($this->dteTime->format('H'),$this->dteTime->format('i'));
				$this->dtePeak->setTime($this->dteTime->format('H'),$this->dteTime->format('i'));
				$this->dteEnd->setTime($this->dteTime->format('H'),$this->dteTime->format('i'));
				$this->fltRA = $arrShower[MTRRA];
				$this->fltDec = $arrShower[MTRDEC];
				$this->fltMagnitude = $arrShower[MTRMAG];
				$this->fltRate = $arrShower[MTRRATE];
			}
		}
	}
	
	public function name() { return( $this->strName ); }
	public function begin() { return( $this->dteBegin ); }
	public function peak() { return( $this->dtePeak ); }
	public function finish() { return( $this->dteEnd ); }
	public function ra() { return( $this->fltRA ); }
	public function dec() { return( $this->fltDec ); }
	public function mag() { return( $this->fltMagnitude ); }
	public function rate() { return( $this->fltRate ); }
}

/***
 *
 *  Comet
 *
 *  Look up the data for comets
 *
 *  Classes and methods
 *    Celestial object - RA, Dec, Alt(), Az(), Rise(), Set(), Distance( RA, Dec )
 *    Planet -  RA(), Dec(), Alt(), Az(), Rise(), Set(), Distance( RA, Dec )
 *    Comet - Name, Ephemeris
 *
 *  2018 - 46P/Wirtanen 
 *       - 21P/Giacobini-Zinner
 *       - 38P/Stephan-Oterma
 *  2019 - Coming soon 
 * 
 ***/

class Comet extends Planet {
/*
 * http://minorplanetcenter.net/iau/Ephemerides/Comets/Soft00Cmt.txt
 * 
 * 0021P         2018 09 10.2784  1.012782  0.710476  172.8627  195.3933   31.9977  20180814   9.0  6.0  21P/Giacobini-Zinner                                     MPC110084
 * 0038P         2018 11 10.9740  1.588570  0.859318  359.5838   77.9997   18.3530  20180814   3.5 12.0  38P/Stephan-Oterma                                        27,  605
 * 0046P         2018 12 12.9356  1.055359  0.658525  356.3308   82.1658   11.7454  20180814  14.0  6.0  46P/Wirtanen                                             MPEC 2018-N81
 * 
 * 12345678901234567 20 2345678901234567 40 2345678901234567 60 2345678901234567 80 234567890123456 100 234567890123456 120 234567890123456789           1234 6789 123456 160 234567890123456 180 234567890123456 200 2345678901234567890
 * 
 * http://www.minorplanetcenter.net/iau/info/CometOrbitFormat.html
 * 
Ephemerides and Orbital Elements Format
The column headed `F77' indicates the Fortran 77 format specifier that should be used to read the specified value.
   Columns   F77    Use

    1 -   4  i4     Periodic comet number
    5        a1     Orbit type (generally `C', `P' or `D')
    6 -  12  a7     Provisional designation (in packed form)

   15 -  18  i4     Year of perihelion passage
   20 -  21  i2     Month of perihelion passage
   23 -  29  f7.4   Day of perihelion passage (TT)

   31 -  39  f9.6   Perihelion distance (AU)
   42 -  49  f8.6   Orbital eccentricity

   52 -  59  f8.4   Argument of perihelion, J2000.0 (degrees)
   62 -  69  f8.4   Longitude of the ascending node, J2000.0
                      (degrees)
   72 -  79  f8.4   Inclination in degrees, J2000.0 (degrees)

   82 -  85  i4     Year of epoch for perturbed solutions
   86 -  87  i2     Month of epoch for perturbed solutions
   88 -  89  i2     Day of epoch for perturbed solutions

   92 -  95  f4.1   Absolute magnitude
   97 - 100  f4.0   Slope parameter

  103 - 158  a56    Designation and Name

  160 - 168  a9     Reference
 * 
 *   21P/Giacobini-Zinner
 * Epoch 2018 Mar. 23.0 TT = JDT 2458200.5
 * T 2018 Sept. 10.27501 TT                                MPCW
 * q   1.0128685            (2000.0)            P               Q
 * n   0.15062276     Peri.  172.85309     +0.98463479     -0.10345588
 * a   3.4984499      Node   195.39615     +0.12213663     +0.98377993
 * e   0.7104808      Incl.   31.99802     +0.12480771     -0.14653988
 * P   6.54
 * From 827 observations 2004 June 21-2018 Apr. 25, mean residual 0".8.
 *      Nongravitational parameters A1 = +0.18, A2 = -0.1254.
 * 
 * Orbital Elements at Epoch 2447389.5 (1988-Aug-16.0) TDB
 * Reference: JPL 13 (heliocentric ecliptic J2000)
 *  Element	Value	Uncertainty (1-sigma) 	 Units 
 * e	.8599470547067442	1.6315e-07	 
 * a	11.28373147583503	3.6415e-07	au
 * q	1.580319827088913	1.8561e-06	au
 * i	18.40849405327677	5.7065e-05	deg
 * node	78.00789745123544	7.208e-05	deg
 * peri	359.5222462179381	0.00015034	deg
 * M	73.10042543807081	7.126e-06	deg
 * tp	2444578.279284795718
 * (1980-Dec-04.77928480)	0.00018473	JED
 * period	13844.50844723087
 * 37.90	0.00067019
 * 1.835e-06	d
 * yr
 * n	.02600309006073856	1.2588e-09	deg/d
 * Q	20.98714312458115	6.773e-07	au
 * 
 * Additional Model Parameters
 *  Parameter	Value	Uncertainty (1-sigma) 
 * A2 [EST]	-2.649473433707058E-11	1.129E-12
 *  	Orbit Determination Parameters
 *    # obs. used (total)   	  250  
 *    data-arc span   	  27591 days (75.54 yr)  
 *    first obs. used   	  1942-11-06  
 *    last obs. used   	  2018-05-22  
 *    planetary ephem.   	  DE431  
 *    SB-pert. ephem.   	  SB431-N16  
 *    condition code   	  0  
 *    fit RMS   	  .64004  
 *    data source   	  ORB  
 *    producer   	  Javier Roa  
 *    solution date   	  2018-May-30 15:12:26  
 * 
 * Additional Information
 *  Earth MOID = .594766 au 
 *  Jupiter MOID = 1.33474 au 
 *  T_jup = 1.887  
 * 
Comet 38P/Stephan-Oterma Orbital Elements
The following table lists the orbital elements of Comet 38P/Stephan-Oterma	at epoch 15 August 1988 00:00 UTC (JD: 2447389.5). Source: JPL Small-Body Database

Element	Symbol	Value
Orbit eccentricity	e	0.85994705
Orbit inclination	i	18.40849405°
Perihelion distance	q	1.58031983 AU
236,412,482 km
Aphelion distance	Q	20.98714312 AU
3,139,631,930 km
Semi-major axis	a	11.28373148 AU
1,688,022,206 km
Orbital period	period	37.9000 years
13,844.5084 days
Date of perihelion transit	Tp	1980-Dec-04 18:42:10
2,444,578.2793 JD
Next perihelion transit		2018-Oct-31 06:54
2,458,422.7877 JD
Argument of perihelion	peri	359.52224621794°
Longitude of the ascending node	node	78.007897451235°
Mean anomaly	M	73.100425438071°
Mean motion	n	0.02600309°/day
Closest approach to Earth*		2018-Dec-17
Distance of closest approach*		0.76573081 AU
114,551,700 km
* NOTE: values for the closest approach are computed for the time interval between 2013-Jan-01 and 2075-Dec-31, with a sampling interval of 1 day.
 * 
 * 

http://ssd.jpl.nasa.gov/horizons.cgi

JPL/HORIZONS                    46P/Wirtanen               2018-Aug-17 03:10:13
Rec #:  900542 (+COV) Soln.date: 2018-Jul-16_08:12:42     # obs: 52 (2013-2018)
 
IAU76/J2000 helio. ecliptic osc. elements (au, days, deg., period=Julian yrs):
 
  EPOCH=  2458010.5 ! 2017-Sep-14.0000000 (TDB)    RMSW= n.a.
   EC= .6584823676483879   QR= 1.055182701764401   TP= 2458465.4574243971
   OM= 82.16767378494878   W= 356.3427497135656    IN= 11.74632086850374
   A= 3.089687330339739    MA= 277.43366020543     ADIST= 5.124191958915077
   PER= 5.4310026203546    N= .18148146            ANGMOM= .02275625
   DAN= 1.05604            DDN= 5.10415            L= 78.58682
   B= -.7440523            MOID= .0706344          TP= 2018-Dec-12.9574243971
 
Comet physical (GM= km^3/s^2; RAD= km):
   GM= n.a.                RAD= .600
   M1=  13.6     M2=  n.a.     k1=  14.    k2= n.a.     PHCOF= n.a.
 
COMET comments 
1: soln ref.= JPL#K182/4, data arc: 2013-10-30 to 2018-07-10
2: k1=14.;
 
 * 
 *   46P/Wirtanen
 * Epoch 2018 Dec. 28.0 TT = JDT 2458480.5
 * T 2018 Dec. 12.94825 TT                                 MPCW
 * q   1.0553573            (2000.0)            P               Q
 * n   0.18118193     Peri.  356.35491     +0.19780398     -0.95927112
 * a   3.0930917      Node    82.15941     +0.90442396     +0.09926135
 * e   0.6588018      Incl.   11.74623     +0.37800911     +0.26447326
 * P   5.44
 * From 1290 observations 2007 Aug. 4-2018 July 10, mean residual 0".8.
 *      Nongravitational parameters A1 = +0.10, A2 = -0.1752.
 * 
 ***/
	protected $fltMaxMagnitude;
	protected $idComet;
	private $orbitalElements = array (
		'0021P' => [ OENAME => '0021P', STARMAGNITUDE => 7.03,	// 21P/Giacobini-Zinner
		OEMLNG => [100.466449,  36000.7698231,    0.00030368,    0.000000021],
		OEMDST => [  1.000001018,   0.0,          0.0,           0.0],
		OEECTY => [  0.01670862,   -0.000042037, -0.0000001236,  0.00000000004],
		OEINCL => [  0.0,           0.0,          0.0,           0.0],
		OENODE => [  0.0,           0.0,          0.0,           0.0],
		OEPERD => [102.937348,      1.7195269,    0.00045962,    0.000000499]],

		'0038P' => [ OENAME => '0038P', STARMAGNITUDE =>  9.23,	// 38P/Stephan-Oterma
		OEMLNG => [252.250906, 149474.0722491,    0.00030397,    0.000000018],
		OEMDST => [  0.387098310,   0.0,          0.0,           0.0],
		OEECTY => [  0.20563175,    0.000020406, -0.0000000284, -0.00000000017],
		OEINCL => [  7.004986,      0.0018215,   -0.00001809,    0.000000053],
		OENODE => [ 48.330893,      1.1861890,    0.00017587,    0.000000211],
		OEPERD => [ 77.456119,      1.5564775,    0.00029589,    0.000000056]],

		'0046P' => [ OENAME => '0046P', STARMAGNITUDE => 8.87,	// 46P/Wirtanen
		OEMLNG => [181.979801,  58519.2130302,    0.00031060,    0.000000015],
		OEMDST => [  0.723329820,   0.0,          0.0,           0.0],
		OEECTY => [  0.00677188,   -0.000047766,  0.0000000975,  0.00000000044],
		OEINCL => [  3.394662,      0.0010037,   -0.00000088,   -0.000000007],
		OENODE => [ 76.679920,      0.9011190,    0.00040665,   -0.000000080],
		OEPERD => [131.563707,      1.4022188,   -0.00107337,   -0.000005315]]
		
		);
	private $orbitTable = array (
		'0021P' => [ OENAME => '21P/Giacobini-Zinner', STARMAXMAGNITUDE => 7.03, 	// 21P/Giacobini-Zinner
					STAREPOCHMIN => 1533729600, STAREPOCHMAX => 1546430400, 
					'1533124800' => [STARRA => 0.0754444444444444, STARDEC => 65.6391666666667, STARMAGNITUDE => 8.81 ],
					'1533729600' => [STARRA => 1.25030555555556, STARDEC => 66.6145555555556, STARMAGNITUDE => 8.36 ],
					'1534334400' => [STARRA => 2.56938888888889, STARDEC => 64.8027777777778, STARMAGNITUDE => 7.94 ],
					'1534939200' => [STARRA => 3.78238888888889, STARDEC => 59.8662777777778, STARMAGNITUDE => 7.56 ],
					'1535544000' => [STARRA => 4.739, STARDEC => 51.7993888888889, STARMAGNITUDE => 7.27 ],
					'1536148800' => [STARRA => 5.44933333333333, STARDEC => 41.2716111111111, STARMAGNITUDE => 7.09 ],
					'1536753600' => [STARRA => 5.97775, STARDEC => 29.3639722222222, STARMAGNITUDE => 7.05 ],
					'1537358400' => [STARRA => 6.38055555555556, STARDEC => 17.3641666666667, STARMAGNITUDE => 7.18 ],
					'1537963200' => [STARRA => 6.69519444444444, STARDEC => 6.33638888888889, STARMAGNITUDE => 7.43 ],
					'1538568000' => [STARRA => 6.94325, STARDEC => -3.19780555555556, STARMAGNITUDE => 7.77 ],
					'1539172800' => [STARRA => 7.13575, STARDEC => -11.1890555555556, STARMAGNITUDE => 8.17 ],
					'1539777600' => [STARRA => 7.27811111111111, STARDEC => -17.8106388888889, STARMAGNITUDE => 8.61 ],
					'1540382400' => [STARRA => 7.37336111111111, STARDEC => -23.2868888888889, STARMAGNITUDE => 9.05 ],
					'1540987200' => [STARRA => 7.42252777777778, STARDEC => -27.8161666666667, STARMAGNITUDE => 9.5 ],
					'1541592000' => [STARRA => 7.42533333333333, STARDEC => -31.538, STARMAGNITUDE => 9.94 ],
					'1542196800' => [STARRA => 7.38252777777778, STARDEC => -34.5285, STARMAGNITUDE => 10.36 ],
					'1542801600' => [STARRA => 7.29736111111111, STARDEC => -36.8251111111111, STARMAGNITUDE => 10.78 ],
					'1543406400' => [STARRA => 7.17541666666667, STARDEC => -38.4453888888889, STARMAGNITUDE => 11.18 ],
					'1544011200' => [STARRA => 7.02463888888889, STARDEC => -39.3927777777778, STARMAGNITUDE => 11.57 ],
					'1544616000' => [STARRA => 6.85630555555556, STARDEC => -39.6693333333333, STARMAGNITUDE => 11.95 ],
					'1545220800' => [STARRA => 6.68386111111111, STARDEC => -39.3016388888889, STARMAGNITUDE => 12.32 ],
					'1545825600' => [STARRA => 6.52, STARDEC => -38.3479722222222, STARMAGNITUDE => 12.68 ],
					'1546430400' => [STARRA => 6.37477777777778, STARDEC => -36.8874166666667, STARMAGNITUDE => 13.04 ]
					],

		'0038P' => [ OENAME => '38P/Stephan-Oterma', STARMAXMAGNITUDE => 9.15, 	// 38P/Stephan-Oterma
					STAREPOCHMIN => 1533729600, STAREPOCHMAX => 1546430400, 
					'1533124800' => [STARRA => 3.57011111111111, STARDEC => 2.95233333333333, STARMAGNITUDE => 14.15 ],
					'1533729600' => [STARRA => 3.82516666666667, STARDEC => 3.95136111111111, STARMAGNITUDE => 13.71 ],
					'1534334400' => [STARRA => 4.08575, STARDEC => 4.94291666666667, STARMAGNITUDE => 13.26 ],
					'1534939200' => [STARRA => 4.35166666666667, STARDEC => 5.93252777777778, STARMAGNITUDE => 12.82 ],
					'1535544000' => [STARRA => 4.62280555555556, STARDEC => 6.92783333333333, STARMAGNITUDE => 12.39 ],
					'1536148800' => [STARRA => 4.89880555555556, STARDEC => 7.9375, STARMAGNITUDE => 11.97 ],
					'1536753600' => [STARRA => 5.17886111111111, STARDEC => 8.97238888888889, STARMAGNITUDE => 11.56 ],
					'1537358400' => [STARRA => 5.46213888888889, STARDEC => 10.0474444444444, STARMAGNITUDE => 11.17 ],
					'1537963200' => [STARRA => 5.74783333333333, STARDEC => 11.1804722222222, STARMAGNITUDE => 10.8 ],
					'1538568000' => [STARRA => 6.03477777777778, STARDEC => 12.3910555555556, STARMAGNITUDE => 10.46 ],
					'1539172800' => [STARRA => 6.32108333333333, STARDEC => 13.7015, STARMAGNITUDE => 10.15 ],
					'1539777600' => [STARRA => 6.60469444444444, STARDEC => 15.1373888888889, STARMAGNITUDE => 9.87 ],
					'1540382400' => [STARRA => 6.88347222222222, STARDEC => 16.7250833333333, STARMAGNITUDE => 9.63 ],
					'1540987200' => [STARRA => 7.15469444444445, STARDEC => 18.4893333333333, STARMAGNITUDE => 9.44 ],
					'1541592000' => [STARRA => 7.41466666666667, STARDEC => 20.4521388888889, STARMAGNITUDE => 9.29 ],
					'1542196800' => [STARRA => 7.65927777777778, STARDEC => 22.6288888888889, STARMAGNITUDE => 9.19 ],
					'1542801600' => [STARRA => 7.8845, STARDEC => 25.0216388888889, STARMAGNITUDE => 9.15 ],
					'1543406400' => [STARRA => 8.08583333333333, STARDEC => 27.6144166666667, STARMAGNITUDE => 9.16 ],
					'1544011200' => [STARRA => 8.25811111111111, STARDEC => 30.3691666666667, STARMAGNITUDE => 9.23 ],
					'1544616000' => [STARRA => 8.39669444444444, STARDEC => 33.2186944444444, STARMAGNITUDE => 9.35 ],
					'1545220800' => [STARRA => 8.49869444444444, STARDEC => 36.0665833333333, STARMAGNITUDE => 9.53 ],
					'1545825600' => [STARRA => 8.563, STARDEC => 38.798, STARMAGNITUDE => 9.77 ],
					'1546430400' => [STARRA => 8.59061111111111, STARDEC => 41.2926944444444, STARMAGNITUDE => 10.06 ]
					],

		'0046P' => [ OENAME => '46P/Wirtanen', STARMAXMAGNITUDE => 8.87,	// 46P/Wirtanen
					STAREPOCHMIN => 1533729600, STAREPOCHMAX => 1546430400, 
					'1533124800' => [STARRA => 0.949861111111111, STARDEC => -14.0110555555556, STARMAGNITUDE => 18.7 ],
					'1533729600' => [STARRA => 1.09658333333333, STARDEC => -14.6173611111111, STARMAGNITUDE => 18.29 ],
					'1534334400' => [STARRA => 1.23702777777778, STARDEC => -15.4284166666667, STARMAGNITUDE => 17.86 ],
					'1534939200' => [STARRA => 1.36997222222222, STARDEC => -16.4580555555556, STARMAGNITUDE => 17.42 ],
					'1535544000' => [STARRA => 1.494, STARDEC => -17.7168333333333, STARMAGNITUDE => 16.96 ],
					'1536148800' => [STARRA => 1.60711111111111, STARDEC => -19.2101111111111, STARMAGNITUDE => 16.49 ],
					'1536753600' => [STARRA => 1.70691666666667, STARDEC => -20.928, STARMAGNITUDE => 16.01 ],
					'1537358400' => [STARRA => 1.79169444444444, STARDEC => -22.8353055555556, STARMAGNITUDE => 15.51 ],
					'1537963200' => [STARRA => 1.86036111111111, STARDEC => -24.8755833333333, STARMAGNITUDE => 15.01 ],
					'1538568000' => [STARRA => 1.91180555555556, STARDEC => -26.9678611111111, STARMAGNITUDE => 14.49 ],
					'1539172800' => [STARRA => 1.94602777777778, STARDEC => -28.9935, STARMAGNITUDE => 13.97 ],
					'1539777600' => [STARRA => 1.96647222222222, STARDEC => -30.7941388888889, STARMAGNITUDE => 13.45 ],
					'1540382400' => [STARRA => 1.97988888888889, STARDEC => -32.1950555555556, STARMAGNITUDE => 12.92 ],
					'1540987200' => [STARRA => 1.99544444444444, STARDEC => -33.0043055555556, STARMAGNITUDE => 12.39 ],
					'1541592000' => [STARRA => 2.02588888888889, STARDEC => -32.9753055555556, STARMAGNITUDE => 11.84 ],
					'1542196800' => [STARRA => 2.09122222222222, STARDEC => -31.7558333333333, STARMAGNITUDE => 11.28 ],
					'1542801600' => [STARRA => 2.21830555555556, STARDEC => -28.78225, STARMAGNITUDE => 10.69 ],
					'1543406400' => [STARRA => 2.44347222222222, STARDEC => -22.95875, STARMAGNITUDE => 10.06 ],
					'1544011200' => [STARRA => 2.82530555555556, STARDEC => -12.0155555555556, STARMAGNITUDE => 9.42 ],
					'1544616000' => [STARRA => 3.46627777777778, STARDEC => 7.41194444444444, STARMAGNITUDE => 8.9 ],
					'1545220800' => [STARRA => 4.50147222222222, STARDEC => 32.8675833333333, STARMAGNITUDE => 8.88 ],
					'1545825600' => [STARRA => 5.92413888888889, STARDEC => 51.0203888888889, STARMAGNITUDE => 9.41 ],
					'1546430400' => [STARRA => 7.32708333333333, STARDEC => 58.1544166666667, STARMAGNITUDE => 10.14 ]
					]
	);
	
	function __construct( $idComet ) {
		if( is_string( $idComet ) ) $this->idComet = $idComet;
		$this->build ();
	}
	
	public function build() {
		$tzEarth = new DateTimeZone( TZUTC );
		$dteToday = new DateTime( 'noon', $tzEarth );
		$dteWeekAgo = new DateTime();
		$dteWeekAgo->setTimestamp( $dteToday->getTimestamp() -86400*7 );
		if( isset( $this->orbitTable[ $this->idComet ] ) ) {
			if( isset( $this->orbitTable[ $this->idComet ][ OENAME ] ) ) {
				$this->arrObjectElements = $this->orbitTable[ $this->idComet ];
				$this->strObjectName = $this->arrObjectElements[ OENAME ];
				$this->fltMaxMagnitude = $this->arrObjectElements[ STARMAXMAGNITUDE ];
				if( $this->arrObjectElements[ STAREPOCHMIN ] <= $dteToday->getTimestamp() && $dteToday->getTimestamp() <= $this->arrObjectElements[ STAREPOCHMAX ] ) {
					foreach($this->arrObjectElements AS $keyToday => $arrToday) {
						if( is_array($arrToday) && $dteWeekAgo->getTimestamp()<$keyToday && $keyToday<=$dteToday->getTimestamp()) {	
							if( isset( $arrToday[ STARRA ] ) ) $this->fltRightAscension = $arrToday[ STARRA ];
							if( isset( $arrToday[ STARDEC ] ) ) $this->fltDeclination = $arrToday[ STARDEC ];
							if( isset( $arrToday[ STARMAGNITUDE ] ) ) $this->fltMagnitude = $arrToday[ STARMAGNITUDE ];
						}
					}
				}
			}
		}
	}

	public function magmax() { return $this->fltMaxMagnitude; }
}


/***
 *
 *  Eclipse
 *
 *  Look up the data for lunar and solar eclipses
 *
 *  Classes and methods
 *    Eclipse - Name, Date, Time, Constellation 
 *
 *    Moon -  RA(), Dec(), Alt(), Az(), Rise(), Set(), Distance( RA, Dec )
 *         -  Penumbra Begins, Umbra Begins, Totality begins, Totality Ends, Umbra Ends, Penumbra Ends
 *
Calendar Date	TD of Greatest Eclipse	Eclipse Type	Central Eclipse Class	Saros Series	Gamma	Eclipse Magnitude	Path Width (km)	Central Duration																	
2017 Feb 26	14:54:33	Annular	central	140	-0.4578	0.9922	30.6	00m44s																	
2017 Aug 21	18:26:40	Total	central	145	0.4367	1.0306	114.7	02m40s																	
2018 Feb 15	20:52:33	Partial	150	0.599	-	Antarctica, s S. America
2018 Jul 13	03:02:16	Partial	117	0.336	-	s Australia
2018 Aug 11	09:47:28	Partial	155	0.737	-	n Europe, ne Asia
2019 Jan 06	01:42:38	Partial	122	0.715	-	ne Asia, n Pacific
2019 Jul  2	19:24:07	Total	central	127	-0.6466	1.0459	200.6	04m33s	https://eclipse.gsfc.nasa.gov/SEpath/SEpath2001/SE2019Jul02Tpath.html																
2019 Dec 26	05:18:53	Annular	central	132	0.4135	0.9701	117.9	03m40s	https://eclipse.gsfc.nasa.gov/SEpath/SEpath2001/SE2019Dec26Apath.html																
2020 Jun 21	06:41:15	Annular	central	137	0.1209	0.994	21.2	00m38s	https://eclipse.gsfc.nasa.gov/SEpath/SEpath2001/SE2020Jun21Apath.html																
2020 Dec 14	16:14:39	Total	central	142	-0.2939	1.0254	90.2	02m10s	https://eclipse.gsfc.nasa.gov/SEpath/SEpath2001/SE2020Dec14Tpath.html																
2021 Jun 10	10:43:07	Annular	central	147	0.9152	0.9435	526.8	03m51s	https://eclipse.gsfc.nasa.gov/SEpath/SEpath2001/SE2021Jun10Apath.html																
2021 Dec 04	07:34:38	Total	central	152	-0.9526	1.0367	418.7	01m54s	https://eclipse.gsfc.nasa.gov/SEpath/SEpath2001/SE2021Dec04Tpath.html																
2023 Apr 20	04:17:56	Hybrid	central	129	-0.3952	1.0132	49.0	01m16s	https://eclipse.gsfc.nasa.gov/SEpath/SEpath2001/SE2023Apr20Hpath.html																
2023 Oct 14	18:00:41	Annular	central	134	0.3753	0.952	187.4	05m17s	https://eclipse.gsfc.nasa.gov/SEpath/SEpath2001/SE2023Oct14Apath.html																
2024 Apr 08	18:18:29	Total	central	139	0.3431	1.0566	197.5	04m28s																	
2024 Oct 02	18:46:13	Annular	central	144	-0.3509	0.9326	266.5	07m25s																	
2026 Feb 17	12:13:06	Annular	central	121	-0.9743	0.963	616.3	02m20s																	
2026 Aug 12	17:47:06	Total	central	126	0.8977	1.0386	293.9	02m18s																	
2027 Feb 06	16:00:48	Annular	central	131	-0.2952	0.9281	281.6	07m51s																	
2027 Aug 02	10:07:50	Total	central	136	0.1421	1.079	257.7	06m23s																	
2028 Jan 26	15:08:59	Annular	central	141	0.3901	0.9208	323.0	10m27s																	
2028 Jul 22	02:56:40	Total	central	146	-0.6056	1.056	230.2	05m10s																	
2030 Jun 01	06:29:13	Annular	central	128	0.5626	0.9443	249.6	05m21s																	
2030 Nov 25	06:51:37	Total	central	133	-0.3867	1.0468	169.3	03m44s																	
2031 May 21	07:16:04	Annular	central	138	-0.197	0.9589	152.2	05m26s																	
2031 Nov 14	21:07:31	Hybrid	central	143	0.3078	1.0106	38.3	01m08s																	
2032 May 09	13:26:42	Annular	central	148	-0.9375	0.9957	43.7	00m22s																	
2033 Mar 30	18:02:36	Total	central	120	0.9778	1.0462	781.1	02m37s																	
2034 Mar 20	10:18:45	Total	central	130	0.2894	1.0458	159.1	04m09s																	
2034 Sep 12	16:19:28	Annular	central	135	-0.3936	0.9736	102.1	02m58s																	
2035 Mar 09	23:05:54	Annular	central	140	-0.4368	0.9919	31.5	00m48s																	
2035 Sep 02	01:56:46	Total	central	145	0.3727	1.032	116.3	02m54s																	
2037 Jul 13	02:40:36	Total	central	127	-0.7246	1.0413	200.6	03m58s																	
2038 Jan 05	13:47:11	Annular	central	132	0.4169	0.9728	107.2	03m18s																	
2038 Jul 02	13:32:55	Annular	central	137	0.0398	0.9911	31.2	01m00s																	
2038 Dec 26	01:00:10	Total	central	142	-0.2881	1.0268	95.2	02m18s																	
2039 Jun 21	17:12:54	Annular	central	147	0.8312	0.9454	365.3	04m05s																	
2039 Dec 15	16:23:46	Total	central	152	-0.9458	1.0356	379.8	01m51s																	
 *

Lunar Eclipses: 2011 - 2020
Calendar Date	TD of Greatest Eclipse	Eclipse Type	Saros Series	Umbral Magnitude	Partial Duration	Total Duration	Geographic Region of Eclipse Visibility
(Link to Figure)						(Link to RASC Observers Handbook)
2011 Jun 15	20:13:43	Total	130	1.700	03h39m	01h40m	S.America, Europe, Africa, Asia, Aus.
2011 Dec 10	14:32:56	Total	135	1.106	03h32m	00h51m	Europe, e Africa, Asia, Aus., Pacific, N.A.
2012 Jun 04	11:04:20	Partial	140	0.370	02h07m		Asia, Aus., Pacific, Americas
2012 Nov 28	14:34:07	Penumbral	145	-0.187	-		Europe, e Africa, Asia, Aus., Pacific, N.A.
2013 Apr 25	20:08:38	Partial	112	0.015	00h27m		Europe, Africa, Asia, Aus.
2013 May 25	04:11:06	Penumbral	150	-0.934	-		Americas, Africa
2013 Oct 18	23:51:25	Penumbral	117	-0.272	-		Americas, Europe, Africa, Asia
2014 Apr 15	07:46:48	Total	122	1.291	03h35m	01h18m	Aus., Pacific, Americas
2014 Oct 08	10:55:44	Total	127	1.166	03h20m	00h59m	Asia, Aus., Pacific, Americas
2015 Apr 04	12:01:24	Total	132	1.001	03h29m	00h05m	Asia, Aus., Pacific, Americas
2015 Sep 28	02:48:17	Total	137	1.276	03h20m	01h12m	e Pacific, Americas, Europe, Africa, w Asia
2016 Mar 23	11:48:21	Penumbral	142	-0.312	-		Asia, Aus., Pacific, w Americas
2016 Sep 16	18:55:27	Penumbral	147	-0.064	-		Europe, Africa, Asia, Aus., w Pacific
2017 Feb 11	00:45:03	Penumbral	114	-0.035	-		Americas, Europe, Africa, Asia
2017 Aug 07	18:21:38	Partial	119	0.246	01h55m		Europe, Africa, Asia, Aus.
2018 Jan 31	13:31:00	Total	124	1.315	03h23m	01h16m	Asia, Aus., Pacific, w N.America
2018 Jul 27	20:22:54	Total	129	1.609	03h55m	01h43m	S.America, Europe, Africa, Asia, Aus.
2019 Jan 21	05:13:27	Total	134	1.195	03h17m	01h02m	c Pacific, Americas, Europe, Africa
2019 Jul 16	21:31:55	Partial	139	0.653	02h58m		S.America, Europe, Africa, Asia, Aus.
2020 Jan 10	19:11:11	Penumbral	144	-0.116	-		Europe, Africa, Asia, Aus.
2020 Jun 05	19:26:14	Penumbral	111	-0.405	-		Europe, Africa, Asia, Aus.
2020 Jul 05	04:31:12	Penumbral	149	-0.644	-		Americas, sw Europe, Africa
2020 Nov 30	09:44:01	Penumbral	116	-0.262	-		Asia, Aus., Pacific, Americas
 *
 ***/
class Eclipse extends Moon {
	private $_eclipseElements = array (
		 [ ECLDATE => '2017 Feb 26', 	ECLBEGIN => '14:54:11', ECLPEAK => '14:54:33', ECLEND => '14:54:55', ECLTYPE => 'Annular', 	ECLDURATION => '00m44s' ],
		 [ ECLDATE => '2017 Aug 21', 	ECLBEGIN => '18:25:20', ECLPEAK => '18:26:40', ECLEND => '18:28:00', ECLTYPE => 'Total Solar', 	ECLDURATION => '02m40s' ],
		 [ ECLDATE => '2018 Feb 15', 	ECLBEGIN => '20:22:33', ECLPEAK => '20:52:33', ECLEND => '21:22:33', ECLTYPE => 'Partial Solar', 	ECLDURATION => '01h00' ],
		 [ ECLDATE => '2018 Jul 13', 	ECLBEGIN => '02:32:16', ECLPEAK => '03:02:16', ECLEND => '03:32:16', ECLTYPE => 'Partial Solar', 	ECLDURATION => '01h00' ],
		 [ ECLDATE => '2018 Aug 11', 	ECLBEGIN => '09:17:28', ECLPEAK => '09:47:28', ECLEND => '10:17:28', ECLTYPE => 'Partial Solar', 	ECLDURATION => '01h00' ],
		 [ ECLDATE => '2019 Jan 06', 	ECLBEGIN => '01:12:38', ECLPEAK => '01:42:38', ECLEND => '02:12:38', ECLTYPE => 'Partial Solar', 	ECLDURATION => '01h00' ],
		 [ ECLDATE => '2018 Jul 27', 	ECLBEGIN => '19:31:54', ECLPEAK => '20:22:54', ECLEND => '21:14:54', ECLTYPE => 'Total Lunar', 	ECLDURATION => '01h43m' ],
		 [ ECLDATE => '2019 Jan 21', 	ECLBEGIN => '04:42:27', ECLPEAK => '05:13:27', ECLEND => '05:44:27', ECLTYPE => 'Total Lunar', 	ECLDURATION => '01h02m' ],
		 [ ECLDATE => '2019 Jul 2', 	ECLBEGIN => '19:21:51', ECLPEAK => '19:24:07', ECLEND => '19:26:24', ECLTYPE => 'Total Solar', 	ECLDURATION => '04m33s' ],
		 [ ECLDATE => '2019 Jul 16', 	ECLBEGIN => '20:02:55', ECLPEAK => '21:31:55', ECLEND => '23:00:55', ECLTYPE => 'Partial Lunar', 	ECLDURATION => '02h58m' ],
		 [ ECLDATE => '2021 Jun 10', 	ECLBEGIN => '10:41:10', ECLPEAK => '10:43:06', ECLEND => '10:45:01', ECLTYPE => 'Annular Solar', 	ECLDURATION => '03m51' ],
		 [ ECLDATE => '2021 Dec 04', 	ECLBEGIN => '07:33:41', ECLPEAK => '07:34:38', ECLEND => '07:35:35', ECLTYPE => 'Total Solar', 	ECLDURATION => '01m54' ],
		 [ ECLDATE => '2022 Apr 30', 	ECLBEGIN => '19:42:36', ECLPEAK => '20:42:36', ECLEND => '21:42:36', ECLTYPE => 'Partial Solar', 	ECLDURATION => '02h00' ],
		 [ ECLDATE => '2022 Oct 25', 	ECLBEGIN => '10:01:19', ECLPEAK => '11:01:19', ECLEND => '12:01:19', ECLTYPE => 'Partial Solar', 	ECLDURATION => '02h00' ]
	);
	private $strDate; 
	private $dteBegin;
	private $dtePeak;
	private $dteEnd;
	private $strType;
	private $fltDuration;
	
	function __construct( $dteCurrent ){ 
		// Find the next solar or lunar eclipse or transit of Mercury
		$tzEarth = new DateTimeZone( TZUTC );
		$tzLocal = $dteCurrent->getTimezone();
		$dteNext = clone $dteCurrent;
		$dteNext->add( DateInterval::createFromDateString('1 year') );
		foreach( $this->_eclipseElements AS $arrEclipse ) {
			$dteEclipse = DateTime::createFromFormat( ECLDATEFORMAT, $arrEclipse[ECLDATE], $tzEarth );
			$dteEclipseStart = DateTime::createFromFormat( ECLTIMEFORMAT, $arrEclipse[ECLBEGIN], $tzEarth );
			$dteEclipsePeak = DateTime::createFromFormat( ECLTIMEFORMAT, $arrEclipse[ECLPEAK], $tzEarth );
			$dteEclipseStop = DateTime::createFromFormat( ECLTIMEFORMAT, $arrEclipse[ECLEND], $tzEarth );
			$dteEclipse->setTime($dteEclipseStart->format('H'),$dteEclipseStart->format('i'),$dteEclipseStart->format('s'));
			$dteEclipseStart->setDate($dteEclipse->format('Y'),$dteEclipse->format('m'),$dteEclipse->format('d'));
			$dteEclipsePeak->setDate($dteEclipse->format('Y'),$dteEclipse->format('m'),$dteEclipse->format('d'));
			$dteEclipseStop->setDate($dteEclipse->format('Y'),$dteEclipse->format('m'),$dteEclipse->format('d'));
			/* echo '<comment><![CDATA['. $arrEclipse[ECLDATE] . chr(13) . chr(10) . 'eclipse date'. chr(13) . chr(10); 
			print_r( $dteEclipse ); 
			echo 'start time'. chr(13) . chr(10); 
			print_r( $dteEclipseStart ); 
			echo 'peak time'. chr(13) . chr(10); 
			print_r( $dteEclipsePeak ); 
			echo 'stop time'. chr(13) . chr(10); 
			print_r( $dteEclipseStop ); 
			echo 'next date'. chr(13) . chr(10); 
			print_r( $dteNext ); 
			echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10); */
			if( $dteEclipseStart->getTimestamp() < $dteCurrent->getTimestamp() && $dteCurrent->getTimestamp() < $dteEclipseStop->getTimestamp() ) {
				// this is the current eclipse
				$this->dteBegin = clone $dteEclipseStart;
				$this->dtePeak = clone $dteEclipsePeak;
				$this->dteEnd = clone $dteEclipseStop;
				$this->dteBegin->setTimezone( $tzLocal );
				$this->dtePeak->setTimezone( $tzLocal );
				$this->dteEnd->setTimezone( $tzLocal );
				$this->strType = $arrEclipse[ECLTYPE];
				if( $arrEclipse[ECLDURATION] == 'm' ) {
					$this->fltDuration = DateTime::createFromFormat( ECLSDFORMAT, $arrEclipse[ECLDURATION] ); 
				} else {
					$this->fltDuration = DateTime::createFromFormat( ECLLDFORMAT, $arrEclipse[ECLDURATION] ); 
				}
				$this->strType = $arrEclipse[ECLTYPE];
				break;
			} elseif( $dteEclipseStart->getTimestamp() < $dteNext->getTimestamp() && $dteCurrent->getTimestamp() < $dteEclipseStop->getTimestamp() ) {
				// this is the next eclipse
				$dteNext = clone $dteEclipseStart;
				$this->dteBegin = clone $dteEclipseStart;
				$this->dtePeak = clone $dteEclipsePeak;
				$this->dteEnd = clone $dteEclipseStop;
				$this->dteBegin->setTimezone( $tzLocal );
				$this->dtePeak->setTimezone( $tzLocal );
				$this->dteEnd->setTimezone( $tzLocal );
				$this->strType = $arrEclipse[ECLTYPE];
				if( $arrEclipse[ECLDURATION] == 'm' ) {
					$this->fltDuration = DateTime::createFromFormat( ECLSDFORMAT, $arrEclipse[ECLDURATION] ); 
				} else {
					$this->fltDuration = DateTime::createFromFormat( ECLLDFORMAT, $arrEclipse[ECLDURATION] ); 
				}
				$this->strType = $arrEclipse[ECLTYPE];
			}
		}
	}
	
	public function begin() { return( $this->dteBegin ); }
	public function peak() { return( $this->dtePeak ); }
	public function finish() { return( $this->dteEnd ); }
	public function type() { return( $this->strType ); }
	public function duration() { return( $this->fltDuration ); }
}


/***
 *
 *  Aurora forecast
 *
 ***/

/***
 *
 *  Get sun times for events to see if the event is visible at any time
 *
 *  Input the rise and set times (opposition time for aurora) and magnitude (5.5 for aurora)
 *
 *  The brighter the object, the earlier after sunset it can be seen
 *    The crescent moon, when it is more than 25% illuminated, can be seen at anytime
 *    Venus and Jupiter, at maginitudes -3.8 and -1.6, can be seen at sunset until sunrise 
 *    First and second magnitude stars and planets cannot be seen until the end of civil twilight
 *    Third and fourth magnitude stars cannot be seen until the end of nautical twilight
 *    The moon illuminates the sky as if it was nautical twilight
 *    Fifth and sixth magnitude stars and aurora cannot be seen until the end of astronomical twilight
 *
 *  Object times are given as evening viewing (until peak or bottom) and morning viewing
 *  in case the object is visible twice, as in it sets and also rises during the night.
 *  If the Sun never rises and the object never sets,  
 *  the object rise and set times are already set to the bottom time, the time the object crosses the meridian at its lowest above the horizon,
 *  and the Sun rise and set times are already set to the transit time, the time the sun crosses the meridian at its highest below the horizon.
 *  So, 
 *
 *  Returns an array, start, stop
 *
 ***/
function getStartStopTimes( $tsRise, $tsSet, $fltLat, $fltLng, $fltMag ) {
	// get the suntimes for today and tomorrow
	$arrToday = date_sun_info( time(), $fltLat, $fltLng );
	$arrTomorrow = date_sun_info( time()+86400, $fltLat, $fltLng );
	$tsOpposition = ( $arrToday[SUNTRANSIT] + $arrTomorrow[SUNTRANSIT] )/2;
	foreach($arrToday AS $keyToday => $tsToday) {
		if( is_null($tsToday) ) {	// Object never rises
			$arrToday[ $keyToday ] = $tsOpposition;
		}
		if( $tsToday == 1 ) {	// Object never sets
			$arrToday[ $keyToday ] = $arrToday[SUNTRANSIT];
		}
	}
	foreach($arrTomorrow AS $keyTomorrow => $tsTomorrow) {
		if( is_null($tsTomorrow) ) {	// Object never rises
			$arrTomorrow[ $keyTomorrow ] = $tsOpposition;
		}
		if( $tsTomorrow == 1 ) {	// Object never sets
			$arrTomorrow[ $keyTomorrow ] = $arrTomorrow[SUNTRANSIT];
		}
	}
	$tsStart = $arrToday[SUNSET];
	$tsStop = $arrTomorrow[SUNRISE];
	/*if( $tsRise < $tsSet ) {*/
	//echo '<comment>'; print_r( $arrToday ); print_r( $arrTomorrow ); echo '</comment>' . chr(13) . chr(10);
		if( $fltMag >= 6.0 ) {
			$tsStart = $arrToday[ASTROTWILIGHTEND];
			$tsStop = $arrTomorrow[ASTROTWILIGHTBEG]; 
		}
		elseif( $fltMag >= 4.0 ) {
			$tsStart = $arrToday[NAUTTWILIGHTEND] - ($fltMag-4.0)/2*($arrToday[NAUTTWILIGHTEND]-$arrToday[ASTROTWILIGHTEND]);
			$tsStop = $arrTomorrow[NAUTTWILIGHTBEG] - ($fltMag-4.0)/2*($arrTomorrow[NAUTTWILIGHTBEG]-$arrTomorrow[ASTROTWILIGHTBEG]); 
		}
		elseif( $fltMag >= 2.0 ) {
			$tsStart = $arrToday[CIVILTWILIGHTEND] - ($fltMag-2.0)/2*($arrToday[CIVILTWILIGHTEND]-$arrToday[NAUTTWILIGHTEND]);
			$tsStop = $arrTomorrow[CIVILTWILIGHTBEG] - ($fltMag-2.0)/2*($arrTomorrow[CIVILTWILIGHTBEG]-$arrTomorrow[NAUTTWILIGHTBEG]); 
		}
		elseif( $fltMag >= 0.0 ) {
			$tsStart = $arrToday[SUNSET] - ($fltMag)/2*($arrToday[SUNSET]-$arrToday[CIVILTWILIGHTEND]); 	// Before Sunrise;
			$tsStop = $arrTomorrow[SUNRISE] - ($fltMag)/2*($arrTomorrow[SUNRISE]-$arrTomorrow[CIVILTWILIGHTBEG]);		// After  Sunset; 
		}
		elseif( $fltMag >= -6.0 ) {
			$tsStart = $arrToday[SUNSET] - ($fltMag)/4*($arrToday[SUNSET]-$arrToday[CIVILTWILIGHTEND]); 	// After Sunrise;
			$tsStop = $arrTomorrow[SUNRISE] - ($fltMag)/4*($arrTomorrow[SUNRISE]-$arrTomorrow[CIVILTWILIGHTBEG]);		// Before  Sunset; 
		}/*
	}
	else {
	echo '<comment>'; print_r( $arrToday ); print_r( $arrTomorrow ); echo '</comment>' . chr(13) . chr(10);
		if( $fltMag >= 6.0 ) {
			$tsStop = $arrToday[ASTROTWILIGHTBEG]; 
			$tsStart = $arrTomorrow[ASTROTWILIGHTEND];
		}
		elseif( $fltMag >= 4.0 ) {
			$tsStop = $arrToday[NAUTTWILIGHTBEG] + (6.0-$fltMag)*($arrToday[NAUTTWILIGHTBEG]-$arrToday[ASTROTWILIGHTBEG]); 
			$tsStart = $arrTomorrow[NAUTTWILIGHTEND] + (6.0-$fltMag)*($arrTomorrow[NAUTTWILIGHTEND]-$arrTomorrow[ASTROTWILIGHTEND]);
		}
		elseif( $fltMag >= 2.0 ) {
			$tsStop = $arrToday[CIVILTWILIGHTBEG] + (4.0-$fltMag)*($arrToday[CIVILTWILIGHTBEG]-$arrToday[NAUTTWILIGHTBEG]); 
			$tsStart = $arrTomorrow[CIVILTWILIGHTEND] + (4.0-$fltMag)*($arrTomorrow[CIVILTWILIGHTEND]-$arrTomorrow[NAUTTWILIGHTEND]);
		}
		elseif( $fltMag >= 0.0 ) {
			$tsStop = $arrToday[SUNRISE] + (2.0-$fltMag)*($arrToday[SUNRISE]-$arrToday[CIVILTWILIGHTBEG]); 	// Before Sunrise
			$tsStart = $arrTomorrow[SUNSET] + (integer)(2.0-$fltMag)*($arrTomorrow[SUNSET]-$arrTomorrow[CIVILTWILIGHTEND]);		// After  Sunset
		}
	}*/
	/*if( $tsRise < $tsSet ) {
		if( $tsStart < $tsRise ) $tsStart = $tsRise;
		if( $tsSet < $tsStop ) $tsStop = $tsSet;
		return( [ STARRISE => $tsRise, STARSTART => (integer)$tsStart, STARSTOP => (integer)$tsStop, STARSET => $tsSet ] );
	} else*//*if( $tsRise < $tsSet )  {
		if( $tsSet < $tsStart ) $tsStart = $tsRise;
		if( $tsStop < $tsRise ) $tsStop = $tsSet;
		return( [ STARSTART => (integer)$tsStart, STARSET => $tsSet, STARRISE => $tsRise, STARSTOP => (integer)$tsStop ] );
	}*/
	if( $tsRise != $tsSet ) {
		$arrReturn = [ STARRISE => $tsRise, STARSTART => (integer)$tsStart, STARSTOP => (integer)$tsStop, STARSET => $tsSet ];
	} else {
		$arrReturn = [ STARRISE => (integer)$tsStart, STARSTART => (integer)$tsStart, STARSTOP => (integer)$tsStop, STARSET => (integer)$tsStop ];
	}
	asort( $arrReturn, SORT_NUMERIC );
	// echo '<comment>'; print_r( $arrReturn ); echo '</comment>' . chr(13) . chr(10);
	return( $arrReturn );
}
 
/***
 * 
 * Function: file_post_contents
 * 
 * Parameters:
 *   $url:	The web address of the form action destination and the parameters to use for posting
 *     scheme: (Optional) the scheme (http or https)
 *     protocol:
 *     host:
 *     port: (Optional) the port to use for posting ( e.g. http://domain.com:<port> )
 *     path:
 *     query:
 * 
 * Usage: file_post_contents('<formHTTPaddress>?<getStyleParameters>');
 * Example: file_post_contents('http://heavens-above.com/AllSats.aspx?lat=43.8248&lng=-80.0041
                                                                     &loc=Forks+of+the+Credit+Provincial+Park
                                                                     &alt=406&tz=EST
                                                                     &ctl00$cph1$TimeSelectionControl1$comboMonth=24213
                                                                     &ctl00$cph1$TimeSelectionControl1$comboDay=9
                                                                     &ctl00$cph1$TimeSelectionControl1$radioAMPM=AM
                                                                     &ctl00$cph1$radioButtonsMag=3.0');
 * 
 * http://php.net/manual/en/function.file-get-contents.php#80435
 *
 **/
function file_post_contents($url,$headers=false) {
    $url = parse_url($url);

    if (!isset($url['port'])) {
      if ($url['scheme'] == 'http') { $url['port']=80; }
      elseif ($url['scheme'] == 'https') { $url['port']=443; }
    }
    $url['query']=isset($url['query'])?$url['query']:'';

    $url['protocol']=$url['scheme'].'://';
    $eol="\r\n";

    $headers =  "POST ".$url['protocol'].$url['host'].$url['path']." HTTP/1.0".$eol. 
                "Host: ".$url['host'].$eol. 
                "Referer: ".$url['protocol'].$url['host'].$url['path'].$eol. 
                "Content-Type: application/x-www-form-urlencoded".$eol. 
                "Content-Length: ".strlen($url['query']).$eol.
                $eol.$url['query'];
    $fp = fsockopen($url['host'], $url['port'], $errno, $errstr, 30); 
    if($fp) {
      fputs($fp, $headers);
      $result = '';
      while(!feof($fp)) { $result .= fgets($fp, 128); }
      fclose($fp);
      if (!$headers) {
        //removes headers
        $pattern="/^.*\r\n\r\n/s";
        $result=preg_replace($pattern,'',$result);
      }
      return $result;
    }
}


$arrEvents = [];
$aurora = new Aurora();
if( $blnVerbose ) {
	echo ' <comment>'.  $aurora->nightForecast()[ AURORANAME ] .' -> '. $aurora->nightForecast()[ AURORALEVEL ] .'</comment>' . chr(13) . chr(10);
	echo ' <comment>'.  $aurora->hourForecast()[ AURORANAME ] .' -> '. $aurora->hourForecast()[ AURORALEVEL ]  .'</comment>' . chr(13) . chr(10);
}
if( $aurora->nightForecast()[ AURORALEVEL ] >= $intAuroraLevel || $aurora->hourForecast()[ AURORALEVEL ] >= $intAuroraLevel ) { $arrEvents[] = [ EVENTTYPE => AURORAEVENT ]; } 

/***
 *
 *  List of fixed bright stars near the ecliptic
 *
	0.85	Aldebaran 	4.598333333	16.50916667
	1.04	Spica	13.41833333	-11.16133333
	1.09	Antares 	16.49	-26.43183333
	1.15	Pollux 	7.755	28.026
	1.35	Regulus	10.13833333	11.96716667
	1.68	El Nath	5.436666667	28.6075
	1.90	Alhena 	6.628333333	16.39916667
	2.01	Algieba 	10.33166667	19.84166667
	2.06	Nunki	18.921	-29.29671667
	2.29	Dschubba 	16.005	-22.62166667
	2.43	Sabik 	17.17166667	-15.72466667
	2.75	Zubenelgenubi	14.84666667	-16.04166667										
 *
	Algol	2.12 	3.39 	2.86730 d 	3.133369861	40.95564722	
	Mira 	2.0 	10.1 	332 d 	2.316886694	-2.9776375	
 	Chi Cyg 	3.3 	14.2 	408 d 	32.91405556	117.5163417	
 ***/

$arrEclipticStars = [
	[	STARMAGNITUDE => 0.85,	STARNAME => 'Aldebaran', 	STARRA => 4.598333333,	STARDEC => 16.50916667	],
	[	STARMAGNITUDE => 1.68,	STARNAME => 'El Nath',	STARRA => 5.436666667,	STARDEC => 28.6075	],
	[	STARMAGNITUDE => 1.90,	STARNAME => 'Alhena', 	STARRA => 6.628333333,	STARDEC => 16.39916667	],
	[	STARMAGNITUDE => 1.15,	STARNAME => 'Pollux', 	STARRA => 7.755,	STARDEC => 28.026	],
	[	STARMAGNITUDE => 1.35,	STARNAME => 'Regulus',	STARRA => 10.13833333,	STARDEC => 11.96716667	],
	[	STARMAGNITUDE => 2.01,	STARNAME => 'Algieba', 	STARRA => 10.33166667,	STARDEC => 19.84166667	],
	[	STARMAGNITUDE => 1.04,	STARNAME => 'Spica',	STARRA => 13.41833333,	STARDEC => -11.16133333	],
	[	STARMAGNITUDE => 2.75,	STARNAME => 'Zubenelgenubi',	STARRA => 14.84666667,	STARDEC => -16.04166667	],										
	[	STARMAGNITUDE => 2.29,	STARNAME => 'Dschubba', 	STARRA => 16.005,	STARDEC => -22.62166667	],
	[	STARMAGNITUDE => 1.09,	STARNAME => 'Antares', 	STARRA => 16.49,	STARDEC => -26.43183333	],
	[	STARMAGNITUDE => 2.43,	STARNAME => 'Sabik', 	STARRA => 17.17166667,	STARDEC => -15.72466667	],
	[	STARMAGNITUDE => 2.06,	STARNAME => 'Nunki',	STARRA => 18.921,	STARDEC => -29.29671667	]
		];
$objStars = [];
foreach( $arrEclipticStars AS $arrStar ) {
	$objStars[ $arrStar[STARNAME] ] = new FixedStar( $arrStar[STARNAME], [ STARRA => $arrStar[STARRA], STARDEC => $arrStar[STARDEC], STARMAGNITUDE => $arrStar[STARMAGNITUDE] ] );
}
$arrEclipsingStars = [
	[	STARNAME => 'Algol', STARMAXMAGNITUDE => 2.12,	STARMINMAGNITUDE => 3.39,	STARPERIOD => 2.86730,	STARRA => 3.133369861,	STARDEC => 40.95564722	], 
	[	STARNAME => 'Beta Persei', STARMAXMAGNITUDE => 2.12,	STARMINMAGNITUDE => 3.39, STARDATE => time()+86400*2.75,	STAREPOCH => 2445641.554, 	STARPERIOD => 2.867324,	STARRA => 3.133369861,	STARDEC => 40.95564722	],
	[ STARNAME => 'DelLib', STARMAXMAGNITUDE => 4.91,	STARMINMAGNITUDE => 5.9,	STARRA => 15.01620833,	STARDEC => 8.518938889, 	STAREPOCH => 2445641.554, 	STARPERIOD => 2.0 ],
	[ STARNAME => 'uHer', STARMAXMAGNITUDE => 4.69,	STARMINMAGNITUDE => 5.37,	STARRA => 17.2887778,	STARDEC => 33.1, 	STAREPOCH => 2445641.554, 	STARPERIOD => 2.0 ],
	[ STARNAME => 'R CMa', STARMAXMAGNITUDE => 5.7,	STARMINMAGNITUDE => 6.34,	STARRA => 7.3244944,	STARDEC => -16.39525, 	STAREPOCH => 2445641.554, 	STARPERIOD => 1.0 ]		
		];
foreach( $arrEclipsingStars AS $arrStar ) {
	$objStars[ $arrStar[STARNAME] ] = new AlgolStar( $arrStar[STARNAME], $arrStar );
}

$arrMiraStars = [
	[	STARNAME => 'Mira', STARMAGNITUDE => 3.04,	STARMAXMAGNITUDE => 2.0,	STARMINMAGNITUDE => 10.1,	STAREPOCH => 2457809.334,	STARPERIOD => 331.96,	STAREPOCHMIN => 2458350.64140,	STAREPOCHMAX => 2458463.334,	STARRA => 2.316886694,	STARDEC => -2.9776375	],
	[	STARNAME => 'Chi Cyg', STARMAGNITUDE => 6.06,	STARMAXMAGNITUDE => 3.3,	STARMINMAGNITUDE => 14.2,	STAREPOCH => 2457646.29166,	STARPERIOD => 408.05,	STAREPOCHMIN => 2458708.55000,	STAREPOCHMAX => 2458460.50000,	STARRA => 19.8427555,	STARDEC => 32.91405556	],
	[	STARNAME => 'RR Sco', STARMAGNITUDE => 6.06,	STARMAXMAGNITUDE => 5.0,	STARMINMAGNITUDE => 12.4,	STAREPOCH => 2458213.50000,	STARPERIOD => 281.45,	STAREPOCHMIN => 2458436.50000,	STAREPOCHMAX => 2458563.95000,	STARRA => 16.943844,	STARDEC => -30.58005556	],
	[	STARNAME => 'R Car', STARMAGNITUDE => 6.06,	STARMAXMAGNITUDE => 4.5,	STARMINMAGNITUDE => 9.9,	STAREPOCH => 2458213.50000,	STARPERIOD => 308.71,	STAREPOCHMIN => 2458432.50000,	STAREPOCHMAX => 2458580.21000,	STARRA => 9.537386111,	STARDEC => -62.78888889	],
	[	STARNAME => 'R Hya', STARMAGNITUDE => 6.06,	STARMAXMAGNITUDE => 3.5,	STARMINMAGNITUDE => 10.9,	STAREPOCH => 2458119.50000,	STARPERIOD => 360.0,	STAREPOCHMIN => 2458602.50000,	STAREPOCHMAX => 2458388.50000,	STARRA => 13.49521389,	STARDEC => -23.28130556	],
	[	STARNAME => 'R Leo', STARMAGNITUDE => 6.06,	STARMAXMAGNITUDE => 4.5,	STARMINMAGNITUDE => 9.9,	STAREPOCH => 2458204.50000,	STARPERIOD => 309.95,	STAREPOCHMIN => 2458384.50000,	STAREPOCHMAX => 2458515.50000,	STARRA => 9.792636111,	STARDEC => 11.42880556	],
	[	STARNAME => 'S Car', STARMAGNITUDE => 6.06,	STARMAXMAGNITUDE => 4.5,	STARMINMAGNITUDE => 9.9,	STAREPOCH => 2458285.50000,	STARPERIOD => 273.35,	STAREPOCHMIN => 2458360.50000,	STAREPOCHMAX => 2458435.50000,	STARRA => 10.15608056,	STARDEC => -61.54897222	],
	[	STARNAME => 'T Cen', STARMAGNITUDE => 6.55,	STARMAXMAGNITUDE => 5.5,	STARMINMAGNITUDE => 9.0,	STAREPOCH => 2458336.50000,	STARPERIOD => 90.6,	STAREPOCHMIN => 2458381.50000,	STAREPOCHMAX => 2458427.50000,	STARRA => 13.69598611,	STARDEC => -33.59738889	]
		];
foreach( $arrMiraStars AS $arrStar ) {
	// $objStars[ $arrStar[STARNAME] ] = new MiraStar( $arrStar[STARNAME], [ STARRA => $arrStar[STARRA], STARDEC => $arrStar[STARDEC], STARPERIOD => $arrStar[STARPERIOD], STARMAXMAGNITUDE => $arrStar[STARMAXMAGNITUDE], STARMINMAGNITUDE => $arrStar[STARMINMAGNITUDE] ] );
	$objStars[ $arrStar[STARNAME] ] = new MiraStar( $arrStar[STARNAME], $arrStar );
}

$objNextShower = new MeteorShower( $dteDisplayTime );
if( $blnVerbose ) {
	echo ' <comment><![CDATA['; print_r( $objNextShower ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
}

$objNextEclipse = new Eclipse( $dteDisplayTime );
if( $blnVerbose ) {
	echo ' <comment><![CDATA['; print_r( $objNextEclipse ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
} /****/


// Moon at 14:32:32 on Sept 8, 2017: Right ascension 	1h 2m 28s	Declination	2deg 8' 24"
/****
	Mercury	Venus	Mars	Jupiter	Saturn	Uranus	Neptune	Pluto
Right ascension	10h 3m 18.2s	9h 13m 54.4s	10h 16m 57.2s	13h 27m 27.5s	17h 21m 34.9s	1h 43m 58.7s	22h 56m 56.5s	19h 12m 6.5s
Declination	11deg 16' 17"	16deg 30' 23"	11deg 55' 26"	-8deg 0' 36"	-22deg 0' 23"	10deg 7' 8"	-7deg 44' 5"	-21deg 44' 27"
Range (AU)	0.834	1.386	2.620	6.213	9.920	19.153	28.941	32.875
Elongation from Sun	17.0deg	30.0deg	14.1deg	37.3deg	95.2deg	138.1deg	176.5deg	120.8deg
Brightness	0.5	-3.8	1.8	-1.6	0.4	5.7	7.8	14.2
****/
$objPlanets = [];
$objPlanets['Moon'] = new Moon( 'Moon', [ STARRA => 1 +2/60 +28/3600,	STARDEC => 2 +8/60 +24/3600, STARMAGNITUDE => -14.5, LATITUDE => $fltLatLngAlt[LATITUDE], LONGITUDE => $fltLatLngAlt[LONGITUDE], STARDATE => $dteDisplayTime ] );
$objStars[ 'Moon' ] = $objPlanets['Moon'];
$objPlanets['Mercury'] = new Planet( 'Mercury', [ STARRA => 20 +0/60 +36.0/3600,	STARDEC => -22 -09/60 -49/3600, STARMAGNITUDE => 0.5, STARDATE => $dteDisplayTime ] );
$objStars[ 'Mercury' ] = $objPlanets['Mercury'];
$objPlanets['Venus'] = new Planet( 'Venus', [ STARRA => 21 +12/60 +07.0/3600,	STARDEC => -17 -32/60 -44/3600, STARMAGNITUDE => -3.8, STARDATE => $dteDisplayTime ] );
$objStars[ 'Venus' ] = $objPlanets['Venus'];
$objPlanets['Mars'] = new Planet( 'Mars', [ STARRA => 16 +01/60 +05.0/3600,	STARDEC => -19 -57/60 -08/3600, STARMAGNITUDE => 1.8, STARDATE => $dteDisplayTime ] );
$objStars[ 'Mars' ] = $objPlanets['Mars'];
$objPlanets['Jupiter'] = new Planet( 'Jupiter', [ STARRA => 15 +15/60 +43.0/3600,	STARDEC => -16 -57/60 -07/3600, STARMAGNITUDE => -1.6, STARDATE => $dteDisplayTime ] );
$objStars[ 'Jupiter' ] = $objPlanets['Jupiter'];
$objPlanets['Saturn'] = new Planet( 'Saturn', [ STARRA => 18 +20/60 +08.0/3600,	STARDEC => -22 -28/60 -35/3600, STARMAGNITUDE => 0.4, STARDATE => $dteDisplayTime ] );
$objStars[ 'Saturn' ] = $objPlanets['Saturn'];
foreach( $objStars AS $objStar ) {
	foreach( $objPlanets AS $objPlanet ) {
		$fltDistanceBetween = $objStar->distance( $objPlanet->ra(), $objPlanet->dec() );
		// echo ' <comment>'. $objStar->name() . ' ' . $objPlanet->name() . ' angDist:' . $objStar->distance( $objPlanet->ra(), $objPlanet->dec() ) .'</comment>' . chr(13) . chr(10);
		if( $fltDistanceBetween < $fltDistCheck 
				&& $objStar->name() != $objPlanet->name() ) {
			$arrEvents[] = [ EVENTTYPE => STARCONJ, $objStar->name(), $objPlanet->name(), STARDIST => $fltDistanceBetween ];
		}
	}
	// I hope that we don't need the planets anymore.
	if( isset( $objPlanets[ $objStar->name() ] ) ) { unset( $objPlanets[ $objStar->name() ] ); }
}

// Go get the satellite events
// $fltLatLngAlt[TZTIMEZONE] = 
$objSatelliteEvening = new Satellite_Page( $fltLatLngAlt, 'evening' );
//$objSatelliteMorning = new Satellite_Page( $fltLatLngAlt, 'morning' );
//echo ' <comment><![CDATA['; print_r( $objSatelliteEvening ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
//echo ' <comment><![CDATA['; print_r( $objSatelliteMorning ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
$objISS = new ISS_Page( $fltLatLngAlt, 'morning' );
$objIridium = new Iridium_Page( $fltLatLngAlt );
// echo ' <comment><![CDATA['; print_r( $objIridium ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);

// echo ' <comment><![CDATA['; print_r( $arrEvents ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
echo ' <observatory>' . chr(13) . chr(10);
echo '  <latitude>'. $fltLatLngAlt[LATITUDE] .'</latitude>' . chr(13) . chr(10);
echo '  <longitude>'. $fltLatLngAlt[LONGITUDE] .'</longitude>' . chr(13) . chr(10);
echo '  <elevation>'. $fltLatLngAlt[ALTITUDE] .'</elevation>' . chr(13) . chr(10);
echo ' </observatory>' . chr(13) . chr(10);
echo ' <parameters>' . chr(13) . chr(10);
echo '  <minauroralevel>'. $intAuroraLevel .'</minauroralevel>' . chr(13) . chr(10);
echo '  <maxangdist>'. $fltDistCheck  .'</maxangdist>' . chr(13) . chr(10);
echo ' </parameters>' . chr(13) . chr(10);

// Events created
$objRaDec = new radec( $fltLatLngAlt[LATITUDE], $fltLatLngAlt[LONGITUDE]);
$dteLocalTime = new DateTime;
$objRaDec->settimefromtimestamp($dteLocalTime->getTimestamp());
echo ' <events>' . chr(13) . chr(10);
foreach( $arrEvents AS $arrEvent ) {
	switch( $arrEvent[ EVENTTYPE ] ) {
	case AURORAEVENT:
		$arrEventTimes = getStartStopTimes( $dteLocalTime->getTimestamp(), $dteLocalTime->getTimestamp(), $fltLatLngAlt[LATITUDE], $fltLatLngAlt[LONGITUDE], 6.5 );
		echo '  <aurora>' . chr(13) . chr(10);
		echo '   <auroranight level="'. $aurora->nightForecast()[ AURORALEVEL ] .'">'. $aurora->nightForecast()[ AURORANAME ] .'</auroranight>' . chr(13) . chr(10);
		echo '   <aurorahour level="'. $aurora->hourForecast()[ AURORALEVEL ] .'">'. $aurora->hourForecast()[ AURORANAME ] .'</aurorahour>' . chr(13) . chr(10);
		echo '   <start atomic="'. $arrEventTimes[ STARSTART ] .'">'. $dteDisplayTime->setTimestamp( $arrEventTimes[ STARSTART ] )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</start>' . chr(13) . chr(10);
		echo '   <stop atomic="'. $arrEventTimes[ STARSTOP ] .'">'. $dteDisplayTime->setTimestamp( $arrEventTimes[ STARSTOP ] )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</stop>' . chr(13) . chr(10);		
		echo '  </aurora>' . chr(13) . chr(10);
		break;
	case STARCONJ:
		echo '  <conjunction>' . chr(13) . chr(10);
		echo '   <object>' . chr(13) . chr(10);
		echo '    <starname>'. $arrEvent[ 0 ] .'</starname>' . chr(13) . chr(10);
		echo '    <ra>'. $objStars[ $arrEvent[ 0 ] ]->ra() .'</ra>' . chr(13) . chr(10);
		echo '    <dec>'. $objStars[ $arrEvent[ 0 ] ]->dec() .'</dec>' . chr(13) . chr(10);
		echo '    <mag>'. $objStars[ $arrEvent[ 0 ] ]->mag() .'</mag>' . chr(13) . chr(10);
		// ToDo: Big time has to make this object oriented because the Moon calculates RaDec differently
		$objStars[ $arrEvent[ 0 ] ]->setRiseSet( $fltLatLngAlt[LATITUDE], $fltLatLngAlt[LONGITUDE] );
		$objRaDec->setradec( $objStars[ $arrEvent[ 0 ] ]->ra(), $objStars[ $arrEvent[ 0 ] ]->dec() );
		echo '    <alt>'. $objRaDec->getALT($objStars[ $arrEvent[ 0 ] ]->peak()) .'</alt>' . chr(13) . chr(10);
		echo '    <az>'. $objRaDec->getAZ($objStars[ $arrEvent[ 0 ] ]->peak()) .'</az>' . chr(13) . chr(10);
		echo '    <rise atomic="'. $objStars[ $arrEvent[ 0 ] ]->rise() .'">'. $dteDisplayTime->setTimestamp( $objStars[ $arrEvent[ 0 ] ]->rise() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</rise>' . chr(13) . chr(10);
		echo '    <set atomic="'. $objStars[ $arrEvent[ 0 ] ]->set() .'">'. $dteDisplayTime->setTimestamp( $objStars[ $arrEvent[ 0 ] ]->set() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</set>' . chr(13) . chr(10);
		$arrEventTimes = getStartStopTimes( $objStars[ $arrEvent[ 0 ] ]->rise(), $objStars[ $arrEvent[ 0 ] ]->set(), $fltLatLngAlt[LATITUDE], $fltLatLngAlt[LONGITUDE], $objStars[ $arrEvent[ 0 ] ]->mag() );
		echo '    <start atomic="'. $arrEventTimes[ STARSTART ] .'">'. $dteDisplayTime->setTimestamp( $arrEventTimes[ STARSTART ] )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</start>' . chr(13) . chr(10);
		echo '    <stop atomic="'. $arrEventTimes[ STARSTOP ] .'">'. $dteDisplayTime->setTimestamp( $arrEventTimes[ STARSTOP ] )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</stop>' . chr(13) . chr(10);		
		echo '   </object>' . chr(13) . chr(10);
		if( $objStars[ $arrEvent[ 0 ] ]->mag() > $objStars[ $arrEvent[ 1 ] ]->mag() ) {  // display the viewing times based on the dimmer member
			$blnEventActive = false;
			$blnSkyActive = false;
			foreach( $arrEventTimes AS $keyEvent => $tsTime ) {
				switch( $keyEvent ) {
				case 'set':
					if( $blnEventActive && $blnSkyActive ) {
						echo '   <ends atomic="'. $tsTime .'">'. $dteDisplayTime->setTimestamp( $tsTime )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</ends>' . chr(13) . chr(10);
					}
					$blnEventActive = false;
					break;
				case 'illumination':
					if( $blnEventActive && !$blnSkyActive ) {
						echo '   <begins atomic="'. $tsTime .'">'. $dteDisplayTime->setTimestamp( $tsTime )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</begins>' . chr(13) . chr(10);
					}
					$blnSkyActive = true;
					break;
				case 'rise':
					if( !$blnEventActive && $blnSkyActive ) {
						echo '   <begins atomic="'. $tsTime .'">'. $dteDisplayTime->setTimestamp( $tsTime )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</begins>' . chr(13) . chr(10);
					}
					$blnEventActive = true;
					break;
				case 'extinction':
					if( $blnEventActive && $blnSkyActive ) {
						echo '   <ends atomic="'. $tsTime .'">'. $dteDisplayTime->setTimestamp( $tsTime )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</ends>' . chr(13) . chr(10);
					}
					$blnSkyActive = false;
					break;
				}
			}
		}
		echo '   <object>' . chr(13) . chr(10);
		echo '    <starname>'. $arrEvent[ 1 ] .'</starname>' . chr(13) . chr(10);
		echo '    <ra>'. $objStars[ $arrEvent[ 1 ] ]->ra() .'</ra>' . chr(13) . chr(10);
		echo '    <dec>'. $objStars[ $arrEvent[ 1 ] ]->dec() .'</dec>' . chr(13) . chr(10);
		echo '    <mag>'. $objStars[ $arrEvent[ 1 ] ]->mag() .'</mag>' . chr(13) . chr(10);
		$objStars[ $arrEvent[ 1 ] ]->setRiseSet( $fltLatLngAlt[LATITUDE], $fltLatLngAlt[LONGITUDE] );
		$objRaDec->setradec( $objStars[ $arrEvent[ 1 ] ]->ra(), $objStars[ $arrEvent[ 1 ] ]->dec() );
		echo '    <alt>'. $objRaDec->getALT($objStars[ $arrEvent[ 1 ] ]->peak()) .'</alt>' . chr(13) . chr(10);
		echo '    <az>'. $objRaDec->getAZ($objStars[ $arrEvent[ 1 ] ]->peak()) .'</az>' . chr(13) . chr(10);
		echo '    <rise atomic="'. $objStars[ $arrEvent[ 1 ] ]->rise() .'">'. $dteDisplayTime->setTimestamp( $objStars[ $arrEvent[ 1 ] ]->rise() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</rise>' . chr(13) . chr(10);
		echo '    <set atomic="'. $objStars[ $arrEvent[ 1 ] ]->set() .'">'. $dteDisplayTime->setTimestamp( $objStars[ $arrEvent[ 1 ] ]->set() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</set>' . chr(13) . chr(10);
		$arrEventTimes = getStartStopTimes( $objStars[ $arrEvent[ 1 ] ]->rise(), $objStars[ $arrEvent[ 1 ] ]->set(), $fltLatLngAlt[LATITUDE], $fltLatLngAlt[LONGITUDE], $objStars[ $arrEvent[ 1 ] ]->mag() );
		echo '    <start atomic="'. $arrEventTimes[ STARSTART ] .'">'. $dteDisplayTime->setTimestamp( $arrEventTimes[ STARSTART ] )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</start>' . chr(13) . chr(10);
		echo '    <stop atomic="'. $arrEventTimes[ STARSTOP ] .'">'. $dteDisplayTime->setTimestamp( $arrEventTimes[ STARSTOP ] )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</stop>' . chr(13) . chr(10);		
		echo '   ' . chr(13) . chr(10);
		echo '   </object>' . chr(13) . chr(10);
		if( $objStars[ $arrEvent[ 1 ] ]->mag() > $objStars[ $arrEvent[ 0 ] ]->mag() ) {  // display the viewing times based on the dimmer member
			$blnEventActive = false;
			$blnSkyActive = false;
			foreach( $arrEventTimes AS $keyEvent => $tsTime ) {
				switch( $keyEvent ) {
				case 'set':
					if( $blnEventActive && $blnSkyActive ) {
						echo '   <ends atomic="'. $tsTime .'">'. $dteDisplayTime->setTimestamp( $tsTime )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</ends>' . chr(13) . chr(10);
					}
					$blnEventActive = false;
					break;
				case 'illumination':
					if( $blnEventActive && !$blnSkyActive ) {
						echo '   <begins atomic="'. $tsTime .'">'. $dteDisplayTime->setTimestamp( $tsTime )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</begins>' . chr(13) . chr(10);
					}
					$blnSkyActive = true;
					break;
				case 'rise':
					if( !$blnEventActive && $blnSkyActive ) {
						echo '   <begins atomic="'. $tsTime .'">'. $dteDisplayTime->setTimestamp( $tsTime )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</begins>' . chr(13) . chr(10);
					}
					$blnEventActive = true;
					break;
				case 'extinction':
					if( $blnEventActive && $blnSkyActive ) {
						echo '   <ends atomic="'. $tsTime .'">'. $dteDisplayTime->setTimestamp( $tsTime )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</ends>' . chr(13) . chr(10);
					}
					$blnSkyActive = false;
					break;
				}
			}
		}
		echo '   <angdistance value="'. $arrEvent[ STARDIST ] .'">'. number_format($arrEvent[ STARDIST ], 2, '.', ',') .'</angdistance><statement> deg</statement>' . chr(13) . chr(10);
		echo '  </conjunction>' . chr(13) . chr(10);
		break;
	}
} 

$arrSatellitePasses = $objSatelliteEvening->getPasses();
if( sizeof( $arrSatellitePasses ) >= 1 ) {
	foreach( $arrSatellitePasses AS $objPass ) {
		echo '  <satellite>' . chr(13) . chr(10);
		echo '   <vehicle>'. $objPass->getVehicle() .'</vehicle>' . chr(13) . chr(10);
		echo '   <mag>'. $objPass->getMagnitude() .'</mag>' . chr(13) . chr(10);
		echo '   <rise atomic="'. $objPass->rise()->getTimestamp() .'">'. $objPass->rise()->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</rise>' . chr(13) . chr(10);
		echo '   <peak atomic="'. $objPass->peak()->getTimestamp() .'">'. $objPass->peak()->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</peak>' . chr(13) . chr(10);
		echo '   <set atomic="'. $objPass->set()->getTimestamp() .'">'. $objPass->set()->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</set>' . chr(13) . chr(10);
		echo '   <link>'. $objPass->getLink()[SATURL] .'</link>' . chr(13) . chr(10);
		echo '  </satellite>' . chr(13) . chr(10);
	} 
} 

$arrSatellitePasses = $objISS->getPasses();
if( sizeof( $arrSatellitePasses ) >= 1 ) {
	foreach( $arrSatellitePasses AS $objPass ) {
		echo '  <satellite>' . chr(13) . chr(10);
		echo '   <vehicle>'. $objPass->getVehicle() .'</vehicle>' . chr(13) . chr(10);
		echo '   <mag>'. $objPass->getMagnitude() .'</mag>' . chr(13) . chr(10);
		echo '   <rise atomic="'. $objPass->rise()->getTimestamp() .'">'. $objPass->rise()->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</rise>' . chr(13) . chr(10);
		echo '   <peak atomic="'. $objPass->peak()->getTimestamp() .'">'. $objPass->peak()->format( 'Ymd H\hi T' ) .'</peak>' . chr(13) . chr(10);
		echo '   <set atomic="'. $objPass->set()->getTimestamp() .'">'. $objPass->set()->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</set>' . chr(13) . chr(10);
		echo '   <link>'. $objPass->getLink()[SATURL] .'</link>' . chr(13) . chr(10);
		echo '  </satellite>' . chr(13) . chr(10);
	} 
}

$arrSatellitePasses = $objIridium->getPasses();
if( sizeof( $arrSatellitePasses ) >= 1 ) {
	foreach( $arrSatellitePasses AS $objPass ) {
		echo '  <satellite>' . chr(13) . chr(10);
		echo '   <vehicle>'. $objPass->getVehicle() .'</vehicle>' . chr(13) . chr(10);
		echo '   <mag>'. $objPass->getMagnitude() .'</mag>' . chr(13) . chr(10);
		echo '   <peak atomic="'. $objPass->peak()->getTimestamp() .'">'. $objPass->peak()->format( 'Ymd H\hi T' ) .'</peak>' . chr(13) . chr(10);
		echo '   <distance>'. $objPass->getDistance() .'</distance>' . chr(13) . chr(10);
		echo '   <link>'. $objPass->getLink()[SATURL] .'</link>' . chr(13) . chr(10);
		echo '  </satellite>' . chr(13) . chr(10);
	} 
}

foreach( $arrEclipsingStars AS $arrStar ) {
	$objStar = $objStars[ $arrStar[STARNAME] ]; 
	$objRaDec->settimefromtimestamp($objStar->datemin()->getTimestamp());
	$objRaDec->setradec( $objStar->ra(), $objStar->dec() );
		echo '  <variable>' . chr(13) . chr(10);
		echo '   <starname>'. $objStar->name() .'</starname>' . chr(13) . chr(10);
		echo '   <ra>'. $objStar->ra() .'</ra>' . chr(13) . chr(10);
		echo '   <dec>'. $objStar->dec() .'</dec>' . chr(13) . chr(10);
		echo '   <alt>'. $objRaDec->getALT($objStar->datemin()->getTimestamp()) .'</alt>' . chr(13) . chr(10);
		echo '   <az>'. $objRaDec->getAZ($objStar->datemin()->getTimestamp()) .'</az>' . chr(13) . chr(10);
		echo '   <rise atomic="'. $objStar->rise() .'">'. $dteDisplayTime->setTimestamp( $objStar->rise() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</rise>' . chr(13) . chr(10);
		echo '   <set atomic="'. $objStar->set() .'">'. $dteDisplayTime->setTimestamp( $objStar->set() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</set>' . chr(13) . chr(10);
		echo '   <mag>'. $objStar->mag() .'</mag>' . chr(13) . chr(10);
		echo '   <magmax>'. $objStar->getMaxMagnitude() .'</magmax>' . chr(13) . chr(10);
		echo '   <magmin>'. $objStar->getMinMagnitude() .'</magmin>' . chr(13) . chr(10);
		echo '   <minimum atomic="'. $objStar->datemin()->getTimestamp() .'">'. $dteDisplayTime->setTimestamp( $objStar->datemin()->getTimestamp() )->format( 'Ymd H\hi T' ) .'</minimum>' . chr(13) . chr(10);
		echo '  </variable>' . chr(13) . chr(10);
}
foreach( $arrMiraStars AS $arrStar ) {
	$objStar = $objStars[ $arrStar[STARNAME] ]; 
	$objRaDec->settimefromtimestamp($objStar->datemin()->getTimestamp());
	$objRaDec->setradec( $objStar->ra(), $objStar->dec() );
		echo '  <variable>' . chr(13) . chr(10);
		echo '   <starname>'. $objStar->name() .'</starname>' . chr(13) . chr(10);
		echo '   <ra>'. $objStar->ra() .'</ra>' . chr(13) . chr(10);
		echo '   <dec>'. $objStar->dec() .'</dec>' . chr(13) . chr(10);
		echo '   <alt>'. $objRaDec->getALT($objStar->datemin()->getTimestamp()) .'</alt>' . chr(13) . chr(10);
		echo '   <az>'. $objRaDec->getAZ($objStar->datemin()->getTimestamp()) .'</az>' . chr(13) . chr(10);
		echo '   <rise atomic="'. $objStar->rise() .'">'. $dteDisplayTime->setTimestamp( $objStar->rise() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</rise>' . chr(13) . chr(10);
		echo '   <set atomic="'. $objStar->set() .'">'. $dteDisplayTime->setTimestamp( $objStar->set() )->format( ( $blnVerbose ? 'Ymd ' : '').'H\hi T' ) .'</set>' . chr(13) . chr(10);
		echo '   <mag>'. $objStar->mag() .'</mag>' . chr(13) . chr(10);
		echo '   <magmax>'. $objStar->getMaxMagnitude() .'</magmax>' . chr(13) . chr(10);
		echo '   <magmin>'. $objStar->getMinMagnitude() .'</magmin>' . chr(13) . chr(10);
		echo '   <minimum atomic="'. $objStar->datemin()->getTimestamp() .'">'. $dteDisplayTime->setTimestamp( $objStar->datemin()->getTimestamp() )->format( 'Ymd H\hi T' ) .'</minimum>' . chr(13) . chr(10);
		echo '   <maximum atomic="'. $objStar->datemax()->getTimestamp() .'">'. $dteDisplayTime->setTimestamp( $objStar->datemax()->getTimestamp() )->format( 'Ymd H\hi T' ) .'</maximum>' . chr(13) . chr(10);
		echo '  </variable>' . chr(13) . chr(10);
}
// Comets
/*// */
$arrComets = ['0021P','0038P','0046P'];
foreach( $arrComets AS $idComet ) {
	$objComet = new Comet($idComet);
	if( $blnVerbose ) {
		echo ' <comment><![CDATA['; print_r( $objComet ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
	}
	echo '  <comet>' . chr(13) . chr(10);
	echo '   <cometname>'. $objComet->name() .'</cometname>' . chr(13) . chr(10);
	echo '   <ra>'. $objComet->ra() .'</ra>' . chr(13) . chr(10);
	echo '   <dec>'. $objComet->dec() .'</dec>' . chr(13) . chr(10);
	echo '   <mag>'. $objComet->mag() .'</mag>' . chr(13) . chr(10);
	echo '  </comet>' . chr(13) . chr(10);
}

// Meteor shower
if( !is_null($objNextShower->name()) ) {
	if( $blnVerbose ) {
		echo ' <comment><![CDATA['; print_r( $objNextShower ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
	}
	echo '  <shower>' . chr(13) . chr(10);
	echo '   <radiantname>'. $objNextShower->name() .'</radiantname>' . chr(13) . chr(10);
	echo '   <ra>'. $objNextShower->ra() .'</ra>' . chr(13) . chr(10);
	echo '   <dec>'. $objNextShower->dec() .'</dec>' . chr(13) . chr(10);
	echo '   <begin atomic="'. $objNextShower->begin()->getTimestamp() .'">'. $objNextShower->begin()->format( 'Ymd H\hi T' ) .'</begin>' . chr(13) . chr(10);
	echo '   <peak atomic="'. $objNextShower->peak()->getTimestamp() .'">'. $objNextShower->peak()->format( 'Ymd H\hi T' ) .'</peak>' . chr(13) . chr(10);
	echo '   <end atomic="'. $objNextShower->finish()->getTimestamp() .'">'. $objNextShower->finish()->format( 'Ymd H\hi T' ) .'</end>' . chr(13) . chr(10);
	echo '   <mag>'. $objNextShower->mag() .'</mag>' . chr(13) . chr(10);
	echo '   <rate>'. $objNextShower->rate() .'</rate>' . chr(13) . chr(10);
	echo '  </shower>' . chr(13) . chr(10);
}

// Eclipse
if( !is_null($objNextEclipse->type()) ) {
	if( $blnVerbose ) {
		echo ' <comment><![CDATA['; print_r( $objNextEclipse ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
	}
	echo '  <eclipse>' . chr(13) . chr(10);
	echo '   <eclipsetype>'. $objNextEclipse->type() .'</eclipsetype>' . chr(13) . chr(10);
	echo '   <begin atomic="'. $objNextEclipse->begin()->getTimestamp() .'">'. $objNextEclipse->begin()->format( 'Ymd H\hi T' ) .'</begin>' . chr(13) . chr(10);
	echo '   <peak atomic="'. $objNextEclipse->peak()->getTimestamp() .'">'. $objNextEclipse->peak()->format( 'Ymd H\hi T' ) .'</peak>' . chr(13) . chr(10);
	echo '   <end atomic="'. $objNextEclipse->finish()->getTimestamp() .'">'. $objNextEclipse->finish()->format( 'Ymd H\hi T' ) .'</end>' . chr(13) . chr(10);
/** 
	echo '   <ra>'. $objNextEclipse->ra() .'</ra>' . chr(13) . chr(10);
	echo '   <dec>'. $objNextEclipse->dec() .'</dec>' . chr(13) . chr(10);
	echo '   <mag>'. $objNextEclipse->mag() .'</mag>' . chr(13) . chr(10);
	echo '   <rate>'. $objNextEclipse->rate() .'</rate>' . chr(13) . chr(10);
*/	echo '  </eclipse>' . chr(13) . chr(10);
}
	
	echo ' </events>' . chr(13) . chr(10);

echo '</astroforecast>' . chr(13) . chr(10);
?>
