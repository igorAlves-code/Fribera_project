const listItems = document.querySelectorAll(".menuBar ul li");
const contentTabs = document.querySelectorAll('.content-tab');

listItems.forEach(item => {
    
  item.addEventListener("click", () => {

        listItems.forEach(i => i.classList.remove("selected"));
        item.classList.add("selected");

        contentTabs.forEach(c => c.classList.remove('active'));
        const targetId = item.getAttribute('data-target');
        const targetContent = document.getElementById(targetId);
        if (targetContent) {
            targetContent.classList.add('active');
        }
        console.log(item);
    });
});

const input = document.getElementById('search');
const tabela = document.querySelector('.modelTable').getElementsByTagName('tbody')[0];

input.addEventListener('keyup', function() {
    const filtro = input.value.toLowerCase();
    const linhas = tabela.getElementsByTagName('tr');

    for (let i = 0; i < linhas.length; i++) {
        const colunas = linhas[i].getElementsByTagName('td');
        let textoLinha = '';
        for (let j = 0; j < colunas.length; j++) {
            textoLinha += colunas[j].textContent.toLowerCase() + ' ';
        }
        if (textoLinha.includes(filtro)) {
            linhas[i].style.display = '';
        } else {
            linhas[i].style.display = 'none';
        }
    }
});

const tabelaCorpo = document.querySelector('.modelTable tbody');

tabelaCorpo.addEventListener('click', (event) => {
        const linha = event.target.closest('tr');
        if (!linha) return;
        
        const pecaId = linha.dataset.id;
        const estoque = parseInt(linha.dataset.estoque);
        
        const displayCell = linha.querySelector('.quantidade-display');
        const hiddenInput = linha.querySelector(`.peca-quantidade-input[data-peca-id="${pecaId}"]`);

        let quantidadeAtual = parseInt(displayCell.textContent);

        console.log('log: ', quantidadeAtual)

        if (event.target.classList.contains('adicionar')) {
            if (quantidadeAtual < estoque) {
                quantidadeAtual+=1;
            }
        } else if (event.target.classList.contains('ri-delete-bin-line')) {
            if (quantidadeAtual > 0) {
                quantidadeAtual-=1;
            }
        }
        displayCell.textContent = quantidadeAtual;
        hiddenInput.value = quantidadeAtual;
});
