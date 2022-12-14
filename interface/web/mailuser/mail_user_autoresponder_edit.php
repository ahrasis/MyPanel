<?php
/*
Copyright (c) 2012, Till Brehm, ISPConfig UG
All rights reserved.

Redistribution and use in source and binary forms, with or without modification,
are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice,
      this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice,
      this list of conditions and the following disclaimer in the documentation
      and/or other materials provided with the distribution.
    * Neither the name of ISPConfig nor the names of its contributors
      may be used to endorse or promote products derived from this software without
      specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/


/******************************************
* Begin Form configuration
******************************************/

$tform_def_file = "form/mail_user_autoresponder.tform.php";

/******************************************
* End Form configuration
******************************************/

require_once '../../lib/config.inc.php';
require_once '../../lib/app.inc.php';

//* Check permissions for module
$app->auth->check_module_permissions('mailuser');

// Loading classes
$app->uses('tpl,tform,tform_actions');
$app->load('tform_actions');

class page_action extends tform_actions {

	function onShow() {

		$this->id = $_SESSION['s']['user']['mailuser_id'];

		parent::onShow();

	}

	function onSubmit() {

		$this->id = $_SESSION['s']['user']['mailuser_id'];

		//* if autoresponder checkbox not selected, do not save dates
		if (!isset($_POST['autoresponder']) && array_key_exists('autoresponder_start_date', $_POST)) {
			$this->dataRecord['autoresponder_start_date'] = array_map(function($item) { return 0;}, $this->dataRecord['autoresponder_start_date']);
			$this->dataRecord['autoresponder_end_date'] = array_map(function($item) { return 0;}, $this->dataRecord['autoresponder_end_date']);
			
			/* To be used when we go to PHP 7.x as min PHP version
			$this->dataRecord['autoresponder_start_date'] = array_map( function ('$item') { 'return 0;' }, $this->dataRecord['autoresponder_start_date']);
			$this->dataRecord['autoresponder_end_date'] = array_map( function ('$item') { 'return 0;' }, $this->dataRecord['autoresponder_end_date']);
			*/
			
		}

		parent::onSubmit();

	}

	function onShowEnd() {
		global $app;
		// Is autoresponder set?
		if ($this->dataRecord['autoresponder'] == 'y') {
			$app->tpl->setVar("ar_active", 'checked="checked"');
		} else {
			$app->tpl->setVar("ar_active", '');
		}

		if($this->dataRecord['autoresponder_subject'] == '') {
			$app->tpl->setVar('autoresponder_subject', $app->tform->lng('autoresponder_subject'));
		} else {
			$app->tpl->setVar('autoresponder_subject', $this->dataRecord['autoresponder_subject'], true);
		}

		parent::onShowEnd();
	}


}

$app->tform_actions = new page_action;
$app->tform_actions->onLoad();

?>
