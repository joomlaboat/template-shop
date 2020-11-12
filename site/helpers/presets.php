<?php
/**
 * TEMPLATESHOP Joomla! Native Component
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * TEMPLATESHOP Form Field class for the Template Shop component
 */
class JFormFieldPresets extends JFormFieldList
{
        /**
         * The field type.
         *
         * @var         string
         */
        protected $type = 'presets';
 
        /**
         * Method to get a list of options for a list input.
         *
         * @return      array           An array of JHtml options.
         */
        protected function getOptions() 
        {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('id,presetname');
                $query->from('#__templateshop_presets');
                $db->setQuery((string)$query);
                $messages = $db->loadObjectList();
                $options = array();
                
                
                if ($messages)
                {
                        foreach($messages as $message) 
                        {
                                $options[] = JHtml::_('select.option', $message->id, $message->presetname);
                                
                        }
                }
                
                $options = array_merge(parent::getOptions(), $options);
                return $options;
        }
}
