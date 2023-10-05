#!/bin/bash

# Check if username is provided
if [ -z "$1" ]; then
  echo "Usage: $0 <username>"
  exit 1
fi

# Set username from the first argument
username="$1"
#if second argument is provided, set it as the zip file variable otherwise use complianz-gdpr-premium.zip
if [ -z "$2" ]; then
  zip_file="complianz-gdpr-premium.zip"
else
  zip_file="$2"
fi

# Define variables
remote_path="./public_html/wp-content/plugins"
# Get the directory of the current script
script_dir="$(dirname "$0")"

# Define the relative path to the ZIP file
zip_file_dir="${script_dir}/../../updates/${zip_file}"

# Generate a timestamp
timestamp=$(date +"%Y%m%d%H%M%S")

# check if file exists
if [ ! -f "$zip_file_dir" ]; then
    echo "File not found!"
    exit 1
fi

# Upload the ZIP file
scp ${zip_file_dir} ${username}@translate.really-simple-plugins.com:${remote_path}/

# Rename the existing 'complianz-gdpr-premium' folder, if it exists, and append the timestamp
ssh ${username}@translate.really-simple-plugins.com "if [ -d ${remote_path}/complianz-gdpr-premium ]; then mv ${remote_path}/complianz-gdpr-premium ./public_html/wp-content/plugins-backup/complianz-gdpr-premium/complianz-gdpr-premium-${timestamp}; fi"

# Unzip the new ZIP file
ssh ${username}@translate.really-simple-plugins.com "unzip -o ${remote_path}/${zip_file} -d ${remote_path}/"

# Optionally, remove the ZIP file from the server
 ssh ${username}@translate.really-simple-plugins.com "rm ${remote_path}/${zip_file}"
