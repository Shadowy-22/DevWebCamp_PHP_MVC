import Swal from 'sweetalert2'

(function(){
    let eventos = [];

    const resumen = document.querySelector('#lista-registro-resumen');
    
    if(resumen){
        const formularioRegistro = document.querySelector('#registro');
        const eventosBoton = document.querySelectorAll('.evento__agregar');
        eventosBoton.forEach(boton => boton.addEventListener('click', seleccionarEvento));
        formularioRegistro.addEventListener('submit', submitFormulario);

        mostrarEventos();

        function seleccionarEvento({target}){
            if(eventos.length < 5){
                // Deshabilitar el evento
                target.disabled = true;
                // Agregarlo al arreglo
                eventos = [...eventos, {
                    id: target.dataset.id,
                    titulo: target.parentElement.querySelector('.evento__nombre').textContent.trim()
                }]
                
                mostrarEventos();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Máximo 5 eventos por registro',
                    confirmButtonText: 'OK'
                });      
            }
        }

        function mostrarEventos() {
            // Limpiar el HTML
            limpiarEventos();
            
            if(eventos.length > 0) {
                eventos.forEach(evento => {
                    const eventoDOM = document.createElement('DIV');
                    eventoDOM.classList.add('lista-registro__evento');

                    const titulo = document.createElement('H3');
                    titulo.classList.add('lista-registro__nombre');
                    titulo.textContent = evento.titulo;

                    const botonEliminar = document.createElement('BUTTON');
                    botonEliminar.classList.add('lista-registro__eliminar');
                    botonEliminar.innerHTML = `<i class='fa-solid fa-trash'></i>`
                    botonEliminar.onclick = function() {
                        eliminarEvento(evento.id);
                    }

                    // Renderizar en el HTML
                    eventoDOM.appendChild(titulo);
                    eventoDOM.appendChild(botonEliminar);
                    resumen.appendChild(eventoDOM);
                });
            } else {
                const noRegistro = document.createElement('P');
                noRegistro.textContent = 'No hay eventos, añade hasta 5 del lado izquierdo';
                noRegistro.classList.add('lista-registro__texto');
                resumen.appendChild(noRegistro);
            }
        }

        function eliminarEvento(id) {
            eventos = eventos.filter(evento => evento.id !== id);
            const botonAgregar = document.querySelector(`[data-id="${id}"]`);
            botonAgregar.disabled = false;
            mostrarEventos();
        }

        function limpiarEventos() {
            while(resumen.firstChild){
                resumen.removeChild(resumen.firstChild);
            }
        }

        async function submitFormulario(e) {
            e.preventDefault();

            // Obtener el regalo
            const regaloId = document.querySelector('#regalo').value;
            const eventosId = eventos.map(evento => evento.id);

            if(eventosId.length === 0 || regaloId === ''){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Elige al menos un Evento y un Regalo',
                    confirmButtonText: 'OK'
                });
                return;      
            }

            // Objeto de FormData
            const datos = new FormData();
            datos.append('eventos_id', eventosId);
            datos.append('regalo_id', regaloId);

            const url = '/finalizar-registro/conferencias';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();

            if(resultado.resultado){
                Swal.fire({
                    title: 'Registro Exitoso',
                    text: 'Tus conferencias se han almacenado y tu registro fue exitoso, te esperamos en DevWebCamp',
                    icon:'success',
                    confirmButtonText: 'OK'
                }).then( () => location.href = `/boleto?id=${resultado.token}`); 
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error',
                    confirmButtonText: 'OK'
                }).then( () => location.reload() );
            }
            console.log(resultado);
        }
    }
})();