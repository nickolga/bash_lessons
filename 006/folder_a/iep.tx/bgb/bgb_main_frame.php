<?php
	Security::init();
	$dskey = io::get('dskey');
	$ds = DataStorage::factory($dskey);
	$esy = io::get('ESY');

	$tabs = new UITabs('tabs');
	$tabs->addTab('Standards Based')->url(CoreUtils::getURL('std_main.php', $_GET));
	$tabs->addTab('Regular Goals')->url(CoreUtils::getURL('bgb_main.php', $_GET));

	print $tabs->toHTML();
	print FFInput::factory()
			->name('screenURL')
			->value(CoreUtils::getURL($ds->safeGet('screenURL'), array('dskey' => $dskey)))
			->hide()
			->toHTML();
?>
<script type="text/javascript">
		function switchTab(id) {
			var tab1 = UITabs.get('tabs');
			if (id >= 0) {
				tab1.switchTab(id);
			} else {
				api.goto($('#screenURL').val());
			}
		}
</script>
