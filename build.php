<?

Gearbox\Engine::addGear([
	"name" => "Gearbox::ActiveRecord",
	"loader" => function($class_name){
		if(\Gearbox\Engine\Loader::classicGearLoader($class_name, 'ActionRecord', 'activerecord/lib/active_record/')){
    	return true;
		}
		return false;
	}
]);
