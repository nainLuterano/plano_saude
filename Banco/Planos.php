<?php

/**
 * Classe obtém os dados do plano
 */

class Planos
{
    private $_TabelaPlanos;
    private $_Filter = array();
    
    /**
     * acessa o arquivo com os planos em json
     * converte os dados em arrays
     */
    function __construct()
    {
        $TabelaPlanosJson = file_get_contents(__DIR__."/dados/planos.json");
        $this->_TabelaPlanos = json_decode($TabelaPlanosJson);
    }

    /**
     * @return array
     */
    function get_tabela()
    {
        return $this->_TabelaPlanos;
    }

}
