<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$fieldSets = $this->form->getFieldsets('params');
foreach ($fieldSets as $name => $fieldSet) :
	$paramstabs = 'params-' . $name;
	echo HTMLHelper::_('bootstrap.addTab', 'myTab', $paramstabs, Text::_($fieldSet->label));

	if (isset($fieldSet->description) && trim($fieldSet->description)) :
		echo '<div class="alert alert-info">' . $this->escape(Text::_($fieldSet->description)) . '</div>';
	endif;
	?>
		<?php foreach ($this->form->getFieldset($name) as $field) : ?>
			<div class="control-group">
				<div class="control-label"><?php echo $field->label; ?></div>
				<div class="controls"><?php echo $field->input; ?></div>
			</div>
		<?php endforeach; ?>
	<?php echo HTMLHelper::_('bootstrap.endTab'); ?>
<?php endforeach; ?>
