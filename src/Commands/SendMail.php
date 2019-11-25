<?php namespace App\Commands;

use App\Conf\Conf;
use App\Model\Messages;
use App\Services\DependencyInjection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendMail extends Command
{
    protected static $defaultName = 'send-mail';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $eloquent = new \App\Model\BDConection;
        $eloquent->conectar();

        $messages = new Messages();
        $pendingMessage = $messages->where('sent', 0)->first();

        if ( $pendingMessage ) {
            $CONF = Conf::getConf();
            $mailer = DependencyInjection::obtenerElemento('Mailer');
    
            $mailer->sendMail([
                'to' => $CONF['EMAIL']['APP_EMAIL'],
                'from' => $pendingMessage->email,
                'replyTo' => $pendingMessage->email,
                'subject' => 'Formulario de contacto PHPAvanzado',
                'body' => "Nombre: $pendingMessage->name <br><br> Mensaje: <br> <pre>$pendingMessage->message</pre>"
            ]);
    
            $pendingMessage->sent = true;
            $pendingMessage->save();

            $output->writeln('Enviado');
        }
        else {
            $output->writeln('No hay mensajes pendientes para enviar');
        }
    }
}