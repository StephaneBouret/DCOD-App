const threshold = .1
const options = {
    root: null,
    rootMargin: '0px',
    threshold
}

const handleIntersect = function (entries, observer) {
    entries.forEach(entry => {
        // chaque élément de entries correspond à une variation
        // d'intersection pour un des éléments cible:
        if (entry.intersectionRatio > threshold) {
            entry.target.classList.remove('reveal');
            observer.unobserve(entry.target);
        }
    });
}

const observer = new IntersectionObserver(handleIntersect, options);
var target = document.querySelector('.reveal');
observer.observe(target);