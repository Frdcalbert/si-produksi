{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data User')
@section('page-title', 'Data User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <span class="text-muted">Kelola Data User</span>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah User
    </a>
</div>

<div class="filter-section">
    <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-6">
            <label class="filter-label">Cari User</label>
            <div class="input-group">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari nama / username / email..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                @if(isset($search) && $search)
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i></a>
                @endif
            </div>
        </div>
        <div class="col-md-6 text-end">
            <span class="badge-custom badge-secondary"><i class="bi bi-people me-1"></i>Total: {{ $users->count() }}</span>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $key => $user)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td><strong>{{ $user->nama }}</strong></td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->no_hp }}</td>
                            <td>
                                <span class="badge-custom badge-{{ $user->role == 'Admin' ? 'danger' : 'info' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($user->id != auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data user</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- PAGINATION --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center p-3 border-top gap-2">
            <div class="pagination-info">
                <i class="bi bi-info-circle me-1"></i>
                Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} 
                dari {{ $users->total() }} data
            </div>
            <div>
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection