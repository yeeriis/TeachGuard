toastr.options = {
    "positionClass": "toast-bottom-right",
    "timeOut": "5000",
    "closeButton": true,
    "progressBar": true
};

document.addEventListener('DOMContentLoaded', () => {
    const dropAreaHorarios = document.getElementById('drop-area-horarios');
    const fileInputHorarios = document.getElementById('file-input-horarios');
    let totalFiles = 0;
    let processedFiles = 0;

    function handleFileHorarios(file) {
        if (file.type === 'text/plain') {
            readFileContentHorarios(file);
        } else {
            toastr.error('Si us plau, selecciona un arxiu TXT.');
        }
    }

    function readFileContentHorarios(file) {
        const reader = new FileReader();
        reader.onload = () => {
            const content = reader.result;
            processTxtDataHorarios(content);
        };
        reader.readAsText(file);
    }

    function parseHorarioData(line) {
        const data = line.split(',');
        const codi_horari = parseInt(data[0]);
        const classe = data[1] ? data[1].replace(/"/g, '') : '';
        const professor = data[2] ? data[2].replace(/"/g, '') : '';
        const asignatura = data[3] ? data[3].replace(/"/g, '') : '';
        const aula = data[4] ? data[4].replace(/"/g, '') : '';
        const dia = parseInt(data[5]);
        const hora = parseInt(data[6]);
        return {
            codi_horari,
            classe,
            professor,
            asignatura,
            aula,
            dia,
            hora
        };
    }

    function processTxtDataHorarios(content) {
        const lines = content.split('\n').filter(line => line.trim() !== '');
        const tableBody = document.getElementById('table-body-horarios');
        tableBody.innerHTML = '';
        totalFiles = lines.length;
        processedFiles = 0;

        lines.forEach((line) => {
            const data = parseHorarioData(line);
            if (data.codi_horari) {
                const { codi_horari, classe, professor, asignatura, aula, dia, hora } = data;
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${codi_horari}</td>
                    <td>${classe}</td>
                    <td>${professor}</td>
                    <td>${asignatura}</td>
                    <td>${aula}</td>
                    <td>${dia}</td>
                    <td>${hora}</td>
                `;
                tableBody.appendChild(row);
                sendDataToServerHorarios(codi_horari, classe, professor, asignatura, aula, dia, hora);
            } else {
                totalFiles--;
            }
        });
    }

    function sendDataToServerHorarios(codi_horari, classe, professor, asignatura, aula, dia, hora) {
        if (codi_horari && dia && hora) {
            const formData = new FormData();
            formData.append('codi_horari', codi_horari);
            formData.append('classe', classe);
            formData.append('professor', professor);
            formData.append('asignatura', asignatura);
            formData.append('aula', aula);
            formData.append('dia', dia);
            formData.append('hora', hora);

            fetch('views/admin/processarHoraris.php', {
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
                    processedFiles++;
                    if (processedFiles === totalFiles) {
                        toastr.success('Tots els horaris s\'han pujat correctament.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    processedFiles++;
                    if (processedFiles === totalFiles) {
                        toastr.error('Hi ha hagut un error en pujar alguns horaris.');
                    }
                });
        } else {
            console.warn('S\'ha omès l\'enviament de dades perquè falten camps obligatoris.');
        }
    }

    dropAreaHorarios.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropAreaHorarios.classList.add('drag-over');
    });

    dropAreaHorarios.addEventListener('dragleave', () => {
        dropAreaHorarios.classList.remove('drag-over');
    });

    dropAreaHorarios.addEventListener('drop', (event) => {
        event.preventDefault();
        dropAreaHorarios.classList.remove('drag-over');
        const file = event.dataTransfer.files[0];
        handleFileHorarios(file);
    });

    fileInputHorarios.addEventListener('change', () => {
        const file = fileInputHorarios.files[0];
        handleFileHorarios(file);
    });
});
