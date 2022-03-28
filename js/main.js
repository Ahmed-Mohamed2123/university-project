
if (
    location.href.includes('index')
    || location.href.includes('schools/add')
    || location.href.includes('subjects/add')
    || location.href.includes('/auth')
    || location.href.includes('results/add')) {

    // net header height
    let topHeight = header.offsetHeight + 24;
    main.style.height = `calc(100vh - ${topHeight}px)`;
}

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
