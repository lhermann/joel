#!/bin/bash
set -e

# Install packages if not already installed
if ! command -v gifsicle &> /dev/null || ! command -v optipng &> /dev/null || ! command -v ffmpeg &> /dev/null; then
    apt-get update
    apt-get install -y gifsicle optipng ffmpeg
    apt-get clean
    rm -rf /var/lib/apt/lists/*
fi

# Execute the original entrypoint
exec docker-entrypoint.sh "$@"
