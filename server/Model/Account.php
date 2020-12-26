<?php

namespace Model;

use Intervention\Image\ImageManager as Img;
use Model\Conn;
use Config\Table;
use Config\Output;
use Config\Path;
use Logger;

class Account
{
  /**
   * Obter conta.
   * 
   * @param integer $id
   */
  public static function get(int $id): object
  {
    $q = Conn::table(Table::ACCOUNTS)
      ::select(['id', 'name', 'email'])
      ::where('id', $id)
      ::send();

    if (!$q) {
      throw new \Exception(Output::ACCOUNT_NO_CONTENT);
    }

    $account = $q->fetch_object();
    $account->permissions = self::getPermissions($id);

    return $account;
  }

  /**
   * Obter todas as contas.
   *
   * @return array
   */
  public static function getAll(): array
  {
    $q = Conn::table(Table::ACCOUNTS)
      ::select(['id', 'name', 'email'])
      ::send();

    return $q ? $q->fetch_all(MYSQLI_ASSOC) : [];
  }

  /**
   * Adicionar conta.
   *
   * @param array $object { email, password, name, permissions }
   * @return array
   */
  public static function add(object $data): bool
  {
    // Campos obrigatórios do formulário de cadastro.
    $fields = ['email', 'password', 'name', 'permissions'];

    // Verificar se não contém, dentre os dados fornecidos pelo usuário, algum campo requerido.
    foreach ($fields as $field) {
      if (!property_exists($data, $field)) {
        throw new \Exception(Output::ACCOUNT_CREATION['MISSING_FIELD']['message'], Output::ACCOUNT_CREATION['MISSING_FIELD']['code']);
      }
    }

    // Validar dados.
    foreach ($data as $key => $val) {
      if (!is_string($val)) continue;

      $data->{$key} = addslashes($val);
    }

    // Verificar se já existe cadastro com este email.
    $registered = self::registeredEmail($data->email);

    if ($registered) {
      throw new \Exception(Output::ACCOUNT_CREATION['EXISTING_EMAIL']['message'], Output::ACCOUNT_CREATION['EXISTING_EMAIL']['code']);
    }

    // Salvar imagem
    // if (!empty($data->img)) {
    //   $manager = new Img(array('driver' => 'gd'));
    //   $make = $manager->make($data->img['tmp_name']);
    //   $img = Path::AVATARS . '/' . dechex(time()) . dechex(mt_rand()) . '.jpg';
    //   $make->save(SYSTEM_ROOT . $img);
    // } else {
    //   $img = '';
    // }

    // Criptografar senha.
    $password = password_hash($data->password, PASSWORD_DEFAULT);

    // Inserir conta no banco de dados
    Conn::table(Table::ACCOUNTS)
      ::insert(
        ['name', 'email', 'password'],
        [
          "'$data->name'",
          "'$data->email'",
          "'$password'",
        ]
      )
      ::send();

    // Buscar id da conta inserida no banco de dados.
    $id = Conn::$conn->insert_id;

    Logger::account(Logger::ACCOUNT_ADD, ACCOUNT_ID, $id);

    // Inserir permissões.
    self::setPermissions($id, $data->permissions);

    return true;
  }

