<?php

###################################################################
# File Download 2.0
###################################################################
# Adapted to PHP5 by BigTalk Jon Ryser http://JonRyser.com
# Visit http://www.zubrag.com/scripts/ for original script updates
###################################################################
# Sample call:
#    download.php?f=phptutorial.zip
#
# Sample call (browser will try to save with new file name):
#    download.php?f=phptutorial.zip&fc=php123tutorial.zip
###################################################################

# Allow direct file download (hotlinking)?
# Empty - allow hotlinking
# If set to nonempty value (Example: example.com) will only allow downloads when referrer contains this text.
define('ALLOWED_REFERRER', DOMAIN_NAME);

# Download folder, i.e. folder where you keep all files for download.
# MUST end with slash (i.e. "/" )
define('BASE_DIR', BODEGA);

# log downloads?  true/false
define('LOG_DOWNLOADS', TRUE);

# log file name
define('LOG_FILE', DOWNLOADS_LOG);

# The size in bytes to chunk the file when downloading.
define('CHUNKSIZE', 1*(1024*1024));

# Allowed extensions list in format 'extension' => 'mime type'
# If myme type is set to empty string then script will try to detect mime type
# itself, which would only work if you have Mimetype or Fileinfo extensions
# installed on server.
$allowed_ext=array(

  # archives
  'zip' => 'application/zip',

  # documents
  'txt' => 'application/txt',
  'rtf' => 'application/rtf',
  'htm' => 'application/html',
  'html' => 'application/html',
  'pdf' => 'application/pdf',
  'doc' => 'application/msword',
  'docx' => 'application/msword',
  'xls' => 'application/vnd.ms-excel',
  'xlsx' => 'application/vnd.ms-excel',
  'ppt' => 'application/vnd.ms-powerpoint',
  'pptx' => 'application/vnd.ms-powerpoint',

  # executables
  'exe' => 'application/octet-stream',

  # images
  'gif' => 'image/gif',
  'png' => 'image/png',
  'jpg' => 'image/jpeg',
  'jpeg' => 'image/jpeg',

  # audio
  'mp3' => 'audio/mpeg',
  'wav' => 'audio/x-wav',

  # video
  'mp4' => 'video/mp4',
  'm4v' => 'video/x-m4v',
  'mpeg' => 'video/mpeg',
  'mpg' => 'video/mpeg',
  'mpe' => 'video/mpeg',
  'mov' => 'video/quicktime',
  'avi' => 'video/x-msvideo',
  'wmv' => 'video/x-ms-wmv',
  '3gp' => 'video/3gpp'
);