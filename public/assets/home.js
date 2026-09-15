(() => {
    const form = document.getElementById('upload-form');
    const button = document.getElementById('submit-button');
    const overlay = document.getElementById('processing-overlay');
    const ring = document.getElementById('progress-ring');
    const value = document.getElementById('progress-value');
    const message = document.getElementById('progress-message');
    let processingTimer = null;
    let currentProgress = 0;

    const describeFiles = (input) => {
        const output = document.querySelector(`[data-file-summary="${input.id}"]`);
        const zone = input.closest('.form-step').querySelector('.drop-zone');
        const files = Array.from(input.files ?? []);

        zone.classList.toggle('has-files', files.length > 0);

        if (files.length === 0) {
            output.textContent = input.multiple ? 'Nenhum arquivo escolhido' : 'Nenhuma planilha escolhida';
            output.className = 'upload-help';
            return;
        }

        if (input.multiple) {
            const firstNames = files.slice(0, 2).map((file) => file.name).join(', ');
            const remainder = files.length > 2 ? ` e mais ${files.length - 2}` : '';
            output.textContent = `${files.length} arquivo(s): ${firstNames}${remainder}`;
            input.setCustomValidity(files.length > 10 ? 'Selecione no máximo 10 arquivos.' : '');
        } else {
            output.textContent = files[0].name;
        }

        output.className = 'selected-files';
    };

    document.querySelectorAll('input[type="file"]').forEach((input) => {
        const zone = input.closest('.form-step').querySelector('.drop-zone');

        input.addEventListener('change', () => describeFiles(input));

        ['dragenter', 'dragover'].forEach((eventName) => {
            zone.addEventListener(eventName, (event) => {
                event.preventDefault();
                zone.classList.add('is-dragging');
            });
        });

        ['dragleave', 'drop'].forEach((eventName) => {
            zone.addEventListener(eventName, (event) => {
                event.preventDefault();
                zone.classList.remove('is-dragging');
            });
        });

        zone.addEventListener('drop', (event) => {
            const incoming = Array.from(event.dataTransfer.files ?? []);
            if (incoming.length === 0) {
                return;
            }

            const transfer = new DataTransfer();
            (input.multiple ? incoming : incoming.slice(0, 1)).forEach((file) => transfer.items.add(file));
            input.files = transfer.files;
            describeFiles(input);
        });
    });

    const updateProgress = (percentage, text) => {
        currentProgress = Math.max(currentProgress, Math.min(100, Math.round(percentage)));
        ring.style.setProperty('--progress', currentProgress);
        value.textContent = `${currentProgress}%`;
        message.textContent = text;
    };

    const showError = () => {
        if (processingTimer !== null) {
            window.clearInterval(processingTimer);
        }

        overlay.classList.remove('is-visible');
        button.disabled = false;
        currentProgress = 0;
        window.alert('Não foi possível processar os arquivos. Verifique se o servidor continua ligado e tente novamente.');
    };

    form.addEventListener('submit', (event) => {
        if (!form.checkValidity()) {
            return;
        }

        event.preventDefault();
        button.disabled = true;
        overlay.classList.add('is-visible');
        updateProgress(1, 'Preparando os arquivos...');

        const request = new XMLHttpRequest();
        request.open(form.method, form.action);

        request.upload.addEventListener('progress', (uploadEvent) => {
            if (uploadEvent.lengthComputable) {
                updateProgress((uploadEvent.loaded / uploadEvent.total) * 70, 'Enviando os arquivos...');
            }
        });

        request.upload.addEventListener('load', () => {
            updateProgress(70, 'Lendo as planilhas e comparando os nomes...');
            processingTimer = window.setInterval(() => {
                if (currentProgress < 95) {
                    updateProgress(currentProgress + 1, 'Lendo as planilhas e comparando os nomes...');
                }
            }, 450);
        });

        request.addEventListener('load', () => {
            if (processingTimer !== null) {
                window.clearInterval(processingTimer);
            }

            if (request.status < 200 || request.status >= 300) {
                showError();
                return;
            }

            updateProgress(100, 'Tudo pronto! Abrindo o resultado...');
            window.setTimeout(() => {
                document.open();
                document.write(request.responseText);
                document.close();
            }, 350);
        });

        request.addEventListener('error', showError);
        request.addEventListener('abort', showError);
        request.send(new FormData(form));
    });
})();
