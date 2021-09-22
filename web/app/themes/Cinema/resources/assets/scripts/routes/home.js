//import { gsap } from 'gsap';
//import { ScrollTrigger } from 'gsap/ScrollTrigger';

export default {
  init() {
    // JavaScript to be fired on the home page
    
    // 2. Image tekst effect Experimenteel, testen in aparte branch voordat we dit implementeren
    // https://codepen.io/GreenSock/pen/pojzxwZ
    // Todo: /normaal/patronen/
      
    // gsap.utils.toArray('.gs_reveal').forEach(function(elem) {
    //   hide(elem); // assure that the element is hidden when scrolled into view, ?
      
    //   ScrollTrigger.create({
    //     trigger: elem,
    //     start: 'top center',
    //     end: 'bottom 200',
    //     markers: true,
    //     onEnter: function() { animateFrom(elem) }, 
    //     onEnterBack: function() { animateFrom(elem, -1) },

    //     // deze moet weer terug :
    //     onLeave: function() { hide(elem) }, // assure that the element is hidden when scrolled into view
    //     onLeaveBack: function() { hide(elem) }, // assure that the element is hidden when scrolled into view
    //   });
    // });

    //3. trigger voor de "pinned sidebar"
    // ScrollTrigger.create({
    //   trigger: '.indrukwekkend-content',
    //   start: 'top 150px',
    //   markers: true,
    //   // pin for the difference in heights between the content and the sidebar
    //   end: self => '+=' + (document.querySelector('.indrukwekkend-content').offsetHeight -  self.pin.offsetHeight),
    //  // end: document.querySelector('.indrukwekkend-content').offsetHeight,
    //   pin: '.indrukwekkend-pin',
    //   // before version 3.4.1, the "float" property wasn't copied to the pin-spacer, so we manually do it here. Could do it in a style sheet instead if you prefer. 
    //  //onRefresh: self => self.pin.parentNode.style.float = 'left',
    //   pinSpacing: false,
    // });

  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  },
};

// function hide(elem) {
//   console.log('ik verlaat nu het beeld');
//   gsap.set(elem, {autoAlpha: 0});
// }

// function animateFrom(elem, direction) {
//   direction = direction | 1;
//   console.log('ik start nu');
  
//   // dit is voor de tekst:
//   var x = 0,
//       y = direction * 100;

//   // dit is voor de afbeelding: Deze omdraaien: alleeen de right heeft een class
//   if(elem.classList.contains('has-media-on-the-right')) {
//     x = 100;
//   } else {
//       x = -100;
//   }

//   // Childnode 0 is 
//   gsap.fromTo(elem.childNodes[0], {x: x, y: 0, autoAlpha: 0}, {
//     duration: 1.25, 
//     x: 0,
//     y: 0, 
//     autoAlpha: 1, 
//     ease: 'expo', 
//     overwrite: 'auto',
//   });

//   // Childnode 1 is het tekst onderdeel van Media Text
//   gsap.fromTo(elem.childNodes[1], {x: 0, y: y, autoAlpha: 0}, {
//     duration: 1.25, 
//     x: 0,
//     y: 0,
//     autoAlpha: 1, 
//     ease: 'expo', 
//     overwrite: 'auto',
//   });

//   // dit is het hele element
//   gsap.fromTo(elem, {autoAlpha: 0}, {
//     duration: 1.25, 
//     autoAlpha: 1, 
//     ease: 'expo', 
//     overwrite: 'auto',
//   });
// }
