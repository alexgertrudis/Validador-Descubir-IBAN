<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\IBANController;

class ValidadorIBANTest extends TestCase
{
    /** @test */
    public function puede_validar_un_IBAN_correcto()
    {
        // Inicialización del controlador IBAN para realizar pruebas.
        $ibanController = new IBANController();
        // Establecimiento de un IBAN que es conocido por ser válido.
        $ibanValido = 'ES9121000418450200051332';
        // Ejecución de la validación del IBAN y afirmación de que el resultado es verdadero.
        $this->assertTrue($ibanController->validaIBAN($ibanValido));
    }

    /** @test */
    public function puede_invalidar_un_IBAN_incorrecto()
    {
        // Creación del controlador IBAN para usar en la prueba.
        $ibanController = new IBANController();
        // Definición de un IBAN que es incorrecto.
        $ibanInvalido = 'ES1234567890123456789012';
        // Prueba para asegurar que el método validaIBAN retorna false para un IBAN incorrecto.
        $this->assertFalse($ibanController->validaIBAN($ibanInvalido));
    }

    /** @test */
    public function puede_validar_un_CCC_correcto()
    {
        // Creación de una instancia del controlador para validar el CCC.
        $ibanController = new IBANController();
        // CCC válido proporcionado para la prueba.
        $cccValido = '01234567890123456789';
        // Verificación de que el método validaCCC retorna true para un CCC correcto.
        $this->assertTrue($ibanController->validaCCC($cccValido));
    }

    /** @test */
    public function puede_invalidar_un_CCC_incorrecto()
    {
        // Instanciación del controlador para pruebas de CCC.
        $ibanController = new IBANController();
        // Establecimiento de un CCC que es incorrecto.
        $cccInvalido = '12345678901234567890';
        // Confirmación de que el método validaCCC retorna false para un CCC incorrecto.
        $this->assertFalse($ibanController->validaCCC($cccInvalido));
    }

    /** @test */
    public function puede_descubrir_el_IBAN_a_partir_de_un_IBAN_con_asteriscos()
    {
        // Inicialización del controlador para descubrir IBANs.
        $ibanController = new IBANController();
        // Definición de un IBAN parcialmente ocultado para la prueba.
        $ibanConAsteriscos = 'ES**************20';
        // Ejecución del método para descubrir el IBAN completo a partir de uno con asteriscos.
        $resultado = $ibanController->descubreixIBAN($ibanConAsteriscos);
        // Afirmación de que el resultado no es nulo, indicando que el IBAN pudo ser descubierto.
        $this->assertNotNull($resultado);
    }
}