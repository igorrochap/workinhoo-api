<?php

namespace App\Console\Commands\Localizacao;

use App\Actions\Localizacao\Bairro\ImportaBairros;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:carga-bairros {--uf=}')]
#[Description('Executa carga de bairros por UF')]
class CargaBairros extends Command
{
    public function __construct(
        private readonly ImportaBairros $importaBairros,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $uf = $this->option('uf');
        if (is_null($uf)) {
            $this->alert('O UF do estado é obrigatorio. Utilize --uf=<uf>');

            return 1;
        }

        try {
            $this->importaBairros->executa($uf);
            $this->info('Bairros importados com sucesso!');

            return 0;
        } catch (\Throwable $th) {
            $this->error($th->getMessage());

            return 1;
        }
    }
}
