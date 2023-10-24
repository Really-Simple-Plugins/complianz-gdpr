#!/bin/bash
# Check if a commands exists
check_command() {
  if ! command -v "$1" &> /dev/null; then
    echo "Error: $1 could not be found."
    exit 1
  fi
}
check_command wp
check_command scp
check_command ssh
check_command zip

# Define plugin and language file names
plugin_name="complianz-gdpr-premium"
language_file_prefix="complianz-gdpr"

# Initialize upload mode to 'disabled'
upload_mode="false"

# Check if username is provided
if [ -z "$1" ]; then
  echo "Usage: $0 <username> [--upload]"
  exit 1
fi

# Set username from the first argument
username="$1"

# Check for upload flag as the second argument
if [[ "$2" == "--upload" ]]; then
    upload_mode="true"
else
    upload_mode="false"
fi

# Define variables
remote_path="./public_html/wp-content/plugins"
# Get the directory of the current script

# Generate a timestamp
timestamp=$(date +"%Y%m%d%H%M%S")

# Define language mappings as an array
declare -a lang_mappings=(
  "nl_NL:nl_BE"
  "fr_FR:fr_BE fr_CA"
  "de_DE:de_CH_informal de_AT"
  "de_DE_formal:de_CH"
  "en_GB:en_NZ en_AU"
  "es_ES:es_EC es_MX es_CO es_VE es_CL es_CR es_GT es_HN es_PE es_PR es_UY es_AR es_DO"
)


# Define function to create RC
create_rc_zip() {
	# Step 1: Remove all .json files from the /languages/
	echo "Create RC for ${plugin_name}"
  echo "Create RC #1: Remove existing '${plugin_name}' directory and ZIP if they exist"
  cd .. || { echo "Failed to change directory"; exit 1; }

  [ -d "updates/${plugin_name}/" ] && rm -r updates/${plugin_name}/
  [ -f "updates/${plugin_name}.zip" ] && rm -r updates/${plugin_name}-${stable_tag}.zip


  # Step 9: Use rsync to copy files to '${plugin_name}', excluding the defined files and directories
  # This step copies only the necessary files to create a clean '${plugin_name}' directory.
  echo "Create RC #2: Copying files to 'updates/${plugin_name}' directory"
  EXCLUDES=(
    "--exclude=.git"
    "--exclude=.min.min."
    "--exclude=.DS_Store"
    "--exclude=.idea"
    "--exclude=.gitlab-ci.yml"
    "--exclude=phpunit.xml.dist"
    "--exclude=tests/"
    "--exclude=bin/"
    "--exclude=/vendor/"
    "--exclude=/automation/"
    "--exclude=composer.*"
    "--exclude=.phpcs.xml.dist"
    "--exclude=prepros.config"
    "--exclude=.eslint"
    "--exclude=node_modules"
    "--exclude=composer.phar"
    "--exclude=composer.lock"
    "--exclude=package.json"
    "--exclude=package-lock.json"
    "--exclude=.editorconfig"
    "--exclude=gulpfile.js"
    "--exclude=/.phpunit.cache/"
    "--exclude=.phpunit.cache"
    "--exclude=phpcs.xml.dist"
    "--exclude=.eslintignore"
    "--exclude=.eslintrc.json"
    "--exclude=.gitignore"
    "--exclude=.phpunit.result.cache"
  )

  rsync -aqr "${EXCLUDES[@]}" ${plugin_name}/. updates/${plugin_name}/ || { echo "rsync failed"; exit 1; }

  # Step 10: Create a ZIP archive of the '${plugin_name}' directory, named according to the stable tag
  echo "Create RC #3: Creating a ZIP archive of the '${plugin_name}' directory within the 'updates' directory"
  cd updates || { echo "Failed to change directory"; exit 1; }
  zip -qr9 "${plugin_name}-${stable_tag}.zip" ${plugin_name} "__MACOSX" || { echo "Failed to create ZIP archive"; exit 1; }
  echo "Create RC Done! Created 'updates/${plugin_name}-${stable_tag}.zip"
}

