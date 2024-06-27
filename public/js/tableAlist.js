document.addEventListener("DOMContentLoaded",function () {
    const summaryElements = document.querySelectorAll('summary');
    const downArrows = document.querySelectorAll('.iconoBajar');
    const upArrows = document.querySelectorAll('.iconoSubir');

    summaryElements.forEach((summaryElement, index) => {
      const downArrow = downArrows[index];
      const upArrow = upArrows[index];
      let isOpen = false; // Inicialmente cerrado

      summaryElement.addEventListener('click', () => {
        isOpen = !isOpen;

        if (isOpen) {
          downArrow.style.display = 'none';
          upArrow.style.display = 'inline';
        } else {
          downArrow.style.display = 'inline';
          upArrow.style.display = 'none';
        }
      });
    });
    //FUNCIÓN DE BUSQUEDA
    var tableAlist = $('#tableAlist');
    var inputBusqueda = $('#searchData');
   var filaNoResultados = $('#filaNoResultados');

  var filas = tableAlist.find('tbody tr');

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

    $('#pos,#placa,#NumeroInterno,#motorista,#fecha,#hora,#aprobado,#reponsable,#docMotorista').on('click', function (e){
        var column = $(this);
        var order = column.data('order') || 'asc';
         var indexColmn = column.index();
         var orderedRow = filas.toArray().sort(function(filaA, filaB) {
             var valueA = $(filaA).find('td').eq(indexColmn).text();
             var valueB = $(filaB).find('td').eq(indexColmn).text();
             return (order === 'asc' ? 1 : -1) * valueA.localeCompare(valueB);
        });


        filas.detach().sort(function(filaA, filaB) {
            var valueA = $(filaA).find('td').eq(indexColmn).text();
             var valueB = $(filaB).find('td').eq(indexColmn).text();
            return (order === 'asc' ? 1 : -1) * valueA.localeCompare(valueB);
        }).appendTo(tableAlist.find('tbody'));


        $('#pos,#nroInt,#placa,#conductor,#ruta,#docMotorista').not(column).data('order', null);
        $('#pos,#nroInt,#placa,#conductor,#ruta,#docMotorista').find('i').removeClass().addClass('fas fa-sort');


        column.find('i').removeClass().addClass(order === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down');

        column.data('order', order === 'asc' ? 'desc' : 'asc');

    });
    document.getElementById('desc1').addEventListener('click', function(){
        const nodata = document.getElementById('noData');
        if (nodata) {
            Swal.fire({
                position: "center",
                icon: "info",
                title: "No hay alistamientos",
                text: 'No puedes acceder a esta opción',
                showConfirmButton: false,
                timer: 2500
            });
        }else{
            const table = document.getElementById('tableAlist');
            const clonedTable = table.cloneNode(true);
            clonedTable.querySelectorAll('tfoot').forEach(footer => footer.remove());
            const columnsToRemove = ['pos', 'hora', 'view', 'delete'];

            clonedTable.querySelectorAll('tr').forEach(row => {
                Array.from(row.children).forEach(cell => {
                    const columnName = cell.id;
                    if (columnsToRemove.includes(columnName)) {
                        row.removeChild(cell);
                    }
                });
            });

            clonedTable.querySelectorAll('td.delTd').forEach(td => {
                td.remove();
            });

            // Ajustar las fechas
            clonedTable.querySelectorAll('td.dateTd').forEach(td => {
                const dateStr = td.innerText;
                const adjustedDate = new Date(dateStr);

                // Formatear la fecha como YYYY-MM-DD directamente en lugar de ajustar la zona horaria
                const formattedDate = adjustedDate.toISOString().split('T')[0];
                td.innerText = formattedDate;
            });

            // Crear una hoja de trabajo
            const ws = XLSX.utils.table_to_sheet(clonedTable, { raw: true });

            // Crear un libro de trabajo
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Sheet 1');

            // Descargar el libro de trabajo como archivo Excel
            XLSX.writeFile(wb, 'Alistamiento.xlsx');
        }

    })
    $(document).on('click', '#deleteTrash', function (e) {
        var interno = $(this).data('nro-interno')
        var id = $(this).data('alist-id')
        Swal.fire({
             title: '¿Estás seguro?',
             html: 'Se eliminará Alistamiiento del Interno  <strong>'+ interno +'</strong>, Esta acción no se puede revertir',
             icon: 'warning',
             showCancelButton: true,
             confirmButtonColor: '#d33',
             cancelButtonColor: '#3085d6',
             confirmButtonText: 'Sí, eliminarlo',
             cancelButtonText: 'Cancelar',
         }).then((result)=> {
             if (result.isConfirmed) {
                 $.ajax({
                     type: "DELETE",
                     url: "/transultana/delete/Alist/" +id,
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     },
                     success: function (response) {
                         if (response.success) {
                             Swal.fire({
                                 icon: 'success',
                                 title: 'Correcto',
                                 text: 'Alistamiento Eliminado correctamente',
                                 showConfirmButton: false,
                                 timer: 1500,
                             }).then(function () {
                                 location.reload();
                            });
                         }else {
                             Swal.fire('Error', 'Hubo un error al eliminar el Alistamiento', 'error');
                         }
                     }
                 });
             }
         })

     })
})







