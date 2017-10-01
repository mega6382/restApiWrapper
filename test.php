<?php
include 'index.php';

$url = "https://requestb.in/";

$raw = new restApiWrapper($url);

//Sending a POST Request, with Params, custom headers and return type Json decoded Object
$request1 = $raw->post(
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

var_dump($request1);

//Sending a GET Request, without any params or custom headers and return will be a string
$request2 = $raw->get('ufkbi1uf', [], [], '');

var_dump($request2);


//Sending a OPTIONS Request, with params, but no custom headers and return will be a string
$request3 = $raw->options('ufkbi1uf', ['name' => 'basil'], [], '');

var_dump($request3);
