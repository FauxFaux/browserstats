#!/bin/sh

for f in *.csv; do <$f sed 1,2d | perl -pe 's/"//g;s/ ([^ ]*),/\t$1\t/' | cut -f1 | head -n30; done | sort | uniq -c | sort -rn | sed 's/ *[0-9]* //'

