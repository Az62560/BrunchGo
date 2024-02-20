<?php
namespace App\Classe;

use Mailjet\Resources;
use Mailjet\Client;


Class Mail
{
    private $api_key = 'e9825437f180fbfab2a630eab2a20485';
    private $api_key_secret= '813936c531ec7421ac93e56492decac1';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "justincornu@gmail.com",
                        'Name' => "Brunch Go"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 5699890,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    "Variables" => [
                        'content' => $content,                       
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
        // dd($response->getData());
    }
}
?>