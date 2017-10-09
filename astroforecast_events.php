<?php
/***
 *  Events lookup page
 *  Planetary alignment
 *  Aurora forecast
 *  Satelite lookup
 *  Variable stars
 *  Meteor shower
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
			// TODO: DELETE LINE  var_dump( $arrJson );
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
	$fltLatLngAlt[ALTITUDE] = ( isset($arrJson['results'][0]['elevation']) ? $arrJson['results'][0]['elevation'] :113 );
}
$strGoogleAPIkey='AIzaSyA6vk3c-4SnKzfrzK2qLgH9hQ-NEkLZsIU'; // Timezone key
// TODO: https://maps.googleapis.com/maps/api/timezone/json?location=39.7391536,-104.9847034&timestamp=&key=
$strLatLng = urlencode( (string)$fltLatLngAlt[LATITUDE] .','. (string)$fltLatLngAlt[LONGITUDE] );
$objRequest = file_get_contents('https://maps.googleapis.com/maps/api/timezone/json?location=' . $strLatLng . '&timestamp=' . time() . '&key=' . $strGoogleAPIkey );
$arrJson = json_decode($objRequest, true);	// Return a JSON array
// TODO: DELETE LINE  var_dump( $arrJson );
$tzDisplay = new DateTimeZone( isset($arrJson['timeZoneId']) ? $arrJson['timeZoneId'] : TZUTC ); 
$dteDisplayTime = new DateTime();
$dteDisplayTime->setTimezone( $tzDisplay );
$blnVerbose = isset( $_GET['verbose'] );

echo ' <comment>'. 'Lat:' . $fltLatLngAlt[LATITUDE] . ' Lng:' . $fltLatLngAlt[LONGITUDE] . ' Alt:' . $fltLatLngAlt[ALTITUDE] .'</comment>' . chr(13) . chr(10);

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
	static private $_orbitalElements = array (
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
	
	private function helios($p,$jd) {
		// heliocentric xyz for planet p and julian date jd
		$T=($jd-J2000)/36525;
		$T2=$T*$T;
		$T3=$T2*$T;
		// longitude of ascending node (in degrees)
		$N=$this->rev($p->N[0]+$p->N[1]*$T+$p->N[2]*$T2+$p->N[3]*$T3);
		// inclination (in degrees)
		$i=$p->i[0]+$p->i[1]*$T+$p->i[2]*$T2+$p->i[3]*$T3;
		// Mean longitude (in degrees)
		$L=$this->rev($p->L[0]+$p->L[1]*$T+$p->L[2]*$T2+$p->L[3]*$T3);
		// semimajor axis (in A.U.)
		$a=$p->a[0]+$p->a[1]*$T+$p->a[2]*$T2+$p->a[3]*$T3;
		// eccentricity
		$e=$p->e[0]+$p->e[1]*$T+$p->e[2]*$T2+$p->e[3]*$T3;
		// longitude of perihelion (in degrees)
		$P=$this->rev($p->P[0]+$p->P[1]*$T+$p->P[2]*$T2+$p->P[3]*$T3);
		$M=$this->rev($L-$P);
		$w=$this->rev($L-$N-$M);
		// if( ( $p->name=='Jupiter' || $p->name=='Earth' ) && $jd==2451542 ){alert($p->name +' a:'+ a +' e:'+ e +' P:'+ P +' M:'+ M +' w:'+ w);}
		// Eccentric anomaly (in degrees)
		$E0=$M+(RAD2DEG)*$e*sin($M*DEG2RAD)*(1+$e*cos($M*DEG2RAD));
		$E=$E0-($E0-(RAD2DEG)*$e*sin($E0*DEG2RAD)-$M)/(1-$e*cos($E0*DEG2RAD));
		while (abs($E0-$E) > 0.0005) {
			$E0=$E;
			$E=$E0-($E0-(RAD2DEG)*$e*sin($E0*DEG2RAD)-$M)/(1-$e*cos($E0*DEG2RAD));
		};
		$x=$a*(cos($E)-$e);
		$y=$a*sqrt(1-$e*$e)*sin($E*DEG2RAD);
		$r=sqrt($x*$x+$y*$y);
		$v=$this->rev(atan2($y,$x)*RAD2DEG);
		// if( ( $p->name=='Jupiter' || $p->name=='Earth' ) && $jd==2451542 ){alert($p->name +' E:'+ E +' x:'+ x +' y:'+ y +' r:'+ r +' v:'+ v);}
		// Heliocentric Ecliptic Rectangular Coordinates
		$xeclip=$r*(cos($N*DEG2RAD)*cos(($v+$w)*DEG2RAD)-sin($N*DEG2RAD)*sin(($v+$w)*DEG2RAD)*cos($i*DEG2RAD));
		$yeclip=$r*(sin($N*DEG2RAD)*cos(($v+$w)*DEG2RAD)+cos($N*DEG2RAD)*sin(($v+$w)*DEG2RAD)*cos($i*DEG2RAD));
		$zeclip=$r*sin(($v+$w)*DEG2RAD)*sin($i*DEG2RAD);
		// if( ( $p->name=='Jupiter' || $p->name=='Earth' ) && $jd==2451542 ){alert($p->name +' xeclip:'+ xeclip +' yeclip:'+ yeclip +' zeclip:'+ zeclip);}
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
		$jdobs = $dteLocalTime->getTimestamp() - J2000;
		if( isset( $this->_orbitalElements[$this->strObjectName] ) ) {
	//	$lst = local_sidereal(dateofinterestY,(dateofinterestM+1),(dateofinterestD),-utcHours+12,longitude); //Noon
	//	var d=dayno(year,month,day,hours);
	//	var lst=(98.9818+0.985647352*d+hours*15+lon);
	//	return rev(lst)/15;
			$earth_xyz=$this->helios( $this->_orbitalElements[OESUN], $jdobs );
			$planet_xyz=$this->helios($this->_orbitalElements[$this->strObjectName], $jdobs );
			// if( $this->_orbitalElements[$this->strObjectName].name=='Jupiter' && jdobs==2451542 ){alert( planets[pnum].name+' x:'+ planet_xyz[0] +' y:'+ planet_xyz[1] +' z:'+ planet_xyz[2] +' jdobs:'+ jdobs);}
			$radec=$this->radecr( $planet_xyz, $earth_xyz, $jdobs );
			$this->fltRightAscension = $radec[ STARRA ];
			$this->fltDeclination = $radec[ STARDEC ];
			$this->fltMagnitude = $this->_orbitalElements[$this->strObjectName][ STARMAGNITUDE ];
		}
		else {
			if( isset( $this->arrObjectElements[ STARRA ] ) ) $this->fltRightAscension = $this->arrObjectElements[ STARRA ];
			if( isset( $this->arrObjectElements[ STARDEC ] ) ) $this->fltDeclination = $this->arrObjectElements[ STARDEC ];
			if( isset( $this->arrObjectElements[ STARMAGNITUDE ] ) ) $this->fltMagnitude = $this->arrObjectElements[ STARMAGNITUDE ];
		}
		//  echo ' <comment>'. ' Ra:' . $this->fltRightAscension. ' Dec:' . $this->fltDeclination . ' Mag:' . $this->fltMagnitude. ' Name:' . $this->strObjectName . '</comment>' . chr(13) . chr(10);
		// echo ' <comment><![CDATA['; print_r( $this->arrObjectElements ); echo ' ]]>' . chr(13) . chr(10) . ' </comment>' . chr(13) . chr(10);
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
		// echo '<comment>'. htmlspecialchars( $strAurora, ENT_XML1 | ENT_COMPAT, 'UTF-8') .'</comment>' . chr(13) . chr(10);
		$this->intNightForecast = $this->parse_results_page( $strAurora, '<div class="levels">', '</div>', '<span class="level-', 'l">' );
		$this->strNightForecast = $this->parse_results_page( $strAurora, '<div class="levels">', '</div>', '<span class="level-'. $this->intNightForecast .'l">', ':</span>' );
		$this->intHourForecast = $this->parse_results_page( $strAurora, 'Short-Term (1-hour) Forecast', '</div>', '<span class="level-', 'l">' );
		$this->strHourForecast = $this->parse_results_page( $strAurora, 'Short-Term (1-hour) Forecast', '</div>', '<span class="level-'. $this->intHourForecast .'l">', ':</span>' );
		
	}
	
	private function parse_results_page( $strPageCapture, $strPageStart, $strPageEnd, $strParseStart, $strParseEnd ) {
		$intParseL = strpos( $strPageCapture, $strPageStart ) + strlen( $strPageStart );
		$intParseR = strpos( $strPageCapture, $strPageEnd, $intParseL );
		$strNibble = ( $intParseR > $intParseL ? substr( $strPageCapture, $intParseL , $intParseR - $intParseL ) : substr( $strPageCapture, $intParseL ) );
		$intParseL = strpos( $strNibble, $strParseStart ) + strlen( $strParseStart );
		$intParseR = strpos( $strNibble, $strParseEnd, $intParseL );
		$strParse = ( $intParseR > $intParseL ? substr( $strNibble, $intParseL , $intParseR - $intParseL ) : substr( $strNibble, $intParseL ) );
		
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
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data '°</td>'
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
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data '°</td>'
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
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data '°</td>' 
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
		$strURLSrc = 'http://heavens-above.com/AllSats.aspx?lat='. (string)$fltLat .'&lng='. (string)$fltLng .'&loc=Astronomy+Forecast&alt='. (string)$fltAlt .'&tz='. $strTimeZone;
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
			// TODO: DELETE LINE  var_dump( $arrJson );
			$fltLatLngAltTz[ALTITUDE] = ( isset($arrJson['results'][0]['elevation']) ? $arrJson['results'][0]['elevation'] :113 );
		}
		if( !isset( $fltLatLngAltTz[TZTIMEZONE] ) ) {
			$strGoogleAPIkey='AIzaSyA6vk3c-4SnKzfrzK2qLgH9hQ-NEkLZsIU'; // Timezone key
			$strLatLng = urlencode( (string)$fltLatLngAltTz[LATITUDE] .','. (string)$fltLatLngAltTz[LONGITUDE] );
			$objRequest = file_get_contents('https://maps.googleapis.com/maps/api/timezone/json?location=' . $strLatLng . '&timestamp=' . time() . '&key=' . $strGoogleAPIkey );
			$arrJson = json_decode($objRequest, true);	// Return a JSON array
			// TODO: DELETE LINE  var_dump( $arrJson );
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
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data '°</td>'
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
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data '°</td>'
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
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the data '°</td>' 
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
		$strURLSrc = 'http://heavens-above.com/PassSummary.aspx?satid=25544&lat='. (string)$fltLat .'&lng='. (string)$fltLng .'&loc=Astronomy+Forecast&alt='. (string)$fltAlt .'&tz='. $strTimeZone;
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
		$strEOD = htmlspecialchars( '</td>' );	// Find the end of the altitude '°</td>'
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		$intParseR = strpos( $strLine, $strEOD, $intParseL ) -2;
		$arrParse[SATRISE][SATALT] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		$arrParse[SATPEAK][SATALT] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		$arrParse[SATSET][SATALT] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $arrParse[SATPEAK][SATALT] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		
		//echo ' <comment><![CDATA['; print_r( substr( $strLine, $intParseR ) ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$strBOD = htmlspecialchars( '<td align="center">' );	// Find the start of the data
		$strEOD = htmlspecialchars( ' (' );	// Find the end of the azimuth '° (S)</td>' OR '° (SW)</td>' OR '° (NNE)</td>'
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
		$strEOD = htmlspecialchars( ' <img ' );	// Find the end of the Sun altitude '° <img '
		$intParseL = strpos( $strLine, $strBOD, $intParseR ) + strlen( $strBOD );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
		$intParseR = strpos( $strLine, $strEOD, $intParseL ) -2;
		$arrParse[FLRSUNALT] = substr( $strLine, $intParseL, $intParseR - $intParseL );
		// echo ' <comment><![CDATA['. chr(13) . chr(10) . $intParseL . chr(13) . chr(10) . $intParseR . chr(13) . chr(10) . SATAZ .':'. $arrParse[FLRSUNALT] . chr(13) . chr(10) . ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
				
		return( $arrParse ); }
	
	function build($fltLat,$fltLng,$fltAlt,$strTimeZone) {
		
		// get the satellite information (Iridium_Page)
		$strURLSrc = 'http://heavens-above.com/IridiumFlares.aspx?lat='. $fltLat .'&lng='. $fltLng .'&loc=Astronomy+Forecast&alt='. $fltAlt .'&tz='. $strTimeZone;
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
			// TODO: DELETE LINE  var_dump( $arrJson );
			$fltLatLngAltTz[ALTITUDE] = ( isset($arrJson['results'][0]['elevation']) ? $arrJson['results'][0]['elevation'] :113 );
		}
		if( !isset( $fltLatLngAltTz[TZTIMEZONE] ) ) {
			$strGoogleAPIkey='AIzaSyA6vk3c-4SnKzfrzK2qLgH9hQ-NEkLZsIU'; // Timezone key
			$strLatLng = urlencode( (string)$fltLatLngAltTz[LATITUDE] .','. (string)$fltLatLngAltTz[LONGITUDE] );
			$objRequest = file_get_contents('https://maps.googleapis.com/maps/api/timezone/json?location=' . $strLatLng . '&timestamp=' . time() . '&key=' . $strGoogleAPIkey );
			$arrJson = json_decode($objRequest, true);	// Return a JSON array
			// TODO: DELETE LINE  var_dump( $arrJson );
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
 *  Look up the date for the minimax for Khi Cyg and Omi Cet, Mira type variable stars
 *
 *  Classes and methods
 *    Algol - RA, Dec, Alt(), Az(), Rise(), Set(), Minimum(), Begin(), End()
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
		 'Algol'  => [ STARNAME => 'Algol', STARMAXMAGNITUDE => 2.12,	STARMINMAGNITUDE => 3.39,	STARRA => 3.133369861,	STARDEC => 40.95564722, 	STAREPOCH => 2445641.554, 	STARPERIOD => 2.867324 ]
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
			//	echo ' <comment><![CDATA['; print_r( $this->dteMinimum ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
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
			if( isset($this->arrObjectElements[ STAREPOCH ]) && isset($this->arrObjectElements[ STARPERIOD ]) ) {
				$intCycles = floor( ( $this->dteMinimum->getTimestamp() - $this->tsEpoch ) / $this->fltPeriod ) + 1;
				$this->dteMinimum->setTimestamp( (integer) ($this->tsEpoch + $this->fltPeriod * $intCycles ) );
			// That was relative to Algol itself - negligible adjustment for light travel time to the Earth in it's orbital position
				// echo ' <comment><![CDATA['; print_r( $intCycles ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
			} else {
				// echo ' <comment><![CDATA['; print_r( [$this->name(), $this->_varibilityElements, $this->dteMinimum ] ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
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
 *  Look up the date for meteor showers
 *
 *  Classes and methods
 *    MeteorShower - Name, Date, Time, Constellation 
 *
 ***/

