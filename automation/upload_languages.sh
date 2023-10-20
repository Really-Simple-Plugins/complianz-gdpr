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

# Define variables
# Set the remote path where the language files will be uploaded.
remote_path="./public_html/wp-content/plugins"
# Get the directory of the current script
script_dir="$(dirname "$0")"

# Generate a timestamp
# This timestamp is used for the backup directory.
timestamp=$(date +"%Y%m%d%H%M%S")
backup_dir="translations-backup/${plugin_name}"
backup_dir_timestamp="${backup_dir}/${timestamp}"

# SSH into the remote server, change to the wp-content directory, create a backup directory, and copy .po files into it
echo "Creating backup directory and copying .po files"
ssh ${username}@translate.really-simple-plugins.com "cd ./public_html/wp-content/ && mkdir -p ${backup_dir_timestamp} && cp plugins/${plugin_name}/languages/*.po ${backup_dir_timestamp}/"

# Check the number of backup folders and delete the oldest if there are more than 10
echo "Checking number of backup folders and deleting the oldest if there are more than 10"
ssh ${username}@translate.really-simple-plugins.com "cd ./public_html/wp-content/${backup_dir} && [ \$(ls -1 | wc -l) -gt 10 ] && ls -1tr | head -n 1 | xargs -I {} rm -r {}"

# Define language mappings as an array
declare -a lang_mappings=(
  "nl_NL:nl_BE"
  "fr_FR:fr_BE fr_CA"
  "de_DE:de_CH_informal de_AT"
  "de_DE_formal:de_CH"
  "en_GB:en_NZ en_AU"
  "es_ES:es_EC es_MX es_CO es_VE es_CL es_CR es_GT es_HN es_PE es_PR es_UY es_AR es_DO"
)

# Upload all .pot and .po files to the remote server
echo "Uploading .pot and .po files"
scp ${script_dir}/../languages/${language_file_prefix}*.{pot,po} ${username}@translate.really-simple-plugins.com:${remote_path}/${plugin_name}/languages/ || { echo "scp failed"; exit 1; }

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


#cd ..
# run wp loco sync ${textdomain}
ssh ${username}@translate.really-simple-plugins.com "cd public_html && wp loco sync ${language_file_prefix} && echo \"Synced Loco: ${language_file_prefix}\"" || { echo "ssh or wp loco sync failed"; exit 1; }
