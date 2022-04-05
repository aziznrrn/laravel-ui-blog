const generateDatatable = (options) => {
    const table = options.table;
    table.DataTable().clear();
    table.DataTable().destroy();
    table.DataTable({
        stateSave: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        processing: true,
        ajax: options.ajax,
        columns: options.columns,
        columnDefs: options.columnDefs,
        initComplete: options.initComplete
    })
}
