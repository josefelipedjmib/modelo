<?php

namespace Base\Form\Validator;

class Cpf extends CgcAbstract {

    public function __construct(array $options = array()){
        parent::__construct($options);
    }

    /**
     * Tamanho do Campo
     * @var int
     */
    protected $size = 11;

    /**
     * Modificadores de Dígitos
     * @var array
     */
    protected $modifiers = [
        [10, 9, 8, 7, 6, 5, 4, 3, 2],
        [11, 10, 9, 8, 7, 6, 5, 4, 3, 2]
    ];

}
