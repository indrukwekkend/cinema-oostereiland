import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export default {
  init() {
    // JavaScript to be fired on the about us page
      $('#toon_tickets').on('click', function (e) {
        e.preventDefault();
        $('#ticket-modal').addClass('show');
      });

      $('#ticket-modal__close').on('click', function (e) {
        e.preventDefault();
        $('#ticket-modal').removeClass('show');
      });
      
      gsap.registerPlugin(ScrollTrigger);

      // scroll trigger pin CTA element
      ScrollTrigger.create({
        trigger: '#cta-barxxxx',
        start: 'top top', 
        pin: '#cta-barxxx',
        end: '+=15000',
        toggleClass: {targets: '#cta-bar', className: 'active'}, 
      });
  },
};
