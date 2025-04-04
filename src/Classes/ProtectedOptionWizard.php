<?php

declare(strict_types=1);

/**
 * Plenta Protected Select Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2015-2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       http://opensource.org/licenses/lgpl-3.0.html
 * @link          https://github.com/plenta/
 */

namespace Plenta\ProtectedSelect\Classes;

use Contao\ArrayUtil;
use Contao\Controller;
use Contao\Database;
use Contao\Environment;
use Contao\Image;
use Contao\Input;
use Contao\OptionWizard;
use Contao\StringUtil;

/**
 * Class ProtectedOptionWizard.
 */
class ProtectedOptionWizard extends OptionWizard
{
    /**
     * Validate input and set value.
     */
    public function validate(): void
    {
        $arrReferences = [];
        $mandatory = $this->mandatory;
        $options = StringUtil::deserialize(Input::post($this->strName));

        // Check labels only (values can be empty)
        if (\is_array($options)) {
            foreach ($options as $key => $option) {
                $options[$key]['label'] = trim($option['label']);
                $options[$key]['value'] = trim($option['value']);
                $options[$key]['reference'] = trim($option['reference']);

                if (\strlen($options[$key]['label'])) {
                    $this->mandatory = false;
                }

                $arrReferences[] = $options[$key]['reference'];
            }
        }

        if (\count(array_unique($arrReferences)) != \count($arrReferences)) {
            $this->addError($GLOBALS['TL_LANG']['ERR']['uniqueReference']);
        }

        $options = array_values($options);
        $varInput = $this->validator($options);

        if (!$this->hasErrors()) {
            $this->varValue = $varInput;
        }

        // Reset the property
        if ($mandatory) {
            $this->mandatory = true;
        }
    }

    /**
     * Generate the widget and return it as string.
     *
     * @return string
     */
    public function generate()
    {
        $arrButtons = ['copy', 'delete', 'drag'];

        // Make sure there is at least an empty array
        if (!\is_array($this->varValue) || !$this->varValue[0]) {
            $this->varValue = [['']];
        }

        // Begin table
        $return = '<table class="tl_optionwizard" style="max-width:none" id="ctrl_'.$this->strId.'" summary="Field wizard">
  <thead>
    <tr>
      <th>'.$GLOBALS['TL_LANG'][$this->strTable]['opReference'].'</th>
      <th>'.$GLOBALS['TL_LANG'][$this->strTable]['opValueProtected'].'</th>
      <th>'.$GLOBALS['TL_LANG'][$this->strTable]['opLabel'].'</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody class="sortable">';

        // Add fields
        for ($i = 0; $i < \count($this->varValue); ++$i) {
            $return .= '
    <tr>
      <td><input type="text" name="'.$this->strId.'['.$i.'][reference]" id="'.$this->strId.'_reference_'.$i.'" class="tl_text" value="'.StringUtil::specialchars($this->varValue[$i]['reference'] ?? '').'" /></td>
      <td><input type="text" name="'.$this->strId.'['.$i.'][value]" id="'.$this->strId.'_value_'.$i.'" class="tl_text" value="'.StringUtil::specialchars($this->varValue[$i]['value'] ?? '').'" /></td>
      <td style="width: auto"><input type="text" name="'.$this->strId.'['.$i.'][label]" id="'.$this->strId.'_label_'.$i.'" class="tl_text" value="'.StringUtil::specialchars($this->varValue[$i]['label'] ?? 'label').'" /></td>
      <td><input type="checkbox" name="'.$this->strId.'['.$i.'][default]" id="'.$this->strId.'_default_'.$i.'" class="fw_checkbox" value="1"'.($this->varValue[$i]['default'] ?? false ? ' checked="checked"' : '').' /> <label for="'.$this->strId.'_default_'.$i.'">'.$GLOBALS['TL_LANG'][$this->strTable]['opDefault'].'</label></td>
      <td><input type="checkbox" name="'.$this->strId.'['.$i.'][group]" id="'.$this->strId.'_group_'.$i.'" class="fw_checkbox" value="1"'.($this->varValue[$i]['group'] ?? false ? ' checked="checked"' : '').' /> <label for="'.$this->strId.'_group_'.$i.'">'.$GLOBALS['TL_LANG'][$this->strTable]['opGroup'].'</label></td>';

            // Add row buttons
            $return .= '
      <td style="white-space:nowrap; padding-left:3px;">';

            foreach ($arrButtons as $button) {
                if ('drag' == $button) {
                    $return .= '<button type="button" class="drag-handle" title="" aria-hidden="true">'.Image::getHtml('drag.svg', '', 'class="drag-handle" title="'.$GLOBALS['TL_LANG']['MSC']['move'].'"').'</button>';
                } else {
                    $return .= '<button type="button" data-command="'.$button.'" title="'.StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['ow_'.$button]).'">'.Image::getHtml($button.'.svg', $GLOBALS['TL_LANG']['MSC']['ow_'.$button]).'</button> ';
                }
            }

            $return .= '</td>
    </tr>';
        }

        return $return.'
  </tbody>
  </table><script>Backend.optionsWizard("ctrl_'.$this->strId.'")</script>';
    }
}
