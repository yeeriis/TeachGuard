document.addEventListener('DOMContentLoaded', () => {
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('file-input');
    const submitBtn = document.getElementById('submit-btn');

    function handleFile(file) {
        if (file.type === 'text/plain') {
            readFileContent(file);
        } else {
            alert('Por favor, selecciona un archivo TXT.');
        }
    }

    function readFileContent(file) {
        const reader = new FileReader();
        reader.onload = () => {
            const content = reader.result;
            processTxtData(content);
        };
        reader.readAsText(file);
    }

    function processTxtData(content) {
        const lines = content.split('\n');
        const tableBody = document.getElementById('table-body');
        tableBody.innerHTML = '';
    
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
                sendDataToServer(nombre_aula, nombre_curso);
            }
        });
    }

    function sendDataToServer(nombre_aula, nombre_curso) {
        const formData = new FormData();
        formData.append('nombre_aula', nombre_aula);
        formData.append('nombre_curso', nombre_curso);
    
        fetch('views/admin/processarArxiu.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al enviar los datos al servidor.');
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos insertados correctamente:', data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    dropArea.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropArea.classList.add('drag-over');
    });

    dropArea.addEventListener('dragleave', () => {
        dropArea.classList.remove('drag-over');
    });

    dropArea.addEventListener('drop', (event) => {
        event.preventDefault();
        dropArea.classList.remove('drag-over');
        const file = event.dataTransfer.files[0];
        handleFile(file);
    });

    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        handleFile(file);
    });
});
