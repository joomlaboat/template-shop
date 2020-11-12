<?php
/**
 * templateshop Joomla! 3.0 Native Component
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');
require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'types.php');
 
class JFormFieldTMSType extends JFormFieldList
{
        /**
         * The field type.
         *
         * @var         string
         */
        public $type = 'TMSType';
 
        /**
         * Method to get a list of options for a list input.
         *
         * @return      array           An array of JHtml options.
         */
        protected function getOptions() 
        {
                $options = array();
                
                $records=TMSTypes::loadTypes();
                
                $options[] = JHtml::_('select.option', '', '- '.JText::_( 'Select Type' ));
                
                if ($records)
                {
                        foreach($records as $record) 
                        {
                                $options[] = JHtml::_('select.option', $record->templatetype, $record->templatetype);
                                
                        }
                }
                
                $options = array_merge(parent::getOptions(), $options);
                
                return $options;
        }
        
        
}
