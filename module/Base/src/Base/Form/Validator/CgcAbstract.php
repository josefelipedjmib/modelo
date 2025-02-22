<?php

namespace Base\Form\Validator;

use Zend\Validator\AbstractValidator;

/**
 * Classe CgcAbstract para validar tanto cpf quanto cnpj.
 * Existem no pacote as classes Cpf e Cnpj que a extendem.
 */
abstract class CgcAbstract extends AbstractValidator {

    public function __construct($options = null) {
        parent::__construct($options);
        if (array_key_exists('validIfEmpty', $options))
            $this->validIfEmpty = $options['validIfEmpty'];
        if (array_key_exists('acceptFakeNumbers', $options))
            $this->acceptFakeNumbers = $options['acceptFakeNumbers'];
    }

    /**
     * Tamanho Inválido
     * @var string
     */
    const SIZE = 'size';

    /**
     * Números Expandidos
     * @var string
     */
    const EXPANDED = 'expanded';

    /**
     * Dígito Verificador
     * @var string
     */
    const DIGIT = 'digit';

    /**
     * Tamanho do Campo
     * @var int
     */
    protected $size = 0;
    

    /**
     * Modelos de Mensagens
     * @var string
     */
    protected $messageTemplates = [
        self::SIZE => "'%value%' não possui tamanho esperado.",
        self::EXPANDED => "'%value%' não possui um formato aceitável.",
        self::DIGIT => "'%value%' não é um documento válido."
    ];

    /**
     * Modificadores de Dígitos
     * @var array
     */
    protected $modifiers = array();
    protected $validIfEmpty = true;
    protected $fakeNumbersSize = 3;
    protected $acceptFakeNumbers = false;

    /**
     * Validação Interna do Documento
     * @param string $value Dados para Validação
     * @return boolean Confirmação de Documento Válido
     */
    protected function check($value) {
        if(empty($value))
            return false;
        // Captura dos Modificadores
        foreach ($this->modifiers as $modifier) {
            $result = 0; // Resultado Inicial
            $size = count($modifier); // Tamanho dos Modificadores
            if($size > strlen($value))
                return false;
            for ($i = 0; $i < $size; $i++) {
                $result += $value[$i] * $modifier[$i]; // Somatório
            }
            $result = $result % 11;
            $digit = ($result < 2 ? 0 : 11 - $result); // Dígito
            // Verificação
            if ($value[$size] != $digit) {
                return false;
            }
        }
        return true;
    }

    public function isValid($value) {
        if (!$this->validIfEmpty && empty($value)) {
            return true;
        }
        // Filtro de Dados
        $data = preg_replace('/[^0-9]/', '', $value);
        //Verifica se estar no padrão 000 -- usuários padrões
        if ($this->acceptFakeNumbers && strlen($data) == $this->fakeNumbersSize) {
            return true;
        }
        // Verificação de Tamanho
        if (strlen($data) != $this->size) {
            $this->error(self::SIZE, $value);
            return false;
        }
        // Verificação de Dígitos Expandidos
        if (str_repeat($data[0], $this->size) == $data) {
            $this->error(self::EXPANDED, $value);
            return false;
        }
        // Verificação de Dígitos
        if (!$this->check($data)) {
            $this->error(self::DIGIT, $value);
            return false;
        }
        // Comparações Concluídas
        return true; // Todas Verificações Executadas
    }

}
