window.addEventListener("scroll", () => {
  if (window.scrollY > 100) {
    document.getElementById("navigation").classList.add("o-surface-1");
  } else {
    document.getElementById("navigation").classList.remove("o-surface-1");
  }
});
