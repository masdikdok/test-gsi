<?php
namespace App\Components;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


/**
 *
 */
class Helpers
{

    function __construct()
    {
        // code...
    }

    public static function templateOutput(){
        return [
            'result' => 0,
            'alert' => [
                'type' => 'error',
                'message' => 'Request is failed!',
            ],
            'model' => [],
            'error' => []
        ];
    }

    public static function AlertConfig()
	{
		return array(
			'db.create'=>array(
				'success'=>'Model has been created',
				'error'=>'Failed to create model',
			),
			'db.delete'=>array(
				'success'=>'Model has been deleted',
				'error'=>'Failed to delete model',
			),
			'db.update'=>array(
				'success'=>'Model has been updated',
				'error'=>'Failed to update model',
			),
			'db.view'=>array(
				'success'=>'Model has been loaded',
				'error'=>'Failed to load model',
			)
		);
	}

	public static function setAlert($params)
	{
		// ovveride the message
		$config = self::AlertConfig();
		$outp = array(
            'type'=>'',
            'message'=>'',
            'timer' => isset($params['timer']) ? $params['timer'] : 1750,
            'is_html' => isset($params['is_html']) ? $params['is_html'] : false
         );

		// check if params have message
		if(array_key_exists('message', $params) && array_key_exists('type', $params))
		{
			$outp['message'] = $params['message'];
			$outp['type'] = $params['type'];
		}
		else if(array_key_exists('schema', $params) && array_key_exists('type', $params))
		{
			$outp['type'] = $params['type'];
			$outp['message'] = $config[$params['schema']][$params['type']];


			// replace
			if(array_key_exists('model', $params))
			{
				$outp['message'] = str_replace('Model', $params['model'], $outp['message']);
			}
		}

		// set the alert
		Session::flash('alert', $outp);
	}

}


?>
