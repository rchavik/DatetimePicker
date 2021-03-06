<?php

App::uses('CroogoFormHelper', 'View/Helper');

class DatetimePickerHelper extends CroogoFormHelper {

	public $helpers = array(
		'Html',
		'Time',
		'Js',
	);

	public function assets() {
		$o = array('inline' => false);
		$scripts = array(
			'DatetimePicker.bootstrap-datetimepicker',
			'DatetimePicker.date',
			'DatetimePicker.daterangepicker',
		);
		$this->_View->Html->script($scripts, $o);
		$css = array(
			'DatetimePicker.bootstrap-datetimepicker',
			'DatetimePicker.daterangepicker',
		);
		$this->_View->Html->css($css, null, $o);
	}

	public function beforeRender($viewFile) {
		parent::beforeRender($viewFile);
		if (Configure::read('DatetimePicker.assets') === true) {
			$this->assets();
		}

		if (Configure::read('DatetimePicket.autoScript') === true) {
			$s =<<<EOF
$('.input-prepend.date,.input-append.date').datetimepicker();
$('input.daterange').daterangepicker();
EOF;
			$this->_View->Js->buffer($s);
		}
	}

	protected function _datePickerOptions($fieldName, $options) {
		if (empty($options['label']) && empty($options['placeholder'])) {
			$options['placeholder'] = Inflector::humanize(__($fieldName));
		}

		if (!array_key_exists('dateFormat', $options)) {
			$options['dateFormat'] = 'YMD';
		}
		if (!array_key_exists('timeFormat', $options)) {
			$options['timeFormat'] = '24';
		}
		if ($options['dateFormat'] === false && $options['timeFormat'] === false) {
			$options['dateFormat'] = 'YMD';
			$options['timeFormat'] = '24';
			$this->log('both dateFormat and timeFormat is false. using default value.');
		}
		if (empty($options['separator'])) {
			$options['separator'] = '-';
		}

		$options['label'] = false;
		$options['type'] = 'text';

		$iconOptions = array(
			'class' => 'icon-calendar',
		);
		if (!empty($options['dateFormat'])) {
			$iconOptions['data-date-icon'] = 'icon-calendar';
		}
		if (!empty($options['timeFormat'])) {
			$iconOptions['data-time-icon'] = 'icon-time';
		}

		$icon = $this->Html->tag('i', '', $iconOptions);
		$options['between'] = $this->Html->tag('span',
			$icon,
			array(
				'class' => 'add-on',
			)
		);

		$options = $this->_dateValue($fieldName, $options);

		if (empty($options['div'])) {
			$options['div'] = 'input input-prepend date';
			unset($options['picker']);
		}
		return $options;
	}

	protected function _dateValue($fieldName, $options) {
		list($model, $field) = pluginSplit($fieldName);
		if (empty($model)) {
			$model = $this->_View->Form->defaultModel;
		}
		if (!empty($options['value'])) {
			$value = $options['value'];
		} else if (!empty($this->data[$model][$field])) {
			$value = $this->data[$model][$field];
		}
		if (!empty($value)) {
			$format = $jsFormat = '';
			$formats = $jsFormats = array();
			$formatArr = str_split(strtoupper($options['dateFormat']));
			foreach ($formatArr as $c) {
				switch ($c) {
					case 'Y':
						$formats[] = 'Y';
						$jsFormats[] = 'yyyy';
					break;
					case 'M':
						$formats[] = 'm';
						$jsFormats[] = 'MM';
					break;
					case 'D':
						$formats[] = 'd';
						$jsFormats[] = 'dd';
					break;
				}
			}
			if ($formats) {
				$format = implode($options['separator'], $formats);
				$jsFormat = implode($options['separator'], $jsFormats);
			}

			if ($options['timeFormat']) {
				switch ($options['timeFormat']) {
					case '24':
						$format .= ' H:i:s';
						$jsFormat .= ' hh:mm:ss';
					break;
					case '12':
						$format .= ' h:i:s A';
						$jsFormat .= ' hh:mm:ss PP';
					break;
				}
			}
			$options['value'] = $this->Time->format(trim($format), $value);
			$options['data-format'] = trim($jsFormat);
		}
		unset($options['dateFormat']);
		unset($options['timeFormat']);
		unset($options['separator']);
		return $options;
	}

	protected function _dateRangePickerOptions($fieldName, $options) {
		if (empty($options['class'])) {
			$options['class'] = 'daterange';
		} else {
			$options['class'] .= ' daterange';
		}
		$options['type'] = 'text';
		return $options;
	}

	public function input($fieldName, $options = array()) {
		$toggle = null;
		if (isset($options['data-toggle'])) {
			$toggle = $options['data-toggle'];
		}

		switch ($toggle) {
			case 'date':
			case 'time':
			case 'datetime':
				$options = $this->_datePickerOptions($fieldName, $options);
			break;

			case 'daterange':
				$options = $this->_dateRangePickerOptions($fieldName, $options);
			break;
		}

		return parent::input($fieldName, $options);
	}

}
