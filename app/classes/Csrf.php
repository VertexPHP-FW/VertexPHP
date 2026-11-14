<?php

    namespace classes;
class Csrf
{
  private $length;
  private $token;
  private $token_expiration;
  private $expiration_time;

  public function __construct($expiration_time = 600, $length = 32)
  {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }
    $this->expiration_time = $expiration_time;
    $this->length = $length;

    if (!isset($_SESSION['csrf_token'])) {
      $this->generate();
      $this->storeToken();
    } else {
      $this->token = $_SESSION['csrf_token']['token'];
      $this->token_expiration = $_SESSION['csrf_token']['expiration'];
    }    
  
    session_write_close();

  }

  private function generate()
  {
    $this->token = bin2hex(random_bytes($this->length));
    $this->token_expiration = time() + $this->expiration_time;
  }

  private function storeToken()
  {
    $_SESSION['csrf_token'] = [
      'token' => $this->token,
      'expiration' => $this->token_expiration
    ];
  }

  public static function validate($csrf_token, $validate_expiration = false){
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }
    if (!isset($_SESSION['csrf_token'])) {
      session_write_close();
      return false;
    }
    $session_token = $_SESSION['csrf_token']['token'];
    $session_expiration = $_SESSION['csrf_token']['expiration'];

    if ($validate_expiration && $session_expiration < time()) {
      session_write_close();
      return false;
    }
    session_write_close();
    return hash_equals($session_token, $csrf_token);
  }

  public function get_token()
  {
    return $this->token;
  }

  public function get_expiration()
  {
    return $this->token_expiration;
  }
}
