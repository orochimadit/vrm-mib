const btn = document.querySelector('.btn-nav-mobile')

const body = document.querySelector('body')
btn.addEventListener('click', () => {
    body?.classList.toggle('show-nav-mobile')
})