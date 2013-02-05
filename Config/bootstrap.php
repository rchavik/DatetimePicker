<?php

//Configure::write('DatetimePicker.assets', true);

Croogo::hookHelper('*', array(
	'Form' => array(
		'className' => 'DatetimePicker.DatetimePicker',
	)
));
