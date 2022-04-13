window.addEventListener('load', () => {

    const myAlert =document.getElementById('toast');//select id of toast

    if (myAlert) {
        const bsAlert = new bootstrap.Toast(myAlert); // initialize it
        bsAlert.show();
    }
});

function printInvoice(ele) {
    const openWindow = window.open("", "title", "attributes");

    openWindow.document.write(
        `
            <!doctype html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport"
                      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <title>view all</title>

                <link rel="stylesheet" href="./../../css/bootstrap.min.css">
            </head>
            <body>
            ${ele.parentElement.parentElement.innerHTML}

            </body>
            </html>
`
    );

    setTimeout(() => {
        openWindow.document.close();
        openWindow.focus();
        openWindow.print();
        openWindow.close();
    }, 100);
}



