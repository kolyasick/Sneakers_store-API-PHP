<?php 
namespace Src\Models\Users;
use Src\Models\Articles\ActiveRecordEntity;
use Src\Exceptions\InvalidArgumentException;
use Src\Services\Db;

class User extends ActiveRecordEntity {
    protected $nickname;
    protected $email;
    protected $isConfirmed;
    protected $role;
    protected $passwordHash;
    protected $authToken;
    protected $createdAt;

    public function getNickName() {
        return $this->nickname;
    }

    public function setNickName($nickname): Void {
        $this->nickname = $nickname;
    }

    public function setRole($role): Void {
        $this->role = $role;
    }

    public function setPasswordHash($passwordHash): Void {
        $this->passwordHash = $passwordHash;
    }

    public function setEmail($email): Void {
        $this->email = $email;
    }
    public function getIsConfirmed() {
        return $this->isConfirmed;
    }

    public function getRole() {
        return $this->role;
    }

    public function getPasswordHash() {
        return $this->passwordHash;
    }

    public function getAuthToken() {
        return $this->authToken;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getEmail() {
        return $this->email;
    }

    protected static function getTableName(): string {
        return 'users';
    }

    public static function signUp(array $userData) {
        if (empty($userData['nickname'])) {
            throw new InvalidArgumentException('Не передан nickname');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $userData['nickname'])) {
            throw new InvalidArgumentException('Nickname может состоять только из символов латинского алфавита и цифр');
        }
        if (static::findByOneColumn('nickname', $userData['nickname']) !== null) {
            throw new InvalidArgumentException('Пользователь с таким nickname уже существует');
        }
        if (empty($userData['email'])) {
            throw new InvalidArgumentException('Не передан email');
        }
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('email некорректен');
        }
        if (static::findByOneColumn('email', $userData['email']) !== null) {
            throw new InvalidArgumentException('Такой email уже зарегистрирован');
        }
        if (empty($userData['password'])) {
            throw new InvalidArgumentException('Не передан password');
        }
        if (mb_strlen($userData['password'] < 8)) {
            throw new InvalidArgumentException('Пароль не должен быть менее 8 символов');
        }
        if ($userData['password'] !== $userData['repeat_password']) {
            throw new InvalidArgumentException('Пароли не совпадают');
        }
        $user = new User();
        $user->nickname = $userData['nickname'];
        $user->email = $userData['email'];
        $user->passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $user->role = 'user';
        $user->isConfirmed = true;
        $user->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
        $user->save();
        return $user;
    }

    public static function login(array $loginData): User {
        if (empty($loginData['email'])) {
            http_response_code(404);
            echo json_encode('Email is empty');
            exit;
        }
        if (empty($loginData['password'])) {
            http_response_code(404);
            echo json_encode('Password is empty');
            exit;
        }
        $user = User::findByOneColumn('email', $loginData['email']);
        if ($user == null) {
            http_response_code(404);
            echo json_encode('No user with such email');
            exit;
        }
        if (!password_verify($loginData['password'], $user->passwordHash)) {
            http_response_code(404);
            echo  json_encode('Wrong password');
            exit;
        }
        if (!$user->getIsConfirmed()) {
            http_response_code(404);
            echo json_encode('User is not confirmed');
            exit;
        }
        $user->refreshAuthToken();
        $user->save();
        return $user;
    }

    public function refreshAuthToken() {
        $this->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }

    public function updateUserPass(array $userData): User {
        if (!empty($userData['password']) && !password_verify($userData['password'], $this->passwordHash)) {
            throw new InvalidArgumentException('Неправильный старый пароль');
        }
        if ($userData['new_password'] !== $userData['repeat_password']) {
            throw new InvalidArgumentException('Пароли не совпадают');
        }
        if (!empty($userData['password']) && 
            $userData['new_password'] == $userData['repeat_password'] && 
            password_verify($userData['password'], $this->passwordHash)){
                
            $this->setPasswordHash(password_hash($userData['new_password'], PASSWORD_DEFAULT));
        }
        
        $this->save();
        return $this;
    }
    public function updateUser(array $userData): User {
        // Check if nickname is provided and valid
        if (empty($userData['nickname'])) {
            return $this->sendErrorResponse(400, 'Nickname not found');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $userData['nickname'])) {
            return $this->sendErrorResponse(400, 'Wrong nickname format');
        }

        // Check if email is provided and valid
        if (empty($userData['email'])) {
            return $this->sendErrorResponse(400, 'Email not found');
        }
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->sendErrorResponse(400, 'Wrong email format');
        }

        // Check if password is provided
        if (empty($userData['password'])) {
            return $this->sendErrorResponse(400, 'Verify password');
        }

        // Check if the password is correct
        if (!password_verify($userData['password'], $this->passwordHash)) {
            return $this->sendErrorResponse(400, 'Wrong password');
        }

        // Check if the nickname or email have changed
        $nicknameChanged = $userData['nickname'] !== $this->getNickName();
        $emailChanged = $userData['email'] !== $this->getEmail();

        if (!$nicknameChanged && !$emailChanged) {
            return $this->sendErrorResponse(400, 'Data not changed');
        }

        // Check if the new email already exists in the database
        if ($emailChanged && static::findByOneColumn('email', $userData['email']) !== null) {
            return $this->sendErrorResponse(400, 'Email exists');
        }

        // Update user data
        if ($nicknameChanged) {
            $this->setNickName($userData['nickname']);
        }
        if ($emailChanged) {
            $this->setEmail($userData['email']);
        }

        // Save the updated user data
        $this->save();

        http_response_code(200);
        echo json_encode(['success' => 'Update successful']);
        return $this;
    }

    private function sendErrorResponse(int $statusCode, string $message) {
        http_response_code($statusCode);
        echo json_encode($message);
        exit;
    }


    public static function getUserByToken(string $authToken): ?self {
        $db = DB::getInstance();
        $entities = $db->query(
            'SELECT * FROM `' . User::getTableName() . '` WHERE auth_token=:token;', [':token' => $authToken], static::class
        );
        return $entities ? $entities[0] : null;
    }
}