(function() {
    const tagsInput = document.querySelector('#tags_input');

    if(tagsInput) {

        const tagsDiv = document.querySelector('#tags');
        const tagsInputHidden = document.querySelector('[name="tags"]');

        // Array que contendrá los tags y se actualizará según las acciones del usuario
        let tags = [];

        // Recuperar del input oculto
        if(tagsInputHidden.value !== '') {
            tags = tagsInputHidden.value.split(',');
            mostrarTags();
        }


        // Escuchar los cambios en el input
        tagsInput.addEventListener('keypress', guardarTag);

        function guardarTag(e) {
            // Si el keyCode es el de ","
            if(e.keyCode === 44){

                // Previene que aparezca la "," al ingresar un nuevo tag
                e.preventDefault();
                
                // Evita que se ingrese un valor vacio o repetido al Array de tags
                if(e.target.value.trim() === '' || e.target.value.length < 1 || repeatedTag(e.target.value)){

                    e.target.value = '';
                    return
                }

                // Actualiza el Array con lo nuevo
                tags = [...tags, e.target.value.trim()]       
                tagsInput.value = '';

                mostrarTags();
            }
        }

        function mostrarTags() {
            tagsDiv.textContent = '';

            tags.forEach(tag => {
                const etiqueta = document.createElement('LI');
                etiqueta.classList.add('formulario__tag');
                etiqueta.textContent = tag;
                etiqueta.ondblclick = eliminarTag;
                tagsDiv.appendChild(etiqueta);
            });

            actualizarInputHidden();
        }

        function eliminarTag(e) {
            e.target.remove();

            // Actualizar el array tags con lo que se acabó de remover
            tags = tags.filter(tag => tag !== e.target.textContent);

            actualizarInputHidden();
        }

        // El input hidden contiene lo que se enviará a la DB
        function actualizarInputHidden() {
            tagsInputHidden.value = tags.toString();
        }

        function repeatedTag(needle){
            let iguales = false;
            tags.forEach(tag => {

                // Transformamos ambos a lower case y comparamos si son iguales a los que estan en el arreglo de tags
                if(tag.toLowerCase().trim() === needle.toLowerCase().trim()){
                    iguales = true;
                }

            });
            return iguales;
        }
    }
})();