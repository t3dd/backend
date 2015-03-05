#!/bin/bash

set -e

COMPOSER=composer
NPM=npm
BOWER=bower

if [ -n "${bamboo_capability_system_builder_command_composer}" ] ; then
	COMPOSER="${bamboo_capability_system_builder_command_composer}"
fi
if [ -n "${bamboo_capability_system_builder_command_npm}" ] ; then
	NPM="${bamboo_capability_system_builder_command_npm}"
fi
if [ -n "${bamboo_capability_system_builder_command_bower}" ] ; then
	BOWER="${bamboo_capability_system_builder_command_bower}"
fi

if [ "$BUILD_ENV" = "ci" ] ; then
	COMPOSER_OPTS=--ignore-platform-reqs php
else
	COMPOSER_OPTS=
fi

$COMPOSER $COMPOSER_OPTS install
$NPM install

for D in Packages/*/T3DD.*/Resources/Public ; do
	[ -d "$D" ] || continue
	[ -f "$D/package.json" ] || continue
	echo "Install npm packages in $D"
	( cd "$D" && $NPM install )
done

for D in Packages/*/T3DD.*/Resources/Public ; do
	[ -d "$D" ] || continue
	[ -f "$D/bower.json" ] || continue
	echo "Install bower packages in $D"
	( cd "$D" && $BOWER install )
done