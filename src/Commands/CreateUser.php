<?php namespace App\Commands;

use App\Model\BDConection;
use App\Model\BDUsers;
use App\Services\Validation;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUser extends Command
{
    protected static $defaultName = 'create-user';

    protected $defaultPassword;

    public function __construct()
    {
        $this->defaultPassword = 'ProyectoPHP2019';
        $this->BDUsers = new BDUsers();
        $this->BDConection = new BDConection;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Crea un nuevo usuario en la app.')
            ->setHelp("Crea un nuevo usuario en la app, segun un email y un contraseña opcional, si no se especifica una contraseña, se genera una default. Contraseña default actual: \"{$this->defaultPassword}\"")
            ->addArgument('email', InputArgument::REQUIRED, 'Email del usuario')
            ->addArgument('password', InputArgument::OPTIONAL, 'Contraseña del usuario')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Creando usuario');

        $validation = new Validation;
        $this->BDConection->conectar();

        $email = $input->getArgument('email');

        if ( $input->getArgument('password') ) {
            $password = $input->getArgument('password');
        }
        else {
            $password = $this->defaultPassword;
            $output->writeln("Usando contraseña default: \"{$this->defaultPassword}\"");
        }

        $output->writeln('---------------');

        try {
            $validation->validarSignup( $email, $password );

            if ( $validation->errorMessage ) {
                throw new Exception( $validation->errorMessage );
            }

            $this->BDUsers->registrarUsuario( $email, $password );
            $output->writeln('Usuario registrado con éxito.');
        }
        catch (Exception $e) {
            $output->writeln( 'Error: ' . $e->getMessage() );
        }
    }
}