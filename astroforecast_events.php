<?php
/***
 *  *********************
 *  *  Offline version  *
 *  *********************
 *
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
// define('J1970', 2440588);  Defined in SunCalc.php
// define('J2000', 2451545);  Defined in SunCalc.php
define('DATADIR', 'data');
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
define('STARPERIOD', 'period');
define('STARDATE', 'date');
define('STARDATEMINIMUM', 'datemin');
define('STARDATEMAXIMUM', 'datemax');
define('STARASCPERIOD', 'periodasc');
define('STARDESCPERIOD', 'perioddesc');
define('LATITUDE', 'lat');
define('LONGITUDE', 'lng');
define('ALTITUDE', 'alt');
define('DEG2RAD', M_PI / 180 );
define('RAD2DEG', 180 / M_PI );
define('HR2RAD', M_PI / 12 );
define('HR2DEG', 180 / 12 );

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
	
	public function distance( $fltRa, $fltDec ) {
		// Find the distance between this object and another object to see if it is nearby, as in a few degrees.
		// Objects near the ecliptic are practically cartesian for this purpose.
		$fltDistRa = ( $fltRa - $this->fltRightAscension ) * cos( DEG2RAD * ( $fltDec + $this->fltDeclination ) / 2 );
		$fltDistDec = $fltDec - $this->fltDeclination;
		// echo ' <comment>'. ' Ra:' . $this->fltRightAscension. ' Dec:' . $this->fltDeclination . ' Ra:' . $fltRa. ' Dec:' . $fltDec . '</comment>' . chr(13) . chr(10);
		return( sqrt( pow( $fltDistRa * HR2DEG, 2 ) + pow( $fltDistDec, 2 ) ) );
	}
}

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

	// Orbital elements by Peter Hayes http://www.aphayes.pwp.blueyonder.co.uk/ https://github.com/clicktrend/ephemeris
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
		echo ' <comment>'. ' Ra:' . $this->fltRightAscension. ' Dec:' . $this->fltDeclination . ' Mag:' . $this->fltMagnitude. ' Name:' . $this->strObjectName . '</comment>' . chr(13) . chr(10);
		echo ' <comment><![CDATA['; print_r( $this->arrObjectElements ); echo ' ]]>' . chr(13) . chr(10) . ' </comment>' . chr(13) . chr(10);
	}
}

class Moon extends Planet {
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
		switch( floor(( (integer) $dteAurora->format('H') +1)/3)*3 - (integer) $dteAurora->format('H') ) {
		case -1:
			$dteAurora->sub( DateInterval::createFromDateString('1 hour') );
			break;
		case 1:
			$dteAurora->add( DateInterval::createFromDateString('1 hour') );
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

class ISS extends Planet {
}

class Iridium extends Planet {
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
}

class AlgolStar extends VariableStar {
	protected $fltPeriod;
	protected $dteMinimum;

	public function build() {
		if( isset( $this->arrObjectElements[ STARRA ] ) ) $this->fltRightAscension = $this->arrObjectElements[ STARRA ];
		if( isset( $this->arrObjectElements[ STARDEC ] ) ) $this->fltDeclination = $this->arrObjectElements[ STARDEC ];
		if( isset( $this->arrObjectElements[ STARMAXMAGNITUDE ] ) ) $this->fltMagnitude = $this->arrObjectElements[ STARMAXMAGNITUDE ];
		if( isset( $this->arrObjectElements[ STARMAGNITUDE ] ) ) $this->fltMagnitude = $this->arrObjectElements[ STARMAGNITUDE ];
		if( isset( $this->arrObjectElements[ STARMAXMAGNITUDE ] ) ) $this->fltMaxMagnitude = $this->arrObjectElements[ STARMAXMAGNITUDE ];
		if( isset( $this->arrObjectElements[ STARMINMAGNITUDE ] ) ) $this->fltMinMagnitude = $this->arrObjectElements[ STARMINMAGNITUDE ];
		if( isset( $this->arrObjectElements[ STARPERIOD ] ) ) $this->fltPeriod = $this->arrObjectElements[ STARPERIOD ];
		if( isset( $this->arrObjectElements[ STARDATEMINIMUM ] ) ) $this->dteMinimum = $this->arrObjectElements[ STARDATEMINIMUM ];
	}
}

class MiraStar extends VariableStar {
	protected $fltPeriod;
	protected $fltDescPeriod;
	protected $fltAscPeriod;
	protected $dteMinimum;
	protected $dteMaximum;

	public function build() {
		if( isset( $this->arrObjectElements[ STARRA ] ) ) $this->fltRightAscension = $this->arrObjectElements[ STARRA ];
		if( isset( $this->arrObjectElements[ STARDEC ] ) ) $this->fltDeclination = $this->arrObjectElements[ STARDEC ];
		if( isset( $this->arrObjectElements[ STARMAGNITUDE ] ) ) $this->fltMagnitude = $this->arrObjectElements[ STARMAGNITUDE ];
		if( isset( $this->arrObjectElements[ STARMAXMAGNITUDE ] ) ) $this->fltMaxMagnitude = $this->arrObjectElements[ STARMAXMAGNITUDE ];
		if( isset( $this->arrObjectElements[ STARMINMAGNITUDE ] ) ) $this->fltMinMagnitude = $this->arrObjectElements[ STARMINMAGNITUDE ];
		if( isset( $this->arrObjectElements[ STARPERIOD ] ) ) $this->fltPeriod = $this->arrObjectElements[ STARPERIOD ];
		if( isset( $this->arrObjectElements[ STARDATEMINIMUM ] ) ) $this->dteMinimum = $this->arrObjectElements[ STARDATEMINIMUM ];
		if( isset( $this->arrObjectElements[ STARDATEMAXIMUM ] ) ) $this->dteMaximum = $this->arrObjectElements[ STARDATEMAXIMUM ];
		if( isset( $this->arrObjectElements[ STARASCPERIOD ] ) ) $this->fltAscPeriod = $this->arrObjectElements[ STARASCPERIOD ];
		if( isset( $this->arrObjectElements[ STARDESCPERIOD ] ) ) $this->fltDescPeriod = $this->arrObjectElements[ STARDESCPERIOD ];
	}
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
}

/***
 *
 *  Aurora forecast
 *
 ***/

