@extends('layouts.backend.main')
@section('title','Formulir')
@push('css')
<link rel="stylesheet" href="{{ asset('backend/vendor/toastr/css/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/toastr-custom.css') }}">
<link rel="stylesheet" href="{{ asset('backend/vendor/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/vendor/datepicker/jquery-ui.css') }}">
<style>
    .step {
        display: none;
    }
    .step.active {
        display: block;
    }
    .nav-pills .nav-link {
        color: #495057;
    }
    .nav-pills .nav-link.active {
        background-color: #007bff;
        color: white;
    }
    .nav-pills .nav-link.completed {
        background-color: #28a745;
        color: white;
    }
    .nav-link.completed {
    background-color: #28a745;
    color: white;
    }

    .nav-link.active {
        background-color: #007bff;
        color: white;
    }

    .is-invalid {
        border-color: red;
    }

    .button-simpan {
        background-color: #28a745;
        color: white;
        padding: 7px 10px;
        border: none;
        border-radius: 4px;
    }

    .button-simpan:hover {
        background-color: #20943b;
        color: white;
        padding: 7px 10px;
        border: none;
        border-radius: 4px;
    }

    .badge-lunas {
        background-color: #28a745;
        color: white;
        padding: 3px 5px;
        border: none;
        border-radius: 4px;
        font-size: 12px;
    }
    .table td {
        padding: 8px 10px; /* Kurangi padding horizontal */
        padding-left: 0; /* Hilangkan padding kiri di kolom pertama */
    }

    .table tr td:first-child {
        width: 25%; /* Batasi lebar kolom pertama */
        font-weight: bold;
    }

    .table tr td:last-child {
        width: 75%; /* Sisakan ruang untuk kolom kedua */
    }

    /* Atau dengan metode fixed */
    .table {
        table-layout: fixed;
        width: 100%;
    }

    .table td {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endpush
@section('content')
<div class="content-body">
    <div class="container-fluid">
            <div class="col-sm-6 p-md-0 mb-4">
                <div class="welcome-text">
                    @if (isset($mhs->status_step) == 5)
                    <h3>Pembayaran</h3>
                    @else
                    <h3>@yield('title')</h3>
                    @endif
                </div>
            </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if (optional($mhs)->status_step == 5)
                            <table class="table table-striped text-dark">
                                <tr>
                                    <td>No Pendaftaran</td>
                                    <td>: 86234627346234</td>
                                </tr>
                                <tr>
                                    <td>Nama Lengkap</td>
                                    <td>: {{ $mhs->nama ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td>Program Studi</td>
                                    <td>: {{ $prodi_studi[$prodi->program_studi] ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>: {{ $jk[$mhs->jenis_kelamin] ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td>Biaya Pendaftaran</td>
                                    <td>: 200.000,- 
                                        @if ($mhs->status_pembayaran == 1)
                                        <span class="badge-lunas">Lunas</span>
                                        @else
                                        <span class="badge badge-danger">Belum Lunas</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            @if ($mhs->status_pembayaran == 1)
                            <p class="text-primary">Silahkan download bukti pembayaran untuk proses daftar ulang</p>
                            <a href="{{ route('formulir.downloadBuktiPembayaran', $pembayaran->id) }}" class="btn btn-sm btn-warning" target="_blank"><i class="bi bi-file-earmark-arrow-down"></i> Download Bukti Pendaftaran</a>
                            @else
                            <button id="btnModalBayar" class="btn btn-sm btn-primary"><i class="bi bi-file-earmark-arrow-up"></i> Upload Bukti Pembayaran</button>
                            @endif
                        @else
                        @include('backend.mhs.step.nav')
                        <form id="casnForm" class="text-dark" enctype="multipart/form-data">
                            <!-- Step 1: Identitas Pribadi -->
                             @include('backend.mhs.step.data-pribadi')

                            <!-- Step 2: Data Keluarga -->
                            @include('backend.mhs.step.data-orang-tua')

                            <!-- Step 3: Pendidikan -->
                            @include('backend.mhs.step.data-prodi')
                            

                            <!-- Step 4: Dokumen Pendukung -->
                           @include('backend.mhs.step.data-dokumen')

                            <!-- Navigasi -->
                            <div class="mt-3">
                                <button type="button" id="prevBtn" class="btn btn-warning">Sebelumnya</button>
                                <button type="button" id="nextBtn" class="btn btn-primary">Selanjutnya</button>
                                <button type="submit" id="submitBtn" class="button-simpan" style="display:none;"><i class="bi bi-floppy"></i> Simpan</button>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('backend.mhs.components.modal-simpan-formulir')
@include('backend.mhs.components.modal-upload-bayar')
@endsection
@push('js')
<script src="{{ asset('backend/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/vendor/toastr/js/toastr.min.js') }}"></script>
<script src="{{ asset('backend/vendor/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('backend/vendor/datepicker/jquery-ui.js') }}"></script>
{{-- // Fungsi validasi step 1 --}}
<script>
    $(document).ready(function() {

        $("#date").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "1950:" + new Date().getFullYear(),
            dateFormat: 'yy-mm-dd', 
        });

        $("#date_ayah").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "1950:" + new Date().getFullYear(),
            dateFormat: 'yy-mm-dd', 
        });

        $("#date_ibu").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "1950:" + new Date().getFullYear(),
            dateFormat: 'yy-mm-dd', 
        });
        // Inisialisasi variabel dari server
        let currentStep = {{ $mhs->status_step ?? 1 }};
        const totalSteps = $('.step').length;

        // Styling untuk select2 invalid
        $('head').append(`
            <style>
                .is-invalid-select2 .select2-selection {
                    border-color: #dc3545 !important;
                }
                .select2-container.is-invalid-select2 {
                    border: 1px solid #dc3545 !important;
                    border-radius: 4px !important;
                }
            </style>
        `);

        // Inisialisasi select2
        $('.select2').select2({
            placeholder: "Pilih...",
            allowClear: true
        });

        // Fungsi untuk melakukan pengecekan NIK secara real-time
        function checkNikUniqueness(nik) {
            return new Promise((resolve, reject) => {
                // Ambil NIK asli dari data mahasiswa yang sedang diedit
                const originalNik = $('[name="id_mahasiswa"]').data('original-nik');
                
                // Jika NIK tidak berubah, langsung resolve
                if (nik === originalNik) {
                    resolve({ unique: true });
                    return;
                }

                $.ajax({
                    url: '{{ route('formulir.check.nik.checkNikUniquenessStep1') }}',
                    method: 'POST',
                    data: {
                        nik: nik,
                        id_mahasiswa: $('[name="id_mahasiswa"]').val() || null
                    },
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr) {
                        reject(xhr);
                    }
                });
            });
         }
        // Fungsi validasi form step 1
        function validateStep1() {
            let isValid = true;
            const requiredFields = [
                'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 
                'kawin', 'kewarganegaraan', 'asal_sekolah', 'alamat', 
                'provinsi', 'kabupaten', 'kecamatan', 'desa', 'kode_pos', 'no_hp',
            ];

            requiredFields.forEach(field => {
                const $field = $(`[name="${field}"]`);
                
                // Validasi untuk select2
                if ($field.hasClass('select2')) {
                    const select2Container = $field.next('.select2-container');
                    
                    if (!$field.val()) {
                        select2Container.addClass('is-invalid-select2');
                        isValid = false;
                    } else {
                        select2Container.removeClass('is-invalid-select2');
                    }
                } 
                
                // Validasi untuk input biasa
                if (!$field.val()) {
                    $field.addClass('is-invalid');
                    isValid = false;
                } else {
                    $field.removeClass('is-invalid');
                }
            });

            // Validasi khusus NIK
            const nik = $('[name="nik"]').val();
            if (!nik || nik.length !== 16) {
                $('[name="nik"]').addClass('is-invalid');
                isValid = false;
                toastr.error('NIK harus 16 digit');
            }

            return isValid;
        }

        // Fungsi untuk melakukan pengecekan NIK secara real-time
        function checkNikOrtuUniqueness(nik, jenisNik) {
        return new Promise((resolve, reject) => {

            const idOrtu = $('[name="id_ortu"]').val() || '';
            
            $.ajax({
                url: '{{ route('formulir.check.nik.ortu.uniqueness') }}',
                method: 'POST',
                data: {
                    nik: nik,
                    id_ortu: idOrtu,
                    jenis_nik: jenisNik
                },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr) {
                    reject(xhr);
                }
            });
        });
    }
        // Fungsi validasi form step 2 (Data Orang Tua)
        function validateStep2() {
            let isValid = true;
            const requiredFields = [
                'nama_ayah', 'nama_ibu', 'tempat_lahir_ayah', 'tempat_lahir_ibu', 
                'tanggal_lahir_ayah', 'tanggal_lahir_ibu', 'nik_ayah', 'nik_ibu', 
                'pendidikan_ayah', 'pendidikan_ibu', 'no_hp_ayah', 'no_hp_ibu', 
                'pekerjaan_ayah', 'pekerjaan_ibu', 'penghasilan_ayah', 'penghasilan_ibu', 
                'alamat_ortu', 'provinsi_ortu', 'kabupaten_ortu', 'kecamatan_ortu', 'desa_ortu'
            ];

            requiredFields.forEach(field => {
                const $field = $(`[name="${field}"]`);
                
                // Validasi untuk select2
                if ($field.hasClass('select2')) {
                    const select2Container = $field.next('.select2-container');
                    
                    if (!$field.val()) {
                        select2Container.addClass('is-invalid-select2');
                        isValid = false;
                    } else {
                        select2Container.removeClass('is-invalid-select2');
                    }
                } 
                
                // Validasi untuk input biasa
                if (!$field.val()) {
                    $field.addClass('is-invalid');
                    isValid = false;
                } else {
                    $field.removeClass('is-invalid');
                }
            });

            // Validasi khusus NIK Ayah
            const nikAyah = $('[name="nik_ayah"]').val();
            if (!nikAyah || nikAyah.length !== 16) {
                $('[name="nik_ayah"]').addClass('is-invalid');
                isValid = false;
                toastr.error('NIK Ayah harus 16 digit');
            } else {
                checkNikOrtuUniqueness(nikAyah, 'ayah').then(response => {
                    if (!response.unique) {
                        $('[name="nik_ayah"]').addClass('is-invalid');
                        toastr.error(response.message);
                        isValid = false;
                    }
                });
            }

            // Validasi khusus NIK Ibu
            const nikIbu = $('[name="nik_ibu"]').val();
            if (!nikIbu || nikIbu.length !== 16) {
                $('[name="nik_ibu"]').addClass('is-invalid');
                isValid = false;
                toastr.error('NIK Ibu harus 16 digit');
            } else {
                checkNikOrtuUniqueness(nikIbu, 'ibu').then(response => {
                    if (!response.unique) {
                        $('[name="nik_ibu"]').addClass('is-invalid');
                        toastr.error(response.message);
                        isValid = false;
                    }
                });
            }

            return isValid;
        }

        // Fungsi validasi form step 3 (Pilih Prodi)
        function validateStep3() {
            let isValid = true;
            const requiredFields = [
                'jenis_pendaftaran', 'program_studi', 'jenis_kelas'
            ];

            requiredFields.forEach(field => {
                const $field = $(`[name="${field}"]`);
                
                
                // Validasi untuk input biasa
                if (!$field.val()) {
                    $field.addClass('is-invalid');
                    isValid = false;
                } else {
                    $field.removeClass('is-invalid');
                }
            });

            return isValid;
        }

        // Fungsi validasi form step 4 (Dokumen)
        function validateStep4() {
            let isValid = true;
            const requiredFields = [
                'pas_foto', 'ijazah', 'kk', 'ktp', 'daftar_nilai'
            ];

            requiredFields.forEach(field => {
                const $field = $(`[name="${field}"]`);
                const fileInput = $field[0];
                
                // Cek apakah file sudah dipilih
                if (!fileInput.files.length) {
                    $field.addClass('is-invalid');
                    isValid = false;
                    toastr.error(`Silakan unggah ${field.replace('_', ' ')}`);
                } else {
                    // Tambahan validasi ukuran file
                    const maxSize = 1024 * 1024; // 1MB
                    const fileSize = fileInput.files[0].size;
                    
                    if (fileSize > maxSize) {
                        $field.addClass('is-invalid');
                        isValid = false;
                        toastr.error(`Ukuran file ${field.replace('_', ' ')} maksimal 1MB`);
                    } else {
                        $field.removeClass('is-invalid');
                    }
                }
            });

            return isValid;
        }

        // Fungsi inisialisasi step berdasarkan status
        function initializeStepNavigation() {
            // Reset navigasi
            $('#wizardNav .nav-link').removeClass('active completed disabled');

            // Aktifkan dan tandai step yang sudah dilalui
            for (let i = 1; i < currentStep; i++) {
                $(`.nav-link[data-step="${i}"]`)
                    .addClass('completed')
                    .removeClass('disabled');
            }

            // Set step saat ini aktif
            $(`.nav-link[data-step="${currentStep}"]`)
                .addClass('active')
                .removeClass('disabled');

            // Sembunyikan semua step
            $('.step').hide().removeClass('active');

            // Tampilkan step saat ini
            $(`.step[data-step="${currentStep}"]`)
                .show()
                .addClass('active');

            // Update navigasi tombol
            $('#prevBtn').toggle(currentStep > 1);
            $('#nextBtn').toggle(currentStep < totalSteps);
            $('#submitBtn').toggle(currentStep === totalSteps);

            // Nonaktifkan step yang belum bisa diakses
            $('#wizardNav .nav-link').each(function() {
                const stepNumber = parseInt($(this).data('step'));
                if (stepNumber > currentStep) {
                    $(this).addClass('disabled');
                }
            });
        }

        // Panggil fungsi inisialisasi step
        initializeStepNavigation();

        // Fungsi simpan data step 1
        function simpanDataStep1() {
            // Validasi form terlebih dahulu
            if (!validateStep1()) {
                return;
            }

            const formData = new FormData($('#casnForm')[0]);

            // Tambahkan id_mahasiswa jika sudah ada
            @if($mhs && $mhs->id)
                formData.append('id_mahasiswa', '{{ $mhs->id }}');
            @endif

            $.ajax({
                url: '{{ route('formulir.simpan.step1') }}',
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        currentStep = response.nextStep;
                        initializeStepNavigation();
                    } else {
                        // Tampilkan error dari validator
                        if (response.errors) {
                            $.each(response.errors, function(field, messages) {
                                $(`[name="${field}"]`).addClass('is-invalid');
                                // Tampilkan pesan error pertama
                                toastr.error(messages[0]);
                            });
                        } else {
                            toastr.error(response.message);
                        }
                    }
                },
                error: function(xhr) {
                    // Parsing error response untuk menangani error dari validator
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            $(`[name="${field}"]`).addClass('is-invalid');
                            toastr.error(messages[0]);
                        });
                    } else {
                        toastr.error('Terjadi kesalahan saat menyimpan data');
                    }
                }
            });
        }
        
        $('[name="nik"]').on('blur', function() {
            const nik = $(this).val();
            const originalNik = $(this).data('original-nik');
            
            if (nik && nik.length === 16) {
                // Jika NIK tidak berubah, tidak perlu validasi
                if (nik === originalNik) {
                    $(this).removeClass('is-invalid');
                    return;
                }

                checkNikUniqueness(nik).then(response => {
                    if (!response.unique) {
                        $(this).addClass('is-invalid');
                        toastr.error('NIK sudah digunakan');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
            }
        });

       
        // Event listener navigasi step
        $('#wizardNav .nav-link').click(function(e) {
            e.preventDefault();
            
            // Cek apakah nav-link disabled
            if ($(this).hasClass('disabled')) {
                toastr.warning('Lengkapi step sebelumnya terlebih dahulu');
                return;
            }

            const targetStep = parseInt($(this).data('step'));
            
            // Jika ingin pindah ke step 2
            if (targetStep === 2 && currentStep === 1) {
                if (!validateStep1()) {
                    toastr.error('Lengkapi data Identitas Pribadi terlebih dahulu');
                    return;
                }
                simpanDataStep1();
            } else {
                // Langsung pindah jika step sudah dilalui
                currentStep = targetStep;
                initializeStepNavigation();
            }
        });

        // Tombol Next
        $('#nextBtn').click(function() {
            if (currentStep === 1) {
                if (!validateStep1()) {
                    toastr.error('Lengkapi Data Identitas Pribadi terlebih dahulu');
                    return;
                }
                simpanDataStep1();
            } else if (currentStep === 2) {
                if (!validateStep2()) {
                    toastr.error('Lengkapi Data Orang Tua terlebih dahulu');
                    return;
                }
                // Simpan data step 2
                simpanDataStep2();
            } else if (currentStep === 3) {
                if (!validateStep3()) {
                    toastr.error('Lengkapi Data Program Studi terlebih dahulu');
                    return;
                }
                // Simpan data step 3
                simpanDataStep3();
            } else {
                currentStep++;
                initializeStepNavigation();
            }
        });

        // Tombol Previous
        $('#prevBtn').click(function() {
            currentStep--;
            initializeStepNavigation();
        });

        $('[name="nik_ayah"]').on('blur', function() {
            const nik = $(this).val();
            const originalNik = $(this).data('original-nik');
            
            if (nik && nik.length === 16) {
                // Jika NIK tidak berubah, tidak perlu validasi
                if (nik === originalNik) {
                    $(this).removeClass('is-invalid');
                    return;
                }

                checkNikOrtuUniqueness(nik, 'ayah').then(response => {
                    if (!response.unique) {
                        $(this).addClass('is-invalid');
                        toastr.error(response.message);
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
            }
        });

        $('[name="nik_ibu"]').on('blur', function() {
            const nik = $(this).val();
            const originalNik = $(this).data('original-nik');
            
            if (nik && nik.length === 16) {
                // Jika NIK tidak berubah, tidak perlu validasi
                if (nik === originalNik) {
                    $(this).removeClass('is-invalid');
                    return;
                }

                checkNikOrtuUniqueness(nik, 'ibu').then(response => {
                    if (!response.unique) {
                        $(this).addClass('is-invalid');
                        toastr.error(response.message);
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
            }
        });

        // Fungsi simpan data step 2
        function simpanDataStep2() {
            // Validasi form terlebih dahulu
            if (!validateStep2()) {
                return;
            }

            const formData = new FormData($('#casnForm')[0]);
            @if($ortu && $ortu->id)
                formData.append('id_ortu', '{{ $ortu->id }}');
            @endif

            $.ajax({
            url: '{{ route('formulir.simpan.step2') }}',
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    currentStep = response.nextStep;
                    initializeStepNavigation();
                } else {
                    // Tampilkan error dari validator
                    if (response.errors) {
                        $.each(response.errors, function(field, messages) {
                            $(`[name="${field}"]`).addClass('is-invalid');
                            
                            // Tampilkan pesan error untuk setiap field
                            messages.forEach(function(message) {
                                toastr.error(message);
                            });
                        });
                    } else {
                        toastr.error(response.message);
                    }
                }
            },
            error: function(xhr) {
                // Parsing error response untuk menangani error dari validator
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function(field, messages) {
                        $(`[name="${field}"]`).addClass('is-invalid');
                        
                        // Tampilkan pesan error untuk setiap field
                        messages.forEach(function(message) {
                            toastr.error(message);
                        });
                    });
                } else {
                    toastr.error('Terjadi kesalahan saat menyimpan data');
                }
            }
            });
        }
        // Event listener untuk validasi saat input berubah
        $('input, select').on('input change', function() {
            $(this).removeClass('is-invalid');
        });

        
        // Fungsi simpan data step 3
        function simpanDataStep3() {
            const formData = new FormData($('#casnForm')[0]);
            $.ajax({
                url: '{{ route('formulir.simpan.step3') }}',
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // toastr.success('Data Pilih Prodi berhasil disimpan');
                        currentStep = response.nextStep;
                        initializeStepNavigation();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan saat menyimpan data');
                }
            });
        }
        
        // Fungsi simpan data step 4
        function simpanDataStep4() {
            const formData = new FormData($('#casnForm')[0]);

            $.ajax({
                url: '{{ route('formulir.simpan.step4') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    // Disable tombol saat proses
                    $('#konfirmasiSimpanBtn').prop('disabled', true)
                        .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Data formulir berhasil disimpan');
                        currentStep = response.nextStep;
                        initializeStepNavigation();
                        $('#modalKonfirmasi').modal('hide');
                        setTimeout(() => {
                            window.location.href = '{{ route('formulir.index') }}';
                        }, 1000);
                    } else {
                        // Tampilkan error dari validator
                        if (response.errors) {
                            $.each(response.errors, function(field, messages) {
                                $(`[name="${field}"]`).addClass('is-invalid');
                                toastr.error(messages[0]);
                            });
                        } else {
                            toastr.error(response.message);
                        }
                    }
                },
                error: function(xhr) {
                    // Parsing error response untuk menangani error dari validator
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            $(`[name="${field}"]`).addClass('is-invalid');
                            toastr.error(messages[0]);
                        });
                    } else {
                        toastr.error('Terjadi kesalahan saat mengunggah dokumen');
                    }
                },
                complete: function() {
                    // Enable kembali tombol
                    $('#konfirmasiSimpanBtn').prop('disabled', false)
                        .html('Simpan');
                }
            });
        }

        
        // Tombol Submit
        $('#submitBtn').click(function(e) {
            // Mencegah submit form default
            e.preventDefault();
            
            if (currentStep === 4) {
                // Validasi client-side
                if (!validateStep4()) {
                    toastr.error('Lengkapi data Dokumen terlebih dahulu');
                    return false;
                }

                // Tampilkan modal konfirmasi
                $('#modalKonfirmasi').modal('show');
            }
            
            // Kembalikan false untuk mencegah submit
            return false;
        });

        // Pastikan form tidak submit secara default
        $('#casnForm').on('submit', function(e) {
            e.preventDefault();
            return false;
        });

        // Tombol Konfirmasi Simpan di Modal
        $('#konfirmasiSimpanBtn').click(function(e) {
            // Mencegah submit default
            e.preventDefault();
            
            // Validasi ulang sebelum simpan
            if (validateStep4()) {
                simpanDataStep4();
            }
            
            return false;
        });
    });
