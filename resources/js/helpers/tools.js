function getUrlParams(url, sParam) {
    // извлекаем строку из URL или объекта window
    var queryString = url ? url.split('?')[1] : window.location.search.slice(1);

    // перекодируем
    queryString = decodeURI(queryString);
    // console.log('queryString', queryString)
    // объект для хранения параметров
    var obj = {};

    // если есть строка запроса
    if (queryString) {

        // данные после знака # будут опущены
        queryString = queryString.split('#')[0];

        // разделяем параметры
        var arr = queryString.split('&');

        for (var i=0; i<arr.length; i++) {
            // разделяем параметр на ключ => значение
            var a = arr[i].split('=');

            // обработка данных вида: list[]=thing1&list[]=thing2
            var paramNum = undefined;
            var paramName = a[0].replace(/\[\d*\]/, function(v) {
                paramNum = v.slice(1,-1);
                return '';
            });

            // передача значения параметра ('true' если значение не задано)
            var paramValue = typeof(a[1])==='undefined' ? true : a[1];

            // преобразование регистра
            // paramName = paramName.toLowerCase();
            // декодируем знаки
            // paramValue = decodeURIComponent(paramValue.toLowerCase());
            paramValue = decodeURIComponent(paramValue);

            // если ключ параметра уже задан
            if (obj[paramName]) {
                // преобразуем текущее значение в массив
                if (typeof obj[paramName] === 'string') {
                    obj[paramName] = [obj[paramName]];
                }
                // если не задан индекс...
                if (typeof paramNum === 'undefined') {
                    // помещаем значение в конец массива
                    obj[paramName].push(paramValue);
                }
                // если индекс задан...
                else {
                    // размещаем элемент по заданному индексу
                    obj[paramName][paramNum] = paramValue;
                }
            }
            // если параметр не задан, делаем это вручную
            else {
                obj[paramName] = paramValue;
            }
        }
    }
    return obj;
}
function updateUrl(method, name, value) {
    if (history.pushState) {
        var baseUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + window.location.search;
        var newUrl = new URL(baseUrl);
        if(method === 'delete'){
            newUrl.searchParams.delete(name);
        }else if(method === 'set'){
            newUrl.searchParams.set(name, value);
        }else if(method === 'append'){
            newUrl.searchParams.append(name, value);
        }

        history.pushState(null, null, newUrl);
    }
    else {
        console.warn('History API не поддерживается');
    }
}
export {
    getUrlParams,
    updateUrl,
};
