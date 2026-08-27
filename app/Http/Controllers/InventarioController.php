<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;

class InventarioController extends Controller
{
    // Función para MOSTRAR la tabla
    public function index(Request $request)
    {
        // 1. Preparamos la consulta base (sin pedir los datos todavía)
        $query = Animal::query();

        // 2. Revisamos si el HTML nos mandó algo por el input name="buscar"
        if ($request->has('buscar') && $request->buscar != '') {
            $termino = $request->buscar;
            
            // Filtramos donde el arete O el nombre se parezcan a lo que escribió
            $query->where('arete', 'LIKE', '%' . $termino . '%')
                  ->orWhere('nombre', 'LIKE', '%' . $termino . '%');
        }

        // 3. Ahora sí, traemos los resultados ordenados por los más recientes
        $animales = $query->latest()->get(); 
        
        return view('inventario.index', compact('animales'));
    }

    // Función para GUARDAR el nuevo animal
    public function store(Request $request)
    {
        $request->validate([
            'arete' => 'required|unique:animals',
            'raza' => 'required',
            'sexo' => 'required',
            'estado' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg|max:2048' // Validamos que sea imagen y pese menos de 2MB
        ]);

        // Lógica para guardar la foto
        $rutaFoto = null;
        if ($request->hasFile('foto')) {
            // Esto guarda la foto en storage/app/public/vacas y devuelve la ruta
            $rutaFoto = $request->file('foto')->store('vacas', 'public');
        }

        Animal::create([
            'arete' => $request->arete,
            'foto' => $rutaFoto, // Guardamos la ruta en la BD
            'nombre' => $request->nombre,
            'raza' => $request->raza,
            'sexo' => $request->sexo,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'estado' => $request->estado,
        ]);

        return redirect()->route('inventario.index')->with('exito', 'Animal registrado correctamente.');
    }
    // Función para MOSTRAR el formulario lleno con los datos de la vaca
    public function edit($id)
{
    // Buscamos al animal en la base de datos por su ID
    $animal = Animal::findOrFail($id);
    
    // Lo enviamos a tu vista de edición
    return view('inventario.edit', compact('animal'));
}
    // Función para GUARDAR los cambios
    public function update(Request $request, Animal $animal)
    {
        $request->validate([
            'arete' => 'required',
            'raza' => 'required',
            'sexo' => 'required',
            'estado' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $datos = [
            'arete' => $request->arete,
            'nombre' => $request->nombre,
            'raza' => $request->raza,
            'sexo' => $request->sexo,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'estado' => $request->estado,
        ];

        // Si subió una foto nueva, la reemplazamos
        if ($request->hasFile('foto')) {
            $datos['foto'] = $request->file('foto')->store('vacas', 'public');
        }

        $animal->update($datos);

        return redirect()->route('inventario.index')->with('exito', 'Datos actualizados.');
    }
   

    // Eliminar Animal
    public function destroy(Animal $animal)
    {
        $animal->delete();
        return redirect()->route('inventario.index')->with('exito', 'Animal eliminado del inventario.');
    }
}