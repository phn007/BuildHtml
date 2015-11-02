<?php

/*
//Usage Command
controller option [optvalue] action funciton parameter

textsite -c ddb2.ini create code param1 param2
scrapte -c csvfilename nordstrom 1-10 1-100
*/

include 'optionList.php';

class Options
{
	public $optionList; //object
	
	function __construct()
	{
		$this->optionList = new OptionList();
	}

	function verifyOptions( $controller, $input )
   	{
		//Input
		$inputAction   = $this->getInputAction( $input );
		$inputFunction = $this->getInputFunctions( $input );
		$inputOptions  = $this->getInputOptions( $input );
		$inputParams   = $this->getInputParameters( $input );
		$this->printInputActionFalse( $inputAction );
		$this->printInputFuntionFalse( $inputFunction );

		//ตรวจสอบว่า class OptionList มีการกำหนด method ที่มีชื่อตรงกับ $controller หรือเปล่า
		$this->checkOptionListMethodExist( $controller );
		
		//ดึงรายการ optionlist ของ method ที่มีชื่อตรงกับ $controller
		$controllerOptionList = $this->getControllerOptionList( $controller, $inputAction );

		//ตรวจสอบว่ามีชื่อ action อยู่ใน Controller Option List หรือเปล่า
		$this->checkActionExistInOptionList( $inputAction, $controllerOptionList );
		
		//Option from OptionList Class
		//ดึงรายการ function List จาก OptionList ที่ match กับ input action
		$actionFunctionList = $this->getActionFunctionList( $controllerOptionList, $inputAction );
		
		//Next Step : verify parameter from actionFunctionList
		$params = $this->parameterOfFuncion( $actionFunctionList, $inputFunction, $inputParams );

		$result = array(
			'controller' => $controller,
			'action' => $inputAction,
			'function'  => $inputFunction,
			'params' => $params,
			'options' => $inputOptions,
		);
		return $result;
   	}
	
	function parameterOfFuncion( $actionFunctionList, $inputFunction, $inputParams )
	{
		$params = null;
		if ( isset( $actionFunctionList[ $inputFunction ] ) )
			$functionList = $actionFunctionList[ $inputFunction ];
		elseif ( isset( $actionFunctionList['*'] ) )
			$functionList = $actionFunctionList['*'];

		$i = 0;
		foreach ( $functionList as $item ) {
			if ( isset( $inputParams[$i]) )
				$params[$item] = $inputParams[$i];
			$i++;
		}
		return $params;
	}
	
	function printInputActionFalse( $inputAction )
	{
		if ( false === $inputAction )
			die( "Required Action\n" );
	}
	
	function printInputFuntionFalse( $inputFunction )
	{
		if ( false === $inputFunction )
			die( "Required Function\n" );
	}
	
	function checkActionExistInOptionList( $action, $controllerOptionList )
	{
		$actionKeys = array_keys( $controllerOptionList );
		if ( ! in_array( $action, $actionKeys ) )
			die( "Input action not found!!!\n" );
	}
	
	function checkOptionListMethodExist( $controller )
	{
		if ( ! method_exists( $this->optionList, $controller ) )
			die( "Not defined option list method\n" );
	}
	
	function getActionFunctionList( $controllerOptionList, $action )
	{	
		return $controllerOptionList[$action]['functions'];
	}
	
	function getControllerOptionList( $controller, $action )
	{
		return $this->optionList->$controller();
	}
	
	function getInputAction( $input )
	{
		$result = false;
		if ( isset( $input[1][0] ) )
			$result = $input[1][0];
		return $result;
	}
	
	function getInputFunctions( $input )
	{
		$result = false;
		if ( isset( $input[1][1] ) )
			$result = $input[1][1];
		return $result;
	}
	
	function getInputOptions( $input )
	{
		return $input[0];
	}
	
	function getInputParameters( $input )
	{
		$result = null;
		if ( !empty( $input[1] ) )
		{
			unset( $input[1][0] );
			unset( $input[1][1]);
			
			//reset array index
			$result = array_values( $input[1] );
			
		}
		return $result;
	}

	/**
	 * Version 1
	 */
	function get( $controller, $action, $options )
	{
		if ( ! isset( $options[1][1] ) )
			$options[1][1] = null;

		$params = array(
			'function' => $options[1][0],
			'arg' => $options[1][1],
		);

		return array(
			'controller' => $controller,
			'action' => $action,
			'param' => $params,
			'option' => $options[0]
		);
	}
}
