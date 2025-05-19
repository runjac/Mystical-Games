document.addEventListener("DOMContentLoaded", function() {
    const carouselSlide = document.querySelector(".carousel-slide");
    const images = document.querySelectorAll(".carousel-slide img");
  
    // Botones de navegación
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");
  
    // Contador para la posición del carrusel
    let counter = 1;
    const size = images[0].clientWidth;
  
    // Establecer la posición inicial del carrusel
    carouselSlide.style.transform = `translateX(${-size * counter}px)`;
  
    // Botón siguiente
    nextBtn.addEventListener("click", () => {
      if (counter >= images.length - 1) return;
      carouselSlide.style.transition = "transform 0.5s ease-in-out";
      counter++;
      carouselSlide.style.transform = `translateX(${-size * counter}px)`;
    });
  
    // Botón anterior
    prevBtn.addEventListener("click", () => {
      if (counter <= 0) return;
      carouselSlide.style.transition = "transform 0.5s ease-in-out";
      counter--;
      carouselSlide.style.transform = `translateX(${-size * counter}px)`;
    });
  
    // Función para que el carrusel sea automático
    setInterval(() => {
      if (counter >= images.length - 1) return;
      carouselSlide.style.transition = "transform 0.5s ease-in-out";
      counter++;
      carouselSlide.style.transform = `translateX(${-size * counter}px)`;
    }, 5000);
  
    // Reiniciar el carrusel al final de las imágenes
    carouselSlide.addEventListener("transitionend", () => {
      if (images[counter].id === "lastClone") {
        carouselSlide.style.transition = "none";
        counter = images.length - 2;
        carouselSlide.style.transform = `translateX(${-size * counter}px)`;
      }
      if (images[counter].id === "firstClone") {
        carouselSlide.style.transition = "none";
        counter = images.length - counter;
        carouselSlide.style.transform = `translateX(${-size * counter}px)`;
      }
    });
  });
  