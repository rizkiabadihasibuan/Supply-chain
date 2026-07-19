/**
 * ============================================================
 * ADMIN ARTICLE MANAGEMENT – Import JS
 * resources/js/admin/articles/import.js
 * ============================================================
 */

function handleCSVFileSelect(event) {
    const file = event.target.files[0];
    if (file) {
        document.getElementById('csv-filename').textContent = file.name;
        document.getElementById('drag-drop-zone').style.display = 'none';
        document.getElementById('import-preview-section').style.display = 'block';
        document.getElementById('btn-start-import').disabled = false;
    }
}

function startImportCSV() {
    const btn = document.getElementById('btn-start-import');
    const progressBar = document.getElementById('csv-import-progress-bar');
    
    btn.disabled = true;
    btn.textContent = 'Memproses...';

    let progress = 0;
    const interval = setInterval(() => {
        progress += 10;
        progressBar.style.width = `${progress}%`;
        progressBar.setAttribute('aria-valuenow', progress);

        if (progress >= 100) {
            clearInterval(interval);
            btn.textContent = 'Impor Selesai';
            document.getElementById('import-summary-section').style.display = 'block';
            
            if (typeof ArticlesCore !== 'undefined') {
                ArticlesCore.showToast('Import berhasil dilakukan. 12 rekor ditambahkan!');
            }
        }
    }, 150);
}

function resetImportModal() {
    document.getElementById('drag-drop-zone').style.display = 'block';
    document.getElementById('import-preview-section').style.display = 'none';
    document.getElementById('import-summary-section').style.display = 'none';
    
    const progressBar = document.getElementById('csv-import-progress-bar');
    progressBar.style.width = '0%';
    
    const fileInput = document.getElementById('import-csv-file');
    fileInput.value = '';
    
    const btn = document.getElementById('btn-start-import');
    btn.disabled = true;
    btn.textContent = 'Impor Berkas';
}
