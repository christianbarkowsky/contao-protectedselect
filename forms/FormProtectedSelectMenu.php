<?php

/*
 * FormProtectedSelectMenu
 *
 * PHP version 5
 * @copyright  Christian Barkowsky 2014-2015
 * @copyright  Jan Theofel 2011-2013, ETES GmbH 2010
 * @author     Christian Barkowsky <hallo@christianbarkowsky.de>
 * @author     Jan Theofel <jan@theofel.de>
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    LGPL-3.0+
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
	
	
	public function parse($arrAttributes=null)
	{
		dump(deserialize($this->protectedOptions, true));
		
		$this->arrOptions = deserialize($this->protectedOptions, true);
		
		return parent::parse($arrAttributes);
	}
}
