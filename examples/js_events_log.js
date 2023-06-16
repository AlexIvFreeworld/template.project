//syntax
  //monitorEvents(element,event)
  
  //listen to all events
  monitorEvents(window);
  
  //stop listening
  unmonitorEvents(window);

  //start listening
  monitorEvents(window,"submit");
  
  //stop listening
  unmonitorEvents(window,"submit");

// This will log all events on the body
monitorEvents(document.body);

// This will log only click events on the h2 element
var main = document.querySelector('#main');
monitorEvents(main, 'click');

var h2 = document.querySelector('h2[itemprop="headline"]');
console.log(h2);
monitorEvents(h2, 'click');

var form = document.querySelector('form.form-feedback');
console.log(form);
monitorEvents(form, 'submit');

(function() { // Overriding XMLHttpRequest
    var oldXHR = window.XMLHttpRequest;

    function newXHR() {
        var realXHR = new oldXHR();

        realXHR.addEventListener("readystatechange", function(el) { 
            console.log("an ajax request was made");
            console.log(el); 
        }, false);

        return realXHR;
    }

    window.XMLHttpRequest = newXHR;
})();