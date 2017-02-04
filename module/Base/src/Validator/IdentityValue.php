<?php
namespace Base\Validator;

use Zend\Validator\AbstractValidator;

class IdentityValue extends AbstractValidator
{
    const NOT_INDENTITY = 'notIndentity';

    protected $messageTemplates = [
        self::NOT_INDENTITY   => "Values not identity",
    ];

    private $numberCompare = 0;

    /**
     * IdentityValue constructor.
     * @param array|null|\Traversable $numberCompare
     */
    public function __construct($numberCompare)
    {
        parent::__construct();
        $this->numberCompare = $numberCompare;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        if ($value != $this->numberCompare) {
            $this->error(self::NOT_INDENTITY);
            return false;
        }
        return true;
    }

}
