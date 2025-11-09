// alert("Hello...!!! Its me")
const d = document

const footerCollapse = d.getElementById("footer-collapse")
const brandCollapse = d.getElementById("brand-collapse")

let isCollapsed = false;

const textMenuCollapse = () => {

  let menuLinks = Array.from(d.querySelectorAll( ".menu__link>span.text" ))
  // console.log(menuLinks);
  if (!isCollapsed) {

    menuLinks.forEach(link => {
      link.style.display = "inline";
      link.parentElement.style.width = "100%";
    })

    footerCollapse.children[1].style.display = "inline"
    brandCollapse.children[1].style.display = "block"

    isCollapsed = false;

  } else {

    menuLinks.forEach(link => {
      link.style.display = "none";
      link.parentElement.style.width = "auto";
    })

    footerCollapse.children[1].style.display = "none"
    brandCollapse.children[1].style.display = "none"

    isCollapsed = true;

  }

}

const menuCollapse = () => {

  if (isCollapsed) {
    document.body.style.gridTemplateColumns = "300px repeat(3, 1fr)"
    isCollapsed = false;
  }else {
    document.body.style.gridTemplateColumns = "60px repeat(3, 1fr)"
    isCollapsed = true;
  }

}

footerCollapse.addEventListener("click", menuCollapse)
footerCollapse.addEventListener("click", textMenuCollapse)

// Funcionalidad para mantener el estado activo del menú
document.addEventListener('DOMContentLoaded', function() {
  
  // Función para actualizar el estado activo de los menús
  function updateActiveMenu() {
    const currentPath = window.location.pathname;
    const menuLinks = document.querySelectorAll('.menu__link');
    
    // Remover todas las clases activas
    menuLinks.forEach(link => {
      link.classList.remove('menu__link--active');
    });
    
    // Agregar clase activa al menú correspondiente
    menuLinks.forEach(link => {
      const href = link.getAttribute('href');
      if (href && currentPath.includes(href) && href !== '/dashboard/') {
        link.classList.add('menu__link--active');
      } else if (href === '/dashboard/' && (currentPath === '/dashboard/' || currentPath === '/dashboard/main')) {
        link.classList.add('menu__link--active');
      }
    });
  }
  
  // Manejar el submenu de administración
  function handleAdminSubmenu() {
    const currentPath = window.location.pathname;
    const adminPaths = ['/dashboard/gestion_usuarios', '/dashboard/gestion_agencias', '/dashboard/gestion_areas'];
    const adminCollapse = document.getElementById('adminCollapse');
    const adminToggle = document.querySelector('[href="#adminCollapse"]');
    
    // Verificar si estamos en una página de administración
    const isAdminPage = adminPaths.some(path => currentPath.includes(path));
    
    if (isAdminPage && adminCollapse && adminToggle) {
      // Mantener el submenu abierto
      adminCollapse.classList.add('show');
      adminToggle.setAttribute('aria-expanded', 'true');
      adminToggle.classList.add('menu__link--active');
      
      // Prevenir que se cierre automáticamente
      adminToggle.addEventListener('click', function(e) {
        if (isAdminPage) {
          e.preventDefault();
        }
      });
    }
  }
  
  // Inicializar
  updateActiveMenu();
  handleAdminSubmenu();
  
  // Actualizar cuando cambie la página (para SPAs)
  window.addEventListener('popstate', function() {
    updateActiveMenu();
    handleAdminSubmenu();
  });
  
});
