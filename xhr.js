/*Mark Soluiman
18039781
*/


//Creating the xhr request
function createRequest(){
    var xhr=false

    if(window.XMLHttpRequest){
        xhr=new XMLHttpRequest()

    }
    else if (window.ActiveXObject){
        xhr=new ActiveXObject("Microsoft.XMLHTTP")
    }
    return xhr
}