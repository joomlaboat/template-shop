/**
 * TEMPLATESHOP Joomla! Native Component
 * @version 1.1.5
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/


function tmsPaginationSet(page,presetid)
{
    var modalbox=document.getElementById("tms-pagination_modal_box");
    modalbox.style.display = "block";
            
    var urlobj=document.getElementById("tms-current-url");
    var url=urlobj.innerHTML;
    //alert(url);

    var obj=document.getElementById("tms_wrapper_"+presetid);
    var templates_wrapper=document.getElementById("templates_wrapper_"+presetid);
    
    
    var http = null;
    var params = "";
    params+="&tmpl=component";
    params+="&clean=1";
    params+="&tmspage="+page;
    
    
    if (!http)
    {
        http = CreateHTTPRequestObject ();   // defined in ajax.js
    }
    
    if (http)
    {
     //   alert(url);
        http.open("POST", url, true);
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        http.onreadystatechange = function()
        {
            
            if (http.readyState == 4)
            {
                var res=http.response;
                
                // Fade out 
                templates_wrapper.style.opacity = 0;
                
                // Fade in 
                setTimeout(function(){obj.innerHTML=res;
                           var templates_wrapper2=document.getElementById("templates_wrapper_"+presetid);
                           templates_wrapper2.style.opacity = 0;
                           },250);
                
                setTimeout(function(){
                    var templates_wrapper3=document.getElementById("templates_wrapper_"+presetid);
                    templates_wrapper3.style.opacity = 1;
                    },300);
                
                
                
                modalbox.style.display = "none";
                return false;
                
            }
        };
        http.send(params);
    }
    else
    {
        modalbox.style.display = "none";
        obj.innerHTML="<span style='color:red;'>Cannot Save</span>";
    }
}



