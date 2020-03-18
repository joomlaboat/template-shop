var codemirror_editors=[];
var codemirror_active_index=0;
var temp_params_tag="";
var temp_params_count=0;

var tag_sets=[];
var tmpobject=null;

function showModal(tag,top,left,line,positions,isnew)
{
    var form_content=getParamEditForm(tag,line,positions,isnew);
    if(form_content==null)
        return false;
    
            var obj=document.getElementById("layouteditor_modal_content_box");
            obj.innerHTML=form_content;
            
            // Get the modal
            var modal = document.getElementById('layouteditor_Modal');

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("layouteditor_close")[0];

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            };

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };
            
            var box=document.getElementById("layouteditor_modalbox");
            
            
            modal.style.display = "block";
            
            
            var w=box.offsetWidth;
            var h=box.offsetHeight;
            
            var d = document;
            e = d.documentElement;
            
            var doc_w=e.clientWidth;
            var doc_h=e.clientHeight;
            
            var x=left-w/2;
            if(x<10)
                x=10;
                
            if(x+w+10>doc_w)
                x=doc_w-w-10;
                
            var y=top-h/2;

            if(y<50)
                y=50;
                
                
            if(y+h+50>doc_h)
            {
                y=doc_h-h-50;
            }
            
            box.style.left=x+'px';
            box.style.top=y+'px'
        }

function addTag(index,tag,param_count)
{
    if(param_count>0)
    {
        var cm=codemirror_editors[index];
        var cr=cm.getCursor();
        
        var positions=[cr.ch,cr.ch];
        var mousepos=cm.cursorCoords(cr,"window");
        
        var cleantagname=getCleanTagName(atob(tag));
        
        showModal(cleantagname,mousepos.top,mousepos.left,cr.line,positions,1);
    }
    else
        updateCodeMirror(atob(tag));
}

