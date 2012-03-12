set terminal png size 2000,2000
#set key off
set xdata time
set timefmt "%Y-%m-%d"
set yrange [0:100]
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
	if ($v < 20)
		unset($bs[$br]);

$bs = array_keys($bs);
natsort($bs);

foreach ($fs as $f) {
	@$d.=date("Y-m-d", strtotime(preg_replace("/-/","W",preg_replace("/.csv$/","",$f))));
	$r=0;
	foreach ($bs as $b) {
		$d.=" " . ($r += (isset($o[$f][$b]) ? $o[$f][$b] : 0));
	}
	$d.="\n";
}

file_put_contents("dat", $d);

$s = "plot";
$i=2;
foreach ($bs as $b) {
	$s .= " \"dat\" using 1:" . ($i++) . " t $b w lines,";
}

echo preg_replace("/,$/","",$s);

