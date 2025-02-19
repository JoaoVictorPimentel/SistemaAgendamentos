<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Agendamento;
use App\Models\Servico;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function dashboard()
    {
        return Inertia::render('Admin/Dashboard');
    }

    
    public function index()
    {
        $this->authorize('viewAny', Agendamento::class);

        $agendamentos = Agendamento::with(['servico', 'user'])->get();
        return Inertia::render('Admin/Agendamento', [
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
            'status' => 'required|in:pendente,confirmado,cancelado',
        ]);

        Agendamento::create([
            'servico_id' => $request->servico_id,
            'user_id' => Auth::id(),
            'nome_cliente' => $request->nome_cliente,
            'celular' => $request->celular,
            'data_agendamento' => $request->data_agendamento,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.agendamentos.index')->with('success', 'Agendamento criado com sucesso!');
    }

    public function show(Agendamento $agendamento)
    {
        $this->authorize('view', $agendamento);

        $agendamento->load(['servico', 'user']);
        return Inertia::render('Admin/Agendamentos/Show', [
            'agendamento' => $agendamento,
        ]);
    }

    public function update(Request $request, Agendamento $agendamento)
    {
        $this->authorize('update', $agendamento);

        $request->validate([
            'servico_id' => 'required|exists:servicos,id',
            'nome_cliente' => 'required|string|max:255',
            'celular' => 'required|string|max:20',
            'data_agendamento' => 'required|date',
            'status' => 'required|in:pendente,confirmado,cancelado',
        ]);

        $agendamento->update([
            'servico_id' => $request->servico_id,
            'nome_cliente' => $request->nome_cliente,
            'celular' => $request->celular,
            'data_agendamento' => $request->data_agendamento,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.agendamentos.index')->with('success', 'Agendamento atualizado com sucesso!');
    }

    public function destroy(Agendamento $agendamento)
    {
        $this->authorize('delete', $agendamento);

        $agendamento->delete();

        return redirect()->route('admin.agendamentos.index')->with('success', 'Agendamento excluído com sucesso!');
    }
}