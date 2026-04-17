<?php

/**
 * Define missing OCI constants for Oracle extension compatibility
 * This file must be loaded before Laravel application boots
 */

// Define missing OCI constants if they don't exist
if (!defined('OCI_DEFAULT')) {
    define('OCI_DEFAULT', 0);
}

if (!defined('OCI_COMMIT_ON_SUCCESS')) {
    define('OCI_COMMIT_ON_SUCCESS', 0);
}

if (!defined('OCI_DESCRIBE_ONLY')) {
    define('OCI_DESCRIBE_ONLY', 1);
}

if (!defined('OCI_EXPLICIT_FETCH')) {
    define('OCI_EXPLICIT_FETCH', 0);
}

if (!defined('OCI_SEEK_CUR')) {
    define('OCI_SEEK_CUR', 0);
}

if (!defined('OCI_SEEK_SET')) {
    define('OCI_SEEK_SET', 1);
}

if (!defined('OCI_SEEK_END')) {
    define('OCI_SEEK_END', 2);
}

if (!defined('OCI_FETCHSTATEMENT_OFFSET')) {
    define('OCI_FETCHSTATEMENT_OFFSET', 0);
}

if (!defined('OCI_ASSOC')) {
    define('OCI_ASSOC', 1);
}

if (!defined('OCI_NUM')) {
    define('OCI_NUM', 2);
}

if (!defined('OCI_BOTH')) {
    define('OCI_BOTH', 3);
}

if (!defined('OCI_RETURN_NULLS')) {
    define('OCI_RETURN_NULLS', 1);
}

if (!defined('OCI_RETURN_LOBS')) {
    define('OCI_RETURN_LOBS', 2);
}

if (!defined('OCI_DTYPE_FILE')) {
    define('OCI_DTYPE_FILE', 1);
}

if (!defined('OCI_DTYPE_LOB')) {
    define('OCI_DTYPE_LOB', 2);
}

if (!defined('OCI_DTYPE_ROWID')) {
    define('OCI_DTYPE_ROWID', 3);
}

if (!defined('OCI_DTYPE_TIMESTAMP')) {
    define('OCI_DTYPE_TIMESTAMP', 4);
}

if (!defined('OCI_DTYPE_TIMESTAMP_TZ')) {
    define('OCI_DTYPE_TIMESTAMP_TZ', 5);
}

if (!defined('OCI_DTYPE_TIMESTAMP_LTZ')) {
    define('OCI_DTYPE_TIMESTAMP_LTZ', 6);
}

if (!defined('OCI_DTYPE_INTERVAL_YM')) {
    define('OCI_DTYPE_INTERVAL_YM', 7);
}

if (!defined('OCI_DTYPE_INTERVAL_DS')) {
    define('OCI_DTYPE_INTERVAL_DS', 8);
}
