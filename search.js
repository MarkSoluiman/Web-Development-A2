/*Mark Soluiman
18039781
*/

// this file is responsible for getting data from the admin.html form using the POST method where these data will
//be processed by the searchprocess.php file




var xhr = new XMLHttpRequest();
function getData(
  dataSource,
  divID,
aBSearch
) {
  if (xhr) {
    var place = document.getElementById(divID);
    var requestBody =
      "bsearch=" +
      encodeURIComponent(aBSearch) 
      
    xhr.open("POST", dataSource, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        place.innerHTML = xhr.responseText;
      }
    };
    xhr.send(requestBody);
  }
}
