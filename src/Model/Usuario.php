<?php

namespace Alura\Leilao\Model;

class Usuario extends \Alura\Leilao\Model\Lance
{
    /** @var string */
    private $nome;

    public function __construct(string $nome)
    {
        $this->nome = $nome;
    }

    public function getNome(): string
    {
        return $this->nome;
    }
}
