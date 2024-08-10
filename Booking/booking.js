/*Mark Soluiman
18039781
*/

// this file is responsible for getting data from the booking.html form using the POST method where these data will
//be processed by the bookingprocess.php file


var xhr = new XMLHttpRequest();
function getData(
  dataSource,
  divID,
  aCName,
  aPhone,
  aUNumber,
  aSNumber,
  aStName,
  aSbName,
  aDsbName,
  aDate,
  aTime
) {
  if (xhr) {
    var place = document.getElementById(divID);
    var requestBody =
      "cname=" +
      encodeURIComponent(aCName) +
      "&phone=" +
      encodeURIComponent(aPhone) +
      "&unumber=" +
      encodeURIComponent(aUNumber) +
      "&snumber=" +
      encodeURIComponent(aSNumber) +
      "&stname=" +
      encodeURIComponent(aStName) +
      "&sbname=" +
      encodeURIComponent(aSbName) +
      "&dsbname=" +
      encodeURIComponent(aDsbName) +
      "&date=" +
      encodeURIComponent(aDate) +
      "&time=" +
      encodeURIComponent(aTime);
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
