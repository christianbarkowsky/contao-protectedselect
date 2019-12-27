<?php

/**
 * protected select
 * Adds a new formula select widget which hides the internal field values in the frontend
 *
 * @copyright  Christian Barkowsky 2015-2019
 * @copyright  Jan Theofel 2011-2014, ETES GmbH 2010
 * @copyright  ETES GmbH 2010
 * @author     Christian Barkowsky <hallo@christianbarkowsky.de>
 * @author     Jan Theofel <jan@theofel.de>
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace Contao;

use Contao\Input;
use Contao\StringUtil;
use Contao\FormSelectMenu;

class FormProtectedSelectMenu extends FormSelectMenu
{

    /**
     * @var string
     */
    protected $strTemplate = 'form_select';

    /**
     * Add specific attributes
     */
    public function __set($strKey, $varValue)
    {
        parent::__set($strKey, $varValue);
    }

    /**
     * Add specific attributes
     */
    public function __get($strKey)
    {
        switch ($strKey) {
            case 'value':
                $this->arrOptions = StringUtil::deserialize($this->protectedOptions, true);

                if (is_array($this->varValue)) {
                    $arrValues = [];

                    foreach ($this->varValue as $k => $value) {
                        foreach ($this->arrOptions as $option) {
                            if ($option['reference'] == $value) {
                                $arrValues[$k] = $option['value'];
                                continue(2);
                            }
                        }
                    }

                    return $arrValues;
                }

                foreach( $this->arrOptions as $option ) {
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
     * Generate the options
     *
     * @return array The options array
     */
    protected function getOptions()
    {
        $this->arrOptions = StringUtil::deserialize($this->protectedOptions, true);

        if (!is_array($this->varValue) && !strlen($this->varValue) && isset($_GET[$this->strName])) {
            $this->varValue = Input::get($this->strName);
        }

        foreach ($this->arrOptions as $k => $option) {
            $this->arrOptions[$k]['value'] = $option['reference'];
        }

        return parent::getOptions();
    }

    /**
     * Check for a valid option
     */
    /**/
    public function validate()
    {
        $mandatory = $this->mandatory;
        $options = $this->getPost($this->strName);

        // Check if there is at least one value
        if ($mandatory && is_array($options)) {
            foreach ($options as $option) {
                if (strlen($option)) {
                    $this->mandatory = false;
                    break;
                }
            }
        }

        $varInput = $this->validator($options);

        // Check for a valid option (see #4383)
        if (!empty($varInput) && !$this->isValidOption($varInput)) {
            $this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['invalid'], (is_array($varInput) ? implode(', ', $varInput) : $varInput)));
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
