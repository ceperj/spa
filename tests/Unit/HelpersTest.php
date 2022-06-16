<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    private function each($tests, $closure){
        foreach($tests as $key=>$value){
            $closure($key, $value);
        }
    }

    public function test_remove_accents_must_return_accordingly()
    {
        $tests = [
            'ÁÉÍÓÚ' => 'AEIOU',
            'ÂÊÎÔÛ' => 'AEIOU',
            'ÀÈÌÒÙ' => 'AEIOU',
            'ÄËÏÖÜ' => 'AEIOU',
            'ÃÕÑÇ' => 'AONC',
            'áéíóú' => 'aeiou',
            'âêîôû' => 'aeiou',
            'àèìòù' => 'aeiou',
            'äëïöüÿ' => 'aeiouy',
            'ãõñç' => 'aonc',
        ];
        $this->each($tests, function($input, $expected){
            $actual = remove_accents($input);
            $this->assertEquals($expected, $actual, "Word '$input' did not evaluate to '$expected', but is '$actual'.");
        });
    }

    public function test_layout_file_alpha_must_format_accordingly()
    {
        $tests = [
            ['', 5, '     '],
            [null, 5, '     '],
            ['Escola', 6, 'ESCOLA'],
            ['Escola', 10, 'ESCOLA    '],
            ['Escola', 3, 'ESC'],
            ['Escola de Gestão de Políticas Públicas', 30, 'ESCOLA DE GESTAO DE POLITICAS '],
            ['Ação de Graças nos Álpes SUÍÇOS', 30, 'ACAO DE GRACAS NOS ALPES SUICO'],
        ];
        $this->each($tests, function($index, $test){
            $input = $test[0];
            $size = $test[1];
            $expected = $test[2];
            $output = layout_file_alpha($input, $size);
            $this->assertEquals($expected, $output);
        });
    }

    public function test_layout_file_number_must_return_accordingly()
    {
        $tests = [
            null => [4, '    '],
            '' => [4, '    '],
            0 => [4, '0000'],
            '0' => [4, '0000'],
            1 => [4, '0001'],
            '1' => [4, '0001'],
            1234 => [4, '1234'],
            '1234' => [4, '1234'],
            12345678 => [4, '5678'],
            '12345678' => [4, '5678'],
            '0123456789' => [10, '0123456789'],
            '0123456789' => [5, '56789'],
            '0123456789' => [15, '000000123456789'],
        ];
        $this->each($tests, function($input, $test){
            $size = $test[0];
            $expected = $test[1];
            $output = layout_file_number($input, $size);
            $this->assertEquals($expected, $output, "Value '$input' ($size) should return '$expected' but returned '$output'.");
        });
    }
}
