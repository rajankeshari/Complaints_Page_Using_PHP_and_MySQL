/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/*$().ready(function(){
 setTimeout(function() {     
     $(".flash").fadeOut("slow", function() {
        $(".flash").text("");
          });
     }, 2000);
 }); */
function getAppPath(a) {
    var pathArray = location.pathname.split('/');
    var appPath = "/";
    for(var i=1; i<pathArray.length-a; i++) {
        appPath += pathArray[i]+"/" ;
    }
    return self.location.protocol+'//'+self.location.host+appPath;
}
