document.addEventListener('DOMContentLoaded', function () {
    const moveLeftButtons = document.querySelectorAll('.move-left-btn');
    const moveRightButtons = document.querySelectorAll('.move-right-btn');
    const guardarButton = document.getElementById('guardarButton');
    let ausencias = [];

    moveLeftButtons.forEach(button => {
        button.addEventListener('click', function () {
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
            selectedOption.remove();
        });
    });

    moveRightButtons.forEach(button => {
        button.addEventListener('click', function () {
            const hora = this.dataset.hora;
            const faltadosList = document.querySelector(`.faltados-list[data-hora="${hora}"]`);
            const selectElement = document.querySelector(`select[data-hora="${hora}"]`);

            if (faltadosList.children.length > 0) {
                const listItem = faltadosList.lastChild;
                const professorId = listItem.dataset.professorId;
                const professorName = listItem.textContent;
                const option = document.createElement('option');

                option.value = professorId;
                option.textContent = professorName;
                selectElement.appendChild(option);
                faltadosList.removeChild(listItem);

                const index = ausencias.findIndex(ausencia => ausencia.hora === hora && ausencia.professorId === professorId);
                if (index !== -1) {
                    ausencias.splice(index, 1);
                }
            }
        });
    });

    guardarButton.addEventListener('click', function () {
        fetch('views/admin/processarAbsencia.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(ausencias)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success('Les absències han estat desades correctament.');
                    ausencias = [];
                } else {
                    toastr.error('Error al desar absències: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Error al desar absències.');
            });
    });

    function getDiaId() {
        return new Date().getDay();
    }
});
