#!/bin/sh
for y in 2009; do for w in $(seq -w 52); curl 'http://gs.statcounter.com/chart.php?statType_hidden=browser_version&region_hidden=ww&granularity=weekly&statType=Browser%20Version&region=Worldwide&fromWeekYear='$y'-'$w'&toWeekYear='$y'-'$w'&csv=1' > $y-$w.csv; done

