<?php

namespace App\Controllers;

use App\Models\LoginModel;
use App\Models\AlumnoModel;
use App\Models\ProfesorModel; 
use app\Models\SistemaClaseModel;
use app\Models\ProfesorSistemasVinculoModel;
use app\Models\MateriaModel;
use app\Models\MateriaProfesorModel;


class Configuracion extends BaseController
{
    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $id_auth = $session->get('id_auth');
        $rol = $session->get('rol');
        $db = \Config\Database::connect();

        // Buscamos los datos según el rol para mostrarlos en la vista
       if ($rol == 'Profesor') {
        $data['usuario'] = $db->table('perfil_profesor')->where('id_auth', $id_auth)->get()->getRowArray();
        
        // --- NUEVA LÓGICA PARA SISTEMAS ---
        $id_profesor = $data['usuario']['id_profesor'];
        $data['todos_los_sistemas'] = $db->table('sistemas_clase')->get()->getResultArray();
        $vinculos = $db->table('profesor_sistemas_vinculo')
                       ->where('id_profesor', $id_profesor)
                       ->get()->getResultArray();
        $data['mis_sistemas'] = array_column($vinculos, 'id_sistema');

        $data['todas_las_materias'] = $db->table('materias')->get()->getResultArray();
        $vinculosMaterias = $db->table('materias_profesor')->where('id_profesor', $id_profesor)->get()->getResultArray();
        $data['mis_materias'] = array_column($vinculosMaterias, 'id_materia');
    } else {
        $data['usuario'] = $db->table('perfiles_estudiantes')->where('id_auth', $id_auth)->get()->getRowArray();
    }

        // Obtener tasa BCV (Opcional, manejo de error silencioso)
        $tasa_bcv = 382.63;
        try {
            $client = \Config\Services::curlrequest();
            $response = $client->get('https://open.er-api.com/v6/latest/USD', ['timeout' => 2]);
            $resultado = json_decode($response->getBody());
            if (isset($resultado->rates->VES)) { $tasa_bcv = $resultado->rates->VES; }
        } catch (\Exception $e) { }

        $data['tasa_bcv'] = $tasa_bcv;

