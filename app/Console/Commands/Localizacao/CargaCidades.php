<?php

namespace App\Console\Commands\Localizacao;

use App\Actions\Localizacao\Cidade\ImportaCidades;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:carga-cidades {--uf=}')]
#[Description('Executa carga de cidades por UF')]
class CargaCidades extends Command
{
    public function __construct(
        private readonly ImportaCidades $importaCidades,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $uf = $this->option('uf');
        if (is_null($uf)) {
            $this->alert('O UF do estado é obrigatorio. Utilize --uf=<uf>');

            return 1;
        }

        try {
            $this->importaCidades->executa($uf);
            $this->info('Cidades importadas com sucesso!');

            return 0;
        } catch (\Throwable $th) {
            $this->error($th->getMessage());

            return 1;
        }
    }
}
