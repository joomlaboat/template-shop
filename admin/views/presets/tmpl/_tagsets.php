<?php
/**
 * TEMPLATESHOP for Joomla!
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

//https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/number

function get_PageLayout_Tagsets()
{
    $sets=array();
    
    
    $tmp_params=[
                                    ['param'=>'Parameter','type'=>'list',
                                     
                                        'options'=>
                                        [
                                            ['value'=>'login','description'=>'Template Monster Affiliate Program Login'],
                                            ['value'=>'webapipassword','description'=>'Web API Password'],
                                            ['value'=>'presetcode','description'=>'Preset Code']

                                        ]
                                    ]
                ];
    
    $tags=array(
                ['tag'=>'templates','description'=>'Catalog of templates',
                    'params'=>[
                        ['param'=>'Number of templates per page','type'=>'number','min'=>1,'max'=>100,'step'=>1]
                    ]
                 
                ],
                ['tag'=>'pagination','description'=>'Inserts pagination bar',
                 'params'=>
                        [
                            ['param'=>'Buttons','type'=>'list','options'=>
                                        [
                                            ['value'=>'5','description'=>'<< 1 2 3 >>'],
                                            ['value'=>'6','description'=>'<< 1 2 3 4 >>'],
                                            ['value'=>'7','description'=>'<< 1 2 3 4 5 >>'],
                                            ['value'=>'8','description'=>'<< 1 2 3 4 5 6 >>'],
                                            ['value'=>'9','description'=>'<< 1 2 3 4 5 6 7 >>'],
                                            ['value'=>'10','description'=>'<< 1 2 3 4 5 6 7 8 >>']
                                            
                                            
                                        ]
                            ]
                        ]
                 
                 ],
                ['tag'=>'keywords','description'=>'Keywords used to filter templates'],
                ['tag'=>'filter','description'=>'Filter Applied'],
                ['tag'=>'category','description'=>'Category'],
                
                ['tag'=>'settings',
                        'description'=>'Returns Setting`s Value',
                        'params'=>$tmp_params,
                        'required'=>1
                    ]
                
                
                );
    
    $sets[]=['title'=>'Dynamic Values','tags'=>$tags];
    $sets[]=getHtmlTags();
    
    return $sets;
}


function get_ItemLayout_Tagsets()
{
    $sets=array();
    
    
        $tmp_params=[
                                    ['param'=>'Parameter','type'=>'list',
                                     
                                        'options'=>
                                        [
                                            ['value'=>'login','description'=>'Template Monster Affiliate Program Login'],
                                            ['value'=>'webapipassword','description'=>'Web API Password'],
                                            ['value'=>'presetcode','description'=>'Preset Code']
                                            

                                        ]
                                    ]
                ];
    
    $tags=array(
        
                    ['tag'=>'id','description'=>"Displays Template ID"],
                    ['tag'=>'price','description'=>"Regular price of the template"],
                    ['tag'=>'exclusiveprice','description'=>"Exclusive price of the template"],
                    ['tag'=>'dateofaddition','description'=>"The date when the template was added to the database in YYYY-MM-DD format"],
                    ['tag'=>'numberofdownloads','description'=>"Number of downloads"],
                    ['tag'=>'ishosting','description'=>"Equals 1 for Hosting Website templates"],
					['tag'=>'isflash','description'=>"Equals 1 for Flash Intro templates"],
					['tag'=>'isadult','description'=>"Equals 1 for Adult templates"],
                    ['tag'=>'isuniquelogo','description'=>"Equals 1 for unique Logo templates"],
                    ['tag'=>'isnonuniquelogo','description'=>"Equals 1 for non-unique Logo templates"],
                  
                    ['tag'=>'screenshots','description'=>"List of all screenshots"],
                    
                    ['tag'=>'type','description'=>"Template Type (Joomla, Wordpress etc.)"],
                    ['tag'=>'typeicon','description'=>"Template Type Icon",
                        'params'=>
                        [
                            ['param'=>'Background','type'=>'list','options'=>
                                        [
                                            ['value'=>'transparent','description'=>'Transparent'],
                                            ['value'=>'filled','description'=>'Filled']
                                        ]
                            ]
                        ]
                    ],
                    
                    ['tag'=>'screenshot',
                        'description'=>'Returns link to screenshot image',
                        'params'=>[
                                    ['param'=>'Width','type'=>'number','min'=>16,'max'=>10000],
                                    ['param'=>'Height','type'=>'number','min'=>16,'max'=>10000]/*,
                                    ['param'=>'Max Width','type'=>'number','min'=>16,'max'=>10000],
                                    ['param'=>'Min Height','type'=>'number','min'=>16,'max'=>10000]*/
                                   ]
                                   
                    ],
        
                    ['tag'=>'numberofpages','description'=>"Number of subpages (some templates are not full site templates but still contain several subpages)"],
                    ['tag'=>'isfull','description'=>"Equals 1 for Full Site templates"],
                    ['tag'=>'author','description'=>"Template`s author identifier"],
                    ['tag'=>'isnonuniquecorporate','description'=>"Equals 1 for unique Corporate Identity Packages"],
                    ['tag'=>'isuniquecorporate','description'=>"Equals 1 for non-unique Corporate Identity Packages"],
          
                    ['tag'=>'settings',
                        'description'=>'Returns Setting`s Value',
                        'params'=>$tmp_params,
                        'required'=>1
                    ]
    
                );
    
    $sets[]=['title'=>'Dynamic Values','tags'=>$tags];
    
    
    $tmp_params=[
                                    ['param'=>'Parameter','type'=>'list',
                                     
                                        'options'=>
                                        [
                                            ['value'=>'isadmin','description'=>'Super Administrator'],
                                            ['value'=>'ishosting','description'=>'Hosting Website templates'],
                                            ['value'=>'isflash','description'=>'Flash Intro templates'],
                                            ['value'=>'isadult','description'=>'Adult templates'],
                                            
                                            ['value'=>'isfull','description'=>'Full Site templates'],
                                            
                                            ['value'=>'isuniquelogo','description'=>'Logo templates'],
                                            ['value'=>'isnonuniquelogo','description'=>'Non-unique Logo templates'],
                                            ['value'=>'isuniquecorporate','description'=>'Unique Corporate Identity Packages'],
                                            ['value'=>'isnonuniquecorporate','description'=>'Non-unique Corporate Identity Packages']
                                            
                                        ]
                                    ]
                ];
    $tags=array(
                    ['tag'=>'if',
                        'description'=>'If statement. Rudimental function that checks if value exists or IS TRUE.',
                        'params'=>$tmp_params,
                        'required'=>1,
                        'default'=>'[if:*]....[endif:*]'
                    ],
                    
                    ['tag'=>'endif',
                        'description'=>'End If statement.',
                        'params'=>$tmp_params,
                        'required'=>1,
                        'visible'=>0
                    ]
                );
    
    $sets[]=['title'=>'If Statements','tags'=>$tags];
    
    $sets[]=getHtmlTags();
    
    return $sets;
}

function getHtmlTags()
{
    $tags=array(['tag'=>htmlentities('<p></p>'),'description'=>'Paragraph','type'=>'html'],
                ['tag'=>htmlentities('<img scr="" title="" alt="" />'),'description'=>'Image','type'=>'html']
                );
    
    $tags=['title'=>'HTML Tags','tags'=>$tags];
    
    return $tags;
}
