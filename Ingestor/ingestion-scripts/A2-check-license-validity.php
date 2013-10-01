#!/usr/bin/env php

<?
# Author: andrew.galloway@nexenta.com
# Created On: 2013-09-26
# Last Updated On: 2013-09-26
# Description:
#   checks if license is expired or not

# include generic functions file
include '/root/Collector/Ingestor/ingestion-scripts/functions.php';

# name of this script - could be filename, or something unique people will recognize
$SCRIPT_NAME = "A2-check-license-validity.php";

# put your actual code within this function, be sure to exit 0 if successful and
# exit 1 if not
function main($BUNDLE_DIR) {
    $WARN_FILE = $BUNDLE_DIR . "/ingestor/warnings/check-license-validity";

    if (is_file($BUNDLE_DIR . "/appliance/nlm.key")) {
        $license_key = file_get_contents($BUNDLE_DIR . "/appliance/nlm.key");
        $license_key = trim($license_key);

        $expires = expiryTimestamp($license_key);

        if (time() > $expires) {
            file_put_contents($WARN_FILE, " - License key appears to be expired.\n");
        }
    }

    exit(0);
}

# this runs first, and does sanity checking before invoking main() function

# check for necessary directory argument and runtime
if (php_sapi_name() != 'cli') {
    print "Must be run from commandline!\n";
    exit(1);
} else {
    if (!array_key_exists(1, $argv)) {
        print "Must be passed directory as argument!\n";
        exit(1);
    } else {
        if (!is_dir($argv[1])) {
            print "Directory invalid!\n";
            exit(1);
        } else {
            main($argv[1]);
        }
    }
}

?>
