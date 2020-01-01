function start_search(query){
document.body.scrollTop = document.documentElement.scrollTop = 0;
var search_input = document.getElementById("search-input");

//var h1_row = document.getElementById("h1-row");
//var content_row = document.getElementById("content-row");
//var pagination_row = document.getElementById("pagination-row");
var search_status_cont = document.getElementById("loader-row");

search_status_cont.style.display = "block";


var text = ["Выполняется поиск видео", "Создан новый поток", "Задействован гибкий процесс поиска", "Подождите идет поиск", "Отслеживание данных", "Загрузка страниц",  "Синхронизация данных", "Вектор прогресса поиска 90%"];
var counter = 0;
var elem = document.getElementById("loading-information");
elem.innerHTML = "Поисковая машина активирована";
var inst = setInterval(change, 3000);

function change() {
  elem.innerHTML = text[counter];
  counter++;
  if (counter >= text.length) {
    counter = 0;
    // clearInterval(inst); // uncomment this if you want to stop refreshing after one cycle
  }
}

if(search_input.value.length<3 && query.length<=0)return;
$('#query-text').remove();
$('#page-content').remove();
/***********************************/
var req = getXmlHttp();
req.onreadystatechange = function() {
    if (req.readyState == 4) {
        if(req.status == 200) {  //alert(req.responseText);
            if(req.responseText==''){
                    h1_row.style.display="block";
                    content_row.style.display="block";
                    pagination_row.style.display="block";

                    loader_row.style.display = "none";
                    alert("notfound");
                }else{
                    //window.location.href = "http://"+document.domain+"/" + decodeURIComponent(req.responseText);

                    search_status_cont.style.display = "none";
                    window.location.href = decodeURIComponent(req.responseText);
                }
			}
		}
	}
    if(query.length>3){

        req.open('GET', '/search.php?q=' + decodeURIComponent(query), true);
    }else{
	    req.open('GET', '/search.php?q=' + encodeURIComponent(search_input.value), true);
    }
	req.send(null);
}

function getXmlHttp(){
  var xmlhttp;
  try {
    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (e) {
    try {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (E) {
      xmlhttp = false;
    }
  }
  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
    xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}




function search_input_key_pressed(e){
    if (e.keyCode == 13) {
        start_search('');
        return false;
    }
}

$('#full-view').on('hidden.bs.modal', function (e) {
  $('#iframemov').remove();
})

function modal_init(frame, name){
    var full_view = document.getElementById("full-view");
    var modal_title = document.getElementById("modal-title");
    var modal_body = document.getElementById("modal-body");

    //full_view.style.display = "block";
    modal_title.innerText = decodeURIComponent(name);
    //modal_body.innerHTML = "<iframe frameborder=\"0\" scrolling=\"no\" allowfullscreen=\"1\" allow=\"autoplay; fullscreen\" src=\""+decodeURIComponent(frame)+"\" width=\"100%\" height=\"480px\" align=\"left\">";
    modal_body.innerHTML = "<iframe id=\"iframemov\" style=\"overflow:hidden;height:100%;width:100%;position: absolute;top: 0; right: 0; bottom: 0; left: 0;\" frameborder=\"0\" scrolling=\"no\" allowfullscreen=\"1\" width=\"100%\" height=\"100%\" src=\""+decodeURIComponent(frame)+"\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>";
    //var full_view2 = document.getElementById("query-text");
    //full_view2.innerHTML = "<iframe src=\""+decodeURIComponent(frame)+"\" style=\"position:absolute; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;\">Your browser doesn't support iframes</iframe>";
    $('#full-view').modal('show');
}