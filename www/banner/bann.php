<?php
/*
 * Copyright (c) 2009 adlabs.com.ua
 * Authors: dev@adlabs.com.ua 
 */

/*
 * ADLABS_DEBUG - ���������, ������������ ����� �������
 * 0 - ������� ���������. ��������� �� ������� �� ��������� �� �����
 * 1 - ������� ��������
 */
define('ADLABS_DEBUG',0);

/*
 * ADLABS_CONNECTION_TYPE - ���������� ��� ����������
 * 0 - ���������� ����� Socket. ������������� ��� PHP4 � ��� PHP5 c ����������� CURL. ������� �� ���������
 * 1 - ���������� ����� CURL. 
 */
define('ADLABS_CONNECTION_TYPE',1);	

/*
 * ADLABS_CONNECT_TIMEOUT - ����� �������� �����������, ��� 
 */
define('ADLABS_CONNECT_TIMEOUT',2);

/*
 * ADLABS_USE_CASH - ����������, ���������� �� ������������� ����
 * 0 - ������ ����� ���������� ���������� ����� ���������� � addbanner.biz
 * 1 - ������ ������������� �� ��������� ������
 */
define('ADLABS_USE_CASH',0);

/*
 * ADLABS_PATH - ���� ������������ ���, �� ADLABS_PATH - ��� ��������, � ������� �������� ����� ����
 * ��� ������ � ����� � �������� ������ ���� ������� ������� 777. �� ������ ���������� � ����� �����  
 */
define('ADLABS_PATH','efd6410271c5099f5675dd919805bbee');	  

/*
 * ADLABS_HOST - �������� ��� �������, �������������� ����������
 * ���� ��������� ����� DNS ����������, ����� ������������ IP-����� 89.249.22.205
 */
define('ADLABS_HOST','addbanner.biz');

/*
 * ADLABS_SCRIPT - ���������, ������������ ��� �������, ������� �������� �� ����� ����������
 */
define('ADLABS_SCRIPT','get.php');		

/*
 * ADLABS_OUTPUT_MODE
 * 0 - ���������� ��������������� ��������� �� �����. ����������� �� ���������
 * 1 - ������ ���������� ����������. �� ����� ��� �� ����� �������� ������ ������� 
 */
define('ADLABS_OUTPUT_MODE',1);

/*
 * ADLABS_PHP_VERSION
 * 5 - �������� ��� php5. ����������� �� ���������
 * 4 - �������� ��� php4 
 */
define('ADLABS_PHP_VERSION',5);

/*
 * ADLABS_STRICT_CASH_SIZE
 * 1 - ������������ ������ ����
 * 0 - �� ������������ ������ ����. ����������� �� ���������
 */
define('ADLABS_STRICT_CASH_SIZE',0);

/*
 * ADLABS_CLUSTER_SIZE
 * ���� ����������� ����������� ������� ����,
 * ���������� ������� ������ �������� ������� � KB
 */
define('ADLABS_CLUSTER_SIZE',4);

/*
 * ADLABS_CASH_SIZE
 * ���� ����������� ����������� ������� ����,
 * ���������� ������� ������ ���� � KB
 */
define('ADLABS_CASH_SIZE',10240);

/*
 * ADLABS_NAME_CASH_SIZE
 * ���� ����������� ����������� ������� ����,
 * ���������� ������� ��� �����, ��� ����� ��������� ������� ������
 */
define('ADLABS_NAME_CASH_SIZE','size.ini');

 function deleteCash($filename){
 	foreach($filename as $key=>$url){
 		if(preg_match('/du_.+/Uis',$key)){
 			echo $url.'$$$';
		 	$url = urlencode($url);
		 	$url = $_SERVER['DOCUMENT_ROOT']."/".ADLABS_PATH.'/'.md5($url);
		 	$result = 0;
		 	if(file_exists($url) && @fsockopen(ADLABS_HOST, 80, &$errno, &$errstr, 2)){ 
				if(ADLABS_STRICT_CASH_SIZE){
					$filename = $_SERVER['DOCUMENT_ROOT']."/".ADLABS_PATH.'/'.ADLABS_NAME_CASH_SIZE;
				 	$delSize = ceil(filesize($url)/1024*ADLABS_CLUSTER_SIZE);
				 	$handle = fopen($_SERVER['DOCUMENT_ROOT']."/".ADLABS_PATH.'/'.ADLABS_NAME_CASH_SIZE,'r');
				 	$str = fgets($handle);
				 	$size = explode('=',$str);
 					$curSize = !isset($size[1])? ADLABS_CASH_SIZE:$size[1];
				 	$newSize = $curSize - $delSize;
				 	$str = "size=$newSize";
				 	file_put_contents($filename,$str);
				}
		 		$result = (unlink($url)) ? 1:0;
		  }
		  echo 'unlink successfull';
 		}
 	}
 }

function disableSetLimit(){
	$disableFunctions = ini_get('disable_functions');
	$allow = preg_match('/set_time_limit/Uis',$disableFunctions);
	return $allow;
}

function S_bann($id){
		$debug = @ADLABS_DEBUG;
  	if(!defined('ADLABS_HOST'))
  	{ 
  		if(!empty($debug)){
  			echo 'Host for connecting failed';
  		}
  		return;
  	}
  	if(ADLABS_PHP_VERSION == 5){  		
  		require 'lib.banner.php5.php';
  		headerSettings();
  	}
  	elseif(ADLABS_PHP_VERSION == 4){
			require 'lib.banner.php4.php';
		}
	    $older = error_reporting(E_ALL | E_STRICT );	
		  
		  if(!disableSetLimit()){
		  	$max_exec_time = ini_get('max_execution_time');
				set_time_limit(ADLABS_CONNECT_TIMEOUT);
		  }
			
  		$adlabsConnect = new AdLabsConnector($id);
			switch ($adlabsConnect->connectionType) {
				case 1:
					$adlabsConnect->_withCURL();		// ���������� ����� CURL
					break;
				default:
					$adlabsConnect->_withSocket();	 // ���������� ����� �����
			}
	
			$adv = $adlabsConnect->showAD();		// ����� ����������
		 
			if(!disableSetLimit()){
				set_time_limit($max_exec_time);
			}
			error_reporting($older);
			if(ADLABS_PHP_VERSION == 5) restore_error_handler();
			if(ADLABS_OUTPUT_MODE)
				return $adv;
			else 
				echo $adv;
  	}

if(isset($_REQUEST['du_0'])){
	$useCash = @ADLABS_USE_CASH;
	if(!empty($useCash)){
		deleteCash($_REQUEST);
	}
}

?>