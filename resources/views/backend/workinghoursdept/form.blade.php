<div class="row mb-3">
   <div class="col-6">
        <div class="mb-3">
            <label for="branch" class="form-label">Branchs</label>
            <select id="branch" name="branch" class="form-select" required>
                <option value="">-- Pilih Branch --</option>
                @foreach($branchs as $d )
                    @php
                        // Tentukan nilai ID yang harus diperiksa:
                        // 1. Prioritaskan nilai 'old' jika ada (setelah validasi gagal).
                        // 2. Jika tidak, gunakan ID posisi dari data $employee lama.
                        // 3. Gunakan operator Null Coalescing (?? '') untuk menghindari error jika $employee null.
                        $selectedId = old('branch', $whd->branch_code ?? '');
                    @endphp
                    <option value="{{ $d->id }}"
                            {{ $selectedId == $d->id ? 'selected' : '' }}>
                        {{ $d->name }}
                    </option>
                @endforeach
            </select>
        </div>
   </div>
   <div class="col-6">
        <div class="mb-3">
            <label for="dept" class="form-label">Departemnts</label>
            <select id="dept" name="dept" class="form-select" required>
                <option value="">-- Pilih Departement --</option>
                @foreach($departements as $d )
                    @php
                        // Tentukan nilai ID yang harus diperiksa:
                        // 1. Prioritaskan nilai 'old' jika ada (setelah validasi gagal).
                        // 2. Jika tidak, gunakan ID posisi dari data $employee lama.
                        // 3. Gunakan operator Null Coalescing (?? '') untuk menghindari error jika $employee null.
                        $selectedId = old('dept', $whd->dept_code ?? '');
                    @endphp
                    <option value="{{ $d->id }}"
                            {{ $selectedId == $d->id ? 'selected' : '' }}>
                        {{ $d->name }}
                    </option>
                @endforeach
            </select>
        </div>
   </div>
   <div class="row mb-3">
        <div class="col-6">
            <table class="table table-responsive table-borderless" id="tblwhd">
                <thead>
                    <tr>
                        <th>HARI</th>
                        <th class="text-center">JAM KERJA</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Asumsi Anda mengulangi baris ini untuk 7 hari, kita ambil contoh Senin --}}
                    <tr>
                        <td class="align-middle">
                            Senin
                            <input type="hidden" name="day[]" value="Senin">
                        </td>
                        <td>
                            <select name="idwk[]" id="idwk" class="form-select" required>
                                <option value="">Pilih Jam Kerja</option>

                                {{-- 1. Ambil ID Jadwal yang tersimpan untuk hari ini ('Senin') --}}
                                @php
                                    // Gunakan operator Null Coalescing (??) untuk menangani kasus jika 'Senin' belum ada di database
                                    $selectedId = $workingdays['Senin'] ?? null;
                                @endphp

                                @foreach($workinghours as $d )
                                    <option value="{{ $d->id }}"
                                            {{-- 2. Bandingkan ID Jadwal yang tersimpan dengan ID yang sedang di-loop --}}
                                            {{ $selectedId == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td class="align-middle">
                            Selasa
                            <input type="hidden" name="day[]" value="Selasa">
                        </td>
                        <td>
                            <select name="idwk[]" id="idwk" class="form-select" required>
                                <option value="">Pilih Jam Kerja</option>

                                {{-- 1. Ambil ID Jadwal yang tersimpan untuk hari ini ('Senin') --}}
                                @php
                                    // Gunakan operator Null Coalescing (??) untuk menangani kasus jika 'Senin' belum ada di database
                                    $selectedId = $workingdays['Selasa'] ?? null;
                                @endphp

                                @foreach($workinghours as $d )
                                    <option value="{{ $d->id }}"
                                            {{-- 2. Bandingkan ID Jadwal yang tersimpan dengan ID yang sedang di-loop --}}
                                            {{ $selectedId == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-middle">
                            Rabu
                            <input type="hidden" name="day[]" value="Rabu">
                        </td>
                        <td>
                            <select name="idwk[]" id="idwk" class="form-select" required>
                                <option value="">Pilih Jam Kerja</option>

                                {{-- 1. Ambil ID Jadwal yang tersimpan untuk hari ini ('Senin') --}}
                                @php
                                    // Gunakan operator Null Coalescing (??) untuk menangani kasus jika 'Senin' belum ada di database
                                    $selectedId = $workingdays['Rabu'] ?? null;
                                @endphp

                                @foreach($workinghours as $d )
                                    <option value="{{ $d->id }}"
                                            {{-- 2. Bandingkan ID Jadwal yang tersimpan dengan ID yang sedang di-loop --}}
                                            {{ $selectedId == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-middle">
                            Kamis
                            <input type="hidden" name="day[]" value="Kamis">
                        </td>
                        <td>
                            <select name="idwk[]" id="idwk" class="form-select" required>
                                <option value="">Pilih Jam Kerja</option>

                                {{-- 1. Ambil ID Jadwal yang tersimpan untuk hari ini ('Senin') --}}
                                @php
                                    // Gunakan operator Null Coalescing (??) untuk menangani kasus jika 'Senin' belum ada di database
                                    $selectedId = $workingdays['Kamis'] ?? null;
                                @endphp

                                @foreach($workinghours as $d )
                                    <option value="{{ $d->id }}"
                                            {{-- 2. Bandingkan ID Jadwal yang tersimpan dengan ID yang sedang di-loop --}}
                                            {{ $selectedId == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-middle">
                            Jumat
                            <input type="hidden" name="day[]" value="Jumat">
                        </td>
                        <td>
                            <select name="idwk[]" id="idwk" class="form-select" required>
                                <option value="">Pilih Jam Kerja</option>

                                {{-- 1. Ambil ID Jadwal yang tersimpan untuk hari ini ('Senin') --}}
                                @php
                                    // Gunakan operator Null Coalescing (??) untuk menangani kasus jika 'Senin' belum ada di database
                                    $selectedId = $workingdays['Jumat'] ?? null;
                                @endphp

                                @foreach($workinghours as $d )
                                    <option value="{{ $d->id }}"
                                            {{-- 2. Bandingkan ID Jadwal yang tersimpan dengan ID yang sedang di-loop --}}
                                            {{ $selectedId == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-middle">
                            Sabtu
                            <input type="hidden" name="day[]" value="Sabtu">
                        </td>
                        <td>
                            <select name="idwk[]" id="idwk" class="form-select" required>
                                <option value="">Pilih Jam Kerja</option>

                                {{-- 1. Ambil ID Jadwal yang tersimpan untuk hari ini ('Senin') --}}
                                @php
                                    // Gunakan operator Null Coalescing (??) untuk menangani kasus jika 'Senin' belum ada di database
                                    $selectedId = $workingdays['Sabtu'] ?? null;
                                @endphp

                                @foreach($workinghours as $d )
                                    <option value="{{ $d->id }}"
                                            {{-- 2. Bandingkan ID Jadwal yang tersimpan dengan ID yang sedang di-loop --}}
                                            {{ $selectedId == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-middle">
                            Minggu
                            <input type="hidden" name="day[]" value="Minggu">
                        </td>
                        <td>
                            <select name="idwk[]" id="idwk" class="form-select" required>
                                <option value="">Pilih Jam Kerja</option>

                                {{-- 1. Ambil ID Jadwal yang tersimpan untuk hari ini ('Senin') --}}
                                @php
                                    // Gunakan operator Null Coalescing (??) untuk menangani kasus jika 'Senin' belum ada di database
                                    $selectedId = $workingdays['Minggu'] ?? null;
                                @endphp

                                @foreach($workinghours as $d )
                                    <option value="{{ $d->id }}"
                                            {{-- 2. Bandingkan ID Jadwal yang tersimpan dengan ID yang sedang di-loop --}}
                                            {{ $selectedId == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-6">
            <table class="table table-responsive table-hover">
                <thead class="table-light">
                    <tr>
                        <th colspan="7" class="text-center">MASTER JAM KERJA</th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Awal Jam Masuk</th>
                        <th>Jam Masuk</th>
                        <th>Batas Masuk</th>
                        <th>Jam Pulang</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($workinghours as $d)
                        <tr>
                            <th scope="row" class="align-middle">{{ $loop->iteration }}</th>

                            <td class="align-middle">{{ $d->name }}</td>
                            <td class="align-middle font-weight-bold">{{\Carbon\carbon::parse($d->start_time)->format('H:i') }}</td>
                            <td class="align-middle">{{ \Carbon\carbon::parse($d->entry_time)->format('H:i') }}</td>
                            <td class="align-middle font-weight-bold">{{ \Carbon\carbon::parse($d->end_time)->format('H:i') }}</td>
                            <td class="align-middle">{{ \Carbon\carbon::parse($d->out_time)->format('H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

   </div>
</div>

