<?php

namespace VersoBit\ResponsiveSlider\XF\Entity;

class User extends XFCP_User
{
	public function canManageResponsiveSlider(&$error = null): bool
	{
		return $this->is_admin || $this->hasPermission('general', 'vbResponsiveSliderManage');
	}
}
