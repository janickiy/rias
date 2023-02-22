/* Menu */
const $menu = document.querySelector('.menu');
if ($menu) {
  const $menuOpen = $menu.querySelector('.menu__open');
  $menuOpen.addEventListener('click', () => {
    $menu.classList.add('menu--active');
    document.body.classList.add('body--lock');
  });

  const $menuClose = $menu.querySelector('.menu__close');
  $menuClose.addEventListener('click', () => {
    $menu.classList.remove('menu--active');
    document.body.classList.remove('body--lock');
  });
}

/* Dropdown */
const $dropdowns = document.querySelectorAll('.dropdown');
$dropdowns.forEach($filter => {
  const $btn = $filter.querySelector('.dropdown__btn');
  $btn.addEventListener('click', () => $filter.classList.toggle('dropdown--active'));
});

window.addEventListener('click', (e) => {
  const $activeDropdown = document.querySelector('.dropdown--active');
  const isInner = e.target.closest('.dropdown') && !e.target.classList.contains('dropdown');
  if (!$activeDropdown || isInner) {
    return;
  }

  $activeDropdown.classList.remove('dropdown--active');
});

/* Header */
const $header = document.querySelector('.header');
if ($header) {
  window.addEventListener('scroll', () => {
    const height = $header.offsetHeight + 20;
    if (window.scrollY > height && !$header.classList.contains('header--fixed')) {
      $header.classList.add('header--fixed');
    } else if (window.scrollY <= height && $header.classList.contains('header--fixed')) {
      $header.classList.remove('header--fixed');
    }
  });
}