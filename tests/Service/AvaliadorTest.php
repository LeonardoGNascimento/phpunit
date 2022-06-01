<?php

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    protected Avaliador $leiloeiro;

    protected function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }

    public function testLeilaoVazioNaoPodeSerAvaliado()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Não é possivel avaliar leilão vazio');
        $leilao = new Leilao('Fusca');
        $this->leiloeiro->avalia($leilao);
    }
    
    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorDeveEncontrarMaiorValor(Leilao $leilao): void
    {
        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        $this->assertEquals(2500, $maiorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorDeveEncontrarMenorValor(Leilao $leilao): void
    {
        $this->leiloeiro->avalia($leilao);

        $menorValor = $this->leiloeiro->getMenorValor();

        $this->assertEquals(1700, $menorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorDeveBuscar3MaioresValores(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maiores = $this->leiloeiro->getMaioresLances();
        $this->assertCount(3, $maiores);
        $this->assertEquals(2500, $maiores[0]->getValor());
        $this->assertEquals(2000, $maiores[1]->getValor());
        $this->assertEquals(1700, $maiores[2]->getValor());
    }

    public function leilaoEmOrdemCrescente(): array
    {
        $leilao = new Leilao('Fiat');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1700));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        return [
            'ordem-crescente' => [$leilao]]
            ;
    }

    public function leilaoEmOrdemDecrescente(): array
    {
        $leilao = new Leilao('Fiat');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($ana, 1700));

        return [
            'ordem-decrescente' => [$leilao]
        ];
    }

    public function leilaoEmOrdemAleatoria(): array
    {
        $leilao = new Leilao('Fiat');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($ana, 1700));

        return [
        'ordem-aleatoria' => [$leilao]
        ];
    }
}
