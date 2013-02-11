<?php

Configure::write('DatetimePicker.assets', true);

Configure::write('DatetimePicker.autoScript', false);

Croogo::hookHelper('*', array(
	'Form' => array(
		'className' => 'DatetimePicker.DatetimePicker',
	)
));
