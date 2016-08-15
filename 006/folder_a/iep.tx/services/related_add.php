<?php

	Security::init();

	$dskey = io::get('dskey');
	$RefID = io::geti('RefID');
	$ds = DataStorage::factory($dskey);
	$tsRefID = $ds->safeGet('tsRefID');
	$stdIEPYear = $ds->safeGet('stdIEPYear');

	$edit = new EditClass('edit1', $RefID);

	$edit->title = 'Add/Edit Related Services';

	$edit->setSourceTable('webset_tx.std_srv_related', 'refid');

	$edit->addGroup('General Information');
	$edit->addControl('Service Type', 'select')
		->sqlField('srefid')
		->name('srefid')
		->sql("
			SELECT rrefid,
				   service
			  FROM webset_tx.def_srv_related
			 WHERE (enddate IS NULL or now()< enddate)
			 ORDER BY seqnum
		")
		->req();

	$edit->addControl('Specify Service')
		->sqlField('serv_other')
		->name('serv_other')
		->showIf('srefid', db::execSQL("
                                  SELECT rrefid
                                    FROM webset_tx.def_srv_related
                                   WHERE SUBSTRING(LOWER(service), 1, 5) = 'other'
                                 ")->indexAll())
		->size(50);

	$edit->addControl('Frequency', 'select')
		->sqlField('freq')
		->name('freq')
		->sql("
			SELECT frefid,
				   frequency
			  FROM webset_tx.def_srv_frequency
			 WHERE (enddate IS NULL or now()< enddate)
			 ORDER BY seqnum
		")
		->req();

	$edit->addControl('Specify Frequency')
		->sqlField('freq_other')
		->name('freq_other')
		->showIf('freq', db::execSQL("
                                  SELECT frefid
                                    FROM webset_tx.def_srv_frequency
                                   WHERE SUBSTRING(LOWER(frequency), 1, 5) = 'other'
                                 ")->indexAll())
		->size(50);

	$edit->addControl('Duration', 'select')
		->sqlField('duration')
		->name('duration')
		->sql("
			SELECT drefid,
				   duration
			  FROM webset_tx.def_srv_duration
			 WHERE (enddate IS NULL or now()< enddate)
			 ORDER BY seqnum
		")
		->req();

	$edit->addControl('Specify Duration')
		->sqlField('duration_oth')
		->name('duration_oth')
		->showIf('duration', db::execSQL("
                                  SELECT drefid
                                    FROM webset_tx.def_srv_duration
                                   WHERE SUBSTRING(LOWER(duration), 1, 5) = 'other'
                                 ")->indexAll())
		->size(50);

	$edit->addControl('Location', 'select')
		->sqlField('loc')
		->name('loc')
		->sql("
			SELECT lrefid,
				   location
			  FROM webset_tx.def_srv_locations
			 WHERE (enddate IS NULL or now()< enddate)
			 ORDER BY seqnum
		")
		->req();

	$edit->addControl('Specify Location')
		->sqlField('loc_other')
		->name('loc_other')
		->showIf('loc', db::execSQL("
                                  SELECT lrefid
                                    FROM webset_tx.def_srv_locations
                                   WHERE SUBSTRING(LOWER(location), 1, 5) = 'other'
                                 ")->indexAll())
		->size(50);

	$edit->addGroup('Update Information', true);
	$edit->addControl('Last User', 'protected')->value(SystemCore::$userUID)->sqlField('lastuser');
	$edit->addControl('Last Update', 'protected')->value(date('m-d-Y H:i:s'))->sqlField('lastupdate');
	$edit->addControl('Student ID', 'hidden')->value($tsRefID)->sqlField('stdrefid');
	$edit->addControl('IEP Year ID', 'hidden')->value($stdIEPYear)->sqlField('iep_year');

	$edit->finishURL = CoreUtils::getURL('related.php', array('dskey' => $dskey));
	$edit->cancelURL = CoreUtils::getURL('related.php', array('dskey' => $dskey));

	$edit->printEdit();
?>
