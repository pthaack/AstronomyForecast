<?php
/***
 *  No calculation download
 *  Pre-load the weather images so that the live XML does not take so long
 *  Also, pre-load the aurora forcast page
 *
 *  Do garbage collection 
 *
 ***/

//Set timezone for Ottawa, source of government maps
define('TIMEZONE', 'America/Toronto');
define('NECLOUDCOVER', 'northeast');
define('NWCLOUDCOVER', 'northwest');
define('SECLOUDCOVER', 'southeast');
define('SWCLOUDCOVER', 'southwest');
define('TRNORTHAMERICA', 'transparency');
define('SENORTHAMERICA', 'seeing');
define('UVNORTHAMERICA', 'wind');
define('HRNORTHAMERICA', 'humidity');
define('TTNORTHAMERICA', 'temperature');
define('DATADIR', 'data');
define('DATELENGTH', 10);

header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>' . chr(13) . chr(10);   
echo '<?xml-stylesheet type="text/css" href="astroforecastCSS.css"?>' . chr(13) . chr(10);
echo '<astroforecast>' . chr(13) . chr(10);

$tzServer = new DateTimeZone( TIMEZONE );
$dteServer = new DateTime( 'now', $tzServer );

// Weather Maps
$strPathURL = 'http://weather.gc.ca/data/prog/regional/';
$strSuffixLocal = '.png';
$strSuffixURL = '.png?1';
$strDate = $dteServer->format('Ymd') . ($dteServer->format('H')< 12 || $dteServer->format('H')==00 ? '00' : '12' );
$maps = [ NECLOUDCOVER, NWCLOUDCOVER, SECLOUDCOVER, SWCLOUDCOVER, TRNORTHAMERICA, SENORTHAMERICA, UVNORTHAMERICA, HRNORTHAMERICA, TTNORTHAMERICA ];
$arrFiles = scandir( DATADIR );
//	echo ' <comment>Files '; print_r($arrFiles); echo '</comment>' . chr(13) . chr(10);

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
		$strLocalSrc = DATADIR . '/' . $strDate . $strMap . $strHour . $strSuffixLocal;
		$strLocalAddress = $strDate . $strMap . $strHour . $strSuffixLocal;
		$strURLSrc = $strPathURL . $strDa