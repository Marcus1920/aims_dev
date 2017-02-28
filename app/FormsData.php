<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB as DB;
use App\Http\Controllers\DatabaseController AS DbController;
use App\FormField;

class FormsData extends Eloquent {
	protected $table    = 'forms_data';
	protected $fillable = ['id','form_id','data', 'table'];
	
	public function __construct($attr = array()) {
		$txtDebug = "FormsData(\$attr = array()) \$attr - ".print_r($attr,1);
		parent::__construct($attr);
		//die("<pre>{$txtDebug}</pre>");
	}
	
	public function getTitleAttribute($title) {
		//return "WtF!?";
		//die("getTitleAttribute(\$title) \$title - {$title}");
		return $title;
  }
	
	public static function query() {
		//die();
		return parent::query();
	}
	
	public function save(array $options = array()) {
		$txtDebug = "FormsData->save(\$option) \$options - ".print_r($options, 1);
		//$txtDebug .= ", \$this - ".print_r($this, 1);
		$attr = $this->attributesToArray();
		$data = json_decode($attr['data'], true);
		$txtDebug .= ", \$attr - ".print_r($attr, 1);
		$txtDebug .= ", \$data - ".print_r($data, 1);
		$saved = false;
		$table = $attr['table'];
		$txtDebug .= "\n  \$table - {$table}";
		if ($table == "") {
			$saved = parent::save($options);
		} else {
			$dbTable = DbController::getTable($table);
			$primary = $dbTable['primary'][0];
			$txtDebug .= ", \$dbTable - ".print_r($dbTable, 1);
			$txtDebug .= "\n  \$primary - {$primary}";
			$keys = array_keys($data);
			$vals = array_values($data);
			$tosave = array($data);
			$res = DB::table($table);
			
			foreach ($data AS $key=>$val) {
				if (in_array($key, $dbTable['primary'])) continue;
				$res->where($key, "=",$val);
			}
			/*if ($data['id'] == -1) $saved = DB::table($table)->insert($data);
			else $saved = DB::table($table)->update($data);*/
			
			//$txtDebug .= ", \$entry - ".print_r($entry, 1);
			$txtDebug .= ", \$res - ".print_r($res->toSql(), 1).", count - ".$res->count();
			$txtDebug .= "\n  bindings - ".print_r($res->getBindings(), 1);
			$item = null;
			
			if ($res->count() == 0) {
				//$item = $res->first();
			} else {
				
			}
			
			if ($data[$primary] == -1) {
				if ($res->count() == 0) $saved = DB::table($table)->insert($data);
			} else {
				
			}
			
			$txtDebug .= "\n  \$item - {$item}";
		}
		$txtDebug .= "\n  \$saved - ".var_export($saved, true);
		//die("<pre>{$txtDebug}</pre>");
		
		return $saved;
	}
}