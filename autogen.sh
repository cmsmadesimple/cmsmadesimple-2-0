#!/bin/sh

#If lib/silk doesn't exist, check out the default silk repository
#first. If you're a developer on silk, you can add your own remote
#or re-check it out.
if [ ! -d "lib/silk" ]; then
	git clone git://github.com/tedkulp/silk.git lib/silk
fi

#Create the tmp directories.  If they exist, no harm.
mkdir tmp > /dev/null 2>&1
mkdir tmp/cache > /dev/null 2>&1
chmod 777 tmp/cache > /dev/null 2>&1
mkdir tmp/templates_c > /dev/null 2>&1
chmod 777 tmp/templates_c > /dev/null 2>&1

#Copy .orig files to their proper places, if they don't
#exist
if [ ! -f "config/setup.yml" ]; then
	cp config/setup.yml.orig config/setup.yml > /dev/null 2>&1
fi

#Copy index.php here.  Will copy it no matter what, in case it
#has changed
cp lib/silk/index.php index.php > /dev/null 2>&1

#Do the same with our silk.php command line goodness
cp lib/silk/silk.php silk.php > /dev/null 2>&1