class MeteorShower {
	private $_showerElements = array (
		 'Perseids' => [ MTRNAME => 'Perseids', MTRBEGIN => 'Jul 17', MTRPEAK => 'Aug 11', MTREND => 'Aug 24', MTRTIME => '00h00', MTRRA => 3.4, 	MTRDEC => 45.4, 	MTRMAG => 2.0, MTRRATE => 20 ],
		 'Draconids' => 	[ MTRNAME => 'Draconids', MTRBEGIN => 'Oct 7', MTRPEAK => 'Oct 7', MTREND => 'Oct 8', MTRTIME => '22h00', MTRRA => 6.4, 	MTRDEC => 14.9, 	MTRMAG => 2.5, MTRRATE => 20 ],
		 'Leonids'  => [ MTRNAME => 'Leonids', MTRBEGIN => 'Nov 6', MTRPEAK => 'Nov 18', MTREND => 'Nov 30', MTRTIME => '02h00', MTRRA => 10.5, 	MTRDEC => 16.8, 	MTRMAG => 2.5, MTRRATE => 15 ],
		 'Orionids' => 	[ MTRNAME => 'Orionids', MTRBEGIN => 'Oct 2', MTRPEAK => 'Oct 22', MTREND => 'Nov 7', MTRTIME => '02h00', MTRRA => 6.4, 	MTRDEC => 14.9, 	MTRMAG => 2.5, MTRRATE => 20 ],
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
		// Return the next meteor shower based on the peak date
		$tzLocal = $dteCurrent->getTimezone();
		$this->dtePeak = clone $dteCurrent;
		$this->dtePeak->add( DateInterval::createFromDateString('1 year') );
		foreach( $this->_showerElements AS $arrShower ) {
			$dteShower = DateTime::createFromFormat( MTRDATEFORMAT, $arrShower[MTRPEAK], $tzLocal );
			if( $dteCurrent->getTimestamp() < $dteShower->getTimestamp() && $dteShower->getTimestamp() < $this->dtePeak->getTimestamp() ) {
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
	public function end() { return( $this->dteEnd ); }
	public function ra() { return( $this->fltRA ); }
	public function dec() { return( $this->fltDec ); }
	public function mag() { return( $this->fltMagnitude ); }
	public function rate() { return( $this->fltRate ); }
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
echo ' <comment>'.  $aurora->nightForecast()[ AURORANAME ] .'</comment>' . chr(13) . chr(10);
echo ' <comment>'.  $aurora->hourForecast()[ AURORANAME ] .'</comment>' . chr(13) . chr(10);
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
	[	STARNAME => 'Beta Persei', STARMAXMAGNITUDE => 2.12,	STARMINMAGNITUDE => 3.39, STARDATE => time()+86400*3,	STAREPOCH => 2445641.554, 	STARPERIOD => 2.86730,	STARRA => 3.133369861,	STARDEC => 40.95564722	],
		
		];
foreach( $arrEclipsingStars AS $arrStar ) {
	$objStars[ $arrStar[STARNAME] ] = new AlgolStar( $arrStar[STARNAME], [ STARRA => $arrStar[STARRA], STARDEC => $arrStar[STARDEC], STARPERIOD => $arrStar[STARPERIOD], STARMAXMAGNITUDE => $arrStar[STARMAXMAGNITUDE], STARMINMAGNITUDE => $arrStar[STARMINMAGNITUDE] ] );
}

$arrMiraStars = [
	[	STARNAME => 'Mira', STARMAGNITUDE => 3.04,	STARMAXMAGNITUDE => 2.0,	STARMINMAGNITUDE => 10.1,	STAREPOCH => 2457809.334,	STARPERIOD => 332,	STAREPOCHMIN => 2457696.64140,	STAREPOCHMAX => 2457809.334,	STARRA => 2.316886694,	STARDEC => -2.9776375	],
	[	STARNAME => 'Chi Cyg', STARMAGNITUDE => 6.06,	STARMAXMAGNITUDE => 3.3,	STARMINMAGNITUDE => 14.2,	STAREPOCH => 2457646.29166,	STARPERIOD => 408,	STAREPOCHMIN => 2457891.50834,	STAREPOCHMAX => 2457646.29166,	STARRA => 32.91405556,	STARDEC => 117.5163417	]
		];
foreach( $arrMiraStars AS $arrStar ) {
	// $objStars[ $arrStar[STARNAME] ] = new MiraStar( $arrStar[STARNAME], [ STARRA => $arrStar[STARRA], STARDEC => $arrStar[STARDEC], STARPERIOD => $arrStar[STARPERIOD], STARMAXMAGNITUDE => $arrStar[STARMAXMAGNITUDE], STARMINMAGNITUDE => $arrStar[STARMINMAGNITUDE] ] );
	$objStars[ $arrStar[STARNAME] ] = new MiraStar( $arrStar[STARNAME], $arrStar );
}

$objNextShower = new MeteorShower( $dteDisplayTime );
echo ' <comment><![CDATA['; print_r( $objNextShower ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);


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
$objPlanets['Moon'] = new Moon( 'Moon', [ STARRA => 1 +2/60 +28/3600,	STARDEC => 2 +8/60 +24/3600, STARMAGNITUDE => -14.5, LATITUDE => $fltLatLngAlt[LATITUDE], LONGITUDE => $fltLatLngAlt[LONGITUDE]  ] );
$objStars[ 'Moon' ] = $objPlanets['Moon'];
$objPlanets['Mercury'] = new Planet( 'Mercury', [ STARRA => 10 +3/60 +18.2/3600,	STARDEC => 11 +16/60 +17/3600, STARMAGNITUDE => 0.5 ] );
$objStars[ 'Mercury' ] = $objPlanets['Mercury'];
$objPlanets['Venus'] = new Planet( 'Venus', [ STARRA => 9 +13/60 +54.4/3600,	STARDEC => 16 +30/60 +23/3600, STARMAGNITUDE => -3.8 ] );
$objStars[ 'Venus' ] = $objPlanets['Venus'];
$objPlanets['Mars'] = new Planet( 'Mars', [ STARRA => 10 +16/60 +57.2/3600,	STARDEC => 11 +55/60 +26/3600, STARMAGNITUDE => 1.8 ] );
$objStars[ 'Mars' ] = $objPlanets['Mars'];
$objPlanets['Jupiter'] = new Planet( 'Jupiter', [ STARRA => 13 +27/60 +27.5/3600,	STARDEC => -8 -0/60 -36/3600, STARMAGNITUDE => -1.6 ] );
$objStars[ 'Jupiter' ] = $objPlanets['Jupiter'];
$objPlanets['Saturn'] = new Planet( 'Saturn', [ STARRA => 17 +21/60 +34.9/3600,	STARDEC => -22 -0/60 -23/3600, STARMAGNITUDE => 0.4 ] );
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
		echo '   <auroranight>'. $aurora->nightForecast()[ AURORANAME ] .'</auroranight>' . chr(13) . chr(10);
		echo '   <aurorahour>'. $aurora->hourForecast()[ AURORANAME ] .'</aurorahour>' . chr(13) . chr(10);
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

// Meteor shower
echo '  <shower>' . chr(13) . chr(10);
echo '   <radiantname>'. $objNextShower->name() .'</radiantname>' . chr(13) . chr(10);
echo '   <ra>'. $objNextShower->ra() .'</ra>' . chr(13) . chr(10);
echo '   <dec>'. $objNextShower->dec() .'</dec>' . chr(13) . chr(10);
echo '   <begin atomic="'. $objNextShower->begin()->getTimestamp() .'">'. $objNextShower->begin()->format( 'Ymd H\hi T' ) .'</begin>' . chr(13) . chr(10);
echo '   <peak atomic="'. $objNextShower->peak()->getTimestamp() .'">'. $objNextShower->peak()->format( 'Ymd H\hi T' ) .'</peak>' . chr(13) . chr(10);
echo '   <end atomic="'. $objNextShower->end()->getTimestamp() .'">'. $objNextShower->end()->format( 'Ymd H\hi T' ) .'</end>' . chr(13) . chr(10);
echo '   <mag>'. $objNextShower->mag() .'</mag>' . chr(13) . chr(10);
echo '   <rate>'. $objNextShower->rate() .'</rate>' . chr(13) . chr(10);
echo '  </shower>' . chr(13) . chr(10);

echo ' </events>' . chr(13) . chr(10);

echo '</astroforecast>' . chr(13) . chr(10);
?>
