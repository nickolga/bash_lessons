<?php

	Security::init();

	$dskey = io::get('dskey');
	$RefID = io::geti('RefID');
	$constr = io::get('constr');
	$ds = DataStorage::factory($dskey);
	$tsRefID = $ds->safeGet('tsRefID');
	$evalproc_id = $ds->safeGet('evalproc_id');
	$student = new IDEAStudent($tsRefID);
	$screenURL = $ds->safeGet('screenURL');

	$values = '<values>' . chr(10);
	foreach ($_POST as $key => $val) {
		if ($val != '' and substr($key, 0, 7) == 'constr_') $values .= '<value name="' . substr($key, 7, strlen($key)) . '">' . stripslashes($val) . '</value>' . chr(10);
	}
	$values .= '</values>' . chr(10);

	if (io::get('evalproc') == 'no') {
		$evalproc_value = "NULL";
	} else {
		$evalproc_value = $evalproc_id;
	}
	if (io::get('other_id') == '') {
		$other_id = 'NULL';
	} else {
		$other_id = io::get('other_id');
	}
	$RefID = db::execSQL("
		SELECT refid
		  FROM webset.std_constructions
		 WHERE stdrefid = " .  $tsRefID . "
		   AND evalproc_id " . ($evalproc_value == "NULL" ? " IS " : " = ") . $evalproc_id . "
		   AND other_id " . ($other_id == "NULL" ? " IS " : " = ") . $other_id . "
		   AND constr_id = " . io::geti("constr") . "
	")->getOne();

	if ($RefID > 0) {
		$RefID = DBImportRecord::factory('webset.std_constructions', 'refid')
			->key('refid', $RefID)
			->set('values', base64_encode($values))
			->set('lastuser', SystemCore::$userUID)
			->set('lastupdate', 'NOW()', true)
			->import()
			->recordID();
	} else {
		$RefID = DBImportRecord::factory('webset.std_constructions', 'refid')
			->set('stdrefid', $tsRefID)
			->set('evalproc_id', $evalproc_value, true)
			->set('constr_id', io::geti('constr'))
			->set('other_id', $other_id, true)
			->set('values', base64_encode($values))
			->set('lastuser', SystemCore::$userUID)
			->set('lastupdate', 'NOW()', true)
			->import()
			->recordID();
	}

	if (io::post('finishFlag') == 'no') {
		header('Location: ' . CoreUtils::getURL('./group_edit.php', array_merge($_GET, array('RefID' => $RefID))));
	} else {
		header('Location: ' . CoreUtils::getURL('./group_list.php', $_GET));
	}
?>