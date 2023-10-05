#!/bin/bash

# Check if username is provided
if [ -z "$1" ]; then
  echo "Usage: $0 <username>"
  exit 1
fi

# Set username from the first argument
username="$1"

# Change to the directory where the script is located
cd "$(dirname "$0")"
cd ..

# Create a timestamp
timestamp=$(date +"%Y%m%d%H%M%S")
backup_dir="translations-backup/complianz-gdpr-premium"
backup_dir_timestamp="translations-backup/complianz-gdpr-premium/${timestamp}"

# SSH into the remote server, change to the wp-content directory, create a backup directory, and copy .po files into it
ssh ${username}@translate.really-simple-plugins.com "cd ./public_html/wp-content/ && mkdir -p ${backup_dir_timestamp} && cp plugins/complianz-gdpr-premium/languages/*.po ${backup_dir_timestamp}/"

# Check the number of backup folders and delete the oldest if there are more than 10
ssh ${username}@translate.really-simple-plugins.com "cd ./public_html/wp-content/${backup_dir} && [ \$(ls -1 | wc -l) -gt 10 ] && ls -1tr | head -n 1 | xargs -I {} rm -r {}"

# Download .po files
scp ${username}@translate.really-simple-plugins.com:/public_html/wp-content/plugins/complianz-gdpr-premium/languages/*.po languages/
