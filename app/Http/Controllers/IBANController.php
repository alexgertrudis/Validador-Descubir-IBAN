<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IBANController extends Controller
{
    /**
     * Esta función verifica si un número IBAN (Número de Cuenta Bancaria Internacional) es válido.
     * 
     * @param string $iban El número IBAN que queremos validar.
     * @return bool Devuelve true si el IBAN es correcto, y false si no lo es.
     */
    public function validaIBAN($iban)
    {
        // Primero, convertimos el IBAN a letras mayúsculas y eliminamos los espacios en blanco que pueda tener.
        $iban = strtoupper(str_replace(' ', '', $iban));

        // Comprobamos que el IBAN tenga el formato correcto (que empiece por dos letras seguidas de números y más caracteres).
        if (preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{4}[0-9]{7}([A-Z0-9]?){0,16}$/', $iban)) {
            // Extraemos las partes del IBAN para verificar su validez.
            $country = substr($iban, 0, 2); // Código del país
            $checksum = substr($iban, 2, 2); // Dígitos de control
            $ibanNumber = substr($iban, 4); // Número de cuenta

            // Ajustamos el número para calcular su validez.
            $ibanNumber .= ord($country[0]) - 55 . ord($country[1]) - 55 . substr($checksum, 0, 1) . substr($checksum, 1, 1);

            // Si el resultado de esta operación es 1, el IBAN es válido.
            if (bcmod($ibanNumber, '97') == 1) {
                return true;
            }
        }
        return false;
    }

    /**
     * Esta función verifica si un número CCC (Código de Cuenta Corriente) es válido.
     *
     * @param string $ccc El número CCC que queremos validar.
     * @return bool Devuelve true si el CCC es correcto, y false si no lo es.
     */
    public function validaCCC($ccc)
    {
        // Eliminamos espacios y guiones que pueda tener el CCC.
        $ccc = str_replace([' ', '-'], '', $ccc);

        // Comprobamos que el CCC tenga exactamente 20 dígitos.
        if (strlen($ccc) !== 20) {
            return false;
        }

        // Aseguramos que todos los caracteres sean números.
        if (!ctype_digit($ccc)) {
            return false;
        }

        // Calculamos un número de control para ver si el CCC es válido.
        $checksum = 0;
        foreach (str_split($ccc) as $index => $digit) {
            $checksum += (int) $digit * (10 - ($index % 10));
        }

        // Si el número resultante al dividirlo por 11 es cero y es distinto de cero, el CCC es válido.
        return $checksum % 11 === 0 && $checksum !== 0;
    }

    /**
     * Esta función descubre y retorna un IBAN completo a partir de un IBAN parcialmente oculto con asteriscos.
     *
     * @param string $ibanConAsteriscos El IBAN parcial con asteriscos.
     * @return string|null Retorna el IBAN completo si es posible reconstruirlo, de lo contrario retorna null.
     */
    public function descubreixIBAN($ibanConAsteriscos)
    {
        // Convertimos el IBAN a mayúsculas y eliminamos espacios.
        $ibanConAsteriscos = strtoupper(str_replace(' ', '', $ibanConAsteriscos));
        $longitud = strlen($ibanConAsteriscos);

        // Aseguramos que haya suficiente información para reconstruir el IBAN.
        if ($longitud < 4) {
            return null;
        }

        // Extraemos el código del país y los dígitos de control del final.
        $codigoPais = substr($ibanConAsteriscos, 0, 2);
        $digitosControl = substr($ibanConAsteriscos, -2);

        // Completamos el IBAN con asteriscos en el medio.
        $asteriscos = str_repeat('*', $longitud - 4);
        $ibanCompleto = $codigoPais . $asteriscos . $digitosControl;

        // Devolvemos el IBAN reconstruido.
        return $ibanCompleto;
    }
}