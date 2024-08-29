<?php
 
include_once("./Services/COPage/classes/class.ilPageComponentPlugin.php");
 
class ilJs3qPlayerPlugin extends ilPageComponentPlugin {
    public function getPluginName(): string
    {
        return "Js3qPlayer";
    }

    public function isValidParentType(string $a_parent_type): bool
    {
		return true;
    }
}