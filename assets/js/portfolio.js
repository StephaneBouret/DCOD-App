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
 * Porfolio isotope and filter
 */
window.addEventListener('load', () => {
  let portfolioContainer = select('.portfolio-container');
  if (portfolioContainer) {
    let portfolioIsotope = new Isotope(portfolioContainer, {
      itemSelector: '.portfolio-item'
    });

    // let portfolioFilters = select('#portfolio-games-flters li', true);

    // on('click', '#portfolio-games-flters li', function (e) {
    //   e.preventDefault();
    //   portfolioFilters.forEach(function (el) {
    //     el.classList.remove('filter-active');
    //   });
    //   this.classList.add('filter-active');

    //   portfolioIsotope.arrange({
    //     filter: this.getAttribute('data-filter')
    //   });
    //   portfolioIsotope.on('arrangeComplete', function () {
    //     AOS.refresh()
    //   });
    // }, true);
  }

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