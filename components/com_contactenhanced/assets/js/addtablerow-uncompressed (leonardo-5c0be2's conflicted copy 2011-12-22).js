var inject_row = function( field_id)
{
	var table 		= $(field_id+'_table_body')
	var rowCount	= $(field_id+'_row_count');
	var lastRow		= table.getLast();
	var newTR 		= lastRow.clone();
	rowCount.value++;
	newTR.id		= field_id+'_tr['+rowCount.value+']';
	newTR.toggleClass('sectiontableentry1');
	newTR.toggleClass('sectiontableentry2');
	newTR.injectInside( table );
}

var remove_row = function( field_id)
{
	var rowCount	= $(field_id+'_row_count');
	if(rowCount.value > 1){
		var lastRow		= $(field_id+'_tr['+rowCount.value+']');
		lastRow.dispose();
		rowCount.value--;
	}
}