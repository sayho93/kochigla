<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/shared/bases/Const.php";
/**
 * @description : A Class for Defining constants and Setting Conduction Mode
 * @author : PickleCode
 * @apiNote : DO NOT MODIFY THE CONSTANTS UNLESS YOU EXACTLY KNOW WHAT YOU ARE DOING
 */
if(!class_exists("Configs")) {

	class Configs{

        var $CONFIG;
        var $CONFIG_MODE;
        var $PF_URL;
        var $PF_API;
        var $PF_FILE_PATH;
        var $PF_FILE_TEMP_PATH;
        var $PF_FILE_DISPLAY_PATH;
        var $PF_FILE_PATH_1080;
        var $PF_FILE_PATH_720;
        var $PF_FILE_PATH_640;
        var $PF_FILE_PATH_480;
        var $PF_FILE_PATH_320;
        var $PF_FILE_PATH_100;
        var $PF_DB_HOST;
        var $PF_DB_NAME;
        var $PF_DB_USER;
        var $PF_DB_PASSWORD;
        var $PF_DB_CHARSET;

		function __construct(){
        	$this->init();
        }

		function init(){
		    $DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
            /**
             * Variables which can be changed by developers and environments
             * @changeable true
             */
            $this->CONFIG_MODE = CONDUCT_MODE_DEV;
            $this->CONFIG = array(
                CONDUCT_MODE_DEV => array(
                    /**
                     * File Paths for DEV MODE
                     */
                    URL => "http://localhost/mygift",
                    API_PATH => "/mygift/shared/public/route.php?F=",
                    URL_PATH => $DOCUMENT_ROOT."/mygift/rawFiles",
                    URL_PATH_TEMP => $DOCUMENT_ROOT."/mygift/tempFiles",
                    URL_DISPLAY_PATH => "/mygift/rawFiles",
                    URL_PATH_100 => $DOCUMENT_ROOT."/mygift/file_100",
                    URL_PATH_320 => $DOCUMENT_ROOT."/mygift/file_320",
                    URL_PATH_480 => $DOCUMENT_ROOT."/mygift/file_480",
                    URL_PATH_640 => $DOCUMENT_ROOT."/mygift/file_640",
                    URL_PATH_720 => $DOCUMENT_ROOT."/mygift/file_720",
                    URL_PATH_1080 => $DOCUMENT_ROOT."/mygift/file_1080",
                    /**
                     * Database Config for DEV MODE
                     */
                    DATABASE_HOST => "picklecode.co.kr",
                    DATABASE_NAME => "kochigla",
                    DATABASE_USER => "kochigla",
                    DATABASE_PASSWORD => "kochigla!@#$",
                    DATABASE_CHARSET => "utf8"
                ),
                CONDUCT_MODE_TEST => array(
                    /**
                     * File Paths for TEST MODE
                     */
                    URL => "http://picklecode.co.kr/mygift",
                    API_PATH => "/mygift/shared/public/route.php?F=",
                    URL_PATH => $DOCUMENT_ROOT."/mygift/rawFiles",
                    URL_PATH_TEMP => $DOCUMENT_ROOT."/mygift/tempFiles",
                    URL_DISPLAY_PATH => $DOCUMENT_ROOT."/mygift/file_display",
                    URL_PATH_100 => $DOCUMENT_ROOT."/mygift/file_100",
                    URL_PATH_320 => $DOCUMENT_ROOT."/mygift/file_320",
                    URL_PATH_480 => $DOCUMENT_ROOT."/mygift/file_480",
                    URL_PATH_640 => $DOCUMENT_ROOT."/mygift/file_640",
                    URL_PATH_720 => $DOCUMENT_ROOT."/mygift/file_720",
                    URL_PATH_1080 => $DOCUMENT_ROOT."/mygift/file_1080",
                    /**
                     * Database Config for TEST MODE
                     */
                    DATABASE_HOST => "picklecode.co.kr",
                    DATABASE_NAME => "kochigla",
                    DATABASE_USER => "kochigla",
                    DATABASE_PASSWORD => "kochigla!@#$",
                    DATABASE_CHARSET => "utf8"
                ),
                CONDUCT_MODE_LIVE => array(
                    /**
                     * File Paths for LIVE MODE
                     */
                    URL => "http://picklecode.co.kr/mygift",
                    API_PATH => "/mygift/shared/public/route.php?F=",
                    URL_PATH => $DOCUMENT_ROOT."/mygift/rawFiles",
                    URL_PATH_TEMP => $DOCUMENT_ROOT."/mygift/tempFiles",
                    URL_DISPLAY_PATH => $DOCUMENT_ROOT."/mygift/file_display",
                    URL_PATH_100 => $DOCUMENT_ROOT."/mygift/file_100",
                    URL_PATH_320 => $DOCUMENT_ROOT."/mygift/file_320",
                    URL_PATH_480 => $DOCUMENT_ROOT."/mygift/file_480",
                    URL_PATH_640 => $DOCUMENT_ROOT."/mygift/file_640",
                    URL_PATH_720 => $DOCUMENT_ROOT."/mygift/file_720",
                    URL_PATH_1080 => $DOCUMENT_ROOT."/mygift/file_1080",
                    /**
                     * Database Config for LIVE MODE
                     */
                    DATABASE_HOST => "picklecode.co.kr",
                    DATABASE_NAME => "kochigla",
                    DATABASE_USER => "kochigla",
                    DATABASE_PASSWORD => "kochigla!@#$",
                    DATABASE_CHARSET => "utf8"
                )
            );

            /**
             * Variables which must not be changed
             * @apiNote DO NOT MODIFY UNLESS YOU EXACTLY KNOW WHAT YOU ARE DOING
             * @description Variables to be used by developers
             */
            $this->PF_URL = $this->CONFIG[$this->CONFIG_MODE][URL];
            $this->PF_API = $this->CONFIG[$this->CONFIG_MODE][API_PATH];
            $this->PF_FILE_PATH = $this->CONFIG[$this->CONFIG_MODE][URL_PATH];
            $this->PF_FILE_TEMP_PATH = $this->CONFIG[$this->CONFIG_MODE][URL_PATH_TEMP];
            $this->PF_FILE_DISPLAY_PATH = $this->CONFIG[$this->CONFIG_MODE][URL_DISPLAY_PATH];
            $this->PF_FILE_PATH_1080 = $this->CONFIG[$this->CONFIG_MODE][URL_PATH_1080];
            $this->PF_FILE_PATH_720 = $this->CONFIG[$this->CONFIG_MODE][URL_PATH_720];
            $this->PF_FILE_PATH_640 = $this->CONFIG[$this->CONFIG_MODE][URL_PATH_640];
            $this->PF_FILE_PATH_480 = $this->CONFIG[$this->CONFIG_MODE][URL_PATH_480];
            $this->PF_FILE_PATH_320 = $this->CONFIG[$this->CONFIG_MODE][URL_PATH_320];
            $this->PF_FILE_PATH_100 = $this->CONFIG[$this->CONFIG_MODE][URL_PATH_100];
            $this->PF_DB_HOST = $this->CONFIG[$this->CONFIG_MODE][DATABASE_HOST];
            $this->PF_DB_NAME = $this->CONFIG[$this->CONFIG_MODE][DATABASE_NAME];
            $this->PF_DB_USER = $this->CONFIG[$this->CONFIG_MODE][DATABASE_USER];
            $this->PF_DB_PASSWORD = $this->CONFIG[$this->CONFIG_MODE][DATABASE_PASSWORD];
            $this->PF_DB_CHARSET = $this->CONFIG[$this->CONFIG_MODE][DATABASE_CHARSET];
		}
	}
}

?>
