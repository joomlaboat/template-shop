//var MagicClickInWork=false;
var MagicClickElement="";
var MagicClickObject=null;
var MagicClickObjectInProcess=false;

function MagicClickObjectDisableProcess()
{
    MagicClickElement="";
    MagicClickObject=null;

    MagicClickObjectInProcess=false;

}

function processMagicClick()
{
    //if(MagicClickObjectInProcess)
        //return;

    MagicClickObjectInProcess=true;

    //alert("zur");
    var TagName=MagicClickObject.tagName;
    alert(TagName);
    if(TagName=='IMG')
        process_MG_Image(MagicClickObject);

    //alert(TagName);
    //alert(MagicClickElement);
    //



    setTimeout(MagicClickObjectDisableProcess,0.5);
}


function process_MG_Image(obj)
{
    var data={
        tag:"IMG",
        itemid: MagicClick_Itemid,
        src: obj.getAttribute("src"),
        url: b2a(location.href),
        content: ""
    };
    //alert(JSON.stringify(data));
    //alert(JSON.stringify(obj.innerHTML));
    make_MG_request(data);
}

function make_MG_request(data)
{
    var url=MagicClick_PrepareLink(['magicclick_task'],['magicclick_task=find']);
    var other_params={
        headers:{"content-type":"application/json; charset=UTF-8"},
        body:data,
        mothod: "GET",
        mode: "no-cors",
        credentials: "same-origin"
    };

    alert(url);
    fetch(url,other_params).then(function(response)
	{
			if(response.ok)
			{
				response.json().then(function(json)
				{
					suggestions=Array.from(json);
                    alert(JSON.stringify(suggestions));
					//current_table_id=tableid;
					//updateFieldsBox();
				});
			}
			else
			{
				console.log('Network request for MagicClick assistance failed with response ' + response.status + ': ' + response.statusText);
				//tags_box_obj.innerHTML='<p class="msg_error">'+'Network request for products.json failed with response ' + response.status + ': ' + response.statusText+'</p>';
			}
	}).catch(function(err)
	{
			console.log('Fetch Error :', err);
	});


    //.then(data=>{return data.json();})
    //.then(res=>{alert(res);})
    //.then(error=>{alert("error:"+error);});

}

function doMagicClickOnAnchor(event)
{
    //This to prevent clicking on the links when ctrl and shift are pressed.
    if (event.ctrlKey && event.shiftKey)
    {

        event.preventDefault();
        event.stopPropagation();
        return false;
    }

}

function doMagicClick(event)
{

    if (event.ctrlKey && event.shiftKey)
    {
        var innervalue = this.innerHTML;
        var TagName=this.tagName;

        if(MagicClickObjectInProcess)
            return;

        if(TagName=='IMG' || MagicClickElement=="" || (MagicClickElement.length>innervalue.length && innervalue!=""))
        {
            //var target = event.target || event.srcElement;

            MagicClickElement=innervalue;
            MagicClickObject=this;
            setTimeout(processMagicClick,0.5);

            if(TagName=='IMG')
                MagicClickObjectInProcess=true;


        }

        event.preventDefault();
        event.stopPropagation();
        return false;
    }
}

function mc_addClickEvents()
{
    //var elements=['div','p','li','img','h1'];
    var elements=['img','p'];

    for(var e=0;e<elements.length;e++)
    {
        var matches = document.querySelectorAll(elements[e]);

        for (var i = 0; i < matches.length; i++)
        {
            matches[i].addEventListener("click", doMagicClick, false);
        }
    }

    var matches2 = document.querySelectorAll('a');
    for (var i2 = 0; i2 < matches2.length; i2++)
    {
        matches2[i2].addEventListener("click", doMagicClickOnAnchor, false);
    }
}

    function MagicClick_PrepareLink(deleteParams,addParams)
    {
        var link=window.location.href;

        var pair=link.split('#');
        link=pair[0];

        for(var i=0;i<deleteParams.length;i++)
        {
            link=removeURLParameter(link, deleteParams[i]);
        }

        for(var a=0;a<addParams.length;a++)
        {

            if(link.indexOf("?")==-1)
                link+="?"; else link+="&";

            link+=addParams[a];
        }

        return link;
    }


document.addEventListener("DOMContentLoaded",function(){mc_addClickEvents();})