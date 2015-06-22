<?

namespace Gearbox\ActiveRecord;

class Connection{
  static $current_environment = null;
  static $current_database_name = null;

  static $databases = [];
  static $adapters = [];
  static $connections = [];

  static function setAdapter($name, $type){
    self::$adapters[$name] = $type;
  }

  static function readerFile(){
    if(file_exists(\GearBox\Engine::gbaseDir()."/config/database.php")) include \GearBox\Engine::gbaseDir()."/config/database.php";
  }

  static function environment($environment, $call_or_database){
    self::$current_environment = $environment;
    if(is_array($call_or_database))
      self::database('default', $call_or_database);
    else
      $call_or_database();
  }

  static function database($database_name, $params){
    self::$databases[self::$current_environment] = isset(self::$databases[self::$current_environment]) ? self::$databases[self::$current_environment] : [];
    $params['class'] = self::$adapters[$params['adapter']];
    self::$databases[self::$current_environment][$database_name] = $params;
  }

  static function get($database_name = 'default'){
    $environment = \GearBox\Engine::getConfig()->environment;
    if(!isset(self::$connections[$database_name])){
      if(!isset(self::$databases[$environment][$database_name])){
        throw new \Exception('Database não encontrado.'.$environment.'=>'.$database_name);
      }
      $params = self::$databases[$environment][$database_name];
      self::$connections[$database_name] = new $params['class']($params);
    }
    return self::$connections[$database_name];
  }

}
