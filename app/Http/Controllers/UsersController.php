<?php

namespace App\Http\Controllers;

use App\Mail\perfilUpdate;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\tipoUsuario;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\getEmails;
use App\Mail\updateUser;

use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nombre_usuario' => 'required|string|max:255',
            'documento' => 'required|string|max:255',
            'tipoUser' => 'required|exists:tipousuario,idtipousuario',
            'usuario' => 'required',
            'pass' => 'required',
            'correo' => 'required'
        ]);
        $existingUsers = Users::where('usuario', '=', $request->input('usuario'))->first();
        $existingEmailByUsers = Users::where('correo', '=', $request->input('correo'))->first();
        $existingDocuments = Users::where('documento', '=', $request->input('documento'))->first();

        if ($existingUsers && $existingUsers->estado == 1) {
            // Nombre de usuario ya existe, devuelve una respuesta con mensaje de error
            return response()->json(['userError' => true]);
        } else if ($existingEmailByUsers && $existingEmailByUsers->estado == 1) {
            // correo ya existe, devuelve una respuesta con mensaje de error
            return response()->json(['emailError' => true]);
        } else if ($existingDocuments && $existingDocuments->estado == 1) {
            // correo ya existe, devuelve una respuesta con mensaje de error
            return response()->json(['documentError' => true]);
        }
        $firmaFileName = '';
        if ($request->hasFile('firmaFile')) {
            $firmaData = $request->file('firmaFile');
            $extension = $firmaData->getClientOriginalExtension();
            // $allExtesion = ['png','jpg','jpeg','pdf'];
            $firmaFileName = 'firma/' . uniqid() . '_' . $request->input('documento') . ''. $extension;
            Storage::disk('public')->put($firmaFileName, file_get_contents($firmaData));
        }
        if ($request->input('firmas')) {
            $firmaData = $request->input('firmas');
            $firmaFileName = 'firma/' . uniqid() . '_' . $request->input('documento') . '.png';
            Storage::disk('public')->put($firmaFileName, file_get_contents($firmaData));
        }
        $user = new Users();


        $user->nombre_usuario = $request->input('nombre_usuario');
        $user->apellido = $request->input('apellido');
        $user->documento = $request->input('documento');
        if (!empty($firmaFileName)) {
            $user->firma = $firmaFileName;
        }
        $user->direccion = $request->input('direccion');
        $user->correo = $request->input('correo');
        $user->usuario = $request->input('usuario');
        $user->pass = bcrypt($request->input('pass'));
        $user->idtipo_usuario = $request->input('tipoUser');
        $user->estado = 1;
        $user->email_verified_at = now();
        $user->save();
        // Auth::login($user);
        $user->idtipo_usuario = $request->input('tipoUser');
        $userType = tipoUsuario::find($user->idtipo_usuario);
        $data = [
            'user' => $user->usuario,
            'password' => $request->input('pass'),
            'userType' => $userType->nombre_tipo_usuario,
        ];
        $email = $user->correo = $request->input('correo');
        Mail::to($email)->send(new getEmails($data));
        return response()->json(['exitoso' => true]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'pass' => 'required',
        ]);
        $user = Users::where('usuario', $request['usuario'])->where('estado', 1)->first();
        if ($user && Hash::check($request['pass'], $user->pass)) {
            Auth::login($user);
            return response()->json(['exitoso' => true]);
        } else {
            return response()->json(['exitoso' => false]);
        }
    }
    public function logOut(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['exitoso' => true]);
    }
    public function tipoUsuario()
    {
        $tipoUsuario = tipoUsuario::all();
        return view('Auth.registerUser', ['tipoUsuario' => $tipoUsuario]);

    }

    public function tableUsers(Request $request)
    {
        $paginate = $request->input('per_page', 25);
        $users = Users::orderBy('created_at', 'desc')->where('estado', 1)->paginate($paginate);
        $currentPage = $users->currentPage();
        $itemsPage = $users->perPage();
        $position = ($currentPage - 1) * $itemsPage + 1;
        return view('tables.tableUsers', ['users' => $users, 'position' => $position]);

    }
    //  listado consecutivo
    //  Trajeta de propiedad Activa-Inactiva
    public function edit($id)
    {
        $users = Users::find($id);
        $tipoUsuario = tipoUsuario::all();

        return view('update.updateUser', compact('users', 'tipoUsuario'));

    }
    public function update(Request $request, $id)
    {
        $user = Users::find($id);
        if (!$user) {
            return back()->with('error', 'Usuario no encontrado');
        }
        $request->validate([
            'nombre_usuario' => 'nullable|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'documento' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'correo' => 'nullable|string|email|max:255',
            'usuario' => 'required|string',
            'pass' => 'nullable|string',
            'tipoUser' => 'required|string',
        ]);
        // Verificar si se proporcionó un correo electrónico diferente y validar su unicidad
        if ($request->filled('correo') && $request->input('correo') !== $user->correo) {
            $existingEmailByUsers = Users::where('correo', '=', $request->input('correo'))->first();
            if ($existingEmailByUsers && $existingEmailByUsers->estado == 1) {
                // Correo electrónico ya existe, devuelve una respuesta con mensaje de error
                return response()->json(['emailError' => true]);
            }
        }
        // Verificar si se proporcionó un nombre de usuario diferente y validar su unicidad
        if ($request->filled('usuario') && $request->input('usuario') !== $user->usuario) {
            $existingUsers = Users::where('usuario', '=', $request->input('usuario'))->first();
            if ($existingUsers && $existingUsers->estado == 1) {
                // Nombre de usuario ya existe, devuelve una respuesta con mensaje de error
                return response()->json(['userError' => true]);
            }
        }

        // Actualizar los campos del usuario
        $user->nombre_usuario = $request->input('nombre_usuario');
        $user->apellido = $request->input('apellido');
        $user->direccion = $request->input('direccion');
        $user->correo = $request->input('correo');
        $user->usuario = $request->input('usuario');
        $user->idtipo_usuario = $request->input('tipoUser');

        // Verificar si se proporcionó una nueva contraseña y encriptarla
        if ($request->filled('pass')) {
            $password = bcrypt($request->input('pass'));
        } else {
            // Si no se proporcionó una nueva contraseña, usar la contraseña actual
            $password = $user->pass;
        }
        $user->pass = $password;

        // Guardar los cambios en el usuario
        $user->save();

        // Obtener el tipo de usuario
        $userType = tipoUsuario::find($user->idtipo_usuario);

        // Preparar los datos para el correo electrónico
        $email = $user->correo;
        $data = [
            'user' => $user->usuario,
            'password' => $request->filled('pass') ? $request->input('pass') : 'Misma contraseña', // Aquí se enviará la contraseña actual o la nueva
            'userType' => $userType->nombre_tipo_usuario,
        ];

        // Enviar el correo electrónico de actualización de usuario
        Mail::to($email)->send(new updateUser($data));

        // Devolver una respuesta JSON de éxito
        return response()->json(['success' => true]);
    }
    public function destroy($id)
    {
        $user = Users::find($id);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
        $user->estado = 2;
        $user->save();

        return response()->json(['success' => 'usuario eliminado correctamente']);

    }
    public function editUser()
    {
        $users = Users::find(Auth::user()->idusuario);
        return view('update.profile')->with('users', $users);

    }
    public function perfilUpdate(Request $request)
    {
        $user = Users::find(Auth::user()->idusuario);
        if (!$user) {
            return back()->with('error', 'Usuario No encontrado');
        }
        $request->validate([
            'nombre_usuario' => 'nullable|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'documento' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'correo' => 'nullable|string|email|max:255',
            'usuario' => 'required|string',
            'pass' => 'nullable|string|',
        ]);
        // Si el correo electrónico proporcionado en la solicitud es diferente al correo electrónico actual del usuario
        if ($request->filled('correo') && $request->input('correo') !== $user->correo) {
            $existingEmailByUsers = Users::where('correo', '=', $request->input('correo'))->first();
            if ($existingEmailByUsers && $existingEmailByUsers->estado == 1) {
                // correo ya existe, devuelve una respuesta con mensaje de error
                return response()->json(['emailError' => true]);
            }
        }
        if ($request->filled('usuario') && $request->input('usuario') !== $user->usuario) {
            $existingUsers = Users::where('usuario', '=', $request->input('usuario'))->first();
            if ($existingUsers && $existingUsers->estado == 1) {
                // Nombre de usuario ya existe, devuelve una respuesta con mensaje de error
                return response()->json(['userError' => true]);
            }
        }

        $user->nombre_usuario = $request->input('nombre_usuario');
        $user->apellido = $request->input('apellido');
        $user->direccion = $request->input('direccion');
        $user->correo = $request->input('correo');
        $user->usuario = $request->input('usuario');
        if ($request->filled('pass')) {
            $user->pass = bcrypt($request->input('pass'));
        }
        $user->save();
        $email = $user->correo = $request->input('correo');
        $data = [
            'user' => $user->usuario,
            'password' => $request->input('pass'),
        ];
        Mail::to($email)->send(new perfilUpdate($data));
        return response()->json(['success' => true]);
    }

}
