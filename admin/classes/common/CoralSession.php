<?php

class CoralSession
{
  private static $instance;

  private static function open_for_read() {
    if (!isset(self::$instance)) {
       session_start();
       session_write_close();
       self::$instance = $_SESSION;
     }
    return self::$instance;
  }
  
  public static get($key) {
    self::open_for_read();
    if (isset($_SESSION[$key])) {
      return $_SESSION[$key]
    } else {
      return null;
    }
  }
  
  public static set($key, $value) {
    session_start();
    $_SESSION[$key] = $value;
    session_write_close();
    self::$instance = $_SESSION
  }
  
}
  
  
  
  
  
?>