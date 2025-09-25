function cerrarSesion(){
    Swal.fire({
        title: "CERRAR SESIÓN",
        text: "ESTAS DE SEGURO DE CERRAR TU CUENTA!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "SI, SALIR!",
        cancelButtonText: "NO, QUEDARME!"
      }).then((result) => {
        if (result.isConfirmed) {
            window.location = base_url + 'dashboard/salir';
        }
      });
}