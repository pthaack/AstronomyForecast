<?php
/*
 SunCalc is a PHP library for calculating sun/moon position and light phases.
 https://github.com/gregseth/suncalc-php
 Based on Vladimir Agafonkin's JavaScript library.
 https://github.com/mourner/suncalc
 Sun calculations are based on http://aa.quae.nl/en/reken/zonpositie.html
 formulas.
 Moon calculations are based on http://aa.quae.nl/en/reken/hemelpositie.html
 formulas.
 Calculations for illumination parameters of the moon are based on
 http://idlastro.gsfc.nasa.gov/ftp/pro/astro/mphase.pro formulas and Chapter 48
 of "Astronomical Algorithms" 2nd edition by Jean Meeus (Willmann-Bell,
 Richmond) 1998.
 Calculations for moon rise/set times are based on
 http://www.stargazing.net/kepler/moonrise.html article.
 
 
From Paul Schlyter, Stockholm, Sweden
email:  <a href="mailto:pausch@stjarnhimlen.se">pausch@stjarnhimlen.se</a> or 
WWW:    <a href="http://stjarnhimlen.se/">http://stjarnhimlen.se/</a><BR>

The primary orbital elements are here denoted as:
	N	longitude of the ascending node
	i	inclination to the ecliptic (plane of the Earth's orbit)
	w	argument of perihelion
	a	semi-major axis, or mean distance from Sun
	e	eccentricity (0=circle, 0-1=ellipse, 1=parabola)
	M 	mean anomaly (0 at perihelion; increases uniformly with time)

Related orbital elements are:
	w1	N + w   = longitude of perihelion
	L	M + w1  = mean longitude
	q	a*(1-e) = perihelion distance
	Q	a*(1+e) = aphelion distance
	P	a ^ 1.5 = orbital period (years if a is in AU, astronomical units)
	T	Epoch_of_M - (M(deg)/360_deg) / P  = time of perihelion
	v	true anomaly (angle between position and perihelion)
	E	eccentric anomaly


Orbital elements of the Sun:
    N = 0.0
    i = 0.0
    w = 282.9404 + 4.70935E-5 * d
    a = 1.000000  (AU)
    e = 0.016709 - 1.151E-9 * d
    M = 356.0470 + 0.9856002585 * d
Orbital elements of the Moon:
    N = 125.1228 - 0.0529538083 * d
    i = 5.1454
    w = 318.0634 + 0.1643573223 * d
    a = 60.2666  (Earth radii)
    e = 0.054900
    M = 115.3654 + 13.0649929509 * d
Orbital elements of Mercury:
    N =  48.3313 + 3.24587E-5 * d
    i = 7.0047 + 5.00E-8 * d
    w =  29.1241 + 1.01444E-5 * d
    a = 0.387098  (AU)
    e = 0.205635 + 5.59E-10 * d
    M = 168.6562 + 4.0923344368 * d
Orbital elements of Venus:
    N =  76.6799 + 2.46590E-5 * d
    i = 3.3946 + 2.75E-8 * d
    w =  54.8910 + 1.38374E-5 * d
    a = 0.723330  (AU)
    e = 0.006773 - 1.302E-9 * d
    M =  48.0052 + 1.6021302244 * d
Orbital elements of Mars:
    N =  49.5574 + 2.11081E-5 * d
    i = 1.8497 - 1.78E-8 * d
    w = 286.5016 + 2.92961E-5 * d
    a = 1.523688  (AU)
    e = 0.093405 + 2.516E-9 * d
    M =  18.6021 + 0.5240207766 * d
Orbital elements of Jupiter:
    N = 100.4542 + 2.76854E-5 * d
    i = 1.3030 - 1.557E-7 * d
    w = 273.8777 + 1.64505E-5 * d
    a = 5.20256  (AU)
    e = 0.048498 + 4.469E-9 * d
    M =  19.8950 + 0.0830853001 * d
Orbital elements of Saturn:
    N = 113.6634 + 2.38980E-5 * d
    i = 2.4886 - 1.081E-7 * d
    w = 339.3939 + 2.97661E-5 * d
    a = 9.55475  (AU)
    e = 0.055546 - 9.499E-9 * d
    M = 316.9670 + 0.0334442282 * d
Orbital elements of Uranus:
    N =  74.0005 + 1.3978E-5 * d
    i = 0.7733 + 1.9E-8 * d
    w =  96.6612 + 3.0565E-5 * d
    a = 19.18171 - 1.55E-8 * d  (AU)
    e = 0.047318 + 7.45E-9 * d
    M = 142.5905 + 0.011725806 * d
Orbital elements of Neptune:
	N = 131.7806 + 3.0173E-5 * d
	i = 1.7700 - 2.55E-7 * d
	w = 272.8461 - 6.027E-6 * d
	a = 30.05826 + 3.313E-8 * d  (AU)
	e = 0.008606 + 2.15E-9 * d
	M = 260.2471 + 0.005995147 * d

First, compute the eccentric anomaly, E, from M, the mean anomaly, and e, the eccentricity. As a first approximation, do (E and M in radians):
	E = M + e * sin(M) * ( 1.0 + e * cos(M) )
If e, the eccentricity, is less than about 0.05-0.06, this approximation is sufficiently accurate. Recursion required for Mercury (e = 0.205635) and Mars (e = 0.093405), set E0=E and then use this iteration formula (E and M in radians):
	E1 = E0 - ( E0 - e * sin(E0) - M ) / ( 1 - e * cos(E0) )
For each new iteration, replace E0 with E1. Iterate until E0 and E1 are sufficiently close together.

Now compute the planet's distance and true anomaly:
	xv = r * cos(v) = a * ( cos(E) - e )
	yv = r * sin(v) = a * ( sqrt(1.0 - e*e) * sin(E) )

	v = atan2( yv, xv )
	r = sqrt( xv*xv + yv*yv )

Compute the planet's position in 3-dimensional space:
	xh = r * ( cos(N) * cos(v+w) - sin(N) * sin(v+w) * cos(i) )
	yh = r * ( sin(N) * cos(v+w) + cos(N) * sin(v+w) * cos(i) )
	zh = r * ( sin(v+w) * sin(i) )
Compute the ecliptic longitude and latitude
	lonecl = atan2( yh, xh )
	latecl = atan2( zh, sqrt(xh*xh+yh*yh) )
Now we have computed the heliocentric (Sun-centered) coordinate of the planet, and we have included the most important perturbations. We want to compute the geocentric (Earth-centerd) position. We should convert the perturbed lonecl, latecl, r to (perturbed) xh, yh, zh:
	xh = r * cos(lonecl) * cos(latecl)
	yh = r * sin(lonecl) * cos(latecl)
	zh = r               * sin(latecl)
If we must, compute the Sun's position: convert lonsun, rs (where rs is the r computed here) to xs, ys:
	xs = rs * cos(lonsun)
	ys = rs * sin(lonsun)
(Of course, any correction for precession should be added to lonecl and lonsun before converting to xh,yh,zh and xs,ys).

Now convert from heliocentric to geocentric position:
	xg = xh + xs
	yg = yh + ys
	zg = zh
We now have the planet's geocentric (Earth centered) position in rectangular, ecliptic coordinates.
Let's convert our rectangular, ecliptic coordinates to rectangular, equatorial coordinates: simply rotate the y-z-plane by ecl, the angle of the obliquity of the ecliptic:
	xe = xg
	ye = yg * cos(ecl) - zg * sin(ecl)
	ze = yg * sin(ecl) + zg * cos(ecl)
Finally, compute the planet's Right Ascension (RA) and Declination (Dec):
	RA  = atan2( ye, xe )
	Dec = atan2( ze, sqrt(xe*xe+ye*ye) )
Compute the geocentric distance:
	rg = sqrt(xg*xg+yg*yg+zg*zg) = sqrt(xe*xe+ye*ye+ze*ze)



 
*/
// shortcuts for easier to read formulas
define('PI', M_PI);
define('rad', PI / 180);
// date/time constants and conversions
define('daySec', 60 * 60 * 24);
define('J1970', 2440588);
define('J2000', 2451545);
// general calculations for position
define('e', rad * 23.4397); // obliquity of the Earth
define('J0', 0.0009);
function toJulian($date) { return $date->getTimestamp() / daySec - 0.5 + J1970; }
function fromJulian($j)  {
    if (!is_nan($j)) {
        $dt = new DateTime("@".round(($j + 0.5 - J1970) * daySec));
        $dt->setTimezone((new DateTime())->getTimezone());
        return $dt;
    }
}
function toDays($date)   { return toJulian($date) - J2000; }
function rightAscension($l, $b) { return atan2(sin($l) * cos(e) - tan($b) * sin(e), cos($l)); }
function declination($l, $b)    { return asin(sin($b) * cos(e) + cos($b) * sin(e) * sin($l)); }
function azimuth($H, $phi, $dec)  { return atan2(sin($H), cos($H) * sin($phi) - tan($dec) * cos($phi)); }
function altitude($H, $phi, $dec) { return asin(sin($phi) * sin($dec) + cos($phi) * cos($dec) * cos($H)); }
function siderealTime($d, $lw) { return rad * (280.16 + 360.9856235 * $d) - $lw; }
// calculations for sun times
function julianCycle($d, $lw) { return round($d - J0 - $lw / (2 * PI)); }
function approxTransit($Ht, $lw, $n) { return J0 + ($Ht + $lw) / (2 * PI) + $n; }
function solarTransitJ($ds, $M, $L)  { return J2000 + $ds + 0.0053 * sin($M) - 0.0069 * sin(2 * $L); }
function hourAngle($h, $phi, $d) { return acos((sin($h) - sin($phi) * sin($d)) / (cos($phi) * cos($d))); }
// returns set time for the given sun altitude
function getSetJ($h, $lw, $phi, $dec, $n, $M, $L) {
    $w = hourAngle($h, $phi, $dec);
    $a = approxTransit($w, $lw, $n);
    return solarTransitJ($a, $M, $L);
}
// general sun calculations
function solarMeanAnomaly($d) { return rad * (357.5291 + 0.98560028 * $d); }
function eclipticLongitude($M) {
    $C = rad * (1.9148 * sin($M) + 0.02 * sin(2 * $M) + 0.0003 * sin(3 * $M)); // equation of center
    $P = rad * 102.9372; // perihelion of the Earth
    return $M + $C + $P + PI;
}
function hoursLater($date, $h) {
    $dt = clone $date;
    return $dt->add( new DateInterval('PT'.round($h*3600).'S') );
}
class DecRa {
    public $dec;
    public $ra;
    function __construct($d, $r) {
        $this->dec = $d;
        $this->ra  = $r;
    }
}
class DecRaDist extends DecRa {
    public $dist;
    function __construct($d, $r, $dist) {
        parent::__construct($d, $r);
        $this->dist = $dist;
    }
}
class AzAlt {
    public $azimuth;
    public $altitude;
    function __construct($az, $alt) {
        $this->azimuth = $az;
        $this->altitude = $alt;
    }
}
class AzAltDist extends AzAlt {
    public $dist;
    function __construct($az, $alt, $dist) {
        parent::__construct($az, $alt);
        $this->dist = $dist;
    }
}
function sunCoords($d) {
    $M = solarMeanAnomaly($d);
    $L = eclipticLongitude($M);
    return new DecRa(
        declination($L, 0),
        rightAscension($L, 0)
    );
}
function moonCoords($d) { // geocentric ecliptic coordinates of the moon
    $L = rad * (218.316 + 13.176396 * $d); // ecliptic longitude
    $M = rad * (134.963 + 13.064993 * $d); // mean anomaly
    $F = rad * (93.272 + 13.229350 * $d);  // mean distance
    $l  = $L + rad * 6.289 * sin($M); // longitude
    $b  = rad * 5.128 * sin($F);     // latitude
    $dt = 385001 - 20905 * cos($M);  // distance to the moon in km
    return new DecRaDist(
        declination($l, $b),
        rightAscension($l, $b),
        $dt
    );
}
// Orbital elements of the Moon:
/***
 *  N = 125.1228 - 0.0529538083 * d
 *  i = 5.1454
 *  w = 318.0634 + 0.1643573223 * d
 *  a = 60.2666  (Earth radii)
 *  e = 0.054900
 *  M = 115.3654 + 13.0649929509 * d
 **/	
