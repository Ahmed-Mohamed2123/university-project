let header = document.getElementById('header');
let main = document.getElementById('main');
let sidebar = document.getElementById('sidebar');

// sum width => from (right: 12px) and the thing on the left side 12px
widthHeader = 320 + 24;

// Subtract width from (left: 12px)
widthSidebar = 320 - 12;

window.onload = () => {
    if (window.innerWidth < 996) {
        whenInnerSmallWidth();
    } else {
        whenInnerBigWidth();
    }
}

window.onresize = () => {
    header.style.width = `calc(100% - ${sidebar.style.width})`;
    if (window.innerWidth < 996) {
        whenInnerSmallWidth();
    } else {
        whenInnerBigWidth();
    }
}

function whenInnerBigWidth() {
    sidebar.style.left = '12px';
    sidebar.style.width = `${widthSidebar}px`;
    main.style.width = `calc(100% - ${widthHeader}px)`;
    header.style.width = `calc(100% - ${widthHeader}px)`;
}
function whenInnerSmallWidth() {
    sidebar.style.left = '-100%';
    sidebar.style.width = 'calc(100% - 24px)';
    main.style.width = `calc(100% - 24px)`;
    header.style.width = `calc(100% - 24px)`;
}

// open sidebar
function openSidebar() {
    sidebar.style.left = '12px';
}

function closeSidebar() {
    sidebar.style.left = '-100%';
}
