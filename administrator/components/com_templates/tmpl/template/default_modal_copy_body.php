<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_templates
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

?>
<div id="template-manager-copy" class="container-fluid">
	<div class="mt-2">
		<div class="col-md-12">
			<div class="control-group">
				<div class="control-label">
					<label for="new_name" class="modalTooltip" title="<?php echo HTMLHelper::_('tooltipText', 'COM_TEMPLATES_TEMPLATE_NEW_NAME_LABEL', 'COM_TEMPLATES_TEMPLATE_NEW_NAME_DESC'); ?>">
						<?php echo Text::_('COM_TEMPLATES_TEMPLATE_NEW_NAME_LABEL'); ?>
					</label>
				</div>
				<div class="controls">
					<input class="form-control" type="text" id="new_name" name="new_name">
				</div>
			</div>
		</div>
	</div>
</div>
