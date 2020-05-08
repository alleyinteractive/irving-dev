#!/bin/bash

set -e

# Bail early if we aren't building.
if [[ $BUILD != "1" ]]; then
	exit 0
fi

# Bail early if there is no package.json.
if [[ ! -f $1s/$2/package.json ]]; then
	exit 0
fi

echo "Building $1s/$2 ..."
cd $1s/$2
npm install --quiet
npm run build
