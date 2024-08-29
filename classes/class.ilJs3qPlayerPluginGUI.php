<?php
 
include_once("./Services/COPage/classes/class.ilPageComponentPluginGUI.php");
 
class ilJs3qPlayerPluginGUI extends ilPageComponentPluginGUI {
	function executeCommand(): void	{
		global $ilCtrl;
 
		$next_class = $ilCtrl->getNextClass();
 
		switch($next_class) {
			default:
				$cmd = $ilCtrl->getCmd();
				if (in_array($cmd, array("create", "save", "edit", "update", "cancel"))) {
					$this->$cmd();
				}
				break;
		}
	}
 
	function insert(): void {
		global $tpl;
 
		$form = $this->initForm(true);
		$tpl->setContent($form->getHTML());
	}
 
	public function create(): void {
		global $tpl, $lng, $ilCtrl;
 
		$form = $this->initForm(true);
		if ($form->checkInput())
		{
			$properties = array(
				"playout_id" => $form->getInput("playout_id"),
				);
			if ($this->createElement($properties))
			{
                $tpl->setOnScreenMessage("success", $lng->txt("msg_obj_modified"), true);
				$this->returnToParent();
			}
		}
 
		$form->setValuesByPost();
		$tpl->setContent($form->getHtml());
	}
 
	function edit(): void {
		global $tpl;
  
		$form = $this->initForm();
		$tpl->setContent($form->getHTML());		
	}
 
	function update() {
		global $tpl, $lng, $ilCtrl;
 
		$form = $this->initForm(true);
		if ($form->checkInput()) {
			$properties = array(
				"playout_id" => $form->getInput("playout_id"),
			);

            if ($this->updateElement($properties)) {
                $tpl->setOnScreenMessage("success", $lng->txt("msg_obj_modified"), true);
				$this->returnToParent();
			}
		}
 
		$form->setValuesByPost();
		$tpl->setContent($form->getHtml());
	}
 
	public function initForm($a_create = false)	{
		global $lng, $ilCtrl;
 
		include_once("Services/Form/classes/class.ilPropertyFormGUI.php");
		$form = new ilPropertyFormGUI();
 
		$playoutIdInput = new ilTextInputGUI($this->getPlugin()->txt("playout_id"), "playout_id");
		$playoutIdInput->setMaxLength(40);
		$playoutIdInput->setSize(40);
		$playoutIdInput->setRequired(true);
		$form->addItem($playoutIdInput);
 
		if (!$a_create) {
			$prop = $this->getProperties();
			$playoutIdInput->setValue($prop["playout_id"]);
		}
 
		// save and cancel commands
		if ($a_create) {
			$this->addCreationButton($form);
			$form->addCommandButton("cancel", $lng->txt("cancel"));
			$form->setTitle($this->getPlugin()->txt("cmd_insert"));
		} else {
			$form->addCommandButton("update", $lng->txt("save"));
			$form->addCommandButton("cancel", $lng->txt("cancel"));
			$form->setTitle($this->getPlugin()->txt("edit_ex_el"));
		}
 
		$form->setFormAction($ilCtrl->getFormAction($this));
 
		return $form;
	}
 
	function cancel() {
		$this->returnToParent();
	}
 
	function getElementHTML($a_mode, array $a_properties, $a_plugin_version): string {
		$pl = $this->getPlugin();
		$tpl = $pl->getTemplate("tpl.content.html");

        if ($a_mode === 'edit') {
            $tpl->setVariable("AUTOPLAY", 'autoplay: false,');
        }

        $tpl->setVariable("PLAYOUT_ID", $a_properties["playout_id"]);
        $tpl->setVariable("RND", rand(0, 1000000));
        $tpl->setVariable("MODE", $a_mode);

        $tpl->parseCurrentBlock();
		return $tpl->get();
	}
}
 
?>