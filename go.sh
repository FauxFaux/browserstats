#!/bin/sh
(printf "set terminal png size 2000,2000\nset key off\nset xdata time; set timefmt \"%%Y-%%m-%%d\"\n"; php -r 'array_shift($argv); foreach ($argv as $f) { $fs[]=$f; foreach (file($f) as $l) { $x = preg_split("/,/", $l); $bs[$x[0]] = true; @$o[$f][$x[0]] = (float)$x[1]; } } unset($bs["\n"]); $bs = array_keys($bs); sort($bs); foreach ($fs as $f) { @$d.=date("Y-m-d", strtotime(preg_replace("/-/","W",preg_replace("/.csv$/","",$f)))); $r=0; foreach ($bs as $b) { $d.=" " . (isset($o[$f][$b]) ? ($r+=$o[$f][$b]) : $r); } $d.="\n"; } $s = "plot "; $i=2; foreach ($bs as $b) { $s .= " \"dat\" using 1:" . ($i++) . " t $b w filledcurves,"; }; file_put_contents("dat", $d); echo preg_replace("/,$/","",$s);' *.csv) | gnuplot