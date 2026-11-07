<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen Tidak Ditemukan — Vessel Risk Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Manrope', sans-serif; }</style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4 text-gray-800">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg border border-gray-100 p-8 text-center">
        <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-5 border border-amber-100">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>

        <h1 class="text-xl font-bold text-gray-900 mb-2">Berkas Fisik Belum Tersedia</h1>
        
        <p class="text-sm text-gray-600 mb-4 leading-relaxed">
            Dokumen <strong class="text-gray-900 font-mono text-xs bg-gray-100 px-2 py-0.5 rounded break-all">{{ $filename }}</strong> merupakan rekaman lampiran historis yang tidak disertakan dalam arsip backup server.
        </p>

        <div class="bg-amber-50/70 border border-amber-200 rounded-xl p-3.5 mb-6 text-left text-xs text-amber-900 leading-normal">
            <span class="font-semibold block mb-1">ℹ Informasi Audit:</span>
            Metadata dokumen ini tercatat di basis data, namun berkas fisik aslinya berada di akun Google Drive eksternal atau perlu diunggah ulang melalui form pemeriksaan survei.
        </div>

        <div class="flex gap-3">
            <button onclick="window.close()" class="flex-1 py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition duration-150">
                Tutup Jendela
            </button>
            <a href="{{ url()->previous() }}" class="flex-1 py-2.5 px-4 bg-[#007bff] hover:bg-[#0069d9] text-white text-sm font-semibold rounded-lg shadow-sm transition duration-150 inline-block">
                Kembali
            </a>
        </div>
    </div>
</body>
</html>
