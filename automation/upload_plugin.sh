#!/bin/bash

# Define plugin and language file names
plugin_name="complianz-gdpr-premium"
language_file_prefix="complianz-gdpr"

# Check if username is provided
# Exit the script if no username is provided.
if [ -z "$1" ]; then
  echo "Usage: $0 <username>"
  exit 1
fi

# Set username from the first argument
username="$1"
#if second argument is provided, set it as the zip file variable otherwise use ${plugin_name}.zip
if [ -z "$2" ]; then
  zip_file="${plugin_name}.zip"
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

# These mappings define which 'not main' languages correspond to each 'main' language.
# Define language mappings as an array
declare -a lang_mappings=(
  "nl_NL:nl_BE"
  "fr_FR:fr_BE fr_CA"
  "de_DE:de_CH_informal de_AT"
  "de_DE_formal:de_CH"
  "en_GB:en_NZ en_AU"
  "es_ES:es_EC es_MX es_CO es_VE es_CL es_CR es_GT es_HN es_PE es_PR es_UY es_AR es_DO"
)

# Check if the ZIP file exists
# Exit the script if the ZIP file is not found.
if [ ! -f "$zip_file_dir" ]; then
  echo "File not found!"
  exit 1
fi

# Upload the ZIP file
# This step uploads the ZIP file to the remote server.
scp ${zip_file_dir} ${username}@translate.really-simple-plugins.com:${remote_path}/ || { echo "scp failed"; exit 1; }

# Rename the existing '${plugin_name}' folder, if it exists, and append the timestamp
# This step renames any existing '${plugin_name}' folder to avoid conflicts.
ssh ${username}@translate.really-simple-plugins.com "if [ -d ${remote_path}/${plugin_name} ]; then mv ${remote_path}/${plugin_name} ./public_html/wp-content/plugins-backup/${plugin_name}/${plugin_name}-${timestamp}; fi" || { echo "ssh or mv failed"; exit 1; }

# Unzip the new ZIP file
# This step unzips the uploaded ZIP file.
ssh ${username}@translate.really-simple-plugins.com "unzip -q -o ${remote_path}/${zip_file} -d ${remote_path}/" || { echo "ssh or unzip failed"; exit 1; }

# Optionally, remove the ZIP file from the server
ssh ${username}@translate.really-simple-plugins.com "rm ${remote_path}/${zip_file}"

# SSH into the remote server and delete 'not main' language files
echo "Deleting 'not main' language files"
for mapping in "${lang_mappings[@]}"; do
  IFS=":" read -ra langs <<< "$mapping"
  source_lang="${langs[0]}"
  target_langs="${langs[1]}"

  for target_lang in $target_langs; do
    ssh ${username}@translate.really-simple-plugins.com "cd ${remote_path}/${plugin_name}/languages/ && rm -f ${language_file_prefix}-$target_lang.po && echo \"Deleted: ${language_file_prefix}-$target_lang.po\"" || { echo "ssh or rm failed for $target_lang"; exit 1; }
    ssh ${username}@translate.really-simple-plugins.com "cd ${remote_path}/${plugin_name}/languages/ && rm -f ${language_file_prefix}-$target_lang.mo && echo \"Deleted: ${language_file_prefix}-$target_lang.mo\"" || { echo "ssh or rm failed for $target_lang"; exit 1; }
  done
done

# SSH into the remote server and run loco sync
ssh ${username}@translate.really-simple-plugins.com "cd public_html && wp loco sync ${language_file_prefix} && echo \"Synced Loco: ${language_file_prefix}\"" || { echo "ssh or wp loco sync failed"; exit 1; }
