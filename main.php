<?php
/**
 * Importação dos arquivos necessários
 * da aplicação
 * @author andré luiz tavares rocha pitta <andre00lu@gmail.com>
 */


/**
 *  Importação da classe Benefiácio
 *  que será usado como tipagem
 */ 

require_once __DIR__."/TypesDatas/Beneficiario.php";

/**
 *  Importação da classe responsável
 *  do grupo de beneficiários da aplicação
 */
require_once __DIR__."/Class/Beneficiarios.php";

/**
 * Importação da classe para consulta aos dados
 * dos arquivos
 */
require_once __DIR__."/Banco/Banco.php";

/**
 * Importação das funções gerais da aplicação
 */
require_once __DIR__."/functions/functions.php";

/**
 * Importação do programa principal
 */
require_once __DIR__."/app/start.php";

