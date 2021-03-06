<?php
    Security::init();

    $list = new listClass();
    $list->title = 'Gifted Program';
    $list->showSearchFields = true;
	$list->printable = true;

    $list->SQL = "
        SELECT stdrefid,
               " . IDEAParts::get('stdname') . " AS stdname,
	           vouname,
	           gl_code,
	           giftedprogram,
	           sc_name
	      FROM webset.vw_dmg_studentmst std
               LEFT OUTER JOIN c_manager_statedef.sc_gifted sci ON std.giftedprogram = sci.sc_code AND sci.sc_statecode = '" . VNDState::factory()->code . "'
	           " . IDEAParts::get('gradeJoin') . "
	           " . IDEAParts::get('repSchoolJoin') . "
	     WHERE std.vndrefid = VNDREFID
	     ORDER BY 2
    ";

    $list->addSearchField(FFStudentName::factory());
	$list->addSearchField(FFIDEASchool::factory(true));
    $list->addSearchField(FFSwitchAI::factory('Student Status'), "COALESCE(stdstatus, 'A')")->name('stdstatus')->value('A');
    $list->addSearchField(FFGradeLevel::factory())->sqlField('std.gl_refid');

	$list->addSearchField('Gifted Program', 'giftedprogram', 'list')
		->sql("
            SELECT sc_code, sc_code || ' - ' || sc_short_name
              FROM c_manager_statedef.sc_gifted
			 WHERE sc_statecode = '" . VNDState::factory()->code . "'
		 	 ORDER BY 2
        ");


    $list->addColumn('Student Name');
    $list->addColumn('Reporting School');
    $list->addColumn('Grade');
    $list->addColumn('Code');
    $list->addColumn('Gifted Program');

    $list->printList();

?>
