let btnWebinars = document.getElementsByName('webinars');
let btnTeachers = document.getElementsByName('teachers');
let btnStatistic = document.getElementsByName('statistic');
let btnDelWebinars = document.getElementsByName('delWebinars');
let btnDelRecords = document.getElementsByName('delRecords');

btnWebinars[0].onclick = function() { showContent('../webinars.php', 'contentBody'); };
btnTeachers[0].onclick = function() { showContent('../teachers.php', 'contentBody'); };
btnStatistic[0].addEventListener("click", function() { showContent('../all_statistic.php', 'contentBody'); });
btnDelWebinars[0].onclick = function() { showContent('../delwebinars.php', 'contentBody'); };
btnDelRecords[0].onclick = function() { showContent('../delrecords.php', 'contentBody'); };

document.body.addEventListener('click', event => {
// нажали кнопку на странице ВСЕ ВЕБИНАРЫ
    if (event.target.className === 'btnWebinarsClick') {
        let startDate = document.getElementsByName('instartdate');
        let endDate = document.getElementsByName('inenddate');
        if (Date.parse(startDate[0].value) <= Date.parse(endDate[0].value)) {
            showContent('../listwebinars.php?instartdate='+startDate[0].value+'&inenddate='+endDate[0].value, 'contentTest');
        } else {
            document.getElementById('contentTest').innerHTML = "Начальная дата больше конечной. Исправьте";
        }
    }

//нажали кнопку на странице ПРЕПОДАВАТЕЛЬ
    if (event.target.className === 'btnTeacherClick') {
        let startDate = document.getElementsByName('instartdate');
        let endDate = document.getElementsByName('inenddate');
        let idTeacher = document.getElementsByName('idTeacher');
        let statusWebinar = document.getElementsByName('statusWebinarTeacher');
        let messageError = '';

        if (Date.parse(startDate[0].value) > Date.parse(endDate[0].value)) {
            messageError = "Начальная дата больше конечной. Исправьте<br>";
        }

        if (idTeacher[0].value == 0) {
            messageError += "Не указан преподаватель. Исправьте<br>";
        }

        if (messageError == '') {
            showContent('../showTeacherWebinars.php?instartdate='+startDate[0].value+'&inenddate='+endDate[0].value+'&idTeacher='+idTeacher[0].value, 'contentTest');
        } else {
            document.getElementById('contentTest').innerHTML = messageError;
        }
    }

// нажали на кнопку СТАРТ на странице УДАЛЕНИЕ ЗАПИСЕЙ
    if (event.target.className === 'btnRecordsClick') {
        let startDate = document.getElementsByName('instartdate');
        let endDate = document.getElementsByName('inenddate');
        let idTeacher = document.getElementsByName('idTeacher');
        let messageError = '';
        
        if (Date.parse(startDate[0].value) > Date.parse(endDate[0].value)) {
            messageError = "Начальная дата больше конечной. Исправьте<br>";
        }

        if (messageError == '') {
            showContent('../showdelrecords.php?instartdate='+startDate[0].value+'&inenddate='+endDate[0].value+'&idTeacher='+idTeacher[0].value, 'contentTest');
        } else {
            document.getElementById('contentTest').innerHTML = messageError;
        }
    }

// нажали на кнопку УДАЛИТЬ на странице УДАЛЕНИЕ ЗАПИСЕЙ
    if (event.target.className === 'btnDelRecordsClick') {
        let boxDelRec = document.getElementsByClassName('boxDelRec');

        let arr = new Array();
        var i = boxDelRec.length;
        while(i--) {
            if(boxDelRec[i].checked) {
                arr.push(boxDelRec[i].name);
            }
        }
        var jsonStr = JSON.stringify(arr);

        showContentPOST('../realdelrecords.php', 'delRecords', jsonStr);
    }

// нажали на кнопку Уверен, УДАЛИТЬ на странице УДАЛЕНИЕ ЗАПИСЕЙ
    if (event.target.className === 'btnDelYes') {
        let messageError = '';

        let boxDelRec = document.getElementsByClassName('boxDelRec');

        let arr = new Array();
        var i = boxDelRec.length;
        while(i--) {
            if(boxDelRec[i].checked) {
                arr.push(boxDelRec[i].name);
            }
        }
        var jsonStr = JSON.stringify(arr);

        showContentPOST('../vacatingspace.php', 'contentTest', jsonStr);

        document.getElementById('delRecords').innerHTML = messageError;
    }

// нажали на кнопку Уверен, ОТМЕНИТЬ на странице УДАЛЕНИЕ ЗАПИСЕЙ
    if (event.target.className === 'btnDelCancel') {
        let messageError = '';

        document.getElementById('contentTest').innerHTML = messageError;
        document.getElementById('delRecords').innerHTML = messageError;
    }

});

function showContent(link, container) {
    document.getElementById('contentTest').innerHTML = "";
    if(link.indexOf('listwebinars') != -1) {
        var cont = document.getElementById(container);
    } else {
        var cont = document.getElementById(container);
    }

    var loading = document.getElementById('loading');

    cont.innerHTML = loading.innerHTML;
    var http = createRequestObject();
    if (http) {
        http.open('get', link);
        http.onreadystatechange = function() {
            if (http.readyState == 4) {
                cont.innerHTML = http.responseText;
            }
        }
        http.send(null);
    } else {
        document.location = link;
    }
}

// создание ajax объекта
function createRequestObject() {
    try { return new XMLHttpRequest() } catch (e) {
        try { return new ActiveXObject('Msxml2.XMLHTTP') } catch (e) {
            try { return new ActiveXObject('Microsoft.XMLHTTP') } catch (e) { return null; }
        }
    }
}


function showContentPOST(link, container, jsonArr) {
  var xmlhttp = getXmlHttp(); // Создаём объект XMLHTTP
  xmlhttp.open('POST', link, true); // Открываем асинхронное соединение
  xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Отправляем кодировку
  xmlhttp.send("x=" + jsonArr); // Отправляем POST-запрос
  xmlhttp.onreadystatechange = function() { // Ждём ответа от сервера
    if (xmlhttp.readyState == 4) { // Ответ пришёл
      if(xmlhttp.status == 200) { // Сервер вернул код 200 (что хорошо)
        document.getElementById(container).innerHTML = xmlhttp.responseText; // Выводим ответ сервера
      }
    }
  };
}


function getXmlHttp() {
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