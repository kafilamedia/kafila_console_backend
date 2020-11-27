// FONT AWESOME
import { library, dom } from '@fortawesome/fontawesome-svg-core'
import { fab } from '@fortawesome/free-brands-svg-icons'
import { fas } from '@fortawesome/free-solid-svg-icons'

library.add(fab)
library.add(fas)

dom.i2svg()

// NAVBAR BURGER
document.addEventListener('DOMContentLoaded', () => {

  // Get all "navbar-burger" elements
  const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

  // Check if there are any navbar burgers
  if ($navbarBurgers.length > 0) {

    // Add a click event on each of them
    $navbarBurgers.forEach( el => {
      el.addEventListener('click', () => {

        // Get the target from the "data-target" attribute
        const target = el.dataset.target;
        const $target = document.getElementById(target);

        // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
        el.classList.toggle('is-active');
        $target.classList.toggle('is-active');

      });
    });
  }

});

// ALPINEJS
import Alpine from 'alpinejs'

Alpine.start()

// MY APP
class Apl {
  constructor() {
    this.show = false
    this.delete_url = false
  }

  modalConfirm(url) {
    this.delete_url = url
    this.show = true
  }

  open() {
    this.show = true
  }

  close() {
    this.show = false
  }
}

window.myApp = new Apl()