  /**
   * Atualizar conta.
   *
   * @param int $id.
   * @param object $data Dados da conta.
   * @return boolean
   */
  public static function update(int $id, object $data): bool
  {
    // Campos obrigatórios do formulário de alteração.
    $fields = ['email', 'name', 'permissions'];

    // Verificar se não contém, dentre os dados fornecidos pelo usuário, algum campo requerido.
    foreach ($fields as $field) {
      if (!property_exists($data, $field)) {
        throw new \Exception(Output::ACCOUNT_CREATION['MISSING_FIELD']['message'], Output::ACCOUNT_CREATION['MISSING_FIELD']['code']);
      }
    }

    // Validar dados.
    foreach ($data as $key => $val) {
      if (!is_string($val)) continue;

      $data->{$key} = addslashes($val);
    }

    // Verificar se já existe cadastro com este email.
    $registered = self::registeredEmail($data->email, $id);

    if ($registered) {
      throw new \Exception(Output::ACCOUNT_CREATION['EXISTING_EMAIL']['message'], Output::ACCOUNT_CREATION['EXISTING_EMAIL']['code']);
    }

    // Salvar imagem
    // if (!empty($data->img)) {
    //   $manager = new Img(array('driver' => 'gd'));
    //   $make = $manager->make($data->img['tmp_name']);
    //   $img = Path::AVATARS . '/' . dechex(time()) . dechex(mt_rand()) . '.jpg';
    //   $make->save(SYSTEM_ROOT . $img);
    // } else {
    //   $img = '';
    // }

    $update = [
      'alteredAt' => "CURRENT_TIMESTAMP()",
      'name' => "'$data->name'",
      'email' => "'$data->email'"
    ];

    // Criptografar senha.
    if ($data->password) {
      $password = password_hash($data->password, PASSWORD_DEFAULT);
      $update['password'] = "'$password'";
    }

    // Atualizar conta no banco de dados
    Conn::table(Table::ACCOUNTS)
      ::update($update)
      ::where('id', $id)
      ::send();

    Logger::account(Logger::ACCOUNT_UPDATE, ACCOUNT_ID, $id);

    // Inserir permissões.
    self::setPermissions($id, $data->permissions);

    return true;
  }

  /**
   * Remover conta por id.
   *
   * @param integer $id
   * @return boolean
   */
  public static function remove(int $id): bool
  {
    if ($id === ACCOUNT_ID) {
      throw new \Exception(Output::ACCOUNT_REMOVE_UNAUTHORIZED);
    }

    $q = Conn::table(Table::ACCOUNTS)
      ::deleteWhere('id', $id)
      ::send();

    Logger::account(Logger::ACCOUNT_REMOVE, ACCOUNT_ID, $id);

    return $q;
  }

  /**
   * Verificar se existe uma conta registrada com o e-mail espeficidado.
   *
   * @param string $email
   * @param int|null $accountId
   * @return array|null Retorna o id e senha da conta se houver, se não retorna null.
   */
  public static function registeredEmail(string $email, ?int $accountId = null): ?array
  {
    $email = addslashes($email);
    $q = null;

    if (!$accountId) {
      $q = Conn::table(Table::ACCOUNTS)
        ::select(['id', 'password'])
        ::where('email', "'$email'")
        ::send();
    } else {
      $q = Conn::table(Table::ACCOUNTS)
        ::select(['id', 'password'])
        ::where('email', "'$email'")
        ::and('id', $accountId, "!=")
        ::send();
    }

    if (!$q) {
      return null;
    }

    return $q->fetch_row();
  }

  /**
   * Obter permissões da conta.
   *
   * @param integer $id
   * @return array
   */
  public static function getPermissions(int $id): array
  {
    $q = Conn::table(Table::ACCOUNTS_PERMISSIONS)
      ::select(['modules_id'])
      ::where('accounts_id', $id)
      ::send();

    $permissions = [];

    if ($q) {
      $permissions = array_merge(...$q->fetch_all(MYSQLI_NUM));
    }

    return $permissions;
  }

  /**
   * Definir permissões da conta.
   *
   * @param integer $id
   * @param array $permissions
   * @return bool
   */
  private static function setPermissions(int $id, array $permissions): bool
  {
    $permissions = array_map(function ($p) use ($id) {
      return [$id, $p];
    }, $permissions);

    Conn::table(Table::ACCOUNTS_PERMISSIONS)
      ::deleteWhere('accounts_id', $id)
      ::send();

    if (count($permissions)) {

      Conn::table(Table::ACCOUNTS_PERMISSIONS)
        ::insert(
          ['accounts_id', 'modules_id'],
          $permissions,
          true
        )
        ::send();
    }

    Logger::account(Logger::ACCOUNT_SET_PERMISSIONS, ACCOUNT_ID, $id);
    return true;
  }
}
