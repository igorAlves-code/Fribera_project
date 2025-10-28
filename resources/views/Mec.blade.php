<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="shortcut icon" href="/img/logo.ico" type="image/x-icon">
    <link rel="icon" href="/img/logo.jpeg" type="image/png">
    <script src="./js/script.js" defer></script>
    <link rel="stylesheet" href="css/mec.css">
    <title>Fribera</title>
</head>
<body>
    <div class="menuBar">
        <img src="/img/logo.jpeg"></img>
        <ul>
            <li class="selected" data-target="content"><i class="ri-pencil-line"></i>Criar pedido</li>
            <li data-target="consult"><i class="ri-search-line"></i>Consultar pedidos</li>
        </ul>
    </div>
    <div class="mainContent">
        <div class="perfil">
            <a href="{{ route('logout') }}"><i class="ri-logout-box-line"></i>Sair</a>
        </div>
        <div class="main">
            <div id="content" class="content-tab active">
                <form action="{{route('criarRequisicao')}}" method="post" id="formPecas">
                    @csrf
                    <h2>Criar Novo Pedido de Serviço de Geladeira</h2>
                    <p class="sub">Preencha os detalhes abaixo para submeter um novo pedido de serviço.</p>
                    <div class="inputs">
                        <div class="volt-set">
                            <label class="volt-label">Voltagem</label>
                            <select class="volt-select" name="voltagem">
                                <option>110V</option>
                                <option>220V</option>
                            </select>
                        </div>
                        <div class="model-group">
                            <label for="modeloE" class="model-label">Modelo do equipamento</label>
                            <input type="text" class="model-input" placeholder="ex.: VB31" name="modelo">
                        </div>
                        <div class="desc-set">
                            <label for="desc" class="desc-label">Descrição</label>
                            <input type="text" class="desc-input" name="desc">
                        </div>
                    </div>

                    <h3>Peças disponíveis</h3>
                    <p class="sub">Selecione as peças necessárias e adicione ao seu pedido.</p>
                    
                    <input type="text" id="search" placeholder="Pesquisar...">
                    <div class="table">
                        <table class="modelTable">
                            <thead>
                                @error('pecas')
                                <span style="color:red;">{{ $message }}</span>
                                @enderror
                                @error('error')
                                <span style="color:red;">{{ $message }}</span>
                                @enderror
                                <tr>
                                    <th>Peça</th>
                                    <th>Em estoque</th>
                                    <th>Quantidade desejada</th>
                                    <th>Acões</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pecas as $peca)
                                <tr data-id="{{ $peca->id }}" data-estoque="{{ $peca->qtde }}">
                                    <td>{{ $peca->nome }}</td>
                                    <td>{{ $peca->qtde }}</td>
                                    <td class="quantidade-display">0</td>
                                    <input 
                                        type="hidden" 
                                        class="peca-quantidade-input"
                                        name="pecas[{{ $peca->id }}]"
                                        value="0"
                                        data-peca-id="{{ $peca->id }}"
                                    >
                                    <td id="PecaFunctions">
                                        <button type="button" class="adicionar">Adicionar</button>
                                        <i class="ri-delete-bin-line"></i>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <input type="submit" id="enviar"></input>
                </form>
            </div>
            <div id="consult" class="content-tab">
                <h2>Gerenciamento de solicitações</h2>
                <div class="cards"> 
                    <div class="cardContent">
                        <div class="topCard">
                            <p>Total de solicitações</p>
                            <i class="ri-pages-line"></i>
                        </div>
                        <h2>{{ $totalReqs->count() }}</h2>
                    </div>
                    <div class="cardContent">
                        <div class="topCard">
                            <p>Em andamento</p>
                            <i class="ri-timer-flash-line"></i>
                        </div>
                        <h2>{{ $pendentes }}</h2>
                    </div>
                    <div class="cardContent">
                        <div class="topCard">
                            <p>Concluidas</p>
                            <i class="ri-check-double-line"></i>
                        </div>
                        <h2>{{ $concluidas }}</h2>
                    </div>
                    <div class="cardContent">
                        <div class="topCard">
                            <p>Rejeitadas</p>
                            <i class="ri-close-circle-line"></i>
                        </div>
                        <h2>{{ $rejeitadas }}</h2>
                    </div>
                </div>  
                
                <div class="filter-container">
                    <h3>Filtro de solicitações</h3>
                    <form class="filter-row" method="get" action="{{ route('mec')}}">
                        <div class="filter-group">
                            <label class="filter-label">Status</label>
                            <select class="filter-select" id="filterStatus" name="status">
                                <option value="">Todos</option>
                                <option {{ request('status') == 'pendente' ? 'selected' : '' }} value='pendente'>Pendente</option>
                                <option {{ request('status') == 'concluida' ? 'selected' : '' }} value='concluida'>Concluida</option>
                                <option {{ request('status') == 'rejeitada' ? 'selected' : '' }} value='rejeitada'>Rejeitada</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                        <label class="filter-label">Data de Abertura</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="filter-input" name="data_abertura" placeholder="Selecionar data" 
                            id="filterData" value="{{ request('data_abertura') }}">
                        </div>
                        </div>
                        
                        <div class="filter-actions">
                            <input type="submit" class="btn-apply" value="Aplicar Filtros"></input>
                            <a href="{{ route('mec') }}" class="btn-reset">Redefinir</a>
                        </div>
                    </form>

                    <div class="table">
                        <table class="modelTable">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Modelo</th>
                                    <th>Data abertura</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reqs as $req)
                                <tr data-id="{{ $req->id }}">
                                    @php
                                        $statusClasse = '';
                                        if ($req->status == 'pendente') {
                                            $statusClasse = 'status-pendente';
                                        } elseif ($req->status == 'concluida') {
                                            $statusClasse = 'status-concluida';
                                        } elseif ($req->status == 'rejeitada') {
                                            $statusClasse = 'status-rejeitada';
                                        }
                                    @endphp
                                    <td>{{ $req->descricao }}</td>
                                    <td>{{ $req->modelo }}</td>
                                    <td>{{ $req->data_requisicao }}</td>
                                    <td><p class="{{ $statusClasse }}">{{ $req->status }}</p></td>
                                    <td><a href="{{ route('detalhar', $req->id) }}">detalhes</a>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="result">
                            
                        </div>
                    </div>

                    </div>
                </div> 
            </div>
    </div>
</body>
</html> 