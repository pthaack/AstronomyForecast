<?php
/***
								*********************
								*  Online version   *
								*********************
 *  No calculation download
 *  Pre-load the weather images so that the live XML does not take so long
 *  Also, pre-load the aurora forcast page
 *
 *  Do garbage collection 
 *
 ***/

//Set timezone for Ottawa, source of government maps
define('TIMEZONE', 'America/Toronto');
define('TZTIMEZONE', 'timezone');	// Timezone parameter 
define('LATITUDE', 'lat');
define('LONGITUDE', 'lng');
define('ALTITUDE', 'alt');
define('NECLOUDCOVER', 'northeast');
define('NWCLOUDCOVER', 'northwest');
define('SECLOUDCOVER', 'southeast');
define('SWCLOUDCOVER', 'southwest');
define('TRNORTHAMERICA', 'transparency');
define('SENORTHAMERICA', 'seeing');
define('UVNORTHAMERICA', 'wind');
define('HRNORTHAMERICA', 'humidity');
define('TTNORTHAMERICA', 'temperature');
define('TZALASKA', 'America/Juneau');
define('DATADIR', 'data');
define('DATELENGTH', 10);
define('THISPATH', '/mnt/stor12-wc2-dfw1/588082/956997/www.philipyoung.ca/web/content/philslab/');

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>' . chr(13) . chr(10);   
echo '<?xml-stylesheet type="text/css" href="astroforecastCSS.css"?>' . chr(13) . chr(10);
echo '<astroforecast>' . chr(13) . chr(10);

$tzServer = new DateTimeZone( TIMEZONE );
$dteServer = new DateTime( 'now', $tzServer ); 
$dteServer->add( DateInterval::createFromDateString('1 hour') );  // preload the images one hour early, but do not delete the old ones.
$tzAlaska = new DateTimeZone( TZALASKA );
$dteAlaska = new DateTime( 'now', $tzAlaska );
$blnActivety = false;
$blnVerbose = isset( $_GET['verbose'] );

// Weather Maps
$strPathURL = 'http://weather.gc.ca/data/prog/regional/';
$strSuffixLocal = '.png';
$strSuffixURL = '.png?1';
$strDate = $dteServer->format('Ymd') . ($dteServer->format('H')< 12 || $dteServer->format('H')==00 ? '00' : '12' );
$maps = [ NECLOUDCOVER, NWCLOUDCOVER, SECLOUDCOVER, SWCLOUDCOVER, TRNORTHAMERICA, UVNORTHAMERICA, HRNORTHAMERICA, TTNORTHAMERICA, SENORTHAMERICA ];
// echo ' <comment>' . $strDate . '</comment>' . chr(13) . chr(10);
// echo ' <comment>' . $dteYesterday->format('Ymd') . ($dteYesterday->format('H')< 12 || $dteYesterday->format('H')==00 ? '00' : '12' ) . '</comment>' . chr(13) . chr(10);
// echo ' <comment>' . getcwd() . '/'. DATADIR . '</comment>' . chr(13) . chr(10);
// echo ' <comment>' . $_SERVER['DOCUMENT_ROOT'].'/philslab/'. DATADIR . '</comment>' . chr(13) . chr(10);
// echo ' <comment>' . THISPATH . DATADIR . '</comment>' . chr(13) . chr(10);
// echo ' <comment>' . dirname(__FILE__) . '/'. DATADIR . '</comment>' . chr(13) . chr(10);
// echo ' <comment>' . basename(__DIR__) . '/'.DATADIR . '</comment>' . chr(13) . chr(10);
$arrFiles = scandir(dirname(__FILE__) . '/' . DATADIR );
//	echo ' <comment>Files '; print_r($arrFiles); echo '</comment>' . chr(13) . chr(10);
// die('Path is not complete');

