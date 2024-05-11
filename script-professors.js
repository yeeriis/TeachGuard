document.addEventListener('DOMContentLoaded', () => {
    const dropAreaProfessors = document.getElementById('drop-area-professors');
    const fileInputProfessors = document.getElementById('file-input-professors');

    // Manejo de archivos de profesores
    function handleFileProfessors(file) {
        if (file.type === 'text/plain') {
            readFileContentProfessors(file);
        } else {
            alert('Por favor, selecciona un archivo TXT.');
        }
    }

    function readFileContentProfessors(file) {
        const reader = new FileReader();
        reader.onload = () => {
            const content = reader.result;
            processTxtDataProfessors(content);
        };
        reader.readAsText(file);
    }

    function parseProfessorData(line) {
        const data = line.split(',');
        const codi_professor = data[0].replace(/"/g, '');
        const nom = data[28].replace(/"/g, '');
        const cognoms = data[1].replace(/"/g, '');
        const carrec = data[23].replace(/"/g, '');
        
        return {
            codi_professor,
            nom,
            cognoms,
            carrec
        };
    }

    function processTxtDataProfessors(content) {
        const lines = content.split('\n');
        const tableBody = document.getElementById('table-body-professors');
        tableBody.innerHTML = '';

        lines.forEach((line) => {
            // Parsear línea para obtener los datos del profesor
            const data = parseProfessorData(line);
            if (data) {
                const { codi_professor, nom, cognoms, carrec } = data;
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${codi_professor}</td>
                    <td>${nom}</td>
                    <td>${cognoms}</td>
                    <td>${carrec}</td>
                `;
                tableBody.appendChild(row);
                sendDataToServerProfessors(codi_professor, nom, cognoms, carrec);
            }
        });
    }

    function sendDataToServerProfessors(codi_professor, nom, cognoms, carrec) {
        const formData = new FormData();
        formData.append('codi_professor', codi_professor);
        formData.append('nom', nom);
        formData.append('cognoms', cognoms);
        formData.append('carrec', carrec);

        fetch('views/admin/processarProfessors.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al enviar los datos de profesores al servidor.');
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos de profesores insertados correctamente:', data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    dropAreaProfessors.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropAreaProfessors.classList.add('drag-over');
    });

    dropAreaProfessors.addEventListener('dragleave', () => {
        dropAreaProfessors.classList.remove('drag-over');
    });

    dropAreaProfessors.addEventListener('drop', (event) => {
        event.preventDefault();
        dropAreaProfessors.classList.remove('drag-over');
        const file = event.dataTransfer.files[0];
        handleFileProfessors(file);
    });

    fileInputProfessors.addEventListener('change', () => {
        const file = fileInputProfessors.files[0];
        handleFileProfessors(file);
    });
});
