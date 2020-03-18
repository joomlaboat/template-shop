<?php
/**
 * Template Shop Joomla! Native Component
 * @version 1.1.5
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$jinput = JFactory::getApplication()->input;
$clean=$jinput->getInt('clean',0);
if($clean==0)
{
    $document = JFactory::getDocument();
    $document->addCustomTag('<link href="/media/com_templateshop/css/style.css" rel="stylesheet">');

    $app		= JFactory::getApplication();
    $params=$app->getParams();

    if ( $params->get( 'show_page_heading', 1 ) )
    {
        echo '<div class="componentheading'.$params->get('pageclass_sfx').'"><h1>'.$params->get( 'page_title' ).'</h1></div>';
    }
}
else
{
    if (ob_get_contents()) ob_end_clean();
}

echo $this->templateshopcode;


if($clean==1)
{
    die;
}
?>