$arrEvents = [];
$aurora = new Aurora();
echo ' <comment>'.  $aurora->nightForecast()[ AURORANAME ] .'</comment>' . chr(13) . chr(10);
echo ' <comment>'.  $aurora->hourForecast()[ AURORANAME ] .'</comment>' . chr(13) . chr(10);
if( $aurora->nightForecast()[ AURORALEVEL ] >= $intAuroraLevel || $aurora->hourForecast()[ AURORALEVEL ] >= $intAuroraLevel ) { $arrEvents[] = AURORAEVENT; } 

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
	[	STARMAGNITUDE => 2.29,	STARNAME => 'Dschubba', 	STARRA => 16.005,	STARDEC => -22.62166667	],
	[	STARMAGNITUDE => 1.09,	STARNAME => 'Antares', 	STARRA => 16.49,	STARDEC => -26.43183333	],
	[	STARMAGNITUDE => 2.43,	STARNAME => 'Sabik', 	STARRA => 17.17166667,	STARDEC => -15.72466667	],
	[	STARMAGNITUDE => 2.06,	STARNAME => 'Nunki',	STARRA => 18.921,	STARDEC => -29.29671667	]
		];
$objStars = [];
foreach( $arrEclipticStars AS $arrStar ) {
	$objStars[] = new FixedStar( $arrStar[STARNAME], [ STARRA => $arrStar[STARRA], STARDEC => $arrStar[STARDEC], STARMAGNITUDE => $arrStar[STARMAGNITUDE] ] );
}
$arrEclipsingStars = [
	[	STARNAME => 'Algol', STARMAXMAGNITUDE => 2.12,	STARMINMAGNITUDE => 3.39,	STARPERIOD => 2.86730,	STARRA => 3.133369861,	STARDEC => 40.95564722	]
		];
