# restApiWrapper
A Curl Wrapper for RESTful APIs

To Install use composer:

    composer require mega6382/rest-api-wrapper

## Quick Examples

#### Create a restApiWrapper instance

````PHP
$url = "https://example.com/";
$raw = new raw\restApiWrapper($url);
````
#### Send a POST Request, with Params, Custom Headers and Return type Json Object

````PHP
$request = $raw->post(
            'ufkbi1uf', // Endpoint to the API
            [ //POST Params
                'param1' => 'abc',
                'param2' => 'def',
                'param3' => 'ghi',
            ],
            [ //Request Headers 
                'Content-Type: application/xml',
                'Connection: Keep-Alive'
            ],
            'json' //Return type
        );
var_dump($request);
````
#### Send a GET Request, without any Params or Custom Headers and return String

````PHP
$request = $raw->get('ufkbi1uf', [], [], '');
var_dump($request);
````

#### Send a OPTIONS Request, with Params, but no Custom headers and return will be a string

````PHP
$request = $raw->options('ufkbi1uf', ['name' => 'basil'], [], '');
var_dump($request);
````
