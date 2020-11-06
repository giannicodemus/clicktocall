<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PAMI\Client\Impl\ClientImpl as PamiClient;
use PAMI\Message\Action\OriginateAction;
use PAMI\Message\Action\LogoffAction;

class ClicktocallController extends Controller
{
    //
    public function ctc($numeroA, $numeroB){

        // Configuramos a conexao com o manager do Asterisk
        $pamiClientOptions = array(
            'host' => '127.0.0.1',
            'scheme' => 'tcp://',
            'port' => '5038',
            'username' => 'tutorialclicktocall',
            'secret' => 'tutorialclicktocall',
            'connect_timeout' => 60000,
            'read_timeout' => 60000
        );

        // Instancia a conexao
        $conexao = new PamiClient($pamiClientOptions);
        $conexao->open();

        // Configura Originate
        $action = new OriginateAction('Local/'.$numeroA.'@pabx');
        $action->setContext('pabx');
        $action->setExtension($numeroB);
        $action->setPriority('1');
        $action->setAsync(true);

        //Envia
        $conexao->send($action);

        // Mandar o tchau pro asterisk =)
        $action2 = new LogoffAction;
        $conexao->send($action2);

        // Fecha
        $conexao->close();


        return "Ligando para ".$numeroA."  e encaminhando para ".$numeroB."<br /><br />";
    }
}
