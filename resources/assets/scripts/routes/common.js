import LazyLoader from 'blazy';
export default {
  init() {
    new LazyLoader({ selector: '.lazy' });
    // JavaScript to be fired on all pages
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};
