<?php

/**
 * @description lista de funções gerais da aplicação
 */


// Variáveis gerais necessárias para algumas funções
$Func_Banco = new Banco();

$Func_tabelaPlanos = $Func_Banco->setTabela('planos')->search();

$Func_listRegPlanos = getListRegistro();


/**
 * Verifica se registro escolhido existe na tabela
 * 
 * @return bool
 */
function registroValido($reg): bool
{
    global $Func_listRegPlanos;

    return in_array($reg, $Func_listRegPlanos);
}

/**
 *  Mostra na tela uma tabela dos registros dos planos cadastrados
 * 
 * @return void 
 */
function showRegistro(): void
{
    global $Func_tabelaPlanos;
    $totalLinhas= array();
    $cabecalho = "\n Registro        Plano\n"; 
    $tamanhoLinhaSeparacao = 0;

    foreach ($Func_tabelaPlanos as $plano) {
        $linhaInformacao = "| ".$plano->registro ."        ". $plano->nome;
        array_push($totalLinhas, $linhaInformacao);
        $tamanhoLinhaSeparacao =  $tamanhoLinhaSeparacao < strlen($linhaInformacao) - 2 ? 
        strlen($linhaInformacao) + 1: $tamanhoLinhaSeparacao;
    }

    for ($traco=0; $traco <= $tamanhoLinhaSeparacao; $traco++ ) {
        $linhaSeparacao.='-';
    }

    echo $cabecalho;
    foreach ( $totalLinhas as $informacao ) {
        echo $linhaSeparacao."\n";
        $diferençaEntreLinhas = strlen($informacao) - $tamanhoLinhaSeparacao;   
        echo str_pad($informacao, strlen($informacao) + abs($diferençaEntreLinhas) ," ", STR_PAD_RIGHT)."|\n"; 
    }
    echo $linhaSeparacao."\n\n";

}

/**
 * Função que valida se o registro fornecido pelo usuário é válido
 * e retorna caso seja verdadeiro
 * 
 * @return string
 */
function selecionarRegistro() 
{
    while (true) {
        showRegistro();
        $registro = readline("Selecione um Registro: ");
        if ( registroValido($registro) ) {
            break;
        } else {
            exec("cls");
            echo "Esse registro não é válido \n";
            echo "Escolha um registro válido \n";
        }
    }

    return $registro;
}

/**
 * função que recebe registro do plano e
 * retorna o código do plano
 * 
 * @param $reg <string>
 * 
 * @return int
 */
function getCodigoPlano($reg): int
{
    global $Func_tabelaPlanos;

    foreach ($Func_tabelaPlanos as $plano) {
        if ($plano->registro == $reg) {
            $codigo = $plano->codigo;
        }
    }
    return $codigo;
}

/**
 * Função que busca o plano
 * usuando o registro
 * e retorna o nome do plano
 */
function getNomePlano($reg): string
{
    global $Func_tabelaPlanos;

    foreach ($Func_tabelaPlanos as $plano) {
        if ($plano->registro == $reg) {
            $nome = $plano->nome;
        }
    }
    return $nome;
}

/**
 * Retorna uma array com os registro dos planos
 */
function getListRegistro(): array
{
    global $Func_tabelaPlanos;
    $Func_listRegPlanos = array();

    foreach ($Func_tabelaPlanos as $plano) {

        array_push($Func_listRegPlanos, $plano->registro);
    }
    return $Func_listRegPlanos;
}

/**
 * Seleciona uma idade válida e retorna o valor
 */
function selecionarIdadeBeneficionario(): int
{
    while (true) {
        $idade = intval(readline("Qual idade do Beneficiário: "));

        if ($idade > 0 ) {
            break;
        } else {
            echo "Idade inválida \n";
        }
    }
    return $idade;

}

/**
 * Verifica quantas palavras acentuadas existe na string
 * e retorna o valor
 */
function quantosAcentosTem($palavra): int
{
    $qnt = 0;
    $regex = "[ÁáàâãäªÉéèêëÍíìîïÓóòôõöºÚúùûüçñ]+";

     preg_match("/" . $regex . "/i", $palavra, $ocorrencias,  PREG_OFFSET_CAPTURE);
    if (isset($ocorrencias[0]) ) {
        return count($ocorrencias[0]);
    } else {
        return 0;
    }
}



$registroBeneficiarios;

/**
 * Cria objeto beneficiario
 * usa os valores fornecidos pelo usuário
 * a partir de um terminal
 * e retorna o objeto criado
 */
function createBeneficiario(): Beneficiario
{
    global $registroBeneficiarios;
    
    $registroBeneficiarios = empty($registroBeneficiarios) ?
     selecionarRegistro() : $registroBeneficiarios;
    
    $beneficiario = new Beneficiario();




    $beneficiario = new Beneficiario();
    echo "\n";
    $beneficiario->Nome = readline("Qual o nome do Beneficiário: ");
    echo "\n";
    $beneficiario->Idade = selecionarIdadeBeneficionario();   
    
    $beneficiario->Plano->Registro = $registroBeneficiarios;
    $beneficiario->Plano->Codigo  = getCodigoPlano($beneficiario->Plano->Registro);
    $beneficiario->Plano->Nome = getNomePlano($beneficiario->Plano->Registro);
    
    return $beneficiario;
}


function formatValor($valor): string
{
    return "R$ ".$valor.",00";
}

/**
 * ler a quantidade de beneficiários fornecida
 * pelo usuário e verifica se a quantidade 
 * de beneficiários é válida
 */
function lerQuantidadeBeneficiarios(): int
{
    while (true) {
        $qnt = readline(" Quantidade de Beneficiários: ");
        if (intval($qnt) <= 0 ) {
            echo "\n Informe um valor válido\n\n";
        } else {
            return (int) $qnt;
        }
    }

}

/**
 * Formatar os dados para aprensetação na tabela
 */
function formatarLinhaTabelaBeneficiario($beneficiario): string
{
    $espacoBeneficiarioIdade = 21 + quantosAcentosTem($beneficiario->Nome);
    $espacoPreco = $beneficiario->Idade < 10 ? 16 : 15;
    $nomeBeneficiario = str_pad($beneficiario->Nome, $espacoBeneficiarioIdade, " ", STR_PAD_RIGHT);
    $precoPagar = str_pad("R$ ".$beneficiario->Plano->Preco. ",00", $espacoPreco, " ", STR_PAD_LEFT)."  |";
    
   
    return "| ". $nomeBeneficiario . " ". $beneficiario->Idade. " ". $precoPagar ."\n";
  
}