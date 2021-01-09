<?php


/**
 * Classe responsável por grupo de beneficiários
 */
class Beneficiarios
{
    private $_listBeneficiarios;
    private $_banco;
    private $_grupoBeneficiarios;

    /**
     * Inicia as variáveis e conexão com banco
     */
    function __construct()
    {
        $this->_listBeneficiarios = array();
        $this->_grupoBeneficiarios = array();
        $this->_banco = new Banco();
    }


    /**
     * Adicionar object Beneficiario a lista de beneficiarios 
     * 
     * @param $beneficiario <Beneficiario>
     * 
     * @return void
     */
    function addBeneficiario($beneficiario): void
    {
        $reg = $beneficiario->Plano->Registro;

        array_push($this->_grupoBeneficiarios, $beneficiario);
        $this->_setPrecoPlano();
    }

    /**
     * Obtém faixa etária do plano do beneficiário
     * 
     * @param $beneficiario <Beneficiario>
     * 
     * @return string 
     */
    function getFaixaPlanoBeneficiario($beneficiario): string
    {
        $idade = $beneficiario->Idade;
        
        if ( $idade <= 17) {
            return 'faixa1';
        } elseif ( $idade > 17 && $idade <= 40) {
            return "faixa2";            
        } else {
            return "faixa3";
        }

    }




    /**
     * Obtém o valor do plano do beneficiário
     * baseado no total de beneficiário do plano
     * e do plano propriamente dito
     * 
     * @param beneficiario <Beneficiario>
     * 
     * @param totalBeneficarios <int>
     * 
     * @return int
     * 
     */
    function getPrecoPlano($beneficiario, $totalBeneficiarios): int
    { 
        $faixaPlano = $this->getFaixaPlanoBeneficiario($beneficiario);

        $listPlanos = $this->_banco
           ->setTabela('precos')
           ->where(
           array(
               array("codigo" ,"==", $beneficiario->Plano->Codigo),
               array("minimo_vidas" ,"<=",$totalBeneficiarios)
           ))
           ->sort("minimo_vidas")
           ->search();


        $precoPlano;

        foreach ( $listPlanos as $plano) {

            if ($plano->minimo_vidas <= $totalBeneficiarios ) {
                $precoPlano = $plano->$faixaPlano; 
            }
        }

        return $precoPlano;
        
    }

    /**
     * Função que adiciona o valor do plano para o beneficiário
     * 
     * @return void
     */
    private function _setPrecoPlano(): void
    {
        foreach ( $this->_grupoBeneficiarios as $indexBeneficiario => $beneficiario) {
            $this->_grupoBeneficiarios[$indexBeneficiario]->Plano->Preco = $this->getPrecoPlano($beneficiario, count($this->_grupoBeneficiarios));
        }
    }


    /**
     * Total do valor a ser pago pelo grupo de beneficiários
     * 
     * @return float
     */
    function totalPagar(): float
    {
        $total = 0;


        foreach ( $this->_grupoBeneficiarios as $indexBeneficiario => $beneficiario) {
            $total+=$this->_grupoBeneficiarios[$indexBeneficiario]->Plano->Preco;
        }


        return (float) $total;
    }

    /**
     * Mostra uma tabela com os dados dos beneficiários.
     * nome, idade e preço do plano do beneficiário.
     * 
     * Esses dados tem que ser primeiro adicionados com o método addBeneficiario
     * 
     * @return void
     */

    function showBeneficiariosPlanos(): void
    {

        $linhaSeparacao = '';
        $tabela = array();



        foreach ($this->_grupoBeneficiarios as $beneficiario ) {
            
            $linha = formatarLinhaTabelaBeneficiario($beneficiario);

            array_push(
                $tabela,
                $linha 
            );
        }


        $linhaSeparacao = str_repeat("-", 45);
        
        $cabecalho = "\n". str_pad(' Beneficiários', 20," ", STR_PAD_RIGHT)."   idade       preço \n";
 
        echo $cabecalho;
        foreach ( $tabela as $linha ) {
            echo $linhaSeparacao."\n";
            echo $linha;
        }

        echo $linhaSeparacao."\n";
    }
}