<?php

namespace App\Http\Controllers;
use App\Models\Peca;
use App\Models\Requisicoe;
Use App\Models\RequisicaoPeca;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AlmoxController extends Controller
{
    public function almox(){
        $pecas = Peca::all();
        $qtde_50 = Peca::where('qtde', '<', 50)->get()->count();
        $pecaMaisPedida = DB::table('requisicao_pecas')
            ->select('peca_id', DB::raw('SUM(qtde) as total_pedida'))
            ->groupBy('peca_id')
            ->orderByDesc('total_pedida')
            ->limit(1)
            ->first();

        $nomePeca = 'Não encontrada';
        $quantidadeTotal = 0;

        if ($pecaMaisPedida) {
            $quantidadeTotal = $pecaMaisPedida->total_pedida;
            
            $peca = Peca::find($pecaMaisPedida->peca_id);
            if ($peca) {
                $nomePeca = $peca->nome;
            }
        }

        $totalGeral = Requisicoe::count();
        $totalAprovadas = Requisicoe::where('status', 'aprovada')->count();
        $percentualAprovadas = 0;
        if ($totalGeral > 0) {
            $percentualAprovadas = ($totalAprovadas / $totalGeral) * 100;
            $percentualAprovadas = number_format($percentualAprovadas, 2);
        }

        $hoje = Carbon::today();
        $ultimaSegunda = $hoje->copy()->startOfWeek(Carbon::MONDAY);

        $requisicoes = Requisicoe::whereDate('data_requisicao', '>=', $ultimaSegunda->toDateString())
            ->whereDate('data_requisicao', '<=', $hoje->toDateString())
            ->count();
        
        return view('almox', ['pecas' => $pecas, 'qtde_50' => $qtde_50, 'nome' => $nomePeca, 'qtdeTotal' => $quantidadeTotal, 'percent' => $percentualAprovadas, 'monday' => $requisicoes]);
        
    }   
    public function update(Request $request){
        
        $data = $request->validate([
            'pecas' => 'required|array',
            'pecas.*' => 'nullable|integer|min:0',
        ]);
        
        $pecasParaAtualizar = $data['pecas'];
        $count = 0;

        DB::beginTransaction();

        try{
            foreach ($pecasParaAtualizar as $pecaId => $novaQtdeString) {
                
                $novaQtde = intval($novaQtdeString);
                $peca = Peca::find($pecaId);

                if ($peca) {
                    if ($novaQtde !== (int)$peca->qtde) {
                        $peca->qtde = $novaQtde;
                        $peca->save(); 
                        $count++;
                    }
                }
            }
            
            if ($count > 0) {
                DB::commit();

                return redirect()->route('almox')
                                 ->with('success', "Sucesso! Foram atualizadas $count peças no estoque.");
            }
            
            DB::rollBack(); 
            return redirect()->route('almox')
                             ->with('info', "Nenhuma peça foi alterada, pois todos os valores eram iguais aos atuais.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("Erro na atualização em lote de peças: " . $e->getMessage());

            return redirect()->back()
                 ->withErrors(['error' => 'Erro ao processar as atualizações. Nenhuma alteração foi salva.'])
                 ->withInput();
        }

    }
}
