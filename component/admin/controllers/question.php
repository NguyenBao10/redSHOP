<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Controller Country Detail
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 * @since       __DEPLOY_VERSION__
 */
class RedshopControllerQuestion extends RedshopControllerForm
{
	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_item = 'question';

	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_list = 'questions';

	/**
	 * Save question
	 *
	 * @param   integer  $send    Send Question?
	 * @param   string   $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @todo    I know, I know this is not a proper way. But we needs to move to form way.
	 *
	 * @return  boolean  True if successful, false otherwise.
	 */
	public function save($send = 0, $urlVar = null)
	{
		$jinput = JFactory::getApplication()->input;
		$post = $jinput->post->getArray();
		$data = $post['jform'];

		$data['id'] = $post['id']? $post['id']: 0;

		$model = $this->getModel('Question');

		if ($data['id'] == 0)
		{
			$user = JFactory::getDbo();

			$data['user_name'] 		= $user->username;
			$data['user_email']		= $user->email;
			$data['question_date'] 	= time();
			$data['parent_id'] 		= 0;
		}

		$row = $model->save($data);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_QUESTION_DETAIL');
		}

		if ($send == 1)
		{
			redshopMail::getInstance()->sendAskQuestionMail($data['id']);
		}

		$this->setRedirect('index.php?option=com_redshop&view=questions', $msg);
	}

	/**
	 * Send function
	 * 
	 * @return void
	 */
	public function send()
	{
		$this->save(1);
	}

	/**
	 * Remove function
	 * 
	 * @return void
	 */
	public function remove()
	{
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('Question');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=questions', $msg);
	}

	/**
	 * Remove Answer function
	 *  
	 * @return void
	 */
	public function removeAnswer()
	{
		$cid = JRequest::getVar('aid', array(0), 'post', 'array');
		$qid = JRequest::getVar('id', 0, 'post', 'int');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('Question');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&task=question.edit&id=' . $qid, $msg);
	}

	/**
	 * Send Answer
	 * 
	 * @return void
	 */
	public function sendAnswer()
	{
		$cid = JRequest::getVar('aid', array(0), 'post', 'array');
		$qid = JRequest::getVar('id', 0, 'post', 'int');

		for ($i = 0, $in = count($cid); $i < $in; $i++)
		{
			$redshopMail = redshopMail::getInstance();
			$redshopMail->sendAskQuestionMail($cid[$i]);
		}

		$msg = JText::_('COM_REDSHOP_ANSWER_MAIL_SENT');
		$this->setRedirect('index.php?option=com_redshop&task=question.edit&id=' . $qid, $msg);
	}

	/**
	 * logic for save an order
	 *
	 * @access public
	 * @return void
	 */
	public function saveorder()
	{
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		$order = JRequest::getVar('order', array(), 'post', 'array');

		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);
		$model = $this->getModel('Question');
		$model->saveorder($cid, $order);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=questions', $msg);
	}
}