</script>
<script>
$(document).ready(function() {
    function initKabupaten() {
        var provinsi_id = $('#provinsi').val();
        
        // Cek apakah provinsi sudah dipilih
        if (provinsi_id) {
            $.ajax({
                url: '{{ route('formulir.getKabupaten') }}',
                type: 'POST',
                data: {
                    provinsi_id: provinsi_id
                },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    $('#kabupaten').empty().append('<option selected disabled>Pilih Kabupaten</option>');
                    
                    $.each(response, function(key, value) {
                        // Cek old value atau value yang sudah tersimpan
                        var selected = (value.id == "{{ old('kabupaten', $mhs->id_kab ?? '') }}")
                            ? 'selected' 
                            : '';
                        
                        $('#kabupaten').append(
                            '<option value="' + value.id + '" ' + selected + '>' 
                            + value.name + '</option>'
                        );
                    });

                    // Trigger change untuk mengisi kecamatan jika ada
                    $('#kabupaten').trigger('change');
                }
            });
        }
    }

    // Fungsi untuk mengisi dropdown kecamatan saat halaman dimuat
    function initKecamatan() {
        var kabupaten_id = $('#kabupaten').val();
        
        // Cek apakah kabupaten sudah dipilih
        if (kabupaten_id) {
            $.ajax({
                url: '{{ route('formulir.getKecamatan') }}',
                type: 'POST',
                data: {
                    kabupaten_id: kabupaten_id
                },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    $('#kecamatan').empty().append('<option selected disabled>Pilih Kecamatan</option>');
                    
                    $.each(response, function(key, value) {
                        // Cek old value atau value yang sudah tersimpan
                        var selected = (value.id == "{{ old('kecamatan', $mhs->id_kec ?? '') }}")
                            ? 'selected' 
                            : '';
                        
                        $('#kecamatan').append(
                            '<option value="' + value.id + '" ' + selected + '>' 
                            + value.name + '</option>'
                        );
                    });

                    // Trigger change untuk mengisi desa jika ada
                    $('#kecamatan').trigger('change');
                }
            });
        }
    }

    // Fungsi untuk mengisi dropdown desa saat halaman dimuat
    function initDesa() {
        var kecamatan_id = $('#kecamatan').val();
        
        // Cek apakah kecamatan sudah dipilih
        if (kecamatan_id) {
            $.ajax({
                url: '{{ route('formulir.getDesa') }}',
                type: 'POST',
                data: {
                    kecamatan_id: kecamatan_id
                },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    $('#desa').empty().append('<option selected disabled>Pilih Desa</option>');
                    
                    $.each(response, function(key, value) {
                        // Cek old value atau value yang sudah tersimpan
                        var selected = (value.id == "{{ old('desa', $mhs->id_desa ?? '') }}")
                            ? 'selected' 
                            : '';
                        
                        $('#desa').append(
                            '<option value="' + value.id + '" ' + selected + '>' 
                            + value.name + '</option>'
                        );
                    });
                }
            });
        }
    }

    // Event listener untuk dropdown Provinsi
    $('#provinsi').change(function() {
        var provinsi_id = $(this).val();
        
        // Reset dropdown di bawahnya
        $('#kabupaten').html('<option value="" selected disabled>Pilih Kabupaten</option>');
        $('#kecamatan').html('<option value="" selected disabled>Pilih Kecamatan</option>');
        $('#desa').html('<option value="" selected disabled>Pilih Desa</option>');
        
        // Panggil fungsi untuk mengisi kabupaten
        initKabupaten();
    });

    // Event listener untuk dropdown Kabupaten
    $('#kabupaten').change(function() {
        var kabupaten_id = $(this).val();
        
        // Reset dropdown di bawahnya
        $('#kecamatan').html('<option value="" selected disabled>Pilih Kecamatan</option>');
        $('#desa').html('<option value="" selected disabled>Pilih Desa</option>');
        
        // Panggil fungsi untuk mengisi kecamatan
        initKecamatan();
    });

    // Event listener untuk dropdown Kecamatan
    $('#kecamatan').change(function() {
        var kecamatan_id = $(this).val();
        
        // Reset dropdown desa
        $('#desa').html('<option value="" selected disabled>Pilih Desa</option>');
        
        // Panggil fungsi untuk mengisi desa
        initDesa();
    });

    // Inisialisasi dropdown saat halaman dimuat
    // Cek apakah sudah ada provinsi yang dipilih
    @if(old('provinsi', $mhs->id_prov ?? false))
        initKabupaten();
    @endif

    @if(old('kabupaten', $mhs->id_kab ?? false))
        initKecamatan();
    @endif

    @if(old('kecamatan', $mhs->id_kec ?? false))
        initDesa();
    @endif
});
</script>
{{--  Wilayah Ortu --}}
<script>
$(document).ready(function() {
    function initKabupatenOrtu() {
        var provinsi_id = $('#provinsi_ortu').val();
        
        // Cek apakah provinsi sudah dipilih
        if (provinsi_id) {
            $.ajax({
                url: '{{ route('formulir.getKabupatenOrtu') }}',
                type: 'POST',
                data: {
                    provinsi_id: provinsi_id
                },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    $('#kabupaten_ortu').empty().append('<option selected disabled>Pilih Kabupaten</option>');
                    
                    $.each(response, function(key, value) {
                        // Cek old value atau value yang sudah tersimpan
                        var selected = (value.id == "{{ old('kabupaten_ortu',$ortu->id_kab ?? '') }}")
                            ? 'selected' 
                            : '';
                        
                        $('#kabupaten_ortu').append(
                            '<option value="' + value.id + '" ' + selected + '>' 
                            + value.name + '</option>'
                        );
                    });

                    // Trigger change untuk mengisi kecamatan jika ada
                    $('#kabupaten_ortu').trigger('change');
                }
            });
        }
    }

    // Fungsi untuk mengisi dropdown kecamatan saat halaman dimuat
    function initKecamatanOrtu() {
        var kabupaten_id = $('#kabupaten_ortu').val();
        
        // Cek apakah kabupaten sudah dipilih
        if (kabupaten_id) {
            $.ajax({
                url: '{{ route('formulir.getKecamatanOrtu') }}',
                type: 'POST',
                data: {
                    kabupaten_id: kabupaten_id
                },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    $('#kecamatan_ortu').empty().append('<option selected disabled>Pilih Kecamatan</option>');
                    
                    $.each(response, function(key, value) {
                        // Cek old value atau value yang sudah tersimpan
                        var selected = (value.id == "{{ old('kecamatan_ortu',$ortu->id_kec?? '') }}")
                            ? 'selected' 
                            : '';
                        
                        $('#kecamatan_ortu').append(
                            '<option value="' + value.id + '" ' + selected + '>' 
                            + value.name + '</option>'
                        );
                    });

                    // Trigger change untuk mengisi desa jika ada
                    $('#kecamatan_ortu').trigger('change');
                }
            });
        }
    }

    // Fungsi untuk mengisi dropdown desa saat halaman dimuat
    function initDesaOrtu() {
        var kecamatan_id = $('#kecamatan_ortu').val();
        
        // Cek apakah kecamatan sudah dipilih
        if (kecamatan_id) {
            $.ajax({
                url: '{{ route('formulir.getDesaOrtu') }}',
                type: 'POST',
                data: {
                    kecamatan_id: kecamatan_id
                },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    $('#desa_ortu').empty().append('<option selected disabled>Pilih Desa</option>');
                    
                    $.each(response, function(key, value) {
                        // Cek old value atau value yang sudah tersimpan
                        var selected = (value.id == "{{ old('desa_ortu',$ortu->id_kel?? '') }}")
                            ? 'selected' 
                            : '';
                        
                        $('#desa_ortu').append(
                            '<option value="' + value.id + '" ' + selected + '>' 
                            + value.name + '</option>'
                        );
                    });
                }
            });
        }
    }

    // Event listener untuk dropdown Provinsi
    $('#provinsi_ortu').change(function() {
        var provinsi_id = $(this).val();
        
        // Reset dropdown di bawahnya
        $('#kabupaten_ortu').html('<option value="" selected disabled>Pilih Kabupaten</option>');
        $('#kecamatan_ortu').html('<option value="" selected disabled>Pilih Kecamatan</option>');
        $('#desa_ortu').html('<option value="" selected disabled>Pilih Desa</option>');
        
        // Panggil fungsi untuk mengisi kabupaten
        initKabupatenOrtu();
    });

    // Event listener untuk dropdown Kabupaten
    $('#kabupaten_ortu').change(function() {
        var kabupaten_id = $(this).val();
        
        // Reset dropdown di bawahnya
        $('#kecamatan_ortu').html('<option value="" selected disabled>Pilih Kecamatan</option>');
        $('#desa_ortu').html('<option value="" selected disabled>Pilih Desa</option>');
        
        // Panggil fungsi untuk mengisi kecamatan
        initKecamatanOrtu();
    });

    // Event listener untuk dropdown Kecamatan
    $('#kecamatan_ortu').change(function() {
        var kecamatan_id = $(this).val();
        
        // Reset dropdown desa
        $('#desa_ortu').html('<option value="" selected disabled>Pilih Desa</option>');
        
        // Panggil fungsi untuk mengisi desa
        initDesaOrtu();
    });

    // Inisialisasi dropdown saat halaman dimuat
    // Cek apakah sudah ada provinsi yang dipilih
    @if(old('provinsi_ortu', $ortu->id_prov ?? false))
        initKabupatenOrtu();
    @endif

    @if(old('kabupaten_ortu', $ortu->id_kab ?? false))
        initKecamatanOrtu();
    @endif

    @if(old('kecamatan_ortu',$ortu->id_kec ?? false))
        initDesaOrtu();
    @endif
});
</script>
<script>
    //Pembayaran

    function validatePembayaran() { 
        let isValid = true;
            const requiredFields = [
                'bukti_pembayaran'
            ];

            requiredFields.forEach(field => {
                const $field = $(`[name="${field}"]`);
                const fileInput = $field[0];
                
                // Cek apakah file sudah dipilih
                if (!fileInput.files.length) {
                    $field.addClass('is-invalid');
                    isValid = false;
                    toastr.error(`Silakan unggah ${field.replace('_', ' ')}`);
                } else {
                    // Tambahan validasi ukuran file
                    const maxSize = 1024 * 1024; // 1MB
                    const fileSize = fileInput.files[0].size;
                    
                    if (fileSize > maxSize) {
                        $field.addClass('is-invalid');
                        isValid = false;
                        toastr.error(`Ukuran file ${field.replace('_', ' ')} maksimal 1MB`);
                    } else {
                        $field.removeClass('is-invalid');
                    }
                }
            });

            return isValid;
     }
    $(document).ready(()=>{
        $('#btnModalBayar').click((e)=>{
            e.preventDefault();
            $('#modalBayar').modal('show');
        })

        $('#btnSimpanBuktiBayar').click((e)=>{
            e.preventDefault();
            if (!validatePembayaran()) {
                return;
            }

            let formData = new FormData();
            let fileInput = $('#bukti_pembayaran')[0];
            
            if (fileInput.files.length > 0) {
                formData.append('bukti_pembayaran', fileInput.files[0]);
            } else {
                toastr.error('Silakan pilih file bukti pembayaran');
                return;
            }

            $.ajax({
                url: '{{ route('formulir.simpanBuktiPembayaran') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    $('#btnSimpanBuktiBayar').prop('disabled', true)
                        .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                },
                success: function(response) {
                    if (response.success) {
                       // Tampilkan notifikasi terlebih dahulu
                        toastr.success('Bukti pembayaran berhasil disimpan');

                        // Kemudian sembunyikan modal
                        $('#modalBayar').modal('hide');

                        // Terakhir, redirect dengan sedikit delay
                        setTimeout(() => {
                            window.location.href = '{{ route('formulir.index') }}';
                        }, 1000);
                    } else {
                        if (response.errors) {
                            $.each(response.errors, function(field, messages) {
                                $(`[name="${field}"]`).addClass('is-invalid');
                                toastr.error(messages[0]);
                            });
                        } else {
                            toastr.error(response.message);
                        }
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            $(`[name="${field}"]`).addClass('is-invalid');
                            toastr.error(messages[0]);
                        });
                    } else {
                        toastr.error('Terjadi kesalahan saat mengunggah dokumen');
                    }
                },
                complete: function() {
                    $('#btnSimpanBuktiBayar').prop('disabled', false)
                        .html('Simpan');
                }
            });
        });
    });
</script>

@endpush
