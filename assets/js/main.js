document.addEventListener('DOMContentLoaded', function () {
  gsap.registerPlugin(ScrollTrigger)
  let allProjects = []
  let itemsPerPage = 4
  let currentIndex = 0

  const grid = document.getElementById('projetos-grid')
  const loadMoreBtn = document.getElementById('load-more-btn')

  fetch('projetos.json')
    .then((response) => response.json())
    .then((data) => {
      allProjects = data
      renderProjects()
    })
    .catch((error) => console.error('Erro ao carregar os projetos:', error))

  function renderProjects() {
    const nextIndex = currentIndex + itemsPerPage
    const slice = allProjects.slice(currentIndex, nextIndex)

    slice.forEach((project, index) => {
      const article = document.createElement('article')
      article.innerHTML = `
          <img src="${project.imagem}" alt="Thumbnail ${project.titulo}">
          <div class="content">
              <div><span>${project.categoria}</span></div>
              <div>
                  <h3>${project.titulo}</h3>
                  <p>${project.descricao}</p>
              </div>
              <a href="${project.link}" target="_blank">Ver projeto</a>
          </div>
      `
      grid.appendChild(article)

      // Animação individual com GSAP e ScrollTrigger
      gsap.from(article, {
        scrollTrigger: {
          trigger: article,
          start: 'top 85%',
          toggleActions: 'play none none none',
        },
        opacity: 0,
        y: 40,
        duration: 0.6,
        delay: index * 0.1,
        ease: 'power2.out',
      })
    })

    currentIndex = nextIndex

    if (currentIndex >= allProjects.length) {
      loadMoreBtn.style.display = 'none'
    }
  }

  loadMoreBtn.addEventListener('click', function (e) {
    e.preventDefault()
    loadMoreBtn.textContent = 'Carregando...'
    loadMoreBtn.disabled = true

    setTimeout(() => {
      renderProjects()
      if (currentIndex < allProjects.length) {
        loadMoreBtn.textContent = 'Ver mais projetos'
        loadMoreBtn.disabled = false
      }
    }, 500)
  })

  const header = document.querySelector('header')

  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      header.classList.add('scrolled')
    } else {
      header.classList.remove('scrolled')
    }
  })
  const form = document.querySelector('form')
  const modal = document.querySelector('#modal-sucesso')
  const fecharModal = document.querySelector('#fechar-modal')

  fecharModal.addEventListener('click', () => {
    modal.classList.remove('ativo')
  })

  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.classList.remove('ativo')
    }
  })

  function showError(inputElement, message) {
    const wrapper =
      inputElement.closest('.custom-select-wrapper') ||
      inputElement.parentElement

    inputElement.classList.add('error')
    if (wrapper.classList.contains('custom-select-wrapper')) {
      wrapper.classList.add('error')
    }

    let errorSpan = wrapper.querySelector('.error-message')
    if (!errorSpan) {
      errorSpan = document.createElement('span')
      errorSpan.className = 'error-message'
      wrapper.appendChild(errorSpan)
    }
    errorSpan.textContent = message
  }

  function clearError(inputElement) {
    const wrapper =
      inputElement.closest('.custom-select-wrapper') ||
      inputElement.parentElement

    inputElement.classList.remove('error')
    if (wrapper.classList.contains('custom-select-wrapper')) {
      wrapper.classList.remove('error')
    }

    const errorSpan = wrapper.querySelector('.error-message')
    if (errorSpan) {
      errorSpan.remove()
    }
  }

  const nome = document.querySelector('#nome')
  const email = document.querySelector('#email')
  const tipoProjeto = document.querySelector('#tipo_projeto')
  const prazo = document.querySelector('#prazo_desejado')
  const mensagem = document.querySelector('#mensagem')

  nome.addEventListener('input', () => {
    if (nome.value.trim()) clearError(nome)
  })

  email.addEventListener('input', () => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (emailRegex.test(email.value.trim())) clearError(email)
  })

  mensagem.addEventListener('input', () => {
    if (mensagem.value.trim()) clearError(mensagem)
  })

  form.addEventListener('submit', async (e) => {
    e.preventDefault()
    let isValid = true

    if (!nome.value.trim()) {
      showError(nome, 'Por favor, insira seu nome.')
      isValid = false
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!email.value.trim()) {
      showError(email, 'O campo e-mail é obrigatório.')
      isValid = false
    } else if (!emailRegex.test(email.value.trim())) {
      showError(email, 'Por favor, insira um e-mail válido.')
      isValid = false
    }

    if (!tipoProjeto.value) {
      showError(tipoProjeto, 'Selecione o tipo de projeto.')
      isValid = false
    }

    if (!prazo.value) {
      showError(prazo, 'Selecione o prazo desejado.')
      isValid = false
    }

    if (!mensagem.value.trim()) {
      showError(mensagem, 'Escreva uma breve mensagem sobre o projeto.')
      isValid = false
    }

    if (!isValid) return

    const submitBtn = form.querySelector('button[type="submit"]')
    const originalText = submitBtn ? submitBtn.textContent : 'Enviar'
    if (submitBtn) {
      submitBtn.textContent = 'Enviando...'
      submitBtn.disabled = true
      submitBtn.style.opacity = '0.7'
    }

    const formData = new FormData(form)

    try {
      const response = await fetch('envia.php', {
        method: 'POST',
        body: formData,
      })

      if (response.ok) {
        modal.classList.add('ativo')
        form.reset()

        document
          .querySelectorAll('.custom-select-wrapper')
          .forEach((wrapper) => {
            const span = wrapper.querySelector('.select-trigger span')
            span.textContent = 'Selecione'
            span.style.color = '#1f2937'
          })
      } else {
        alert('Ocorreu um erro ao enviar. Tente novamente.')
      }
    } catch (error) {
      alert('Erro de conexão com o servidor.')
    }
  })

  document.querySelectorAll('.custom-option').forEach((option) => {
    option.addEventListener('click', () => {
      const wrapper = option.closest('.custom-select-wrapper')
      const hiddenInput = wrapper.querySelector('input[type="hidden"]')
      if (hiddenInput.value) {
        clearError(hiddenInput)
      }
    })
  })
  document.querySelectorAll('.custom-select-wrapper').forEach((wrapper) => {
    const trigger = wrapper.querySelector('.select-trigger')
    const options = wrapper.querySelectorAll('.custom-option')
    const hiddenInput = wrapper.querySelector('input[type="hidden"]')
    const triggerText = trigger.querySelector('span')

    trigger.addEventListener('click', (e) => {
      e.stopPropagation()
      document.querySelectorAll('.custom-select-wrapper').forEach((w) => {
        if (w !== wrapper) w.classList.remove('open')
      })
      wrapper.classList.toggle('open')
    })

    options.forEach((option) => {
      option.addEventListener('click', () => {
        triggerText.textContent = option.textContent
        triggerText.style.color = '#1f2937'
        hiddenInput.value = option.getAttribute('data-value')
        wrapper.classList.remove('open')
      })
    })
  })

  window.addEventListener('click', () => {
    document.querySelectorAll('.custom-select-wrapper').forEach((wrapper) => {
      wrapper.classList.remove('open')
    })
  })

  document.querySelectorAll('.select-trigger').forEach((trigger) => {
    trigger.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault()
        trigger.closest('.custom-select-wrapper').classList.toggle('open')
      }
    })
  })
  const menuToggle = document.querySelector('.menu-toggle')
  const nav = document.querySelector('header nav')
  const menuOverlay = document.querySelector('.menu-overlay')
  const menuIcon = menuToggle.querySelector('i')

  function toggleMenu() {
    nav.classList.toggle('active')
    menuOverlay.classList.toggle('active')

    const isOpen = nav.classList.contains('active')
    menuIcon.classList.toggle('fa-bars', !isOpen)
    menuIcon.classList.toggle('fa-xmark', isOpen)
    menuToggle.setAttribute('aria-expanded', isOpen)
  }

  menuToggle.addEventListener('click', toggleMenu)
  menuOverlay.addEventListener('click', toggleMenu)

  document.querySelectorAll('header nav a').forEach((link) => {
    link.addEventListener('click', () => {
      nav.classList.remove('active')
      menuOverlay.classList.remove('active')
      menuIcon.classList.remove('fa-xmark')
      menuIcon.classList.add('fa-bars')
      menuToggle.setAttribute('aria-expanded', 'false')
    })
  })

  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger)

    // Animação de entrada do Hero
    const heroTimeline = gsap.timeline({ defaults: { ease: 'power3.out' } })
    heroTimeline
      .from(
        '#hero .hero-image-wrapper',
        { opacity: 0, y: 30, duration: 1 },
        '+=0.2',
      )
      .from('#hero h1', { opacity: 0, y: 30, duration: 0.8 }, '-=0.6')
      .from('#hero p', { opacity: 0, y: 20, duration: 0.8 }, '-=0.4')
      .from(
        '#hero ul li',
        { opacity: 0, y: 15, duration: 0.5, stagger: 0.1 },
        '-=0.4',
      )
      .from('#hero .btns', { opacity: 0, y: 20, duration: 0.6 }, '-=0.3')

    gsap.from('.projetos article', {
      scrollTrigger: { trigger: '.projetos', start: 'top 75%' },
      opacity: 0,
      y: 50,
      duration: 0.6,
      stagger: 0.15,
      ease: 'power2.out',
    })
  }
})
