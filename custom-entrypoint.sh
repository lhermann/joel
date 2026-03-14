#!/bin/bash
set -e

# Install packages if not already installed
if ! command -v gifsicle &> /dev/null || ! command -v optipng &> /dev/null || ! command -v ffmpeg &> /dev/null || ! command -v jpegtran &> /dev/null; then
    apt-get update
    apt-get install -y gifsicle optipng ffmpeg libjpeg-turbo-progs
    apt-get clean
    rm -rf /var/lib/apt/lists/*
fi

# Install WP-CLI if not already installed
if ! command -v wp &> /dev/null; then
    curl -sO https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x wp-cli.phar
    mv wp-cli.phar /usr/local/bin/wp
fi

# Execute the original entrypoint
exec /usr/local/bin/docker-entrypoint.sh "$@"
