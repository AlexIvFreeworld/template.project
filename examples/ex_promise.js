let user;

user = new Promise(function (resolve, reject) {
    try {
        //асинхронный код
        BX24.callMethod('profile', {}, function (res) {
            if (res.data())
                resolve(res.data())   // Если операция завершается успешно, вызываем функцию resolve и передаем ей результат выполнения
        });
    } catch (err) {
        resolve(err)    // Если операция завершается с ошибкой, вызываем функцию rejec  и передаем ей объект ошибки
    }
})


user.then(res => {
    console.log(res);
    // res  результат выполнения Promise
})


const myFirstPromise = new Promise((resolve, reject) => {
    // We call resolve(...) when what we were doing asynchronously was successful, and reject(...) when it failed.
    // In this example, we use setTimeout(...) to simulate async code.
    // In reality, you will probably be using something like XHR or an HTML API.
    setTimeout(() => {
        resolve("Success!"); // Yay! Everything went well!
    }, 250);
});

myFirstPromise.then((successMessage) => {
    // successMessage is whatever we passed in the resolve(...) function above.
    // It doesn't have to be a string, but if it is only a succeed message, it probably will be.
    console.log(`Yay! ${successMessage}`);
});