<?php
/**
 * Performs the FoundationFormTransformation on UserForm
 * @author Ryan Wachtl
 * @package foundationforms
 */
class FoundationCustomUserFormExtension extends DataExtension {

	public function updateForm()
	{
		$this->owner->addExtraClass('custom');
	}

}
