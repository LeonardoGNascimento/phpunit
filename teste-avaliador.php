<?php

use Alura\Leilao\Service\Avaliador;
use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;

require_once 'vendor/autoload.php';

$leilao = new Leilao('Fiat');

$maria = new Usuario('Maria');
$joao = new Usuario('Joao');

$leilao->recebeLance(new Lance($joao, 2000));
$leilao->recebeLance(new Lance($maria, 2500));

$leiloeiro = new Avaliador();
$leiloeiro->avalia($leilao);

$maiorValor = $leiloeiro->getMaiorValor();

$valorEsperado = 2500;

if ($maiorValor == $valorEsperado) {
    echo 'TESTE OK';
} else {
    echo 'TESTE FALHOU';
}