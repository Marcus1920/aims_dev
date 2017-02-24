<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB as DB;
use App\FormField;

class FormsData extends Eloquent {
	protected $table    = 'forms_data';
	protected $fillable = ['id','form_id','data'];
	
	public function __construct($attr = array()) {
		$txtDebug = "FormsData(\$attr = array()) \$attr - ".print_r($attr,1);
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
		$saved = false;
		$saved = parent::save($options);
		return $saved;
	}
}