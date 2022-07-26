/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************!*\
  !*** ./resources/js/custom.js ***!
  \********************************/
// window.moment = require('moment');
// require('moment/locale/bn-bd');
// const date = document.getElementById('date');
//
// setInterval(function (){
//     date.innerText = moment().format('dddd, Do MMM YYYY, LTS')
// }, 1000)
if (screen.width < 768) {
  window.addEventListener('click', close);
}

var bar = document.getElementById('bar');
bar.addEventListener('click', function () {
  var menu = document.getElementById('menu');
  var bg = document.getElementById('bg');
  menu.classList.remove('w-0');
  menu.style.width = '70%';
  menu.style.transition = '0.5s';
  this.classList.remove('block');
  this.classList.add('hidden');
  bg.classList.remove('hidden');
  bg.classList.add('block');
});

function close(e) {
  var menu = document.getElementById('menu');
  var bar = document.getElementById('bar');
  var bg = document.getElementById('bg');

  if (!menu.contains(e.target) && !bar.contains(e.target)) {
    // menu.style.display = 'none';
    menu.classList.remove('w-0');
    menu.classList.add('shadow-lg');
    menu.style.width = '0px';
    menu.style.transition = '0.5s';
    bar.classList.remove('hidden');
    bar.classList.add('block');
    bg.style.display = 'none';
  } else {
    menu.classList.remove('w-0');
    menu.style.width = '70%';
    menu.style.transition = '0.5s';
    bg.style.display = 'block';
  }
}
/******/ })()
;