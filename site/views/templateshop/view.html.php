<?php
/**
 * TEMPLATESHOP Joomla! 3.0 Native Component
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the TEMPLATESHOP Component
 */

        
        class TEMPLATESHOPViewTEMPLATESHOP extends JViewLegacy
        {
                // Overwriting JView display method
                function display($tpl = null) 
                {
                        $this->templateshopcode = $this->get('templateshopcode');
                
                        // Check for errors.
                        if (count($errors = $this->get('Errors'))) 
                        {
                                JFactory::getApplication()->enqueueMessage(implode('<br />', $errors), 'error');
                                return false;
                        }
                
 
                        // Display the view
                        parent::display($tpl);
                }
        }
