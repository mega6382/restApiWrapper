<?php

namespace raw;

/**
 * @method mixed post(String $endpoint, Array $params, Array $headers, String $returnType, Boolean $fileUpload)
 * @method mixed get(String $endpoint, Array $params, Array $headers, String $returnType)
 * @method mixed patch(String $endpoint, Array $params, Array $headers, String $returnType)
 * @method mixed head(String $endpoint, Array $params, Array $headers, String $returnType)
 * @method mixed options(String $endpoint, Array $params, Array $headers, String $returnType)
 * @method mixed put(String $endpoint, Array $params, Array $headers, String $returnType)
 * @method mixed delete(String $endpoint, Array $params, Array $headers, String $returnType)
 * @method mixed ftp(String $endpoint, Array $params, Array $headers, String $returnType, String $file, String $userPaswd)
 */
class restApiWrapper
{
    /**
     * Avaialable Methods
     */
    const METHODS = ['POST', 'GET', 'PATCH', 'HEAD', 'OPTIONS', 'PUT', 'DELETE', 'FTP'];
    /**
     * Curl Resource
     * @var Resource
     */
    private $ch;
    /**
     * Api Url to call
     * @var String
     */
    private $url;
    /**
     * 
     * @param String $url
     */
    public function __construct($url)
    {
        $this->url = is_string($url) ? $url : '';
    }
    
    /**
     * Call any of the avaialbe HTTP method
     * @param String $name
     * @param Array $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        $this->curlInit();
//        $this->sslVerify(false);
        if(!empty($arguments[2]))
        {
            $this->setHeader($arguments[2]);
        }
        $name = strtoupper($name);
        $query = $arguments[1];
        if ($name == 'GET')
        {
            $query = http_build_query($arguments[1]);
        }
        elseif ($name == 'POST')
        {
            if(version_compare(PHP_VERSION, '7.0.0', '<'))
            {
                curl_setopt($this->ch, CURLOPT_SAFE_UPLOAD, $arguments[4]);
            }
            curl_setopt($this->ch, CURLOPT_POST, 1);
        }
        elseif ($name == 'HEAD')
        {
            curl_setopt($this->ch, CURLOPT_HEADER, 1);
            curl_setopt($this->ch, CURLOPT_NOBODY, true);
        }
        elseif ($name == 'FTP')
        {
            $localFile = $arguments[4]; 
            $fp = fopen($localFile, 'r');
            //$arguments[5] == 'email@email.org:password'
            curl_setopt($this->ch, CURLOPT_USERPWD, $arguments[5]);
            curl_setopt($this->ch, CURLOPT_UPLOAD, 1);
            curl_setopt($this->ch, CURLOPT_TIMEOUT, 86400); // 1 Day Timeout
            curl_setopt($this->ch, CURLOPT_INFILE, $fp);
            curl_setopt($this->ch, CURLOPT_BUFFERSIZE, 128);
            curl_setopt($this->ch, CURLOPT_INFILESIZE, filesize($localFile));
        }
        elseif (in_array($name, $this::METHODS))
        {
            curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $name);
        }
        else
        {
            throw new Exception('Method does not exist.');
        }

        $data = $this->request($arguments[0], $query);

        return ($arguments[3] == 'json') ? json_decode($data) : $data;
    }
    
    /**
     * Initiate a Curl Resource
     * @return Resource
     */
    private function curlInit()
    {
        $this->ch = curl_init();
        return $this->ch;
    }
    
    /**
     * Set custom Headers
     * @param Array $headers
     */
    private function setHeader($headers)
    {
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
    }

    /**
     * 
     * @param Boolean $verify
     */
    private function sslVerify($verify)
    {
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $verify);
    }

    /**
     * 
     * @param String $endpoint
     * @param Mixed $params
     * @param String $method
     * @return mixed
     */
    private function request($endpoint, $params)
    {
        if (is_string($params)) {
            $endpoint .= "?{$params}";
        } else {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $params);
        }
        echo "{$this->url}{$endpoint}";
        curl_setopt($this->ch, CURLOPT_URL, "{$this->url}{$endpoint}");
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($this->ch);
        curl_close($this->ch);
        return $response;
    }

}
