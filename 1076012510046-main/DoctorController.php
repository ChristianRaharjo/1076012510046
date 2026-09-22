<?php
require_once 'logic.php';

class DoctorController
{
    private DoctorModel $model;
    private AuthModel $auth;
    private ReservationModel $reservation;

    public function __construct()
    {
        $this->model = new DoctorModel();
        $this->auth = new AuthModel();
        $this->reservation = new ReservationModel();
    }

    public function handleRequest(): void
    {
        $action = $_GET['action'] ?? 'view';
        $user = $this->auth->getUser();

        if (!$user && $action !== 'login' && $action !== 'register') {
            header('Location: index.php?action=login');
            exit;
        }

        switch ($action) {
            case 'login': 
                $this->loginAction(); 
                break;
            case 'register':
                $this->registerAction();
                break;
            case 'logout': 
                $this->auth->logout();
                header('Location: index.php?action=login'); 
                exit;
            case 'book': 
                $this->requireRole('pasien');
                $this->bookAction(); 
                break;
            case 'reservations': 
                $this->reservationsAction(); 
                break;
            case 'ticket':
                $this->requireRole('pasien');
                $this->ticketAction();
                break;
            case 'add': 
                $this->requireRole('admin');
                $this->addDoctorAction(); 
                break;
            case 'edit': 
                $this->requireRole('admin'); 
                $this->editDoctorAction(); 
                break;
            case 'delete': 
                $this->requireRole('admin'); 
                $this->deleteDoctorAction(); 
                break;
            default: 
                $this->viewAction(); 
                break;
        }
    }

    private function requireRole(string $role): void 
    {
        $user = $this->auth->getUser();
        if (!$user || $user['role'] !== $role) {
            header('Location: index.php');
            exit;
        }
    }

    private function loginAction(): void 
    {
        if ($this->auth->getUser()) { 
            header('Location: index.php'); 
            exit; 
        }
        
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->auth->login($_POST['username'] ?? '', $_POST['password'] ?? '')) {
                header('Location: index.php'); 
                exit;
            } else {
                $error = 'Username atau Password salah!';
            }
        }
        require 'login.php';
    }

    private function registerAction(): void
    {
        if ($this->auth->getUser()) { 
            header('Location: index.php'); 
            exit; 
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $nama = trim($_POST['nama'] ?? '');

            if ($username === '' || $password === '' || $nama === '') {
                $error = 'Semua field wajib diisi!';
            } else if ($this->auth->register($username, $password, $nama)) {
                $this->auth->login($username, $password);
                header('Location: index.php');
                exit;
            } else {
                $error = 'Username sudah digunakan, silakan pilih yang lain.';
            }
        }
        require 'register.php';
    }

    private function bookAction(): void 
    {
        $id = (int)($_GET['id'] ?? 0);
        $doctor = $this->model->findById($id);

        if (!$doctor || $doctor['status'] !== DoctorModel::STATUS_AVAILABLE) {
            header('Location: index.php'); 
            exit;
        }

        $error = '';
        $user = $this->auth->getUser();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hari = $_POST['hari'] ?? '';
            $jam = $doctor['jadwal'][$hari] ?? '';

            if ($hari === '' || $jam === '') {
                $error = 'Silakan pilih hari praktik yang tersedia.';
            } else {
                $newId = $this->reservation->add([
                    'id_dokter' => $doctor['id'],
                    'nama_dokter' => $doctor['nama_dokter'],
                    'nama_pasien' => $user['nama'],
                    'hari' => $hari,
                    'jam' => $jam
                ]);
                header('Location: index.php?action=ticket&id=' . $newId);
                exit;
            }
        }
        require 'book.php';
    }

    private function reservationsAction(): void 
    {
        $user = $this->auth->getUser();
        $reservations = $this->reservation->getAll();
        
        if ($user['role'] === 'pasien') {
            $reservations = array_filter($reservations, fn($r) => $r['nama_pasien'] === $user['nama']);
        }
        require 'reservations.php';
    }

    private function ticketAction(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $ticket = $this->reservation->findById($id);
        $user = $this->auth->getUser();

        if (!$ticket || $ticket['nama_pasien'] !== $user['nama']) {
            header('Location: index.php?action=reservations');
            exit;
        }

        require 'ticket.php';
    }

    private function viewAction(): void 
    {
        $user = $this->auth->getUser();
        $doctors = $this->model->getAll();
        
        if ($user['role'] === 'admin') {
            require 'view_admin.php';
        } else {
            require 'view_pasien.php';
        }
    }

    private function addDoctorAction(): void 
    {
        $error = ''; 
        $form = $this->formData($_POST);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $error = $this->validate($form, false);
            if ($error === '') { 
                $this->model->add($form); 
                header('Location: index.php'); 
                exit; 
            }
        }
        require 'addDoctor.php';
    }

    private function editDoctorAction(): void 
    {
        $id = (int)($_GET['id'] ?? 0); 
        $doctor = $this->model->findById($id);
        
        if (!$doctor) { 
            header('Location: index.php'); 
            exit; 
        }
        
        $error = ''; 
        $form = $this->formData($doctor);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $form = $this->formData($_POST); 
            $error = $this->validate($form, true);
            if ($error === '') { 
                $this->model->update($id, $form); 
                header('Location: index.php'); 
                exit; 
            }
        }
        require 'editDoctor.php';
    }

    private function deleteDoctorAction(): void 
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) { 
            $this->model->delete($id); 
        }
        header('Location: index.php'); 
        exit;
    }

    private function formData(array $source): array 
    {
        $rawJadwal = is_array($source['jadwal'] ?? null) ? $source['jadwal'] : [];
        return [
            'nama_dokter'  => $this->clean($source['nama_dokter'] ?? ''),
            'spesialisasi' => $this->clean($source['spesialisasi'] ?? ''),
            'status'       => $this->clean($source['status'] ?? DoctorModel::STATUS_AVAILABLE),
            'jadwal'       => DoctorModel::normalizeSchedule($rawJadwal),
        ];
    }
    
    private function clean($value): string 
    { 
        return is_string($value) ? trim($value) : ''; 
    }
    
    private function validate(array $form, bool $checkStatus): string 
    {
        if ($form['nama_dokter'] === '' || $form['spesialisasi'] === '') {
            return 'Nama dokter dan spesialisasi wajib diisi.';
        }
        if (!in_array($form['spesialisasi'], DoctorModel::SPECIALTIES, true)) {
            return 'Spesialisasi tidak valid.';
        }
        if ($checkStatus && !in_array($form['status'], DoctorModel::STATUSES, true)) {
            return 'Status praktik tidak valid.';
        }
        if (count(array_filter($form['jadwal'], fn($jam) => $jam !== '')) === 0) {
            return 'Isi jam praktik minimal untuk satu hari.';
        }
        return '';
    }
}