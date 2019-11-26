<?php namespace App\Services;

class AsyncCommand
{
    public function __construct( array $CONF )
    {
        $this->CONF = $CONF;
    }

    public function ejecutarComando( string $comando )
    {
        $rutaLog = $this->CONF['PATH']['LOG'];

        if( $this->isWin() ) {
            $comando = 'start /B ' . $comando . ' > ' . $rutaLog . '/cmd-output.log"';
            pclose(popen($comando, 'r'));
        }
        else {
            $comando = '/usr/bin/nohup ' . $comando . ' >' . $rutaLog . '/shell-output.log 2>&1 &';
            shell_exec($comando);
        }
    }

    protected function isWin()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}