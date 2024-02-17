<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Customers;
use App\Models\Device;
use Dflydev\DotAccessData\Util;
use Illuminate\Http\Request;

class Events extends Controller
{
    public function index()
    {
        $reponseJson = file_get_contents('php://input');


        $reponseArray = json_decode($reponseJson, true);
        $session = Device::where('session', $reponseArray['data']['sessionId'])->first();

        // verifica se o serviço está em andamento

        $active = 1;
        if ($active && $reponseArray['data']['message']['from'] == "5511986123660@s.whatsapp.net") {
            $this->verifyService($reponseArray, $session);
        }

        // file_put_contents(Utils::createCode().".txt",$reponseJson);
    }

    public function teste()
    {
        $texto = file_get_contents('php://input');
        $reponseJson = file_get_contents('teste-recebe.txt');

        $reponseArray = json_decode($reponseJson, true);
        $session = Device::where('session', $reponseArray['data']['sessionId'])->first();



        // verifica se o serviço está em andamento
        $this->verifyService($reponseArray, $session);
    }

    public function verifyService($reponseArray, $session)
    {
        if (!$reponseArray['data']['message']['fromMe'] || !$reponseArray['data']['message']['fromGroup']) {

            $jid = $reponseArray['data']['message']['from'];

            $service = Chat::where('session_id',  $session->id)
                ->where('jid', $jid)
                ->where('active', 1)
                ->first();

            $customer = Customers::where('jid',  $jid)
                ->first();

            if (!$service) {
                echo "Novo o atendimento criando chat </br>";
                $service = new Chat();
                $service->jid = $jid;
                $service->session_id = $session->id;
                $service->service_id = Utils::createCode();
            }

            if (!$customer) {
                echo "usuário não existe , Criar usuário </br>";
                $customer = new Customers();
                $customer->jid = $jid;
                $customer->save();
                $text = "Olá Vimos que voçê não tem Cadastro, por favor Digite seu Nome";
                $service->await_answer = "name";
                $service->save();
                $this->sendMessagem($session->session, $customer->phone, $text);
                exit;
            } else {
                if ($service->await_answer != "init_chat") {
                    $text = "Olá " . $customer->name . " é bom ter voçê novamente aki! ";
                    $service->await_answer = "init_chat";
                    $service->save();
                    $this->sendMessagem($session->session, $customer->phone, $text);
                }
            }


            if ($service->await_answer == "name") {
                $customer->name = $reponseArray['data']['message']['text'];
                $customer->update();
                $text = "Por favor " . $customer->name . " Digite seu Cep";
                $service->await_answer = "cep";
                $service->update();
                $this->sendMessagem($session->session, $customer->phone, $text);
                exit;
            }

            if ($service->await_answer == "cep") {

                $cep = $reponseArray['data']['message']['text'];
                $cep = Utils::returnCep($cep);
                if ($cep) {
                    $customer->zipcode = $cep['cep'];
                    $customer->public_place = $cep['logradouro'];
                    $customer->neighborhood = $cep['bairro'];
                    $customer->city = $cep['localidade'];
                    $customer->state = $cep['uf'];
                    $customer->update();
                    $service->await_answer = "number";
                    $service->update();
                    $text = "Por Favor Digite o Número da residência";
                } else {
                    $service->await_answer = "cep";
                    $text = "Cep inválido Digite novamente!";
                }
                $this->sendMessagem($session->session, $customer->phone, $text);
                exit;
            }
            if ($service->await_answer == "number") {

                $customer->number = $reponseArray['data']['message']['text'];
                $customer->update();
                $location = $customer->location . " \n  O Endereço está Correto ? ";
                $options = [
                    "Sim",
                    "Não"
                ];
                $this->sendMessagewithOption($session->session, $customer->phone, $location, $options);

                $service->await_answer = "cep_confirmation";
                $service->update();
            }

            if ($service->await_answer == "cep_confirmation") {

                $response = $reponseArray['data']['message']['text'];

                switch ($response) {
                    case  "1";
                        $service->await_answer = "init_chat";
                        $service->update();
                        $text =  $customer->name . " \n  Seu cadastro foi Realizado \n com sucesso ";
                        $this->sendMessagem($session->session, $customer->phone, $text);
                        break;

                    case '2';
                        $service->await_answer = "cep";
                        $service->update();
                        $text =  $customer->name . " \n Por favor Digite seu cep Novamente.";
                        $this->sendMessagem($session->session, $customer->phone, $text);
                }
            }
        } else {
            'eu enviei ou é grupo';
        }
    }

    public function sendMessagem($session, $phone, $texto)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                                        "number": "' . $phone . '",
                                        "message": {
                                            "text": "' . $texto . '"
                                        },
                                        "delay": 3
                                    }',
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        echo $response;
    }

    public function sendMessagewithOption($session, $phone, $text, $options)
    {
        $curl = curl_init();

        $send = array(
            "number" => $phone,
            "message" => array(
                "text" => $text,
                "options" => $options,
            ),
            "delay" => 3
        );


        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($send),
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}
