<?php
class phpException extends exception {
    public function __construct($errno, $errstr, $errfile, $errline) {
        parent::__construct();
        $this->code = $errno;
        $this->message = $errstr;
        $this->file = $errfile;
        $this->line = $errline;
    }
}

function err2exc($errno, $errstr, $errfile, $errline) {
    throw new phpException($errno, $errstr, $errfile, $errline);
}

class AdLabsConnector{
	var $fh;
	var $host;
	var $url;
	var $debug;
	var $connectionType;
	var $connectTimeout;
	var $data;
	var $result;
	var $useCash;
	var $path;
	var $filename;
	var $script;
	var $version;
	var $isDefBanner;
	var $nameCashSize;
	var $clusterSize;
	var $cashSize;
	var $cashStrict;
	
	public function __construct($id){
		$this->host = defined('ADLABS_HOST')? ADLABS_HOST:'';
		$this->debug = defined('ADLABS_DEBUG')? ADLABS_DEBUG:0;
		$this->connectionType = defined('ADLABS_CONNECTION_TYPE')? ADLABS_CONNECTION_TYPE:0;
		$this->connectTimeout = defined('ADLABS_CONNECT_TIMEOUT')? ADLABS_CONNECT_TIMEOUT:2;
		$this->useCash = defined('ADLABS_USE_CASH')? ADLABS_USE_CASH:0;
		$ref = isset($_SERVER['HTTP_REFERER'])? urlencode($_SERVER['HTTP_REFERER']):'';
		$this->url = urlencode($_SERVER['REQUEST_URI']);
		$ip = $_SERVER['REMOTE_ADDR'];
		$agent = isset($_SERVER['HTTP_USER_AGENT'])? urlencode($_SERVER['HTTP_USER_AGENT']):'';
		$rhost = $_SERVER['HTTP_HOST'];		
		$this->data = "id=$id&ref=$ref&url=$this->url&ip=$ip&agent=$agent&host=$rhost";		
		$this->result = '';
		$this->filename = md5($this->url);		
		$this->path = defined('ADLABS_PATH')? $_SERVER['DOCUMENT_ROOT']."/".ADLABS_PATH.'/'.$this->filename:'';
		$this->script = ADLABS_SCRIPT;
		$this->cashStrict = defined('ADLABS_STRICT_CASH_SIZE')? ADLABS_STRICT_CASH_SIZE:0;
		$this->nameCashSize = ADLABS_NAME_CASH_SIZE;
		$this->clusterSize = defined('ADLABS_CLUSTER_SIZE')? ADLABS_CLUSTER_SIZE:4;
		$this->cashSize = defined('ADLABS_CASH_SIZE')? ADLABS_CASH_SIZE:10240;
	}
		
 private function readFromCash(){
 	try{
 		if(file_exists($this->path)){
	 		$handle = fopen($this->path,'r');
	 		if(!$handle){
	 			throw new Exception("Can't read file");
	 		}
	 		$this->result = fread($handle,filesize($this->path));
	 		fclose($handle);
	 	}
 	}
 	catch(Exception $e){
 		if($this->debug) echo $e->getMessage();
 	}
 }  
 
 private function writeToCash(){
 	try{
	 	$permission = $this->getPermissionForWriteCash();
	 	if(preg_match('/^<i><\/i>.+/',trim($this->result)) && $permission){
	 		$handle = fopen($this->path,'w');
 			fwrite($handle,$this->result);
 			fclose($handle);
 			$this->setCurCashSize(); 			
	 	} 		
 	}
 catch(Exception $e){
 		if($this->debug) echo $e->getMessage();
 	}
 }
 
 private function getCurCashSize(){
 	try{
 		$handle = fopen($_SERVER['DOCUMENT_ROOT']."/".ADLABS_PATH.'/'.$this->nameCashSize,'r');
 		if(!$handle){
	 		throw new Exception("Can't get cash size");
	 	}
	 	$str = fgets($handle);
	 	$size = $this->getSizeFromStr($str);
	 	return $size;
 	}
 	catch(Exception $e){
 		if($this->debug) echo $e->getMessage();
 	}
 }
 
 private function getSizeFromStr($str){
 	$size = explode('=',$str);
 	$size = !isset($size[1])? $this->getAllowCashSize():$size[1];
 	return $size;
 }
 
 private function getAllowCashSize(){
 	$allowSize = $this->cashSize;
 	return $allowSize;
 } 
 
 private function getPermissionForWriteCash(){
 	if(!$this->cashStrict) return 1;
 	$allowSize = $this->getAllowCashSize();
 	$curSize = $this->getCurCashSize();
 	$permission = ($allowSize - $curSize > 0)? 1:0;
 	return $permission;
 }
 
 private function getRealsaveSize(){
 	$size = ceil(filesize($this->path)/1024*$this->clusterSize);
 	return $size;
 }
 
 private function setCurCashSize(){
 	try{
	 	$filename = $_SERVER['DOCUMENT_ROOT']."/".ADLABS_PATH.'/'.$this->nameCashSize;
	 	$saveSize = $this->getRealSaveSize();
	 	$curSize = $this->getCurCashSize();
	 	$newSize = $curSize + $saveSize;
	 	$str = "size=$newSize";
	 	file_put_contents($filename,$str);
 	}
 	catch(Exception $e){
 		if($this->debug) echo $e->getMessage();
 	}
 }
 
 public function showAD(){
 	if(preg_match('/^<i><\/i>/',trim($this->result))){
		return preg_replace('/<i><\/i>/','',$this->result);
 	} 	
 } 
  
 public function _withSocket(){
 	
	 	if($this->useCash){
	 		$this->readFromCash();
	 	}
	 			
	 	if(empty($this->result)){
		 	if($this->connectSOCKET()){
			 	if($this->useCash && !empty($this->result) && preg_match('/^<i><\/i>.+/',trim($this->result)) && empty($this->isDefBanner)){
			 		$this->writeToCash();
			 	}
		 	}
	 	}
 }

 public function _withCURL(){
 	 	if($this->useCash){
	 		$this->readFromCash();
	 	}
	 	if(empty($this->result)){
		 	if($this->connectCURL()){
			 	if($this->useCash && !empty($this->result) && preg_match('/^<i><\/i>.+/',trim($this->result)) && empty($this->isDefBanner)){
			 		$this->writeToCash();
			 	}
		 	}
	 	}
 } 
  
 private function connectSOCKET(){ 	
   try
     { 
     $this->fh = fsockopen($this->host,80, &$errno, &$errstr, $this->connectTimeout);
       stream_set_timeout($this->fh, $this->connectTimeout);
          if(!$this->fh)
            {
              throw new Exception("Can't connect via socket");
            }
		
		$len=strlen($this->data);

$msg =<<<EOT
POST /$this->script HTTP/1.0
Host: $this->host
Content-Length: $len
Content-Type: application/x-www-form-urlencoded charset=utf-8
Connection: Close\r\n\r\n
EOT;

	  if(!fputs($this->fh,$msg.$this->data)) {
			fclose($this->fh);
			throw new Exception("Can't put data via socket");
		}

		while (!feof($this->fh)){
			$tmp = fgets($this->fh,128);
		  if($tmp == "\r\n"){
		  	break;
 			}
 			elseif(preg_match('/404.+Not.+Found/Uis',$tmp)){
	 				throw new Exception("Can't get data via socket");
 			}
		}
		while (!feof($this->fh)) $this->result.=fread($this->fh,32000);
    if(preg_match('/<span><\/span>/Uis',$this->result)){
			$this->isDefBanner = 1;
		}
		return true;
	}
	catch (Exception $e) {
		   if($this->debug) echo 'ERROR in connectSOCKET: ',  $e->getMessage(), "\n";
		   return false;
	}
}
   
 private function connectCURL(){
       try
          {
          	ini_set('allow_url_fopen', 1);
          	$ch = curl_init();
          	if ($ch) {
                curl_setopt($ch, CURLOPT_URL, 'http://'.$this->host.'/'.$this->script);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
								$this->result = curl_exec($ch);
								if(preg_match('/404.+Not.+Found/Uis',$this->result)){
									throw new Exception("The requested URL was not found on this server");
								}
								if(preg_match('/<span><\/span>/Uis',$this->result)){
									$this->isDefBanner = 1;
								}
                if ($this->result) {
                    return true;
                }
		          	else
		            {
		             throw new Exception("Can't connect via CURL");
		            }
                curl_close($ch);
            }          	
          }
          catch (Exception $e){
            if($this->debug) echo 'ERROR in connectCURL: ',  $e->getMessage(), "\n";
            return false;
          }
   }

 private function footerSettings(){
 	
 }
}

function headerSettings(){
	 	set_error_handler('err2exc', E_ALL);
 }
?>