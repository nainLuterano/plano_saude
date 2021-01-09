<?php 

/**
 * Classe que acessa os dados dos preços dos planos
 */

class Precos
{

    private $_TabelaPrecos;
    private $_Filter = array();

    function __construct()
    {

        $PrecosJson = file_get_contents(__DIR__."/dados/precos.json");
        $this->TabelaPrecos = json_decode($PrecosJson);
    }

    /**
     * Retorna a tabela com dados dos Preços
     * do plano
     * 
     * @return array
     */
    function get_tabela()
    {
        return $this->TabelaPrecos;
    }

    /**
     * Função que ordena os dados da tabela
     * e a modifica
     * 
     * @return void
     */

    function sort($filter): void
    {
        $this->_Filter = $filter;
        usort(
            $this->TabelaPrecos ,
            array($this, "_criterioSort")
        );
    }

    /**
     * Função com os critérios de ordenação dos dados
     * 
     * @return int
     */
    private function _criterioSort($primeiroPlano, $segundoPlano): int
    {
        $primeiroPlano = (array) $primeiroPlano;
        $segundoPlano = (array) $segundoPlano;        

        if ($primeiroPlano[$this->_Filter] == $segundoPlano[$this->_Filter]) return 0;
        return ( $primeiroPlano[$this->_Filter] <  $segundoPlano[$this->_Filter])? -1: 1;
    }

}


