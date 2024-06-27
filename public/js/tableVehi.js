document.addEventListener("DOMContentLoaded", () => {
    var tablaUsuarios = $('#tableVehi');
    var inputBusqueda = $('#searchData');
    var filaNoResultados = $('#filaNoResultados');

    var filas = tablaUsuarios.find('tbody tr');

    inputBusqueda.on('input', function () {
        var valorBusqueda = inputBusqueda.val().toLowerCase();

        var filasFiltradas = filas.filter(function () {
            return $(this).text().toLowerCase().indexOf(valorBusqueda) > -1;
        });

        filaNoResultados.toggle(filasFiltradas.length === 0);

        filas.hide();
        filasFiltradas.show();
    });
    $('form').submit(function (e) {
        e.preventDefault();
    });

    $('#pos,#nroInt,#placa,#conductor,#ruta,#motorista').on('click', function (e) {
        var column = $(this);
        var order = column.data('order') || 'asc';
        var indexColmn = column.index();
        var orderedRow = filas.toArray().sort(function (filaA, filaB) {
            var valueA = $(filaA).find('td').eq(indexColmn).text();
            var valueB = $(filaB).find('td').eq(indexColmn).text();
            return (order === 'asc' ? 1 : -1) * valueA.localeCompare(valueB);
        });


        filas.detach().sort(function (filaA, filaB) {
            var valueA = $(filaA).find('td').eq(indexColmn).text();
            var valueB = $(filaB).find('td').eq(indexColmn).text();
            return (order === 'asc' ? 1 : -1) * valueA.localeCompare(valueB);
        }).appendTo(tablaUsuarios.find('tbody'));


        $('#pos,#nroInt,#placa,#conductor,#ruta').not(column).data('order', null);
        $('#pos,#nroInt,#placa,#conductor,#ruta').find('i').removeClass().addClass('fas fa-sort');


        column.find('i').removeClass().addClass(order === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down');

        column.data('order', order === 'asc' ? 'desc' : 'asc');

    });
})


