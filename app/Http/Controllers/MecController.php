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

        $userId = Auth::id();
        $pecas = Peca::all();
        $totalReqs = Requisicoe::where('user_id', $userId)->get();

        $statusSearch = Requisicoe::where('user_id', $userId)->when(
            $request->filled('filterStatus'),
            fn($query) =>
            $query->whereLike('status', '%' .$request->filterStatus. '%') 
        );

        $concluidasCount = Requisicoe::where('user_id', $userId)->where('Status', 'concluida')->count();
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

        $request->validate([
            'voltagem' => 'required|string|in:110V,220V',
            'modelo' => 'required|string|max:255',
            'desc' => 'required|string',
            'pecas.*' => 'nullable|integer|min:0',
        ]);
        
        $pecas_solicitadas = array_filter($request->input('pecas', []));

        

        if (empty($pecas_solicitadas) && !config('app.allow_empty_requisition', false)) {
            return redirect()->back()->withErrors(['pecas' => 'A requisição deve conter pelo menos uma peça.']);
        }
        
        
        DB::beginTransaction();
        try {
            
            $dadosRequisicao = $request->only(['modelo', 'voltagem', 'desc']);

            $requisicao = Requisicoe::create([
                'user_id' => auth()->id(),
                'data_requisicao' => Carbon::now(),
                'status' => 'pendente',
                'modelo' => $dadosRequisicao['modelo'],
                'voltagem' => $dadosRequisicao['voltagem'],
                'descricao' => $dadosRequisicao['desc'],
            ]);

            $requisicaoId = $requisicao->id;

            $itensParaInserir = [];
            foreach ($pecas_solicitadas as $pecaId => $quantidade) {
                if ($quantidade > 0) {
                    $itensParaInserir[] = [
                        'requisicao_id' => $requisicaoId,
                        'peca_id' => $pecaId,
                        'qtde' => $quantidade,
                    ];
                }
            }
            if (!empty($itensParaInserir)) {
                RequisicaoPeca::insert($itensParaInserir);
            }
            DB::commit();

            return redirect()->route('mec')
                             ->with('success', 'Requisição criada com sucesso! ID: ' . $requisicaoId);

            } 
            catch (\Exception $e) {
                DB::rollBack();
                Log::error("Erro ao criar requisição: " . $e->getMessage(), ['request' => $request->all()]);

                return redirect()->back()
                                ->withInput()
                                ->with('error', 'Ocorreu um erro ao salvar a requisição. Tente novamente.');
            }
        }
}
