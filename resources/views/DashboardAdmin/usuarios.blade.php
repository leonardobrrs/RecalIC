<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Usuários - Sistema de Recall</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Cola aqui o CSS da barra lateral (sidebar) do teu dashboardADM.blade.php */
        body, html { height: 100%; margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        .sidebar { background-color: #212529; color: white; padding: 20px; display: flex; flex-direction: column; align-items: center; height: 100vh; position: fixed; width: 280px; }
        .sidebar-footer { margin-top: auto; width: 100%; padding-bottom: 20px; }
        .logout-button { display: flex; align-items: center; justify-content: center; gap: 10px; background: none; border: none; color: white; font-size: 1.1rem; cursor: pointer; opacity: 0.8; transition: opacity 0.3s ease; text-decoration: none; width: 100%; }
        .logout-button:hover { opacity: 1; color: white; }
        .profile-avatar { width: 150px; height: 150px; border-radius: 50%; background-color: #495057; display: flex; align-items: center; justify-content: center; font-size: 70px; font-weight: bold; color: white; margin-bottom: 20px; }
        .sidebar h5 { margin-bottom: 40px; }
        .sidebar .nav-button { background-color: #f8f9fa; color: #343a40; border: none; border-radius: 20px; padding: 10px 20px; width: 80%; text-align: center; margin-bottom: 15px; text-decoration: none; font-weight: 500; transition: background-color 0.3s ease; }
        .sidebar .nav-button:hover { background-color: #e2e6ea; }
        .sidebar .nav-button.active { background-color: #dee2e6; font-weight: bold; }
        .main-content { margin-left: 280px; padding: 40px; }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar">
        <div class="profile-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
        <h5>{{ explode(' ', auth()->user()->name)[0] }}</h5>
        <a href="{{ url('/admin/dashboard') }}" class="nav-button">Ocorrências</a>
        <a href="{{ url('/admin/usuarios') }}" class="nav-button">Usuários</a>
        <a href="{{ url('/admin/relatorios') }}" class="nav-button">Relatórios</a>
        <div class="sidebar-footer">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button">
                    <i class="bi bi-power" style="font-size: 1.5rem;"></i>
                    <span>Sair</span>
                </button>
            </form>
        </div>
    </div>

    <div class="main-content flex-grow-1">
        <h2 class="mb-4">Gestão de Usuários</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('admin.usuarios.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Pesquisar (Nome ou Email)</label>
                            <input type="text" name="search" id="search" class="form-control" value="{{ $filters['search'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label for="role" class="form-label">Papel</label>
                            <select name="role" id="role" class="form-select">
                                <option value="">Todos os Papéis</option>
                                <option value="admin" {{ ($filters['role'] ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="relator" {{ ($filters['role'] ?? '') == 'relator' ? 'selected' : '' }}>Relator</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Todos</option>
                                <option value="bloqueado" {{ ($filters['status'] ?? '') == 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-dark w-100">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Papel</th>
                        <th>Reputação</th>
                        <th class="text-end">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                @if($usuario->role == 'admin')
                                    <span class="badge bg-primary">Admin</span>
                                @else
                                    <span class="badge bg-secondary">Relator</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $score = $usuario->reputation_score;
                                    if ($score <= 0) {
                                        echo '<span class="badge bg-danger">Bloqueado</span>';
                                    } elseif ($score < 50) {
                                        echo '<span class="badge bg-danger text-dark">Ruim</span>';
                                    } elseif ($score < 75) {
                                        echo '<span class="badge bg-warning text-dark">Média</span>';
                                    } else {
                                        echo '<span class="badge bg-success">Boa</span>';
                                    }
                                @endphp
                            </td>

                            <td class="text-end">
                                <div class="btn-group" role="group">

                                    @php
                                        $actionText = $usuario->role == 'admin' ? 'rebaixar para Relator' : 'promover para Admin';
                                    @endphp
                                    <form action="{{ route('admin.usuarios.toggleRole', $usuario->id) }}" method="POST" class="me-1" onsubmit="return confirm('Tem certeza que deseja {{ $actionText }} este usuário?');">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm {{ $usuario->role == 'admin' ? 'btn-warning' : 'btn-success' }}"
                                                @if(auth()->id() == $usuario->id) disabled @endif
                                                title="{{ $usuario->role == 'admin' ? 'Rebaixar para Relator' : 'Promover para Admin' }}">
                                            <i class="bi {{ $usuario->role == 'admin' ? 'bi-arrow-down-circle' : 'bi-arrow-up-circle' }}"></i>
                                        </button>
                                    </form>

                                    @if ($usuario->reputation_score > 0)
                                        <form action="{{ route('admin.usuarios.block', $usuario->id) }}" method="POST" class="me-1" onsubmit="return confirm('Tem certeza que deseja bloquear este usuário? (Reputação será 0)');">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    @if(auth()->id() == $usuario->id) disabled @endif
                                                    title="Bloquear usuário (Reputação 0)">
                                                <i class="bi bi-person-x-fill"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.usuarios.unblock', $usuario->id) }}" method="POST" class="me-1" onsubmit="return confirm('Tem certeza que deseja desbloquear este usuário? (Reputação será 100)');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-info" title="Desbloquear usuário (Reputação 100)">
                                                <i class="bi bi-person-check-fill"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este usuário? Todas as suas ocorrências serão anonimizadas.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" @if(auth()->id() == $usuario->id) disabled @endif title="Excluir usuário">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhum usuário encontrado.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $usuarios->appends($filters ?? [])->links() }}
            </div>
        </div>
    </div>
</div>
</body>
</html>
