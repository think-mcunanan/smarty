<?php
/*
 * SOAP component
 *
 * @author      RosSoft
 * @version     0.1
 * @license		MIT
 *
 */

define('LOG_REQUEST', 1);
define('LOG_ANSWER', 2);

class SoapComponent extends CakeObject
{
	var $controller  = null;
    var $error       = null;
    var $dbgstr      = null;
    var $rawrequest  = null;
    var $rawresponse = null;

	function startup(&$controller)
	{
		$this->controller=& $controller;
	}

	function client($url, $func, $param=array(''), $useProxy = true, $doDebug = false, $loglevel = 0, $timeout = 30)
	{
		App::import('vendor', 'nusoap');

		//you have to rename all the instances of soapclient to soap_client in the file nusoap.php (PHP5 compat)

		$client = new soap_client($url, $useProxy, false, false, false, false, 0, $timeout);

        $client->setGlobalDebugLevel(DEBUG);

        $client->decodeUTF8(false);

        // we use the WSDL and make a proxy
        if($useProxy)
        {
           $proxy = $client->getProxy();
           $proxy->decodeUTF8(false);

           $response = $proxy->{$func}($param);

           if($proxy->fault || $client->fault)
           {
              $this->error = $response;
           }
           else
           {
              $this->error = $proxy->getError();
           }

           if($doDebug)
              $this->dbgstr = $proxy->debug_str;

           if( ($loglevel & LOG_REQUEST) > 0)
             $this->rawrequest = $proxy->request;

           if( ($loglevel & LOG_ANSWER) > 0)
              $this->rawresponse = $proxy->response;
        }
        else
        {
           $response = $client->call($func, $param, "");

           if($client->fault)
           {
              $this->error = $response;
           }
           else
           {
              $this->error = $client->getError();
           }

           if($doDebug)
              $this->dbgstr = $client->debug_str;

           if( ($loglevel & LOG_REQUEST) > 0)
              $this->rawrequest = $client->request;

           if( ($loglevel & LOG_ANSWER) > 0)
               $this->rawresponse = $client->response;
        }

        if($this->error)
           return null;

		return $response;
	}

	/**
	 * Little fix
	 * In RC5, vendor() uses require, not require_once
	 */
	function vendor_once($arg)
	{
       	if(file_exists(APP.'vendors'.DS.$arg.'.php'))
        {
            require_once(APP.'vendors'.DS.$arg.'.php');
        }
        else
        {
            require_once(VENDORS.$arg.'.php');
        }
	}

	function service($name=false)
	{
		global $api;
		global $controller;

		//clean the output
		ob_end_clean();
		$this->controller->autoRender=false;

		//include the service
		$controller=$this->controller;
		$name=$this->controller->action;
		$servicefile=Inflector::underscore($this->controller->name) .DS . $name . '.php';
		require_once CONTROLLERS . 'soap_services' . DS . $servicefile;


		//create a soap server
		vendor('/nusoap/nusoap');
		$server=new soap_server();
		//$server->wsdl->addComplexType(<br />

		$wsdl="{$name}wsdl";
		$urn="urn:$wsdl";
		$server->configureWSDL($wsdl, $urn);
		//Register the method to expose
		$server->register(	$name,$api['input'],$api['output'],
						  	$urn, "$urn#$name",
					    	'rpc',		// style
    						'encoded', // use
    						$api['doc']);	// documentation

		$data = file_get_contents('php://input');
		$server->service($data);
	}
}
?>