<?php

namespace BespokeSupport\Reg;

/**
 * Class Reg.
 */
class Reg
{
    const INVALID_REG = 'Not a valid Registration Mark';

    const STYLE_CURRENT = 'current';

    const STYLE_DATELESS_LETTER = 'dateless_letter';

    const STYLE_DATELESS_NUMBER = 'dateless_number';

    const STYLE_PREFIX = 'prefix';

    const STYLE_SUFFIX = 'suffix';

    /**
     * @var string
     */
    public $charLetter;

    /**
     * @var string
     */
    public $charNumber;

    /**
     * @var string
     */
    public $charPrefix;

    /**
     * @var string
     */
    public $charSuffix;

    /**
     * @var string
     */
    public $reg;

    /**
     * @var string
     */
    public $style;

    /**
     * Reg constructor.
     *
     * @param $reg
     */
    public function __construct($reg)
    {
        $cleaned = static::clean($reg);

        if (preg_match('/^((\d{1,4})([A-Z]{1,3})|([A-Z]{1,3})(\d{1,4}))$/', $cleaned) && strlen($cleaned) <= 7) {
            $this->style = (ctype_digit(substr($cleaned, 0, 1))) ?
                self::STYLE_DATELESS_NUMBER :
                self::STYLE_DATELESS_LETTER;
            $this->reg = $cleaned;
            $this->charLetter = preg_replace('/\d/', '', $cleaned);
            $this->charNumber = preg_replace('/[^\d]/', '', $cleaned);
            $this->charPrefix = null;
            $this->charSuffix = null;
            if ($this->style == self::STYLE_DATELESS_LETTER) {
                $this->extraValidationDatelessLetter();
            } else {
                $this->extraValidationDatelessNumber();
            }
            $this->extraValidationDateless();
        } elseif (preg_match('/^([A-Z]{2})(\d{2})([A-Z]{3})$/', $cleaned, $matches)) {
            $this->style = self::STYLE_CURRENT;
            $this->reg = $cleaned;
            $this->charLetter = $matches[3];
            $this->charNumber = $matches[2];
            $this->charPrefix = $matches[1];
            $this->charSuffix = null;
            $this->extraValidationCurrent();
        } elseif (preg_match('/^([A-Z]{1})(\d{1,3})([A-Z]{3})$/', $cleaned, $matches)) {
            $this->reg = $cleaned;
            $this->style = self::STYLE_PREFIX;
            $this->charLetter = $matches[3];
            $this->charNumber = $matches[2];
            $this->charPrefix = $matches[1];
            $this->charSuffix = null;
            $this->extraValidationPrefix();
        } elseif (preg_match('/^([A-Z]{3})(\d{1,3})([A-Z]{1})$/', $cleaned, $matches)) {
            $this->reg = $cleaned;
            $this->style = self::STYLE_SUFFIX;
            $this->charPrefix = null;
            $this->charLetter = $matches[1];
            $this->charSuffix = $matches[3];
            $this->charNumber = $matches[2];
            $this->extraValidationSuffix();
        } else {
            throw new \InvalidArgumentException(self::INVALID_REG);
        }
    }

    /**
     * @param $plate
     *
     * @return string
     */
    public static function clean($plate)
    {
        $plate = strtoupper(preg_replace('/[^\dA-Za-z]/', '', $plate));

        return $plate;
    }

    /**
     * @param $reg
     *
     * @return Reg|null
     */
    public static function create($reg)
    {
        if (is_a($reg, self::class)) {
            return $reg;
        }

        try {
            return new self($reg);
        } catch (\Exception $e) {
            return;
        }
    }

    protected function extraValidationCurrent()
    {
    }

    protected function extraValidationDateless()
    {
        if ('III' == $this->charLetter) {
            throw new \Exception(self::INVALID_REG);
        }

        if ('QQQ' == $this->charLetter) {
            throw new \Exception(self::INVALID_REG);
        }
    }

    protected function extraValidationDatelessLetter()
    {
    }

    protected function extraValidationDatelessNumber()
    {
        if ('0' == substr($this->charNumber, 0, 1)) {
            throw new \Exception(self::INVALID_REG);
        }
    }

    protected function extraValidationPrefix()
    {
        if ('ZAA' == $this->charLetter) {
            throw new \Exception(self::INVALID_REG);
        }

        if ('Z' == substr($this->charLetter, 0, 1)) {
            throw new \Exception(self::INVALID_REG);
        }
    }

    protected function extraValidationSuffix()
    {
        if ('QQQ' == $this->charLetter) {
            throw new \Exception(self::INVALID_REG);
        }

        if ('ZZZ' == $this->charLetter) {
            throw new \Exception(self::INVALID_REG);
        }

        if ('ZAA' == $this->charLetter) {
            throw new \Exception(self::INVALID_REG);
        }

        if ('QAA' == $this->charLetter) {
            throw new \Exception(self::INVALID_REG);
        }

        if ('I' == substr($this->charLetter, 0, 1)) {
            throw new \Exception(self::INVALID_REG);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getFormatted();
    }

    /**
     * @return string
     */
    public function getFormatted()
    {
        switch ($this->style) {
            case self::STYLE_DATELESS_NUMBER:
                $return = $this->charNumber.' '.$this->charLetter;
                break;
            case self::STYLE_DATELESS_LETTER:
                $return = $this->charLetter.' '.$this->charNumber;
                break;
            case self::STYLE_PREFIX:
                $return = $this->charPrefix.$this->charNumber.' '.$this->charLetter;
                break;
            case self::STYLE_SUFFIX:
                $return = $this->charLetter.' '.$this->charNumber.$this->charSuffix;
                break;
            case self::STYLE_CURRENT:
                $return = $this->charPrefix.$this->charNumber.' '.$this->charLetter;
                break;
            // @codeCoverageIgnoreStart
            default:
                throw new \LogicException('Unknown Reg Style');
            // @codeCoverageIgnoreEnd
        }

        return $return;
    }

    /**
     * @return bool
     */
    public function isStyleCurrent()
    {
        return self::STYLE_CURRENT == $this->style;
    }

    /**
     * @return bool
     */
    public function isStyleDateless()
    {
        return in_array($this->style, [self::STYLE_DATELESS_LETTER, self::STYLE_DATELESS_NUMBER]);
    }

    /**
     * @return bool
     */
    public function isStylePrefix()
    {
        return self::STYLE_PREFIX == $this->style;
    }

    /**
     * @return bool
     */
    public function isStyleSuffix()
    {
        return self::STYLE_SUFFIX == $this->style;
    }
}
