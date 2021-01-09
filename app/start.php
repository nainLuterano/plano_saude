<?php
/**
 * Programa principal
 * cadastro de beneficiários
 * Mostra o valor a ser pago por cada beneficiário
 * Mostra o total a ser pago
 */ 


$grupoBeneficiarios = new Beneficiarios();


$qntBeneficiarios = lerQuantidadeBeneficiarios();




for ($posicao = 0; $posicao < $qntBeneficiarios; $posicao++ ) {
    
    $beneficiario = createBeneficiario();
    
    $grupoBeneficiarios->addBeneficiario($beneficiario);
}


$grupoBeneficiarios->showBeneficiariosPlanos();

echo "Total a pagar: " . formatValor($grupoBeneficiarios->totalPagar())."\n";