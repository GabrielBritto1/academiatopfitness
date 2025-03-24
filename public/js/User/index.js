function confirmarExclusao(event, formulario) {
    event.preventDefault();

    Swal.fire({
        title: 'Tem certeza?',
        text: "Esta ação não poderá ser revertida!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processando...',
                text: 'Aguarde enquanto o usuário é excluído.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            formulario.submit();
        }
    });
}

// loadPrincipal()

// LISTAR USUÁRIOS
// function loadPrincipal() {
//     let tabela = $('#list').closest('table');
//     let numColunas = tabela.find('thead tr th').length;
//     let tableLoad = `<tr><td colspan="${numColunas}" class="text-center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></td></tr>`;
//     $(`#list`).html(tableLoad);

//     $.get(window.location.origin + "/user/list", {
//         // status_filter : $("#status_filter option:selected").val(),
//     })
//         .then(function (data) {
//             if (data.status == "success") {

//                 $("#list").html(``);

//                 console.log(data.data.data)

//                 if (data.data.data.length > 0) {
//                     data.data.data.forEach(item => {

//                         $("#list").append(`
//                         <tr>
//                             <td class="align-middle">${item.id}</td>
//                             <td class="align-middle">${item.name}</td>
//                             <td class="align-middle">${item.email}</td>
//                             <td class="align-middle">${item.name}</td>
//                             <td class="align-middle">
//                                 <a href="/user/${item.id}/edit"><i class="fas fa-edit"></i></a>
//                                 <i class="fas fa-eye"></i>
//                                 <i class="fas fa-trash"></i>
//                             </td>
//                         </tr>
//                     `);
//                     });

//                 } else {

//                     let colSpan = $("#isAdmin").val() === "1" ? '4' : '3';

//                     $("#list").append(`
//                     <tr>
//                         <td class="align-middle text-center" colspan="${numColunas}">Nenhum registro encontrado</td>
//                     </tr>
//                 `);
//                 }

//             } else if (data.status == "error") {
//                 showError(data.message)
//             }
//         })
//         .catch(function (data) {
//             showError(data.responseJSON.message)
//         });
// }