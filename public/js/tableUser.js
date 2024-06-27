// /delete/user/{id}
 document.addEventListener('DOMContentLoaded', () => {
     $(document).on('click', '.btn-delete', function (e) {
         e.preventDefault();
         var userId = $(this).data('id');
         var nombre = $(this).data('name');
         var apellido = $(this).data('apellido');
         Swal.fire({
             title: '¿Estás seguro?',
             html: 'Se eliminará al usuario <strong>'+nombre+" "+apellido+'</strong>, Esta acción no se puede revertir',
             icon: 'warning',
             showCancelButton: true,
             confirmButtonColor: '#d33',
             cancelButtonColor: '#3085d6',
             confirmButtonText: 'Sí, eliminarlo',
             cancelButtonText: 'Cancelar',
         }).then((result) => {
             if (result.isConfirmed) {
                 $.ajax({
                     type: 'DELETE',
                     url: '/delete/user/' + userId,
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     },
                     success: function (response) {
                         if (response.success) {
                             Swal.fire({
                                 icon: 'success',
                                title: 'Correcto',
                                 text: 'Usuario Eliminado correctamente',
                                showConfirmButton: false,
                                 timer: 1500,
                             }).then(function () {
                                 location.reload();
                            });
                         } else {
                             Swal.fire('Error', 'Hubo un error al eliminar el usuario', 'error');
                         }
                    },
                });
             }
        });
    });

         var tablaUsuarios = $('#tableUsers');
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
        $('#thNumber,#thName,#thLast_name,#thUsers,#thEmail').on('click', function (e){
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
            }).appendTo(tablaUsuarios.find('tbody'));


            $('#thNumber,#thName,#thLast_name,#thUsers,#thEmail').not(column).data('order', null);
            $('#thNumber,#thName,#thLast_name,#thUsers,#thEmail').find('i').removeClass().addClass('fas fa-sort');


            column.find('i').removeClass().addClass(order === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down');

            column.data('order', order === 'asc' ? 'desc' : 'asc');
        });

 });
