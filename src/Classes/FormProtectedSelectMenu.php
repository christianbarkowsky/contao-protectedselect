<?php

declare(strict_types=1);

/**
 * Plenta Protected Select Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2015-2023, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       http://opensource.org/licenses/lgpl-3.0.html
 * @link          https://github.com/plenta/
 */

namespace Plenta\ProtectedSelect\Classes;

use Contao\FormSelect;
use Contao\FormSelectMenu;
use Contao\Input;
use Contao\StringUtil;

if(class_exists(FormSelect::class)) {
    class SelectParent extends FormSelect {}
} else {
    class SelectParent extends FormSelectMenu {}
}

class FormProtectedSelectMenu extends SelectParent
{
    protected $strTemplate = 'form_select';

    public function __set($strKey, $varValue): void
    {
        parent::__set($strKey, $varValue);
    }

    public function __get($strKey)
    {
        switch ($strKey) {
            case 'value':
                $this->arrOptions = StringUtil::deserialize($this->protectedOptions, true);

                if (\is_array($this->varValue)) {
                    $arrValues = [];

                    foreach ($this->varValue as $k => $value) {
                        foreach ($this->arrOptions as $option) {
                            if ($option['reference'] == $value) {
                                $arrValues[$k] = $option['value'];
                                continue 2;
                            }
                        }
                    }

                    return $arrValues;
                }

                foreach ($this->arrOptions as $option) {
                    if ($option['reference'] == $this->varValue) {
                        return $option['value'];
                    }
                }

                break;

            default:
                return parent::__get($strKey);
        }
    }

    public function validate(): void
    {
        $mandatory = $this->mandatory;
        $options = $this->getPost($this->strName);

        if ($mandatory && is_array($options)) {
            foreach ($options as $option) {
                if (strlen($option)) {
                    $this->mandatory = false;
                    break;
                }
            }
        }

        $varInput = $this->validator($options);

        if (!empty($varInput) && !$this->isValidOption($varInput)) {
            $this->addError(
                sprintf(
                    $GLOBALS['TL_LANG']['ERR']['invalid'],
                    (is_array($varInput) ? implode(', ', $varInput) : $varInput)
                )
            );
        }

        if ($this->hasErrors()) {
            $this->class = 'error';
        } else {
            $this->varValue = $varInput;
        }

        if ($mandatory) {
            $this->mandatory = true;
        }
    }

    protected function getOptions(): array
    {
        $this->arrOptions = StringUtil::deserialize($this->protectedOptions, true);

        if (!is_array($this->varValue) && !empty($this->varValue) && isset($_GET[$this->strName])) {
            $this->varValue = Input::get($this->strName);
        }

        foreach ($this->arrOptions as $k => $option) {
            $this->arrOptions[$k]['value'] = $option['reference'];
        }

        return parent::getOptions();
    }

    protected function isValidOption($varInput): bool
    {
        $protectedOptions = StringUtil::deserialize($this->protectedOptions, true);

        foreach ($protectedOptions as $k => $option) {
            if ($varInput == $option['reference']) {
                return true;
            }
        }

        return false;
    }
}
