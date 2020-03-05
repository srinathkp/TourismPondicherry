document.addEventListener( 'DOMContentLoaded',function()
{    
    var search_target=document.getElementById("search_target").innerHTML; 
    search_target=search_target.toString();
    var text=document.getElementById("search1").value;
    highlight_word(search_target, text);
    
},false);

function highlight_word(search_target, text)
{
    if(text)
    {
        var pattern=new RegExp("("+text+")", "gi");
        var new_text=search_target.replace(pattern, "<span class='highlight'>"+text+"</span>");
        document.getElementById("search_target").innerHTML=new_text;    
    }
}
