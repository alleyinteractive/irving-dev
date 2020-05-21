#!/bin/bash

set -e

# Bail early if we aren't building.
if [[ $TEST_BUILD != "1" ]]; then
	exit 0
fi

# Bail early if there is no package.json.
if [[ ! -f $1/package.json ]]; then
	echo "No package.json."
	exit 0
fi

echo "Building $1 ..."
cd $1
npm install --quiet
npm run build