$intErrCount = 0;
for( $intHour = 3; $intHour <= 48; $intHour++ )
{
	$strHour = substr( '000' . (string) $intHour, -3);
//	echo ' <comment>Hour '. $strHour .'</comment>' . chr(13) . chr(10);
	foreach( $maps AS $mapkey => $mapval ) {
		switch( $mapval ) {
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
			$strHour = substr( '000' . (string) (floor(( $intHour +1)/3)*3), -3); 
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
		}
		$strLocalSrc = dirname(__FILE__) . '/'. DATADIR . '/' . $strDate . $strMap . $strHour . $strSuffixLocal;
		$strLocalAddress = $strDate . $strMap . $strHour . $strSuffixLocal;
		$strURLSrc = $strPathURL . $strDate . '/' . $strDate . $strMap . $strHour . $strSuffixURL;
//	echo ' <comment>Map '. $strMap .'</comment>' . chr(13) . chr(10);
if( $blnVerbose )	echo ' <comment>Online '. $strURLSrc .'</comment>' . chr(13) . chr(10);
//	echo ' <comment>Local '. $strLocalSrc .'</comment>' . chr(13) . chr(10);
		if( array_search( $strLocalAddress, $arrFiles ) === false ) {
			$imgMap = imagecreatefrompng($strURLSrc);
			if( $imgMap ) {
				imagepng( $imgMap, $strLocalSrc );
				$arrFiles[] = $strLocalAddress;
//	echo ' <comment>File '. $strLocalAddress .'</comment>' . chr(13) . chr(10);
				$blnActivety = true;
			}
			else {
				// TODO: get the file in the background
				// Create a cURL handle
				/**
				 * If cURLed file is blank, it saves anyway.  Not good. 
				$ch = curl_init($strURLSrc);
				
				// Assign POST data
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
				
				// Execute the handle
				$raw=curl_exec($ch);
				curl_close ($ch);
				$fp = fopen($strLocalSrc,'x');
				fwrite($fp, $raw);
				fclose($fp);
				**/
				$intErrCount++;
				echo ' <comment>File cURLed '. $strLocalAddress .'</comment>' . chr(13) . chr(10);
				if( $intErrCount>5 ) {
					echo ' <comment>Error count exceeded</comment>' . chr(13) . chr(10);
					break;
				}
			}
		}
	}
	if( $intErrCount>5 ) {
		echo ' <comment>Error count exceeded</comment>' . chr(13) . chr(10);
		break;
	}
}
if( $blnActivety ) {
	echo ' <comment>map created</comment>' . chr(13) . chr(10);
}

function garbage_collection_maps( $dteDateToKeep, $strMapType ) {

	// TODO: Lookup and delete line. const DATELENGTH = 10;
	$strMap = '';
	$blnActivety = false;
	
	// Recalculate the date string also used in class Weather_Map 
	$strDateToKeep = $dteDateToKeep->format('Ymd') . ((integer)$dteDateToKeep->format('H')<12?'00':'12');
	if((integer)$dteDateToKeep->format('H')<12) {
		$dteDateToAlsoKeep = clone $dteDateToKeep; 
		$dteDateToAlsoKeep->sub( DateInterval::createFromDateString('1 day') );
		$strDateToAlsoKeep = $dteDateToAlsoKeep->format('Ymd') . '12';
	}
	else {
		$strDateToAlsoKeep = $dteDateToKeep->format('Ymd') . '00';
	}
	
	// check if there is any garbage to collect
	$arrFiles = scandir( dirname(__FILE__) . '/'. DATADIR );
	if( sizeof($arrFiles) == 0 ) { return; }		
	
	if( is_numeric( $strDateToKeep ) && strlen( $strDateToKeep ) == DATELENGTH  
			&& is_numeric( $strDateToAlsoKeep ) && strlen( $strDateToAlsoKeep ) == DATELENGTH ) {
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
//  TODO: echo ' <garbagecollection>' . chr(13) . chr(10);
// TODO: echo '  <date>' . $strDateToKeep . '</date>' . chr(13) . chr(10);
// TODO: echo '  <map>' . $strMap . '</map>' . chr(13) . chr(10);
// TODO: echo '</garbagecollection>' . chr(13) . chr(10);
		foreach( $arrFiles AS $strLocalAddress ) {
			if( strpos( $strLocalAddress, $strMap ) > 1 && strpos( $strLocalAddress, $strDateToKeep ) === false && strpos( $strLocalAddress, $strDateToAlsoKeep ) === false ) {
				// A candidate is found. Double check and delete.
				if( is_numeric( substr( $strLocalAddress, 0, DATELENGTH ) ) ) {
					unlink( dirname(__FILE__) . '/'. DATADIR . '/' . $strLocalAddress );
					$blnActivety = true;
				}
			}
		}
	}
	return( $blnActivety );	
}

