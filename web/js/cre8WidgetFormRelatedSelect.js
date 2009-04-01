function cre8WidgetFormRelatedSelectUpdateSelectBox(element_id, data, val)
{
	var obj = $(element_id);
	obj.options.length = 0;
	obj.options[0] = new Option('', '');
	var i = 1;
	data.each(function(d) {
		obj.options[i] = new Option(d.name, d.val);
		if(val != null && d.val == val) {
			obj.options[i].selected = true;
		}
		i++;
	});
}