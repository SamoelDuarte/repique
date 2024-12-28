<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Exibe uma lista de usuários (opcional).
     */
    public function index()
    {
        $areas = Area::all();

        return view('admin.usuario.index', compact('areas'));
    }

    public function getUser()
    {
        return response()->json(User::with('area')->where(['role' => 'user','deleted' => '0'])->get());
    }

    public function show($id)
    {
        return response()->json(User::with('area')->where('id', $id)->get());
    }

    public function update(Request $request, $id)
    {
        // Validação dos dados
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'area_id' => 'required|exists:areas,id', // Certificando-se de que a área existe
            'pontuacao' => 'required|numeric',
            'active' => 'required|boolean',
        ]);

        // Recuperando o usuário pelo ID
        $user = User::findOrFail($id);
        $user->area_id = $request->area_id;  // Associa o usuário à área usando o ID
        // Atualizando os dados do usuário
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'pontuacao' => $validated['pontuacao'],
            'active' => $validated['active'],
            'phone' => $validated['phone'],
        ]);

        // Retornando a resposta com o usuário atualizado
        return response()->json($user);
    }


    /**
     * Cria um novo usuário.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    // Exemplo de controlador para usuários
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'area_id' => 'required|exists:areas,id',  // Validar se o ID da área existe
            'pontuacao' => 'required|numeric',  // Aqui você deve garantir que seja numérico
            'active' => 'required|boolean',
            'phone' => 'required|string',
        ]);

        $user = new User();
        $user->fill($validated);  // Preenche os campos validados
        $user->password = bcrypt($request->password);  // Criptografa a senha
        $user->area_id = $request->area_id;  // Associa o usuário à área usando o ID
        $user->role = 'user';  // Associa o usuário à área usando o ID
        $user->save();  // Salva o usuário

        return response()->json($user);  // Retorna o usuário salvo
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->deleted = true;
        $user->save();

        return response()->json(['type' => 'success' ,'msg' => 'Usuário Deletado com sucesso!' ]);
    }
}
