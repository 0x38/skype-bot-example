<?php
require 'vendor/autoload.php';
use GuzzleHttp\Client;

$clientId = '';
$clientSecret = '';



$postFields = [
    'grant_type' => 'client_credentials',
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'scope' => 'https://graph.microsoft.com/.default'
];


$client = new Client();
$response = $client->post(
    'https://login.microsoftonline.com/common/oauth2/v2.0/token',
    array(
        'body' => $postFields
    )
);

$authorizationData = json_decode($response->getBody()->getContents(), true);
print_r($authorizationData);




try {
    $client = new Client();
    $request = $client->createRequest('POST', 'https://skype.botframework.com/v3/conversations', ['json' => [
        "bot" => [
            "id" => $clientId,
            "name" => "Amadeus First Kiev Bot"
        ],
        "isGroup"=> false,
        "members"=> [
            [
                "id"=> "anton.chernik",
                "name"=> "Anton Chernik"
            ]
        ],
        "topicName"=> "News Alert"
    ]]);
    $request->setHeader('Authorization', 'Bearer '.$authorizationData['access_token']);
    $response = $client->send($request);
    $conversations = json_decode($response->getBody()->getContents(), true);
    print_r($conversations);

    $client = new Client();
    $request = $client->createRequest('POST', 'https://skype.botframework.com/v3/conversations/'.$conversations['id'].'/activities', ['json' => $conversations]);
    $request->setHeader('Authorization', 'Bearer '.$authorizationData['access_token']);
    $response = $client->send($request);
    $conversationList = json_decode($response->getBody()->getContents(), true);
    print_r($conversationList);

} catch (\Exception $ex) {
    print_r($ex->getMessage());
}
print_r($response->getBody()->getContents());