download_po_files() {
	# @todo make sure we are in the right folder
	# The right folder is the root of the plugin
	echo "Downloading .po files"
	scp ${username}@translate.really-simple-plugins.com:/public_html/wp-content/plugins/${plugin_name}/languages/*.po languages/
}

upload_plugin() {

  zip_file="$1"

  # Define the relative path to the ZIP file
  zip_file_dir="${zip_file}"

	# cd to script dir
	cd "$(dirname "$0")" || { echo "Failed to change directory"; exit 1; }
	# Change to wp content/plugins/updates dir
	cd ../updates || { echo "Failed to change directory"; exit 1; }

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
}

# Change to the directory where the script is located
# This ensures that all subsequent commands are run from the correct directory.
cd "$(dirname "$0")" || { echo "Failed to change directory"; exit 1; }
cd .. || { echo "Failed to change directory"; exit 1; }
echo "Changed to directory: $(pwd), starting script..."

# Extract the stable tag from readme.txt
stable_tag=$(grep "Stable tag:" readme.txt | awk '{print $NF}')

# Step 1: Remove all .json files from the /languages/
echo "Step 1: Removing all .json, .mo, .po files from /languages/"
find . -name "${language_file_prefix}-*.json" -print0 | xargs -0 rm
find . -name "${language_file_prefix}-*.mo" -print0 | xargs -0 rm
find . -name "${language_file_prefix}-*.po" -print0 | xargs -0 rm

echo "Step 2: Creating .pot file"
wp i18n make-pot . languages/${language_file_prefix}.pot

# Step 3: Download .po files from translate.really-simple-plugins.com
echo "Step 3: Downloading .po files"
download_po_files


if [ "$upload_mode" == "true" ]; then
	echo "Step 5: Remote upload and sync with translate.really-simple-plugins.com"
	echo "Step 5.1: Create RC"
  create_rc_zip

	echo "Step 5.2: Upload plugin to translate.really-simple-plugins.com"
	# cd to updates folder
	upload_plugin "${plugin_name}-${stable_tag}.zip"

	echo "Step 5.3: Remove secondary languages from translate.really-simple-plugins.com"
		# SSH into the remote server and delete 'not main' language files
		for mapping in "${lang_mappings[@]}"; do
			IFS=":" read -ra langs <<< "$mapping"
			source_lang="${langs[0]}"
			target_langs="${langs[1]}"

			for target_lang in $target_langs; do
				ssh ${username}@translate.really-simple-plugins.com "cd ${remote_path}/${plugin_name}/languages/ && rm -f ${language_file_prefix}-$target_lang.po && echo \"Deleted: ${language_file_prefix}-$target_lang.po\"" || { echo "ssh or rm failed for $target_lang"; exit 1; }
				ssh ${username}@translate.really-simple-plugins.com "cd ${remote_path}/${plugin_name}/languages/ && rm -f ${language_file_prefix}-$target_lang.mo && echo \"Deleted: ${language_file_prefix}-$target_lang.mo\"" || { echo "ssh or rm failed for $target_lang"; exit 1; }
			done
		done

	echo "Step 5.4: Delete all .json files from the /languages folder on translate.really-simple-plugins.com"
	ssh ${username}@translate.really-simple-plugins.com "cd ${remote_path}/${plugin_name}/languages/ && rm -f ${language_file_prefix}-*.json && echo \"Deleted: ${language_file_prefix}-*.json\"" || { echo "ssh or rm failed for $target_lang"; exit 1; }

	echo "Step 5.5: SSH Loco sync"
  ssh ${username}@translate.really-simple-plugins.com "cd public_html && wp loco sync ${language_file_prefix} && echo \"Synced Loco: ${language_file_prefix}\"" || { echo "ssh or wp loco sync failed"; exit 1; }

  # cd back to root of plugin
  cd "$(dirname "$0")" || { echo "Failed to change directory"; exit 1; }

	echo "Step 5.6: Download .po files"
	download_po_files
else
	echo "Step 5: Upload mode is disabled"
fi

echo "Step 6: Copy PO files from main language to secondary languages using lang_mappings"
# Loop through the array and copy files
for mapping in "${lang_mappings[@]}"; do
  # Split the source and target languages
  IFS=":" read -ra langs <<< "$mapping"
  source_lang="${langs[0]}"
  target_langs="${langs[1]}"

  # Loop through each target language and copy .po files
  for target_lang in $target_langs; do
    cp "languages/${language_file_prefix}-$source_lang.po" "languages/${language_file_prefix}-$target_lang.po"
  done
done


echo "Step 7: Make JSON"
wp i18n make-json languages/

echo "Step 8: Make MO"
wp i18n make-mo languages/

echo "Step 9: Create RC"
create_rc_zip

echo "Done!"
