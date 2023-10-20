# Automation Scripts Guide

This README provides instructions on how to use the automation scripts located in the \`/automation/\` directory.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Setting Permissions](#setting-permissions)
- [Using create_rc.sh](#using-create_rcsh)
- [Using translation_upload.sh](#using-translation_uploadsh)
- [Using translation_download.sh](#using-translation_downloadsh)

## Prerequisites

1. Make sure you have \`bash\` installed on your system.
2. Ensure you have \`wp-cli\` installed if the scripts use WordPress commands.
3. Make sure you have \`rsync\` and \`zip\` utilities installed if the scripts use them.

## Set up the SSH keys

1. Open terminal
2. Generate SSH Key: Run the following command to generate an SSH key pair. You can replace your_email@example.com with your actual email address.
```bash
 ssh-keygen -t rsa -b 4096 -C "youremail@example.com"
```
3. Press enter 3 times to save in default location and set no passprase. 
4. Run the following command and copy the SSH key to your clipboard. Include your email and ssh-rsa. 
```bash
cat ~/.ssh/id_rsa.pub
```
5. Add SFTP user and add the SSH key to Cloudways SSH keys. 
6. 


## Setting Permissions

Before running these scripts, you need to set the execute permission. Open a terminal and navigate to the \`/automation/\` directory, then run:

```bash
chmod +x create_rc.sh
chmod +x translation_upload.sh
chmod +x translation_download.sh
```

## Using \`create_rc.sh\`

This script automates the process of creating a release candidate.

**How to Run:**

Navigate to the `/automation/` directory and execute:

```bash
./create_rc.sh rlankhorst
```

## Using \`translation_upload.sh\`

This script automates the process of uploading translation files.

**How to Run:**

Navigate to the `/automation/` directory and execute:

```bash
./translation_upload.sh yourUsername
```

## Using \`translation_download.sh\`

This script automates the process of downloading translation files.

**How to Run:**

Navigate to the `/automation/` directory and execute:

```bash
./translation_download.sh yourUsername
```
