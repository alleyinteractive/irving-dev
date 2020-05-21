#!/bin/bash

set -e

# Bail early if we aren't building.
if [[ $TEST_BUILD != "1" ]]; then
	exit 0
fi

echo "Running JavaScript tests on $1s/$2 ..."
cd $1s/$2
npm run test
