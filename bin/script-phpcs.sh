#!/bin/bash

set -e

# Bail early if we aren't linting.
if [[ $WP_PHPCS != "1" ]]; then
	exit 0
fi

# Bail early if the requested directory does not exist.
if [[ ! -d $1s/$2 ]]; then
	exit 0
fi

echo "Running phpcs on $1s/$2 ..."
cd $1s/$2
phpcs -v
