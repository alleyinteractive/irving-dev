#!/bin/bash

set -e

# Bail early if we aren't linting.
if [[ $PHP_LINT != "1" ]]; then
	exit 0
fi

# Bail early if the requested directory does not exist.
if [[ ! -d $1/$2 ]]; then
	exit 0
fi

echo "Running the PHP linter on $1/$2 ..."
find ./$1/$2 -type "f" -iname "*.php" -not -path "./vendor/*" | xargs -L "1" php -l
