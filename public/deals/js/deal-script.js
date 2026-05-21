("use strict");

window.addEventListener("DOMContentLoaded", (event) => {
  // Listing Gallery View All
  let openItem;
  const body = document.querySelector("body");
  const btnGalleryAll = document.querySelectorAll(
    "#bg-gallery-all, .main .bg-listing-gallery-items"
  );
  const galleryPopup = document.querySelector("#bg-gallery-popup");
  const galleryClose = document.querySelector("#bg-gallery-back");
  const menuItem = document.querySelector("#bg-menu-item");
  const header = document.querySelector("header");
  const html = document.querySelector("html");
  const htmlHome = document.querySelector("html:not(.bg-home)");
  const middleMenu = document.querySelector("#close-middle-menu");
  const packages = document.querySelector("#packages");
  const packagesClose = document.querySelector("#packages .close");
  const packagesCloseBottom = document.querySelector("#packages .close-bottom");
  const rightPanel = document.querySelector(".right-panel");
  const rightWrapper = document.querySelector(".bg-listing-right-wrapper");
  const rightEnqPanel = document.querySelector("#enquire-panel");
  const menuList = document.querySelectorAll(".bg-menu-list");
  const menuListItems = document.querySelectorAll("#bg-menu-list a");
  const lightGallery = document.querySelector(".bgLightGallery");
  const interactiveLists = document.querySelectorAll(".in-container");
  let moreLessBtn = document.querySelectorAll(".in-container .more-less-info");
  const sortList = document.querySelectorAll(".bg-sort li");
  const bgSliderImg = document.querySelectorAll(".bg-slider-img img");

  for (let i = 0; i < btnGalleryAll.length; i++) {
    btnGalleryAll[i].addEventListener("click", function () {
      galleryPopup.classList.remove("hidden");
    });
  }

  function closePopup() {
    galleryPopup?.classList.add("hidden");
    body.classList?.remove("no-overflow-y");
  }

  const animationEnd = function () {
    const checkPoputActive = !galleryPopup?.classList.contains("hidden");
    if (checkPoputActive) body.classList.add("no-overflow-y");
  };

  if (galleryClose) {
    galleryClose.addEventListener("click", closePopup);
    galleryPopup.addEventListener("transitionend", animationEnd);
    galleryPopup.addEventListener("webkitTransitionEnd", animationEnd);
  }

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && !galleryPopup?.classList.contains("hidden"))
      closePopup();
  });

  // Menu

  menuItem?.addEventListener("click", activeMiddleMenu);
  middleMenu?.addEventListener("click", activeMiddleMenu);

  // Middle Menu
  function activeMiddleMenu() {
    menuItem.classList.toggle("active");
    header.classList.toggle("active-middle");
    html.classList.toggle("menu-40-deactive");
  }

  // Packages Collapse
  packagesClose?.addEventListener("click", function () {
    packages.classList.toggle("collapsed");
  });

  // Packages Collapse Bottom
  packagesCloseBottom?.addEventListener("click", function () {
    packages.classList.toggle("collapsed");
  });

  window.addEventListener("scroll", function () {
    // if (this.scrollY > rightPanel.clientHeight + rightWrapper.clientHeight) {
    if (htmlHome) {
      if (this.scrollY > 1000) {
        html.classList.add("menu-middle-large-active");
      } else html.classList.remove("menu-middle-large-active");
    }
  });

  // Setup isScrolling variable
  var isScrolling;
  window.addEventListener(
    "scroll",
    function (event) {
      body.classList.add("scrolling");
      window.clearTimeout(isScrolling);
      isScrolling = setTimeout(function () {
        body.classList.remove("scrolling");
      }, 66);
    },
    false
  );

  // Inline Popups
  for (let i = 0; i < menuList.length; i++) {
    menuList[i].addEventListener("click", function (e) {
      e.stopPropagation();
      menuList[i].classList.toggle("active");
    });
  }

  // Popups
  const modal = document.querySelector(".bg-modal");
  const buttonClose = document.querySelector(".popup-close");
  const buttonsOpen = document.querySelectorAll(".show-bg-modal");

  // const openModal = function (item) {
  //   modal.classList.remove("hidden");
  //   html.classList.add("popup-active");
  // };
  for (let i = 0; i < buttonsOpen.length; i++) {
    let popupItem = buttonsOpen[i].getAttribute("data-popup");
    buttonsOpen[i].addEventListener("click", function (e) {
      if (!modal.classList.contains("hidden")) {
        closeModal();
      }

      e.preventDefault();
      modal.classList.remove("hidden");
      html.classList.add("popup-active");
      openItem = document.querySelector(`#${popupItem}`);
      openItem.classList.remove("d-none");
    });
  }

  const closeModal = function () {
    modal.classList.add("hidden");
    html.classList.remove("popup-active");
    openItem.classList.add("d-none");
  };

  buttonClose.addEventListener("click", closeModal);
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && !modal.classList.contains("hidden")) closeModal();
  });

  if (interactiveLists && moreLessBtn) {
    for (let i = 0; i < interactiveLists.length; i++) {
      moreLessBtn[i]?.addEventListener("click", function () {
        interactiveLists[i].classList.toggle("active");
      });
    }
  }

  if (sortList) {
    for (let i = 0; i < sortList.length; i++) {
      sortList[i].addEventListener("click", function () {
        if (sortList[i].classList.contains("ascend")) {
          sortList[i].classList.remove("ascend");
          sortList[i].classList.add("descend");
        } else if (sortList[i].classList.contains("descend")) {
          sortList[i].classList.add("ascend");
          sortList[i].classList.remove("descend");
        }
      });
    }
  }

  // Listing Loader
  let listLoader = document.querySelector(".intereactive-lists-loader");
  let noLoader = document.querySelector(".no-loader");
  if (listLoader) {
    setTimeout(function () {
      listLoader.remove();
      noLoader.classList.remove("d-none");
    }, 3000);
  }

  // BG Slider Images
  if (bgSliderImg.length > 1) {
    let id = 1;
    setInterval(function () {
      for (let i = 0; i < bgSliderImg.length; i++) {
        if (bgSliderImg[i].classList.contains("active")) {
          bgSliderImg[i].classList.remove("active");
        }
      }
      bgSliderImg[id].classList.add("active");
      id++;
      if (id === bgSliderImg.length) {
        id = 0;
      }
    }, 3000);
  }

  // Gallery Light Box
  class bgLightGallery {
    constructor(settings) {
      this.settings = {
        images: ".gallery__Image",
        loop: true,
        next: undefined,
        prev: undefined,
        dots: undefined,
        close: undefined,
        loader: undefined,
        counter: undefined,
        counterDivider: "/",
        keyboardNavigation: true,
        hiddenElements: [],
      };

      Object.assign(this.settings, settings);

      this.gallery = null;
      this.index = 0;
      this.items = [...document.querySelectorAll(this.settings.images)];

      this.addedItems = {};

      this.touch = {
        endX: 0,
        startX: 0,
      };

      this.init();
    }

    get loading() {
      return !this.settings.hiddenElements.includes("loader");
    }

    get dotsVisible() {
      return !this.settings.hiddenElements.includes("dots");
    }

    init() {
      this.clearUncomplete();
      this.createElements();
      this.bindEvents();
    }

    clearUncomplete() {
      this.items = this.items.filter((item) => {
        return item.dataset.large;
      });
    }

    createElements() {
      this.gallery = document.createElement("DIV");
      this.gallery.classList.add("bgLightGallery");

      this.createSingleElement({
        element: "prev",
        type: "BUTTON",
        event: "click",
        func: this.getPrevious,
      });

      this.createSingleElement({
        element: "next",
        type: "BUTTON",
        event: "click",
        func: this.getNext,
      });

      this.createSingleElement({
        element: "close",
        type: "BUTTON",
        event: "click",
        func: this.closeGallery,
      });

      this.createSingleElement({
        element: "loader",
        type: "SPAN",
        text: "Loading...",
      });

      this.createSingleElement({
        element: "counter",
        type: "SPAN",
        text: "0/0",
      });

      this.createSingleElement({
        element: "dots",
        type: "UL",
        text: "",
      });

      if (!this.settings.hiddenElements.includes("dots")) {
        this.items.forEach((item, i) => {
          let dot = document.createElement("LI");
          dot.dataset.index = i;
          let button = document.createElement("BUTTON");
          button.innerHTML = i;
          button.addEventListener("click", () => {
            this.index = i;
            this.getItem(i);
          });

          dot.append(button);
          this.dots.append(dot);
        });
      }

      window.document.body.append(this.gallery);
    }

    createSingleElement({ element, type, event = "click", func, text }) {
      if (!this.settings.hiddenElements.includes(element)) {
        if (!this.settings[element]) {
          this[element] = document.createElement(type);
          this[element].classList.add(
            `bgLightGallery__${this.capitalizeFirstLetter(element)}`
          );
          this[element].innerHTML = text !== undefined ? text : element;
          this.gallery.append(this[element]);
        } else {
          this[element] = document.querySelector(this.settings[element]);
          this.gallery.append(this[element]);
        }

        if (func) {
          this[element].addEventListener(event, func.bind(this));
        }
      }
    }

    getItem(i, content = null) {
      let contentObj = content;
      if (contentObj === null) {
        contentObj = {};
        contentObj.src = this.items[i].dataset.large;
        contentObj.description = this.items[i].dataset.description;
      }

      if (!this.settings.hiddenElements.includes("counter")) {
        this.counter.innerHTML = `
          <span class="bgLightGallery__Current">${this.index + 1}</span>${
          this.settings.counterDivider
        }<span class="bgLightGallery__Current">${this.items.length}</span>
          `;
      }

      if (!this.addedItems.hasOwnProperty(i)) {
        let image = document.createElement("IMG");

        let galleryItem = document.createElement("DIV");
        galleryItem.classList.add("bgLightGallery__Item");

        if (this.loading) {
          this.loader.classList.add("is-visible");
        }

        this.clearVisible();

        if (this.dotsVisible) {
          this.gallery
            .querySelector(`.bgLightGallery__Dots li[data-index="${i}"]`)
            .classList.add("is-active");
        }

        image.src = contentObj.src;
        image.alt = contentObj.description ? contentObj.description : "";

        galleryItem.innerHTML = `
          <div class="bgLightGallery__ItemImage">
            ${image.outerHTML}
          </div>
          `;

        if (contentObj.description) {
          galleryItem.innerHTML += `
            <div class="bgLightGallery__ItemDescription">
              <p>${contentObj.description}</p>
            </div>
            `;
        }

        this.gallery.append(galleryItem);
        this.addedItems[i] = galleryItem;

        image.addEventListener("load", () => {
          this.addedItems[i].loaded = true;
          if (!this.gallery.querySelector(".bgLightGallery__Item.is-visible")) {
            this.addedItems[i].classList.add("is-visible");
          }

          if (this.loading) {
            this.loader.classList.remove("is-visible");
          }
        });
      } else {
        this.clearVisible();
        if (this.addedItems[this.index].loaded) {
          this.addedItems[this.index].classList.add("is-visible");
          if (this.loading) {
            this.loader.classList.remove("is-visible");
          }
        } else if (this.loading) {
          this.loader.classList.add("is-visible");
        }

        if (this.dotsVisible) {
          this.gallery
            .querySelector(`.bgLightGallery__Dots li[data-index="${i}"]`)
            .classList.add("is-active");
        }
      }

      if (!this.settings.loop) {
        if (this.index === 0) this.prev.setAttribute("disabled", true);
        else this.prev.removeAttribute("disabled");

        if (this.index === this.items.length - 1)
          this.next.setAttribute("disabled", true);
        else this.next.removeAttribute("disabled");
      }
    }

    clearVisible() {
      if (this.gallery.querySelector(".bgLightGallery__Item.is-visible")) {
        this.gallery
          .querySelector(".bgLightGallery__Item.is-visible")
          .classList.remove("is-visible");
      }

      if (this.gallery.querySelector(".bgLightGallery__Dots li.is-active")) {
        this.gallery
          .querySelector(".bgLightGallery__Dots li.is-active")
          .classList.remove("is-active");
      }
    }

    closeGallery() {
      this.gallery.classList.remove("is-visible");
      this.clearVisible();
    }

    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }

    handleGesure() {
      if (this.touch.endX > this.touch.startX + 20) {
        this.getPrevious();
      } else if (this.touch.endX < this.touch.startX - 20) {
        this.getNext();
      }
    }

    getPrevious() {
      if (this.settings.loop) {
        this.index--;
        if (this.index === -1) {
          this.index = this.items.length - 1;
        }
        this.getItem(this.index);
      } else if (this.index > 0) {
        this.index--;
        this.getItem(this.index);
      }
    }

    getNext() {
      if (this.settings.loop) {
        this.index++;
        if (this.index === this.items.length) {
          this.index = 0;
        }
        this.getItem(this.index);
      } else if (this.index < this.items.length - 1) {
        this.index++;
        this.getItem(this.index);
      }
    }

    bindEvents() {
      this.items.forEach((item, i) => {
        item.addEventListener("click", (e) => {
          this.gallery.classList.add("is-visible");
          this.index = i;
          this.getItem(i, {
            src: e.target.dataset.large,
            description: e.target.dataset.description,
          });
        });
      });

      document.addEventListener("keyup", (e) => {
        if (this.gallery.classList.contains("is-visible")) {
          if (e.key === "Escape") this.closeGallery();
          if (this.settings.keyboardNavigation) {
            if (e.keyCode === 39) this.getNext();
            else if (e.keyCode === 37) this.getPrevious();
          }
        }
      });

      this.gallery.addEventListener(
        "touchstart",
        (e) => {
          this.touch.startX = e.changedTouches[0].screenX;
        },
        false
      );

      this.gallery.addEventListener(
        "touchend",
        (e) => {
          this.touch.endX = e.changedTouches[0].screenX;
          this.handleGesure();
        },
        false
      );
    }
  }

  new bgLightGallery();
});
