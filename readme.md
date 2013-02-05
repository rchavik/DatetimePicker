DatetimePicker
==============

- Activate plugin
- Add 'data-toggle' in the $options for your FormHelper::input()
  Valid values: date|time|datetime|daterange
- Cross your fingers hard and hope it works

Code:

```php

echo $this->Form->input('created', array(
	'data-toggle' => 'datetime',
));

```

This plugin includes [tarruda's datetimepicker](http://github.com/tarruda/bootstrap-datetimepicker). However, I may switch library later.  Now hoping that 
[eternicode's fork](https://github.com/eternicode/bootstrap-datepicker/issues/347) will implement a time picker functionality soon.

For range picking, this plugin uses [dangrossman's daterange picker](http://github.com/dangrossman/bootstrap-daterangepicker)
