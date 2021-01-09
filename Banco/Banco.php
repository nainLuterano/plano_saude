<?php 
/**
 * Classe Responsável pela abstração das consultas de dados
 * 
 * @category Banco de dados
 * 
 * @author andré <andre00lu@gmail.com>
 */


 /**
  * Importação das classes banco de dados
  * responsáveis por dados específicos
  */

require_once __DIR__."./Planos.php";
require_once __DIR__."./Precos.php";

/**
 * Classe Responsável pela abstração das consultas de dados
 * 
 * @category Banco_De_Dados
 */
class Banco
{


    private $_List_tabelas = array();
    private $_Tabela_selecionada = array();
    private $_Filter = array();

    /**
     * Cria conexao com dados
     * para consulta
     */
    function __construct()
    {
        $this->_List_tabelas = array(
            "precos" => new Precos(),
            "planos" => new Planos()
        );
    }

    /**
     * Recebe um array das tabelas
     * para consulta dos dados
     * 
     * @param $tabelas <string>
     * 
     * @return $this
     */
    function join($tabelas)
    {
        foreach ($tabelas as $tabela) {
            $this->setTabela($tabela);
        }
        return $this;
    }

    /**
     * Função usada para filtragem dos dados setados
     * pela função where, retornando dados que estão
     * de acordo com as condições
     * 
     * @param $TabelaDados <array>
     * 
     * @return Int
     */
    private function _filterRegister($TabelaDados)
    {

        $notSelected = 0;
        $TabelaDados = (array) $TabelaDados;
        
        foreach ($this->_Filter as $query) {

            switch($query[1]) {
            case '==':
                if ($TabelaDados[$query[0]] != $query[2]) {
                        $notSelected++;
                }
                break;

            case '>':
                if ($TabelaDados[$query[0]] <= $query[2]) {
                        $notSelected++;
                }
                break;

            case '<':
                if ($TabelaDados[$query[0]] >= $query[2]) {
                        $notSelected++;
                }
                break;
                
            case '>=':
                if ($TabelaDados[$query[0]] < $query[2]) {
                        $notSelected++;
                }
                break;
                
            case '<=':

                if ($TabelaDados[$query[0]] > $query[2]) {
                        $notSelected++;
                }
                break;                
            }


        }

        return $notSelected == 0;
    }

    /**
    * Set a tabela que será consultada
    *
    * @param $tabela <string>
    *
    * @return this
    */
    function setTabela($tabela)
    {

        try {
            if (array_key_exists($tabela, $this->_List_tabelas)) {
                array_push($this->_Tabela_selecionada ,$tabela);
                
            } else {
                throw new Exception("Tabela setada não existe");
            }
        } catch (Exception $e) {
            echo $e->getMessage()." - Linha ". $e->getLine() ."\n";
        }

        return $this;

    }

    /**
     * Recebe um matriz de dados com critérios de filtragem
     * array de arrays
     * array de filtragem => array("campo","operação", valor)
     * 
     * @param $filter <array>
     * 
     * @return this
     */
    function where($filter)
    {
        $this->_Filter = $filter;
        return $this;
    }


    /**
     * Ordenar uma tabela específica
     * recebe uma string com o nome do campo
     * 
     * @param $filter <array>
     * 
     * @return this
     */
    function sort($filter)
    {
        $this->_List_tabelas[$this->_Tabela_selecionada[0]]->sort($filter);
        return $this;
    }


 
    /**
     * Faz a busca dos dados da tabela
     * e retorna um array de objetos
     * 
     * @return array 
     */
    function search()
    {

        $getTabelas = array();
        foreach ( $this->_Tabela_selecionada as $tabela ) {

            $getTabelas = array_merge(
                $getTabelas,
                $this->_List_tabelas[$tabela]->get_tabela()
            );
        }

        return array_filter(
            $getTabelas,
            array($this, "_filterRegister")
        );

    }
}
