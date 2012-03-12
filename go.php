set terminal png size 2500,1300
#set key off
#set xdata time
#set timefmt "%Y-%m-%d"
set xtics nomirror rotate by -90
set style data histograms
set style histogram rowstacked
set style fill solid border 0
set key invert reverse Left outside
set boxwidth 1
set noytics
<?

array_shift($argv);
foreach ($argv as $f) {
	$fs[]=$f;
	foreach (file($f) as $l) {
		$x = preg_split("/,/", $l);
		$bs[$x[0]] = true;
		@$o[$f][$x[0]] = (float)$x[1];
		@$t[$x[0]] += (float)$x[1];
	}
}
unset($bs["\n"]);
foreach ($t as $br => $v)
	if ($v < 10)
		unset($bs[$br]);

$bs = array_keys($bs);
natsort($bs);

#$d = 'when ' . implode(' ', $bs);
foreach ($fs as $f) {
	@$d.=date("Y-m-d", strtotime(preg_replace("/-/","W",preg_replace("/.csv$/","",$f))));
	$r=0;
	foreach ($bs as $b) {
		$d.=" " . (/*$r +=*/ (isset($o[$f][$b]) ? $o[$f][$b] : 0));
	}
	$d.="\n";
}

file_put_contents("dat", $d);

$s = 'plot ';
$i = 1;

function brow($b) {
	preg_match('/"(.*) (.*?)"/', $b, $regs);
	return $regs;
}

foreach ($bs as $b) {
	$regs = brow($b);
	if (is_numeric($regs[2]))
		$bv[$regs[1]][] = $regs[2];
}

foreach ($bv as $b => $vers) {
	$bmin[$b] = min($vers);
	$bmax[$b] = max($vers);
}

$hues = array('Chrome' => 80, 'IE' => 160, 'Opera' => 0, 'Firefox' => 20);

foreach ($bs as $b) {
	++$i;
	$s .= "\"dat\" using ($$i):xtic(1) title $b";

	$brow = brow($b);
	if (isset($hues[$brow[1]])) {
		$m = $bmin[$brow[1]];
		$brange = $bmax[$brow[1]] - $m;
		$c = hsv2rgb($hues[$brow[1]], 1, ($brow[2]-$m)/$brange );
		$s .= 'lc rgb "#' . sprintf("%02x%02x%02x", $c[0], $c[1], $c[2]) . '"';
	}

	$s .= ',';
}

echo preg_replace("/,$/","",$s);



// Crushed from http://www.entropy.ch/software/macosx/php/button-image.phps
function hsv2rgb($h, $s, $v)
{
	$h %= 360;

	$h /= 60;
	$i = floor($h);
	$f = $h - $i;
	$p = $v * (1 - $s);
	$q = $v * (1 - $s * $f);
	$t = $v * (1 - $s * (1 - $f));

	switch($i)
	{
		case 0: $r = $v; $g = $t; $b = $p; break;
		case 1: $r = $q; $g = $v; $b = $p; break;
		case 2: $r = $p; $g = $v; $b = $t; break;
		case 3: $r = $p; $g = $q; $b = $v; break;
		case 4: $r = $t; $g = $p; $b = $v; break;
		default: $r = $v; $g = $p; $b = $q; break;
	}

	return array($r * 255, $g * 255, $b * 255);
}


