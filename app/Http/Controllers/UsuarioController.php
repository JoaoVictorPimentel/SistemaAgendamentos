<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Agendamento;

class UsuarioController extends Controller
{
    public function dashboard()
    {   
        return Inertia::render('Usuario/Dashboard');
    }

    public function index()
    {
        $user = auth()->user();

        $agendamentos = Agendamento::where('user_id', $user->id)->with('servico', 'user')->get();
    
        return Inertia::render('Usuario/Agendamento', [
            'agendamentos' => $agendamentos,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuário excluído com sucesso!');
    }
}