$blnActivety = false;
foreach( $maps AS $mapkey => $mapval ) {
	if( garbage_collection_maps( $dteServer, $mapval ) ) {$blnActivety = true;}
}
if( $blnActivety ) {
	echo ' <comment>map garbage collected</comment>' . chr(13) . chr(10);
}

/***
 *
 * Get the Aurora forecast file
 *
 ***/

// Every three hours
/* switch( floor(( (integer) $dteAlaska->format('H') +1)/3)*3 - (integer) $dteAlaska->format('H') ) {
case -1:
	$dteAlaska->sub( DateInterval::createFromDateString('1 hour') );
	break;
case 1:
	$dteAlaska->add( DateInterval::createFromDateString('1 hour') );
	break;
}  */
// Afternoons only (4 hours ahead of Eastern Time)
switch( (integer) $dteAlaska->format('H') ) {
case 0:
	$dteAlaska->sub( DateInterval::createFromDateString('1 hour') );
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('7 hours') );
	break;
case 1:
	$dteAlaska->sub( DateInterval::createFromDateString('2 hours') );
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('8 hours') );
	break;
case 2:
	$dteAlaska->add( DateInterval::createFromDateString('3 hours') );
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('9 hours') );
	break;
case 3:
	$dteAlaska->sub( DateInterval::createFromDateString('4 hours') );
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('10 hours') );
	break;
case 4:
	$dteAlaska->sub( DateInterval::createFromDateString('5 hours') );
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('11 hours') );
	break;
case 5:
	$dteAlaska->add( DateInterval::createFromDateString('6 hours') );
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('12 hours') );
	break;
case 6:
	$dteAlaska->sub( DateInterval::createFromDateString('7 hours') );
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('13 hours') );
	break;
case 7:
	$dteAlaska->sub( DateInterval::createFromDateString('8 hours') );
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('14 hours') );
	break;
case 8:
	$dteAlaska->sub( DateInterval::createFromDateString('9 hours') );
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('15 hours') );
	break;
case 9:
	$dteAlaska->sub( DateInterval::createFromDateString('10 hours') );
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('16 hours') );
	break;
case 10:
	$dteAlaska->sub( DateInterval::createFromDateString('11 hours') );
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('17 hours') );
	break;
case 11:
	$dteAlaska->add( DateInterval::createFromDateString('12 hours') );
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('18 hours') );
	break;
case 12:
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('13 hour') );
	break;
case 13:
case 14:
case 15:
case 16:
case 17:
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('1 hour') );
	break;
case 18:
	$dteAlaska->sub( DateInterval::createFromDateString('1 hour') );
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('2 hours') );
	break;
case 19:
	$dteAlaska->sub( DateInterval::createFromDateString('2 hours') );
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('3 hours') );
	break;
case 20:
	$dteAlaska->sub( DateInterval::createFromDateString('3 hours') );
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('4 hours') );
	break;
case 21:
	$dteAlaska->sub( DateInterval::createFromDateString('4 hours') );
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('5 hours') );
	break;
case 22:
	$dteAlaska->sub( DateInterval::createFromDateString('5 hours') );
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('6 hours') );
	break;
case 23:
	$dteAKtooKeep = clone $dteAlaska;
	$dteAKtooKeep->sub( DateInterval::createFromDateString('6 hours') );
	break;
}

