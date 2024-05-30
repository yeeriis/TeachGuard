toastr.options = {
    "positionClass": "toast-bottom-right",
    "timeOut": "5000",
    "closeButton": true,
    "progressBar": true
};

document.addEventListener('DOMContentLoaded', () => {
    const dropAreaAules = document.getElementById('drop-area-aules');
    const fileInputAules = document.getElementById('file-input-aules');

    function handleFileAules(file) {
        if (file.type === 'text/plain') {
            readFileContentAules(file);
        } else {
            alert('Si us plau, selecciona un arxiu TXT.');
        }
    }

    function readFileContentAules(file) {
        const reader = new FileReader();
        reader.onload = () => {
            const content = reader.result;
            processTxtDataAules(content);
        };
        reader.readAsText(file);
    }

    function processTxtDataAules(content) {
        const lines = content.split('\n');
        const tableBody = document.getElementById('table-body-aules');
        tableBody.innerHTML = '';

        const promises = [];

        lines.forEach((line) => {
            const matches = line.match(/"([^"]+)"/g);
            if (matches && matches.length >= 2) {
                const nombre_aula = matches[0].replace(/"/g, '');
                const nombre_curso = matches[1].replace(/"/g, '');
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${nombre_aula}</td>
                    <td>${nombre_curso}</td>
                `;
                tableBody.appendChild(row);
                promises.push(sendDataToServerAules(nombre_aula, nombre_curso));
            }
        });

        Promise.all(promises)
            .then(() => {
                toastr.success('Totes les dades han estat pujades correctament.');
            })
            .catch((error) => {
                toastr.error('Error al enviar les dades al servidor.');
            });
    }

    function sendDataToServerAules(nombre_aula, nombre_curso) {
        const formData = new FormData();
        formData.append('nombre_aula', nombre_aula);
        formData.append('nombre_curso', nombre_curso);

        return fetch('views/admin/processarArxiu.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al enviar les dades al servidor.');
                }
                return response.json();
            })
            .then(data => {
                return data;
            });
    }

    dropAreaAules.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropAreaAules.classList.add('drag-over');
    });

    dropAreaAules.addEventListener('dragleave', () => {
        dropAreaAules.classList.remove('drag-over');
    });

    dropAreaAules.addEventListener('drop', (event) => {
        event.preventDefault();
        dropAreaAules.classList.remove('drag-over');
        const file = event.dataTransfer.files[0];
        handleFileAules(file);
    });

    fileInputAules.addEventListener('change', () => {
        const file = fileInputAules.files[0];
        handleFileAules(file);
    });
});