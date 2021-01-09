<?php

/**
 * Import a classe Plano que será
 * usada como tipagem dos dados do plano 
 */
require_once __DIR__.'./Plano.php';

/**
 * Classe Beneficiario
 * possui os dados relacionados ao beneficiário
 */
class Beneficiario
{
    public $Idade;
    public $Nome;
    public $Plano;
    
    function __construct()
    {
        $this->Plano = new Plano();
    }
}