function updateCodeMirror(text)
{
    var editor = codemirror_editors[codemirror_active_index];
    
    var doc = editor.getDoc();
    var cursor = doc.getCursor();
    doc.replaceRange(text, cursor);
}



    function textarea_findindex(code)
    {
        for(var i=0;i<text_areas.length;i++)
        {
        	if(text_areas[i][0]==code)
        		return text_areas[i][1];
        }
        return -1;
    }
      
    function addTabExtraEvents()
    {
      
        jQuery(function($)
        {
            $(".nav-tabs a").click(function (e)
            {
            	var a=e.target.href;
            	var codepair=a.split("#");
            	var code=codepair[1];
            	var index=textarea_findindex(code);

                if(index!=-1)
                {
                    renderTagSets(index);
                    
                    setTimeout(function()
                               {
                                    codemirror_active_index=index;
                                    var cm=codemirror_editors[index];
                                    cm.refresh();
                                    
                                    cm.on('dblclick', function(e)
                                    {
                                        var cr=cm.getCursor();
                                        //alert(cr.ch);
                                        //alert(cr.line);
                                        var line=cm.getLine(cr.line);
                                        
                                        var positions=findTagInLine(cr.ch,line);
                                        
                                        if(positions!=null)
                                        {
                                            var tag=line.substring(positions[0]+1, positions[1]-1);
                                            
                                            var mousepos=cm.cursorCoords(cr,"window");
                                            
                                            showModal(tag,mousepos.top,mousepos.left,cr.line,positions,0);
                                            //console.log(h);
                                            //var event=h.event;
                                            //e.preventDefault();
                                            //event.stopPropagation();
                                             //this.stopPropagation().preventDefault();
                                            
                                            //cm.off('contextmenu');
                                        }
                                        
                                    },true);
                                    
                                    //codemirror_editors[index].setOption("mode", "layouteditor");
                                    
                               }, 100);
                    
                }
            });
        });
        
        
        
        
        
        

	}

    function findTagInLine(ch,str)
        {
            var start_pos=-1;
            var end_pos=-1;
            var level=1;
            for(var i=ch;i>-1;i--)
            {
                if(str[i]==']' && i!=ch)
                    level++;
                
                if(str[i]=='[')
                {
                    level--;
                    if(level==0)
                    {
                        start_pos=i;
                        break;
                    }
                }
                
                
            }
            if(start_pos==-1)
                return null;
            
            level=1;
            for(var i2=ch;i2<str.length;i2++)
            {
                if(str[i2]=='[')
                    level++;
                
                if(str[i2]==']')
                {
                    level--;
                    if(level==0)
                    {
                        end_pos=i2;
                        break;
                    }
                }
                
                
            }

            if(end_pos==-1)
                return null;
            
            
            if(start_pos<=ch && end_pos>=ch)
                return [start_pos,end_pos+1];
            
            return null;
        }
        
        
    function findTagObjectByName(lookfor_tag)
    {
        /*
        var pair=lookfor_tag_.split(":");
        lookfor_tag=pair[0];
        */
        
        for(var s=0;s<tag_sets.length;s++)
        {
            var sets=tag_sets[s];
            
            for(var i=0;i<sets.length;i++)
            {
                var tags=sets[i].tags;
                for(var t=0;t<tags.length;t++)
                {
                    var tag=tags[t];
                    var n="";
                    if(typeof tag === 'object')
                        n=tag.tag;
                    else
                        n=tag;
                        
                    
                    if(lookfor_tag==n)
                        return tag;
                }
                
            }
            
        }
        return null;
    }

    function getCleanTagName(tag)
    {
        if(tag[0]=='[' && tag[tag.length-1]==']')
        {
            return tag.substr(1, tag.length-2);
        }
        else
            return tag;
    }

    function getParamEditForm(tag,line,positions,isnew)
    {
        var tag_pair=tag.split(":");
        temp_params_tag=tag_pair[0];
        
        var tagobject=findTagObjectByName(temp_params_tag);
        if(tagobject==null)
            return null;
        
        var description="";
        
        if(typeof tagobject === 'object')
        {
            description=tagobject.description;
        }
        
        
        
        var result="<h3>"+temp_params_tag+"</h3>";
        
        if(description!="")
            result+="<p>"+description+"</p>";
        
        
        if(Array.isArray(tagobject.params))
        {
            result+='<table style="width:90%;"><tbody>';
            var params=[];
            
            if(tag_pair.length>1)
                params=tag_pair[1].split(",");
            
            temp_params_count=tagobject.params.length;
            
            for(var i=0;i<tagobject.params.length;i++)
            {
                var param=tagobject.params[i];
                if(param.visible==null || param.visible!=0)
                {
                    result+='<tr><td>'+param.param+'</td><td>:</td><td>';
                    var vlu="";
                    if(params.length>i)
                        vlu=params[i];
                
                    result+=renderInputBox('layouteditor_param_'+i,param,vlu);
                    result+='</td></tr>';
                }
            }
            
            result+='</tbody></table>';
        }
        else
        {
				temp_params_count=0;
            result+='<p style="font-style:italic;">No Parameters</p>';
        }
        

        tmptagobject=tagobject;
        result+='<div style="text-align:center;"><button id="clsave" onclick=\'return saveParams(event,'+line+','+positions[0]+','+positions[1]+','+isnew+');\' class="btn btn-small button-apply btn-success">Save</button></div>';
        
        return result;
    }
    
    
    function renderInput_List(id,param,value)
    {
        var result="";
        
                if(Array.isArray(param.options))
                {
                
                    result+='<select id="'+id+'" style="width:100%;">';
                    
                    for(var o=0;o<param.options.length;o++)
                    {
                        var opt=param.options[o];
                        
                        var vlu="";
                        var desc="";
                        if(typeof opt === 'object')
                        {
                            vlu=opt.value;
                            desc=opt.description;
                        }
                        else
                        {
                            vlu=opt;
                            desc=opt;
                        }
                        
                        if(vlu==value)
                            result+='<option value="'+vlu+'" selected="selected">'+desc+'</option>';
                        else
                            result+='<option value="'+vlu+'" >'+desc+'</option>';
                    }
                    
                    result+='</select>';
                }
                
        return result;
    }
    
    function renderInputBox(id,param,vlu)
    {
        var result='';
        if(param.type!=null)
        {
            if(param.type=="number")
            {
                var extra="";
                if(param.min!=null)
                    extra+=' min="'+param.min+'"';
                    
                if(param.max!=null)
                    extra+=' max="'+param.max+'"';
                    
                if(param.min!=null)
                    extra+=' step="'+param.step+'"';
                    
                result+='<input type="number" id="'+id+'" value="'+vlu+'" style="width:100%;" '+extra+'>';
            }
            else if(param.type=="list")
            {
                result=renderInput_List(id,param,vlu);
            }
            else
                result+='<input type="text" id="'+id+'" value="'+vlu+'" style="width:100%;">';
        }
        else
            result+='<input type="text" id="'+id+'" value="'+vlu+'" style="width:100%;">';
        
        return result;
    }

    function saveParams(e,line_number,pos1,pos2,isnew)
    {
        e.preventDefault();
        var result='';//'['+temp_params_tag;
        
        var params=[];
        var count=0;

        for(var i=0;i<temp_params_count;i++)
        {
            var obj=document.getElementById('layouteditor_param_'+i);
            var v=obj.value;
            
            params.push(v);
            
            if(v!="")
            	count=i+1; //to include all previous parameters even if they are empty
        }

        
        var tmp_params='';
        
        var newparams=[];
        if(count>0)
        {
        	for(var i=0;i<count;i++)
        		newparams.push(params[i]);
        		
            tmp_params=newparams.join(",");
        }
        
        
        
        if(isnew==1 && tmptagobject.default!=null && tmptagobject.default!='')
        {
            if(tmp_params!="")
                result=tmptagobject.default.replace(/\*/g,tmp_params);
            else
                result=tmptagobject.default;
        }
        else
        {
            result='['+temp_params_tag;
            
            if(count>0)
                result+=':'+tmp_params;
        
            result+=']';    
        }
 
        var cursor_from = {line:line_number,ch:pos1};
        var cursor_to = {line:line_number,ch:(pos2)};
        
        var editor = codemirror_editors[codemirror_active_index];
        
        /*alert(result);
        alert(cursor_from.line);
        alert(cursor_from.ch);
        
        alert(cursor_to.line);
        alert(cursor_to.ch);
        */
        
        var doc = editor.getDoc();
        doc.replaceRange(result, cursor_from,cursor_to,"");
                
        
        var modal = document.getElementById('layouteditor_Modal');
        modal.style.display = "none";
        return false;
    }

    function define_cmLayoutEditor()
    {
        
        define_cmLayoutEditor1('layouteditor','text/html');
        //define_cmLayoutEditor2();
    }
    
    function define_cmLayoutEditor1(modename,nextmodename)
    {
        CodeMirror.defineMode(modename, function(config, parserConfig)
        {
            var layouteditorOverlay =
            {
                token: function(stream, state)
                {
                    var ch;
                    if (stream.match("["))
                    {
                        var hasParameters=false;
                        var level=1;
                        while ((ch = stream.next()) != null)
                        {
                            if (ch == "[" )
                            {
                                level++;
                            }
                            
                            if (ch == "]" )
                            {
                                level-=1;
                                if(level==0)
                                {
                                    stream.eat("]");
                                
                                    if(hasParameters)
                                        return "layouteditortagwithparams";
                                    else
                                        return "layouteditortag";
                                }
                            }
                            
                            if(ch==':' && level==1)
                            {
                                hasParameters=true;
                            }
                        }
                    }
                    while (stream.next() != null && !stream.match("[", false)) {}
                    return null;
                }
            };
            
                       
            return CodeMirror.overlayMode(CodeMirror.getMode(config, parserConfig.backdrop || nextmodename), layouteditorOverlay);
        });
    }
    
    function define_cmLayoutEditor2(modename,nextmodename)
    {
        CodeMirror.defineMode(modename, function(config, parserConfig)
        {
            var layouteditorOverlay =
            {
                token: function(stream, state)
                {
                    var ch;
                    if (stream.match("["))
                    {
                        
                        var hasParameters=false;
                        var level=1;
                        while ((ch = stream.next()) != null)
                        {
                            if (ch == "[" )
                            {
                                level++;
                            }
                            
                            if (ch == "]" )
                            {
                                level-=1;
                                if(level==0)
                                {
                                   // stream.eat("]");
                                
                                    if(hasParameters)
                                    {
                                        //alert(1);
                                        stream.pos-=1;
                                        return "layouteditortagparams";
                                    }
                                    
                                }
                            }
                            
                            if(ch==':')
                            {
                                //alert(stream.start);
                                //alert(stream.lineStart);
                                if(level==1)
                                {
                                    stream.start=stream.pos;
                                
                                
                                    hasParameters=true;
                                }
                            }
                            else
                            {
                                /*
                                if(!hasParameters)
                                    stream.eat(ch);
                                    */
                            }
                        }
                    }
                    while (stream.next() != null && !stream.match("[", false)) {}
                    return null;
                }
            };
            
                       
            return CodeMirror.overlayMode(CodeMirror.getMode(config, parserConfig.backdrop || nextmodename), layouteditorOverlay);
        });
    }


