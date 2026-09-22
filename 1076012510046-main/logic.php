<?php
date_default_timezone_set('Asia/Jakarta');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class AuthModel {
    private const USER_SESSION = 'users';

    public function __construct() {
        if (!isset($_SESSION[self::USER_SESSION])) {
            $_SESSION[self::USER_SESSION] = [
                'admin' => ['username' => 'admin', 'password' => '123', 'role' => 'admin', 'nama' => 'Administrator'],
                'pasien' => ['username' => 'pasien', 'password' => '123', 'role' => 'pasien', 'nama' => 'Budi (Pasien)']
            ];
        }
    }

    public function login(string $username, string $password): bool {
        $users = $_SESSION[self::USER_SESSION];
        if (isset($users[$username]) && $users[$username]['password'] === $password) {
            $_SESSION['user'] = [
                'username' => $users[$username]['username'],
                'role' => $users[$username]['role'],
                'nama' => $users[$username]['nama']
            ];
            return true;
        }
        return false;
    }

    public function register(string $username, string $password, string $nama): bool {
        $users = $_SESSION[self::USER_SESSION];
        if (isset($users[$username])) {
            return false;
        }
        $users[$username] = [
            'username' => $username,
            'password' => $password,
            'role' => 'pasien',
            'nama' => $nama
        ];
        $_SESSION[self::USER_SESSION] = $users;
        return true;
    }

    public function logout(): void {
        unset($_SESSION['user']);
    }

    public function getUser(): ?array {
        return $_SESSION['user'] ?? null;
    }
}

class ReservationModel {
    private const SESSION_KEY = 'reservations';

    public function __construct() {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }
    }

    public function add(array $data): int {
        $reservations = $this->getAll();
        $newId = empty($reservations) ? 1 : max(array_column($reservations, 'id')) + 1;

        $antrianSpesifik = array_filter($reservations, function($r) use ($data) {
            return $r['id_dokter'] === $data['id_dokter'] && $r['hari'] === $data['hari'];
        });
        
        $nomorUrut = count($antrianSpesifik) + 1;
        
        $nomorAntrian = 'D' . $data['id_dokter'] . '-' . str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);

        $reservations[] = [
            'id' => $newId,
            'id_dokter' => $data['id_dokter'],
            'nama_dokter' => $data['nama_dokter'],
            'nama_pasien' => $data['nama_pasien'],
            'hari' => $data['hari'],
            'jam' => $data['jam'],
            'nomor_antrian' => $nomorAntrian,
            'tanggal_booking' => date('Y-m-d H:i:s')
        ];
        $_SESSION[self::SESSION_KEY] = $reservations;
        
        return $newId;
    }

    public function getAll(): array {
        return $_SESSION[self::SESSION_KEY] ?? [];
    }

    public function findById(int $id): ?array {
        foreach ($this->getAll() as $r) {
            if ((int)$r['id'] === $id) return $r;
        }
        return null;
    }
}

class DoctorModel {
    private const SESSION_KEY = 'doctors';
    public const DAYS = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    public const SPECIALTIES = ['Poli Umum', 'Poli Anak', 'Poli Gigi', 'Poli Mata', 'Poli THT', 'Poli Penyakit Dalam', 'Poli Saraf', 'Poli Jantung', 'Poli Bedah'];
    public const STATUS_AVAILABLE = 'Available';
    public const STATUS_NOT_AVAILABLE = 'Not Available';
    public const STATUSES = [self::STATUS_AVAILABLE, self::STATUS_NOT_AVAILABLE];

    public function __construct() {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [
                ['id' => 1, 'no' => 1, 'nama_dokter' => 'Dr. Andi', 'spesialisasi' => 'Poli Umum', 'status' => self::STATUS_AVAILABLE, 'jadwal' => self::normalizeSchedule(['Senin' => '08:00 - 12:00', 'Rabu' => '08:00 - 12:00'])],
                ['id' => 2, 'no' => 2, 'nama_dokter' => 'Dr. Bunga', 'spesialisasi' => 'Poli Gigi', 'status' => self::STATUS_AVAILABLE, 'jadwal' => self::normalizeSchedule(['Selasa' => '09:00 - 13:00', 'Kamis' => '09:00 - 13:00'])],
            ];
        }
    }

    public static function normalizeSchedule($input): array {
        $input = is_array($input) ? $input : [];
        $result = [];
        foreach (self::DAYS as $day) {
            $value = $input[$day] ?? '';
            $result[$day] = is_string($value) ? trim($value) : '';
        }
        return $result;
    }

    private static function normalizeStatus($status): string {
        return in_array($status, self::STATUSES, true) ? $status : self::STATUS_AVAILABLE;
    }

    public function getAll(): array { return $_SESSION[self::SESSION_KEY] ?? []; }
    
    public function findById(int $id): ?array { 
        foreach ($this->getAll() as $doctor) { 
            if ((int)$doctor['id'] === $id) return $doctor; 
        } 
        return null; 
    }
    
    public function add(array $doctor): void {
        $doctors = $this->getAll();
        $newId = empty($doctors) ? 1 : max(array_column($doctors, 'id')) + 1;
        $doctors[] = ['id' => $newId, 'no' => count($doctors) + 1, 'nama_dokter' => trim($doctor['nama_dokter']), 'spesialisasi' => trim($doctor['spesialisasi']), 'status' => self::normalizeStatus($doctor['status'] ?? null), 'jadwal' => self::normalizeSchedule($doctor['jadwal'] ?? [])];
        $_SESSION[self::SESSION_KEY] = $doctors;
    }

    public function update(int $id, array $doctor): void {
        $doctors = $this->getAll();
        foreach ($doctors as $index => $currentDoctor) {
            if ((int)$currentDoctor['id'] === $id) {
                $doctors[$index]['nama_dokter'] = trim($doctor['nama_dokter']);
                $doctors[$index]['spesialisasi'] = trim($doctor['spesialisasi']);
                $doctors[$index]['status'] = self::normalizeStatus($doctor['status'] ?? null);
                $doctors[$index]['jadwal'] = self::normalizeSchedule($doctor['jadwal'] ?? []);
                $_SESSION[self::SESSION_KEY] = $doctors;
                return;
            }
        }
    }

    public function delete(int $id): void {
        $doctors = $this->getAll(); $filtered = []; $counter = 1;
        foreach ($doctors as $doctor) { 
            if ((int)$doctor['id'] !== $id) { 
                $doctor['no'] = $counter++; 
                $filtered[] = $doctor; 
            } 
        }
        $_SESSION[self::SESSION_KEY] = $filtered;
    }
}