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
                .then(response => response.text())
                .then(data => {
                    
                    document.getElementById('uploadResult').innerHTML = data;

                })
                .catch(error => {
                    console.error(error);
                    alert('Upload failed');
                });

        });
    </script>
@endsection
