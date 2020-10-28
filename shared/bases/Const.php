<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2018-08-18
 * Time: 오전 8:49
 */
/**
 * Constants Definitions
 * @author : PickleCode
 */

// Conducting Mode Def.
define("CONDUCT_MODE_DEV", "conduct_mode_dev"); // ON DEVELOPMENT
define("CONDUCT_MODE_TEST", "conduct_mode_test"); // ON TEST
define("CONDUCT_MODE_LIVE", "conduct_mode_live"); // ON LIVE

// Constant for default Value
define("PF_DEFAULT", "#");
define("ROUTE_PARAMETER", "f");
define("ROUTE_PARAMETER_UPPER", "F");

// Config Variable Key
define("URL_DISPLAY_PATH", "URL_DISPLAY_PATH");
define("URL_PATH", "URL_PATH");
define("URL_PATH_TEMP", "URL_PATH_TEMP");
define("URL_PATH_1080", "URL_PATH_1080");
define("URL_PATH_720", "URL_PATH_720");
define("URL_PATH_640", "URL_PATH_640");
define("URL_PATH_480", "URL_PATH_480");
define("URL_PATH_320", "URL_PATH_320");
define("URL_PATH_100", "URL_PATH_100");
define("DATABASE_HOST", "DATABASE_HOST");
define("DATABASE_NAME", "DATABASE_NAME");
define("DATABASE_USER", "DATABASE_USER");
define("DATABASE_PASSWORD", "DATABASE_PASSWORD");
define("DATABASE_CHARSET", "DATABASE_CHARSET");

// PREFIXES
define("ERROR", "PF_ERROR : ");

// Constant Messages
define("MSG_INVALID_COMMAND", ERROR."Invalid Route Parameter. [F]");
define("MSG_CLASS_NOT_EXISTS", ERROR."Invalid Route Parameter. [C]");
define("MSG_METHOD_NOT_EXISTS", ERROR."Invalid Route Parameter. [M]");

define("KEY_USER_AUTH_INFO", "PICKLE_OFFICIAL_KEY_USER_AUTH_INFO_mygift");
define("KEY_ADMIN_AUTH_INFO", "PICKLE_OFFICIAL_KEY_ADMIN_AUTH_INFO_mygift");

// AES Encryption Key
define("AES_KEY", "pkcd931018950503");
define("AES_KEY_128", substr(AES_KEY, 0, 128 / 8));
define("AES_KEY_256", substr(AES_KEY, 0, 256 / 8));

// recaptcha Key
define("REC_SITE_KEY", "6LfZLYgUAAAAAOaamKx26Ffn0kPK6PsTBsdFLhPY");
define("REC_SECRET_KEY", "6LfZLYgUAAAAAK1b6sH4zEnnR8uGnDeEB1WE-1LA");

// Point
define("POINT_AVALANCHE", "_PKCODE_AVALANCHE_");

?>

