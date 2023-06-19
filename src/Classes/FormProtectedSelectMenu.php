<?php

declare(strict_types=1);

/**
 * Plenta Protected Select Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2015-2022, Plenta.io
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
    /**
     * @var string
     */
    protected $strTemplate = 'form_select';

    /**
     * Add specific attributes.
     *
     * @param mixed $strKey
     * @param mixed $varValue
     */
    public function __set($strKey, $varValue): void
    {
        parent::__set($strKey, $varValue);
    }

    /**
     * Add specific attributes.
     *
     * @param mixed $strKey
     */
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

    /**
     * Check for a valid option.
     */
    public function validate(): void
    {
        $mandatory = $this->mandatory;
        $options = $this->getPost($this->strName);

        // Check if there is at least one value
        if ($mandatory && \is_array($options)) {
            foreach ($options as $option) {
                if (\strlen($option)) {
                    $this->mandatory = false;
                    break;
                }
            }
        }

        $varInput = $this->validator($options);

        // Check for a valid option (see #4383)
        if (!empty($varInput) && !$this->isValidOption($varInput)) {
            $this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['invalid'], (\is_array($varInput) ? implode(', ', $varInput) : $varInput)));
        }

        // Add class "error"
        if ($this->hasErrors()) {
            $this->class = 'error';
        } else {
            $this->varValue = $varInput;
        }

        // Reset the property
        if ($mandatory) {
            $this->mandatory = true;
        }
    }

    /**
     * Generate the options.
     *
     * @return array The options array
     */
    protected function getOptions()
    {
        $this->arrOptions = StringUtil::deserialize($this->protectedOptions, true);

        if (!\is_array($this->varValue) && !\strlen($this->varValue) && isset($_GET[$this->strName])) {
            $this->varValue = Input::get($this->strName);
        }

        foreach ($this->arrOptions as $k => $option) {
            $this->arrOptions[$k]['value'] = $option['reference'];
        }

        return parent::getOptions();
    }

    protected function isValidOption($varInput)
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
