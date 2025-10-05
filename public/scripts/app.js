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
