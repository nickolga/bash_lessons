<?php

	function savePart($RefID, &$data, $info) {
		$dskey = $info['dskey'];
		$ds = DataStorage::factory($dskey);
		$tsRefID = $ds->safeGet('tsRefID');
		$stdIEPYear = $ds->safeGet('stdIEPYear');

		$accs = db::execSQL("
			SELECT sta.accrefid,
				   accdesc,
				   acccat
			  FROM webset.statedef_aa_acc AS sta
			 WHERE enddate IS NULL OR NOW ()< enddate
		")->assocAll();
		db::execSQL("
			DELETE FROM webset.std_form_d_acc
			 WHERE syrefid = $stdIEPYear
			       AND stdrefid = $tsRefID
		");
		foreach ($accs AS $acc) {
			if (io::exists('acc_' . $acc['accrefid']) && io::post('acc_' . $acc['accrefid']) != "") {
				DBImportRecord::factory('webset.std_form_d_acc', 'refid')
					->key('accrefid', $acc['accrefid'])
					->key('syrefid', $stdIEPYear)
					->key('stdrefid', $tsRefID)
					->set('acc_subjects', io::post('acc_' . $acc['accrefid']))
					->set('acc_oth', io::post('other_' . $acc['accrefid']))
					->setUpdateInformation()
					->import();
			}
		}
	}

?>