foreach( $arrEclipsingStars AS $arrStar ) {
	$objStars[] = new AlgolStar( $arrStar[STARNAME], [ STARRA => $arrStar[STARRA], STARDEC => $arrStar[STARDEC], STARPERIOD => $arrStar[STARPERIOD], STARMAXMAGNITUDE => $arrStar[STARMAXMAGNITUDE], STARMINMAGNITUDE => $arrStar[STARMINMAGNITUDE] ] );
}

$arrVariableStars = [
	[	STARNAME => 'Mira', STARMAGNITUDE => 3.04,	STARMAXMAGNITUDE => 2.0,	STARMINMAGNITUDE => 10.1,	STARPERIOD => 332,	STARRA => 2.316886694,	STARDEC => -2.9776375	],
	[	STARNAME => 'Chi Cyg', STARMAGNITUDE => 6.06,	STARMAXMAGNITUDE => 3.3,	STARMINMAGNITUDE => 14.2,	STARPERIOD => 408,	STARRA => 32.91405556,	STARDEC => 117.5163417	]
		];
foreach( $arrVariableStars AS $arrStar ) {
	$objStars[] = new MiraStar( $arrStar[STARNAME], [ STARRA => $arrStar[STARRA], STARDEC => $arrStar[STARDEC], STARPERIOD => $arrStar[STARPERIOD], STARMAXMAGNITUDE => $arrStar[STARMAXMAGNITUDE], STARMINMAGNITUDE => $arrStar[STARMINMAGNITUDE] ] );
}

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
$objPlanets['Moon'] = new Moon( 'Moon', [ STARRA => 1 +2/60 +28/3600,	STARDEC => 2 +8/60 +24/3600, STARMAGNITUDE => -14.5 ] );
$objStars[] = $objPlanets['Moon'];
$objPlanets['Mercury'] = new Planet( 'Mercury', [ STARRA => 10 +3/60 +18.2/3600,	STARDEC => 11 +16/60 +17/3600, STARMAGNITUDE => 0.5 ] );
$objStars[] = $objPlanets['Mercury'];
$objPlanets['Venus'] = new Planet( 'Venus', [ STARRA => 9 +13/60 +54.4/3600,	STARDEC => 16 +30/60 +23/3600, STARMAGNITUDE => -3.8 ] );
$objStars[] = $objPlanets['Venus'];
$objPlanets['Mars'] = new Planet( 'Mars', [ STARRA => 10 +16/60 +57.2/3600,	STARDEC => 11 +55/60 +26/3600, STARMAGNITUDE => 1.8 ] );
$objStars[] = $objPlanets['Mars'];
$objPlanets['Jupiter'] = new Planet( 'Jupiter', [ STARRA => 13 +27/60 +27.5/3600,	STARDEC => -8 -0/60 -36/3600, STARMAGNITUDE => -1.6 ] );
$objStars[] = $objPlanets['Jupiter'];
$objPlanets['Saturn'] = new Planet( 'Saturn', [ STARRA => 17 +21/60 +34.9/3600,	STARDEC => -22 -0/60 -23/3600, STARMAGNITUDE => 0.4 ] );
$objStars[] = $objPlanets['Saturn'];
foreach( $objStars AS $objStar ) {
	foreach( $objPlanets AS $objPlanet ) {
		$fltDistanceBetween = $objStar->distance( $objPlanet->ra(), $objPlanet->dec() );
		// echo ' <comment>'. $objStar->name() . ' ' . $objPlanet->name() . ' angDist:' . $objStar->distance( $objPlanet->ra(), $objPlanet->dec() ) .'</comment>' . chr(13) . chr(10);
		if( $fltDistanceBetween < $fltDistCheck 
				&& $objStar->name() != $objPlanet->name() ) {
			$arrEvents[] = [ $objStar->name(), $objPlanet->name(), 'dist' => $fltDistanceBetween ];
		}
	}
	// I hope that we don't need the planets anymore.
	if( isset( $objPlanets[ $objStar->name() ] ) ) { unset( $objPlanets[ $objStar->name() ] ); }
}

echo ' <comment><![CDATA['; print_r( $arrEvents ); echo ' ]]>' . chr(13) . chr(10) . '</comment>' . chr(13) . chr(10);
echo '</astroforecast>' . chr(13) . chr(10);
?>