function renderTagSets(index)
{
    var obj=document.getElementById("layouteditor_tagsets"+index);
    
  //alert(tag_sets.length+","+index);
    var sets=tag_sets[index];
    
    
    var result_li='';
    var result_div='';
    
    for(var i=0;i<sets.length;i++)
    {
        var c="";
        if(i==0)
            c="active";
            
        result_li+='<li class="'+c+'"><a href="#layouteditor_tags'+index+'_'+i+'" data-toggle="tab">'+sets[i].title+'</a></li>';
        result_div+='<div id="layouteditor_tags'+index+'_'+i+'" class="tab-pane '+c+'">'+renderTags(index,sets[i].tags)+'</div>';
    
    }
    
    var result='<ul class="nav nav-tabs" >'+result_li+'</ul>';
    
    result+='<div class="tab-content" id="layouteditor_tagsContent'+index+'">'+result_div+'</div>';
    
    obj.innerHTML=result;

}

function renderTags(index,tags)
{
    var result='<div class="dynamic_values">';
    for(var i=0;i<tags.length;i++)
    {
        var tag=tags[i];
            
        var a="";
        var t="";
        var p=0;
        var d="";
        
        if(typeof tag === 'object')
        {
            if(tag.visible==null || tag.visible!=0)
            {
                
            
                if(Array.isArray(tag.params))
                {
                    if(tag.type!=null && tag.type=="html")
                    {
                        a=htmlDecode2(tagOnly(tag));
                    }
                    else
                    {
                        a='['+tag.tag+']';
                        t='['+tag.tag+':<span>Params</span>]';
                        p=1;
                    }
                }
                else
                {
                    if(tag.type!=null && tag.type=="html")
                    {
                        a=htmlDecode2(tagOnly(tag));
                        t=tagOnly(tag);
                    }
                    else
                    {
                        a=tagOnly(tag);
                        t=tagOnly(tag);
                    }
                }
            
                d=" "+tag.description;
            }
            else
            {
                a="";
            }
            
            
        }
		else
        {
            a='['+tagOnly(tag)+']';
            t='['+tagOnly(tag)+']';
        }    
            
        if(a!="")
            result+='<div><code><a href=\'javascript:addTag('+index+',"'+btoa(a)+'",'+p+');\'>'+t+'</a></code>'+d+'</div>';
        
    }
    
    result+='</div>';
    
    return result;
}

function htmlDecode2(input)
{
  var doc = new DOMParser().parseFromString(input, "text/html");
  return doc.documentElement.textContent;
}

function tagOnly(tag)
{
    if(typeof tag === 'object')
    {
        if(tag.type !=null && tag.type=="html")
        {
            return tag.tag;
        }
        else
            return "["+tag.tag+"]";
    }
    else
        return tag;
}