// Orbital elements of the planets
$orbitalElements = [
	'Sun' => [ 'Nc' => 0.0, 'Nf' => 0.0, 'ic' => 0.0, 'if' => 0.0, 'wc' => 282.9404, 'wf' => 4.70935E-5, 'a' => 1.000000, 'ec' => 0.016709, 'ef' => - 1.151E-9, 'Mc' => 356.0470, 'Mf' => 0.9856002585 ],
	'Mercury' => [ 'Nc' => 48.3313, 'Nf' => 3.24587E-5, 'ic' => 7.0047, 'if' => 5.00E-8, 'wc' => 29.1241, 'wf' => 1.01444E-5, 'a' => 0.387098, 'ec' => 0.205635, 'ef' => 5.59E-10, 'Mc' => 168.6562, 'Mf' => 4.0923344368 ],
	'Venus' => [ 'Nc' => 76.6799, 'Nf' => 2.46590E-5, 'ic' => 3.3946, 'if' => 2.75E-8, 'wc' => 54.8910, 'wf' => 1.38374E-5, 'a' => 0.723330, 'ec' => 0.006773, 'ef' => - 1.302E-9, 'Mc' => 48.0052, 'Mf' => 1.6021302244 ],
	'Mars' => [ 'Nc' => 49.5574, 'Nf' => 2.11081E-5, 'ic' => 1.8497, 'if' => - 1.78E-8, 'wc' => 286.5016, 'wf' => 2.92961E-5, 'a' => 1.523688, 'ec' => 0.093405, 'ef' => 2.516E-9, 'Mc' => 18.6021, 'Mf' => 0.5240207766 ],
	'Jupiter' => [ 'Nc' => 100.4542, 'Nf' => 2.76854E-5, 'ic' => 1.3030, 'if' => - 1.557E-7, 'wc' => 273.8777, 'wf' => 1.64505E-5, 'a' => 5.20256, 'ec' => 0.048498, 'ef' => 4.469E-9, 'Mc' => 19.8950, 'Mf' => 0.0830853001 ],
	'Saturn' => [ 'Nc' => 113.6634, 'Nf' => 2.38980E-5, 'ic' => 2.4886, 'if' => - 1.081E-7, 'wc' => 339.3939, 'wf' => 2.97661E-5, 'a' => 9.55475, 'ec' => 0.055546, 'ef' => - 9.499E-9, 'Mc' => 316.9670, 'Mf' => 0.0334442282 ],
	'Uranus' => [ 'Nc' => 74.0005, 'Nf' => 1.3978E-5, 'ic' => 0.7733, 'if' => 1.9E-8, 'wc' => 96.6612, 'wf' => 3.0565E-5, 'a' => 19.18171, 'af' => - 1.55E-8, 'ec' => 0.047318, 'ef' => 7.45E-9, 'Mc' => 142.5905, 'Mf' => 0.011725806 ] 
	]