$strDateAK = $dteAlaska->format('Ymd');
$strPathURL = 'http://www.gi.alaska.edu/AuroraForecast/NorthAmerica';
$strSuffixLocal = '.html';
// $strHour = substr( '00' . (string) (floor(( (integer) $dteAlaska->format('H') +1)/3)*3), -2); 
$strHour = substr( '00' . $dteAlaska->format('H'), -2); 
$strForecast = '_AuroraForecast';
$strURLSrc = $strPathURL . '/';
// $strURLSrc = $strPathURL . '/' . $strDateAK;
$strLocalSrc = dirname(__FILE__) . '/'. DATADIR . '/' . $strDateAK . $strHour . $strForecast . $strSuffixLocal;
$strLocalAddress = $strDateAK . $strHour . $strForecast . $strSuffixLocal;
$blnActivety = false;
if( array_search( $strLocalAddress, $arrFiles ) === false ) { 
	$strAurora = file_get_contents( $strURLSrc );
	$fpAurora = fopen( $strLocalSrc, 'w' );	// Create a new file and post the contents of the Aurora forecast file.
	fwrite( $fpAurora, $strAurora );
	fclose( $fpAurora );
	// TODO: echo '<comment>'. htmlspecialchars( $strAurora, ENT_XML1 | ENT_COMPAT, 'UTF-8') .'</comment>' . chr(13) . chr(10);
	$blnActivety = true;
}
if( $blnActivety ) {
	echo ' <comment>aurora created</comment>' . chr(13) . chr(10);
}

//$strAKtoKeep = $dteAlaska->format('Ymd') . substr( '00' . (string) (floor(( (integer) $dteAlaska->format('H') +1)/3)*3), -2);
$strAKtoKeep = $dteAlaska->format('YmdH');
//$strAKtooKeep = $dteAKtooKeep->format('Ymd') . substr( '00' . (string) (floor(( (integer) $dteAKtooKeep->format('H') +1)/3)*3), -2);
$strAKtooKeep = $dteAKtooKeep->format('YmdH');
// check if there is any garbage to collect
$arrFiles = scandir( dirname(__FILE__) . '/'. DATADIR );
$blnActivety = false;
if( sizeof($arrFiles) > 0 ) {
	if( is_numeric( $strAKtoKeep ) && strlen( $strAKtoKeep ) == DATELENGTH  
			&& is_numeric( $strAKtooKeep ) && strlen( $strAKtooKeep ) == DATELENGTH ) {
		// TODO: echo ' <garbagecollection>' . chr(13) . chr(10);
		// TODO: echo '  <date>' . $strAKtoKeep . '</date>' . chr(13) . chr(10);
		// TODO: echo '  <date>' . $strAKtooKeep . '</date>' . chr(13) . chr(10);
		// TODO: echo '  <map>' . $strForecast . '</map>' . chr(13) . chr(10);
		foreach( $arrFiles AS $strLocalAddress ) {
			if( strpos( $strLocalAddress, $strForecast ) > 1 && strpos( $strLocalAddress, $strAKtoKeep ) === false && strpos( $strLocalAddress, $strAKtooKeep ) === false ) {
				// A candidate is found. Double check and delete.
				if( is_numeric( substr( $strLocalAddress, 0, DATELENGTH ) ) ) {
					unlink( dirname(__FILE__) . '/'. DATADIR . '/' . $strLocalAddress );
					$blnActivety = true;
		// TODO: echo '  <file>' . $strLocalAddress . '</file>' . chr(13) . chr(10);
				}
			}
		}
		// TODO: echo ' </garbagecollection>' . chr(13) . chr(10);
	}
}
if( $blnActivety ) {
	echo ' <comment>aurora garbage collected</comment>' . chr(13) . chr(10);
}

