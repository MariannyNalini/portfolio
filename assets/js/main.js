document.addEventListener('DOMContentLoaded', function () {
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
    const currentLang = document.body.getAttribute('data-lang') || 'pt'

    const nextIndex = currentIndex + itemsPerPage
    const slice = allProjects.slice(currentIndex, nextIndex)

    slice.forEach((project, index) => {
      const article = document.createElement('article')
      const categoria = project[`categoria_${currentLang}`]
      const titulo = project[`titulo_${currentLang}`]
      const descricao = project[`descricao_${currentLang}`]
      const btnTexto = currentLang === 'pt' ? 'Ver projeto' : 'View project'

      const altText =
        currentLang === 'pt'
          ? `Prévia do projeto ${titulo}`
          : `Preview of the ${titulo} project`

      article.innerHTML = `
          <img src="${project.imagem}" alt="${altText}" loading="lazy" decoding="async">
          <div class="content">
              <div><span>${categoria}</span></div>
              <div>
                  <h3>${titulo}</h3>
                  <p>${descricao}</p>
              </div>
              <a href="${project.link}" target="_blank">${btnTexto}</a>
          </div>
      `
      grid.appendChild(article)
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
  const layout = document.querySelector('#possui_layout')
  const contrato = document.querySelector('#tipo_contrato')
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

    if (!layout.value) {
      showError(prazo, 'Selecione a opção.')
      isValid = false
    }

    if (!contrato.value) {
      showError(prazo, 'Selecione a opção.')
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
      submitBtn.textContent = 'Enviando mensagem...'
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
    } finally {
      if (submitBtn) {
        submitBtn.textContent = originalText
        submitBtn.disabled = false
        submitBtn.style.opacity = '1'
      }
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
  const navContent = document.querySelector('.nav-content')
  const menuOverlay = document.querySelector('.menu-overlay')

  function toggleMenu() {
    nav.classList.toggle('active')
    if (navContent) navContent.classList.toggle('active')
    if (menuOverlay) menuOverlay.classList.toggle('active')

    const isOpen = nav.classList.contains('active')
    menuToggle.setAttribute('aria-expanded', isOpen)
  }

  if (menuToggle) {
    menuToggle.addEventListener('click', toggleMenu)
  }

  if (menuOverlay) {
    menuOverlay.addEventListener('click', toggleMenu)
  }

  document.querySelectorAll('header nav a').forEach((link) => {
    link.addEventListener('click', () => {
      nav.classList.remove('active')
      if (navContent) navContent.classList.remove('active')
      if (menuOverlay) menuOverlay.classList.remove('active')
      menuToggle.setAttribute('aria-expanded', 'false')
    })
  })
})
