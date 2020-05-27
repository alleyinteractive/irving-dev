#!/bin/bash

set -e

# Bail early if we aren't sniffing.
if [[ $WP_PHPCS != "1" ]]; then
	exit 0
fi

echo "Running phpcs ..."
cd ${WP_CORE_DIR}wp-content
composer run phpcs
