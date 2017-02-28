<?php
	namespace App;
	
	class Forms {
		public function addData($form_id = -1, $title = "Add Data", $extra = "") {
			$txtDebug = "Forms::addData(\$form_id) \$form_id - {$form_id}";
			//die("<pre>{$txtDebug}</pre>");
			$html = '<a class="btn btn-xs btn-alt" data-toggle="modal" data-target=".modalDataForm" onClick="launchDataModal(-1,'.$form_id.');">'.$title.'</a>';
			return $html;
		}
	}
?>