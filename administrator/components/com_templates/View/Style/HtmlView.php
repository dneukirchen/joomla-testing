<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_templates
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Templates\Administrator\View\Style;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View to edit a template style.
 *
 * @since  1.6
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The \JObject (on success, false on failure)
	 *
	 * @var   \JObject
	 */
	protected $item;

	/**
	 * The form object
	 *
	 * @var   \JForm
	 */
	protected $form;

	/**
	 * The model state
	 *
	 * @var   \JObject
	 */
	protected $state;

	/**
	 * The actions the user is authorised to perform
	 *
	 * @var    \JObject
	 * @since  4.0.0
	 */
	protected $canDo;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since   1.6
	 */
	public function display($tpl = null)
	{
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');
		$this->form  = $this->get('Form');
		$this->canDo = ContentHelper::getActions('com_templates');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new \JViewGenericdataexception(implode("\n", $errors), 500);
		}

		if ((!Multilanguage::isEnabled()) && ($this->item->client_id == 0))
		{
			$this->form->setFieldAttribute('home', 'type', 'radio');
			$this->form->setFieldAttribute('home', 'class', 'switcher');
		}

		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);

		$isNew = ($this->item->id == 0);
		$canDo = $this->canDo;

		ToolbarHelper::title(
			$isNew ? Text::_('COM_TEMPLATES_MANAGER_ADD_STYLE')
			: Text::_('COM_TEMPLATES_MANAGER_EDIT_STYLE'), 'eye thememanager'
		);

		$toolbarButtons = [];

		// If not checked out, can save the item.
		if ($canDo->get('core.edit'))
		{
			ToolbarHelper::apply('style.apply');
			$toolbarButtons[] = ['save', 'style.save'];
		}

		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create'))
		{
			$toolbarButtons[] = ['save2copy', 'style.save2copy'];
		}

		ToolbarHelper::saveGroup(
			$toolbarButtons,
			'btn-success'
		);

		if (empty($this->item->id))
		{
			ToolbarHelper::cancel('style.cancel');
		}
		else
		{
			ToolbarHelper::cancel('style.cancel', 'JTOOLBAR_CLOSE');
		}

		ToolbarHelper::divider();

		// Get the help information for the template item.
		$lang = Factory::getLanguage();
		$help = $this->get('Help');

		if ($lang->hasKey($help->url))
		{
			$debug = $lang->setDebug(false);
			$url = Text::_($help->url);
			$lang->setDebug($debug);
		}
		else
		{
			$url = null;
		}

		ToolbarHelper::help($help->key, false, $url);
	}
}
