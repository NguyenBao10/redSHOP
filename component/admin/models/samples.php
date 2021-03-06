<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Samples
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       __DEPLOY_VERSION__
 */
class RedshopModelSamples extends RedshopModelList
{
    public function getTable($name = 'sample', $prefix = 'RedshopTable', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    /**
     * Construct class
     *
     * @param array $config An optional associative array of configuration settings.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id',
                'c.id',
                'name',
                'c.name',
                'published',
                'c.published',
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    public function getListQuery()
    {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('distinct(c.id),c.*')
            ->from($db->qn('#__redshop_catalog_sample', 'c'));

        // Filter by search in name.
        $search = $this->getState('filter.search');

        if ( ! empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where($db->qn('c.id') . ' = ' . (int)substr($search, 3));
            } else {
                $search = $db->q('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
                $query->where($db->qn('c.name') . ' LIKE ' . $search);
            }
        }

        // Add the list ordering clause.
        $orderCol  = $this->state->get('list.ordering', 'c.id');
        $orderDirn = $this->state->get('list.direction', 'asc');

        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param string $ordering  An optional ordering field.
     * @param string $direction An optional direction (asc|desc).
     *
     * @return  void
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function populateState($ordering = 'c.id', $direction = 'asc')
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');

        $this->setState('filter.search', $search);

        // List state information.
        parent::populateState($ordering, $direction);
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param string $id A prefix for the store id.
     *
     * @return  string  A store id.
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');

        return parent::getStoreId($id);
    }
}
