#!/bin/bash

set -e

# Bail early if we are building.
if [[ $BUILD == "1" ]]; then
	exit 0
fi

# Bail early if the requested directory does not exist.
if [[ ! -d $1s/$2/tests ]]; then
	exit 0
fi

echo "Running phpunit on $1s/$2 ..."
cd $1s/$2
phpunit
