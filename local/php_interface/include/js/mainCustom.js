var helpR52 = {
    getHtmlAjax: function (url, callback) {
        var httpRequest = false;

        if (window.XMLHttpRequest) { // Mozilla, Safari, ...
            httpRequest = new XMLHttpRequest();
            if (httpRequest.overrideMimeType) {
                httpRequest.overrideMimeType('text/xml');
                // Читайте ниже об этой строке
            }
        } else if (window.ActiveXObject) { // IE
            try {
                httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) { }
            }
        }

        if (!httpRequest) {
            alert('Не вышло :( Невозможно создать экземпляр класса XMLHTTP ');
            return false;
        }
        httpRequest.onreadystatechange = function () {
            helpR52.processResponseHtml(httpRequest, callback);
        };
        httpRequest.open("GET", url, true);
        httpRequest.send(null);

    },
    getJsonAjax: function (url, data, callback) {
        var httpRequest = false;
        data = JSON.stringify(data);

        if (window.XMLHttpRequest) { // Mozilla, Safari, ...
            httpRequest = new XMLHttpRequest();
            if (httpRequest.overrideMimeType) {
                httpRequest.overrideMimeType('text/xml');
                // Читайте ниже об этой строке
            }
        } else if (window.ActiveXObject) { // IE
            try {
                httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) { }
            }
        }

        if (!httpRequest) {
            alert('Не вышло :( Невозможно создать экземпляр класса XMLHTTP ');
            return false;
        }
        httpRequest.onreadystatechange = function () {
            helpR52.processResponseJson(httpRequest, callback);
        };
        // console.log(data);
        httpRequest.open("POST", url, true);
        httpRequest.send(data);

    },
    getHtmlAjaxPost: function (url, data, callback) {
        var httpRequest = false;
        data = JSON.stringify(data);

        if (window.XMLHttpRequest) { // Mozilla, Safari, ...
            httpRequest = new XMLHttpRequest();
            if (httpRequest.overrideMimeType) {
                httpRequest.overrideMimeType('text/xml');
                // Читайте ниже об этой строке
            }
        } else if (window.ActiveXObject) { // IE
            try {
                httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) { }
            }
        }

        if (!httpRequest) {
            alert('Не вышло :( Невозможно создать экземпляр класса XMLHTTP ');
            return false;
        }
        httpRequest.onreadystatechange = function () {
            helpR52.processResponseHtml(httpRequest, callback);
        };
        // console.log(data);
        httpRequest.open("POST", url, true);
        httpRequest.send(data);

    },
    processResponseJson: function (httpRequest, callback) {

        if (httpRequest.readyState == 4) {
            if (httpRequest.status == 200) {
                // console.log(httpRequest.responseText);
                let res = JSON.parse(httpRequest.responseText);
                // console.log(res);
                callback(res);
            } else {
                alert('С запросом возникла проблема.');
            }
        }

    },
    processResponseHtml: function (httpRequest, callback) {
        if (httpRequest.readyState == 4) {
            if (httpRequest.status == 200) {
                // console.log(httpRequest.responseText);
                result = httpRequest.responseText;
                callback(result);
            } else {
                console.log('С запросом возникла проблема.');
                result = "err";
            }
        }
    }
};