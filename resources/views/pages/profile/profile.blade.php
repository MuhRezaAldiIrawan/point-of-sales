@extends('layouts.main')

@section('content')
    <div id="user-profile">
        <div class="row">
            <div class="col-lg-4 col-md-5">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="profile-image mb-3">
                            @if($user->foto)
                                <img src="{{ Storage::url($user->foto) }}"
                                    class="rounded-circle img-border" style="width: 150px; height: 150px; object-fit: cover;" alt="Profile">
                            @else
                                <img src="{{ asset('app-assets/images/portrait/small/avatar-s-1.png') }}"
                                    class="rounded-circle img-border" style="width: 150px; height: 150px; object-fit: cover;" alt="Profile">
                            @endif
                        </div>
                        <h3 class="mb-0">{{ $user->nama_depan }} {{ $user->nama_belakang }}</h3>
                        <p class="text-muted">{{ $user->jabatan->nama_jabatan ?? '-' }}</p>
                        <p class="mb-1"><i class="ft-mail"></i> {{ $user->email ?? '-' }}</p>
                        <p class="mb-1"><i class="ft-phone"></i> {{ $user->no_telepon ?? '-' }}</p>
                        <p class="mb-1"><i class="ft-map-pin"></i> {{ $user->alamat ?? '-' }}</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Informasi Tambahan</h4>
                        <hr>
                        <div class="mb-2">
                            <strong>NIP:</strong>
                            <p class="mb-0">{{ $user->nip }}</p>
                        </div>
                        <div class="mb-2">
                            <strong>KTP:</strong>
                            <p class="mb-0">{{ $user->ktp ?? '-' }}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Tanggal Lahir:</strong>
                            <p class="mb-0">{{ $user->tanggal_lahir ? $user->tanggal_lahir->format('d/m/Y') : '-' }}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Jenis Kelamin:</strong>
                            <p class="mb-0">{{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : ($user->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Tanggal Masuk:</strong>
                            <p class="mb-0">{{ $user->tanggal_masuk ? $user->tanggal_masuk->format('d/m/Y') : '-' }}</p>
                        </div>
                        <div class="mb-2">
                            <strong>Status:</strong>
                            <p class="mb-0">
                                <span class="badge badge-{{ $user->status_karyawan == 'aktif' ? 'success' : 'danger' }}">
                                    {{ ucfirst($user->status_karyawan) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Profile</h4>
                    </div>
                    <div class="card-body">
                        @include('sweetalert::alert')

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_depan">Nama Depan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama_depan') is-invalid @enderror"
                                            id="nama_depan" name="nama_depan" value="{{ old('nama_depan', $user->nama_depan) }}" required>
                                        @error('nama_depan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_belakang">Nama Belakang</label>
                                        <input type="text" class="form-control @error('nama_belakang') is-invalid @enderror"
                                            id="nama_belakang" name="nama_belakang" value="{{ old('nama_belakang', $user->nama_belakang) }}">
                                        @error('nama_belakang')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_telepon">No. Telepon <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                                            id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" required>
                                        @error('no_telepon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select class="form-control @error('jenis_kelamin') is-invalid @enderror"
                                            id="jenis_kelamin" name="jenis_kelamin" required>
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                            id="tanggal_lahir" name="tanggal_lahir"
                                            value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}">
                                        @error('tanggal_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror"
                                    id="alamat" name="alamat" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="foto">Foto Profile</label>
                                <input type="file" class="form-control-file @error('foto') is-invalid @enderror"
                                    id="foto" name="foto" accept="image/jpeg,image/png,image/jpg">
                                <small class="form-text text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ft-save"></i> Update Profile
                                </button>
                                <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                                    <i class="ft-x"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
