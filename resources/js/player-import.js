const fileInput = document.querySelector('#athlete-import-file');
const dropzone = document.querySelector('[data-import-dropzone]');
const selectButton = document.querySelector('[data-select-import-file]');
const form = document.querySelector('[data-import-form]');

if (fileInput && dropzone && form) {
    const fields = window.playerImportFields;
    const requiredFields = new Set([
        'nama_asli',
        'nickname_game',
        'tanggal_lahir',
        'region',
        'role_utama',
        'gaya_permainan_1',
        'gaya_permainan_2',
        'targeting_urut_1',
        'targeting_urut_2',
        'targeting_urut_3',
    ]);

    const normalizeHeader = (value) => String(value ?? '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .replace(/[^a-z0-9]/g, '');

    const aliases = {
        nama_asli: ['realname', 'namaasli', 'fullname'],
        nickname_game: ['nickname', 'nicknameign', 'ign', 'gamename'],
        tanggal_lahir: ['birthdate', 'dateofbirth', 'tanggallahir', 'dob'],
        region: ['region', 'nationality', 'negara'],
        role_utama: ['roleutama1', 'mainrole', 'primaryrole'],
        role_cadangan_1: ['rolecadangan1', 'backuprole1', 'secondaryrole1'],
        role_cadangan_2: ['rolecadangan2', 'backuprole2', 'secondaryrole2'],
        gaya_permainan_1: ['gayapermainan1', 'playstyle1', 'gameplay1'],
        gaya_permainan_2: ['gayapermainan2', 'playstyle2', 'gameplay2'],
        targeting_urut_1: ['gayatargeting1', 'targeting1', 'targetingorder1'],
        targeting_urut_2: ['gayatargeting2', 'targeting2', 'targetingorder2'],
        targeting_urut_3: ['gayatargeting3', 'targeting3', 'targetingorder3'],
        deskripsi_pemain: ['deskripsi', 'description', 'bio', 'biography'],
    };

    const cellText = (value) => {
        if (value === null || value === undefined) return '';
        if (value instanceof Date) {
            return `${String(value.getDate()).padStart(2, '0')}/${String(value.getMonth() + 1).padStart(2, '0')}/${value.getFullYear()}`;
        }
        if (typeof value === 'object') {
            if (Array.isArray(value.richText)) return value.richText.map((part) => part.text).join('');
            if (value.text) return String(value.text);
            if (value.result !== undefined) return cellText(value.result);
        }
        return String(value).trim();
    };

    const parseCsv = (text) => {
        const rows = [];
        let row = [];
        let value = '';
        let quoted = false;
        const source = text.replace(/^\uFEFF/, '');
        let commas = 0;
        let semicolons = 0;
        let inHeaderQuote = false;
        for (const char of source) {
            if (char === '"') inHeaderQuote = !inHeaderQuote;
            else if (!inHeaderQuote && (char === '\n' || char === '\r')) break;
            else if (!inHeaderQuote && char === ',') commas += 1;
            else if (!inHeaderQuote && char === ';') semicolons += 1;
        }
        const delimiter = semicolons > commas ? ';' : ',';

        for (let index = 0; index < source.length; index += 1) {
            const char = source[index];
            if (char === '"') {
                if (quoted && source[index + 1] === '"') {
                    value += '"';
                    index += 1;
                } else {
                    quoted = !quoted;
                }
            } else if (char === delimiter && !quoted) {
                row.push(value);
                value = '';
            } else if ((char === '\n' || char === '\r') && !quoted) {
                if (char === '\r' && source[index + 1] === '\n') index += 1;
                row.push(value);
                if (row.some((cell) => cell.trim() !== '')) rows.push(row);
                row = [];
                value = '';
            } else {
                value += char;
            }
        }
        row.push(value);
        if (row.some((cell) => cell.trim() !== '')) rows.push(row);
        if (quoted) throw new Error('CSV memiliki tanda kutip yang belum ditutup.');
        return rows;
    };

    const workbookRows = async (file) => {
        if (file.name.toLowerCase().endsWith('.csv')) {
            return parseCsv(await file.text());
        }

        const { default: ExcelJS } = await import('exceljs');
        const workbook = new ExcelJS.Workbook();
        await workbook.xlsx.load(await file.arrayBuffer());
        const sheet = workbook.worksheets[0];
        if (!sheet) throw new Error('Workbook tidak memiliki sheet.');

        const rows = [];
        sheet.eachRow({ includeEmpty: false }, (worksheetRow) => {
            const values = [];
            for (let column = 1; column <= sheet.columnCount; column += 1) {
                values.push(cellText(worksheetRow.getCell(column).value));
            }
            if (values.some((cell) => cell.trim() !== '')) rows.push(values);
        });
        return rows;
    };

    const makeMapping = (headers) => {
        const grid = form.querySelector('[data-mapping-grid]');
        grid.replaceChildren();
        const columnLabel = (index) => {
            let number = index + 1;
            let label = '';
            while (number > 0) {
                number -= 1;
                label = String.fromCharCode(65 + (number % 26)) + label;
                number = Math.floor(number / 26);
            }
            return label;
        };

        Object.entries(fields).forEach(([field, label]) => {
            const wrapper = document.createElement('label');
            wrapper.className = 'mapping-field';
            const title = document.createElement('span');
            title.textContent = label;
            if (requiredFields.has(field)) {
                const required = document.createElement('em');
                required.textContent = 'Wajib';
                title.append(' ', required);
            } else {
                const optional = document.createElement('small');
                optional.textContent = 'Opsional';
                title.append(' ', optional);
            }

            const select = document.createElement('select');
            select.name = `column_${field}`;
            select.dataset.mappingField = field;
            const blank = document.createElement('option');
            blank.value = '';
            blank.textContent = '— Pilih kolom —';
            select.append(blank);

            headers.forEach((header, index) => {
                const option = document.createElement('option');
                option.value = String(index);
                option.textContent = `${columnLabel(index)} · ${header || `Kolom ${index + 1}`}`;
                select.append(option);
            });

            const fieldAliases = aliases[field] ?? [normalizeHeader(label)];
            const foundIndex = headers.findIndex((header) => fieldAliases.includes(normalizeHeader(header)));
            if (foundIndex >= 0) select.value = String(foundIndex);

            wrapper.append(title, select);
            grid.append(wrapper);
        });
    };

    const checkMapping = () => {
        const selects = [...form.querySelectorAll('[data-mapping-field]')];
        const selected = selects.filter((select) => select.value !== '').map((select) => select.value);
        const duplicate = new Set(selected).size !== selected.length;
        const missing = selects.some((select) => requiredFields.has(select.dataset.mappingField) && select.value === '');
        form.querySelector('[data-preview-submit]').disabled = duplicate || missing;
    };

    const acceptFile = async (file) => {
        const status = document.querySelector('[data-import-status]');
        const statusText = document.querySelector('[data-import-file-label]');
        const submit = form.querySelector('[data-preview-submit]');
        form.hidden = true;
        submit.disabled = true;

        if (!file) return;
        const extension = file.name.split('.').pop().toLowerCase();
        if (!['csv', 'xlsx'].includes(extension) || file.size > 5 * 1024 * 1024) {
            status.textContent = 'Pilih file CSV atau XLSX dengan ukuran maksimal 5 MB.';
            status.classList.add('is-error');
            return;
        }

        status.textContent = 'Membaca dan mengenali kolom file…';
        status.classList.remove('is-error');
        try {
            const rows = await workbookRows(file);
            if (rows.length < 2) throw new Error('File harus memiliki baris header dan minimal satu baris data.');
            if (rows.length > 501 || rows[0].length > 100) throw new Error('File melebihi batas 500 baris atau 100 kolom.');
            const headers = rows[0].map((header, index) => cellText(header) || `Kolom ${index + 1}`);
            const data = rows.slice(1).map((row) => headers.map((_, index) => cellText(row[index])));
            const serializedRows = JSON.stringify(data);
            if (serializedRows.length > 6000000) throw new Error('Isi file terlalu besar untuk diproses. Kurangi jumlah atau panjang data.');
            form.querySelector('[data-import-headers]').value = JSON.stringify(headers);
            form.querySelector('[data-import-rows]').value = serializedRows;
            document.querySelector('[data-import-row-count]').textContent = `${data.length} baris terdeteksi`;
            statusText.textContent = file.name;
            status.textContent = 'File siap. Periksa pemetaan kolom sebelum melihat preview.';
            makeMapping(headers);
            checkMapping();
            form.hidden = false;
        } catch (error) {
            status.textContent = error instanceof Error ? error.message : 'File tidak dapat dibaca.';
            status.classList.add('is-error');
        }
    };

    selectButton.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', () => acceptFile(fileInput.files[0]));
    form.addEventListener('change', checkMapping);
    form.addEventListener('submit', () => {
        const mapping = Object.fromEntries([...form.querySelectorAll('[data-mapping-field]')]
            .map((select) => [select.dataset.mappingField, select.value || null]));
        form.querySelector('[data-import-mapping]').value = JSON.stringify(mapping);
    });

    ['dragenter', 'dragover'].forEach((eventName) => dropzone.addEventListener(eventName, (event) => {
        event.preventDefault();
        dropzone.classList.add('is-dragging');
    }));
    ['dragleave', 'drop'].forEach((eventName) => dropzone.addEventListener(eventName, (event) => {
        event.preventDefault();
        dropzone.classList.remove('is-dragging');
    }));
    dropzone.addEventListener('drop', (event) => acceptFile(event.dataTransfer.files[0]));
}
