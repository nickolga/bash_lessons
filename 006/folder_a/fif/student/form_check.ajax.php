<?php
	Security::init();

	$RefID = io::posti('RefID', true);

	$form =  db::execSQL("
        SELECT *
          FROM webset.std_fif_forms
         WHERE sfrefid = " . $RefID . "
    ")->assoc();

	$uploaded = false;
	if ($form['uploaded_filename'] != '' && $form['uploaded_content'] != '') {
		$uploaded = true;
	}

	io::ajax('RefID', $RefID);
	io::ajax('uploaded', $uploaded);
?>