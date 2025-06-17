<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                    Jumlah Peserta Terdaftar per Kursus
                </h2>

                <table class="table-auto w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Nama Kursus</th>
                            <th class="py-3 px-6 text-left">Instruktur</th>
                            <th class="py-3 px-6 text-center">Jumlah Peserta</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 dark:text-gray-400 text-sm font-light">
                        @foreach($courses as $course)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $course->nama_kursus }}</td>
                                <td class="py-3 px-6 text-left">{{ $course->instruktur->nama ?? 'N/A' }}</td>
                                <td class="py-3 px-6 text-center">{{ $course->pendaftaran_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>