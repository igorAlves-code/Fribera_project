<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="shortcut icon" href="/img/logo.ico" type="image/x-icon">
    <link rel="icon" href="/img/logo.jpeg" type="image/png">
    <script src="./js/script.js" defer></script>
    <link rel="stylesheet" href="css/almox.css">
    <title>Fribera</title>
</head>
<body>
     <div class="menuBar">
        <img src="/img/logo.jpeg"></img>
        <ul>
            <li class="selected" data-target="content"><i class="ri-pencil-line"></i>Visão geral do estoque</li>
            <li data-target="consult"><i class="ri-search-line"></i>Aprovar requisições</li>
        </ul>
    </div>
    <div class="mainContent">
        <div class="perfil">
            <a href="{{ route('logout') }}"><i class="ri-logout-box-line"></i>Sair</a>
        </div>
        <div class="main">
                <div class="cards"> 
                    <div class="cardContent">
                        <div class="topCard">
                            <p>Estoque baixo</p>
                            <i class="ri-inbox-archive-line"></i>
                        </div>
                        <h2>{{ $qtde_50 }} peças com menos de 50 unidades</h2>
                    </div>
                    <div class="cardContent">
                        <div class="topCard">
                            <p>Solicitações pendentes</p>
                            <i class="ri-timer-flash-line"></i>
                        </div>
                        <h2>{{ $monday}} requisições desde segunda</h2>
                    </div>
                    <div class="cardContent">
                        <div class="topCard">
                            <p>Peça mais solicitadas</p>
                            <i class="ri-bar-chart-box-line"></i>
                        </div>
                        <h2>{{ $nome }}</h2>
                        <h4>Mais de {{ $qtdeTotal }} solicitações</h4>
                    </div>
                    <div class="cardContent">
                        <div class="topCard">
                            <p>Taxa de aprovação</p>
                            <i class="ri-check-double-line"></i>
                        </div>
                        <h2>{{ $percent }}% de solicitações aprovadas</h2>
                    </div>
                </div>

                <div id="content">
                    <h3>Estoque detalhado</h3>
                    <p class="sub">Lista completa de todas as peças e quantidades.</p>

                    <form action="{{route('atualizarPeca')}}" method="post" id="formPecas">
                    @csrf
                        <input type="text" id="search" placeholder="Pesquisar...">
                        <div class="table">
                            <table class="modelTable">
                                <thead>
                                    @if ($errors->any())
                                       <span style="color:red">
                                            @if ($errors->has('error'))
                                                {{ $errors->first('error') }}
                                            @endif
                                        </div>
                                    @endif
                                    @if (session('success'))
                                    <span style="color:rgb(44, 154, 44)">{{ session('success') }}</span>
                                    @endif
                                    @if (session('info'))
                                    <span style="color:rgb(201, 197, 0)">{{ session('info') }}</span>
                                    @endif
                                    <tr>
                                        <th>Id</th>
                                        <th>Peça</th>
                                        <th>Em estoque</th>
                                        <th>Atualizar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pecas as $peca)
                                    <tr>
                                        <td>{{ $peca->id }}</td>
                                        <td>{{ $peca->nome }}</td>
                                        <td id="qtdePecas">{{ $peca->qtde }}</td>
                                        <td>
                                            <input type="number" 
                                            name="pecas[{{ $peca->id }}]" 
                                            value="{{ $peca->qtde }}" 
                                            min="0"
                                            id="number">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    <input type="submit" id="enviar"></input>
                </div>
         </div>
    </div>
</body>
</html>