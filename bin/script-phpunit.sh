#!/bin/bash

set -e

# Bail early if we are building.
if [[ $BUILD == "1" ]]; then
	exit 0
fi

# Bail early if the requested directory does not exist.
if [[ ! -d $1/$2/tests ]]; then
	exit 0
fi

echo "Running phpunit on $1/$2 ..."
cd "${WP_CORE_DIR}wp-content"
cd $1/$2
# Output version for debugging.
phpunit --version
phpunit
WP_MULTISITE=1 phpunit
