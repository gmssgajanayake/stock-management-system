@extends('dashboard')

@section('content-title')
    <h1 class="text-2xl font-bold mb-4 text-gray-800">
        Bulk Orders Upload
    </h1>
@endsection

@section('content')
    <div class="bg-white p-6 rounded shadow">

        <div class="mb-4">
            <a href="{{ route('orders.template') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
                Download CSV Template
            </a>
        </div>

        <form id="csvUploadForm" action="{{ route('orders.bulk-upload.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="file" name="csv_file" required class="border p-2 rounded w-full mb-4">

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                Upload CSV
            </button>

        </form>

        <div id="uploadResult" class="mt-4"></div>

    </div>
@endsection

@section('scripts')
    <script>
        let validRows = [];

        document.getElementById('csvUploadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {

                    if (data.error) {
                        document.getElementById('uploadResult').innerHTML =
                            `<div class="text-red-600">${data.error}</div>`;
                        return;
                    }

                    // store valid rows
                    validRows = data.valid;

                    renderTables({
                        valid: validRows,
                        invalid: data.invalid
                    });

                })
                .catch(error => {
                    console.error(error);
                    alert('Upload failed');
                });
        });

        document.addEventListener('click', function(e) {

            if (e.target.id === 'revalidateBtn') {

                const rows = document.querySelectorAll('#invalidTable tbody tr');

                let correctedData = [];

                rows.forEach(row => {

                    let obj = {};

                    row.querySelectorAll('input').forEach(input => {
                        obj[input.dataset.field] = input.value;
                    });

                    correctedData.push(obj);

                });

                fetch('/orders/bulk-upload/revalidate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({
                            rows: correctedData
                        })
                    })
                    .then(res => res.json())
                    .then(data => {

                        console.log(data);


                        // merge new valid rows with previous valid rows
                        validRows = [...validRows, ...data.valid];

                        renderTables({
                            valid: validRows,
                            invalid: data.invalid
                        });
                    });
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('deleteRowBtn')) {
                const row = e.target.closest('tr');
                row.remove(); // remove row from table
            }

            if (e.target.id === 'revalidateBtn') {
                const rows = document.querySelectorAll('#uploadResult tbody tr');
                let correctedData = [];

                rows.forEach(row => {
                    let obj = {};
                    row.querySelectorAll('input').forEach(input => {
                        obj[input.dataset.field] = input.value;
                    });
                    correctedData.push(obj);
                });

                fetch('/orders/bulk-upload/revalidate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({
                            rows: correctedData
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        validRows = [...validRows, ...data.valid];
                        renderTables({
                            valid: validRows,
                            invalid: data.invalid
                        });
                    });
            }
        });

        function renderTables(data) {

            const container = document.getElementById('uploadResult');
            container.innerHTML = '';

            // VALID TABLE
            if (data.valid && data.valid.length > 0) {

                let validHTML = '<h3 class="font-bold mb-2 text-green-600">Valid Rows</h3>';
                validHTML += '<table class="border w-full mb-6"><thead><tr>';

                Object.keys(data.valid[0]).forEach(col => {
                    if (col !== 'errors') {
                        validHTML += `<th class="border px-2 py-1">${col}</th>`;
                    }
                });

                validHTML += '</tr></thead><tbody>';

                data.valid.forEach(row => {

                    validHTML += '<tr>';

                    Object.keys(row).forEach(col => {

                        if (col === 'errors') return;

                        validHTML += `<td class="border px-2 py-1">${row[col]}</td>`;
                    });

                    validHTML += '</tr>';
                });

                validHTML += '</tbody></table>';

                container.innerHTML += validHTML;
            }

            // INVALID TABLE
            if (data.invalid && data.invalid.length > 0) {

                let invalidHTML = '<h3 class="font-bold mb-2 text-red-600">Invalid Rows</h3>';
                invalidHTML += '<table id="invalidTable" class="border w-full"><thead><tr>';

                Object.keys(data.invalid[0]).forEach(col => {
                    if (col !== 'errors') {
                        invalidHTML += `<th class="border px-2 py-1">${col}</th>`;
                    }
                });

                invalidHTML += '</tr></thead><tbody>';

                data.invalid.forEach((row, index) => {

                    invalidHTML += `<tr data-row="${index}">`;

                    Object.keys(row).forEach(col => {

                        if (col === 'errors') return;

                        const hasError = row.errors && Object.keys(row.errors).includes(col);
                        const errorText = hasError ? row.errors[col].join(', ') : '';

                        invalidHTML += `
                <td class="border px-2 py-1">
                    <input 
                        type="text"
                        value="${row[col]}"
                        title="${errorText}"
                        data-field="${col}"
                        class="w-full p-1 border ${hasError ? 'border-red-500 bg-red-50' : 'border-gray-300'}"
                    >
                </td>
                `;
                    });

                    // Add Delete button
                    invalidHTML += `
                    <td class="border px-2 py-1">
                        <button class="deleteRowBtn bg-red-600 text-white px-2 py-1 rounded">
                            Delete
                        </button>
                    </td>
                `;

                    invalidHTML += '</tr>';
                });

                invalidHTML += '</tbody></table>';

                invalidHTML += `
        <button id="revalidateBtn"
            class="mt-4 bg-orange-600 text-white px-4 py-2 rounded">
            Revalidate
        </button>
        `;

                container.innerHTML += invalidHTML;
            }
        }
    </script>
@endsection
