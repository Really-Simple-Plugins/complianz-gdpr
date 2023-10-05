#!/bin/bash

# Initialize upload flag to disabled (0)
upload_enabled=0

# Check if username is provided
if [ -z "$1" ]; then
  echo "Usage: $0 <username> [--enable-upload]"
  exit 1
fi

# Set username from the first argument
username="$1"

# Check for upload flag as the third argument
if [ "$3" == "--enable-upload" ]; then
  upload_enabled=1
fi

# Change to the directory where the script is located
cd "$(dirname "$0")"
cd ..

# Step 1: Remove all .json files from the /languages/
echo "Removing all .json files from /languages/"
find . -name "complianz-gdpr-*.json" -print0 | xargs -0 rm

# Step 2: Run a wp script to create .pot file
echo "Creating .pot file"
wp i18n make-pot . languages/complianz-gdpr.pot

# Step 3: Download .po files from translate.really-simple-plugins.com
echo "Downloading .po files"
./automation/translation_download.sh ${username}

# Step 4: Run a wp script to update .po
echo "Updating .po files"
wp i18n update-po languages/complianz-gdpr.pot

# Step 5: Run a wp script to generate .json files
echo "Generating .json files"
wp i18n make-json languages/

# Extract the stable tag from readme.txt
stable_tag=$(grep "Stable tag:" readme.txt | awk '{print $NF}')

# Step 6: Change to parent directory
echo "Step 6: Changing to parent directory"
cd ..

# Step 7: Remove existing 'complianz-gdpr-premium' directory and ZIP if they exist
echo "Step 7: Removing existing 'complianz-gdpr-premium' directory and ZIP"
rm -r updates/complianz-gdpr-premium/
rm -r updates/complianz-gdpr-premium-${stable_tag}.zip

# Step 8: Define files and directories to exclude during rsync
echo "Step 8: Defining files and directories to exclude"
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

# Step 9: Use rsync to copy files to complianz-gdpr-premium', excluding the defined files and directories
echo "Step 9: Copying files to 'complianz-gdpr-premium' directory"
rsync -avr "${EXCLUDES[@]}" complianz-gdpr-premium/. updates/complianz-gdpr-premium/

# Step 10: Change to 'updates' directory
echo "Step 11: Changing to 'updates' directory"
cd updates


# echo stable_tag
echo "Stable tag: ${stable_tag}"

# Step 11: Create a ZIP archive of the 'complianz-gdpr-premium' directory, named according to the stable tag
echo "Step 11: Creating ZIP archive"
zip -r9 "complianz-gdpr-premium-${stable_tag}.zip" complianz-gdpr-premium "__MACOSX"

# Step 3: Download .po files from translate.really-simple-plugins.com

#if [ $upload_enabled -eq 1 ]; then
  echo "Upload to translate.really-simple-plugins.com"
  ../complianz-gdpr-premium/automation/translation_upload.sh ${username} "complianz-gdpr-premium-${stable_tag}.zip"
#else
#  echo "Upload disabled"
#fi
