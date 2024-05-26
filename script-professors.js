document.addEventListener('DOMContentLoaded', () => {
    const dropAreaProfessors = document.getElementById('drop-area-professors');
    const fileInputProfessors = document.getElementById('file-input-professors');
    let processedCount = 0;

    function handleFileProfessors(file) {
        if (file.type === 'text/plain') {
            readFileContentProfessors(file);
        } else {
            toastr.error('Si us plau, selecciona un arxiu TXT.');
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
        const codi_professor = data[0] ? data[0].replace(/"/g, '') : '';
        const nom = data[28] ? data[28].replace(/"/g, '') : '';
        const cognoms = data[1] ? data[1].replace(/"/g, '') : '';
        const carrec = data[23] ? data[23].replace(/"/g, '') : '';

        return {
            codi_professor,
            nom,
            cognoms,
            carrec
        };
    }

    function processTxtDataProfessors(content) {
        const lines = content.split('\n').filter(line => line.trim() !== '');
        const totalFiles = lines.length;
        const tableBody = document.getElementById('table-body-professors');
        tableBody.innerHTML = '';

        processedCount = 0;

        lines.forEach((line) => {
            const data = parseProfessorData(line);
            if (data.codi_professor) {
                const { codi_professor, nom, cognoms, carrec } = data;
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${codi_professor}</td>
                    <td>${nom}</td>
                    <td>${cognoms}</td>
                    <td>${carrec}</td>
                `;
                tableBody.appendChild(row);
                sendDataToServerProfessors(codi_professor, nom, cognoms, carrec, totalFiles);
            } else {
                totalFiles--;
            }
        });
    }

    function sendDataToServerProfessors(codi_professor, nom, cognoms, carrec, totalFiles) {
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
                    throw new Error('Error al enviar les dades dels professors al servidor.');
                }
                return response.json();
            })
            .then(data => {
                console.log('Dades dels professors insertades correctament:', data);
                processedCount++;
                if (processedCount === totalFiles) {
                    showToastr();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                processedCount++;
                if (processedCount === totalFiles) {
                    showToastr();
                }
            });
    }

    function showToastr() {
        toastr.success('Dades de professors pujades correctament.');
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
