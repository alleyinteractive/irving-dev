#!/bin/bash

set -e

# Bail early if we aren't building.
if [[ $BUILD != "1" ]]; then
	exit 0
fi

# Bail early if there is no package.json.
if [[ ! -f $1/$2/package.json ]]; then
	echo "No package.json."
	exit 0
fi

echo "Building $1/$2 ..."
cd $1/$2
npm install --quiet
npm run build
