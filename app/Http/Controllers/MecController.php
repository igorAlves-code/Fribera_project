<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peca;
use App\Models\Requisicoe;
Use App\Models\RequisicaoPeca;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MecController extends Controller
{
    public function mec(Request $request){

        $user = Auth::user(); 
        $userId = Auth::id();
        
        $pecas = Peca::all()->map(function ($peca) {
            $peca->qtde_disponivel = $peca->qtde - $peca->qtde_reservada;
            return $peca;
        });       

        $statusSearch = Requisicoe::where('user_id', $userId)->when(
            $request->filled('filterStatus'),
            fn($query) =>
            $query->whereLike('status', '%' .$request->filterStatus. '%') 
        );

        $totalReqs = Requisicoe::where('user_id', $userId)->count();
        $concluidasCount = Requisicoe::where('user_id', $userId)->where('Status', 'aprovada')->count();
        $rejeitadasCount = Requisicoe::where('user_id', $userId)->where('Status', 'rejeitada')->count();
        $pendentesCount = Requisicoe::where('user_id', $userId)->where('Status', 'pendente')->count();

        $query = Requisicoe::where('user_id', $userId);

        if ($request->filled('status')) {
            $status = $request->input('status');

            if (!empty($status)) {
                $query->where('status', $status);
            }
        }
        if ($request->filled('data_abertura')) {
            $dataAbertura = $request->input('data_abertura');
            $query->whereDate('data_requisicao', $dataAbertura);
        }
        $reqs = $query->get();

        return view('mec',
        [
            'userName' => $user->nome,
            'totalReqs' => $totalReqs,
            'statusSearch' => $statusSearch,
            'pecas' => $pecas, 
            'reqs' => $reqs, 
            'concluidas' => $concluidasCount, 
            'rejeitadas' => $rejeitadasCount, 
            'pendentes' => $pendentesCount
        ]);
    }
    public function detalhar($id){ 
        $id_num = (int) $id;
        $detalhes = RequisicaoPeca::where('requisicao_id', $id_num)->with('peca')->get();

        $reqs = Requisicoe::where('id', $id_num)->get();

        return view('detalhes', [
        'requisicao' => $detalhes,
        'reqs' => $reqs
        ]);
    }

    public function store(Request $request){

        $userId = Auth::id();
        
        if (!$userId) {
            Log::warning("Tentativa de criar requisição sem autenticação.");
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'É necessário estar autenticado para criar uma requisição.');
        }

        $request->validate([
            'voltagem' => 'required|string|in:110V,220V',
            'modelo' => 'required|string|max:255',
            'pecas.*' => 'nullable|integer|min:0',
            'codigoMaquina' => 'required|integer|min:0' 
        ]);
        
        $pecas_solicitadas = array_filter($request->input('pecas', []));

        if (empty($pecas_solicitadas) && !config('app.allow_empty_requisition', false)) {
            return redirect()->back()->withErrors(['pecas' => 'A requisição deve conter pelo menos uma peça.']);
        }
        
        
        DB::beginTransaction();
        try {
            
            $dadosRequisicao = $request->only(['modelo', 'voltagem', 'codigoMaquina']);
            
            $codigoMaquinaValue = $dadosRequisicao['codigoMaquina'];
            
            $descricaoFixa = 'Requisição aguardando aprovação';
            
            $itensParaInserir = [];
            foreach ($pecas_solicitadas as $pecaId => $quantidade) {
                $quantidade = (int) $quantidade;
                if ($quantidade > 0) {

                    $peca = Peca::where('id', $pecaId)->lockForUpdate()->first();

                    if (!$peca) {
                        DB::rollBack();
                        return redirect()->back()->withInput()->with('error', "Peça ID {$pecaId} não encontrada.");
                    }
                    
                    $estoqueDisponivel = $peca->qtde - $peca->qtde_reservada;

                    if ($estoqueDisponivel < $quantidade) {
                        DB::rollBack();
                        return redirect()->back()->withInput()->with('error', "Estoque insuficiente ({$estoqueDisponivel}) para a peça '{$peca->nome}'. Necessário: {$quantidade}.");
                    }
                    
                    $peca->increment('qtde_reservada', $quantidade); 
                    $peca->save();

                    $itensParaInserir[] = [
                        'requisicao_id' => null,
                        'peca_id' => $pecaId,
                        'qtde' => $quantidade,
                    ];
                }
            }
            
            // 2. CRIAÇÃO DA REQUISIÇÃO (STATUS 'PENDENTE')
            $requisicao = Requisicoe::create([
                'user_id' => $userId, 
                'data_requisicao' => Carbon::now(),
                'status' => 'pendente',
                'modelo' => $dadosRequisicao['modelo'],
                // Usa o valor obrigatório
                'codigoMaquina' => $codigoMaquinaValue, 
                'voltagem' => $dadosRequisicao['voltagem'],
                'descricao' => $descricaoFixa
            ]);

            $requisicaoId = $requisicao->id;

            // Insere os itens da requisição na tabela pivô
            $itensComId = array_map(function($item) use ($requisicaoId) {
                $item['requisicao_id'] = $requisicaoId;
                return $item;
            }, $itensParaInserir);

            if (!empty($itensComId)) {
                RequisicaoPeca::insert($itensComId);
            }
            
            DB::commit();

            return redirect()->route('mec')
                             ->with('success', 'Requisição criada com sucesso! ID: ' . $requisicaoId);

        } 
        catch (\Illuminate\Database\QueryException $e) {
             DB::rollBack();
             Log::error("Erro de DB (QueryException) ao criar requisição: " . $e->getMessage());
             return redirect()->back()
                             ->withInput()
                             ->with('error', 'Erro de banco de dados. Verifique se o `$fillable` está correto nos seus Models.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro genérico ao criar requisição: " . $e->getMessage(), ['request' => $request->all()]);

            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Ocorreu um erro inesperado ao salvar a requisição. Tente novamente.');
        }
    }
}
