#!/bin/bash

# Define plugin and language file names
plugin_name="complianz-gdpr-premium"
language_file_prefix="complianz-gdpr"

# Initialize upload mode to 'disabled'
upload_mode="disabled"

# Check if username is provided
if [ -z "$1" ]; then
  echo "Usage: $0 <username> [--enable-upload=plugin|translation]"
  exit 1
fi

# Set username from the first argument
username="$1"

# Check for upload flag as the second argument
case "$2" in
  "--enable-upload=plugin")
    upload_mode="plugin"
    ;;
  "--enable-upload=translation")
    upload_mode="translation"
    ;;
  "--enable-upload="*|"")
    upload_mode="disabled"
    ;;
  *)
    echo "Invalid option for --enable-upload. Use plugin, translation, or leave empty for disabled."
    exit 1
    ;;
esac

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


# Step 3: Download .po files from translate.really-simple-plugins.com
echo "Step 2: Downloading .po files"
./automation/translation_download.sh ${username}

# Step 2: Run a wp script to create .pot file
echo "Step 3: Creating .pot file"
wp i18n make-pot . languages/${language_file_prefix}.pot

# Step 4: Copy .po and .mo files to create 'not main' language files
# Define the base path for the language files
echo "Step 4: Copying .po files to create 'not main' language files"
base_path="./languages"

# Define language mappings as strings
lang_mappings=(
  "nl_NL:nl_BE"
  "fr_FR:fr_BE fr_CA"
  "de_DE:de_CH_informal de_AT"
  "de_DE_formal:de_CH"
  "en_GB:en_NZ en_AU"
  "es_ES:es_EC es_MX es_CO es_VE es_CL es_CR es_GT es_HN es_PE es_PR es_UY es_AR es_DO"
)

# Loop through the array and copy files
for mapping in "${lang_mappings[@]}"; do
  # Split the source and target languages
  IFS=":" read -ra langs <<< "$mapping"
  source_lang="${langs[0]}"
  target_langs="${langs[1]}"

  # Loop through each target language and copy both .po and .mo files
  for target_lang in $target_langs; do
    cp "$base_path/${language_file_prefix}-$source_lang.po" "$base_path/${language_file_prefix}-$target_lang.po"
  done
done

# Step 5: Run a wp script to update .po
echo "Step 5: Updating .po files"
wp i18n update-po languages/${language_file_prefix}.pot

# Step 6: Run a wp script to generate .json files
echo "Step 6: Generating .json files"
wp i18n make-json languages/

echo "Step 7: Generate .mo files"
wp i18n make-mo languages/

# Step 7: Remove existing '${plugin_name}' directory and ZIP if they exist
# This step ensures that there are no conflicts with existing files.
echo "Step 7: Remove existing '${plugin_name}' directory and ZIP if they exist"
cd .. || { echo "Failed to change directory"; exit 1; }

[ -d "updates/${plugin_name}/" ] && rm -r updates/${plugin_name}/
[ -f "updates/${plugin_name}.zip" ] && rm -r updates/${plugin_name}-${stable_tag}.zip


# Step 9: Use rsync to copy files to '${plugin_name}', excluding the defined files and directories
# This step copies only the necessary files to create a clean '${plugin_name}' directory.
echo "Step 9: Copying files to 'updates/${plugin_name}' directory"
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
echo "Step 10: Creating a ZIP archive of the '${plugin_name}' directory within the 'updates' directory"
cd updates || { echo "Failed to change directory"; exit 1; }
zip -qr9 "${plugin_name}-${stable_tag}.zip" ${plugin_name} "__MACOSX" || { echo "Failed to create ZIP archive"; exit 1; }

if [ "$upload_mode" == "plugin" ]; then
  echo "Step 11: Run upload_plugin.sh to upload plugin to translate.really-simple-plugins.com"
  ../${plugin_name}/automation/upload_plugin.sh ${username} "${plugin_name}-${stable_tag}.zip"
  echo "Done! Created 'updates/${plugin_name}-${stable_tag}.zip' and uploaded plugin to translate.really-simple-plugins.com"
elif [ "$upload_mode" == "translation" ]; then
  echo "Step 11: Run upload_languages.sh to upload languages to translate.really-simple-plugins.com"
  ../${plugin_name}/automation/upload_languages.sh ${username}
  echo "Done! Created 'updates/${plugin_name}-${stable_tag}.zip' and uploaded languages to translate.really-simple-plugins.com"
else
  echo "Step 11: Upload disabled"
  echo "Done! Created 'updates/${plugin_name}-${stable_tag}.zip"
fi
