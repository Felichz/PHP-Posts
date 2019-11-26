<?php namespace App\Commands;

use App\Conf\Conf;
use App\Model\Messages;
use App\Services\DependencyInjection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class SendMail extends Command
{
    protected static $defaultName = 'send-mail';

    protected function configure()
    {
        $this
            ->setDescription('Enviar Email de la BD.')
            ->setHelp('Enviar un Email previamente guardado en la Base de Datos, con id del email opcional.')
            ->addArgument('id', InputArgument::OPTIONAL, 'Email ID')
            ->addArgument('force', InputArgument::OPTIONAL, 'Forzar envio aunque el email ya figure como enviado')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $eloquent = new \App\Model\BDConection;
        $eloquent->conectar();

        $messages = new Messages();

        $sent = 0;

        if ( $input->getArgument('id') ) {
            $message = $messages->find( $input->getArgument('id') );

            if ( $message ) {
                if ( $message->sent == 1 ) {
                    $sent = 1;
                }
            }
        }
        else {
            $message = $messages->where('sent', 0)->first();

            if ( !$message ) {
                $sent = 1;
            }
        }

        if ( $input->getArgument('force') ) {
            if ( $input->getArgument('force') == 'force' ) {
                $sent = 0;
            }
        }

        if ( $sent == 0 ) {
            if ( $message ) {

                $CONF = Conf::getConf();
                $mailer = DependencyInjection::obtenerElemento('Mailer');
        
                $mailer->sendMail([
                    'to' => $CONF['EMAIL']['APP_EMAIL'],
                    'from' => $message->email,
                    'replyTo' => $message->email,
                    'subject' => 'Formulario de contacto PHPAvanzado',
                    'body' => "Nombre: $message->name <br><br> Mensaje: <br> <pre>$message->message</pre>"
                ]);
        
                $message->sent = true;
                $message->save();

                $output->writeln('Enviado');
            }
            else {
                $output->writeln('Email no encontrado');
            }
        }
        else {
            $output->writeln('Email o Emails ya enviados');
        }
    }
}