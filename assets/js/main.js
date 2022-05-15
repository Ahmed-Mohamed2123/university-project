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
                <title>invoice</title>

                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            </head>
            <body>
            ${ele.parentElement.parentElement.children[1].innerHTML}

            </body>
            </html>
`
    );

    setTimeout(() => {
        openWindow.document.close();
        openWindow.focus();
        openWindow.print();
        openWindow.close();
    }, 600);
}
