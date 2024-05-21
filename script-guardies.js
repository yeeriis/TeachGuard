document.addEventListener('DOMContentLoaded', function() {
    const moveLeftButtons = document.querySelectorAll('.move-left-btn');
    const moveRightButtons = document.querySelectorAll('.move-right-btn');
    const guardarButton = document.getElementById('guardarButton');
    let ausencias = [];

    moveLeftButtons.forEach(button => {
        button.addEventListener('click', function() {
            const hora = this.dataset.hora;
            const selectElement = document.querySelector(`select[data-hora="${hora}"]`);
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const professorId = selectedOption.value;
            const professorName = selectedOption.text;

            const faltadosList = document.querySelector(`.faltados-list[data-hora="${hora}"]`);
            const listItem = document.createElement('li');
            listItem.textContent = professorName;
            listItem.dataset.professorId = professorId;
            faltadosList.appendChild(listItem);

            ausencias.push({ diaId: getDiaId(), hora: hora, professorId: professorId });

            // Eliminar el profesor seleccionado del desplegable
            selectedOption.remove();
        });
    });

    moveRightButtons.forEach(button => {
        button.addEventListener('click', function() {
            const hora = this.dataset.hora;
            const faltadosList = document.querySelector(`.faltados-list[data-hora="${hora}"]`);
            const selectElement = document.querySelector(`select[data-hora="${hora}"]`);

            if (faltadosList.children.length > 0) {
                // Mover el último profesor de "Professors Absents" al desplegable
                const listItem = faltadosList.lastChild;
                const professorId = listItem.dataset.professorId;
                const professorName = listItem.textContent;

                // Crear un nuevo elemento de opción para el desplegable
                const option = document.createElement('option');
                option.value = professorId;
                option.textContent = professorName;

                // Agregar el nuevo elemento de opción al desplegable
                selectElement.appendChild(option);

                // Eliminar el profesor devuelto de "Professors Absents"
                faltadosList.removeChild(listItem);

                // Eliminar la ausencia de la lista de ausencias
                const index = ausencias.findIndex(ausencia => ausencia.hora === hora && ausencia.professorId === professorId);
                if (index !== -1) {
                    ausencias.splice(index, 1);
                }
            }
        });
    });

    guardarButton.addEventListener('click', function() {
        console.log(ausencias);

        // Enviar las ausencias al servidor para guardarlas en la base de datos
        fetch('views/admin/processarAbsencia.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(ausencias)
        })
        .then(response => response.text()) // Cambiado a text() para depuración
        .then(text => {
            console.log('Respuesta del servidor:', text);
            const data = JSON.parse(text);
            if (data.success) {
                console.log('Ausencias guardadas correctamente');
                // Limpiar la lista de ausencias después de guardar
                ausencias = [];
            } else {
                console.error('Error al guardar ausencias:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });

    function getDiaId() {
        return new Date().getDay();
    }
});
