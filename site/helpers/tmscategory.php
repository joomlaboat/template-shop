<?php
/**
 * templateshop Joomla! 3.0 Native Component
 * @version 1.1.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');
require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_templateshop'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'categories.php');
 
class JFormFieldTMSCategory extends JFormFieldList
{
        /**
         * The field type.
         *
         * @var         string
         */
        public $type = 'TMSCategory';
 
        /**
         * Method to get a list of options for a list input.
         *
         * @return      array           An array of JHtml options.
         */
        protected function getOptions() 
        {
                $options = array();
                
                $records=TMSCategories::loadCategories();
                if(count($records)==0)
                {
                        $respond=TMSCategories::getCategories();
                        if($respond!='')
                        {
                                //error
                                $options[] = JHtml::_('select.option', 0, $respond);
                                return $options;
                        }
                        
                        $records=TMSCategories::loadCategories();
                }
                
                
                $options[] = JHtml::_('select.option', 0, '- '.JText::_( 'Select Category' ));
                
                if ($records)
                {
                        foreach($records as $record) 
                        {
                                $options[] = JHtml::_('select.option', $record->id, $record->categoryname);
                                
                        }
                }
                $options = array_merge(parent::getOptions(), $options);
                
                return $options;
        }
        
        
}
