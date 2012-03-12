set terminal png size 2000,2000
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
foreach ($bs as $b) {
	++$i;
	$s .= "\"dat\" using ($$i):xtic(1) title $b,";
}

echo preg_replace("/,$/","",$s);

