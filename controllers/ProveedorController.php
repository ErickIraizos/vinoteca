<?php
class ProveedorController extends Controller {
    private $proveedorModel;

    public function __construct() {
        parent::__construct();
        $this->proveedorModel = $this->loadModel('Proveedor');
    }

    public function index() {
        $proveedores = $this->proveedorModel->findAll(['activo' => 1], 'fecha_registro DESC');
        $this->view('admin/proveedores/index', ['proveedores' => $proveedores, 'title' => 'Proveedores']);
    }

    public function crear() {
        $this->view('admin/proveedores/crear', ['title' => 'Nuevo Proveedor']);
    }

    public function guardar() {
        if ($this->isPost()) {
            $data = [
                'nombre' => $_POST['nombre'],
                'contacto_nombre' => $_POST['contacto_nombre'],
                'telefono' => $_POST['telefono'],
                'email' => $_POST['email'],
                'direccion' => $_POST['direccion'],
                'pais' => $_POST['pais'],
                'activo' => 1
            ];
            $this->proveedorModel->create($data);
            SessionHelper::setFlash('success', 'Proveedor creado correctamente.');
            $this->redirect('admin/proveedores');
        }
    }

    public function editar($id) {
        $proveedor = $this->proveedorModel->findById($id);
        $this->view('admin/proveedores/editar', ['proveedor' => $proveedor, 'title' => 'Editar Proveedor']);
    }

    public function actualizar($id) {
        if ($this->isPost()) {
            $data = [
                'nombre' => $_POST['nombre'],
                'contacto_nombre' => $_POST['contacto_nombre'],
                'telefono' => $_POST['telefono'],
                'email' => $_POST['email'],
                'direccion' => $_POST['direccion'],
                'pais' => $_POST['pais']
            ];
            $this->proveedorModel->update($id, $data);
            SessionHelper::setFlash('success', 'Proveedor actualizado correctamente.');
            $this->redirect('admin/proveedores');
        }
    }

    public function eliminar($id) {
        $this->proveedorModel->update($id, ['activo' => 0]);
        SessionHelper::setFlash('success', 'Proveedor eliminado correctamente.');
        $this->redirect('admin/proveedores');
    }
} 