// Here is an alternate as the University of Alaska site seems to be lagging and inaccurate
$strPathURL = 'http://www.spaceweather.com/index.php';
$strForecast = '_SpaceWeatherForecast';
$strLocalSrc = dirname(__FILE__) . '/'. DATADIR . '/' . $strDateAK . $strHour . $strForecast . $strSuffixLocal;
$strLocalAddress = $strDateAK . $strHour . $strForecast . $strSuffixLocal;
if( array_search( $strLocalAddress, $arrFiles ) === false ) { 
	$strAurora = file_get_contents( $strPathURL );
	$fpAurora = fopen( $strLocalSrc, 'w' );	// Create a new file and post the contents of the Aurora forecast file.
	fwrite( $fpAurora, $strAurora );
	fclose( $fpAurora );
	$blnActivety = true;
}
if( $blnActivety ) {
	echo ' <comment>space weather created</comment>' . chr(13) . chr(10);
}

// check if there is any garbage to collect
$arrFiles = scandir( dirname(__FILE__) . '/'. DATADIR );
$blnActivety = false;
if( sizeof($arrFiles) > 0 ) {
	if( is_numeric( $strAKtoKeep ) && strlen( $strAKtoKeep ) == DATELENGTH  
			&& is_numeric( $strAKtooKeep ) && strlen( $strAKtooKeep ) == DATELENGTH ) {
		foreach( $arrFiles AS $strLocalAddress ) {
			if( strpos( $strLocalAddress, $strForecast ) > 1 && strpos( $strLocalAddress, $strAKtoKeep ) === false && strpos( $strLocalAddress, $strAKtooKeep ) === false ) {
				// A candidate is found. Double check and delete.
				if( is_numeric( substr( $strLocalAddress, 0, DATELENGTH ) ) ) {
					unlink( dirname(__FILE__) . '/'. DATADIR . '/' . $strLocalAddress );
					$blnActivety = true;
				}
			}
		}
	}
}
if( $blnActivety ) {
	echo ' <comment>space weather garbage collected</comment>' . chr(13) . chr(10);
}

// Check on Google API key status
$fltLatLngAltTz = [ LATITUDE => 43.6515952, LONGITUDE => -79.5692296, ALTITUDE => 113, TZTIMEZONE => 'EST' ];
$strGoogleAPIkey='AIzaSyBHVGRox44tkYoHtFFIfzSjxuN6IJ-0MgI'; // Elevation key
$strLatLng = urlencode( (string)$fltLatLngAltTz[LATITUDE] .','. (string)$fltLatLngAltTz[LONGITUDE] );
$objRequest = file_get_contents('https://maps.googleapis.com/maps/api/elevation/json?locations=' . $strLatLng . '&key=' . $strGoogleAPIkey );
$arrJson = json_decode($objRequest, true);	// Return a JSON array
if( !isset($arrJson['results'][0]['elevation']) )
{
  echo ' <comment><![CDATA['; echo 'Problem with Google API Elevation key.' . chr(13) . chr(10); var_dump( $arrJson ); echo ' ]]>' . chr(13) . chr(10) . ' </comment>' . chr(13) . chr(10);
}  
$strGoogleAPIkey='AIzaSyA6vk3c-4SnKzfrzK2qLgH9hQ-NEkLZsIU'; // Timezone key
$strLatLng = urlencode( (string)$fltLatLngAltTz[LATITUDE] .','. (string)$fltLatLngAltTz[LONGITUDE] );
$objRequest = file_get_contents('https://maps.googleapis.com/maps/api/timezone/json?location=' . $strLatLng . '&timestamp=' . time() . '&key=' . $strGoogleAPIkey );
$arrJson = json_decode($objRequest, true);	// Return a JSON array
// TODO: DELETE LINE
if( !isset($arrJson['timeZoneName']) )
{
  echo ' <comment><![CDATA['; var_dump( $arrJson ); echo ' ]]>' . chr(13) . chr(10) . ' </comment>' . chr(13) . chr(10);
}  


echo ' <results>Done</results>' . chr(13) . chr(10);
echo '</astroforecast>' . chr(13) . chr(10);
?>