function planetCoords($d,$p) { // solcentric ecliptic coordinates of the planet
	$N = $orbitalElements[$p]['Nc'] + $orbitalElements[$p]['Nf'] * $d;
	$i = $orbitalElements[$p]['ic'] + $orbitalElements[$p]['if'] * $d;
	$w = $orbitalElements[$p]['wc'] + $orbitalElements[$p]['wf'] * $d;
	$a = $orbitalElements[$p]['a'] + (isset($orbitalElements[$p]['af'])?$orbitalElements[$p]['af']:0) * $d;
	$e = $orbitalElements[$p]['ec'] + $orbitalElements[$p]['ef'] * $d;
	$M = $orbitalElements[$p]['Mc'] + $orbitalElements[$p]['Mf'] * $d;
// First, compute the eccentric anomaly, E, from M, the mean anomaly, and e, the eccentricity. As a first approximation, do (E and M in radians):
	$E = $M + $e * sin($M) * ( 1.0 + $e * cos($M) );
// If e, the eccentricity, is less than about 0.05-0.06, this approximation is sufficiently accurate. Recursion required for Mercury (e = 0.205635) and Mars (e = 0.093405), set E0=E and then use this iteration formula (E and M in radians):
//	E1 = E0 - ( E0 - e * sin(E0) - M ) / ( 1 - e * cos(E0) )
// For each new iteration, replace E0 with E1. Iterate until E0 and E1 are sufficiently close together.
	do while ( abs ( ( $E - $e * sin( $E ) - $M ) / ( 1 - $e * cos( $E ) ) ) > 0.00001 ) {
		$E = $E - ( $E - $e * sin( $E ) - $M ) / ( 1 - $e * cos( $E ) );
	}

	
    $L = rad * (218.316 + 13.176396 * $d); // ecliptic longitude
    $M = rad * (134.963 + 13.064993 * $d); // mean anomaly
    $F = rad * (93.272 + 13.229350 * $d);  // mean distance
    $l  = $L + rad * 6.289 * sin($M); // longitude
    $b  = rad * 5.128 * sin($F);     // latitude
    $dt = 385001 - 20905 * cos($M);  // distance to the planet in au
    return new DecRaDist(
        declination($l, $b),
        rightAscension($l, $b),
        $dt
    );
}
class SunCalc {
    var $date;
    var $lat;
    var $lng;
    // sun times configuration (angle, morning name, evening name)
    private $times = [
        [-0.833, 'sunrise',       'sunset'      ],
        [  -0.3, 'sunriseEnd',    'sunsetStart' ],
        [    -6, 'dawn',          'dusk'        ],
        [   -12, 'nauticalDawn',  'nauticalDusk'],
        [   -18, 'nightEnd',      'night'       ],
        [     6, 'goldenHourEnd', 'goldenHour'  ]
    ];
    // adds a custom time to the times config
    private function addTime($angle, $riseName, $setName) {
        $this->times[] = [$angle, $riseName, $setName];
    }
    function __construct($date, $lat, $lng) {
        $this->date = $date;
        $this->lat  = $lat;
        $this->lng  = $lng;
    }
    // calculates sun position for a given date and latitude/longitude
    function getSunPosition() {
        $lw  = rad * -$this->lng;
        $phi = rad * $this->lat;
        $d   = toDays($this->date);
        $c   = sunCoords($d);
        $H   = siderealTime($d, $lw) - $c->ra;
        return new AzAlt(
            azimuth($H, $phi, $c->dec),
            altitude($H, $phi, $c->dec)
        );
    }
    // calculates sun times for a given date and latitude/longitude
    function getSunTimes() {
        $lw = rad * -$this->lng;
        $phi = rad * $this->lat;
        $d = toDays($this->date);
        $n = julianCycle($d, $lw);
        $ds = approxTransit(0, $lw, $n);
        $M = solarMeanAnomaly($ds);
        $L = eclipticLongitude($M);
        $dec = declination($L, 0);
        $Jnoon = solarTransitJ($ds, $M, $L);
        $result = [
            'solarNoon'=> fromJulian($Jnoon),
            'nadir'    => fromJulian($Jnoon - 0.5)
        ];
        for ($i = 0, $len = count($this->times); $i < $len; $i += 1) {
            $time = $this->times[$i];
            $Jset = getSetJ($time[0] * rad, $lw, $phi, $dec, $n, $M, $L);
            $Jrise = $Jnoon - ($Jset - $Jnoon);
            $result[$time[1]] = fromJulian($Jrise);
            $result[$time[2]] = fromJulian($Jset);
        }
        return $result;
    }
    function getMoonPosition($date) {
        $lw  = rad * -$this->lng;
        $phi = rad * $this->lat;
        $d   = toDays($date);
        $c = moonCoords($d);
        $H = siderealTime($d, $lw) - $c->ra;
        $h = altitude($H, $phi, $c->dec);
        // altitude correction for refraction
        $h = $h + rad * 0.017 / tan($h + rad * 10.26 / ($h + rad * 5.10));
        return new AzAltDist(
            azimuth($H, $phi, $c->dec),
            $h,
            $c->dist
        );
    }
    function getMoonIllumination() {
        $d = toDays($this->date);
        $s = sunCoords($d);
        $m = moonCoords($d);
        $sdist = 149598000; // distance from Earth to Sun in km
        $phi = acos(sin($s->dec) * sin($m->dec) + cos($s->dec) * cos($m->dec) * cos($s->ra - $m->ra));
        $inc = atan2($sdist * sin($phi), $m->dist - $sdist * cos($phi));
        $angle = atan2(cos($s->dec) * sin($s->ra - $m->ra), sin($s->dec) * cos($m->dec) - cos($s->dec) * sin($m->dec) * cos($s->ra - $m->ra));
        return [
            'fraction' => (1 + cos($inc)) / 2,
            'phase'    => 0.5 + 0.5 * $inc * ($angle < 0 ? -1 : 1) / PI,
            'angle'    => $angle
        ];
    }
    function getMoonTimes($inUTC=false) {
        $t = clone $this->date;
        if ($inUTC) $t->setTimezone(new DateTimeZone('UTC'));
        $t->setTime(0, 0, 0);
        $hc = 0.133 * rad;
        $h0 = $this->getMoonPosition($t, $this->lat, $this->lng)->altitude - $hc;
        $rise = 0;
        $set = 0;
        // go in 2-hour chunks, each time seeing if a 3-point quadratic curve crosses zero (which means rise or set)
        for ($i = 1; $i <= 24; $i += 2) {
            $h1 = $this->getMoonPosition(hoursLater($t, $i), $this->lat, $this->lng)->altitude - $hc;
            $h2 = $this->getMoonPosition(hoursLater($t, $i + 1), $this->lat, $this->lng)->altitude - $hc;
            $a = ($h0 + $h2) / 2 - $h1;
            $b = ($h2 - $h0) / 2;
            $xe = -$b / (2 * $a);
            $ye = ($a * $xe + $b) * $xe + $h1;
            $d = $b * $b - 4 * $a * $h1;
            $roots = 0;
            if ($d >= 0) {
                $dx = sqrt($d) / (abs($a) * 2);
                $x1 = $xe - $dx;
                $x2 = $xe + $dx;
                if (abs($x1) <= 1) $roots++;
                if (abs($x2) <= 1) $roots++;
                if ($x1 < -1) $x1 = $x2;
            }
            if ($roots === 1) {
                if ($h0 < 0) $rise = $i + $x1;
                else $set = $i + $x1;
            } else if ($roots === 2) {
                $rise = $i + ($ye < 0 ? $x2 : $x1);
                $set = $i + ($ye < 0 ? $x1 : $x2);
            }
            if ($rise != 0 && $set != 0) break;
            $h0 = $h2;
        }
        $result = [];
        if ($rise != 0) $result['moonrise'] = hoursLater($t, $rise);
        if ($set != 0) $result['moonset'] = hoursLater($t, $set);
        if ($rise==0 && $set==0) $result[$ye > 0 ? 'alwaysUp' : 'alwaysDown'] = true;
        return $result;
    }
}
// tests
/*
$test = new SunCalc(new DateTime(), 48.85, 2.35);
print_r($test->getSunTimes());
print_r($test->getMoonIllumination());
print_r($test->getMoonTimes());
print_r(getMoonPosition(new DateTime(), 48.85, 2.35));
*/
?>