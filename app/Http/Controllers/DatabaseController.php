<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB as DB;

class DatabaseController extends Controller {
	static function getTable($name) {
		$txtDebug = "DatabaseController::getTable()";
		$tables = self::getTables();
		$table = "";
		foreach ($tables AS $table_tmp) if ($table_tmp['name'] == $name) $table = $table_tmp;
		$txtDebug .= "\n\$table - ".print_r($table,1);
		//die("<pre>{$txtDebug}</pre>");
		return $table;
	}
	
	static public function getTables($basic = false) {
		$txtDebug = "DatabaseController->getTables()";
		$tables = [];
		$schema = \DB::getDoctrineSchemaManager();
		$tables_tmp = $schema->listTables();
		foreach ($tables_tmp AS $table_tmp) {
			if ($basic) $tables[$table_tmp->getName()] = $table_tmp->getName();
			else {
				$table = array('name'=>$table_tmp->getName(), 'columns'=>array(), 'primary'=>"");
				if ($table_tmp->hasPrimaryKey()) $table['primary'] = $table_tmp->getPrimaryKey()->getColumns();
				foreach ($table_tmp->getColumns() AS $col_tmp) {
					$col = $col_tmp->toArray();
					$col['type'] = $col['type']->getName();
					//$tables[$table->getName()][] = 
					//$table['columns'][] = $col;
					$table['columns'][] = $col;
				}
				$tables[] = $table;
			}
		}
		$txtDebug .= "\n\$tables - ".print_r($tables,1);
		//die("<pre>{$txtDebug}</pre>");
		return $tables;
	}
}  
?>
