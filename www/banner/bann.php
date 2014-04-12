<?php
/*
 * Copyright (c) 2009 adlabs.com.ua
 * Authors: dev@adlabs.com.ua 
 */

/*
 * ADLABS_DEBUG - константа, определ€юща€ рещим отладки
 * 0 - отладка отключена. —ообщени€ об ошибках не вывод€тс€ на экран
 * 1 - отладка включена
 */
define('ADLABS_DEBUG',0);

/*
 * ADLABS_CONNECTION_TYPE - определ€ет тип соединени€
 * 0 - соединение через Socket. –екомендуетс€ дл€ PHP4 и дл€ PHP5 c отключенным CURL. ¬ключЄн по умолчанию
 * 1 - соединение через CURL. 
 */
define('ADLABS_CONNECTION_TYPE',1);	

/*
 * ADLABS_CONNECT_TIMEOUT - врем€ таймаута соединенни€, сек 
 */
define('ADLABS_CONNECT_TIMEOUT',2);

/*
 * ADLABS_USE_CASH - констаната, отвечающа€ за использование кэша
 * 0 - каждый показ объ€влени€ происходит через соединение с addbanner.biz
 * 1 - ссылки транслируютс€ из локальных файлов
 */
define('ADLABS_USE_CASH',0);

/*
 * ADLABS_PATH - если используетс€ кэш, то ADLABS_PATH - им€ каталога, в котором хран€тс€ файлы кэша
 * дл€ работы с кэшем у каталога должен быть уровень доступа 777. ќн должен находитьс€ в корне сайта  
 */
define('ADLABS_PATH','efd6410271c5099f5675dd919805bbee');	  

/*
 * ADLABS_HOST - доменное им€ сервиса, транслирующего объ€влени€
 * ≈сли обращение через DNS затруднено, можно использовать IP-адрес 89.249.22.205
 */
define('ADLABS_HOST','addbanner.biz');

/*
 * ADLABS_SCRIPT - константа, определ€юща€ им€ скрипта, который отвечает за показ объ€влений
 */
define('ADLABS_SCRIPT','get.php');		

/*
 * ADLABS_OUTPUT_MODE
 * 0 - объ€вление непосредственно выводитс€ на экран. ”становлено по умолчанию
 * 1 - скрипт возвращает объ€вление. «а вывод его на экран отвечает скрипт клиента 
 */
define('ADLABS_OUTPUT_MODE',1);

/*
 * ADLABS_PHP_VERSION
 * 5 - работает под php5. ”становлено по умолчанию
 * 4 - работает под php4 
 */
define('ADLABS_PHP_VERSION',5);

/*
 * ADLABS_STRICT_CASH_SIZE
 * 1 - ограничивать размер кэша
 * 0 - не ограничивать размер кэша. ”становлено по умолчанию
 */
define('ADLABS_STRICT_CASH_SIZE',0);

/*
 * ADLABS_CLUSTER_SIZE
 * если установлено ограничение размера кэша,
 * необходимо указать размер кластера сервера в KB
 */
define('ADLABS_CLUSTER_SIZE',4);

/*
 * ADLABS_CASH_SIZE
 * если установлено ограничение размера кэша,
 * необходимо указать размер кэша в KB
 */
define('ADLABS_CASH_SIZE',10240);

/*
 * ADLABS_NAME_CASH_SIZE
 * если установлено ограничение размера кэша,
 * необходимо указать им€ файла, где будет хранитьс€ текущий размер
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
					$adlabsConnect->_withCURL();		// соединение через CURL
					break;
				default:
					$adlabsConnect->_withSocket();	 // соединение через сокет
			}
	
			$adv = $adlabsConnect->showAD();		// показ объ€влени€
		 
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