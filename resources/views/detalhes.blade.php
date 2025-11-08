<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Fribera</title>
    <style>
        :root {
    --primary-color: #3b82f6; /* Azul suave */
    --text-color: #1f2937; /* Cinza escuro para o texto */
    --border-color: #e5e7eb; /* Cinza claro para bordas */
    --background-light: #f9fafb; /* Fundo muito claro */
    --hover-color: #eff6ff; /* Fundo azul claro para hover */
    --header-bg: #f3f4f6; /* Fundo do cabeçalho */
}
body{
    display: grid;
    place-items: center;
    width: 100vw;
}

.tableResults {
    width: 30%;
    margin-top: 3rem; 
    border-collapse: collapse; 
    border-spacing: 0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
    overflow: hidden; /* Garante que o conteúdo respeite o border-radius */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.tableResults thead tr {
    background-color: var(--header-bg);
    color: var(--text-color);
    text-align: left;
}
p{
    font-size: 18px;
    font-weight: 600;
}
.tableResults th{
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    border-bottom: 2px solid var(--border-color);
}

.tableResults td {
    padding: 12px 20px;
    font-size: 14px;
    color: var(--text-color);
    border-bottom: 1px solid var(--border-color);
}

.tableResults tbody tr:last-child td {
    border-bottom: none;
}

.tableResults td:nth-child(2) {
    font-weight: 700;
    color: var(--primary-color);
}
a{
    /* Cores e Fundo */
    background-color: #3C467B; /* Cor Principal */
    color: #ffffff; /* Texto branco */
    margin-top: 1rem;
    display: inline-flex; /* Permite alinhar o texto e o ícone */
    align-items: center;
    padding: 10px 20px;
    border-radius: 8px; /* Bordas arredondadas modernas */
    font-size: 16px;
    font-weight: 600; /* Semibold */
    text-decoration: none; /* Remove sublinhado padrão */
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(60, 70, 123, 0.2);
    transition: all 0.3s ease;
}
a:hover {
    background-color: #556094; /* Cor um pouco mais clara no hover */
    box-shadow: 0 6px 10px rgba(60, 70, 123, 0.3); /* Aumenta a sombra no hover */
    transform: translateY(-1px); /* Move levemente para cima para dar profundidade */
}
.detail-item {
    display: inline-block; /* Coloca Modelo e Voltagem lado a lado */
    margin: 0 25px; /* Espaçamento entre os dois itens */
    padding: 10px 15px;
    
    /* Aparência de "Badge" ou "Tag" */
    background-color: #f3f4f6; /* Fundo cinza claro */
    color: #1f2937; /* Texto cinza escuro */
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500; /* Medium weight */
    letter-spacing: 0.5px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); /* Sombra muito sutil */
}

/* Estilo para a etiqueta (ex: "Modelo:", "Voltagem:") */
.detail-item strong {
    font-weight: 700; /* Negrito para a etiqueta */
    color: #3b82f6; /* Cor primária para destacar a etiqueta */
    margin-right: 5px;
}
.detail-section {
    /* Define um padding/margem para separar do botão e da tabela */
    margin-top: 20px; 
    padding: 15px 0;
    text-align: center; /* Centraliza o texto */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
    </style>
</head>
<body>
    <table class="tableResults">
        <thead>
            <tr>
                <th>Nome da peça</th>
                <th>Quantidade</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requisicao as $item)
            <tr>
                <td>{{ $item->peca->nome }}</td> 
                <td>{{ $item->qtde }}</td>
            </tr>
            @endforeach
        </tbody>
        
    </table>
    <div class="detail-section">

        <p class="detail-item">GESP: {{$reqs->first()->codigoMaquina}}</p>
        <p class="detail-item">Modelo: {{$reqs->first()->modelo}}</p>
        <p class="detail-item">Voltagem: {{$reqs->first()->voltagem}}</p>
        <p></p>
        <p class="detail-item">Descrição: {{$reqs->first()->descricao}}</p>

    </div>
    <a href="{{ route('mec') }}">Voltar</a>
</body>
</html>