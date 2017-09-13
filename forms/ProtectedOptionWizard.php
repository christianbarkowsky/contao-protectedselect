<?php

/*
 * protected select
 * Adds a new formula select widget which hides the internal field values in the frontend
 *
 * @copyright  Christian Barkowsky 2015-2017, Jan Theofel 2011-2014, ETES GmbH 2010
 * @author     Christian Barkowsky <hallo@christianbarkowsky.de>
 * @author     Jan Theofel <jan@theofel.de>
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


class ProtectedOptionWizard extends \OptionWizard
{
    /**
     * Validate input and set value
     */
    public function validate()
    {
        $arrReferences = array();
        $mandatory = $this->mandatory;
        $options = deserialize(\Input::post($this->strName));

        // Check labels only (values can be empty)
        if (is_array($options))
        {
            foreach ($options as $key=>$option)
            {
                $options[$key]['label'] = trim($option['label']);
                $options[$key]['value'] = trim($option['value']);
                $options[$key]['reference'] = trim($option['reference']);

                if (strlen($options[$key]['label']))
                {
                    $this->mandatory = false;
                }

                $arrReferences[] = $options[$key]['reference'];
            }
        }

        if (count(array_unique($arrReferences)) != count($arrReferences))
        {
            $this->addError($GLOBALS['TL_LANG']['ERR']['uniqueReference']);
        }

        $options = array_values($options);
        $varInput = $this->validator($options);

        if (!$this->hasErrors())
        {
            $this->varValue = $varInput;
        }

        // Reset the property
        if ($mandatory)
        {
            $this->mandatory = true;
        }
    }


    /**
     * Generate the widget and return it as string
     * @return string
     */
    public function generate()
    {
        $arrButtons = array('copy', 'drag', 'up', 'down', 'delete');
        // Make sure there is at least an empty array
        if (!is_array($this->varValue) || !$this->varValue[0])
        {
            $this->varValue = array(array(''));
        }

        // Begin table
        $return = '<table class="tl_optionwizard" id="ctrl_'.$this->strId.'" summary="Field wizard">
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
        for ($i=0; $i<count($this->varValue); $i++)
        {
            $return .= '
    <tr>
      <td><input type="text" name="'.$this->strId.'['.$i.'][reference]" id="'.$this->strId.'_reference_'.$i.'" class="tl_text" value="'.\StringUtil::specialchars($this->varValue[$i]['reference']).'" /></td>
      <td><input type="text" name="'.$this->strId.'['.$i.'][value]" id="'.$this->strId.'_value_'.$i.'" class="tl_text" value="'.\StringUtil::specialchars($this->varValue[$i]['value']).'" /></td>
      <td><input type="text" name="'.$this->strId.'['.$i.'][label]" id="'.$this->strId.'_label_'.$i.'" class="tl_text" value="'.\StringUtil::specialchars($this->varValue[$i]['label']).'" /></td>
      <td><input type="checkbox" name="'.$this->strId.'['.$i.'][default]" id="'.$this->strId.'_default_'.$i.'" class="fw_checkbox" value="1"'.($this->varValue[$i]['default'] ? ' checked="checked"' : '').' /> <label for="'.$this->strId.'_default_'.$i.'">'.$GLOBALS['TL_LANG'][$this->strTable]['opDefault'].'</label></td>
      <td><input type="checkbox" name="'.$this->strId.'['.$i.'][group]" id="'.$this->strId.'_group_'.$i.'" class="fw_checkbox" value="1"'.($this->varValue[$i]['group'] ? ' checked="checked"' : '').' /> <label for="'.$this->strId.'_group_'.$i.'">'.$GLOBALS['TL_LANG'][$this->strTable]['opGroup'].'</label></td>';


          // Add row buttons
          $return .= '
          <td>';

          foreach ($arrButtons as $button)
          {
            if ($button == 'drag')
            {
              $return .= ' <button type="button" class="drag-handle" title="' . \StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['move']) . '">' . \Image::getHtml('drag.svg') . '</button>';
            }
            else
            {
              $return .= ' <button type="button" data-command="' . $button . '" title="' . \StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['ow_'.$button]) . '">' . \Image::getHtml($button.'.svg') . '</button>';
            }
          }

          $return .= '</td>
          </tr>';
        }

        return $return.'

      </tbody>
      </table>
      <script>Backend.optionsWizard("ctrl_'.$this->strId.'")</script>';
    }
}
