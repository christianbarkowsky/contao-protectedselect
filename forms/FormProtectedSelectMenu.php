<?php

/*
 * protected select
 * Adds a new formula select widget which hides the internal field values in the frontend
 *
 * @copyright  Christian Barkowsky 2015, Jan Theofel 2011-2014, ETES GmbH 2010
 * @author     Christian Barkowsky <hallo@christianbarkowsky.de>
 * @author     Jan Theofel <jan@theofel.de>
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

 
/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


class FormProtectedSelectMenu extends \FormSelectMenu
{
	
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
		switch( $strKey )
		{				
			case 'value':
				$this->arrOptions = deserialize($this->protectedOptions, true);
				
				if (is_array($this->varValue))
				{
					$arrValues = array();
					foreach( $this->varValue as $k => $value )
					{
						foreach( $this->arrOptions as $option )
						{
							if ($option['reference'] == $value)
							{
								$arrValues[$k] = $option['value'];
								continue(2);
							}
						}
					}
					return $arrValues;
				}

				foreach( $this->arrOptions as $option )
				{
					if ($option['reference'] == $this->varValue)
					{
						return $option['value'];
					}
				}
				break;
				
			default:
				return parent::__get($strKey);
		}
	}
	
	
	/**
	 * Check for a valid option
	 */
	/**/
	public function validate()
	{	
		$varValue = deserialize($this->getPost($this->strName));
		
		if ($varValue != '' && !$this->isValidOption($varValue))
		{
			$this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['invalid'], $varValue));
		}
		
		$this->varValue = $varValue;
	}
	
	
	protected function isValidOption($varInput)
	{
		$protectedOptions = deserialize($this->protectedOptions, true);
		
		foreach($protectedOptions as $k => $option)
		{
			if($varInput == $option['reference'])
			{
				return true;
			}
		}
		
		return false;
	}
	/**/
	
	
	/**
	 * Generate the widget and return it as string
	 */
	public function generate()
	{		
		$this->arrOptions = deserialize($this->protectedOptions, true);
		
		if (!is_array($this->varValue) && !strlen($this->varValue) && isset($_GET[$this->strName]))
		{
			$this->varValue = \Input::get($this->strName);
		}
		
		$arrOptions = $this->arrOptions;
		
		foreach( $this->arrOptions as $k => $option )
		{
			$this->arrOptions[$k]['value'] = $option['reference'];
		}
		
		$strBuffer = parent::generate();
		
		$this->arrOptions = $arrOptions;
		
		return $strBuffer;
	}
	
	
	/**
	 * Parse the template file and return it as string
	 */
	public function parse($arrAttributes=null)
	{
		dump(deserialize($this->protectedOptions, true));
		
		$this->arrOptions = deserialize($this->protectedOptions, true);
		
		return parent::parse($arrAttributes);
	}
}
