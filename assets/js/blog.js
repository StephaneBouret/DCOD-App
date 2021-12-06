/**
 * Back to top button
 */
backtotop = document.querySelector('.back-to-top');

window.onscroll = () => {
  scrollFunction();
}

/**
 * Easy selector helper function
 */
const select = (el, all = false) => {
  el = el.trim()
  if (all) {
    return [...document.querySelectorAll(el)]
  } else {
    return document.querySelector(el)
  }
}

/**
 * Easy event listener function
 */
const on = (type, el, listener, all = false) => {
  let selectEl = select(el, all)
  if (selectEl) {
    if (all) {
      selectEl.forEach(e => e.addEventListener(type, listener))
    } else {
      selectEl.addEventListener(type, listener)
    }
  }
}

/**
 * Easy on scroll event listener 
 */
const onscroll = (el, listener) => {
  el.addEventListener('scroll', listener)
}

/**
 * Animation on scroll
 */
 window.addEventListener('load', () => {
  AOS.init({
      duration: 1000,
      easing: 'ease-in-out',
      once: true,
      mirror: false
  })
});

backtotop.addEventListener('click', () => {
  topFunction();
})

function scrollFunction() {
  if (window.scrollY > 100) {
      backtotop.classList.add('active')
  } else {
      backtotop.classList.remove('active')
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}

/**
* Preloader
*/
let preloader = document.getElementById('preloader');
if (preloader) {
  window.addEventListener('load', () => {
      preloader.remove();
  });
};