        // Carga la vista principal de configuración
        return view('vistas/configuracion/gestion_usuario', $data);
    }

    public function editar_nombre() {
        $session = session();
        $id_auth = $session->get('id_auth');
        $rol = $session->get('rol');
        $db = \Config\Database::connect();

        if ($rol == 'Profesor') {
            $data['usuario'] = $db->table('perfil_profesor')->where('id_auth', $id_auth)->get()->getRowArray();
        } else {
            $data['usuario'] = $db->table('perfiles_estudiantes')->where('id_auth', $id_auth)->get()->getRowArray();
        }

        return view('vistas/configuracion/editar_nombre', $data);
    }

    public function actualizar()
    {
        $session = session();
        $id_auth = $session->get('id_auth');
        $rol     = $session->get('rol');

        $nombre   = $this->request->getPost('nombre');
        $apellido = $this->request->getPost('apellido');
        // Capturamos el precio
        $precio   = $this->request->getPost('precio_clase'); 
        
        $updateData = [];

        if ($rol == 'Profesor') {
            $model = new \App\Models\ProfesorModel();
            $updateData['nombre_profesor']   = $nombre;
            $updateData['apellido_profesor'] = $apellido;
            
            // Si el precio viene en el POST, lo limpiamos y agregamos al update
            if ($precio !== null) {
                // Reemplazamos coma por punto por si el usuario escribe 10,50
                $updateData['precio_clase'] = str_replace(',', '.', $precio);
            }
            
            $model->where('id_auth', $id_auth)->set($updateData)->update();
        } else {
            $model = new \App\Models\AlumnoModel();
            $updateData['nombre_estudiante']   = $nombre;
            $updateData['apellido_estudiante'] = $apellido;
            
            $model->where('id_auth', $id_auth)->set($updateData)->update();
        }

        $session->set('nombre', $nombre);
        $session->set('apellido', $apellido); 

        return redirect()->to(base_url('configuracion'))->with('msg', '¡Datos actualizados con éxito!');
    }

    public function editar_foto() 
    {
        return view('vistas/configuracion/editar_foto');
    }

    public function actualizar_foto()
    {
        $session = session();
        $id_auth = $session->get('id_auth');
        $rol = $session->get('rol');
        
        $validationRule = [
            'foto_perfil' => [
                'label' => 'Archivo de Imagen',
                'rules' => 'uploaded[foto_perfil]|is_image[foto_perfil]|mime_in[foto_perfil,image/jpg,image/jpeg,image/png,image/gif]|max_size[foto_perfil,2048]',
            ],
        ];

        if (! $this->validate($validationRule)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $img = $this->request->getFile('foto_perfil');

        if ($img->isValid() && ! $img->hasMoved()) {
            $nuevoNombre = $img->getRandomName();
            
            // Usar FCPATH para guardar en public/uploads/perfiles (Correcto)
            $img->move(FCPATH . 'uploads/perfiles', $nuevoNombre);

            $updateData = ['foto' => $nuevoNombre];
            
            if ($rol == 'Profesor') {
                $model = new ProfesorModel();
                $model->where('id_auth', $id_auth)->set($updateData)->update();
            } else {
                $model = new AlumnoModel();
                $model->where('id_auth', $id_auth)->set($updateData)->update();
            }

            // Actualizar Sesión para verlo al instante
            $session->set('foto', $nuevoNombre);

            // Redirige a la configuración
            return redirect()->to(base_url('configuracion'))->with('msg', '¡Foto de perfil actualizada!');
        }

        return redirect()->back()->with('error', 'Hubo un problema al subir la imagen.');
    }

        public function cambiar_password() {

            $session = session();
            $id_auth = $session->get('id_auth');
            
            $passActual = $this->request->getPost('password_actual');
            $passNueva  = $this->request->getPost('password_nueva');
            $passConfirm = $this->request->getPost('password_confirmar');

            // 1. Validar que las nuevas coincidan (doble check del lado del servidor)
            if ($passNueva !== $passConfirm) {
                return redirect()->back()->with('error', 'Las contraseñas nuevas no coinciden.');
            }

            // 2. Obtener la contraseña actual de la BD
            $loginModel = new LoginModel();
            $usuario = $loginModel->find($id_auth);

            if (!$usuario || !password_verify($passActual, $usuario['contraseña'])) {
                return redirect()->back()->with('error', 'La contraseña actual es incorrecta.');
            }

            // 3. Actualizar la contraseña
            $loginModel->update($id_auth, [
                'contraseña' => password_hash($passNueva, PASSWORD_DEFAULT)
            ]);

            return redirect()->to(base_url('configuracion'))->with('msg', '¡Contraseña actualizada exitosamente!');
        }


        // --- GESTIÓN DE SISTEMAS DE CLASE ---

    public function configurar_sistemas()
    {
        $db = \Config\Database::connect();
        
        // 1. Obtener el ID real del profesor
        $perfil = $db->table('perfil_profesor')
                     ->where('id_auth', session()->get('id_auth'))
                     ->get()->getRowArray();
        
        if (!$perfil) return redirect()->to('/')->with('error', 'Perfil no encontrado');
        $id_profesor = $perfil['id_profesor'];

        // 2. Traer TODOS los sistemas disponibles (Google Meet, Zoom, etc)
        $data['todos_los_sistemas'] = $db->table('sistemas_clase')->get()->getResultArray();

        // 3. Traer los sistemas que este profesor YA tiene vinculados
        $vinculos = $db->table('profesor_sistemas_vinculo')
                       ->where('id_profesor', $id_profesor)
                       ->get()->getResultArray();
        
        // Convertimos a un array simple de IDs para que sea fácil marcar los checkboxes en la vista
        $data['mis_sistemas'] = array_column($vinculos, 'id_sistema');

        // Esta sería la vista que debes crear
        return view('vistas/profesor/configurar_sistemas', array_merge($data));
    }

    public function guardar_sistemas()
    {
        $db = \Config\Database::connect();
        $perfil = $db->table('perfil_profesor')
                     ->where('id_auth', session()->get('id_auth'))
                     ->get()->getRowArray();
        
        $id_profesor = $perfil['id_profesor'];
        $sistemas_seleccionados = $this->request->getPost('sistemas'); // Es un array de IDs

        $db->transStart();

        // 1. Borramos los sistemas actuales para evitar duplicados
        $db->table('profesor_sistemas_vinculo')->where('id_profesor', $id_profesor)->delete();

        // 2. Insertamos los nuevos (si seleccionó alguno)
        if (!empty($sistemas_seleccionados)) {
            $dataInsertar = [];
            foreach ($sistemas_seleccionados as $id_sistema) {
                $dataInsertar[] = [
                    'id_profesor' => $id_profesor,
                    'id_sistema'  => $id_sistema
                ];
            }
            $db->table('profesor_sistemas_vinculo')->insertBatch($dataInsertar);
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Hubo un error al guardar los sistemas.');
        }

        return redirect()->back()->with('success', 'Sistemas de clase actualizados correctamente.');
    }

    public function actualizar_sistemas_vinculo()
    {
        $session = session();
        $db = \Config\Database::connect();
        
        $perfil = $db->table('perfil_profesor')->where('id_auth', $session->get('id_auth'))->get()->getRowArray();
        $id_profesor = $perfil['id_profesor'];
        
        $sistemas_seleccionados = $this->request->getPost('sistemas') ?? [];

        $db->transStart();
        // 1. Limpiar anteriores
        $db->table('profesor_sistemas_vinculo')->where('id_profesor', $id_profesor)->delete();

        // 2. Insertar nuevos
        if (!empty($sistemas_seleccionados)) {
            $batch = [];
            foreach ($sistemas_seleccionados as $id_sistema) {
                $batch[] = [
                    'id_profesor' => $id_profesor,
                    'id_sistema'  => $id_sistema
                ];
            }
            $db->table('profesor_sistemas_vinculo')->insertBatch($batch);
        }
        $db->transComplete();

        return redirect()->to(base_url('configuracion'))->with('msg', 'Sistemas de clase actualizados.');
    }

    public function guardar_materias()
    {
        $idProfesor = session()->get('id_profesor'); // O como manejes tu sesión de profe
        $materiasSeleccionadas = $this->request->getPost('materias'); // Esto será un array de IDs

        if (empty($idProfesor)) {
            return redirect()->back()->with('error', 'Sesión no válida');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Borramos las materias que tenía antes para no duplicar
        $db->table('materias_profesor')->where('id_profesor', $idProfesor)->delete();

        // 2. Insertamos las nuevas
        if (!empty($materiasSeleccionadas)) {
            $dataInsert = [];
            foreach ($materiasSeleccionadas as $idMateria) {
                $dataInsert[] = [
                    'id_profesor' => $idProfesor,
                    'id_materia'  => $idMateria
                ];
            }
            $db->table('materias_profesor')->insertBatch($dataInsert);
        }

        $db->transComplete();

        return redirect()->back()->with('success', 'Materias actualizadas correctamente');
    }

    public function actualizar_materias_vinculo()
    {
        $session = session();
        $matProfModel = new \App\Models\MateriaProfesorModel();
        
        // Buscamos el ID del profesor
        $db = \Config\Database::connect();
        $perfil = $db->table('perfil_profesor')->where('id_auth', $session->get('id_auth'))->get()->getRowArray();
        $id_profesor = $perfil['id_profesor'];

        $materias_seleccionadas = $this->request->getPost('materias') ?? [];

        // El modelo se encarga de la lógica limpia
        $matProfModel->where('id_profesor', $id_profesor)->delete();

        if (!empty($materias_seleccionadas)) {
            $dataInsert = [];
            foreach ($materias_seleccionadas as $id_materia) {
                $dataInsert[] = [
                    'id_profesor' => $id_profesor,
                    'id_materia'  => $id_materia
                ];
            }
            $matProfModel->insertBatch($dataInsert);
        }

        return redirect()->to(base_url('configuracion'))->with('msg', 'Materias actualizadas.');
    }

    public function actualizar_precio() 
    {
        $session = session();
        $id_auth = $session->get('id_auth');
        $precio  = $this->request->getPost('precio_clase');

        if ($session->get('rol') == 'Profesor') {
            $model = new \App\Models\ProfesorModel();
            $precioLimpio = str_replace(',', '.', $precio);
            
            $model->where('id_auth', $id_auth)
                ->set(['precio_clase' => $precioLimpio])
                ->update();

            return redirect()->to(base_url('configuracion'))->with('msg', '¡Precio actualizado!');
        }
        
        return redirect()->back()->with('error', 'No tienes permiso.');
    }


}