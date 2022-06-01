<?php

namespace Alura\Leilao\Model;

use DomainException;

class Leilao
{
    private array $lances;
    private string $descricao;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance): void
    {
        if (!empty($this->lances) && $this->ehDoUltimoUsuario($lance)) {
            throw new DomainException('Usuário não pode propor 2 lances consecutivos');
        }

        $totalLancesUsuario = $this->quantidadeLancesUsuario($lance->getUsuario());

        if ($totalLancesUsuario >= 5) {
            throw new DomainException('Usuário não pode propor mais de 5 lances');
        }

        $this->lances[] = $lance;
    }

    public function getLances(): array
    {
        return $this->lances;
    }

    private function ehDoUltimoUsuario(Lance $lance): bool
    {
        $ultimoLance = $this->lances[array_key_last($this->lances)];

        return $lance->getUsuario() == $ultimoLance->getUsuario();
    }

    public function quantidadeLancesUsuario(Usuario $usuario): int
    {
        return array_reduce(
            $this->lances,
            function (int $totalAcumulado, Lance $lanceAtual) use ($usuario) {
                if ($lanceAtual->getUsuario() == $usuario) {
                    return $totalAcumulado + 1;
                }
                return $totalAcumulado;
            }, 0);
    }
}
