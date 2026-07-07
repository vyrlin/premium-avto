$(document).ready(function() {
	// DataTable
	var dtOptions = {"iDisplayLength": 25, "paging": true, "pagingType": "full", "aaSorting": [], "sDom": '<"top">rt<"bottom"ip><"clear">', "language": {
	"processing": "Подождите...",
	"search": "Поиск:",
	"lengthMenu": "Показать _MENU_ записей",
	"info": "_START_ - _END_ из _TOTAL_",
	"infoEmpty": "Записи с 0 до 0 из 0 записей",
	"infoFiltered": "(отфильтровано из _MAX_)",
	"infoPostFix": "",
	"loadingRecords": "Загрузка записей...",
	"zeroRecords": "Записи отсутствуют.",
	"emptyTable": "В таблице отсутствуют данные",
	"paginate": { "first": "◀", "previous": "◁", "next": "▷", "last": "▶" }
	} };
	var table1 = $('#dtTable1').DataTable(dtOptions);
	var table2 = $('#dtTable2').DataTable(dtOptions);
	var table3 = $('#dtTable3').DataTable(dtOptions);
/*	// Apply the search
	table.columns().every( function () {
        var that = this;
         $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } ); */
} ); 