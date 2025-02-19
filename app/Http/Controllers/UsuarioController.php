<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Agendamento;
use App\Models\Servico;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UsuarioController extends Controller
{
    public function dashboard()
    {   
        return Inertia::render('Usuario/Dashboard');
    }

    public function index()
    {
        $user = auth()->user();

        $agendamentos = Agendamento::where('user_id', $user->id)
            ->with('servico', 'user')
            ->orderBy('data_agendamento', 'desc')
            ->paginate(10);

        return Inertia::render('Usuario/Agendamento', [
            'agendamentos' => $agendamentos,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Agendamento::class); 

        $request->validate([
            'servico_id' => 'required|exists:servicos,id',
            'nome_cliente' => 'required|string|max:255',
            'celular' => 'required|string|max:20',
            'data_agendamento' => 'required|date',
            'hora' => 'required',
            'status' => 'required|in:pendente,confirmado,cancelado',
        ]);

        $dataAgendamento = $request->data_agendamento;
        $hora = $request->hora;

        $agendamentoExistente = Agendamento::whereDate('data_agendamento', $dataAgendamento)
            ->where('hora', $hora)
            ->exists();

        if ($agendamentoExistente) {
            return redirect()->route('usuario.agendamentos.index')->with('error', 'Já existe um agendamento nesse horário na mesma data.');
        }

        Agendamento::create([
            'servico_id' => $request->servico_id,
            'user_id' => Auth::id(),  // Usuário logado
            'nome_cliente' => $request->nome_cliente,
            'celular' => $request->celular,
            'data_agendamento' => $dataAgendamento,
            'hora' => $hora,
            'status' => $request->status,
        ]);

        return redirect()->route('usuario.agendamentos.index')->with('success', 'Agendamento criado com sucesso!');
    }


    public function update(Request $request, Agendamento $agendamento)
    {
        $this->authorize('update', $agendamento); 

        $request->validate([
            'servico_id' => 'required|exists:servicos,id',
            'nome_cliente' => 'required|string|max:255',
            'celular' => 'required|string|max:20',
            'data_agendamento' => 'required|date',
            'hora' => 'required',
            'status' => 'required|in:pendente,confirmado,cancelado',
        ]);

        $dataAgendamento = Carbon::parse($request->data_agendamento);
        $dataAtual = Carbon::now();

        if ($dataAgendamento->diffInDays($dataAtual) < 2) {
            return redirect()->route('usuario.agendamentos.index')->with('error', 'Você só pode editar agendamentos com mais de 2 dias de antecedência.');
        }

        $agendamentoExistente = Agendamento::whereDate('data_agendamento', $request->data_agendamento)
            ->where('hora', $request->hora)
            ->where('id', '!=', $agendamento->id) 
            ->exists();

        if ($agendamentoExistente) {
            return redirect()->route('usuario.agendamentos.index')->with('error', 'Já existe um agendamento nesse horário na mesma data.');
        }

        
        $agendamento->update([
            'servico_id' => $request->servico_id,
            'nome_cliente' => $request->nome_cliente,
            'celular' => $request->celular,
            'data_agendamento' => $request->data_agendamento,
            'hora' => $request->hora,
            'status' => $request->status,
        ]);

        return redirect()->route('usuario.agendamentos.index')->with('success', 'Agendamento atualizado com sucesso!');
    }


    public function destroy(Agendamento $agendamento)
    {
        $this->authorize('delete', $agendamento);  

        if ($agendamento->user_id !== Auth::id()) {
            return redirect()->route('usuario.agendamentos.index')->with('error', 'Você não tem permissão para excluir esse agendamento.');
        }

        $agendamento->delete();

        return redirect()->route('usuario.agendamentos.index')->with('success', 'Agendamento excluído com sucesso!');
    }
}
